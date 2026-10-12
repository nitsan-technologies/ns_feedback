# Feature — Backend module (Feedback Report)

*Status: Done.*

Registration and routes: see `architecture.md`. The module shows data for the page selected in the page tree
(`id` query parameter → storage page in `ReportController::initializeAction()`).

## Layout (`Layouts/Report.html`)

Top to bottom: extension name + navbar (Dashboard, Feedback Report), then the `content` section.
No flash messages or config warnings in the layout.

## Dashboard (`ReportController::dashboardAction()`)

- Sums the four vote counters over all reports of the page and counts all feedback rows (`total`).
- Assigns `action`, `pid`, `total`, `totalyescount`, `totalnocount`, `totalyesbutcount`, `totalnobutcount`,
  `trialUrl` (+ empty `rightSide`, `dashboardSupport`, `totalrating`, `report`).
- Template: premium strip (see `premium-cta.md`), then three cards (total, Yes/No, Yes-but/No-but) or an info
  box "Feedback is not available for this page."

## Feedback Report (`listAction()`, `showAction()`)

- List: one row per report with record type (news when `record_id` is set, blog for doktype 137, otherwise page),
  page or news title, quick-feedback total and a link to the detail view; loads the news record via EXT:news.
- Show: every quick-feedback vote of a report (date, button, comment, visitor IP) with a back link to the list.
