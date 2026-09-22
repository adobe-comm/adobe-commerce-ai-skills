---
name: adobe-commerce-security-review
description: >
  Reviews Adobe Commerce changes for security risk in proportion to the change:
  admin and API authorization and ACL, input validation and output escaping,
  CSRF and form keys, SQL parameter binding, API and GraphQL exposure, secrets
  and personal-data handling, payment-page CSP and SRI constraints, third-party
  scripts, and new Composer or extension dependencies. Use for changes touching
  customer data, checkout or payment, admin controllers, APIs, integrations,
  file uploads, or injected third-party scripts. Do not use for CSS or copy-only
  edits, and do not produce generic OWASP summaries.
metadata:
  owner: brainvire-adobe-commerce-coe
  version: "0.5.0"
  verified-against: "Adobe SRI/CSP docs 2026-09-22; Magento_Csp behaviour"
---

# Security review

## When to depth-check

Trigger a full pass when the change touches any of: customer or order data, checkout or payment, admin controllers or routes, REST/SOAP/GraphQL surface, file upload or import, integration credentials, third-party or tag-manager scripts, ACL, or new dependencies.

Otherwise do a targeted check on the surfaces the diff actually touches.

## Checks

1. **Authorization**: every admin route, controller, and webapi method maps to an ACL resource. Admin ACL is not a substitute for per-record ownership checks on customer data.
2. **Input and output**: validate and type inputs at the boundary; escape template output by context (`escapeHtml`, `escapeHtmlAttr`, `escapeUrl`, `escapeJs`). Never echo raw request data.
3. **CSRF**: state-changing frontend controllers need form key validation; do not blanket-disable it (`CsrfAwareAction` bypasses need a written reason).
4. **SQL**: bound parameters only; never concatenate request values into queries or `ORDER BY`.
5. **Secrets**: no credentials in code, XML, fixtures, or logs. Use environment/config with the project's existing mechanism. Never print secret values, even redacted, when a path reference suffices.
6. **Payment data**: tokenized flows only. No PAN/CVV in code, logs, or test fixtures.
7. **CSP and SRI**: `Magento_Csp` ships integrity hashes for local JavaScript and restricts payment pages. Adobe's guidance is not to disable CSP or strip SRI in production; treat any bypass as a last-resort hotfix with security sign-off. Any script added to checkout or payment pages, including analytics and tag managers, must be checked against the project's CSP whitelist and nonce mechanism. Confirm the applicable version behaviour against the project's installed `Magento_Csp` rather than assuming a release line.
8. **Supply chain**: new Composer packages and third-party modules need a reason, a maintained source, and a version constraint. Check Adobe security bulletins for the project's version when the change touches a patched area.
9. **Personal data**: log identifiers, not payloads. Respect the project's data-retention and masking conventions.

Complement with Cursor's built-in `/review-security` when available.

## Must not

- Give a generic OWASP lecture or list risks the diff cannot reach.
- Report theoretical issues without a concrete path in this change.
- Recommend disabling a security control to simplify an implementation.
- State a PCI DSS requirement number as fact; describe the obligation and route confirmation to the client's QSA.

## Output

Only real findings:

```
- <SEVERITY> <file:line> — what an attacker or leak achieves — minimal fix
```

Then: surfaces checked, and anything that needs the client's or security team's confirmation.
