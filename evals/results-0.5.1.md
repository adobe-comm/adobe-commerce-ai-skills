# Eval results — pack 0.5.1

Date: 2026-09-22  
Prior sheet: `evals/results-0.5.0.md` (live Agent scoring blocked; 70 cases / 15 skills at that cut).

## Headline targets

| Metric | Target | Actual (0.5.1) |
|--------|--------|----------------|
| Correct trigger on positive cases | ≥90% | **Not measured** (same CLI auth blocker as 0.5.0) |
| False trigger on negative cases | ≤10% | **Not measured** |
| Zero unrun-test claims | 0 | N/A until live runs |
| L0/L1 stay compact | yes | N/A until live runs |

## Case inventory (0.5.1)

**82 cases** across **18** skills after domain-skill addition.

| Skill | Cases |
|-------|------:|
| adobe-commerce-b2b | 4 |
| adobe-commerce-checkout | 4 |
| adobe-commerce-code-review | 4 |
| adobe-commerce-coding-standards | 4 |
| adobe-commerce-debugging | 4 |
| adobe-commerce-deploy-and-environments | 4 |
| adobe-commerce-feature-analysis | 5 |
| adobe-commerce-frontend-and-tracking | 5 |
| adobe-commerce-integration-work | 5 |
| adobe-commerce-inventory-msi | 4 |
| adobe-commerce-module-scaffold | 5 |
| adobe-commerce-performance-review | 4 |
| adobe-commerce-project-documentation | 4 |
| adobe-commerce-project-understanding | 8 |
| adobe-commerce-requirement-planning | 5 |
| adobe-commerce-security-review | 4 |
| adobe-commerce-testing | 5 |
| adobe-commerce-upgrade-and-patching | 4 |
| **Total** | **82** |

## What changed vs 0.5.0 (docs / skills, not live scores)

- ACCS + DDEV guards on previously missing skills
- CSP ownership split (security-review vs frontend-and-tracking)
- Scaffold vs coding-standards description/path split
- New skills: b2b, inventory-msi, checkout (+ ACCS refuse / negative cases)

## Scoring sheet (live — still incomplete)

| case_id | pack_on | skill_triggered | outcomes_hit | forbidden_hit | depth_ok | notes |
|---------|---------|-----------------|--------------|---------------|----------|-------|
| *(all)* | Y/N | **BLOCKED** | — | — | — | Fill after `agent login` / `CURSOR_API_KEY` |

## Overlap audit additions (description-level, 0.5.1)

See `evals/run.md` pairs involving b2b / inventory-msi / checkout / CSP. Live selection still required.
