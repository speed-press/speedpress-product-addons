<?php
/**
 * More from SpeedPress.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$products = array(
	array(
		'name' => __( 'SpeedPress Addons', 'speedpress-product-addons' ),
		'tag'  => __( 'Plugin · Installed', 'speedpress-product-addons' ),
		'desc' => __( 'WooCommerce product options, conditional logic, and live pricing.', 'speedpress-product-addons' ),
		'url'  => admin_url( 'edit.php?post_type=sppa_addon_group' ),
		'cta'  => __( 'Open workspace', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'WordPress Care Plans', 'speedpress-product-addons' ),
		'tag'  => __( 'Maintenance', 'speedpress-product-addons' ),
		'desc' => __( 'Updates, backups, security, and human support so sites stay ready to grow.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/wordpress-maintenance-services/',
		'cta'  => __( 'Explore maintenance', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'WooCommerce Care', 'speedpress-product-addons' ),
		'tag'  => __( 'Stores', 'speedpress-product-addons' ),
		'desc' => __( 'Checkout, catalog, and store performance support for WooCommerce.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/woocommerce-maintenance-service/',
		'cta'  => __( 'Maintain my store', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'Speed Optimization', 'speedpress-product-addons' ),
		'tag'  => __( 'Performance', 'speedpress-product-addons' ),
		'desc' => __( 'Loading speed, mobile performance, and Core Web Vitals — practical work, not guesswork.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/wordpress-speed-optimization/',
		'cta'  => __( 'Improve my speed', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'Security & Malware Removal', 'speedpress-product-addons' ),
		'tag'  => __( 'Security', 'speedpress-product-addons' ),
		'desc' => __( 'Find problems, remove malware when possible, and harden the site.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/wordpress-malware-removal/',
		'cta'  => __( 'Secure my website', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'Emergency Support', 'speedpress-product-addons' ),
		'tag'  => __( 'Urgent', 'speedpress-product-addons' ),
		'desc' => __( 'Help when a site is down, broken, or throwing fatal errors.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/wordpress-emergency-support/',
		'cta'  => __( 'Get emergency help', 'speedpress-product-addons' ),
	),
	array(
		'name' => __( 'Custom Development', 'speedpress-product-addons' ),
		'tag'  => __( 'Build', 'speedpress-product-addons' ),
		'desc' => __( 'Features, integrations, and WooCommerce work designed around the business.', 'speedpress-product-addons' ),
		'url'  => 'https://wpspeedpress.com/wordpress-development-services/',
		'cta'  => __( 'Discuss a project', 'speedpress-product-addons' ),
	),
);

$quotes = array(
	array( 'Best website support services. Exactly what we were looking for.', 'Website Speed Optimization' ),
	array( 'Laju is a pleasure to work with.', 'Ongoing WordPress Tutoring and Support' ),
	array( 'Always giving his best effort.', 'Expert WordPress Speed and Performance Optimization' ),
	array( 'I highly recommend Laju.', 'WordPress Performance Troubleshooting' ),
	array( 'Great communication and excellent speed results.', 'Core Web Vitals Optimization' ),
	array( 'Fast, professional, and easy to work with.', 'WordPress Website Fixes' ),
	array( 'Clear communication from start to finish.', 'WordPress Troubleshooting' ),
	array( 'The website became faster and easier to use.', 'Performance and UX Optimization' ),
	array( 'Helpful, patient, and very knowledgeable.', 'WordPress Coaching and Support' ),
	array( 'Delivered the work quickly and with care.', 'Custom WordPress Development' ),
);
?>
<div class="wrap sppa-settings sppa-hub">
	<div class="sppa-app-hero">
		<div>
			<p class="sppa-kicker"><?php esc_html_e( 'WordPress Maintenance Partner', 'speedpress-product-addons' ); ?></p>
			<h1><?php esc_html_e( 'SpeedPress products & care', 'speedpress-product-addons' ); ?></h1>
			<p><?php esc_html_e( 'Keep WordPress sites fast, secure, reliable, and ready to grow — with ongoing maintenance, performance work, and 100% human support. No long-term contracts.', 'speedpress-product-addons' ); ?></p>
			<div class="sppa-hero-actions">
				<a class="sppa-btn sppa-btn-primary" href="https://calendar.app.google/LzCoQBSA3Yc17LQ3A" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Book a free consultation', 'speedpress-product-addons' ); ?></a>
				<a class="sppa-btn sppa-btn-ghost" href="https://wpspeedpress.com" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit wpspeedpress.com', 'speedpress-product-addons' ); ?></a>
			</div>
		</div>
		<div class="sppa-hero-badge">
			<strong>4.9/5</strong>
			<span><?php esc_html_e( '35 Upwork ratings · 90%+ Job Success · 66+ jobs · 850+ hours', 'speedpress-product-addons' ); ?></span>
		</div>
	</div>

	<div class="sppa-stat-row">
		<div class="sppa-stat"><strong>5+</strong><span><?php esc_html_e( 'Years experience', 'speedpress-product-addons' ); ?></span></div>
		<div class="sppa-stat"><strong>24/7</strong><span><?php esc_html_e( 'Monitoring', 'speedpress-product-addons' ); ?></span></div>
		<div class="sppa-stat"><strong>50+</strong><span><?php esc_html_e( 'Websites supported', 'speedpress-product-addons' ); ?></span></div>
		<div class="sppa-stat"><strong>99.9%</strong><span><?php esc_html_e( 'Uptime focus', 'speedpress-product-addons' ); ?></span></div>
		<div class="sppa-stat"><strong>&lt;1hr</strong><span><?php esc_html_e( 'Response target', 'speedpress-product-addons' ); ?></span></div>
	</div>

	<h2 class="sppa-section-title"><?php esc_html_e( 'Products from SpeedPress', 'speedpress-product-addons' ); ?></h2>
	<div class="sppa-project-grid">
		<?php foreach ( $products as $product ) : ?>
			<article class="sppa-card sppa-project-card">
				<div class="sppa-project-tag"><?php echo esc_html( $product['tag'] ); ?></div>
				<h2><?php echo esc_html( $product['name'] ); ?></h2>
				<p class="sppa-card-sub"><?php echo esc_html( $product['desc'] ); ?></p>
				<a class="sppa-btn sppa-btn-primary" href="<?php echo esc_url( $product['url'] ); ?>" <?php echo 0 === strpos( $product['url'], 'http' ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo esc_html( $product['cta'] ); ?>
				</a>
			</article>
		<?php endforeach; ?>
	</div>

	<div class="sppa-quote-head">
		<h2 class="sppa-section-title"><?php esc_html_e( 'Verified Upwork feedback', 'speedpress-product-addons' ); ?></h2>
		<a class="sppa-btn sppa-btn-ghost" href="https://www.upwork.com/freelancers/~0149190c8d83bae2e2" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View Upwork profile', 'speedpress-product-addons' ); ?></a>
	</div>
	<div class="sppa-quote-grid">
		<?php foreach ( $quotes as $quote ) : ?>
			<blockquote class="sppa-card sppa-quote">
				<div class="sppa-stars">★★★★★</div>
				<p>“<?php echo esc_html( $quote[0] ); ?>”</p>
				<footer><?php echo esc_html( $quote[1] ); ?> · Upwork</footer>
			</blockquote>
		<?php endforeach; ?>
	</div>

	<div class="sppa-cta-band">
		<div>
			<h2><?php esc_html_e( 'WordPress support, without the headaches', 'speedpress-product-addons' ); ?></h2>
			<p><?php esc_html_e( 'Spend less time dealing with website problems and more time focusing on the business.', 'speedpress-product-addons' ); ?></p>
		</div>
		<a class="sppa-btn sppa-btn-primary" href="https://calendar.app.google/LzCoQBSA3Yc17LQ3A" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Talk to SpeedPress', 'speedpress-product-addons' ); ?></a>
	</div>

	<footer class="sppa-social-bar">
		<div>
			<div class="sppa-logo-mark">SP</div>
			<strong><?php esc_html_e( 'SpeedPress', 'speedpress-product-addons' ); ?></strong>
			<span><?php esc_html_e( 'Find us on the channels listed on wpspeedpress.com', 'speedpress-product-addons' ); ?></span>
		</div>
		<nav class="sppa-socials" aria-label="<?php esc_attr_e( 'SpeedPress social', 'speedpress-product-addons' ); ?>">
			<a href="https://wpspeedpress.com" target="_blank" rel="noopener noreferrer" title="Website">
				<span class="dashicons dashicons-admin-site-alt3"></span><span>Website</span>
			</a>
			<a href="https://www.upwork.com/freelancers/wordpressdeveloperspeedseo" target="_blank" rel="noopener noreferrer" title="Upwork">
				<span class="dashicons dashicons-awards"></span><span>Upwork</span>
			</a>
			<a href="https://www.upwork.com/freelancers/~0149190c8d83bae2e2" target="_blank" rel="noopener noreferrer" title="Upwork profile">
				<span class="dashicons dashicons-star-filled"></span><span>Reviews</span>
			</a>
			<a href="https://calendar.app.google/LzCoQBSA3Yc17LQ3A" target="_blank" rel="noopener noreferrer" title="Book a call">
				<span class="dashicons dashicons-calendar-alt"></span><span>Book a call</span>
			</a>
			<a href="https://wpspeedpress.com/#contact" target="_blank" rel="noopener noreferrer" title="Contact">
				<span class="dashicons dashicons-email-alt"></span><span>Contact</span>
			</a>
		</nav>
	</footer>
</div>
