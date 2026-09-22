---
name: adobe-commerce-frontend-and-tracking
description: >
  Guides Adobe Commerce storefront and admin front-end work (templates, layout
  XML, LESS/CSS, RequireJS, Knockout and UI components, Alpine and Hyva, Edge
  Delivery blocks and drop-ins) and analytics or tracking work (dataLayer, Google
  Tag Manager, GA4, Adobe Analytics), including consent gating and the CSP and
  SRI constraints that apply on checkout and payment pages. Use for any template,
  style, storefront JavaScript, or tracking change. Do not use for backend-only
  logic, and defer Edge Delivery drop-in internals to Adobe's official skills
  when they are installed.
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
  version: "0.5.0"
  verified-against: "Adobe SRI/CSP docs 2026-09-22; Adobe storefront AI tooling 2026-09-22"
---

# Frontend and tracking

## Procedure

1. Detect the stack before editing: Luma/Blank, Hyva, headless/PWA, or Edge Delivery. Check `app/design`, theme `composer.json`, `blocks/`, and `scripts/initializers/`. Never assume Luma.
2. For Edge Delivery drop-ins, prefer Adobe's boilerplate skills and dropins MCP when installed; follow their conventions instead of Magento theme patterns.
3. Change at the right layer:
   - Content and markup: template in the project's own theme, never in `vendor/`
   - Structure and block wiring: layout XML in the theme or module
   - Styles: the project's LESS/CSS entry points and variables, not inline styles
   - Behaviour: the project's existing JS pattern (RequireJS module, UI component, Alpine component) — mirror an existing file
4. Respect theme fallback: override the narrowest scope that achieves the result.

## Tracking

1. Trace only the layers that exist. Establish which are present before proposing a change: event source in code, `dataLayer` push, tag manager container, and the destination tool.
2. Do not assume GTM, GA4, or Adobe Analytics exist. Find the container or the script include first.
3. Check for duplicate firing (server-rendered push plus JS push), correct event naming against the project's existing schema, and that values are typed consistently.
4. Check consent gating: if the project has a consent mechanism, tracking must respect it. Say so explicitly when no consent layer exists.

## CSP and SRI on checkout and payment

Any script added to checkout or payment pages, including analytics and tag managers, must be checked against `Magento_Csp` policy and the project's whitelist and nonce mechanism. Adobe's guidance is not to disable CSP or remove SRI in production; a bypass is a last-resort hotfix needing security review. Verify the behaviour of the installed version rather than assuming a release line. Escalate script-inventory and integrity obligations under PCI DSS 4.0 to the client's QSA rather than asserting requirement numbers.

## Validation

Name a concrete browser checklist (pages, viewports, logged-in and guest where relevant), and run the project's existing front-end or E2E tests that cover the area. Report honestly what was and was not executed.

## Must not

- Edit files under `vendor/`, `generated/`, or `pub/static/`.
- Add inline styles or scripts where the project has a pattern for them.
- Introduce a tracking library the project does not already use without asking.
- Claim a visual result without having viewed it, unless the developer will verify.
