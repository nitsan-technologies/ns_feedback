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
- Content: `actions-rocket` core icon, headline and description on two lines, one `btn btn-primary`
  "Start Free Trial →" link (the arrow is `aria-hidden` markup, not part of the label; same tab).
- Wording and design are the team-wide standard from the 2026-10-14 review, identical in every NITSAN strip
  except for the extension name.
- Labels in `locallang.xlf`:
  - `premium.strip.headline`: "You're using the free version of NS Feedback"
  - `premium.strip.description`: "Unlock premium features and get more out of NS Feedback."
  - `premium.strip.trial`: "Start Free Trial"
- Styling: `.ns-feedback-premium-strip` (+ `-icon`, `-headline`, `-description`) in
  `Resources/Public/Css/main.css` — light-blue card (`#e7f2fc`, border `#8eb6e8`, 4px `#2b6cb0` left accent, 6px
  radius, padding 14px 18px, text `#0d2c4a`), semibold 1.1em headline, `light-dark()` for dark mode, button
  `#007bff` (hover `#0062cc`, `6px 14px` padding, no wrap). Font family and size are pinned to
  `--typo3-font-family` / `--typo3-font-size`, because this module's `global.css` uses Roboto 14px and the
  frontend `nsfeedback.css` (also loaded by the backend layout) pads every `.btn` with `8px 25px`; computed sizes
  match the Page module strips. `main.css` uses CRLF line endings.

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
