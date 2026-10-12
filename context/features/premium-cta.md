# Feature — Premium strip and ns_license free trial

*Status: Done. Requires ns_license 14.5.4 (deep-link support) to be released.*

## Licensing status

Freemium per the NITSAN Free/Freemium sheet. Premium product: https://t3planet.de/typo3-rueckmeldung-erweiterung
(not linked from the UI; the trial stays inside the TYPO3 backend).

Premium adds (T3Planet catalog): rating forms (stars, emojis, numbers), full and popup feedback forms, custom
questions, email notifications. The free version only has the quick Yes/No/Yes-but/No-but feedback.

## Dependency

`composer.json` `"nitsan/ns-license": "^14.5.4"`, `ext_emconf.php` `'ns_license' => '14.5.4-14.9.99'`.

## Strip

- Rendered at the top of the `content` section of `Templates/Report/Dashboard.html`, directly below the navbar
  (the `Report` layout has no flash messages or warnings, so no layout section is needed). List and Show
  views don't render it.
- Content: `actions-star` core icon, headline + one-line description, one `btn btn-primary` "Start Free Trial"
  link (same tab).
- Labels in `locallang.xlf`:
  - `premium.strip.headline`: "All in One Feedback Premium"
  - `premium.strip.description`: "Get more out of your feedback with the premium version. Try it free."
  - `premium.strip.trial`: "Start Free Trial"
  Wording and look follow the ns_backup strip (branch `feature/ns-license-premium-strip`) at the developer's request.
- Styling: Bootstrap utilities + `.ns-feedback-premium-strip` in `Resources/Public/Css/main.css` — gray bar
  identical to `.ns-backup-premium-strip`, `light-dark()` colours for both backend colour schemes, no inline styles.
  The strip sets Verdana 12px, and `.ns-feedback-premium-strip .btn` sets 14px, `6px 12px` padding, `6px` radius
  and `#007bff` (hover `#0062cc`), because this module's `global.css` uses Roboto 14px and the frontend
  `nsfeedback.css` (also loaded by the backend layout) pads every `.btn` with `8px 25px`. Computed result matches
  the ns_backup strip and button exactly (checked side by side in a browser).

## Trial link

`ReportController::dashboardAction()` builds `trialUrl` with the backend `UriBuilder`:
route `nitsan_nslicensemodule` (`NS_LICENSE_MODULE`), param `trial=ns_feedback`, anchor `#free-section`
(`NS_LICENSE_FREE_SECTION_ANCHOR`).

ns_license's `get-license.js` (`maybeOpenTrialDeepLink()`) reads `?trial=ns_feedback` and removes it from the
URL. If the "My Extensions" pane (`#extensions-pane`) holds a Free Trial trigger for `ns_feedback` it activates
that tab and scrolls to `#free-section`; otherwise it activates the Extensions catalog tab
(`#extensions-catalog-tab`). In both cases it then opens ns_license's own trial modal for `ns_feedback`. The
T3Planet catalog lists ns_feedback as a paid extension, so the catalog tab is the usual path.

Limitation: `buildUriFromRoute()` throws if the ns_license module route is missing, so the dashboard needs
ns_license installed (guaranteed by the declared dependency). The ns_license module requires system
maintainer access, while this module is open to all backend users (`access: user`).
