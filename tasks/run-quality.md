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

1. T3Planet → Feedback Report → Dashboard (select a page): premium strip shows directly below the navbar;
   "Start Free Trial" opens the ns_license module and its trial modal for `ns_feedback` (system maintainer).
2. Feedback Report list and detail views show no strip.
3. Frontend: add the Feedback content element, vote Yes and "Yes, but" (with comment), check the counters on the Dashboard.
4. Check light and dark backend themes.
