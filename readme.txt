=== Zibbra ===
Requires at least: 4.6.0
Tested up to: 4.7.2
Contributors: Zibbra
Tags: Ecommerce, Cloud
Stable tag: 1.7.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Zibbra integration plugin for Wordpress

== Description ==

Integration plugin for the Zibbra Cloud E-Commere platform. Transforms your wordpress website into a professional webshop.

[youtube https://www.youtube.com/watch?v=wf-__TWPUFw]

Note: You will need an API license and Zibbra subscription in order to be able to use the plugin. Visit https://zibbra.com to learn more.

== Installation ==

This section describes how to install the plugin and get it working.

1. Make sure you have ioncube installed on your server (http://www.ioncube.com/loaders.php)
2. Make sure you have the API Client Library installed and the include_path configured
2. Upload the plugin files to the `/wp-content/plugins/zibbra` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress
4. Use the Settings->Zibbra screen to configure the plugin (Make sure you enter your API key, API secret)
5. Use the available widgets and tags in your theme to add extra functionality to your existing website
6. Start using the /zibbra/catalog and other webshop pages

== Frequently Asked Questions ==

= Is this plugin free to use? =

You will need an API license key and secret in order to be able to use the plugin. This means you will need at least some E-Commerce bundle from the Zibbra Cloud platform. Contact Us for a quotation to match your need: sales@zibbra.com

= Can I run this plugin without IonCube? =

No, the API Client library is encoded and closed-source. The wordpress plugin speaks to the Zibbra Cloud Platform using this client library. So you will need to install ioncube on your hosting server.

== Screenshots ==

1. Homepage of the demo-webshop digigoods.eu
2. Catalog page of the demo-webshop digigoods.eu
3. Product detail page of the demo-webshop digigoods.eu
4. Checkout page of the demo-webshop digigoods.eu
5. Plugin configuration page
6. Dashboard of the My Zibbra application
7. Sample order of the My Zibbra application

== Changelog ==

= 1.7.6 =

* Implement addons on product page

= 1.7.5 =

* Implement account suspended page

= 1.7.4 =

* Bugfix clearfix in category widget for 6 and 12 products

= 1.7.3 =

* Fix maxval in category widget
* Auto clearfix in category widget

= 1.7.2 =

* Remove more sample/test files from the PayPal client library (security issues)
* Various security updates and fixes

= 1.7.1 =

* Remove sample files from the PayPal client library (security issues)

= 1.7.0 =

* Implement JS hooks on registration and checkout page, so actions can be launched on submit of those pages

= 1.6.7 =

* Fix error in tagging new version

= 1.6.6 =

* Include header/footer on payment verification page

= 1.6.5 =

* SprintPack shipping adapter frontend integration

= 1.6.4 =

* Suppress sending confirmation email from checkout, is handled automatically in the API when adding payments

= 1.6.3 =

* Compatibility issue php 5.6
* Tested on Wordpress 4.7.0

= 1.6.2 =

* Update validation to allow for UK zipcodes (only 2 numbers)
* Include paypal vendor files

= 1.6.1 =

* Bugfix generic shipping method, without shipping adapter

= 1.6.0 =

* Implementation of Payzen payments
* Move all payment adapter libraries and code to the plugin, and out of the client library
* Move all shipping adapter libraries and code to the plugin, and out of the client library
* Improve checkout page
* Improve cart page
* Improve handling of payment/shipping returns/cancellations/errors and order handling
* Few bug fixes in account page

= 1.5.5 =

* Update templates to use the DateTime object for customer orders and invoices

= 1.5.4 =

* Fix login issues on multisite webshops

= 1.5.3 =

* Don't allow number 0 in registration form
* Add continue shopping button in on the cart page
* Fix double submit on product pages
* Add extra warning when shopping cart has insufficient value for a specific voucher

= 1.5.2 =

* Fix bug in notification system when session is already loaded by a different plugin

= 1.5.1 =

* Add extra classes information to shopping cart

= 1.5.0 =

* Update plugin to work with addons
* Add configuration to enclose GUI with bootstrap container
* Add configuration to prevent updating of cart items
* Add configuration to prevent deletion of cart item addons
* Update templates and configuration to allow container-fluid or container for page title and page content

= 1.4.2 =

* Bugfix JS minicart widget

= 1.4.1 =

* Make add-to-cart in catalog page optional, provide link to product page instead
* Compatibility with wordpress 4.6.1

= 1.4.0 =

* New widgets
* Small improvements
* Custom search

= 1.3.6 =

* Bugfix shipping costs

= 1.3.5 =

* Bugfix Mollie payment validation
* Bugfix bpost shipping integration

= 1.3.4 =

* Improvement Mollie payment validation

= 1.3.3 =

* Bugfixes bpost integration

= 1.3.2 =

* Bugfixes bpost integration
* Compatibility with wordpress 4.6.0

= 1.3.1 =

* Bugfix in checkout, load payment adapters before cartToOrder

= 1.3.0 =

* Bugfix in minicart widget
* Implementation of BPost shipping manager

= 1.2.2 =

* Bugfix in links to share product pages

= 1.2.1 =

* Small fix in minicart widget

= 1.2.0 =

* Changes for client library 1.5.0
* Tested for wordpress 4.5.3

= 1.1.7 =

* Bugfix switching number of articles per page

= 1.1.6 =

* Little improvements on tags for widgets
* Tested for wordpress 4.5.2

= 1.1.5 =

* Little improvements on tags for widgets

= 1.1.4 =

* Menu type for Zibbra categories, so we can build custom menu's

= 1.1.3 =

* Tested up to 4.5

= 1.1.2 =

* Fix error in tagging new version

= 1.1.1 =

* Minor fixes
* Update query of Products to match new library version

= 1.1.0 =

* Facebook Pixel tracking
* Allow registration with auto-generated passwords
