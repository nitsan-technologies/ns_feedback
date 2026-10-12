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
| `georgringer/news` | not declared | only used when a report has a `record_id` (news detail feedback); see known issues |

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

- `Configuration/Backend/Modules.php` lists a `premiumExtension` action that `ReportController` doesn't have.
- `ReportController::listAction()` uses `GeorgRinger\News\...\NewsRepository` when a report has `record_id`,
  but EXT:news is not a declared dependency.
- `FeedbackController::quickFeedbackAction()` never reads `newsId` from `$result` (`$newsId` is always 0),
  so quick feedback on news detail pages is stored against the page only.
- `Resources/Public/JavaScript/Main.js` (DataTables init) is not loaded by any backend view, so the
  `.ns-ext-datatable` tables in List/Show render as plain tables.
- `Classes/ViewHelpers/FeedbackViewHelper.php` is unused and broken (uses `ViewFactoryData` without import,
  `execute()->fetchAll()`); it reads a `pages.tx_nsfeedback_enable` field that `ext_tables.sql` doesn't create.
- `FeedbacksRepository::insertNewData()/curlInitCall()/deleteOldApiData()` are unused and target a non-existent
  table `tx_nsfeedback_domain_model_apidata`.
- `Resources/Private/Templates/Default/ConstantEditor*.html` and `Partials/ConstantEditor*.html` are leftovers
  (no controller action renders them).
- `Configuration/Sets/NsFeedback/constants.typoscript` imports `Constants.typoscript` (capital C) but the file
  is `constants.typoscript`, which fails on case-sensitive file systems.
- `ReportController` assigns `sidebarData` / `dashboardSupportData`, which are never set (render empty).
- `Configuration/page.tsconfig` sets `dashboardPreset-nsFeedbackPreset`, but no dashboard preset is defined.
- TCA `iconfile` points to `.gif` icons that don't exist in `Resources/Public/Icons/`.
- Module TypoScript (`module.tx_nsfeedback.view.*`) points to non-existent `Resources/Private/Backend/*` paths;
  templates resolve via Extbase defaults.
- `composer.json` licence `GPL-2.0+` is a deprecated SPDX id (`composer validate` warning).
- README badges/links are outdated (v13.0.3, `t3planet.com/typo3-feedback-extension` vs. sheet premium URL).
