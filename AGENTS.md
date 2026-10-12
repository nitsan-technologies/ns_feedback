# AGENTS.md — ns_feedback (All in One Feedback)

Agent router for the All in One Feedback free extension. Load only what your task needs.

> **Principles:** `context/principles.md`
> **Session log:** `context/session-state.md`
> **Doc index:** `context/docs-map.md`

---

## Always load

| File | Why |
|---|---|
| `context/principles.md` | Guardrails, "nothing that works may break" rule |
| `context/core.md` | Identity, TYPO3/PHP/ns_license constraints, tooling, known issues |

---

## Task router

| Task | Load |
|---|---|
| Frontend plugin, quick feedback buttons, AJAX save, TypoScript / site set settings | `context/features/quick-feedback.md` |
| Backend module (Dashboard, Feedback Report list, detail view) | `context/features/backend-module.md` |
| Premium strip / free trial / ns_license dependency | `context/features/premium-cta.md` |
| Classes, tables, templates, routes, assets | `context/architecture.md` |
| Lint / validate / manual test | `tasks/run-quality.md` |
| Update agent context after a change | `tasks/context-update.md` |

---

## Feature status

| Feature | Status | Agent entry |
|---|---|---|
| Quick feedback plugin (Yes / No / Yes-but / No-but, optional comment) | Done | `context/features/quick-feedback.md` |
| Backend module: Dashboard totals, report list, report detail | Done | `context/features/backend-module.md` |
| Premium strip + ns_license free-trial deep link | Done (needs ns_license 14.5.4 release) | `context/features/premium-cta.md` |

---

## Quick commands

```bash
cd packages/ns_feedback            # or typo3conf/ext/ns_feedback in Classic mode
for f in $(find . -name "*.php" -not -path "./.git/*"); do php -l "$f"; done
composer validate --no-check-publish
for f in Resources/Private/Language/*.xlf; do xmllint --noout "$f"; done
```
