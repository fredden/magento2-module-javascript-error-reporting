# JavaScript error reporting for Magento 2

[![Latest stable version](https://img.shields.io/packagist/v/fredden/magento2-module-javascript-error-reporting?style=plastic)](https://packagist.org/packages/fredden/magento2-module-javascript-error-reporting)
[![Total downloads](https://img.shields.io/packagist/dt/fredden/magento2-module-javascript-error-reporting?style=plastic)](https://packagist.org/packages/fredden/magento2-module-javascript-error-reporting/stats)
[![Code quality alerts](https://img.shields.io/lgtm/alerts/g/fredden/magento2-module-javascript-error-reporting.svg?logo=lgtm&style=plastic)](https://lgtm.com/projects/g/fredden/magento2-module-javascript-error-reporting/alerts/)

## Overview
A Magento 2 module which captures JavaScript errors for later review by website administrators.
JavaScript errors are kept for up to 90 days and available via Magento's administration back-end.

## Features
* JavaScript errors on the front-end are recorded for later review
  - JavaScript error message
  - File (URL), line number, column number of exception if available
  - Stack trace of exception if available
* Limited browser information which might be useful for debugging (but hopefully not for fingerprinting / identifying individual users) is recorded
  - Current URL when error occurred
  - User agent
  - Viewport width & height
* Errors are kept for up to 90 days and automatically deleted after this period
* Errors are available for review in Magento's back-end
  - Admin -> Reports -> JavaScript Error Reporting

## Installation
This module is available on packagist.org and installable via composer:

```sh
composer require fredden/magento2-module-javascript-error-reporting
```

This module uses [semantic versioning (semver)](http://semver.org/).

## Compatibility
|Version|Magento Open Source|Magento Commerce Edition|
|-|-|-|
|2.3.x|:white_check_mark: Yes|:white_check_mark: Yes|
|2.4.x|:white_check_mark: Yes|:white_check_mark: Yes|

PHP version 7.1 or better is required.

No third-party libraries nor services are required.

## Contributing
Community contributions are welcome.
Please open a pull request on GitHub if you have a code suggestion,
or an issue on GitHub if you are encountering a problem with this module.
Please note that issues relating to the problems that this module highlights in others' code are out of scope for support here.

## Planned features
- Ability to change how long errors are kept for (including option for 'forever')
- View details of an individual event
- Summary reports (grouped by message or file or user agent with frequency information)
- Record errors encountered in adminhtml area
