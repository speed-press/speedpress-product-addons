<?php
/**
 * Add-to-cart validation.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Validation
 */
class SPPA_Validation {

	/**
	 * Validate posted add-ons for a product.
	 *
	 * @param bool $passed     Current state.
	 * @param int  $product_id Product ID.
	 * @param int  $qty        Qty.
	 * @return bool
	 */
	public static function validate_add_to_cart( $passed, $product_id, $qty ) {
		$groups = SPPA_Resolver::for_product( $product_id );
		if ( empty( $groups ) ) {
			return $passed;
		}

		$values = self::collect_posted_values( $groups );

		foreach ( SPPA_Helpers::flatten_fields( $groups ) as $field ) {
			if ( ! SPPA_Field_Types::is_input( $field['type'] ) ) {
				continue;
			}
			if ( ! SPPA_Conditions::field_is_visible( $field, $values, $groups ) ) {
				continue;
			}

			$raw = $values[ $field['id'] ] ?? null;
			$ok  = self::validate_field( $field, $raw );
			if ( is_wp_error( $ok ) ) {
				wc_add_notice( $ok->get_error_message(), 'error' );
				$passed = false;
			}
		}

		return $passed;
	}

	/**
	 * Validate one field.
	 *
	 * @param array $field Field.
	 * @param mixed $raw   Value.
	 * @return true|WP_Error
	 */
	public static function validate_field( $field, $raw ) {
		$label = $field['label'] ? $field['label'] : __( 'This field', 'speedpress-product-addons' );
		$empty = SPPA_Pricing::is_empty_value( $raw, $field['type'] );

		if ( ! empty( $field['required'] ) && $empty ) {
			$msg = $field['required_message'] ? $field['required_message'] : sprintf(
				/* translators: %s field label */
				__( '%s is required.', 'speedpress-product-addons' ),
				$label
			);
			return new WP_Error( 'sppa_required', $msg );
		}

		if ( $empty ) {
			return true;
		}

		if ( 'email' === $field['type'] && ! is_email( is_string( $raw ) ? $raw : '' ) ) {
			return new WP_Error( 'sppa_email', sprintf( __( '%s must be a valid email address.', 'speedpress-product-addons' ), $label ) );
		}

		if ( in_array( $field['type'], array( 'text', 'textarea' ), true ) && is_string( $raw ) ) {
			$len = SPPA_Pricing::char_count( $raw );
			if ( '' !== $field['min'] && $len < (int) $field['min'] ) {
				return new WP_Error( 'sppa_min', sprintf( __( '%1$s must be at least %2$d characters.', 'speedpress-product-addons' ), $label, (int) $field['min'] ) );
			}
			if ( '' !== $field['max'] && $len > (int) $field['max'] ) {
				return new WP_Error( 'sppa_max', sprintf( __( '%1$s must be at most %2$d characters.', 'speedpress-product-addons' ), $label, (int) $field['max'] ) );
			}
			if ( ! empty( $field['regex'] ) ) {
				$pattern = $field['regex'];
				if ( @preg_match( '/' . str_replace( '/', '\/', $pattern ) . '/', $raw ) !== 1 ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
					return new WP_Error( 'sppa_regex', sprintf( __( '%s is not in the required format.', 'speedpress-product-addons' ), $label ) );
				}
			}
			if ( 'letters' === ( $field['allowed_chars'] ?? '' ) && ! preg_match( '/^[\p{L}\s]+$/u', $raw ) ) {
				return new WP_Error( 'sppa_chars', sprintf( __( '%s may contain letters only.', 'speedpress-product-addons' ), $label ) );
			}
			if ( 'numbers' === ( $field['allowed_chars'] ?? '' ) && ! preg_match( '/^[0-9]+$/', $raw ) ) {
				return new WP_Error( 'sppa_chars', sprintf( __( '%s may contain numbers only.', 'speedpress-product-addons' ), $label ) );
			}
		}

		if ( in_array( $field['type'], array( 'number', 'quantity', 'customer_price' ), true ) && is_numeric( $raw ) ) {
			$n = (float) $raw;
			if ( '' !== $field['min'] && $n < (float) $field['min'] ) {
				return new WP_Error( 'sppa_min', sprintf( __( '%s is below the minimum.', 'speedpress-product-addons' ), $label ) );
			}
			if ( '' !== $field['max'] && $n > (float) $field['max'] ) {
				return new WP_Error( 'sppa_max', sprintf( __( '%s is above the maximum.', 'speedpress-product-addons' ), $label ) );
			}
		}

		if ( 'file' === $field['type'] ) {
			return self::validate_files( $field );
		}

		if ( ! empty( $field['stock_enabled'] ) && SPPA_Field_Types::has_options( $field['type'] ) ) {
			$selected = is_array( $raw ) ? $raw : array( $raw );
			foreach ( $field['options'] as $option ) {
				if ( ! in_array( (string) $option['id'], array_map( 'strval', $selected ), true ) ) {
					continue;
				}
				if ( '' !== $option['stock'] && (int) $option['stock'] <= 0 ) {
					return new WP_Error( 'sppa_stock', sprintf( __( '%s is out of stock.', 'speedpress-product-addons' ), $option['label'] ) );
				}
			}
		}

		return true;
	}

	/**
	 * Validate uploaded files for a field.
	 *
	 * @param array $field Field.
	 * @return true|WP_Error
	 */
	public static function validate_files( $field ) {
		$key = 'sppa_' . $field['id'];
		if ( empty( $_FILES[ $key ] ) ) {
			if ( ! empty( $field['required'] ) ) {
				return new WP_Error( 'sppa_file', sprintf( __( '%s requires a file upload.', 'speedpress-product-addons' ), $field['label'] ) );
			}
			return true;
		}

		$files = self::normalize_files( $_FILES[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$max_mb   = absint( $field['max_file_mb'] ?: 10 );
		$max_n    = max( 1, absint( $field['max_files'] ?: 1 ) );
		$allowed  = array_filter( array_map( 'trim', explode( ',', strtolower( $field['allowed_types'] ?? 'jpg,jpeg,png,pdf' ) ) ) );

		if ( count( $files ) > $max_n ) {
			return new WP_Error( 'sppa_files', sprintf( __( '%s allows a maximum of %d files.', 'speedpress-product-addons' ), $field['label'], $max_n ) );
		}

		foreach ( $files as $file ) {
			if ( empty( $file['name'] ) || ! empty( $file['error'] ) ) {
				if ( ! empty( $field['required'] ) && (int) $file['error'] === UPLOAD_ERR_NO_FILE ) {
					return new WP_Error( 'sppa_file', sprintf( __( '%s requires a file upload.', 'speedpress-product-addons' ), $field['label'] ) );
				}
				if ( ! empty( $file['error'] ) && (int) $file['error'] !== UPLOAD_ERR_NO_FILE ) {
					return new WP_Error( 'sppa_file', sprintf( __( 'Could not upload the file for %s.', 'speedpress-product-addons' ), $field['label'] ) );
				}
				continue;
			}
			$ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
			if ( $allowed && ! in_array( $ext, $allowed, true ) ) {
				return new WP_Error( 'sppa_mime', sprintf( __( 'File type .%s is not allowed for %s.', 'speedpress-product-addons' ), $ext, $field['label'] ) );
			}
			if ( $file['size'] > $max_mb * 1024 * 1024 ) {
				return new WP_Error( 'sppa_size', sprintf( __( 'File for %s exceeds the %dMB limit.', 'speedpress-product-addons' ), $field['label'], $max_mb ) );
			}
		}

		return true;
	}

	/**
	 * Normalize $_FILES structure to a list.
	 *
	 * @param array $file File array.
	 * @return array
	 */
	public static function normalize_files( $file ) {
		if ( ! isset( $file['name'] ) ) {
			return array();
		}
		if ( ! is_array( $file['name'] ) ) {
			return array( $file );
		}
		$out = array();
		foreach ( $file['name'] as $i => $name ) {
			$out[] = array(
				'name'     => $name,
				'type'     => $file['type'][ $i ] ?? '',
				'tmp_name' => $file['tmp_name'][ $i ] ?? '',
				'error'    => $file['error'][ $i ] ?? 0,
				'size'     => $file['size'][ $i ] ?? 0,
			);
		}
		return $out;
	}

	/**
	 * Collect posted values (not files).
	 *
	 * @param array $groups Groups.
	 * @return array
	 */
	public static function collect_posted_values( $groups ) {
		$values = array();
		$posted = isset( $_POST['sppa'] ) ? wp_unslash( $_POST['sppa'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput
		if ( ! is_array( $posted ) ) {
			$posted = array();
		}

		foreach ( SPPA_Helpers::flatten_fields( $groups ) as $field ) {
			$id = $field['id'];
			if ( 'file' === $field['type'] ) {
				continue;
			}
			if ( ! isset( $posted[ $id ] ) ) {
				continue;
			}
			$raw = $posted[ $id ];
			if ( is_array( $raw ) ) {
				$values[ $id ] = array_map( 'sanitize_text_field', $raw );
			} else {
				$values[ $id ] = ( 'textarea' === $field['type'] ) ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
			}
		}

		return $values;
	}
}
