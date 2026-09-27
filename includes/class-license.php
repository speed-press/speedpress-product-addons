<?php
/**
 * License key activation against the SpeedPress license server.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_License
 */
class SPPA_License {

	const OPTION = 'sppa_license';

	/**
	 * Instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return self
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
		add_action( 'admin_init', array( $this, 'handle_form' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
		add_action( 'sppa_license_check', array( $this, 'recheck' ) );
		if ( ! wp_next_scheduled( 'sppa_license_check' ) ) {
			wp_schedule_event( time() + DAY_IN_SECONDS, 'weekly', 'sppa_license_check' );
		}
	}

	/**
	 * Default license server (your site).
	 *
	 * @return string
	 */
	public static function server_url() {
		if ( defined( 'SPPA_LICENSE_SERVER' ) && SPPA_LICENSE_SERVER ) {
			return untrailingslashit( SPPA_LICENSE_SERVER );
		}
		return 'https://wpspeedpress.com';
	}

	/**
	 * Stored license data.
	 *
	 * @return array
	 */
	public static function data() {
		$defaults = array(
			'key'        => '',
			'last_key'   => '',
			'plan'       => '',
			'billing'    => '',
			'status'     => 'inactive',
			'site'       => '',
			'message'    => '',
			'checked_at' => 0,
		);
		return wp_parse_args( get_option( self::OPTION, array() ), $defaults );
	}

	/**
	 * Whether the plugin is allowed to run storefront features.
	 *
	 * @return bool
	 */
	public static function is_active() {
		if ( defined( 'SPPA_LICENSE_BYPASS' ) && SPPA_LICENSE_BYPASS ) {
			return true;
		}
		$data = self::data();
		return 'active' === $data['status'] && ! empty( $data['key'] );
	}

	/**
	 * Paid plans that unlock every field and rule.
	 *
	 * @return bool
	 */
	public static function is_premium() {
		if ( defined( 'SPPA_LICENSE_BYPASS' ) && SPPA_LICENSE_BYPASS ) {
			return true;
		}
		if ( ! self::is_active() ) {
			return false;
		}
		$plan = self::data()['plan'] ?? '';
		return in_array( $plan, array( 'premium', 'lifetime', 'agency' ), true );
	}

	/**
	 * Field types allowed on the Free plan.
	 *
	 * @return array
	 */
	public static function free_types() {
		return array( 'text', 'textarea', 'number', 'email', 'select', 'radio', 'checkbox', 'heading', 'paragraph' );
	}

	/**
	 * Whether a field type is allowed for the current license.
	 *
	 * @param string $type Type.
	 * @return bool
	 */
	public static function allows_type( $type ) {
		if ( self::is_premium() ) {
			return true;
		}
		return in_array( $type, self::free_types(), true );
	}

	/**
	 * Process activate / deactivate from Settings.
	 */
	public function handle_form() {
		if ( empty( $_POST['sppa_license_action'] ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		check_admin_referer( 'sppa_license' );

		$action = sanitize_key( wp_unslash( $_POST['sppa_license_action'] ) );
		$key    = sanitize_text_field( wp_unslash( $_POST['sppa_license_key'] ?? '' ) );

		if ( 'activate' === $action ) {
			$this->activate( $key );
		} elseif ( 'deactivate' === $action ) {
			$this->deactivate();
		} elseif ( 'remove' === $action ) {
			$this->remove_key();
		}
	}

	/**
	 * Common identity payload.
	 *
	 * @param string $key Key.
	 * @return array
	 */
	private function identity( $key = '' ) {
		return array(
			'key'            => $key,
			'site_url'       => home_url(),
			'site_name'      => get_bloginfo( 'name' ),
			'admin_email'    => get_option( 'admin_email' ),
			'plugin_version' => defined( 'SPPA_VERSION' ) ? SPPA_VERSION : '',
			'wp_version'     => get_bloginfo( 'version' ),
			'wc_version'     => defined( 'WC_VERSION' ) ? WC_VERSION : '',
		);
	}

	/**
	 * Activate against your server.
	 *
	 * @param string $key API / license code.
	 * @return bool
	 */
	public function activate( $key ) {
		$key = strtoupper( preg_replace( '/[^A-Z0-9\-]/i', '', $key ) );
		if ( strlen( $key ) < 8 ) {
			$this->store(
				array(
					'key'     => $key,
					'status'  => 'inactive',
					'message' => __( 'Enter a valid API code.', 'speedpress-product-addons' ),
				)
			);
			return false;
		}

		$response = $this->request( 'activate', $this->identity( $key ) );

		if ( is_wp_error( $response ) ) {
			$this->store(
				array(
					'key'      => $key,
					'last_key' => $key,
					'status'   => 'inactive',
					'message'  => $response->get_error_message(),
				)
			);
			return false;
		}

		$ok = ! empty( $response['success'] );
		$this->store(
			array(
				'key'        => $key,
				'last_key'   => $key,
				'plan'       => sanitize_key( $response['plan'] ?? '' ),
				'billing'    => sanitize_key( $response['billing'] ?? '' ),
				'status'     => $ok ? 'active' : 'inactive',
				'site'       => home_url(),
				'message'    => $response['message'] ?? ( $ok ? __( 'Activated.', 'speedpress-product-addons' ) : __( 'Invalid API code.', 'speedpress-product-addons' ) ),
				'checked_at' => time(),
			)
		);
		return $ok;
	}

	/**
	 * Deactivate this site. Server keeps the website row.
	 */
	public function deactivate() {
		$data = self::data();
		$key  = $data['key'] ? $data['key'] : $data['last_key'];
		$this->request( 'deactivate', $this->identity( $key ) );
		$this->store(
			array(
				'key'        => $key,
				'last_key'   => $key,
				'status'     => 'inactive',
				'message'    => __( 'Deactivated on this site. SpeedPress still has this website on file.', 'speedpress-product-addons' ),
				'checked_at' => time(),
			)
		);
	}

	/**
	 * User cleared the API code. Report key_removed, keep last_key for the server.
	 */
	public function remove_key() {
		$data = self::data();
		$key  = $data['key'] ? $data['key'] : $data['last_key'];
		$this->report_status( 'key_removed', $key );
		$this->store(
			array(
				'key'        => '',
				'last_key'   => $key,
				'status'     => 'inactive',
				'message'    => __( 'API code removed on this site.', 'speedpress-product-addons' ),
				'checked_at' => time(),
			)
		);
	}

	/**
	 * Tell the license server a status without deleting the site.
	 *
	 * @param string $status Status slug.
	 * @param string $key    Key.
	 */
	public function report_status( $status, $key = '' ) {
		$data = self::data();
		if ( ! $key ) {
			$key = $data['key'] ? $data['key'] : $data['last_key'];
		}
		$body           = $this->identity( $key );
		$body['status'] = $status;
		$this->request( 'report', $body );
	}

	/**
	 * Weekly re-check. If the key is gone, still ping so last-seen stays fresh.
	 */
	public function recheck() {
		$data = self::data();
		if ( ! empty( $data['key'] ) ) {
			$this->activate( $data['key'] );
			return;
		}
		if ( ! empty( $data['last_key'] ) ) {
			$this->report_status( 'key_removed', $data['last_key'] );
		}
	}

	/**
	 * Admin notice if locked.
	 */
	public function notice() {
		if ( self::is_active() ) {
			return;
		}
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( $screen && isset( $screen->id ) && false !== strpos( (string) $screen->id, 'sppa-settings' ) ) {
			return;
		}
		$url = admin_url( 'admin.php?page=sppa-settings' );
		echo '<div class="notice notice-warning"><p>';
		echo esc_html__( 'SpeedPress Product Add-Ons needs an API code before it will run on this site.', 'speedpress-product-addons' );
		echo ' <a href="' . esc_url( $url ) . '">' . esc_html__( 'Enter API code', 'speedpress-product-addons' ) . '</a>';
		echo '</p></div>';
	}

	/**
	 * POST to the license server on your domain.
	 *
	 * @param string $action activate|deactivate|report.
	 * @param array  $body   Body.
	 * @return array|WP_Error
	 */
	private function request( $action, $body ) {
		$url  = trailingslashit( self::server_url() ) . 'wp-json/speedpress-license/v1/' . $action;
		$args = array(
			'timeout' => 15,
			'headers' => array( 'Content-Type' => 'application/json' ),
			'body'    => wp_json_encode( $body ),
		);
		$res  = wp_remote_post( $url, $args );
		if ( is_wp_error( $res ) ) {
			return $res;
		}
		$code = wp_remote_retrieve_response_code( $res );
		$data = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( $code < 200 || $code >= 300 || ! is_array( $data ) ) {
			return new WP_Error( 'sppa_license_http', __( 'Could not reach the SpeedPress license server.', 'speedpress-product-addons' ) );
		}
		return $data;
	}

	/**
	 * Persist license option.
	 *
	 * @param array $data Data.
	 */
	private function store( $data ) {
		update_option( self::OPTION, wp_parse_args( $data, self::data() ) );
	}
}
