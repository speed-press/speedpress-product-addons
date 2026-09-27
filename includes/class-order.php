<?php
/**
 * Order, email, and admin display.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Order
 */
class SPPA_Order {

	/**
	 * Instance.
	 *
	 * @var SPPA_Order|null
	 */
	private static $instance = null;

	/**
	 * Singleton.
	 *
	 * @return SPPA_Order
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
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_item_meta' ), 20, 4 );
		add_filter( 'woocommerce_order_item_display_meta_key', array( $this, 'pretty_meta_key' ), 10, 3 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_internal_meta' ) );
	}

	/**
	 * Persist add-ons onto the order line item.
	 *
	 * @param WC_Order_Item_Product $item          Item.
	 * @param string                $cart_item_key Key.
	 * @param array                 $values        Cart values.
	 * @param WC_Order              $order         Order.
	 */
	public function save_item_meta( $item, $cart_item_key, $values, $order ) {
		if ( empty( $values['sppa']['lines'] ) ) {
			return;
		}

		$settings = SPPA_Helpers::settings();
		$show     = 'yes' === $settings['show_in_order'] || 'yes' === $settings['show_in_emails'];

		foreach ( $values['sppa']['lines'] as $line ) {
			$display = $line['value'];
			if ( 'yes' === $settings['show_prices_in_cart'] && ! empty( $line['amount'] ) ) {
				$display .= ' (' . wp_strip_all_tags( wc_price( $line['amount'] ) ) . ')';
			}
			if ( $show ) {
				$item->add_meta_data( $line['label'], $display, false );
			}
		}

		$item->add_meta_data( '_sppa_payload', $values['sppa'], true );

		if ( ! empty( $values['sppa']['total'] ) ) {
			$item->add_meta_data( '_sppa_addon_total', $values['sppa']['total'], true );
		}
	}

	/**
	 * Hide internal keys from the customer-facing meta list.
	 *
	 * @param array $hidden Hidden keys.
	 * @return array
	 */
	public function hide_internal_meta( $hidden ) {
		$hidden[] = '_sppa_payload';
		$hidden[] = '_sppa_addon_total';
		return $hidden;
	}

	/**
	 * Leave display keys as-is (already human labels).
	 *
	 * @param string $display_key Key.
	 * @return string
	 */
	public function pretty_meta_key( $display_key ) {
		return $display_key;
	}
}
