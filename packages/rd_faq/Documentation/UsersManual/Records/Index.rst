.. _faqFields:

=================
FAQ Record Fields
=================

Each FAQ record in the backend includes several fields that define its content and behavior. These are used by the plugin to render the FAQ list on the frontend.

.. contents::
   :local:
   :depth: 1

Title
^^^^^

:guilabel:`Title`

This is the main question or heading of the FAQ. It's shown as the clickable item in accordion frontend layouts.

Slug
^^^^

:guilabel:`Slug`

A URL-friendly version of the title. It is auto-generated from the title but can be customized. The slug is used in the FAQ detail page URL if enabled.

Description
^^^^^^^^^^^

:guilabel:`Description`

This is the full answer to the FAQ question. It supports rich text formatting and can include links, and lists,.

Image
^^^^^

:guilabel:`Image`

An optional image related to the FAQ. It can help illustrate the answer visually or act as an icon beside the question.

Allowed file extensions:

- `.jpg`
- `.jpeg`
- `.png`
- `.gif`
- `.svg`

Category
^^^^^^^^

:guilabel:`Category`

Assign one or more categories to the FAQ. Categories are used for filtering in the plugin settings and frontend filtering options.

**Pro tip:** If subcategories exist, they can be included in the filter if the plugin is configured to include subcategories.

Filter by item
^^^^^^^^^^^^^^

:guilabel:`Filter by item`

Toggle this field to allow the specific FAQ to appear as a filterable item when category or tag filters are shown on the frontend.
