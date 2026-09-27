<?php
/**
 * Product data tab.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Product_Metabox
 */
class SPPA_Product_Metabox {

	/**
	 * Instance.
	 *
	 * @var SPPA_Product_Metabox|null
	 */
	private static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return SPPA_Product_Metabox
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
		if ( class_exists( 'SPPA_License' ) && ! SPPA_License::is_active() ) {
			return;
		}
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'panel' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save' ) );
		add_action( 'woocommerce_admin_process_product_object', array( $this, 'save_object' ) );
	}

	/**
	 * Register tab.
	 *
	 * @param array $tabs Tabs.
	 * @return array
	 */
	public function tab( $tabs ) {
		$tabs['sppa_addons'] = array(
			'label'    => __( 'Product Add-Ons', 'speedpress-product-addons' ),
			'target'   => 'sppa_product_data',
			'class'    => array(),
			'priority' => 75,
		);
		return $tabs;
	}

	/**
	 * Panel markup.
	 */
	public function panel() {
		global $post;
		$product_id = $post ? $post->ID : 0;
		$groups     = SPPA_Repository::get_product_groups( $product_id );
		$exclude    = SPPA_Repository::excludes_global( $product_id );
		?>
		<div id="sppa_product_data" class="panel woocommerce_options_panel hidden">
			<div class="options_group">
				<p class="form-field">
					<label for="sppa_exclude_global"><?php esc_html_e( 'Global add-ons', 'speedpress-product-addons' ); ?></label>
					<input type="checkbox" name="sppa_exclude_global" id="sppa_exclude_global" value="yes" <?php checked( $exclude ); ?> />
					<span class="description"><?php esc_html_e( 'Exclude this product from all global add-on groups.', 'speedpress-product-addons' ); ?></span>
				</p>
				<p class="form-field">
					<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sppa_export&scope=product&id=' . $product_id ), 'sppa_export' ) ); ?>"><?php esc_html_e( 'Export JSON', 'speedpress-product-addons' ); ?></a>
				</p>
			</div>
			<div class="options_group" style="padding:12px;">
				<?php SPPA_Admin::render_builder( $groups, 'sppa_groups' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Save from classic product meta.
	 *
	 * @param int $product_id Product ID.
	 */
	public function save( $product_id ) {
		$this->persist( $product_id );
	}

	/**
	 * Save via CRUD object.
	 *
	 * @param WC_Product $product Product.
	 */
	public function save_object( $product ) {
		$this->persist( $product->get_id() );
	}

	/**
	 * Persist groups.
	 *
	 * @param int $product_id Product ID.
	 */
	protected function persist( $product_id ) {
		static $saved = array();
		if ( isset( $saved[ $product_id ] ) ) {
			return;
		}
		$saved[ $product_id ] = true;

		if ( ! isset( $_POST['sppa_groups'] ) && ! isset( $_POST['sppa_exclude_global'] ) && ! isset( $_POST['product-type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return;
		}

		$groups = isset( $_POST['sppa_groups'] ) ? wp_unslash( $_POST['sppa_groups'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput, WordPress.Security.NonceVerification.Missing
		if ( ! is_array( $groups ) ) {
			$groups = array();
		}
		SPPA_Repository::save_product_groups( $product_id, $groups );

		$exclude = ! empty( $_POST['sppa_exclude_global'] ) ? 'yes' : 'no'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_post_meta( $product_id, SPPA_META_EXCLUDE_GLOBAL, $exclude );
	}
}
