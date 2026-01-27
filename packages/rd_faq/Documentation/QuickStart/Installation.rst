.. _quickInstallation:

==================
Quick installation
==================

In a :ref:`composer-based TYPO3 installation <t3start:install>` you can install
the extension **EXT:rd_faq** via Composer:

.. code-block:: bash

   composer require remotedevs/rd-faq

In TYPO3 installations above version 13.0, the extension will be automatically
installed. You do not have to activate it manually.

If you are using an older version of TYPO3 or have a legacy installation
without Composer, check out the
:ref:`Extended installation <installation>` chapter for manual installation steps.


Update the database schema
--------------------------

Open your TYPO3 backend with :ref:`system maintainer <t3coreapi:system-maintainer>`
permissions.

Then go to:

:guilabel:`Admin Tools > Maintenance` → :guilabel:`Analyze Database` → Click :guilabel:`Create` to apply all changes.

.. image:: /Images/database.png
   :alt: Analyze database in TYPO3
   :width: 600px
   :class: with-shadow


Clear all caches
----------------

Still in :guilabel:`Admin Tools > Maintenance`, click the button:

:guilabel:`Flush Cache` to clear all TYPO3 caches.

.. image:: /Images/CacheFlush.png
   :alt: Flush TYPO3 cache
   :width: 600px
   :class: with-shadow