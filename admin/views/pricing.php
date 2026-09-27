<?php
/**
 * Pricing page.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$book = 'https://calendar.app.google/LzCoQBSA3Yc17LQ3A';
$site = 'https://wpspeedpress.com';
?>
<div class="wrap sppa-settings">
	<div class="sppa-app-hero">
		<div>
			<p class="sppa-kicker"><?php esc_html_e( 'SpeedPress Addons', 'speedpress-product-addons' ); ?></p>
			<h1><?php esc_html_e( 'Simple pricing. Cheaper than the big add-on plugins.', 'speedpress-product-addons' ); ?></h1>
			<p><?php esc_html_e( 'Official WooCommerce Product Add-Ons is $79/year and has no conditional logic. Most marketplace clones are $79–$149/year. SpeedPress undercuts that and includes the features those plugins charge extra for.', 'speedpress-product-addons' ); ?></p>
		</div>
		<a class="sppa-btn sppa-btn-primary" href="<?php echo esc_url( $book ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get a license', 'speedpress-product-addons' ); ?></a>
	</div>

	<div class="sppa-project-grid">
		<article class="sppa-card sppa-project-card">
			<div class="sppa-project-tag"><?php esc_html_e( 'Free', 'speedpress-product-addons' ); ?></div>
			<h2>$0</h2>
			<p class="sppa-card-sub"><?php esc_html_e( 'One site. Core fields. Good for testing or a single small catalog.', 'speedpress-product-addons' ); ?></p>
			<ul class="sppa-help-list">
				<li><?php esc_html_e( '1 website', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Text, textarea, number, email, dropdown, radio, checkbox, heading', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Human support via SpeedPress', 'speedpress-product-addons' ); ?></li>
			</ul>
		</article>
		<article class="sppa-card sppa-project-card">
			<div class="sppa-project-tag"><?php esc_html_e( 'Premium · recommended', 'speedpress-product-addons' ); ?></div>
			<h2>$29<span style="font-size:14px;font-weight:600"> / year</span></h2>
			<p class="sppa-card-sub"><?php esc_html_e( 'One site. Everything in the plugin. Less than half of Woo’s official add-on.', 'speedpress-product-addons' ); ?></p>
			<ul class="sppa-help-list">
				<li><?php esc_html_e( '1 website', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'All field types, layouts, styles', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Conditional logic (AND / OR)', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Live pricing, uploads, swatches', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Expires in 12 months unless renewed', 'speedpress-product-addons' ); ?></li>
			</ul>
		</article>
		<article class="sppa-card sppa-project-card">
			<div class="sppa-project-tag"><?php esc_html_e( 'Lifetime', 'speedpress-product-addons' ); ?></div>
			<h2>$100<span style="font-size:14px;font-weight:600"> once</span></h2>
			<p class="sppa-card-sub"><?php esc_html_e( 'One site. Same features as Premium. The code never expires.', 'speedpress-product-addons' ); ?></p>
			<ul class="sppa-help-list">
				<li><?php esc_html_e( '1 website', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'All Premium features', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'License server will not auto-revoke this code', 'speedpress-product-addons' ); ?></li>
			</ul>
		</article>
		<article class="sppa-card sppa-project-card">
			<div class="sppa-project-tag"><?php esc_html_e( 'Agency', 'speedpress-product-addons' ); ?></div>
			<h2>$29<span style="font-size:14px;font-weight:600"> × sites / year</span></h2>
			<p class="sppa-card-sub"><?php esc_html_e( 'Priced from Premium. 5 sites = $145/year. 10 sites = $290/year.', 'speedpress-product-addons' ); ?></p>
			<ul class="sppa-help-list">
				<li><?php esc_html_e( 'Set max sites on the Agency key', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'All Premium features', 'speedpress-product-addons' ); ?></li>
				<li><?php esc_html_e( 'Yearly expiry like Premium', 'speedpress-product-addons' ); ?></li>
			</ul>
		</article>
	</div>

	<div class="sppa-card" style="margin-top:16px">
		<h2><?php esc_html_e( 'What the market charges', 'speedpress-product-addons' ); ?></h2>
		<p class="sppa-card-sub"><?php esc_html_e( 'Figures from vendor sites and the Barn2 add-ons roundup. Annual unless noted.', 'speedpress-product-addons' ); ?></p>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Plugin', 'speedpress-product-addons' ); ?></th>
					<th><?php esc_html_e( 'Typical price', 'speedpress-product-addons' ); ?></th>
					<th><?php esc_html_e( 'Conditional logic', 'speedpress-product-addons' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr><td>WooCommerce Product Add-Ons (official)</td><td>$79 / year</td><td><?php esc_html_e( 'No (docs say not in core)', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>Product Addons and Custom Fields Manager</td><td>$79 / year</td><td><?php esc_html_e( 'Yes', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>Product Addons and Extra Options</td><td>$79 / year</td><td><?php esc_html_e( 'Yes', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>Barn2 Product Options</td><td>$149 / year</td><td><?php esc_html_e( 'Yes', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>YITH Product Add-Ons</td><td><?php esc_html_e( 'Free + ~$100 / year Pro', 'speedpress-product-addons' ); ?></td><td><?php esc_html_e( 'Pro', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>PPOM (N-Media)</td><td><?php esc_html_e( 'Free + paid Pro add-ons', 'speedpress-product-addons' ); ?></td><td><?php esc_html_e( 'Yes', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>Acowebs Product Addons</td><td><?php esc_html_e( 'Free + paid Pro', 'speedpress-product-addons' ); ?></td><td><?php esc_html_e( 'Basic free / advanced Pro', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td>Advanced Product Fields</td><td><?php esc_html_e( 'Free + $89 Pro', 'speedpress-product-addons' ); ?></td><td><?php esc_html_e( 'Yes', 'speedpress-product-addons' ); ?></td></tr>
				<tr><td><strong>SpeedPress Addons</strong></td><td><strong>$0 · $29/yr · $100 life · $29×sites agency</strong></td><td><strong><?php esc_html_e( 'Yes on paid plans', 'speedpress-product-addons' ); ?></strong></td></tr>
			</tbody>
		</table>
	</div>

	<div class="sppa-card" style="margin-top:16px">
		<h2><?php esc_html_e( 'What you get vs those plugins', 'speedpress-product-addons' ); ?></h2>
		<ul class="sppa-help-list">
			<li><?php esc_html_e( 'Field types they sell: text, textarea, number, email, phone, URL, dropdown, radio, checkbox, multi-select, image swatches, color picker/swatches, file upload, date, time, quantity, customer-defined price, heading, HTML, hidden.', 'speedpress-product-addons' ); ?></li>
			<li><?php esc_html_e( 'Conditional show/hide with AND / OR — missing from official Woo Product Add-Ons.', 'speedpress-product-addons' ); ?></li>
			<li><?php esc_html_e( 'Pricing: fixed, percent, quantity, character, length/area/weight, discounts, live total on the product page.', 'speedpress-product-addons' ); ?></li>
			<li><?php esc_html_e( 'Global groups + per-product groups, product type filters, layouts and styles.', 'speedpress-product-addons' ); ?></li>
			<li><?php esc_html_e( 'Cart, checkout, order, and email display of selected options.', 'speedpress-product-addons' ); ?></li>
			<li><?php esc_html_e( 'API code + license console so you know which site is running it.', 'speedpress-product-addons' ); ?></li>
		</ul>
		<p class="sppa-card-sub"><?php esc_html_e( 'We are not cloning Google Maps, S3 uploads, image croppers, or font libraries from PPOM/Acowebs Pro. Those stay out so the price can stay low. If a store needs one of those, book a custom build.', 'speedpress-product-addons' ); ?></p>
	</div>

	<div class="sppa-cta-band">
		<div>
			<h2><?php esc_html_e( 'Ready for a Free or Premium code?', 'speedpress-product-addons' ); ?></h2>
			<p><?php esc_html_e( 'Create the code in the SpeedPress License Server, then paste it under Settings → API code.', 'speedpress-product-addons' ); ?></p>
		</div>
		<a class="sppa-btn sppa-btn-primary" href="<?php echo esc_url( $site ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'wpspeedpress.com', 'speedpress-product-addons' ); ?></a>
	</div>
</div>
