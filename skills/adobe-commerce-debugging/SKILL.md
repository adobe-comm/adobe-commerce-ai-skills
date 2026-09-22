---
name: adobe-commerce-debugging
description: >
  Drives root-cause analysis for Adobe Commerce defects and incidents: reproduces
  the problem, gathers evidence from logs and state (exception, system and debug
  logs, cron and queue state, indexer status, cache, config, DI and plugin
  wiring), tests hypotheses, then proposes the smallest fix plus a regression
  test. Use for bugs, "it worked before", failed syncs, admin or checkout errors,
  blank or 500 pages, stale data, and incident analysis from provided logs. Do
  not use for new feature development, pure code review, or ERP/OMS contract
  design (integration-work once root cause is an external boundary).
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.1"
  verified-against: "Magento 2.4.x CLI surface; Adobe debugging guidance 2026-09-22"
---

# Debugging

Root cause before change. One hypothesis at a time.

## When to use / skip

Use: defect, incident, unexplained behaviour, failing sync or job.
Skip: greenfield features; reviewing someone else's diff (use code-review); ACCS/ACO in-process PHP "fixes" that should be out-of-process.

## Procedure

1. Establish the exact symptom: what happens, where (storefront/admin/API/CLI/cron), for whom, since when, and how reproducible.
2. Reproduce locally or in dev. If you cannot reproduce, say so and work from artefacts the developer provides. Never connect to production. Prefix Magento CLI with `ddev exec` when `.ddev/` exists.
3. **Platform:** on ACCS/ACO, prefer SaaS logs/events/App Builder traces over inventing Magento PHP plugins as the fix.
4. Gather evidence before theorising — see [references/log-and-state-map.md](references/log-and-state-map.md) for where to look per symptom class. If the boundary is ERP/OMS/webhook, continue RCA here then hand contract changes to integration-work.
5. Write a short hypothesis table; test the cheapest discriminating check first:

   | # | Hypothesis | Discriminating check | Result |
   |---|-----------|----------------------|--------|

6. Narrow to the responsible layer (config, DI/plugin order, data, cache/index, third-party module, infrastructure) with a file path or command output as proof.
7. Propose the smallest fix at the correct layer. Do not fix symptoms in templates when the cause is in a service.
8. Add or name a regression test that would have caught it.
9. If the root cause stays unknown, state what is known, what was ruled out, and the next diagnostic step. Do not ship a speculative fix silently.

## Must not

- Change code before the cause is identified.
- Disable cache, CSP, SRI, or validation to make a symptom disappear.
- Clear or truncate data as a "fix".
- Claim a fix is verified without running the reproduction again.

## Output

```
Symptom: ...
Reproduced: yes|no (how)
Evidence: <paths, log lines, command output>
Root cause: <CONFIRMED path/line> or Unknown — ruled out: ...
Fix: <smallest change>
Regression test: <name/location>
Validation: Ran: <cmd> -> <result>
```
