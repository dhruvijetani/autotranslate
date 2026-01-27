.. _faqCategories:

Categories
==========

Categories help group and organize your FAQ entries so that users can filter or view questions by topic.

Creating categories
-------------------

To add or manage categories:

#. Go to the backend module: :guilabel:`Web > List`.
#. Open the folder where your FAQ records are stored (e.g., "FAQ Storage").
#. Click the :guilabel:`+ Create new record` button at the top.
#. Under the list of available record types, choose :guilabel:`FAQ Category`.
#. Enter a name for the category (e.g., "Payments", "Returns", "Technical Issues").
#. Save the record.

.. figure:: /Images/AddFaqCategoryButton.png
   :class: with-shadow with-border
   :alt: Create FAQ Category
   :width: 600px

   Clicking on "Create new record" and selecting "FAQ Category"

   Form fields for adding/editing an FAQ category

Assigning categories to FAQ records
-----------------------------------

When editing or creating an FAQ:

#. In the :guilabel:`General` or :guilabel:`Relations` tab, look for the field :guilabel:`Category`.
#. Select one or more categories that apply to the question.
#. Save the FAQ record.

Using categories in the frontend
--------------------------------

If your FAQ plugin supports filtering, users will be able to filter questions by the categories you defined. You can also use TypoScript or Fluid template logic to render only FAQs from specific categories on different pages.