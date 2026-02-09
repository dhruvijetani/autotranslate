.. include:: ../Includes.txt

.. _configuration:

Configuration
=============

API Setup
---------
The extension uses a secure API connection. Ensure your server has access to the translation endpoint.

TCA Configuration
-----------------
By default, the extension respects the `l10n_mode` and `exclude` settings in TCA. Fields marked as `exclude` or non-string types will be skipped.