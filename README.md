# WISECP module PayPal&copy; smart checkout

A payment module for WISECP E-Commerce application to allow a modal window for payment at checkout which avoids the transfer of the user to the PayPal website.

![Buttons at checkout](https://user-images.githubusercontent.com/42153624/201633313-2477a548-d7ce-4417-adda-4f9071b2d9fa.jpg)

### PayPal Developer - Smart Pay Buttons References

API/SDK config methods https://developer.paypal.com/sdk/js/reference/

Sandbox credit card generator for testing
https://developer.paypal.com/dashboard/creditCardGenerator

Real time test panel
https://developer.paypal.com/demo/checkout/#/pattern/server

**Modal Window At Checkout**

![Modal window](https://user-images.githubusercontent.com/42153624/201633538-73aaef47-f9ec-47e4-81b9-94e15efeaefd.jpg)

## Configuration Screen
**Required Fields**

* Primary PayPal account email address
* Client ID string https://developer.paypal.com/dashboard/

![Configuration screen](https://user-images.githubusercontent.com/42153624/201631963-8b14b098-0a14-40b3-9f91-cf3769b5b932.png)

## Vendors
* Bootstrap 5.2.2
## Versions
* 1.0.7 - 11/15/22
* 1.0.5 - 11/14/22
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
* Funding options field only saves value when options are added or when all are cleared. Not saving when single option is removed. Likely a javascript update event in select2 is not firing.

If you encounter errors and have a GitHub account, post issues here. https://github.com/WebsiteDons/wisecp-module-paypal-smart-checkout/issues

## TODO
* Configure invoice bulk payment
* Add method to set money symbol and decimal based on locale
* 
