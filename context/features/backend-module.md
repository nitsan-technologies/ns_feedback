# Feature — Backend module (Feedback Report)

*Status: Done. Code-read; data scope matches the records written in the live vote test (2026-10-12).*

Registration and routes: see `architecture.md`. Menu: **T3Planet → Feedback Report**, with the page tree.

## Data scope

`ReportController::initializeAction()` sets the report storage page to the `id` of the selected page. Reports
are stored on the page that was voted on (`quick-feedback.md`), so the module shows **only that one page's
feedback**. There is no recursion into subpages and no site-wide overview. Selecting the root page shows only
votes cast on the root page. Without a selected page (`id` = 0) everything is empty. `findAllByLanguage()`
ignores the language, so all languages are summed.

## Layout (`Layouts/Report.html`)

The layout links its own CSS: vendored Bootstrap 5.3, `global.css`, `main.css`, `font-awesome.css` and the
frontend `nsfeedback.css`. A navbar follows with brand "Feedback", **Dashboard** and **Reports** (tooltip:
"Reports tab displays all the Feedback reports…"), then the `content` section. There are no flash messages,
no doc header buttons and no config warnings.

## Dashboard (`dashboardAction()`)

- Premium strip at the top (`premium-cta.md`).
- `total` = number of `Feedbacks` rows on the page (`FeedbacksRepository::countAllByLanguage()`, storage = `id`).
  The four counters are summed over the page's reports.
- `total > 0`: three cards: "Total Feedbacks", Yes / No, "Yes, But" / "No, But".
- Otherwise: "Oops! Feedback is not available for this page. Please select other page to view feedbacks."
- Also assigns empty `rightSide`, `dashboardSupport`, `totalrating`, `report` (premium leftovers).

## Reports (`listAction()`, `showAction()`)

- List: one row per report of the page. Columns: type (News if `record_id` is set, Blog for doktype 137, else
  Page), page or news title, quick-feedback total (sum of the four counters), and a "View Feedbacks" button →
  show. A report with `record_id` loads the news via EXT:news `NewsRepository`. In the free version
  `record_id` is never set (see `quick-feedback.md`), so this branch is dormant.
- Show: votes with `feedback_type == 3`. Columns: date (`m/d/Y`), button label, comment, visitor IP.
  A back link goes to the list.
- Tables carry `.ns-ext-datatable`, but `Main.js` (DataTables) is never loaded, so they render as plain tables.
