=== SpeedPress Product Add-Ons ===
Contributors: speedpress
Tags: woocommerce, product addons, product options, conditional logic, extra fields
Requires at least: 6.2
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Advanced WooCommerce product options with conditional logic, dynamic pricing, image swatches, file uploads, and live price calculation.

== Description ==

SpeedPress Product Add-Ons lets customers personalize products before they reach the cart.

= Field types =

Text, textarea, number, email, dropdown, radio, checkbox, checkbox group, multi-select, image radio, image checkbox, color picker, color swatches, file upload, date, time, quantity, customer-defined price, heading, paragraph/HTML, hidden.

= Pricing =

Fixed, percentage of product, quantity × price, character × price (with optional base + extra rates), length/area/weight multipliers, negative/discount prices, quantity tiers, and role-based overrides.

= Conditional logic =

Show or hide any field when other fields match AND / OR rules (is, is not, contains, greater than, empty, etc.).

= Assignment =

* Per-product groups on the Product data → Product Add-Ons tab
* Global groups (WooCommerce → Global Add-Ons) assigned to all products, specific products, categories, tags, or product types, with exclusions

= Cart through order =

Selections persist through cart, checkout, order admin, My Account, and emails. Prices are recalculated server-side. File uploads are stored in randomized monthly directories.

= Integrations =

* Variable products (add-ons sit on top of variations)
* WooCommerce Subscriptions (add-on amount is part of the cart line price)
* HPOS declared compatible
* Cart & Checkout Blocks declared compatible (add-ons still captured on the product page)
* Gutenberg block: Product Add-Ons
* Elementor widget: Product Add-Ons
* REST: `/wp-json/sppa/v1/products/{id}` and `/wp-json/sppa/v1/groups`
* JSON export from the product tab

== Installation ==

1. Upload the `speedpress-product-addons` folder to `/wp-content/plugins/`.
2. Activate the plugin.
3. WooCommerce must be active.
4. Edit a product → Product Add-Ons tab, or create a Global Add-On Group.

== Frequently Asked Questions ==

= Does this replace WooCommerce variations? =

No. Use variations for Size / Color type attributes. Use this plugin for extras: engraving, gift wrap, uploads, dates, and conditional upgrades.

= Is conditional logic included? =

Yes. Each field can show or hide based on AND / OR rules against other field values.

== Changelog ==

= 1.0.0 =
* Initial release.
