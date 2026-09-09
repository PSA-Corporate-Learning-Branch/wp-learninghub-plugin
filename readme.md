
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
