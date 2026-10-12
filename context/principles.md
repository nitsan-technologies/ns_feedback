# ns_feedback — Operating Principles

*Every change to code, context or docs must serve the goals below.*

---

## Goal 1 — Nothing that works may break

The extension is declared for TYPO3 v12, v13 and v14 at the same time (Composer and Classic mode).

- Every change must keep all three majors working. Follow the existing version switches
  (`VersionNumberUtility` in `FeedbackController`, `Typo3Version` in `Configuration/Backend/Modules.php`)
  instead of inventing new ones.
- Prefer the smallest change that fixes the root cause. No drive-by refactors.
- Run `tasks/run-quality.md` and re-test the affected flow in a frontend and backend before claiming done.

## Goal 2 — Token-cost efficiency (agent context)

- **DRY context:** each fact lives in exactly ONE file. Reference, don't copy.
- **Load on demand:** read `context/features/<area>.md` for the task, not everything.
- **Prune as you go:** keep `context/session-state.md` short.

## Goal 3 — Accuracy and trust

- Answer from context + code, never from invented API, table or field names.
- Conflicting facts across files = fix the source file.

---

## Guardrails (non-negotiable)

- Feedback rows store the visitor IP (`user_ip`); don't expose it outside the backend module.
- Keep the TypoScript settings and the site set settings (`Configuration/Sets/NsFeedback/`) in sync
  when adding or renaming a setting.
- Do not change ns_license from an ns_feedback task; reuse its module and trial modal.
- All UI text goes into `Resources/Private/Language/locallang.xlf`, no hard-coded labels.

---

## Apply this whenever you

- Edit `context/` or `AGENTS.md`
- Land a feature or bugfix (update the matching `context/features/*.md` + `session-state.md`)
