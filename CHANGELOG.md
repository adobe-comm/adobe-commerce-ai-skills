# Changelog

## 0.5.0 — 2026-09-22 (hardening)

Version policy: pack version and every skill `metadata.version` track together (all **0.5.0**).

### Task 1 — Vendor / composer.lock probe
- Added `evals/fixtures/vendor-probe-git/` (real git; `vendor/` + `composer.lock` gitignored, present on disk).
- Terminal route confirmed (`detect-stack.sh`, `cat`).
- Direct Agent workspace-root Read inconclusive (`agent -p` auth failure this session).
- Core rule 5 + README: prefer terminal; do not retry `@vendor/...` first.

### Task 2 — Eval scoring
- Live clean-chat Agent scoring blocked by CLI auth; sheet and method in `evals/results-0.5.0.md` (headline % not invented).
- Fixed YAML parse errors in debugging / integration-work / project-understanding / coding-standards / feature-analysis cases; inventory **70** cases (51 pos / 19 neg).
- Description-level overlap audit; tightened project-understanding, feature-analysis, coding-standards descriptions.

### Task 3 — Deferred skills (Option A)
- Added slash-only `adobe-commerce-upgrade-and-patching` and `adobe-commerce-deploy-and-environments` with refuse-production and ACCS/no-UCT cases.

### Task 4 — AskQuestion sourcing
- Primary doc: Cursor Agent overview “Ask questions”. Exact id `AskQuestion` left community-sourced; rules/README soft-match the host tool.

### Task 5 — Shell-hook caveat
- Documented bypass limit in `docs/TEAM_USER_GUIDE.md` (hook behavior unchanged).

### Task 6 — Version drift
- All skills + plugin.json → 0.5.0 (track-with-pack policy).

## 0.4.0 — 2026-09-22

Phase 3 (scoped): performance review, project documentation, and guardrail hooks.

### Added

- `adobe-commerce-performance-review` — hot-path performance checks (collections, N+1, sync external calls, FPC/indexer, bounded cron/queue).
- `adobe-commerce-project-documentation` — slash-only (`disable-model-invocation: true`); ADRs, integration notes, project-facts updates as human-reviewed diffs.
- Guardrail hooks:
  - `scripts/block-protected-writes.py` on `preToolUse` (Write/StrReplace/Delete) — **deny** writes to `vendor/`, `generated/`, `pub/static/`, `app/etc/env.php`, `auth.json` (`failClosed: true`).
  - `scripts/block-protected-shell.py` on `beforeShellExecution` — **deny** shell writes into those paths; **ask** on force-push / DROP / TRUNCATE. Reads of `vendor/` remain allowed.
- Plugin hooks config: `hooks/hooks.json`; project template: `templates/project/.cursor/hooks.json`.
- Eval cases for both new skills; `hooks/README.md`.

### Changed

- `install.sh` now installs hook scripts into `.cursor/hooks/` and creates `.cursor/hooks.json` when absent (will not overwrite an existing hooks.json).
- Pack version **0.4.0** (13 skills).

### Deferred

- `adobe-commerce-upgrade-and-patching`
- `adobe-commerce-deploy-and-environments`

## 0.3.0 — 2026-09-22

Adapted to the CoE's actual environment after the team answered the open verification questions.

### Added

- `scripts/detect-stack.sh` — reads commerce package, `magento/framework` version, PHP constraint, PHPUnit major (with the correct metadata style), phpcs/phpstan/coding-standard presence, composer script aliases, test config locations, DDEV presence, and whether `vendor/` is installed. Runs through the shell, so it works even though `composer.lock` and `vendor/` are gitignored.
- `references/bootstrap-quality-tooling.md` in the testing skill — one-time setup for `magento/magento-coding-standard`, PHPUnit, composer aliases, and a CI gate, for projects with no tooling today.

### Changed

- Core rule: version facts are to be read through the terminal, which ignore files do not block, instead of relying on `.cursorignore` negation. Commands must be prefixed with `ddev exec` when `.ddev/` exists.
- Testing skill: explicit no-harness path — state what is missing, do the manual validation, offer setup as a separate agreed task. Never fabricate a command or imply a run.
- Coding-standards skill: when no linter is installed, say so and apply conventions from the nearest exemplar rather than implying a standard was enforced.
- `templates/project/AGENTS.md`: command-prefix field, and `NOT SET UP` instead of placeholder commands.
- PHPUnit metadata guidance is now version-accurate (12 requires attributes, 11 deprecates annotations, 10 supports both).

### Verified

- PHPUnit 12 removed doc-comment annotation metadata — primary source phpunit.de.
- `bin/magento dev:di:info <class> [<area>]` documented in Adobe's on-premises CLI reference.
- Cursor's clarifying-question tool is `AskQuestion`, host-attached and not always available; policy P1 already handles the fallback.
- All 11 skills revalidated against Agent Skills constraints; `detect-stack.sh` tested on four fixtures.

## 0.2.0 — 2026-09-22

Productivity review of 0.1.0 found the pack was governance-heavy and thin on work the team does daily. This release closes that gap.

### Added

- `adobe-commerce-module-scaffold` — new skill (not in the original catalog). Generates modules, plugins, observers, patches, CLI commands, cron jobs, consumers, GraphQL resolvers, and admin config with ACL, matching project exemplars. Includes `references/file-sets.md` with the minimum correct file set per extension point.
- `adobe-commerce-debugging` — root-cause workflow with `references/log-and-state-map.md` (symptom to first-check table, read-only CLI list).
- `adobe-commerce-testing` — change-type to test-type decision table, PHPUnit major read from `composer.lock`, honest run reporting.
- `adobe-commerce-code-review` — risk-first review with severity ladder and verdict.
- `adobe-commerce-security-review` — surface-scoped checks including CSP/SRI on payment pages.
- `adobe-commerce-frontend-and-tracking` — stack detection, tracking traced only through layers that exist.
- `adobe-commerce-integration-work` — evidence-based inventory plus contract/idempotency/retry/observability matrix and `references/integration-checklist.md`.
- Eval cases for all seven new skills; every skill now has a `cases.yaml`.

### Changed

- Core rule now leads with "do the work, do not narrate it" and forbids ceremony on small tasks. Summary headings reduced for L2+.
- `templates/project/AGENTS.md` now carries concrete default commands the agent uses verbatim, plus a "do not touch" section.
- `detect-platform.sh` emits disambiguation hints and no longer reports an ACCS-shaped repo as plain App Builder.

### Verified

- All 11 `SKILL.md` files pass Agent Skills constraints (name matches folder, description under 1024 chars, body well under the 500-line cap, reference links resolve).

## 0.1.0 — 2026-09-22

### Added

- Cursor plugin manifest, always-on core rule with depth ladder
- Project overlay templates and `install.sh`
- Skills: project-understanding (+ `detect-platform.sh`), feature-analysis, requirement-planning, coding-standards
- Eval fixtures (cloud-shaped, plain-store, app-builder, edge-delivery, accs-shaped, vendor-probe, code-vs-facts) and manual runbook
- Research gate docs: `VERIFICATION.md`, `SOURCES.md`, `ADOPTION.md`

### Decisions

- Teams plan distribution; Azure DevOps (not Jira); AskQuestion policy P1; manual evals first; defer App Builder/EDS to Adobe official skills
