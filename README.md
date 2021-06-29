<br />
<p align="center">
  <a href="link to the projects website">
    <img src="https://github.com/PhazeRoOman/thawani-for-woocommerce/blob/167e4f7bebf797a2bea389f577bdadf90323add5/assets/thawani_logo.svg" alt="Logo" width="250" style="background-color: white">
  </a>

  <h1 align="center">Thawani For WooCommerce</h1>

  <p align="center">
    Enables Credit/Debit card payments through Thawani on your WooCommerce shop.
    <br />
    <br />
    <a href="https://phazero-my.sharepoint.com/:f:/g/personal/m_rawahi_phaze_ro/Eo6RM9fy_PVIpMsu_F8zAl4BqZ7iOez6tq9v1fqElfjxZg?e=P1oLhi">Explore the docs</a>
    .
    <a href="https://github.com/PhazeRoOman/thawani-for-woocommerce/issues">Report a bug</a>
  </p>
</p>

<br />

> <br>
>
> **⚠ INFORMATION & REQUIREMENTS**
>
> - Thawani For WooCommerce is currently only available in Oman.
> - Only OMR is currently supported.
>   <br> <br>

<br />

The Thawani Plugin for WooCommerce allows merchants to accept Credit & Debit card transactions.

This plugin will only work with WooCommerce and is built to extend it.

## Motivation

The need for this project came from the shortage in the Omani market when it comes to payment gateways that allows online transactions to be in OMR. Most payment gateways do not support this and require some kind of currency conversion as part of the transaction process.

[Thawani Pay](https://thawani.om/about/) is a Payment Gateway that solves this problem. The [Thawani Checkout](https://thawani.om/checkout/) API, allows for transactions to be made using OMR.

This project makes Thawani's API accessible to the public. Making it easy for anyone to setup Credit & Debit Payment on their online store.

## Features

- Credit & Debit card payment.
- Payments are in OMR no conversion currency needed.
- Easy to switch from sandbox and live environment.
- Checks for payment confirmation.
- Track Session History
- Logging to make debugging easy.

## Requirements

- WooCommerce 5.6+

For live account, please create a [merchant account](https://thawani.om/merchants/) with Thawani then follow the instructions on their [documentation](https://developer.thawani.om/).

## Installation

1. **Download** the .zip file from this repository.

![download zip file](./download_zip.PNG)

2. **Go to: WordPress Admin > Plugins > Add New** to upload the .zip file you downloaded with Choose File.

![install the plugin](./thawani_install.gif)

3. **Activate** the extension.

![activate the plugin](./activate_plugin_blur.PNG)

More information at: [Managing Plugins](https://wordpress.org/support/article/managing-plugins/).

For the plugin to work properly, please make sure that your permalink settings are set to _post name_. To do that **Go to: Settings > Permalinks > Common Settings** and make sure that _post name_ is selected.

![permalink settings](./permalink.png)

## Thawani For WooCommerce Set up

1. **Go to: WooCommerce > Settings > Payments**.

![payment settings](./woocommerce_setting.PNG)

2. Select **Thawani Payment Gateway**. You are taken to the configuration screen.

![Thawani Payment Gateway](./thawani_payment_gateway_setting.PNG)

3. **Enable/Disable** – Enabled by default. Disable to turn off Thawani Pay.

![enable/disable plugin](./enable_disable.gif)

## Configuration

1. Select the Environment:

- Development Environment

In development environment you will be able to make mock payments to simulate the payment processing flow of a live account.

To enable development mode you need the User Acceptable Test ("UAT") secret and publishable keys. These two keys can be found on the [Thawani documentation](https://developer.thawani.om/).

- Production Environment

To setup a live account you need the following:

- An SSL certificate.
- A Thawani merchant account. You can apply by visiting [Thawani's website](https://thawani.om/merchants/).
- Access to the Thawani merchant dashboard. This is where you can get your live secret and publishable keys. Learn more by reading [Thawani's documentation](https://developer.thawani.om/).
- Add the following URL: `https//<YOUR-SITE-URL>/wc-api/thawani-payment-status` to the webhook URL section on the Thawani dashboard. Learn more by reading [Thawani's documentation](https://developer.thawani.om/).
- Add the live secret and publishable keys to the payment settings page.

![live keys](./live_keys.png)

3. Enable logging

Thawani For WooCommerce comes with a troubleshooting tool. This is in the form of logs. The logs are added each time a user orders an item, even if the payment fails.

To enable logging make sure that the _Enable Logging_ checkbox is checked.

![enable logging](./enable%20logging.png)

To view the logs **Go to: WooCommerce > Status > Logs**

![thawani logs](./logs.png)

The logs messages for this plugin follow the following format `thawani-<DATE-OF-ORDER-PLACEMENT>`.

## Customer Checkout Flow

![customer workflow](./customer_workflow.gif)

Nothing will be change from WooCommerce's default customer checkout flow. Customers will see an option on their checkout page to select payment via Thawani as shown in the image below:

![checkout with thawani](./checkout_page.png)

## Admin Order Fulfillment Flow

To view all orders **Go to: WooCommerce > Orders**

![go to order page](./order_page.png)

Note that this plugin automatically updates the order status as follows:

- **Processing:** Payment received (paid); order is awaiting fulfillment from merchant.
- **On hold:** Awaiting payment
- **Failed:** Payment failed or was declined (unpaid)
- **Canceled:** Canceled by an admin or the customer

For more information about WooCommerce Orders, Go to: [Managing Orders](https://docs.woocommerce.com/document/managing-orders/).

## Frequently Asked Questions

We welcome input from teh community please let us know how we can improve this plugin. Do not hesitate to ask us questions on the [issues](https://github.com/PhazeRoOman/thawani_gw/issues) page.


## Main core developer 
* Muhannad AL-Risi 
    * whatsapp +968 72247666
    * Email  muhannad.alrisi@gmail.com