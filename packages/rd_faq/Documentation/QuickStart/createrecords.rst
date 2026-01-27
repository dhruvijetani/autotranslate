.. _quickContent:
.. _howToStart:

===========================
Create some initial content
===========================

.. _quickPageStructure:

Recommended page structure
==========================

Create at least the following pages:

*  **Home**: The root page that includes your TypoScript template: :guilabel:`Normal page`
*  **FAQ Storage**: A folder to store FAQ records: :guilabel:`Folder`

Your page tree might look like this:

.. code-block:: none

   Home
   ├── Some page
   ├── FAQ Page
   └── Storage
       └── FAQ Storage

.. _quickFAQRecords:

Create FAQ records
==================

Before anything can be shown on the frontend, you need to create some FAQ records.

.. image:: /Images/CreateFaqRecord.png
   :alt: Create FAQ record
   :width: 600px
   :class: with-shadow

#. Go to the module :guilabel:`Web > List`

#. Select the folder page **"FAQ Storage"** you created earlier

#. Click the icon :guilabel:`Create new record`, then choose :guilabel:`Rd F A Q`

#. Fill in the fields like Title, Description, and Category. Click :guilabel:`Save`

.. _quickAddPlugin:

Add Plugin to display FAQ in the frontend
=========================================

You’ll now add the FAQ Plugin to a regular page to display your records.

.. image:: /Images/AddFaqPlugin.png
   :alt: Add FAQ Plugin
   :width: 600px
   :class: with-shadow

#. Go to :guilabel:`Web > Page` and open your display page (e.g., **FAQ Page**)

#. Click to add a new content element

#. Choose :guilabel:`F A Q > List Of FAQs`

#. Switch to the tab :guilabel:`Plugin`

   - Fill in the fields like Short By, Category mode, and Selected Category
   - Set the :guilabel:`Storage Page` to the **FAQ Storage** folder
   - Adjust other options if needed

.. important::

   Click :guilabel:`Save` to store the Plugin configuration.


Check your frontend
===================

Open the FAQ display page on the frontend. Your created FAQ records should now be visible.

Wanna customize how the questions/answers are styled or grouped? Slide into the :ref:`Viewing of Field <faqFields>` chapter to learn more.
