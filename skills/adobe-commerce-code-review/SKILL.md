---
name: adobe-commerce-code-review
description: >
  Reviews a diff, branch, or pull request in an Adobe Commerce project as a
  senior reviewer: requirement fit, architecture and convention fit, correctness,
  risk introduced, test adequacy, and security or performance impact where
  relevant. Escalate dedicated security or performance deep-dives to
  security-review / performance-review when those surfaces dominate. Use when
  asked to review code, a PR, or merge readiness. Do not use to write new
  features or to run a full-repo security audit.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "Cursor built-in review skills 2026-09-22"
---

# Code review

Risk first. Real findings only.

## When to use / skip

Use: review this diff/branch/PR, merge readiness.
Skip: authoring features; full-repo security sweep (security-review); hot-path-only perf audit (performance-review).

## Procedure

1. Get the change set: `git diff`, branch comparison, or the PR. Establish the requirement (Azure DevOps work item via MCP if installed, else description).
2. **Platform:** flag ACCS/ACO diffs that introduce in-process PHP plugins as a finding unless out-of-process is impossible and justified.
3. Review in this order, stopping to note findings as you go:
   1. Does it satisfy the requirement, including edge cases named in the ticket?
   2. Blast radius: shared classes, checkout/payment/pricing, schema, public API, cache keys, cron/queue.
   3. Correctness: null/empty handling, store scope, multi-website, currency, timezone, batch limits.
   4. Convention fit against the project's own exemplars, not generic style.
   5. Test adequacy for the risk level (L0 smoke vs L3 suite).
   6. Security and performance only where the diff touches those surfaces — if findings cluster there, recommend security-review or performance-review rather than duplicating a full audit.
4. Verify claims in the PR description. If it says tests pass, check for evidence; unverified claims are a finding.
5. Skip anything phpcs, static analysis, or CI already enforces. Prefer `ddev exec` evidence when local commands are cited and `.ddev/` exists.
6. Use Cursor's built-in `/review` or `/review-bugbot` when available and merge results without duplicating findings.

## Severity

| Level | Meaning |
|-------|---------|
| BLOCKER | Breaks behaviour, data integrity, security, or the requirement |
| HIGH | Likely defect or serious maintainability/performance problem |
| MEDIUM | Should fix before merge; contained impact |
| LOW | Worth mentioning; author's discretion |

Report no findings when there are none. Do not pad.

## Must not

- Emit a generic checklist for every PR.
- Comment on formatting that tooling handles.
- Request architectural rewrites outside the change's scope without flagging that it is out of scope.
- Approve while validation evidence is missing — say what is missing instead.

## Output

```
Requirement fit: <met | gaps>
Risk: <highest-risk area in the diff>
Findings:
- BLOCKER <file:line> — why it matters — minimal fix
- ...
Tests: <adequate | what is missing>
Verdict: <ready | changes requested> (+ what is still unverified)
```
