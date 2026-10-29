
# BC Gov Corporate Learning Hub
Description: A gateway to everything that BC Gov has to offer for learning opportunities.

## [Check out the Learning Hub](https://learningcentre.gww.gov.bc.ca/learninghub) (**BC Gov ID required**)

This plugin enables a "course" custom content type, and several custom taxonomies to go along 
with it:

- Groups
- Topics
- Delivery Methods
- Learning Partners
- External Systems
- Keywords

Enable custom meta boxes to easily manage:

- Registration link
- ELM course code
- ELM course ID
- Expiry date

This plugin depends on [the custom theme](https://github.com/bcgov/wp-learninghub) that was created
to support it. All template files reside there.

There are numerous helper functions to add support for extended searching into the 
taxonomies and adding fields to taxonomies such as learning partner contact info and
organizational logo.

There are also system-specific synchronization methods, including the 
PSA Learning System (ELM) and the Learning Curator.

### Resumable systems sync (3.2.1)

Open **Courses > Systems Sync** and select **Start Systems Sync**. Keep the page
open while it runs. **Pause** finishes the current request, and **Resume** continues
from the last saved item, including after reloading the page or a PHP timeout.
There is no cron dependency. Closing the page pauses work after the current request.

The sync downloads and validates each of the three feeds separately, saves them in
non-autoloaded site options, updates partners before courses, then checks missing
and expired courses and removes missing mapped partners. All batches in a run use
the same saved feeds; a partial batch is never treated as the complete catalogue.
An empty course feed, invalid JSON, missing required course fields, or duplicate
source IDs stops the run before content changes. Empty partner lists are valid and
remove previously mapped partners during cleanup.

Each request handles at most five work units and stops starting new units after
8 seconds. Downloads have a 10-second timeout and each gets its own request.
These limits leave headroom below a 30-second PHP limit; a single slow database
operation or third-party hook can still exceed it. In that case, Resume retries
the unfinished item. Repeated failure on the same item requires investigating that
record or hook; increasing the server timeout may still help that case.

The seven WordPress content/options tables must use **InnoDB** (checked before a
new run). Each item's database changes and checkpoint commit in one transaction.
Completed items remain saved; an interrupted item's database changes roll back.
A MySQL/MariaDB connection lock prevents simultaneous workers and is released when
the connection closes. The database must permit `GET_LOCK`/`RELEASE_LOCK`. Custom
hooks with external side effects or their own transaction control need separate
review. Fatal-error handling also clears object caches; test with the production
cache plugin before deployment if one is installed.

**Discard saved progress** retains applied changes and lets the next run download
fresh feeds. Snapshots are deleted after completion or discard. The existing
`edit_posts` permission is retained and every mutation requires a WordPress nonce.
No LSApp publishing changes or new scheduled tasks are needed. To override the
existing feed location, set `LEARNINGHUB_SYNC_FEED_BASE_URL` in `wp-config.php`.

Deploy the complete plugin, including `lib/systems-sync.php` and
the `assets/` directory, together. Do not replace only the main PHP file.

The separate read-only prerequisite checker is maintained in
[`tools/learninghub-storage-check/`](tools/learninghub-storage-check/readme.txt).
It can be installed alongside an older Learning Hub plugin to report the current
site's table storage engines before deploying this update. It does not convert
tables or run a sync.

Integration checks:

```sh
# Existing rollback-based partner checks:
wp eval-file wp-content/plugins/wp-learninghub-plugin/tests/learning-partner-sync.php

# Destructive: disposable local database only; back it up and restore afterwards.
# Requires WP_ENVIRONMENT_TYPE=local.
wp eval-file wp-content/plugins/wp-learninghub-plugin/tests/batched-sync.php
```

The batch checks cover malformed feeds, partial-batch cleanup protection, a failed
course insert, saved progress, retry without duplicates, partner relationships,
expiry, overlapping workers, and repeated runs.
They also verify that changed descriptions and topics update the same course post.

### Learning partner sync

Sync matches partners by `learning_partner_source_id`. Existing imported terms without
that metadata are matched by feed slug, then name, and receive the source ID on their
next sync. Their term IDs, course relationships, and logos are preserved. Feed links
and contacts populate `partner-url` and `partner-contact`, as used by the editor and theme.
After deploying this fix, run **Courses > Systems Sync** to refresh existing partners;
no separate migration is required.

Run the regression check on a disposable WordPress installation with this plugin active:

```sh
wp eval-file wp-content/plugins/wp-learninghub-plugin/tests/learning-partner-sync.php
```

The check requires InnoDB tables and rolls back its database changes. It exercises
imported and new partners, repeat syncs, field clearing, and logo/course preservation.

### Version 2 Release Notes:

- sync completely re-written to only touch database when it needs to.
    - sync now based on course ID and not name.
- Curator pathway sync.
- Expiration date can be set on non-Curator/ELM courses after which they are made private.
- "Course Categories" deprecated in favor of two new taxonomies: Groups & Topics.
- All taxonomies but keywords get restricted to super admin addition/management only.
    - Need to discuss with governance committee and communicate to partner admins.
