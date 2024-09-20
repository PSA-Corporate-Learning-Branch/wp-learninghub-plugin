
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

### Version 2 Release Notes:

- sync completely re-written to only touch database when it needs to.
    - sync now based on course ID and not name.
- Curator pathway sync.
- Expiration date can be set on non-Curator/ELM courses after which they are made private.
- "Course Categories" deprecated in favor of two new taxonomies: Groups & Topics.
- All taxonomies but keywords get restricted to super admin addition/management only.
    - Need to discuss with governance committee and communicate to partner admins.
