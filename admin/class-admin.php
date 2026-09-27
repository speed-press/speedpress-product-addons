<?php
/**
 * Admin settings and assets.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Admin
 */
class SPPA_Admin {

	/**
	 * Instance.
	 *
	 * @var SPPA_Admin|null
	 */
	private static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return SPPA_Admin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hooks.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'menu' ), 64 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'menu_mark' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_filter( 'plugin_action_links_' . SPPA_BASENAME, array( $this, 'links' ) );
	}

	/**
	 * Settings link.
	 *
	 * @param array $links Links.
	 * @return array
	 */
	public function links( $links ) {
		$url = admin_url( 'admin.php?page=sppa-settings' );
		array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'speedpress-product-addons' ) . '</a>' );
		return $links;
	}

	/**
	 * SP mark in the admin menu on every screen.
	 */
	public function menu_mark() {
		wp_enqueue_style( 'sppa-menu', SPPA_URL . 'admin/assets/menu.css', array(), SPPA_VERSION );
	}

	/**
	 * Menu.
	 */
	public function menu() {
		$cap  = 'manage_woocommerce';
		$icon = 'data:image/svg+xml;base64,' . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2563EB"/><stop offset="1" stop-color="#9333EA"/></linearGradient></defs><rect width="36" height="36" rx="10" fill="url(#g)"/><text x="18" y="24" text-anchor="middle" font-size="15" font-family="Inter,Arial,sans-serif" font-weight="800" fill="#fff">SP</text></svg>' );

		add_menu_page(
			__( 'SpeedPress Addons', 'speedpress-product-addons' ),
			__( 'SpeedPress Addons', 'speedpress-product-addons' ),
			$cap,
			'sppa-addons',
			array( $this, 'redirect_to_groups' ),
			$icon,
			56
		);

		add_submenu_page(
			'sppa-addons',
			__( 'Product Add-Ons', 'speedpress-product-addons' ),
			__( 'Product Add-Ons', 'speedpress-product-addons' ),
			$cap,
			'edit.php?post_type=sppa_addon_group'
		);

		add_submenu_page(
			'sppa-addons',
			__( 'Settings', 'speedpress-product-addons' ),
			__( 'Settings', 'speedpress-product-addons' ),
			$cap,
			'sppa-settings',
			array( $this, 'settings_page' )
		);

		add_submenu_page(
			'sppa-addons',
			__( 'Pricing', 'speedpress-product-addons' ),
			__( 'Pricing', 'speedpress-product-addons' ),
			$cap,
			'sppa-pricing',
			array( $this, 'pricing_page' )
		);

		add_submenu_page(
			'sppa-addons',
			__( 'More from SpeedPress', 'speedpress-product-addons' ),
			__( 'More from SpeedPress', 'speedpress-product-addons' ),
			$cap,
			'sppa-projects',
			array( $this, 'projects_page' )
		);

		remove_submenu_page( 'sppa-addons', 'sppa-addons' );
	}

	/**
	 * Top-level fallback.
	 */
	public function redirect_to_groups() {
		wp_safe_redirect( admin_url( 'edit.php?post_type=sppa_addon_group' ) );
		exit;
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'sppa_settings_group',
			'sppa_settings',
			array(
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input.
	 * @return array
	 */
	public function sanitize_settings( $input ) {
		$out = SPPA_Helpers::settings();
		$checks = array( 'show_in_cart', 'show_in_checkout', 'show_in_order', 'show_in_emails', 'show_prices_in_cart', 'show_summary', 'live_price' );
		foreach ( $checks as $key ) {
			$out[ $key ] = ! empty( $input[ $key ] ) ? 'yes' : 'no';
		}
		$out['upload_max_mb']         = absint( $input['upload_max_mb'] ?? 10 );
		$out['allowed_mime_types']    = sanitize_text_field( $input['allowed_mime_types'] ?? 'jpg,jpeg,png,gif,webp,pdf,svg,zip' );
		return $out;
	}

	/**
	 * Enqueue builder assets.
	 *
	 * @param string $hook Hook.
	 */
	public function assets( $hook ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		$load   = false;

		$ok_screens = array(
			'product',
			'sppa_addon_group',
			'edit-sppa_addon_group',
			'toplevel_page_sppa-addons',
			'sppa-addons_page_sppa-settings',
			'sppa-addons_page_sppa-projects',
			'sppa-addons_page_sppa-pricing',
			'woocommerce_page_sppa-settings',
		);
		if ( $screen && in_array( $screen->id, $ok_screens, true ) ) {
			$load = true;
		}
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'sppa-settings', 'sppa-projects', 'sppa-addons', 'sppa-pricing' ), true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$load = true;
		}

		if ( ! $load ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( 'sppa-admin', SPPA_URL . 'admin/assets/admin.css', array(), SPPA_VERSION );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'sppa-admin', SPPA_URL . 'admin/assets/admin.js', array( 'jquery', 'jquery-ui-sortable' ), SPPA_VERSION, true );
		wp_localize_script(
			'sppa-admin',
			'sppaAdmin',
			array(
				'types'      => SPPA_Field_Types::all(),
				'operators'  => SPPA_Conditions::operators(),
				'priceTypes' => array(
					'none'       => __( 'No extra price', 'speedpress-product-addons' ),
					'flat'       => __( 'Fixed', 'speedpress-product-addons' ),
					'percentage' => __( 'Percentage of product', 'speedpress-product-addons' ),
					'quantity'   => __( 'Quantity × price', 'speedpress-product-addons' ),
					'character'  => __( 'Character × price', 'speedpress-product-addons' ),
					'length'     => __( 'Length × price', 'speedpress-product-addons' ),
					'area'       => __( 'Area × price', 'speedpress-product-addons' ),
					'weight'     => __( 'Weight × price', 'speedpress-product-addons' ),
					'negative'   => __( 'Discount (negative)', 'speedpress-product-addons' ),
				),
				'isPremium'  => class_exists( 'SPPA_License' ) ? SPPA_License::is_premium() : false,
				'freeTypes'  => class_exists( 'SPPA_License' ) ? SPPA_License::free_types() : array(),
				'i18n'       => array(
					'group'         => __( 'Option group', 'speedpress-product-addons' ),
					'field'         => __( 'New field', 'speedpress-product-addons' ),
					'option'        => __( 'Option', 'speedpress-product-addons' ),
					'confirmDel'    => __( 'Remove this item?', 'speedpress-product-addons' ),
					'removeField'   => __( 'Remove this field?', 'speedpress-product-addons' ),
					'removeGroup'   => __( 'Remove this group?', 'speedpress-product-addons' ),
					'removeFieldTx' => __( 'The field and its options will be deleted from this group after you save.', 'speedpress-product-addons' ),
					'removeGroupTx' => __( 'This group and every field inside it will be removed after you save.', 'speedpress-product-addons' ),
					'keep'          => __( 'Keep it', 'speedpress-product-addons' ),
					'remove'        => __( 'Remove', 'speedpress-product-addons' ),
					'premiumOnly'   => __( 'This field is Premium. Activate a Premium, Lifetime, or Agency code to use it.', 'speedpress-product-addons' ),
				),
			)
		);
	}

	/**
	 * Settings page markup.
	 */
	public function settings_page() {
		$s = SPPA_Helpers::settings();
		?>
		<div class="wrap sppa-settings">
			<div class="sppa-app-hero">
				<div>
					<h1><?php esc_html_e( 'Product Add-Ons', 'speedpress-product-addons' ); ?></h1>
					<p><?php esc_html_e( 'A product options workspace with conditional logic, live pricing, and storefront-ready fields.', 'speedpress-product-addons' ); ?></p>
				</div>
				<span class="sppa-pill">SpeedPress · 1.1.1</span>
			</div>

			<?php $lic = class_exists( 'SPPA_License' ) ? SPPA_License::data() : array(); ?>
			<div class="sppa-card" style="margin-bottom:16px">
				<h2><?php esc_html_e( 'API code', 'speedpress-product-addons' ); ?></h2>
				<p class="sppa-card-sub"><?php esc_html_e( 'This plugin stays locked until you enter the API code SpeedPress issued for this website. Activation reports this site URL to wpspeedpress.com so you can see who installed it.', 'speedpress-product-addons' ); ?></p>
				<?php if ( ! empty( $lic['status'] ) && 'active' === $lic['status'] ) : ?>
					<p><strong><?php esc_html_e( 'Status: Active', 'speedpress-product-addons' ); ?></strong>
					<?php if ( ! empty( $lic['site'] ) ) : ?>
						— <?php echo esc_html( $lic['site'] ); ?>
					<?php endif; ?></p>
				<?php else : ?>
					<p><strong><?php esc_html_e( 'Status: Inactive', 'speedpress-product-addons' ); ?></strong>
					<?php if ( ! empty( $lic['message'] ) ) : ?>
						— <?php echo esc_html( $lic['message'] ); ?>
					<?php endif; ?></p>
				<?php endif; ?>
				<form method="post">
					<?php wp_nonce_field( 'sppa_license' ); ?>
					<div class="sppa-field-row">
						<label for="sppa_license_key"><?php esc_html_e( 'API code', 'speedpress-product-addons' ); ?></label>
						<input type="text" id="sppa_license_key" name="sppa_license_key" value="<?php echo esc_attr( $lic['key'] ?? '' ); ?>" class="regular-text" autocomplete="off" placeholder="SPPA-XXXX-XXXX-XXXX" />
					</div>
					<p>
						<button type="submit" name="sppa_license_action" value="activate" class="button button-primary"><?php esc_html_e( 'Activate', 'speedpress-product-addons' ); ?></button>
						<button type="submit" name="sppa_license_action" value="deactivate" class="button"><?php esc_html_e( 'Deactivate', 'speedpress-product-addons' ); ?></button>
						<button type="submit" name="sppa_license_action" value="remove" class="button"><?php esc_html_e( 'Remove API code', 'speedpress-product-addons' ); ?></button>
					</p>
				</form>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( 'sppa_settings_group' ); ?>
				<div class="sppa-settings-grid">
					<div class="sppa-card">
						<h2><?php esc_html_e( 'Experience', 'speedpress-product-addons' ); ?></h2>
						<p class="sppa-card-sub"><?php esc_html_e( 'Control where selected options appear and how prices update.', 'speedpress-product-addons' ); ?></p>
						<div class="sppa-toggle-list">
							<label class="sppa-toggle"><span><?php esc_html_e( 'Show selections in cart', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_in_cart]" value="yes" <?php checked( $s['show_in_cart'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Show selections at checkout', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_in_checkout]" value="yes" <?php checked( $s['show_in_checkout'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Show on order received / My Account', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_in_order]" value="yes" <?php checked( $s['show_in_order'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Include in emails', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_in_emails]" value="yes" <?php checked( $s['show_in_emails'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Show add-on prices in cart', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_prices_in_cart]" value="yes" <?php checked( $s['show_prices_in_cart'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Live price as options change', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[live_price]" value="yes" <?php checked( $s['live_price'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
							<label class="sppa-toggle"><span><?php esc_html_e( 'Show configuration summary', 'speedpress-product-addons' ); ?></span><span class="sppa-switch"><input type="checkbox" name="sppa_settings[show_summary]" value="yes" <?php checked( $s['show_summary'], 'yes' ); ?> /><span class="sppa-switch-ui"></span></span></label>
						</div>
						<div style="margin-top:18px">
							<div class="sppa-field-row">
								<label for="sppa_upload_max"><?php esc_html_e( 'Upload max (MB)', 'speedpress-product-addons' ); ?></label>
								<input type="number" id="sppa_upload_max" name="sppa_settings[upload_max_mb]" value="<?php echo esc_attr( $s['upload_max_mb'] ); ?>" min="1" max="128" />
							</div>
							<div class="sppa-field-row">
								<label for="sppa_mimes"><?php esc_html_e( 'Allowed file types', 'speedpress-product-addons' ); ?></label>
								<input type="text" id="sppa_mimes" name="sppa_settings[allowed_mime_types]" value="<?php echo esc_attr( $s['allowed_mime_types'] ); ?>" />
							</div>
							<?php submit_button( __( 'Save workspace', 'speedpress-product-addons' ) ); ?>
						</div>
					</div>
					<div class="sppa-card">
						<h2><?php esc_html_e( 'Setup', 'speedpress-product-addons' ); ?></h2>
						<p class="sppa-card-sub"><?php esc_html_e( 'Two places to attach options.', 'speedpress-product-addons' ); ?></p>
						<ul class="sppa-help-list">
							<li><?php echo wp_kses_post( __( 'Per product: edit a product → <strong>Product Add-Ons</strong> tab.', 'speedpress-product-addons' ) ); ?></li>
							<li><?php echo wp_kses_post( sprintf( __( 'Store-wide: <a href="%s">Global Add-On Groups</a>.', 'speedpress-product-addons' ), esc_url( admin_url( 'edit.php?post_type=sppa_addon_group' ) ) ) ); ?></li>
							<li><?php esc_html_e( 'Use each field ID in conditional rules. IDs appear as pills on the field row.', 'speedpress-product-addons' ); ?></li>
						</ul>
					</div>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Sister products / other SpeedPress projects.
	 */
	public function projects_page() {
		include SPPA_PATH . 'admin/views/projects.php';
	}

	/**
	 * Pricing explainer.
	 */
	public function pricing_page() {
		include SPPA_PATH . 'admin/views/pricing.php';
	}


	/**
	 * Shared builder root used by product tab and global CPT.
	 *
	 * @param array  $groups Groups.
	 * @param string $name   Input name prefix, e.g. sppa_groups.
	 */
	public static function render_builder( $groups, $name = 'sppa_groups' ) {
		$groups = is_array( $groups ) ? $groups : array();
		include SPPA_PATH . 'admin/views/builder.php';
	}
}
