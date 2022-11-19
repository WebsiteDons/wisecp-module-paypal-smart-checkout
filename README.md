# WISECP payment module PayPal&copy; smart checkout

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

![admin view](https://user-images.githubusercontent.com/42153624/202801428-e03f1e9b-ee63-42e0-a84e-ffb217c93c33.png)

## Vendors
* Bootstrap 5.2.2
## Versions

| Version | Time | Version | Time |
| ------- | ---- | ------- | ---- |
|  |  | 1.0.11 | 11/18/22 |
|  |  | 1.0.10 | 11/16/22 |
|  |  | 1.0.9 | 11/16/22 |
|  |  | 1.0.8 | 11/15/22 |
|  |  | 1.0.7 | 11/15/22 |
|  |  | 1.0.5 | 11/14/22 |
|  |  | 1.0.4 | 11/13/22 |

## Installation
* Extract and open parent folder
* Upload the package **MaaxPayPal** folder to `coremio/modules/Payment`
## Known Issues
* the test connection function does nothing because the AJAX process is not connected
* Subscription method not operational though the fields exist in configuration
* Secret Key not needed and will be removed
* Funding options field only saves value when options are added or when all are cleared. Not saving when single option is removed. Likely a javascript update event in select2 is not firing.

If you encounter errors and have a GitHub account, post issues here. https://github.com/WebsiteDons/wisecp-module-paypal-smart-checkout/issues . See the mardown guide for syntax to use for highlighting codes etc https://www.markdownguide.org/extended-syntax/

## TODO
* Configure invoice bulk payment - **COMPLETED**
* Add method to set money symbol and decimal based on locale - **COMPLETED**
* 
## Bulk Payment Process
During bulk pay the user can choose which invoices to pay and the toal is changed and passed to the session for use by the module. The default action of WISECP is to continue to show the invoices table list when in the selected payment method view. This causes confusion for the user because the list does not correctly show the invoice(s) they selected previously, but instead show all as auto selected with the total of all.

To overcome that issue, a CSS rule with property `display:none` is added to the module view to remove the table.

**Invoice list view with payment method selection**

![bulk-1](https://user-images.githubusercontent.com/42153624/202319213-e56e198f-3d9a-4998-9e65-bcd97cc8b3c6.jpg)

**Invoices list removed in payment method final**

![bulk2](https://user-images.githubusercontent.com/42153624/202319229-abbccd75-b78e-4d29-bf4f-39243674ada6.jpg)

