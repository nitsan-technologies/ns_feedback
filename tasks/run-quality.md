# Run quality checks

The repo has no PHPUnit, PHPStan or CS config. Available checks:

```bash
# PHP syntax, once per installed PHP version (8.1+)
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done

# composer.json (expected warning: deprecated licence id "GPL-2.0+")
composer validate --no-check-publish

# XLF well-formedness
for f in Resources/Private/Language/*.xlf; do xmllint --noout "$f"; done
```

## Manual test (per TYPO3 major 12 / 13 / 14)

Setup: include the static template "Feedback" or add the site set `nitsan/ns-Feedback` to the site. If the
theme has no jQuery, enable the `jQuery` setting (otherwise the frontend alerts and nothing works).

1. Frontend: add the "feedback" content element to a page. The box renders above `<footer>`, with Yes / No /
   "Yes, But" (No-but is off by default).
2. "Yes, But" with an empty comment → "Please add comment first". With a comment → thank-you overlay, buttons
   gone. Reload: buttons stay hidden for this IP (`restrictMultipleFeedback`).
3. Database: one `tx_nsfeedback_domain_model_report` row and one `…_feedbacks` row on that page (pid = page
   uid), `feedback_type` 3, comment stored.
4. Toggle `allPage` and check a page without the element: box present / absent. Toggle `enableFeedback`:
   scoreboard with page counters.
5. Backend: T3Planet → Feedback Report, select the voted page. Dashboard counters match. Reports → View Feedbacks
   lists the vote. Other pages show "Oops!".
6. Dashboard premium strip directly below the navbar. "Start Free Trial" opens the ns_license module and its
   trial modal for `ns_feedback` (system maintainer). List/Show views have no strip.
7. Check light and dark backend themes.

Remove test content and the two tables' test rows afterwards.
