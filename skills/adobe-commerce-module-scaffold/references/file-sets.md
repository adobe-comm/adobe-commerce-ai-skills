# Minimum file sets

Mirror the project's exemplar formatting. These are the smallest correct sets, not templates to copy verbatim.

## New module

```
app/code/<Vendor>/<Module>/registration.php
app/code/<Vendor>/<Module>/etc/module.xml
app/code/<Vendor>/<Module>/composer.json      # only if project ships modules as packages
```

## Plugin

```
etc/di.xml                 # <type name="Target"><plugin name="vendor_module_intent" type="..." sortOrder="10"/></type>
Plugin/<Target>Plugin.php  # beforeX / afterX / aroundX (prefer before/after)
```

Plugin method names must match the intercepted public method. Interception does not work on non-public, final, or static methods, or on objects created with `new`.

## Observer

```
etc/events.xml             # area-specific file when the event is area-bound
Observer/<Intent>.php      # implements ObserverInterface
```

## Declarative schema change

```
etc/db_schema.xml
etc/db_schema_whitelist.json   # regenerate, keep in sync
```

## Data or schema patch

```
Setup/Patch/Data/<Intent>.php     # DataPatchInterface (getDependencies, getAliases, apply)
Setup/Patch/Schema/<Intent>.php   # SchemaPatchInterface when structural and not expressible declaratively
```

Patches run once and are recorded; make them idempotent where cheap.

## CLI command

```
Console/Command/<Intent>Command.php
etc/di.xml    # add to Magento\Framework\Console\CommandList via arguments
```

## Cron job

```
etc/crontab.xml
Cron/<Intent>.php
```

## Queue consumer

```
etc/communication.xml
etc/queue_topology.xml
etc/queue_publisher.xml
etc/queue_consumer.xml
Model/<Intent>Consumer.php
```

Confirm which of these files the installed version and project actually use before adding all four.

## GraphQL

```
etc/schema.graphqls
Model/Resolver/<Intent>.php     # implements ResolverInterface, thin
etc/di.xml                      # only if wiring is needed
```

## Admin system config

```
etc/adminhtml/system.xml
etc/config.xml                  # defaults
etc/acl.xml                     # resource
```

Read config through a typed provider class, not `ScopeConfigInterface` scattered through business logic, when the project already does so.

## Unit test

```
Test/Unit/<Mirrored path><Class>Test.php
```
