# WISECP module PayPal&copy; smart checkout

A payment module for WISECP E-Commerce application to allow a modal window for payment at checkout which avoids the transfer of the user to the PayPal website.

![paypal-smart checkout buttons modal view](https://user-images.githubusercontent.com/42153624/201498800-2edcb1b2-0822-4314-993e-9f9df8d69107.png)

### PayPal Developer - Smart Pay Buttons References

API/SDK config methods https://developer.paypal.com/sdk/js/reference/

Sandbox credit card generator for testing
https://developer.paypal.com/dashboard/creditCardGenerator

Real time test panel
https://developer.paypal.com/demo/checkout/#/pattern/server

![paypal-smart-modal](https://user-images.githubusercontent.com/42153624/201499044-a3b2db01-ab32-4008-8ad3-4e67792d2e44.jpg)

**Configuration Screen**

![Configuration screen](https://user-images.githubusercontent.com/42153624/201631963-8b14b098-0a14-40b3-9f91-cf3769b5b932.png)

## Vendors
* Bootstrap 5.2.2
## Versions
* 1.0.4 - 11/13/22
## Installation
* Extract and open parent folder
* Upload the package **MaaxPayPal** folder to `coremio/modules/Payment`
## Known Issues
* the test connection function does nothing because the AJAX process is not connected
* Invoice bulk payment needs to be configured. Currently only handles single invoice and new purchase.
* Subscription method not operational though the fields exist in configuration
* Commission method not operational though the field exist
* Secret Key not needed and will be removed

