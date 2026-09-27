<?php
/**
 * JSON import / export.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Import_Export
 */
class SPPA_Import_Export {

	/**
	 * Instance.
	 *
	 * @var SPPA_Import_Export|null
	 */
	private static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return SPPA_Import_Export
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
		add_action( 'admin_post_sppa_export', array( $this, 'export' ) );
		add_action( 'admin_post_sppa_import', array( $this, 'import' ) );
	}

	/**
	 * Export product or global groups as JSON.
	 */
	public function export() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'speedpress-product-addons' ) );
		}
		check_admin_referer( 'sppa_export' );

		$scope = sanitize_key( $_GET['scope'] ?? 'product' );
		$id    = absint( $_GET['id'] ?? 0 );

		if ( 'global' === $scope ) {
			$payload = array(
				'type'   => 'sppa_global_groups',
				'groups' => SPPA_Repository::get_global_groups(),
			);
			$filename = 'sppa-global-addons.json';
		} else {
			$payload = array(
				'type'           => 'sppa_product_groups',
				'product_id'     => $id,
				'exclude_global' => SPPA_Repository::excludes_global( $id ),
				'groups'         => SPPA_Repository::get_product_groups( $id ),
			);
			$filename = 'sppa-product-' . $id . '-addons.json';
		}

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		echo wp_json_encode( $payload, JSON_PRETTY_PRINT );
		exit;
	}

	/**
	 * Import JSON onto a product.
	 */
	public function import() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_die( esc_html__( 'Permission denied.', 'speedpress-product-addons' ) );
		}
		check_admin_referer( 'sppa_import' );

		$product_id = absint( $_POST['product_id'] ?? 0 );
		if ( ! $product_id || empty( $_FILES['sppa_import_file']['tmp_name'] ) ) {
			wp_safe_redirect( wp_get_referer() );
			exit;
		}

		$json = file_get_contents( $_FILES['sppa_import_file']['tmp_name'] ); // phpcs:ignore
		$data = json_decode( $json, true );
		if ( ! is_array( $data ) || empty( $data['groups'] ) ) {
			wp_safe_redirect( add_query_arg( 'sppa_import', 'fail', wp_get_referer() ) );
			exit;
		}

		SPPA_Repository::save_product_groups( $product_id, $data['groups'] );
		if ( isset( $data['exclude_global'] ) ) {
			update_post_meta( $product_id, SPPA_META_EXCLUDE_GLOBAL, $data['exclude_global'] ? 'yes' : 'no' );
		}

		wp_safe_redirect( add_query_arg( 'sppa_import', 'ok', wp_get_referer() ) );
		exit;
	}
}
