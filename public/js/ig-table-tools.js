/**
 * ig-table-tools.js
 *
 * Purely client-side, opt-in table enhancement:
 *   - click-to-sort column headers (asc / desc / default) with a visual indicator
 *   - a single per-table "search this table" box — moved into the panel's own
 *     upper action-icon toolbar when there is one (filter/refresh/delete/+New),
 *     otherwise placed on its own row right above the table
 *
 * Usage: add `data-ig-tabletools` to any <table> that already has real <thead><tr><th>
 * headers and data rows in <tbody>. Everything else (search box, sort indicators,
 * wiring) is generated automatically at runtime. No markup restructuring needed,
 * and nothing here touches routes/AJAX/pagination — it only sorts/filters the
 * rows that are already present in the DOM (i.e. the current page of results).
 *
 * Rows excluded from sorting/filtering (left untouched, always visible):
 *   - rows with the "hide" class (e.g. collapsed DM/accordion data rows)
 *   - rows with the "ig-empty-row" class (existing "no data" placeholder)
 *   - any row added dynamically by this script itself (ig-no-match-row)
 */
(function () {
    'use strict';

    var SKIP_HEADER_RE = /^(action|actions|edit|delete|save|options|select|s\.no\.?|sr no\.?)$/i;
    var DATE_RE = /^\d{4}-\d{1,2}-\d{1,2}([ T]\d{1,2}:\d{2}(:\d{2})?)?$|^\d{1,2}[-\/]\d{1,2}[-\/]\d{2,4}(,?\s*\d{1,2}:\d{2}\s*(AM|PM)?)?$/i;
    var NUMERIC_RE = /^-?\d+(\.\d+)?$/;

    function textOf(el) {
        return (el && el.textContent ? el.textContent : '').replace(/\s+/g, ' ').trim();
    }

    function parseDateLoose(str) {
        if (!str) return NaN;
        var t = Date.parse(str);
        if (!isNaN(t)) return t;
        var m = str.match(/^(\d{1,2})[-\/](\d{1,2})[-\/](\d{2,4})/);
        if (m) {
            var d = parseInt(m[1], 10), mo = parseInt(m[2], 10) - 1, y = parseInt(m[3], 10);
            if (y < 100) y += 2000;
            var dt = new Date(y, mo, d);
            if (!isNaN(dt.getTime())) return dt.getTime();
        }
        return NaN;
    }

    function dataRows(tbody) {
        var rows = [];
        for (var i = 0; i < tbody.children.length; i++) {
            var tr = tbody.children[i];
            if (tr.tagName !== 'TR') continue;
            if (tr.classList.contains('hide')) continue;
            if (tr.classList.contains('ig-empty-row')) continue;
            if (tr.classList.contains('ig-no-match-row')) continue;
            rows.push(tr);
        }
        return rows;
    }

    function cellsOf(tr) {
        var out = [];
        for (var i = 0; i < tr.children.length; i++) {
            if (tr.children[i].tagName === 'TD' || tr.children[i].tagName === 'TH') {
                out.push(tr.children[i]);
            }
        }
        return out;
    }

    function classify(colIndex, headerText, rows) {
        if (SKIP_HEADER_RE.test(headerText)) return null;

        var values = [];
        var hasCheckbox = false;
        for (var i = 0; i < rows.length; i++) {
            var cells = cellsOf(rows[i]);
            var cell = cells[colIndex];
            if (!cell) continue;
            if (cell.querySelector('input[type="checkbox"]')) hasCheckbox = true;
            var v = textOf(cell);
            values.push(v);
        }
        var nonEmpty = values.filter(function (v) { return v !== ''; });

        if (headerText === '' && hasCheckbox) return null;
        if (nonEmpty.length === 0) return null;

        var dateCount = 0, numCount = 0;
        nonEmpty.forEach(function (v) {
            if (DATE_RE.test(v)) dateCount++;
            if (NUMERIC_RE.test(v)) numCount++;
        });

        var distinct = {};
        nonEmpty.forEach(function (v) { distinct[v] = true; });
        var distinctVals = Object.keys(distinct).sort();

        if (dateCount / nonEmpty.length >= 0.7) {
            return { type: 'date', sortable: true };
        }
        if (numCount / nonEmpty.length >= 0.9) {
            return { type: 'numeric', sortable: true };
        }
        if (distinctVals.length > 1 && distinctVals.length <= 8 && distinctVals.length < nonEmpty.length) {
            return { type: 'select', sortable: true, options: distinctVals };
        }
        return { type: 'text', sortable: true };
    }

    // ------------------------------------------------------------------
    // Column widths — proportional to content, not an even split.
    // table-layout:fixed (see the "Table responsiveness" block in
    // style.css) takes column widths from this header row and never lets
    // a column grow past that share again — that's what stops the table
    // from overflowing sideways. Left with no explicit widths, a fixed
    // table just splits evenly across however many columns are visible:
    // technically overflow-free, but it makes a short column (Status,
    // Action) exactly as wide as a long one (Post Message, Subject),
    // which looks unbalanced and forces the long column's text to wrap
    // constantly. This estimates each column's fair share from its own
    // header label and a sample of its actual cell text, so a table whose
    // visible columns change (see the "Choose Columns" modal) still gets
    // sensible, non-overflowing proportions without per-page/per-column
    // CSS that would go stale the moment columns are hidden or reordered.
    // ------------------------------------------------------------------
    var MIN_COL_WEIGHT = 14;   // floor, in "characters" — keeps a short column
                                // (Num., Status, Action, Department) wide
                                // enough to fit its own header label on one
                                // (or close to one) line, plus its sort icon
    var MAX_COL_WEIGHT = 40;   // ceiling — a long free-text column (Post
                                // Message) already wraps onto multiple lines,
                                // so it shouldn't claim width proportional to
                                // its full paragraph length, only to a
                                // "line" of it — capped low enough that it
                                // can't crowd every short column down to
                                // its bare floor at typical column counts
    var WIDTH_SAMPLE_ROWS = 30;

    function applyContentBasedWidths(ths, rows) {
        var weights = ths.map(function (th, idx) {
            var headerLen = textOf(th).length;
            var maxCellLen = 0;
            var sampleCount = Math.min(rows.length, WIDTH_SAMPLE_ROWS);
            for (var r = 0; r < sampleCount; r++) {
                var cell = cellsOf(rows[r])[idx];
                if (!cell) continue;
                var len = textOf(cell).length;
                if (len > maxCellLen) maxCellLen = len;
            }
            var effective = Math.max(headerLen, maxCellLen);
            return Math.min(Math.max(effective, MIN_COL_WEIGHT), MAX_COL_WEIGHT);
        });
        var total = weights.reduce(function (a, b) { return a + b; }, 0);
        if (!total) return;
        ths.forEach(function (th, idx) {
            th.style.width = ((weights[idx] / total) * 100).toFixed(2) + '%';
        });
    }

    function buildSortIcon() {
        var span = document.createElement('span');
        span.className = 'ig-sort-icon';
        span.innerHTML = '<i class="bi bi-arrow-down-up"></i>';
        return span;
    }

    function setSortIconState(th, state) {
        var icon = th.querySelector('.ig-sort-icon i');
        if (!icon) return;
        icon.className = state === 'asc' ? 'bi bi-sort-up-alt'
            : state === 'desc' ? 'bi bi-sort-down'
            : 'bi bi-arrow-down-up';
        th.setAttribute('aria-sort', state === 'asc' ? 'ascending' : state === 'desc' ? 'descending' : 'none');
        th.classList.toggle('ig-th-sorted', state !== 'none');
    }

    function initTable(table) {
        if (table.__igInit) return;
        table.__igInit = true;

        var thead = table.querySelector('thead');
        var tbody = table.querySelector('tbody');
        if (!thead || !tbody) return;
        var headerRow = thead.querySelector('tr');
        if (!headerRow) return;

        var ths = [];
        for (var i = 0; i < headerRow.children.length; i++) {
            if (headerRow.children[i].tagName === 'TH' || headerRow.children[i].tagName === 'TD') {
                ths.push(headerRow.children[i]);
            }
        }

        var rows = dataRows(tbody);
        var colMeta = ths.map(function (th, idx) {
            return classify(idx, textOf(th), rows);
        });

        applyContentBasedWidths(ths, rows);

        // --- sort wiring -------------------------------------------------
        var currentSort = { index: -1, dir: 'none' };

        function applySort(index, dir) {
            var meta = colMeta[index];
            if (!meta) return;
            var liveRows = dataRows(tbody);
            var withKey = liveRows.map(function (tr) {
                var cell = cellsOf(tr)[index];
                var raw = textOf(cell);
                var key;
                if (meta.type === 'numeric') {
                    key = parseFloat(raw);
                    if (isNaN(key)) key = -Infinity;
                } else if (meta.type === 'date') {
                    key = parseDateLoose(raw);
                    if (isNaN(key)) key = -Infinity;
                } else {
                    key = raw.toLowerCase();
                }
                return { tr: tr, key: key };
            });
            withKey.sort(function (a, b) {
                if (a.key < b.key) return dir === 'asc' ? -1 : 1;
                if (a.key > b.key) return dir === 'asc' ? 1 : -1;
                return 0;
            });
            withKey.forEach(function (item) { tbody.appendChild(item.tr); });
        }

        ths.forEach(function (th, index) {
            var meta = colMeta[index];
            if (!meta || !meta.sortable) return;
            th.classList.add('ig-th-sort');
            th.setAttribute('tabindex', '0');
            th.setAttribute('role', 'button');
            th.setAttribute('aria-sort', 'none');

            // IMPORTANT: the <th> itself must stay a normal table-cell so its
            // width stays in sync with the filter row and data cells below it.
            // Flex layout (for spacing the label from the sort icon) goes on an
            // inner wrapper instead of the <th>, never on the cell itself.
            var labelWrap = document.createElement('span');
            labelWrap.className = 'ig-th-content';
            while (th.firstChild) { labelWrap.appendChild(th.firstChild); }
            labelWrap.appendChild(buildSortIcon());
            th.appendChild(labelWrap);

            function cycle() {
                var nextDir;
                if (currentSort.index !== index || currentSort.dir === 'none') {
                    nextDir = 'asc';
                } else if (currentSort.dir === 'asc') {
                    nextDir = 'desc';
                } else {
                    nextDir = 'none';
                }

                if (currentSort.index !== -1 && currentSort.index !== index) {
                    setSortIconState(ths[currentSort.index], 'none');
                }

                currentSort = { index: index, dir: nextDir };
                setSortIconState(th, nextDir);

                if (nextDir === 'none') {
                    table.__igOriginalOrder.forEach(function (tr) {
                        if (tr.parentNode === tbody) tbody.appendChild(tr);
                    });
                } else {
                    applySort(index, nextDir);
                }
                reapplyFilters();
            }

            th.addEventListener('click', cycle);
            th.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); cycle(); }
            });
        });

        table.__igOriginalOrder = dataRows(tbody).slice();

        // --- global per-table search -----------------------------------
        // (Per-column Min/Max/Search filter boxes used to be auto-generated
        // here as an extra <thead> row — removed per design feedback: they
        // duplicated the single search box below, cluttered the header, and
        // their own min-widths were part of what made wide tables crowd
        // their columns. The table now relies solely on this one search box
        // plus the sortable headers above.)
        var searchWrap = document.createElement('div');
        searchWrap.className = 'ig-table-search';
        var searchIcon = document.createElement('i');
        searchIcon.className = 'bi bi-search';
        var searchInput = document.createElement('input');
        searchInput.type = 'search';
        searchInput.className = 'form-control form-control-sm';
        searchInput.placeholder = 'Search this table…';
        searchInput.setAttribute('aria-label', 'Search this table');
        searchInput.addEventListener('input', reapplyFilters);
        searchWrap.appendChild(searchIcon);
        searchWrap.appendChild(searchInput);

        // Prefer dropping the search box into the panel's own upper
        // action-icon bar (filter/refresh/delete/+New, etc.) so it reads as
        // one of the table's main controls instead of a second, separate
        // search row floating above the table. Pages that don't have that
        // toolbar (yet) keep the previous placement — search box on its own
        // row, right above the table — so nothing regresses there.
        var panel = table.closest('.ig-panel, .bgwhite2, .busines_details') || table.parentElement;
        var actionsBar = panel ? panel.querySelector('.ig-toolbar-actions') : null;
        if (actionsBar) {
            searchWrap.classList.add('ig-table-search-inline');
            actionsBar.insertBefore(searchWrap, actionsBar.firstChild);
        } else {
            var toolbar = document.createElement('div');
            toolbar.className = 'ig-table-search-wrap mb-2';
            toolbar.appendChild(searchWrap);
            var anchor = table.closest('.table-responsive') || table;
            anchor.parentNode.insertBefore(toolbar, anchor);
        }

        var noMatchRow = null;
        function ensureNoMatchRow(colCount) {
            if (!noMatchRow) {
                noMatchRow = document.createElement('tr');
                noMatchRow.className = 'ig-no-match-row';
                var td = document.createElement('td');
                td.colSpan = colCount;
                td.className = 'text-center text-muted py-4';
                td.textContent = 'No matching rows.';
                noMatchRow.appendChild(td);
            }
            return noMatchRow;
        }

        function reapplyFilters() {
            var globalTerm = searchInput.value.trim().toLowerCase();
            var rows = dataRows(tbody);
            var visibleCount = 0;
            rows.forEach(function (tr) {
                var rowText = textOf(tr).toLowerCase();
                var visible = globalTerm === '' || rowText.indexOf(globalTerm) !== -1;
                tr.classList.toggle('ig-row-hidden', !visible);
                tr.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            var nmRow = ensureNoMatchRow(ths.length);
            if (visibleCount === 0 && rows.length > 0) {
                if (!nmRow.parentNode) tbody.appendChild(nmRow);
            } else if (nmRow.parentNode) {
                nmRow.parentNode.removeChild(nmRow);
            }
        }
    }

    function initAll() {
        var tables = document.querySelectorAll('table[data-ig-tabletools]');
        for (var i = 0; i < tables.length; i++) initTable(tables[i]);
    }

    // ------------------------------------------------------------------
    // Responsive card mode — runs on every .ig-table (not just the ones
    // opted into sort/filter/search above), since every table needs to be
    // usable below 768px, where style.css turns each row into a labelled
    // card instead of letting it scroll sideways.
    //
    //   1. Copies each column's own header text onto that column's <td> as
    //      data-label, which the card-mode CSS reads via ::before —
    //      generated once here rather than hand-written per template, so
    //      it can never drift out of sync with a header that gets renamed.
    //   2. For rows with more than 7 columns, marks the extra middle ones
    //      .ig-card-extra (collapsed by default in card mode only) and adds
    //      a "Show N more" toggle, so a 12-column ticket row doesn't turn
    //      into a wall of text on a phone. The first column (identity/
    //      selection) and the last (almost always Actions) are never
    //      collapsed. Desktop/tablet table view is completely unaffected —
    //      .ig-card-extra has no styling outside the <768px breakpoint.
    // ------------------------------------------------------------------
    var CARD_VISIBLE_COUNT = 6;

    function applyResponsiveCards(table) {
        if (table.__igCardInit) return;
        table.__igCardInit = true;

        var thead = table.querySelector('thead');
        var tbody = table.querySelector('tbody');
        if (!thead || !tbody) return;
        var headerRow = thead.querySelector('tr');
        if (!headerRow) return;

        var headerCells = [];
        for (var i = 0; i < headerRow.children.length; i++) {
            var c = headerRow.children[i];
            if (c.tagName === 'TH' || c.tagName === 'TD') headerCells.push(c);
        }
        var labels = headerCells.map(function (th) { return textOf(th); });

        dataRows(tbody).forEach(function (tr) {
            var cells = cellsOf(tr);
            // A single colspan'd cell is a placeholder (empty state / "no
            // matching rows"), not real column data — leave it alone.
            if (cells.length === 1 && cells[0].hasAttribute('colspan')) return;

            var extraCells = [];
            cells.forEach(function (cell, idx) {
                if (cell.hasAttribute('colspan')) return;
                if (labels[idx]) cell.setAttribute('data-label', labels[idx]);
                var isLast = (idx === cells.length - 1);
                if (idx >= CARD_VISIBLE_COUNT && !isLast) {
                    cell.classList.add('ig-card-extra');
                    extraCells.push(cell);
                }
            });

            if (extraCells.length > 0 && !tr.querySelector('.ig-card-more-cell')) {
                var moreCell = document.createElement('td');
                moreCell.className = 'ig-card-more-cell';
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ig-card-more-btn';
                btn.setAttribute('aria-expanded', 'false');
                var moreLabel = 'Show ' + extraCells.length + ' more';
                btn.innerHTML = '<i class="bi bi-chevron-down" aria-hidden="true"></i> ' + moreLabel;
                btn.addEventListener('click', function () {
                    var expanded = tr.classList.toggle('ig-card-expanded');
                    btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    btn.innerHTML = expanded
                        ? '<i class="bi bi-chevron-up" aria-hidden="true"></i> Show less'
                        : '<i class="bi bi-chevron-down" aria-hidden="true"></i> ' + moreLabel;
                });
                moreCell.appendChild(btn);
                tr.insertBefore(moreCell, extraCells[0]);
            }
        });
    }

    function initAllCards() {
        var tables = document.querySelectorAll('table.ig-table');
        for (var i = 0; i < tables.length; i++) applyResponsiveCards(tables[i]);
    }

    // ------------------------------------------------------------------
    // Bulk-selection indicator — purely additive visual feedback for the
    // existing "check rows, then click delete" pattern already used by
    // several tables (leadsList, socialticket, socialpost, global search).
    // This never touches the existing delete logic/endpoints — it only
    // reads checkbox state and shows a count on the delete button that's
    // already there, scoped to that specific table+button pair by DOM
    // proximity so two tables on the same page can never cross-count.
    // ------------------------------------------------------------------
    function initBulkSelectionIndicators() {
        var tables = document.querySelectorAll('table.ig-table');
        tables.forEach(function (table) {
            var boxes = table.querySelectorAll('.deleteCheck');
            if (!boxes.length) return;
            var panel = table.closest('.ig-panel, .bgwhite2, .busines_details') || table.parentElement;
            if (!panel) return;
            var deleteBtn = panel.querySelector('.ig-icon-btn-danger, #deleteBtn');
            if (!deleteBtn || deleteBtn.querySelector('.ig-selected-count')) return;

            var badge = document.createElement('span');
            badge.className = 'ig-selected-count';
            deleteBtn.appendChild(badge);

            function refresh() {
                var count = 0;
                boxes.forEach(function (b) { if (b.checked) count++; });
                badge.textContent = count > 0 ? String(count) : '';
                deleteBtn.classList.toggle('ig-has-selection', count > 0);
            }
            boxes.forEach(function (b) { b.addEventListener('change', refresh); });
            refresh();
        });
    }

    function initAllResponsive() {
        initAllCards();
        initBulkSelectionIndicators();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAll();
            initAllResponsive();
        });
    } else {
        initAll();
        initAllResponsive();
    }

    // expose for manual re-init if a page swaps table content via its own JS
    window.igTableTools = { init: initAll, initTable: initTable, initResponsive: initAllResponsive };
})();
