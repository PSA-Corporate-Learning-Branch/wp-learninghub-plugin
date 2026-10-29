Learning Hub Storage Check
Version: 1.0.0

PURPOSE

Inspect the current WordPress site's seven table storage engines before deploying
the proposed resumable Learning Hub sync. The current Learning Hub plugin does
not need to be changed, disabled, or even installed to use this checker.

INSTALLATION

1. Upload learninghub-storage-check.zip using Plugins > Add New > Upload Plugin.
   In Multisite, uploading plugins normally happens in Network Admin.
2. Activate it for the Learning Hub site if site-level activation is available.
   Network activation is also supported: it adds a Tools page to site dashboards,
   but does not run a network-wide scan or do anything on activation.
3. Open the Learning Hub site's dashboard, then Tools > Learning Hub Storage Check.
4. Confirm the site URL is correct and copy the report.
5. Deactivate and delete the checker when finished.

WHAT IT DOES

When an administrator opens its Tools page, the checker issues one SHOW TABLE
STATUS query, restricted to the seven exact table names supplied by WordPress:
posts, postmeta, terms, term_taxonomy, term_relationships, termmeta, options.
It reports each engine, along with basic WordPress/PHP runtime information.
Only administrators with manage_options can open the report.

It does not read course content, credentials, or other sites' table contents.
It makes no outbound requests, saves no reports/options, schedules no tasks,
creates no tables, performs no conversion, and starts no transactions or locks.
Installing and activating any plugin still uses WordPress's normal plugin files
and active-plugin bookkeeping; the checker adds no separate persisted state.

RESULTS

All seven InnoDB: the storage-engine requirement is met. No conversion is needed.
Another engine: do not deploy the current transaction-dependent sync as-is.
Unknown: metadata could not be read or a table was not found. This is not a pass
and does not establish that the table is MyISAM.

This checks table metadata only, not all deployment compatibility. It does not
exercise GET_LOCK/RELEASE_LOCK, rollback, database routing, or object caching.
There is deliberately no conversion button.
