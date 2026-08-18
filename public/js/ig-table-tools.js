/**
 * ig-table-tools.js
 *
 * Purely client-side, opt-in table enhancement:
 *   - click-to-sort column headers (asc / desc / default) with a visual indicator
 *   - an auto-generated per-column filter row (text / select / date / numeric range,
 *     auto-detected from the column's own rendered content)
 *   - a per-table "search this table" box (separate from any column filters)
 *
 * Usage: add `data-ig-tabletools` to any <table> that already has real <thead><tr><th>
 * headers and data rows in <tbody>. Everything else (search box, filter row, sort
 * indicators, wiring) is generated automatically at runtime. No markup restructuring
 * needed, and nothing here touches routes/AJAX/pagination — it only sorts/filters the
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

        // --- filter row ----------------------------------------------------
        var filterRow = document.createElement('tr');
        filterRow.className = 'ig-filter-row';
        var filterInputs = [];

        ths.forEach(function (th, index) {
            var td = document.createElement('th');
            td.className = 'ig-filter-cell';
            var meta = colMeta[index];
            if (meta) {
                if (meta.type === 'select') {
                    var select = document.createElement('select');
                    select.className = 'form-select form-select-sm ig-filter-input';
                    select.setAttribute('aria-label', 'Filter ' + textOf(th));
                    var optAll = document.createElement('option');
                    optAll.value = '';
                    optAll.textContent = 'All';
                    select.appendChild(optAll);
                    meta.options.forEach(function (val) {
                        var opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = val;
                        select.appendChild(opt);
                    });
                    select.addEventListener('change', reapplyFilters);
                    td.appendChild(select);
                    filterInputs.push({
                        index: index,
                        get: function () { return select.value; },
                        matches: function (cellText, val) { return val === '' || cellText === val; }
                    });
                } else if (meta.type === 'date') {
                    var dateInput = document.createElement('input');
                    dateInput.type = 'date';
                    dateInput.className = 'form-control form-control-sm ig-filter-input';
                    dateInput.setAttribute('aria-label', 'Filter ' + textOf(th));
                    dateInput.addEventListener('change', reapplyFilters);
                    td.appendChild(dateInput);
                    filterInputs.push({
                        index: index,
                        get: function () { return dateInput.value; },
                        matches: function (cellText, val) {
                            if (!val) return true;
                            var t = parseDateLoose(cellText);
                            if (isNaN(t)) return false;
                            var cellDay = new Date(t);
                            var iso = cellDay.getFullYear() + '-' +
                                String(cellDay.getMonth() + 1).padStart(2, '0') + '-' +
                                String(cellDay.getDate()).padStart(2, '0');
                            return iso === val;
                        }
                    });
                } else if (meta.type === 'numeric') {
                    var wrap = document.createElement('div');
                    wrap.className = 'ig-filter-range';
                    var min = document.createElement('input');
                    min.type = 'number';
                    min.placeholder = 'Min';
                    min.className = 'form-control form-control-sm ig-filter-input';
                    var max = document.createElement('input');
                    max.type = 'number';
                    max.placeholder = 'Max';
                    max.className = 'form-control form-control-sm ig-filter-input';
                    min.addEventListener('input', reapplyFilters);
                    max.addEventListener('input', reapplyFilters);
                    wrap.appendChild(min);
                    wrap.appendChild(max);
                    td.appendChild(wrap);
                    filterInputs.push({
                        index: index,
                        get: function () { return { min: min.value, max: max.value }; },
                        matches: function (cellText, val) {
                            var n = parseFloat(cellText);
                            if (isNaN(n)) return false;
                            if (val.min !== '' && n < parseFloat(val.min)) return false;
                            if (val.max !== '' && n > parseFloat(val.max)) return false;
                            return true;
                        }
                    });
                } else {
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = 'Search…';
                    input.className = 'form-control form-control-sm ig-filter-input';
                    input.setAttribute('aria-label', 'Filter ' + textOf(th));
                    input.addEventListener('input', reapplyFilters);
                    td.appendChild(input);
                    filterInputs.push({
                        index: index,
                        get: function () { return input.value.trim().toLowerCase(); },
                        matches: function (cellText, val) {
                            return val === '' || cellText.toLowerCase().indexOf(val) !== -1;
                        }
                    });
                }
            }
            filterRow.appendChild(td);
        });
        thead.appendChild(filterRow);

        // --- global per-table search ---------------------------------------
        var toolbar = document.createElement('div');
        toolbar.className = 'ig-table-search-wrap mb-2';
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
        toolbar.appendChild(searchWrap);

        var anchor = table.closest('.table-responsive') || table;
        anchor.parentNode.insertBefore(toolbar, anchor);

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
                var cells = cellsOf(tr);
                var rowText = textOf(tr).toLowerCase();
                var matchesGlobal = globalTerm === '' || rowText.indexOf(globalTerm) !== -1;
                var matchesAllCols = filterInputs.every(function (f) {
                    var cell = cells[f.index];
                    var cellText = cell ? textOf(cell) : '';
                    return f.matches(cellText, f.get());
                });
                var visible = matchesGlobal && matchesAllCols;
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

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // expose for manual re-init if a page swaps table content via its own JS
    window.igTableTools = { init: initAll, initTable: initTable };
})();
