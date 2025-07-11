# Single Directory Components Styleguide Module - Development Environment

This Drupal project is aimed to provide a clean stable environment to work on the SDC Styleguide module.

## Project structure

This environment runs on top of DDev.

## Coding standards

* Follow these pages:
* * https://www.drupal.org/docs/develop/standards/php/php-coding-standards
* * https://www.drupal.org/docs/develop/standards/php/api-documentation-and-comment-standards
* * https://www.drupal.org/docs/develop/standards/php/api-documentation-examples
* * https://www.drupal.org/docs/develop/coding-standards/namespaces
* * https://www.drupal.org/docs/develop/coding-standards/naming-standards-for-services-and-extending-symfony
* * https://www.drupal.org/docs/develop/standards/php/psr-4-namespaces-and-autoloading-in-drupal-8
* * https://www.drupal.org/docs/develop/coding-standards/write-e_all-compliant-code
* When working on classes, ensure the usage of Dependency Injection. Use the service container when in functional code (module or theme files).

## SDC

As this project is to help building the SDC Styleguide, it will work heavily with SDC. The annotated example can be found at
https://www.drupal.org/docs/develop/theming-drupal/using-single-directory-components/annotated-example-componentyml

Some other helpful resources:

* Notes at https://www.drupal.org/docs/develop/theming-drupal/using-single-directory-components/frequently-asked-questions
* API notes: https://www.drupal.org/docs/develop/theming-drupal/using-single-directory-components/api-for-single-directory-components
* The SDC Styleguide module can be found at `web/custom/sdc_styleguide`
