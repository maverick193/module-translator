module-translator
=================

This module allows developers to generate CSV translation files by scanning the code of a chosen module.

The module check the code of the choosen module and prepare a csv file which contains all the sentences in the PHP and XML files.

In order to have a sentence included in the CSV file, it has to be passed as param to the translate function ( __->($word) ) or declared in the "@translate" attribute for XML files.

The module translates :
- PHP classes in the module folder 
- config.xml file
- system.xml file
- adminhtml.xml file

@TODO :
- Translate module layouts and templates
- Implement an Auto-Translation using Google and Bing API
- Compatibility tests on 1.4 1.5 1.6 and E.E versions of Magento

Installation
============
* Before installing this extension, you have to have **php5-xsl** extension installed :

  example : run ```sudo apt-get install php5-xsl ``` on linux to install php-xsl extension


* Install it with [modman](https://github.com/colinmollenhour/modman/wiki)
* Or download and install it manually

Compatibility
=============
Tested on Magento :
- Community >= 1.7
- Enterprise >= 1.12

Support and Contribution
========================
If you have any issues with this extension, please open an issue on Github.

Any contributions are highly appreciated. If you want to contribute, please open [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Copyright and License
=====================
License   : [OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php).

Copyright : (c) 2013 Mohammed NAHHAS
