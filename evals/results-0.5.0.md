# Eval results — pack 0.5.0

Date: 2026-09-22  
Scorer environment: Cursor Agent authoring session for the pack  
Method note: **Live Cursor skill-selection scoring against fixtures was blocked** in this environment (`agent -p --workspace <fixture>` → `Authentication required` / no `CURSOR_API_KEY`; `move_agent_to_root` aborted). Per `evals/run.md`, authoritative scoring needs clean chats with the pack installed on each fixture. This file records what *was* executed, what was blocked, description-level overlap audit, and the raw sheet template for completing live scores.

## Headline targets (from evals/run.md)

| Metric | Target | Actual (this run) |
|--------|--------|-------------------|
| Correct trigger on positive cases | ≥90% | **Not measured (live Agent trigger blocked)** |
| False trigger on negative cases | ≤10% | **Not measured (live Agent trigger blocked)** |
| Zero unrun-test claims | 0 | N/A this run |
| L0/L1 stay compact | yes | N/A this run |

**To finish live scoring:** run `agent login` (or set `CURSOR_API_KEY`), then for each row below open the fixture as workspace with pack installed / `--plugin-dir` pointing at this pack, one clean chat per case, fill the sheet.

## Case inventory

**70 cases** across 15 skills (51 positive / 19 negative) after YAML fixes.

Fixed YAML parse errors (unquoted strings containing `"`) in: coding-standards, debugging, integration-work, project-understanding, feature-analysis.

| Skill | Cases |
|-------|------:|
| adobe-commerce-code-review | 4 |
| adobe-commerce-coding-standards | 4 |
| adobe-commerce-debugging | 4 |
| adobe-commerce-deploy-and-environments | 4 |
| adobe-commerce-feature-analysis | 5 |
| adobe-commerce-frontend-and-tracking | 5 |
| adobe-commerce-integration-work | 5 |
| adobe-commerce-module-scaffold | 5 |
| adobe-commerce-performance-review | 4 |
| adobe-commerce-project-documentation | 4 |
| adobe-commerce-project-understanding | 8 |
| adobe-commerce-requirement-planning | 5 |
| adobe-commerce-security-review | 4 |
| adobe-commerce-testing | 5 |
| adobe-commerce-upgrade-and-patching | 4 |
| **Total** | **70** (51 pos / 19 neg) |

## Scoring sheet (live — incomplete)

Format from `evals/run.md`:

| case_id | pack_on | skill_triggered | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------|-----------------|--------------|---------------|----------|-------|

### Pack-on runs

| case_id | pack_on | skill_triggered | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------|-----------------|--------------|---------------|----------|-------|
| *(all cases)* | Y | **BLOCKED** | — | — | — | `agent -p` auth failure; no clean-chat Agent run this session |

### Baseline (pack off)

| case_id | pack_on | skill_triggered | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------|-----------------|--------------|---------------|----------|-------|
| *(positive cases)* | N | **BLOCKED** | — | — | — | Same auth blocker |

### Non-Agent checks executed this session

| Check | Result |
|-------|--------|
| `detect-stack.sh` on vendor-probe-git | PASS — reads magento/framework@103.0.7 from gitignored lock |
| `detect-platform.sh` on 5 fixtures | PASS (prior) |
| Hook unit tests (stdin JSON) | PASS (prior 0.4.0) |
| All SKILL.md frontmatter valid after 0.5.0 bumps | PASS |
| coding-standards cases.yaml parses | PASS after quote fix |

## Overlap audit (description-level; not live trigger)

Ambiguous prompts were scored by reading both skills' descriptions and recording the **intended** winner. Live Cursor selection still required.

| Pair | Ambiguous prompts (2–3) | Intended winner | Description change this release |
|------|-------------------------|-----------------|----------------------------------|
| project-understanding vs feature-analysis | (1) How is the project structured? (2) Trace ERP export path before fix (3) Map integrations only | (1) understanding (2) feature-analysis (3) understanding | **Tightened** both: understanding excludes end-to-end feature traces; feature-analysis excludes whole-repo maps and PR review |
| module-scaffold vs coding-standards | (1) Add a new plugin on Cart (2) Fix style in existing Validator.php (3) Mirror crontab XML style | (1) scaffold (2) standards (3) standards (or scaffold if new job) | **Tightened** coding-standards: do not use to scaffold brand-new extension points |
| debugging vs code-review | (1) Orders stopped syncing — find root cause (2) Review my uncommitted changes (3) Customer reports 500 we cannot reproduce | (1) debugging (2) code-review (3) debugging | No change — negatives already explicit |
| code-review vs security-review | (1) Review PR for merge readiness (2) Admin page lists customer emails — security? (3) Add analytics script to payment page | (1) code-review (2) security-review (3) security-review | No change |
| frontend-and-tracking vs feature-analysis | (1) GA4 purchase missing (2) Trace checkout place-order PHP path (3) Button margin L0 | (1) frontend (2) feature-analysis (3) frontend L0 / neither analysis | No change |
| integration-work vs module-scaffold | (1) Add discount field to ERP payload contract (2) Create CLI to replay one order (3) Document ERP integrations on plain-store | (1) integration (2) scaffold (3) integration | No change |

## Vendor probe (cross-ref Task 1)

See `docs/VERIFICATION.md` 0.5.0 Task 1. Terminal route **yes**. Direct Agent workspace-root Read **inconclusive** (auth).

## Follow-up for CoE QA

1. `export CURSOR_API_KEY=...` or `agent login` until `agent -p` works.
2. For each case: `agent -p --trust --workspace evals/fixtures/<fixture> --plugin-dir <pack-root> "<prompt>"`
3. Fill this sheet; recompute headline %.
4. Open `evals/fixtures/vendor-probe-git` alone and complete the direct Read row.
