/*
 * IGL Social CRM — lightweight, purely-cosmetic UI enhancements.
 *
 * IMPORTANT: this file must never change application behaviour. It only
 * adds visual polish (subtle hover/press feedback) on top of markup that
 * already works without it. Every effect here is additive and wrapped in
 * defensive checks so a missing element never throws or blocks the page.
 * Nothing in this file listens for form submits, prevents default actions,
 * or talks to the network.
 */
(function () {
  "use strict";

  if (window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    return; // respect the user's motion preference entirely
  }

  document.addEventListener("DOMContentLoaded", function () {
    // Subtle "lift" on cards / stat tiles while the pointer is over them.
    // Pure class toggling — CSS (.states_inner:hover, .bgwhite2:hover) already
    // defines the actual visual change, this just helps on touch devices.
    var liftTargets = document.querySelectorAll(".states_inner, .bgwhite2");
    liftTargets.forEach(function (el) {
      el.addEventListener(
        "touchstart",
        function () {
          el.classList.add("ig-touch-active");
        },
        { passive: true }
      );
      el.addEventListener(
        "touchend",
        function () {
          window.setTimeout(function () {
            el.classList.remove("ig-touch-active");
          }, 150);
        },
        { passive: true }
      );
    });

    // Any element below the fold that opts in via [data-ig-reveal] gets a
    // gentle fade-up the first time it scrolls into view. This is purely
    // decorative progressive enhancement — elements are fully visible by
    // default (see .ig-animate-in in style.css), so nothing breaks if
    // IntersectionObserver is unavailable.
    if ("IntersectionObserver" in window) {
      var revealTargets = document.querySelectorAll("[data-ig-reveal]");
      if (revealTargets.length) {
        var observer = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add("ig-in-view");
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.15 }
        );
        revealTargets.forEach(function (el) {
          observer.observe(el);
        });
      }
    }
  });
})();

/*
 * Tables no longer scroll horizontally (see the "Table responsiveness"
 * block in style.css) — wide tables reflow/wrap instead, and collapse into
 * stacked cards below 768px. There is nothing left for this file to measure
 * or add a scroll affordance for; the per-cell responsive labelling and the
 * "show more" collapsed-column toggle live in ig-table-tools.js instead,
 * since they need to run at the same time the table's own markup is read.
 */

/*
 * Right-side panels (Assigned to Me / Favourite, and any future offcanvas
 * built the same way) — re-trigger the item cards' CSS entrance animation
 * every time the panel opens, not just on first page load. Bootstrap's
 * offcanvas doesn't re-render the DOM on show, so without this the
 * animation (which is a one-shot `animation:` in CSS) would only ever play
 * once. Purely visual: it removes+re-adds a class, nothing about what the
 * panel shows or does changes.
 */
(function () {
  "use strict";
  if (typeof bootstrap === "undefined" || !bootstrap.Offcanvas) return;

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".ig-side-panel").forEach(function (panel) {
      panel.addEventListener("show.bs.offcanvas", function () {
        var items = panel.querySelectorAll(".socialticketinner");
        items.forEach(function (item) {
          item.style.animation = "none";
          // force reflow so re-setting the animation actually restarts it
          void item.offsetWidth;
          item.style.animation = "";
        });
      });
    });
  });
})();

/*
 * Filter modals — tab-switch feels intentional instead of an instant swap.
 * Purely cosmetic: the form still submits exactly as before (same
 * method/action/fields); this only toggles a class.
 */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".filter .nav-pills .nav-link").forEach(function (tab) {
      tab.addEventListener("shown.bs.tab", function (e) {
        var pane = document.querySelector(e.target.getAttribute("href"));
        if (pane) {
          pane.classList.remove("ig-tab-in");
          void pane.offsetWidth;
          pane.classList.add("ig-tab-in");
        }
      });
    });
  });
})();

/*
 * Submit buttons app-wide — brief busy state on click so a slow request
 * doesn't feel like nothing happened. Purely cosmetic: the form still
 * submits exactly as before (same method/action/fields/validation); this
 * only toggles a class.
 * Only <button type="submit"> is targeted — the busy spinner is a CSS
 * ::after pseudo-element, which does not render on <input> elements, so
 * <input type="submit"> (login/register/config pages) is intentionally
 * left untouched rather than risk the label disappearing with no spinner.
 * A handful of forms in this app intercept submit with their own JS
 * (custom validators, AJAX handlers with preventDefault) instead of doing
 * a plain navigation, so the class is also auto-cleared after a short
 * timeout as a safety net — otherwise a button whose submit gets
 * cancelled or handled via AJAX would stay stuck in the busy state.
 */
(function () {
  "use strict";

  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form button[type='submit']").forEach(function (btn) {
      btn.addEventListener("click", function () {
        var form = btn.closest("form");
        if (!form || (typeof form.checkValidity === "function" && !form.checkValidity())) return;
        btn.classList.add("ig-btn-busy");
        setTimeout(function () { btn.classList.remove("ig-btn-busy"); }, 6000);
      });
    });
  });
})();
