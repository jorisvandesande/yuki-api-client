Yuki API Client
===============

An API client for the Yuki SOAP API endpoints.

[![Build Status](https://travis-ci.org/jorisvandesande/yuki-api-client.svg?branch=master)](https://travis-ci.org/jorisvandesande/yuki-api-client)

At the moment only a couple of API calls are implemented:

* Archive
  * DocumentBinaryData()
  * DocumentsInFolder()
  * SearchDocuments()
* Contact
  * SearchContacts()
* Sales
  * ProcessSalesInvoices()

Usage
=====
```php
use JVDS\YukiClient\ContactClient;

$client = new ContactClient('your-yuki-access-key');
$client->searchContacts();
```

Official Yuki documentation
===========================

Documentation for all Yuki endpoints can be found at: 
  https://support.yuki.nl/nl/support/solutions/articles/11000062989-yuki-api-koppeling
