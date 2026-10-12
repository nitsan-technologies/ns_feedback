# ns_feedback — Core facts

## Identity

| Item | Value |
|---|---|
| Extension key | `ns_feedback` |
| Composer name | `nitsan/ns-feedback` |
| Title (ext_emconf) / product name | TYPO3 Feedback Extension / All in One Feedback |
| Namespace | `NITSAN\NsFeedback\` → `Classes/` |
| Version (ext_emconf) | 14.0.0 |
| Repository | https://github.com/nitsan-technologies/ns_feedback |
| Licensing status (NITSAN Free/Freemium sheet) | **Freemium** — this repo is the free version |
| Premium version (sheet) | https://t3planet.de/typo3-rueckmeldung-erweiterung (price "unknown" in the sheet; T3Planet catalog: €199) |

## Compatibility

| Source | TYPO3 | PHP |
|---|---|---|
| `ext_emconf.php` | `12.0.0-14.9.99` | not declared |
| `composer.json` | `^12 \|\| ^13 \|\| ^14` | not declared |
| ns_license 14.5.4 (dependency) | `12.4.18-14.9.99` | — |

- Effective minimum is **TYPO3 12.4.18** because of ns_license; the extension's own constraint still says 12.0.0.
- Code uses constructor promotion, `readonly` and union return types → PHP 8.1+ (TYPO3 12 minimum anyway).

## Dependencies

| Package | Constraint | Why |
|---|---|---|
| `typo3/cms-core` | `^12 \|\| ^13 \|\| ^14` | core |
| `nitsan/ns-license` / `ns_license` | `^14.5.4` / `14.5.4-14.9.99` | TYPO3 Shop module + free-trial deep link (see `features/premium-cta.md`) |
| `georgringer/news` | not declared | backend list loads news titles for reports with `record_id`; the free version never sets it |
| jQuery | not declared | `nsfeedback.js` needs it; bundled copy loaded only with the `jQuery` setting (default off) |

## Tooling

- No PHPUnit, PHPStan, CS fixer or package.json in the repo.
- CI: `.github/workflows/ter-release.yml` only (publishes tags to TER).
- Translations: Crowdin (`.crowdin.yml`), source files `Resources/Private/Language/*.xlf`.

## Conventions

- Extbase plugin (`ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT`) + Extbase backend module with
  `ModuleTemplateFactory` and `renderResponse()`.
- Labels from `locallang.xlf` via `<f:translate>`.
- Backend CSS: `Resources/Public/Css/global.css` and `main.css` (CRLF line endings), both use `light-dark()`
  for dark mode; Bootstrap 5.3 is vendored and loaded by `Layouts/Report.html`.
- Frontend assets: `nsfeedback.js`, `nsfeedback.css`, `font-awesome.css` via TypoScript; optional bundled jQuery.

## Known issues (existing, not fixed)

Behaviour details: `features/quick-feedback.md`, `features/backend-module.md`.

Frontend, verified live on v14.3:

- The server-side "one vote per IP" check in `quickFeedbackAction()` never matches (the
  `FeedbacksRepository` query respects an unset storage page). The same IP can vote again by direct POST,
  or through a second box on the page. Only the template (`restrictMultipleFeedback`) hides the buttons.
- `nsfeedback.js` alerts "jQuery is not included…" on every page and does nothing when the page has no jQuery.
  The `jQuery` setting defaults to off.
- Static template default `allPage = 1` puts the box on every page. The site set default is `false`, and the
  set's `constants.typoscript` imports `Constants.typoscript` (capital C, file is `constants.typoscript`),
  so with the set the static `plugin.tx_nsfeedback_feedback.*` constants are undefined.
- Content elements always use the `Global` layout (the `Default` switch checks `list_type`), and JS moves
  them above `<footer>`, ignoring the editor's placement.
- Frontend assets (JS + 2 CSS) load on every page, even pages without a box.
- JS error branch targets non-existent `#resubmit` / `#feedback-btns`. Selectors are global, so two boxes
  on a page interfere.

Code-read:

- `quickFeedbackAction()` never reads `newsId` from `$result` (`$newsId` is always 0), so news detail pages
  are stored as page feedback and the News branch in the backend list is dormant.
- `lib.newact` and page type `741852` use `switchableControllerActions` (ignored since v12; works because
  `new` is the default action and the AJAX URL names `quickFeedback`).
- `Templates/Feedback/Default.html` uses missing labels `error.header` / `error.security`.
  `FeedbackViewHelper` uses missing `tx_nsfeedback_domain_model_feedbacks.alreadyfeedback`.
- `Configuration/Backend/Modules.php` lists a `premiumExtension` action that `ReportController` doesn't have.
- `ReportController::listAction()` uses `GeorgRinger\News\...\NewsRepository` (EXT:news not declared).
- `Resources/Public/JavaScript/Main.js` (DataTables init) is not loaded, so the List/Show tables are plain.
- `Classes/ViewHelpers/FeedbackViewHelper.php` is unused and broken (uses `ViewFactoryData` without import,
  `execute()->fetchAll()`). It reads a `pages.tx_nsfeedback_enable` field that `ext_tables.sql` doesn't create.
- `FeedbacksRepository::insertNewData()/curlInitCall()/deleteOldApiData()` are unused and target a non-existent
  table `tx_nsfeedback_domain_model_apidata`.
- Unrendered leftovers: `Templates/Default/ConstantEditor*.html`, `Partials/ConstantEditor*.html`,
  `Partials/NoRecordFound.html`, `Partials/Report/Ratings.html`, `ThemeContainsViewHelper`.
- `ReportController` assigns `sidebarData` / `dashboardSupportData`, which are never set (render empty).
- `Configuration/page.tsconfig` sets `dashboardPreset-nsFeedbackPreset`, but no dashboard preset is defined.
- TCA `iconfile` points to `.gif` icons that don't exist in `Resources/Public/Icons/`.
- Module TypoScript (`module.tx_nsfeedback.view.*`) points to non-existent `Resources/Private/Backend/*` paths;
  templates resolve via Extbase defaults.
- `composer.json` licence `GPL-2.0+` is a deprecated SPDX id (`composer validate` warning).
- README badges/links are outdated (v13.0.3, `t3planet.com/typo3-feedback-extension` vs. sheet premium URL).
