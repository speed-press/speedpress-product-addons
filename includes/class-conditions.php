<?php
/**
 * Conditional logic evaluator.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Conditions
 */
class SPPA_Conditions {

	/**
	 * Whether a field should be visible given current values.
	 *
	 * @param array $field  Field.
	 * @param array $values Values keyed by field id.
	 * @param array $groups Groups (unused except for future product-type rules).
	 * @return bool
	 */
	public static function field_is_visible( $field, $values, $groups = array() ) {
		$cond = $field['conditions'] ?? array();
		if ( empty( $cond['enabled'] ) || empty( $cond['rules'] ) ) {
			return true;
		}

		$match   = ( isset( $cond['match'] ) && 'any' === $cond['match'] ) ? 'any' : 'all';
		$action  = ( isset( $cond['action'] ) && 'hide' === $cond['action'] ) ? 'hide' : 'show';
		$results = array();

		foreach ( $cond['rules'] as $rule ) {
			$results[] = self::rule_matches( $rule, $values );
		}

		$ok = ( 'any' === $match ) ? in_array( true, $results, true ) : ! in_array( false, $results, true );

		if ( 'hide' === $action ) {
			return ! $ok;
		}
		return $ok;
	}

	/**
	 * Evaluate a single rule.
	 *
	 * @param array $rule   Rule.
	 * @param array $values Values.
	 * @return bool
	 */
	public static function rule_matches( $rule, $values ) {
		$field_id = $rule['field'] ?? '';
		$op       = $rule['operator'] ?? 'is';
		$expected = isset( $rule['value'] ) ? (string) $rule['value'] : '';
		$actual   = isset( $values[ $field_id ] ) ? $values[ $field_id ] : '';

		if ( is_array( $actual ) ) {
			$haystack = array_map( 'strval', $actual );
			switch ( $op ) {
				case 'is':
				case 'equals':
					return in_array( $expected, $haystack, true );
				case 'is_not':
				case 'not_equals':
					return ! in_array( $expected, $haystack, true );
				case 'is_empty':
					return empty( $haystack );
				case 'is_not_empty':
					return ! empty( $haystack );
				case 'contains':
					foreach ( $haystack as $item ) {
						if ( false !== stripos( $item, $expected ) ) {
							return true;
						}
					}
					return false;
				default:
					return in_array( $expected, $haystack, true );
			}
		}

		$actual = (string) $actual;

		switch ( $op ) {
			case 'is':
			case 'equals':
				return $actual === $expected;
			case 'is_not':
			case 'not_equals':
				return $actual !== $expected;
			case 'contains':
				return $expected !== '' && false !== stripos( $actual, $expected );
			case 'not_contains':
				return $expected === '' || false === stripos( $actual, $expected );
			case 'greater_than':
				return is_numeric( $actual ) && is_numeric( $expected ) && (float) $actual > (float) $expected;
			case 'less_than':
				return is_numeric( $actual ) && is_numeric( $expected ) && (float) $actual < (float) $expected;
			case 'greater_or_equal':
				return is_numeric( $actual ) && is_numeric( $expected ) && (float) $actual >= (float) $expected;
			case 'less_or_equal':
				return is_numeric( $actual ) && is_numeric( $expected ) && (float) $actual <= (float) $expected;
			case 'is_empty':
				return '' === $actual;
			case 'is_not_empty':
				return '' !== $actual;
			default:
				return $actual === $expected;
		}
	}

	/**
	 * Operators for the admin builder.
	 *
	 * @return array
	 */
	public static function operators() {
		return array(
			'is'               => __( 'is', 'speedpress-product-addons' ),
			'is_not'           => __( 'is not', 'speedpress-product-addons' ),
			'contains'         => __( 'contains', 'speedpress-product-addons' ),
			'not_contains'     => __( 'does not contain', 'speedpress-product-addons' ),
			'greater_than'     => __( 'greater than', 'speedpress-product-addons' ),
			'less_than'        => __( 'less than', 'speedpress-product-addons' ),
			'greater_or_equal' => __( 'greater or equal', 'speedpress-product-addons' ),
			'less_or_equal'    => __( 'less or equal', 'speedpress-product-addons' ),
			'is_empty'         => __( 'is empty', 'speedpress-product-addons' ),
			'is_not_empty'     => __( 'is not empty', 'speedpress-product-addons' ),
		);
	}
}
