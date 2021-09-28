
# BC Gov Corporate Learning Hub
Description: A gateway to everything that BC Gov has to offer for learning opportunities.

## [Check out the Learning Hub](https://learninghub.gww.gov.bc.ca) (**BC Gov ID required**)

This plugin enables a custom content type, and several custom taxonomies to go along 
with it. We'll create a "course" type and associate taxonomies such as Categories,
Delivery Methods, and keywords. 

We'll set Courses as a 'page' type, so that we can also leverage parent/child 
relationships if we want to.

We will also enable custom fields to capture information such as "how to register" 
on a item-by-item basis, and create custom meta boxes to better manage the UI
for admin folx.

We provide page templates for the type, both single view and main archives.

There is also system-specific synchronization methods, starting with the 
PSA Learning System (ELM).

- Make private all courses within the defined "Source System" taxonomy. 
- Read a specific feed of courses from a source system
- Loop through each one:
    - Does the course already exist here? 
        - If yes, does anything need updating?
            - Update and publish
        - If no, simply publish
    - If no, create and publish
- **Note again that if the course exists in the system, but not the feed,
  then we retain the record of there once having been a course from that 
  source, but it is kept as "private" so it's removed from public view here. 
