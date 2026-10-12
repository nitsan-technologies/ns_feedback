# ns_feedback — Architecture

## Backend module (`Configuration/Backend/Modules.php`)

| Key | Parent | Access | Path |
|---|---|---|---|
| `nitsan_module` | — (main module, `after web`, label "T3Planet", page tree) | — | — |
| `nitsan_nsfeedbackmodule_report` | `nitsan_module` (`before top`) | `user` | `/module/nitsan/NsFeedbackReport` |

- ns_license registers `nitsan_module` as well, so both extensions share that main-menu entry.
- Navigation component: `@typo3/backend/page-tree/page-tree-element` on v12/v13,
  `@typo3/backend/tree/page-tree-element` on v14 (switch via `Typo3Version`).
- Extbase actions: `ReportController` → `dashboard` (default), `list`, `show`, `premiumExtension` (missing, see `core.md`).

## Frontend plugin (`ext_localconf.php`, `Configuration/TCA/Overrides/tt_content.php`)

`NsFeedback` / `Feedback` content element (`CType nsfeedback_feedback`), all actions non-cacheable:
`FeedbackController` → `new`, `quickFeedback`, `default`. Wizard entry in `Configuration/page.tsconfig`.
Fluid namespace `Feedback` → `NITSAN\NsFeedback\ViewHelpers`.

## Classes

| Class | Role |
|---|---|
| `Controller/FeedbackController` | Frontend: renders the quick feedback box (`new`, `default`), stores a vote (`quickFeedback`, JSON) |
| `Controller/ReportController` | Backend: dashboard totals, report list, report detail; builds ns_license trial URL |
| `Domain/Model/Report` | Per page (or news record) aggregate: four vote counters, page info, `feedbacks` relation |
| `Domain/Model/Feedbacks` | Single vote: IP, type, quick-feedback button, comment, content uid, language |
| `Domain/Repository/ReportRepository` | `checkExistRecord()`, `findAllByLanguage()`, `findByProperties()` |
| `Domain/Repository/FeedbacksRepository` | `countAllByLanguage()` (+ unused API helpers, see `core.md`) |
| `ViewHelpers/LoadAssetsViewHelper` | Adds inline CSS for button/message styling from settings |
| `ViewHelpers/ThemeContainsViewHelper`, `FeedbackViewHelper` | Not used by current templates |

DI: `Configuration/Services.yaml` autowires `NITSAN\NsFeedback\` (models excluded).

## Tables (`ext_tables.sql`)

| Table | Content |
|---|---|
| `tx_nsfeedback_domain_model_report` | `page_id`, `record_id` (news uid), `page_type`, `page_title`, `feedback_yes_count`, `feedback_no_count`, `feedback_yes_but_count`, `feedback_no_but_count`, `sys_lang_id`, `feedbacks` (1:n) |
| `tx_nsfeedback_domain_model_feedbacks` | `report`, `comment`, `user_ip`, `feedback_type` (3 = quick), `quickfeedbacktype` (yes/no/yesbut/nobut), `cid` |

## Views

| File | Used by |
|---|---|
| `Layouts/Report.html` | Backend: CSS includes, navbar (Dashboard, Feedback Report), `content` section |
| `Templates/Report/Dashboard.html` | Dashboard: premium strip, total / Yes-No / Yes-but-No-but cards or "no feedback" info |
| `Templates/Report/List.html`, `Show.html` | Report list (per page/news) and votes of one report |
| `Layouts/Default.html`, `Layouts/Global.html` | Frontend wrappers (both call `<Feedback:loadAssets />`) |
| `Templates/Feedback/New.html`, `Default.html` | Frontend: thank-you box, `Partials/Feedback/QuickFeedback.html`, optional `Partials/Report/YesNo.html` |

## Assets

- Backend (layout `<link>`): `Vendor/bootstrap5.3.min.css`, `Css/global.css`, `Css/main.css`, `Css/font-awesome.css`, `Css/nsfeedback.css`.
- Frontend (TypoScript): `JavaScript/nsfeedback.js`, `Css/nsfeedback.css`, `Css/font-awesome.css`, optional `JavaScript/jquery.js`.
- `Configuration/JavaScriptModules.php` maps `@nitsan/ns-feedback/` → `Resources/Public/JavaScript/`.

## Labels

`locallang.xlf` (UI, FE + BE), `BackendModule.xlf` (main module), `locallang_feedback_report.xlf` (submodule),
`locallang_db.xlf` and `locallang_csh_*.xlf` (TCA), `locallang_feedback_configuration.xlf`.
