# Plugins required for online booking

The theme's booking system (toggle in **Settings → Rafiki**) is built on top of WooCommerce.
WordPress core and plugins live in the `wp_data` Docker volume, which is **not** tracked in
git — only `wp-content/themes/rafiki` is bind-mounted. On a fresh environment, install these
once after `docker compose up -d`:

```
docker compose run --rm wpcli plugin install woocommerce onvo-pay --activate
```

Then, in `/wp-admin`:

1. Run through the WooCommerce setup wizard (store address, currency — Costa Rica / CRC or USD
   depending on how the business prices tours).
2. **WooCommerce → Settings → Payments → Onvopay**: paste the secret and publishable API keys
   from the [ONVO dashboard](https://onvopay.com). This enables card payments.
3. **WooCommerce → Settings → Payments → Direct bank transfer**: the theme relabels and drives
   this gateway's instructions from **Settings → Rafiki** (bank account + SINPE Móvil fields) —
   no manual setup needed there beyond making sure the gateway is enabled.
4. **Settings → Rafiki**: turn on "Enable Online Booking", fill in the bank/SINPE instructions,
   then set a numeric price on any accommodation/activity/package to start selling it.
5. **WooCommerce → Settings → Advanced → Page setup → Checkout page**: make sure this page's
   content is the classic `[woocommerce_checkout]` shortcode, not the block-based Checkout
   block. The theme's custom fields (proof-of-payment upload, simplified billing fields) are
   wired through classic checkout hooks and won't render on the block-based checkout.

If WooCommerce is not installed/active, or the toggle is off, the site behaves exactly as
before: each tour shows its external "Check Availability" link and the WhatsApp button only.
