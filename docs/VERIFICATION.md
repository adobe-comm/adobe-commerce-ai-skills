# Verification log

Research gate for adobe-commerce-ai-skills. Date: 2026-09-22.

## Decisions locked (2026-09-22)

| Topic | Decision |
|-------|----------|
| Cursor plan | Teams (1 team marketplace); ship plugin + `install.sh` fallback |
| Adobe official skills | Option A: do not require local Adobe install now; keep `adobe-commerce-*` names; defer App Builder/EDS/drop-in work to Adobe skills when present |
| Hooks | Include in Phase 3 (not Phase 1–2) |
| Evals | Manual runbook first; CLI/SDK automation later |
| Tickets | Azure DevOps (not Jira); use Azure DevOps MCP if installed, else pasted ticket text |
| Clarifying questions | Policy P1: prefer Cursor `AskQuestion` when attached; else one focused numbered A/B/C question in chat; never guess |
| Skill naming | Keep `adobe-commerce-*` prefix |

Status legend: verified | retracted | open (needs Phase 1 probe or further source).

## Sources read (Phase 0)

| ID | URL | Date read | Notes |
|----|-----|-----------|-------|
| S1 | https://cursor.com/docs/skills.md | 2026-09-22 | Skills dirs, frontmatter (`paths`, `disable-model-invocation`, `metadata`), built-in skills list |
| S2 | https://cursor.com/docs/plugins.md + https://cursor.com/docs/reference/plugins | 2026-09-22 | Cursor Plugin vs Agent Plugin; `.cursor-plugin/plugin.json`; team marketplace modes |
| S3 | https://cursor.com/docs/rules | 2026-09-22 | `.mdc`, `alwaysApply`, globs, AGENTS.md, Team Rules precedence |
| S4 | https://cursor.com/docs/agent/hooks | 2026-09-22 | Events, fail-open default, exit 2 = deny, `failClosed`, plugin `hooks/hooks.json` |
| S5 | https://cursor.com/docs/context/ignore-files | 2026-09-22 | `.cursorignore`; default ignore includes `composer.lock` and `vendor/` via gitignore; `!` negation |
| S6 | https://raw.githubusercontent.com/agentskills/agentskills/main/docs/specification.mdx | 2026-09-22 | name/description constraints; 500-line body guidance; progressive disclosure |
| S11 | https://developer.adobe.com/commerce/extensibility/developer-agent/tools-overview | 2026-09-22 | App Builder MCP + skills; dropins MCP; `aio commerce extensibility tools-setup` |
| S11b | https://developer.adobe.com/commerce/extensibility/developer-agent/best-practices | 2026-09-22 | Four-phase protocol; incremental deploy; stop-and-assess debugging |
| S12 | https://github.com/adobe/skills | 2026-09-22 | Repo exists; AEM and other Adobe skills live here (coverage separate from Commerce PHP) |
| S13 | https://www.npmjs.com/package/@adobe-commerce/commerce-extensibility-tools | 2026-09-22 | Package v3.5.0; App Builder MCP + agent skills; GitHub repo URL returned 404 from fetch |
| S14 | https://experienceleague.adobe.com/en/docs/commerce-operations/release/notes/adobe-commerce/2-4-9 | 2026-09-22 | PHP 8.5 production; 8.4 upgrade-only; PHPUnit 12 |
| S15 | https://developer.adobe.com/commerce/testing/guide/ | 2026-09-22 | Trailing slash required (no-slash 404); taxonomy unit/integration/api-functional/functional/js/static |
| S16 | https://experienceleague.adobe.com/.../upgrade-compatibility-tool-overview | 2026-09-22 | UCT: On-Prem yes, Cloud yes, ACCS no, ACO no |
| S17 | https://developer.adobe.com/commerce/php/development/security/subresource-integrity | 2026-09-22 | Magento_Csp; SRI on payment pages; versions listed below |
| S19 | https://developer.adobe.com/commerce/extensibility/app-development/extension-compatibility/ | 2026-09-22 | PaaS vs SaaS: in-process vs out-of-process; IMS; webhooks/events differences |
| S20 | https://experienceleague.adobe.com/en/tools/commerce-storefront/ai/ | 2026-09-22 | Boilerplate skills + Wayfinder; drop-in developer skill priority |
| Eval | https://cursor.com/docs/cli/headless.md ; https://cursor.com/docs/evals | 2026-09-22 | `agent -p` print mode; SDK preferred for formal evals; `--plugin-dir`, `--workspace` |

## Claim verification table

| Claim | Where used (planned) | Source | Status | Date |
|-------|----------------------|--------|--------|------|
| Cursor Plugin manifest is `.cursor-plugin/plugin.json` with required `name` | plugin root | S2 reference | verified | 2026-09-22 |
| Skills auto-discover from `skills/*/SKILL.md`; rules from `rules/*.mdc` | pack layout | S2 reference | verified | 2026-09-22 |
| Skill `name` max 64, lowercase/hyphens, no leading/trailing/double hyphen | all SKILL.md | S6 | verified | 2026-09-22 |
| Skill `description` max 1024 | all SKILL.md | S6 | verified | 2026-09-22 |
| Cursor adds `paths`, `disable-model-invocation`, `metadata` beyond S6 | coding-standards, frontend, upgrade, deploy, docs skills | S1 | verified | 2026-09-22 |
| Built-in `/review`, `/review-bugbot`, `/review-security` exist | code-review, security-review skills | S1 | verified | 2026-09-22 |
| Hooks fail-open by default; exit 2 denies; `failClosed: true` available | hooks/ | S4 | verified | 2026-09-22 |
| `beforeShellExecution`, `beforeReadFile`, `beforeMCPExecution`, `afterFileEdit` are documented events | hooks candidates | S4 | verified | 2026-09-22 |
| Team marketplace Default Off / Default On / Required | ADOPTION.md | S2 | verified | 2026-09-22 |
| Teams plan: 1 team marketplace; Enterprise: unlimited | ADOPTION.md | S2 | verified | 2026-09-22 |
| `.cursorignore` does not block terminal/MCP | templates, ADOPTION | S5 | verified | 2026-09-22 |
| Default ignore list includes `composer.lock` | project templates, R4 | S5 | verified | 2026-09-22 |
| Gitignored paths ignored by default; re-include with `!` in `.cursorignore` | vendor probe | S5 | verified | 2026-09-22 |
| Negation fails if parent dir excluded with `*` | vendor probe notes | S5 | verified | 2026-09-22 |
| `.cursorindexingignore` exists (indexing-only exclude) | templates | Cursor forum + ignore docs cross-ref | verified | 2026-09-22 |
| UCT applies to on-prem and Cloud, not ACCS/ACO | upgrade skill | S16 | verified | 2026-09-22 |
| Adobe four-phase protocol (requirements, plan approval, code, docs/validation) | core rule R10 | S11b | verified | 2026-09-22 |
| ACCS/SaaS: no Luma; out-of-process extensibility; predefined webhooks/events | platform detection, integration | S19 | verified | 2026-09-22 |
| SRI via Magento_Csp on payment pages for listed patch/release lines | frontend, security | S17 | verified | 2026-09-22 |
| Adobe Commerce 2.4.9: PHPUnit 12 Composer dependency | testing skill | S14 | verified | 2026-09-22 |
| Adobe Commerce 2.4.9: PHP 8.5 fully supported; 8.4 upgrade-only not recommended for production; 8.2/8.3 removed | upgrade skill must read live notes | S14 | verified | 2026-09-22 |
| Prompt claim "2.4.7+ ships SRI" | 7.5 | S17 lists 2.4.4-p9, 2.4.5-p8, 2.4.6-p6, 2.4.7, 2.4.8+ | verified (prompt imprecise; use Adobe version list) | 2026-09-22 |
| Prompt S14 vs S21 PHP production disagreement | upgrade skill | Prefer S14 official notes over third-party S21 | verified (follow Adobe) | 2026-09-22 |
| Testing guide URL without trailing slash | docs | 404 without `/`; works with `/` | verified (prompt URL needs trailing slash) | 2026-09-22 |
| Adobe skill names collide with `adobe-commerce-*` | naming | Adobe kit uses commerce/App Builder oriented names; npm README does not list exact skill folder names in fetch | open | 2026-09-22 |
| `dev:di:info`, `module:status` exist for all project versions | feature-analysis | [TK-verify] | open | 2026-09-22 |
| PHPUnit 12 dropped doc-comment annotation metadata | testing | [TK-verify]; 2.4.9 moved to PHPUnit 12 confirmed; annotation drop needs PHPUnit 12 changelog | open | 2026-09-22 |
| PCI DSS 6.4.3 / 11.6.1 script inventory | frontend/security | third-party summary only in prompt | open (confirm with QSA / do not hard-code IDs without primary source) | 2026-09-22 |
| Platform signal paths (`.magento.app.yaml`, Hyva, DDEV, etc.) | detect-platform.sh | [TK-verify] each signal | open (verify per signal in Phase 1) | 2026-09-22 |
| Agent can read `vendor/magento` when gitignored | R4/R5, templates | S5 + vendor-probe-git | verified (terminal **yes**; direct Read/@ expected-weak / Agent workspace probe inconclusive this session) | 2026-09-22 |
| Agent can read `composer.lock` despite default ignore | R4, version detection | S5 + vendor-probe-git | verified (terminal **yes**; direct Read/@ expected-weak / Agent workspace probe inconclusive) | 2026-09-22 |
| Cursor clarifying-question capability ("Ask questions") | core rule 8 | https://cursor.com/docs/agent/overview | verified (capability); exact id `AskQuestion` = open (community-sourced only) | 2026-09-22 |

## Prompt vs source disagreements

1. **SRI introduction versions:** Prompt says "2.4.7+". Adobe SRI docs list earlier patched lines (2.4.4-p9, 2.4.5-p8, 2.4.6-p6) plus 2.4.7+. Skills must say "check Magento_Csp / release notes for this project's version", not hard-code 2.4.7+.
2. **Testing guide URL:** Prompt S15 without trailing slash 404s; use `https://developer.adobe.com/commerce/testing/guide/`.
3. **PHPUnit major for "latest":** Official unit testing CLI page still cites PHPUnit 10.x for 2.4.8-era docs; 2.4.9 release notes say PHPUnit 12. Skills must read `composer.lock` (once readable) for the project's major.
4. **Built-in skill inventory:** Prompt noted Cursor skill names change; current S1 list differs from older copies. Skills that call built-ins should say "when available" and name `/review`, `/review-bugbot`, `/review-security` as of 2026-09-22.
5. **Adobe GitHub for commerce-extensibility-tools:** npm points at `adobe-commerce/commerce-extensibility-tools`; direct GitHub fetch returned 404 (private or moved). Coverage-gap audit in Phase 1 must use installed tools locally if public clone fails.

## Coverage gap vs Adobe official AI tooling (preliminary)

Adobe covers (do not re-implement):

- App Builder / integration-starter-kit / checkout-starter-kit workflows and four-phase protocol
- Commerce extensibility MCP (deploy, events, doc search)
- Edge Delivery / boilerplate storefront skills + dropins MCP + Wayfinder

Our pack should cover:

- PHP in-process modules on PaaS/on-prem (plugins, observers, declarative schema, GraphQL resolvers, adminhtml)
- Platform-model detection including ACCS/ACO "do not propose in-process PHP"
- Team conventions, depth ladder, evidence tags, honest validation
- Cross-cutting review: security, performance, code review, debugging, upgrade/UCT, deploy for Cloud PaaS
- Project facts / AGENTS.md overlays

Phase 1 will list exact Adobe skill folder names from a local `aio commerce extensibility tools-setup` install when available.

## Phase 1 build notes (2026-09-22)

- Core rule line count: 25 body+frontmatter lines in file (under 40-line guidance for always-on ideas).
- Skills 7.1–7.4 authored; SKILL.md bodies under 150 lines.
- `detect-platform.sh` verified against cloud-shaped, plain-store, app-builder, edge-delivery, accs-shaped fixtures.
- `install.sh` dry-run succeeded (rsync present; cp fallback patched).
- Manual eval cases written; **human scoring still required** per `evals/run.md` (not executed as Agent runs in this session).
- Vendor/`composer.lock` probe: fixture prepared at `evals/fixtures/vendor-probe`. Agent in this pack repo can read those files via absolute path, but that does **not** prove Cursor indexing/`gitignore` behavior when the fixture is the workspace root. **Action for pilot:** open `evals/fixtures/vendor-probe` as workspace and run the prompt in `evals/run.md`; record yes/no below.

### Vendor probe results (superseded by 0.5.0 Task 1)

| Check | Result | Date | Notes |
|-------|--------|------|-------|
| Read `vendor/magento/framework/ProbeInterface.php` with fixture as workspace | inconclusive (Agent) / expected-weak (S5) | 2026-09-22 | Use `vendor-probe-git`; see Task 1 table |
| Read `composer.lock` with fixture as workspace | inconclusive (Agent) / expected-weak (S5) | 2026-09-22 | Terminal route confirmed yes |
| Terminal `detect-stack.sh` / `cat` | **yes** | 2026-09-22 | Blocking path for R4/R5 |

## 0.2.0 productivity review (2026-09-22)

Findings against the goal "high productivity for the Adobe engineering team":

| Finding | Action taken |
|---------|--------------|
| Pack covered only understand/plan/standards — none of the daily work (build, debug, test, review) | Added module-scaffold, debugging, testing, code-review, security-review, frontend-and-tracking, integration-work |
| No generative acceleration; skills mostly stated prohibitions | Added `references/file-sets.md` (minimum correct file set per extension point) and `references/log-and-state-map.md` (symptom to first-check) |
| Core rule risked verbose ceremony on small tasks | Rule now leads with "do the work, do not narrate it"; L0/L1 must stay two to four lines with no headings |
| `AGENTS.md` template was all placeholders, so the agent had to rediscover commands every session | Template now ships concrete default commands used verbatim, plus a do-not-touch section |
| `detect-platform.sh` reported the ACCS fixture as App Builder | Script now emits in-process vs out-of-process hints; retested against all five fixtures |
| No overlap audit procedure for a larger skill set | Added overlap audit table to `evals/run.md` |

Validation run this session:

- `Ran: python3 frontmatter/reference validator over skills/*/SKILL.md -> 11/11 valid (name matches folder, description <=1024 chars, bodies 50-70 lines, all reference links resolve)`
- `Ran: bash detect-platform.sh on 5 fixtures -> correct signals and hints for each`
- `Ran: ./install.sh into a temp dir (0.1.0) -> skills, rule, and templates created`
- `Not run: agent-level eval cases, because scoring requires human runs per evals/run.md`
- `Not run: phpcs/phpunit, because this repo contains no PHP application (fixtures are synthetic stubs)`

Still open before wide rollout: vendor/`composer.lock` readability probe, manual eval scoring, Phase 3 skills and hooks.

## Open items closed 2026-09-22 (agent-verified, no human input needed)

| Claim | Result | Source |
|-------|--------|--------|
| PHPUnit 12 dropped doc-comment annotation metadata | VERIFIED. Deprecated in PHPUnit 11, removed in 12; PHP 8 attributes required. Relevant because Adobe Commerce 2.4.9 depends on PHPUnit 12. | https://phpunit.de/announcements/phpunit-12.html ; https://phpunit.de/announcements/phpunit-11.html |
| `bin/magento dev:di:info` exists | VERIFIED. Signature `bin/magento dev:di:info <class> [<area>]`; shows preference, constructor params, plugins. | https://experienceleague.adobe.com/en/docs/commerce-operations/tools/cli-reference/commerce-on-premises ; https://developer.adobe.com/commerce/php/development/build/dependency-injection-file |
| Cursor clarifying-question capability | VERIFIED in primary docs as **"Ask questions"** (Agent overview). Exact tool id `AskQuestion` remains community-sourced only; core rule 8 soft-matches and chat-falls-back (no hard-fail on name). | https://cursor.com/docs/agent/overview ; forum for id only |

### Vendor probe status

**Closed for rollout (0.5.0 Task 1):** throwaway git fixture `evals/fixtures/vendor-probe-git` proves `vendor/` + `composer.lock` are gitignored while present on disk. Terminal route (`detect-stack.sh`, `cat`) = **yes**. Direct Agent workspace-root Read/@ = inconclusive this session (CLI auth); treated as expected-weak per Cursor ignore docs. Skills/rules prefer terminal first.

## Team answers recorded 2026-09-22 (and what changed because of them)

| Question | Answer | Effect on the pack |
|----------|--------|--------------------|
| Commerce versions in portfolio | Varies; skills must read `composer.lock` every time | No version numbers hard-coded in skill bodies. Added `scripts/detect-stack.sh` to read commerce package, framework version, PHP constraint, PHPUnit major and tooling from composer metadata via the shell |
| Platform models delivered | All of: Cloud PaaS, on-premises, ACCS, ACO, App Builder, Edge Delivery | Kept full platform detection and the ACCS/ACO out-of-process guard |
| Frontend stacks | Luma/Blank, Hyva, headless/PWA, Edge Delivery | Kept all four in frontend stack detection; no default assumption |
| Local stack | DDEV and native | Core rule and skills now require `ddev exec` prefixing when `.ddev/` exists; `AGENTS.md` has an explicit command-prefix field |
| `vendor/` gitignored but installed locally | Confirmed | Core rule now directs the agent to read `composer.lock` and `vendor/magento/*` through the terminal, which ignore files do not block. This removes the dependency on `.cursorignore` negation patterns that Cursor documents as unreliable for nested paths |
| Lint / test setup | None standard today | Testing skill gained an explicit no-harness path plus `references/bootstrap-quality-tooling.md`; coding-standards skill must state when no linter exists rather than implying enforcement; `AGENTS.md` uses `NOT SET UP` instead of placeholder commands |
| Azure DevOps MCP | Unsure whether installed | Kept optional: use the MCP if present, else pasted work item text |

### Consequence for the vendor probe

Because `vendor/` is gitignored but present locally, the terminal route makes the probe non-blocking: version facts are obtainable via `detect-stack.sh` regardless of whether Cursor's file tools can open those paths. The probe is still worth running once to know whether `@`-mention and file-read access also work, but it no longer gates rollout.

## Phase 3 scoped delivery (0.4.0) — 2026-09-22

Delivered per user request (not full original Phase 3 catalog):

| Item | Status |
|------|--------|
| adobe-commerce-performance-review | shipped |
| adobe-commerce-project-documentation (slash-only) | shipped |
| Guardrail hooks (block vendor/ writes) | shipped + unit-tested via stdin JSON |
| upgrade-and-patching | shipped 0.5.0 (slash-only) |
| deploy-and-environments | shipped 0.5.0 (slash-only) |

Hook unit checks (this session):

- `Ran: echo Write vendor path | block-protected-writes.py -> deny`
- `Ran: echo Write app/code path | block-protected-writes.py -> allow`
- `Ran: echo rm vendor | block-protected-shell.py -> deny`
- `Ran: echo git push --force | block-protected-shell.py -> ask`
- `Ran: echo cat vendor | block-protected-shell.py -> allow`

## 0.5.0 Task 1 — Vendor / composer.lock probe (2026-09-22)

### Fixture

Created `evals/fixtures/vendor-probe-git/`: real `git init`, tracks only `.gitignore`, `.cursorignore`, `composer.json`, `README.md`. On disk (untracked): `vendor/magento/framework/ProbeInterface.php`, `composer.lock`. Confirmed with `git check-ignore -v`.

### Results

| Check | Result | Date | Notes |
|-------|--------|------|-------|
| Read `vendor/magento/framework/ProbeInterface.php` with fixture as Agent workspace root | **inconclusive this session** | 2026-09-22 | `move_agent_to_root` aborted; `agent -p --workspace <fixture>` failed: Authentication required (no CURSOR_API_KEY; print mode not authenticated despite `agent status` showing login). Human can finish in 1 minute via fixture README. Per Cursor S5, direct Read/@ of gitignored paths is expected to fail when fixture is workspace root. |
| Read `composer.lock` with fixture as Agent workspace root | **inconclusive this session** | 2026-09-22 | Same auth/workspace blocker. Cursor default-ignore list includes `composer.lock` (S5). |
| Terminal `detect-stack.sh` on fixture | **yes** | 2026-09-22 | `framework-version: magento/framework@103.0.7 (composer.lock)`; `vendor-installed: vendor/magento` |
| Terminal `cat` of ProbeInterface.php | **yes** | 2026-09-22 | Prints `interface ProbeInterface` |
| Parent-workspace Read of fixture paths | yes (not valid for probe) | 2026-09-22 | Pack root is not a git repo, so parent Read success does **not** count |

### Design consequence (applied)

Terminal route is **CONFIRMED**. Direct file-tool route remains expected-weak per S5. Updated `rules/adobe-commerce-core.mdc` rule 5 and `README.md` to say: prefer terminal first; do not retry `@vendor/...` before shell. Skills that need versions keep using `detect-stack.sh`.

### Stop condition

Terminal route did **not** fail — continue hardening. Live Agent workspace-root Read still needs human open of `vendor-probe-git` or a working `CURSOR_API_KEY` for `agent -p`.

## 0.5.0 Task 3 — Deferred skills decision (before writing files)

**Choice: Option A — build thin v1s now.**

Why: CoE delivery models already include Cloud/PaaS and on-premises (team answers 2026-09-22). Upgrades and Cloud/App Builder deploy are high-risk L3 work developers will ask Cursor about; leaving them silent sends people into production-adjacent commands without a skill guard. Both skills get `disable-model-invocation: true` so they never auto-fire.

ACCS/ACO: UCT and classic Cloud deploy hooks do not apply — skills must detect platform and refuse those paths.

## 0.5.0 Task 2 — Eval scoring

Live Agent trigger scoring **blocked**: `agent -p` requires authentication not available in this session (`CURSOR_API_KEY` unset; print mode rejected despite IDE login). Raw sheet + method in `evals/results-0.5.0.md`. Headline ≥90% / ≤10% **not computed from live runs** — do not invent numbers.

Executed instead: fixed YAML parse errors in four `cases.yaml` files (debugging, integration-work, project-understanding, coding-standards/feature-analysis quote lines); **70 cases** parse (51 pos / 19 neg); description-level overlap audit with three description tightenings (project-understanding, feature-analysis, coding-standards); frontmatter validation for 15 skills. Live headline % still blocked — see `evals/results-0.5.0.md`.

## 0.5.0 Task 3 — Deferred skills

Option **A** shipped: `adobe-commerce-upgrade-and-patching`, `adobe-commerce-deploy-and-environments` (both `disable-model-invocation: true`) + eval cases including ACCS no-UCT and refuse-production.

## 0.5.0 Task 4 — AskQuestion sourcing

Primary Cursor docs (`https://cursor.com/docs/agent/overview`) document the capability as **"Ask questions"** (clarifying questions during a task). Exact tool id `AskQuestion` remains **community/forum-sourced**, not named in that primary page. Status changed to: capability verified (primary doc); identifier `AskQuestion` = open/community. Core rule 8 and README updated to prefer the host clarifying-question tool without hard-failing on the name.

## 0.5.0 Task 5 — Shell-hook caveat visibility

Added plain-language caveat to `docs/TEAM_USER_GUIDE.md` Guardrails section: shell guard blocks obvious commands, can be bypassed by obfuscation; safety net ≠ CI/review. Hook `failClosed: false` unchanged.

## 0.5.1 — Skills hardening (2026-09-22)

Closed the three CoE follow-ups from the skills gap review:

1. **ACCS + DDEV** lines on skills that lacked them.
2. **CSP ownership** (security vs frontend) and **scaffold vs coding-standards** description/path split.
3. **Thin domain skills:** `adobe-commerce-b2b`, `adobe-commerce-inventory-msi`, `adobe-commerce-checkout`.

Pack version **0.5.1**; 18 skills; 82 eval cases authored (live trigger scoring still pending auth).

Docs aligned same day: ADOPTION, TEAM_USER_GUIDE, SOURCES, evals/run.md, results-0.5.1.md, AGENTS template exemplars for B2B/MSI/checkout.


