# GraphQL

1. Extend schema in module `etc/schema.graphqls` following project naming.
2. Wire resolvers via DI; keep resolvers thin — business logic in services.
3. Respect existing authorization patterns (`@doc`, identity class, ACL for mutations as project does).
4. Do not break backward compatibility of fields without an explicit ticket decision.
5. Add/adjust cache identity classes when caching is already used for that type in the project.
