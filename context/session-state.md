# ns_feedback — Session state

## 2026-10-12 — ns_license integration + premium strip

- Branch `feature/ns-license-premium-strip` (from `origin/master` @ b7db3bd), commit 116f7af. Later strip
  and context changes are uncommitted.
- Added `nitsan/ns-license ^14.5.4` / `ns_license 14.5.4-14.9.99`.
- Dashboard premium strip with "Start Free Trial" → ns_license module `?trial=ns_feedback#free-section`
  (see `features/premium-cta.md`). Aligned with the ns_backup gray strip after review.
- Created agent context (`AGENTS.md`, `CLAUDE.md`, `context/`, `tasks/`); no previous context existed.
- Open: ns_license 14.5.4 not yet published. Backend click-through of the strip / trial modal pending
  (the trial deep link was only code-read).

## 2026-10-12 — context checked against the running extension

- TYPO3 14.3.7 classic mode, Camino theme, site set `nitsan/ns-Feedback` added temporarily. Tested: content
  element, `allPage`, `enableFeedback`, `quickbuttonsNoBut`, `jQuery`, a vote via curl and in the browser.
  All test data removed afterwards.
- Findings recorded in `features/quick-feedback.md` and `core.md` known issues: duplicate votes per IP
  possible, jQuery alert, Global layout moved above the footer, page uid as `cid` for the page-wide box,
  set constants import case bug.

## 2026-10-14

- Premium strip colours changed from grey to light blue (same palette as ns_revolution_slider). Icon changed from star to rocket (`actions-rocket`). Design aligned with the ns_backup `feature/ns-license-premium-strip-highlight` strip (4px left accent, 10px padding). Headline now "You are using the free version" with an "Upgrade to Premium for ..." description.
