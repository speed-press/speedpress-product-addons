<?php
/**
 * Registered field types.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Field_Types
 */
class SPPA_Field_Types {

	/**
	 * All field types.
	 *
	 * @return array
	 */
	public static function all() {
		return apply_filters(
			'sppa_field_types',
			array(
				'text'            => array(
					'label'      => __( 'Text', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'textarea'        => array(
					'label'      => __( 'Textarea', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'number'          => array(
					'label'      => __( 'Number', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'email'           => array(
					'label'      => __( 'Email', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'phone'           => array(
					'label'      => __( 'Phone', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'url'             => array(
					'label'      => __( 'URL', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'select'          => array(
					'label'      => __( 'Dropdown', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'radio'           => array(
					'label'      => __( 'Radio', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'checkbox'        => array(
					'label'      => __( 'Checkbox', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'checkbox_group'  => array(
					'label'      => __( 'Checkbox Group', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'multiselect'     => array(
					'label'      => __( 'Multi-select', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'image_radio'     => array(
					'label'      => __( 'Image Radio', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'image_checkbox'  => array(
					'label'      => __( 'Image Checkbox', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'color'           => array(
					'label'      => __( 'Color Picker', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'color_swatch'    => array(
					'label'      => __( 'Color Swatches', 'speedpress-product-addons' ),
					'has_options'=> true,
					'has_price'  => true,
					'input'      => true,
				),
				'file'            => array(
					'label'      => __( 'File Upload', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'date'            => array(
					'label'      => __( 'Date Picker', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'time'            => array(
					'label'      => __( 'Time Picker', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'quantity'        => array(
					'label'      => __( 'Quantity', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
				'customer_price'  => array(
					'label'      => __( 'Customer Price', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => false,
					'input'      => true,
				),
				'heading'         => array(
					'label'      => __( 'Heading', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => false,
					'input'      => false,
				),
				'paragraph'       => array(
					'label'      => __( 'Paragraph / HTML', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => false,
					'input'      => false,
				),
				'hidden'          => array(
					'label'      => __( 'Hidden Field', 'speedpress-product-addons' ),
					'has_options'=> false,
					'has_price'  => true,
					'input'      => true,
				),
			)
		);
	}

	/**
	 * Types that collect a customer value.
	 *
	 * @param string $type Type.
	 * @return bool
	 */
	public static function is_input( $type ) {
		$all = self::all();
		return ! empty( $all[ $type ]['input'] );
	}

	/**
	 * Types that have option rows.
	 *
	 * @param string $type Type.
	 * @return bool
	 */
	public static function has_options( $type ) {
		$all = self::all();
		return ! empty( $all[ $type ]['has_options'] );
	}

	/**
	 * Multi-value types.
	 *
	 * @param string $type Type.
	 * @return bool
	 */
	public static function is_multi( $type ) {
		return in_array( $type, array( 'checkbox_group', 'multiselect', 'image_checkbox' ), true );
	}
}
