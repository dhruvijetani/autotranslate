.. _faqPlugins:

======
Plugin
======

The FAQ plugin is used to display frequently asked questions (FAQs) on the frontend with sorting, pagination, category filtering, and custom templates.

It can be added to a page by creating a content element of type :guilabel:`Plugin` and selecting the plugin type :guilabel:`FAQ System`.

.. figure:: /Images/PluginAdditional.png
   :alt: Selecting FAQ Plugin in backend
   :width: 600px

   Selecting the FAQ plugin

.. contents:: Plugin settings overview:
   :local:
   :depth: 1

.. _plugin-list:

FAQ List View
^^^^^^^^^^^^^

The default view displays a list of FAQ records. Several options can be configured through the plugin:

Sorting
"""""""

- **Short By**: Select the field used to sort the FAQ list (e.g., title).
- **Direction of Sorting**: Ascending or descending order.

Limit Records
"""""""""""""

- **Display Maximum Record**: Set the maximum number of FAQs to display (leave empty to show all).

.. _plugin-categories:

Category Mode
^^^^^^^^^^^^^

Use this section to filter FAQs based on assigned categories. The following **5 category modes** are available:

- **Show All Category FAQs** (`ignore`):  
  Display all FAQ records, ignoring their categories.

- **Show FAQ with Selected Category (OR)** (`or`):  
  Show FAQs that belong to *any* of the selected categories.

- **Show FAQ with Selected Category (AND)** (`and`):  
  Show FAQs that belong to *all* selected categories.

- **Don't Show FAQ with Selected Category (NOTOR)** (`notor`):  
  Exclude FAQs that belong to *any* of the selected categories.

- **Don't Show FAQ with Selected Category (NOTAND)** (`notand`):  
  Exclude FAQs that belong to *all* of the selected categories.

Other category-related options:

- **Include Subcategories**:  
  Also include FAQs from the subcategories of selected categories.

- **Filter by Item**:  
  Enable frontend filtering based on selected categories.

- **Selected Category**:  
  Manually pick one or more categories to filter by.

.. _plugin-pagination:

Pagination
^^^^^^^^^^

You can enable pagination for long FAQ lists:

- **Enable Pagination**:  
  Toggle to activate pagination.

- **Display Items Per Page**:  
  Number of FAQs shown per page (default: 10).

- **Maximum Number of Pages**:  
  Limit how many pages are created (default: 10).

.. _plugin-template:

Template Layout
^^^^^^^^^^^^^^^

- **Select Template Layout**:  
  Choose between predefined layout templates (e.g., default, accordion). Custom layouts can be added in your sitepackage.

To register custom layouts, use the following TypoScript:

.. code-block:: typoscript

   tx_rdfaq.templateLayouts {
       1 = FAQ-layout
   }

This will add a new option called **FAQ-layout** to the Template Layout dropdown in the backend plugin settings.

.. figure:: /Images/TemplateLayoutSelection.png
   :alt:  Template layout Code add in Page TSconfig
   :width: 600px
 
   Template layout Code add in Page TSconfig
 
.. figure:: /Images/TemplateLayoutOutput.png
   :alt: Output of added FAQ layout in Page TSconfig
   :width: 600px
 
   Output of added FAQ layout in Page TSconfig
  
  You can use any number to identify your layout and any label to describe it.

  Now it is possible to use a condition in the template to change the layouts, and load a different partial:

.. code-block:: html

    <f:if condition="{faqs}">
      <f:then>
        <div class="faq-container">
          <h1><f:translate key="tx_faq_domain_model_faq.heading" /></h1>
          <f:flashMessages />
          <div class="faq-accordion">
            <f:if condition="{settings.templateLayout} == 1">
              <f:then>
                <f:for each="{faqs}" as="faq" iteration="iterator">
                    <f:render partial="List/Item-new" arguments="{_all}" />
                </f:for>
              </f:then>
              <f:else>
                <f:for each="{faqs}" as="faq">
                  <div class="faq-accordion-item">
                    <f:render partial="Item" arguments="{_all}" />
                  </div>
                </f:for>
              </f:else>
            </f:if>
          </div>
        </div>
      </f:then>
      <f:else>
          <div class="no-faq-found">
            <f:translate key="list_nofaqfound"  extensionName="rd_faq"/>
        </div>
      </f:else>
    </f:if>

As you can see in this example a different partial is loaded if the layout 1 is used.

.. _plugin-additional:

Additional Settings
^^^^^^^^^^^^^^^^^^^

- **Disable Overwrite Demand**:  
  Prevents TypoScript from overriding plugin settings. Keep this checked if you want full control via the content element settings.

- **Storage Page**:  
  Select the page (or folder) where FAQ records are stored. This ensures the plugin loads data from the right place.

