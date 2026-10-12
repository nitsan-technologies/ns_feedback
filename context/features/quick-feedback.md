# Feature — Quick feedback (frontend)

*Status: Done.*

## Rendering

- Content element `nsfeedback_feedback` → `FeedbackController::newAction()` (layout `Default` only when the
  record's `list_type` is `nsfeedback_feedback`, i.e. legacy list plugins; otherwise `Global`). `lib.newact` renders the same action on every page when `allPage` is enabled
  (`page.785456789985`).
- `newAction()` reads the current page (`TSFE->page` on v12, `frontend.page.information` on v13+), the content
  element (`currentContentObject`), and an optional `tx_news_pi1[news]` id; builds the enabled button list
  (`quickbuttonsYes` = 1, `No` = 2, `YesBut` = 3, `NoBut` = 4) and checks for an existing vote from the same IP.
- `New.html`: thank-you box (`thankyoutitle`, `thankyoumsg`), the buttons partial unless the visitor already
  voted and `restrictMultipleFeedback` is on, and the quick-feedback scoreboard (`Partials/Report/YesNo.html`)
  when `quickenable` is on.
- `LoadAssetsViewHelper` adds inline CSS for `buttonstyle`, `buttonbg`, `buttoncolor`, `fontstyle`, `fontcolor`.

## Saving a vote

`nsfeedback.js` posts `tx_nsfeedback_feedback[result][...]` (`feedbackType`, `buttonfor`, `qkbutton`, `cid`,
`commentText`, `newsId`) to the button's `quickFeedback` link (page type `741852`, JSON page defined in
`Configuration/TypoScript/setup.typoscript`). "Yes, but" / "No, but" require a comment.
`quickFeedbackAction()` finds or creates the page's `Report`, adds a `Feedbacks` row (once per IP) and increments
the matching counter. Response: `{"Status":"Success"}`.

## Settings

Two equivalent entry points; keep them in sync:

| Mode | Files | Constant prefix |
|---|---|---|
| Static TypoScript template | `Configuration/TypoScript/{constants,setup}.typoscript` (+ `FeaturesConstants/*`) | `plugin.tx_nsfeedback_feedback.*` |
| Site set `nitsan/ns-Feedback` | `Configuration/Sets/NsFeedback/*` (imports the static setup, then overrides settings) | `ns_feedback.*` (`settings.definitions.yaml`) |

Settings: `allPage`, `restrictMultipleFeedback`, `jQuery`, `quickFeedbackTitle`, `quickboxappearance`,
`quickbuttonsYes/No/YesBut/NoBut`, `enableFeedback` (→ `quickenable`), `thankyoutitle`, `thankyoumsg`,
`fontstyle`, `fontcolor`, `buttonstyle`, `buttonbg`, `btntextcolor`.
