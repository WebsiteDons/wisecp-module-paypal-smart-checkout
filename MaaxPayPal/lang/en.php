<?php 

return [
    'invoice-name'             => 'Payment',
    'option-name'              => 'Credit / Debit Card',
    'name'                     => 'MaaxPayPal',
	'metadesc'					=> 'Allow modal window form sourced from PayPal in iframe to avoid sending buyer to PayPal website',
    'redirect-message'         => 'You are redirecting to PayPal',
    'description'              => 'To be able to accept payments all over the world with Paypal, fill in the required fields below.',
    'email'                    => 'Email Address',
    'email-desc'               => 'Enter the email address of your PayPal account',
    'commission-rate'          => 'Commission Rate (%)',
    'commission-rate-desc'     => 'Enter the percentage (%) value for commission fees',
    'notifications-url'        => 'Notification URL Addresses',
    'save-button'              => 'Save Settings',
    'success1'                 => 'Settings saved successfully.',
    'success2'                 => 'Connection test successful.',
    'item_name'                => 'Payment',
    'convert-to'               => 'Converting Currency',
    'convert-to-desc'          => 'All payments are converted into the selected currency and forwarded to the payment gateway',
    'type'                     => 'Type',
    'type-basic'               => 'Settings',
    'type-subscription'        => 'Subscription Settings',
    'subscription-status'      => 'Enable Subscription',
    'subscription-status-desc' => 'Enable to receive recurring (subscription) payments',
    'test-connection'          => 'Test Connection',
    'pay-with-subscription'    => 'Subscribe Now!',
    'pay-with-normal'          => 'Go to Pay',
    'pay-info1'                => 'Choose a payment method',
    'force-subscription'       => 'Force Subscription',
    'force-subscription-desc'  => 'One-time payment cannot be made with PayPal. Payment can only be made by subscription method',
	'pp-success-notice'			=> '<h3>Thank you for the purchase!</h3><h3 class=\"alert alert-warning\">Please do not exit this page until the process is complete</h3>. <h3>You will be redirected to the confirmation page in a few seconds.</h3>',
	// settings form
	'note_setform_admin_email'=>'Required: Your PayPal primary account email',
	'note_setform_clientid'=>'Required: Get the client ID from you PayPal profile settings <a href="https://paypal.com" target="_blank">Log into PayPal</a>',
	'note_setform_myip'=>'
	Set your local IP address to trigger the sandbox use only for you while your customers continue to send payments. Your IP is <pre>[MYIP]</pre>
	If you have generated a test credit card at <a href="https://developer.paypal.com/dashboard/creditCardGenerator" target="_blank">PayPal card generator</a>, include the values <strong>after</strong> the IP address separated by commas in the exact order as <br/><pre>IP,CARD-NUMBER,CARD-DATE,CARD-PIN</pre>',
	'note_setform_disable_fund'=>'Disable default funding types. Separate each with a comma',
	'note_setform_btnheight'=>'The minimum height is 25 and the maximum is 55',
	'note_setform_btnlbl'=>'The installment feature is available only in MX and BR'
];
