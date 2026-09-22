---
name: adobe-commerce-project-documentation
description: >
  Creates or updates durable Adobe Commerce project knowledge for future
  developers: architecture decision records, integration behavior and data-flow
  notes, business rules, troubleshooting notes, and the project facts file. Use
  when a significant decision or non-obvious rule needs recording, after a
  non-trivial integration or platform choice, or when explicitly invoked with
  /adobe-commerce-project-documentation. Do not use for routine feature changes
  or to generate bulk prose that restates the code.
disable-model-invocation: true
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "CoE project-facts protocol; AGENTS.md studies guidance 2026-09-22"
---

# Project documentation

Document only what code cannot show. Short. Human-reviewed diffs only.

## When to use / skip

Use: explicit invoke; ADR for a real decision; integration behaviour that is not obvious from XML/PHP alone; do-not-touch notes; project-facts updates.
Skip: routine PRs; restating README; auto-generating architecture novels.

## Procedure

1. Confirm with the developer what must be durable (decision, quirk, authoritative system, runbook step). Do not invent scope.
2. Prefer updating an existing doc over creating a new file. Default homes:
   - Decisions: `docs/adr/NNN-title.md` (create `docs/adr/` if the project uses docs this way)
   - Integrations / data flow: `docs/integrations/<name>.md` or the path the project already uses
   - Facts the code cannot show: `docs/ai/project-facts.md` (max 100 lines; evidence path + verified date/commit; no secrets)
3. Every new or changed doc must carry `verified: <YYYY-MM-DD or commit SHA>` near the top.
4. Reference code paths; do not paste large code blocks.
5. Deliver as a **diff for human review** — never bulk-merge generated prose without the developer accepting it.

## ADR template

```markdown
# ADR-NNN: <title>

verified: YYYY-MM-DD

## Context
<what force required a decision>

## Decision
<what we chose>

## Alternatives considered
- <option> — why not

## Consequences
- <positive / negative / follow-ups>
```

## Project facts line format

```
- <fact> — evidence: <path> — verified: YYYY-MM-DD
```

## Must not

- Store credentials, tokens, personal data, or production hostnames with login detail.
- Duplicate Magento core behaviour that any engineer can read in vendor.
- Write docs for L0/L1 routine changes.
- Mark INFERRED claims as verified.

## Output

Paths changed, one-line purpose each, and a reminder that a human must accept the diff before it is treated as project truth.
