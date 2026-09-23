---
name: adobe-commerce-frontend-and-tracking
description: >
  Guides Adobe Commerce storefront and admin front-end work (templates, layout
  XML, LESS/CSS, RequireJS, Knockout and UI components, Alpine and Hyva, Edge
  Delivery blocks) and analytics wiring (dataLayer, GTM, GA4, Adobe Analytics)
  including consent gating. Implements scripts against the project's existing
  CSP/SRI policy; escalate CSP/SRI *bypass or exception* requests to
  security-review. Use for template, style (including L0 button/spacing/CSS),
  storefront JS, or tracking changes. Do not use for backend-only PHP logic,
  coding-standards-only refactors, full security audits, checkout quote/totals/
  place-order logic (checkout), or CSP *violation incidents* framed as "broken /
  quickest fix" root-cause (debugging — never disable CSP). Defer Edge Delivery
  drop-in internals to Adobe skills when installed.
paths:
  - "**/view/**"
  - "**/*.phtml"
  - "**/*.less"
  - "**/*.css"
  - "**/*.js"
  - "**/layout/*.xml"
  - "blocks/**"
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.2"
  verified-against: "Adobe SRI/CSP docs 2026-09-22; Adobe storefront AI tooling 2026-09-22"
---

# Frontend and tracking

## When to use / skip

Use: templates, layout, theme CSS/JS, Hyva/Alpine, dataLayer/GTM/GA4 wiring.
Skip: backend-only; new Magento module scaffold; CSP bypass *approval* (security-review); ACCS Luma theme work that does not exist — use headless/EDS/Adobe skills.

## Procedure

1. **Platform:** on ACCS/ACO, do not assume Luma/Blank PHP themes; prefer headless/EDS/drop-ins and Adobe official skills. On PaaS/on-prem, detect stack before editing: Luma/Blank, Hyva, headless/PWA, or Edge Delivery (`app/design`, theme `composer.json`, `blocks/`, `scripts/initializers/`). Never assume Luma.
2. For Edge Delivery drop-ins, prefer Adobe's boilerplate skills and dropins MCP when installed.
3. Change at the right layer:
   - Content and markup: template in the project's own theme, never in `vendor/`
   - Structure and block wiring: layout XML in the theme or module
   - Styles: the project's LESS/CSS entry points and variables, not inline styles
   - Behaviour: the project's existing JS pattern (RequireJS, UI component, Alpine) — mirror an existing file
4. Respect theme fallback: override the narrowest scope that achieves the result.
5. Prefix Magento/static commands with `ddev exec` when `.ddev/` exists. Never target production.

## Tracking

1. Trace only the layers that exist: event source, `dataLayer` push, tag manager container, destination tool.
2. Do not assume GTM, GA4, or Adobe Analytics exist. Find the container or script include first.
3. Check duplicate firing, event naming vs project schema, typed values, and consent gating.

## CSP and SRI (implementation vs review)

- **This skill:** wire scripts/tags to comply with existing `Magento_Csp` whitelist/nonce; verify installed behaviour via terminal.
- **security-review:** owns approving any CSP/SRI bypass or payment-page exception.
- Do not disable CSP or strip SRI. Do not invent PCI requirement numbers — escalate to the client's QSA via security-review wording.

## Validation

Name a concrete browser checklist; run existing front-end/E2E tests when present. Report honestly what ran.

## Must not

- Edit `vendor/`, `generated/`, or `pub/static/`.
- Add inline styles/scripts where the project has a pattern.
- Introduce a tracking library the project does not use without asking.
- Approve security bypasses (hand to security-review).
