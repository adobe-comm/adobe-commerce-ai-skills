# Integration change checklist

Use when adding or modifying an outbound or inbound contract.

## Before coding

- [ ] Direction, trigger, and frequency stated
- [ ] Source of truth agreed for every shared field
- [ ] Payload contract written down (fields, types, optionality, units, timezone)
- [ ] Volume and peak expectations known, or flagged as unknown
- [ ] Sandbox or mock available; production explicitly out of scope
- [ ] Auth mechanism identified and stored through the project's existing secret mechanism

## Implementation

- [ ] Connect and read timeouts set explicitly
- [ ] Retry policy with backoff and a maximum attempt count
- [ ] Idempotency key or duplicate-detection strategy
- [ ] Exceptions logged with a correlation id and the business identifier (order increment id, SKU, customer id)
- [ ] No secrets in logs, exceptions, or fixtures
- [ ] Non-critical failure cannot block checkout or order placement
- [ ] Batch size bounded; no unbounded collection loads
- [ ] Partial failure semantics defined (whole batch vs per record)

## Async specifics

- [ ] Topic, publisher, and consumer wiring consistent with the installed version's XML
- [ ] Consumer restart is safe; messages are not lost on crash
- [ ] Dead-letter or failure queue, or a documented manual replay path
- [ ] Ordering requirement documented and satisfied, or explicitly not required

## Validation

- [ ] Unit tests for mapping and transformation logic
- [ ] Integration or contract test against mock/sandbox
- [ ] Failure path exercised (timeout, 4xx, 5xx, malformed response)
- [ ] Replay of the same message produces no duplicate side effects

## Handover

- [ ] How to trace one record end to end, written down
- [ ] Alerting or monitoring expectation stated
- [ ] Any client or third-party dependency (sandbox access, credentials, contract sign-off) listed as a blocker
