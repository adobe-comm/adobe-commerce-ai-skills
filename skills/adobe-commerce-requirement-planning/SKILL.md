---
name: adobe-commerce-requirement-planning
description: >
  Turns an Azure DevOps work item, bug report, user story, or client request into
  a proportionate Adobe Commerce implementation plan with acceptance criteria,
  affected areas, risks, and a validation approach. Use when the input is a
  ticket or requirement rather than a precise code instruction. Do not use for
  one-line edits, CSS/copy-only L0 work, or when Adobe App Builder protocol
  already owns planning.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "CoE depth ladder; Adobe four-phase protocol cross-ref 2026-09-22"
---

# Requirement planning

## When to use / skip

Use: Azure DevOps work item, bug, story, client ask needing a plan.
Skip: precise single-file instruction; L0 spacing/copy.

## Procedure

1. Obtain requirement text:
   - If an Azure DevOps MCP is installed and a work item id/URL is given, read it through that MCP.
   - Else use the pasted ticket/story text. Do not assume Jira.
2. Classify depth L0–L3 with a one-line justification (see core rule ladder). Escalate if investigation shows wider blast radius.
3. Extract acceptance criteria. Mark ambiguous criteria as OPEN — use AskQuestion (or one A/B/C chat question) only if the answer changes the plan.
4. Plan size by level:
   - L1: 3–6 bullets (files/pattern, test).
   - L2: files, DB/API impact, backward compatibility, rollout/config, validation.
   - L3: detailed plan; **stop for developer approval before coding**.
5. State what will **not** change.
6. For App Builder / ACCS SaaS / drop-in storefront work: defer to Adobe official skills and their four-phase protocol (requirements → approved plan → code → docs/validation). Do not invent a parallel protocol.
7. Name validation approach honestly (which tests exist in this project — discover, do not invent).

## Must not

- Produce an architecture report for a small change.
- Start L3 coding before explicit approval.
- Invent integrations or modules not in the repo.

## Output format

```
Depth: L# — <justification>
Acceptance criteria:
- ...
Open criteria: ...
Plan:
- ...
Out of scope: ...
Validation: ...
Risks: ...
Approval needed: yes|no
```
