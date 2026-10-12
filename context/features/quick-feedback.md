# Feature — Quick feedback (frontend)

*Status: Done. Flow verified live on TYPO3 14.3 (classic mode, site set, Camino theme) on 2026-10-12.*

The free version has exactly one frontend feature: a "Was this post helpful?" box with up to four buttons.
Votes are aggregated per page. Ratings, full/popup forms and email notifications are premium only
(`premium-cta.md`).

## Two ways the box gets on a page

| Way | How | `cid` stored with a vote |
|---|---|---|
| Content element "feedback" (`CType nsfeedback_feedback`, wizard group "plugins") | `FeedbackController::newAction()` | the `tt_content` uid |
| Every page (`allPage`) | `lib.newact` (`USER_INT`, Extbase `Bootstrap->run`) copied to `page.785456789985` | the **page** uid (the `cObj` data is the page record) |

- Both can render on the same page; then the visitor sees two boxes and two scoreboards.
- `lib.newact` and the AJAX page still set `switchableControllerActions`, which Extbase ignores since v12.
  They work anyway: the first action of the plugin is `new`, and the AJAX link carries `action=quickFeedback`.

## Layout and position

- `newAction()` picks layout `Default` only if `$cdata['list_type'] == 'nsfeedback_feedback'` (legacy
  list-type records). The plugin is registered as a content element (CType), so on v12/v13/v14 it is always
  **`Global`** (`<div class="tx-ns-feedback global-ns-feedback">`).
- `nsfeedback.js` moves every `.global-ns-feedback` to just before the first `<footer>` if one exists. So the
  box ends up above the footer, not where the editor placed the content element.
- Both layouts call `<Feedback:loadAssets />` (`LoadAssetsViewHelper`), which adds inline CSS from `buttonstyle`,
  `buttonbg`, `buttoncolor`, `fontstyle`, `fontcolor`.

## What the visitor sees (`Templates/Feedback/New.html`)

1. `.ns-alert-wrap` thank-you overlay (`thankyoutitle`, `thankyoumsg`), hidden (`position: fixed`) until a vote succeeds.
2. `Partials/Feedback/QuickFeedback.html` inside `#ns-c{cid}`, unless this IP already voted for this
   page + `cid` and `restrictMultipleFeedback` is on (`ReportRepository::checkExistRecord()` with pid, cid, IP, type 3).
   - Title `quickFeedbackTitle`, hidden `feedbacktype` = 3, hidden `animationEffect` = `quickboxappearance`.
   - **Yes** (`buttonfor` 1, `qkbtn` yes) and **No** (2, no) are `f:link.action quickFeedback` links with
     `pageType 741852`. One click submits.
   - **Yes, But** (3, yesbut) and **No, But** (4, nobut) open the shared comment box (`.commenttxt`, effect
     slide / toggle / fade) and disable the other buttons. "Send Feedback" submits. An empty comment shows
     "Please add comment first".
   - Buttons appear if `quickbuttonsYes/No/YesBut/NoBut` = 1 (defaults: Yes, No, Yes-but on; No-but off).
3. `Partials/Report/YesNo.html` scoreboard "User's Quick Feedbacks" (page counters) if `enableFeedback` /
   `quickenable` is on (default off). JS sets bar widths in % of the total and colours them
   (≤20 % red, ≤40 orange, ≤60 cyan, ≤80 blue, else green). It only updates on reload.

`defaultAction()` / `Templates/Feedback/Default.html` render the same box with an `.ns-alert-hp` error header.
They are reachable only with `action=default` in the URL, and their labels `error.header` / `error.security`
don't exist.

## Saving a vote

`nsfeedback.js` (jQuery required) POSTs `tx_nsfeedback_feedback[result][feedbackType|buttonfor|qkbutton|cid|
commentText|newsId]` to the clicked link (`?tx_nsfeedback_feedback[action]=quickFeedback&type=741852&cHash=…`).
Page type `741852` (`quickFeedbackAction` PAGE, JSON header) runs `FeedbackController::quickFeedbackAction()`:

1. Finds the `Report` with `page_id` = current page (`newsId` is never read, so always page-based).
2. If none: creates a `Report` with that button's counter = 1 and one `Feedbacks` row.
3. If found: adds a `Feedbacks` row and increments the matching counter, but only if
   `FeedbacksRepository->findBy(['user_ip' => …])` returns nothing. That repository respects the plugin
   storage page, which is never configured, so the check doesn't match. **Verified: the same IP can vote
   again** (direct POST, or through the second box when the element and `allPage` both render). Only the
   template check above stops repeat votes, by hiding the buttons.
4. Both records go to **pid = the voted page** (`pid` / `page_id`, `page_type` = doktype, `page_title`,
   `sys_lang_id`). The comment is stored only for buttons 3/4. `feedback_type` = 3, `quickfeedbacktype` =
   yes/no/yesbut/nobut.
5. Response `{"Status":"Success"}`. JS empties `#ns-c{cid} .qckform` and shows the overlay. Its close
   icon reloads the page. Any other response would run an error branch that targets the non-existent
   `#resubmit` / `#feedback-btns`.

## Settings

Two entry points with **different defaults**:

| Mode | Files | Keys | `allPage` default |
|---|---|---|---|
| Static template "Feedback" (`sys_template` include) | `Configuration/TypoScript/{constants,setup}.typoscript` + `FeaturesConstants/*` | `plugin.tx_nsfeedback_feedback.*` (`appearanceSettings.appearance.allPage`, `commonSettings.*`, `design.*`, …) | **1** (box on every page; code-read) |
| Site set `nitsan/ns-Feedback` (site dependency) | `Configuration/Sets/NsFeedback/*` | `ns_feedback.*` (`settings.definitions.yaml`, editable in the Site Settings module) | **false** (verified) |

- The set's `setup.typoscript` imports the static setup, then redefines `settings`, `page.includeJSLibs.nsfjquery`,
  `lib.newact` and the `allPage` condition with `ns_feedback.*` constants. Its `constants.typoscript` imports
  `Constants.typoscript` (capital C), which doesn't exist on case-sensitive file systems. The static
  `plugin.tx_nsfeedback_feedback.*` constants are then undefined and the static `allPage = 1` condition never
  fires (verified: with the set and default settings, pages without the element show no box).
- Set settings: `jQuery`, `allPage`, `restrictMultipleFeedback`, `fontstyle`, `fontcolor`, `buttonstyle`,
  `buttonbg`, `btntextcolor` (→ `buttoncolor`), `thankyoutitle`, `thankyoumsg`, `quickFeedbackTitle`,
  `quickboxappearance`, `quickbuttonsYes/No/YesBut/NoBut`, `enableFeedback` (→ `quickenable`), view paths.

## Assets and jQuery

- Included on **every page** of the site once the template or set is active: `nsfeedback.js` (footer),
  `nsfeedback.css`, `font-awesome.css`.
- `nsfeedback.js` is jQuery code but jQuery is off by default (`jQuery` = 0 / false). Without jQuery on the
  page it shows a browser `alert('jQuery is not included, Please include first!')` on every page load and
  nothing works. Enable the `jQuery` setting (bundled `jquery.js`, `forceOnTop`) or let the theme provide it.
  The Camino theme has no jQuery.
- Selectors are global (`.commenttxt`, `.ns-alert-wrap`, `.bar-yes`, …), so with two boxes on a page they act on
  both, and the scoreboard bars use the first box's counts.
