<?php
/**
 * One condition rule.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$operators = isset( $operators ) ? $operators : SPPA_Conditions::operators();
$rprefix   = $name . '[' . $gi . '][fields][' . $fi . '][conditions][rules][' . $ri . ']';
?>
<div class="sppa-rule" data-index="<?php echo esc_attr( $ri ); ?>">
	<input type="text" class="sppa-rule-field" name="<?php echo esc_attr( $rprefix ); ?>[field]" value="<?php echo esc_attr( $rule['field'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Other field ID', 'speedpress-product-addons' ); ?>" />
	<select name="<?php echo esc_attr( $rprefix ); ?>[operator]">
		<?php foreach ( $operators as $ok => $ol ) : ?>
			<option value="<?php echo esc_attr( $ok ); ?>" <?php selected( ( $rule['operator'] ?? 'is' ), $ok ); ?>><?php echo esc_html( $ol ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="text" name="<?php echo esc_attr( $rprefix ); ?>[value]" value="<?php echo esc_attr( $rule['value'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Value or option ID', 'speedpress-product-addons' ); ?>" />
	<button type="button" class="button-link-delete sppa-remove-rule">&times;</button>
</div>
<p class="description sppa-rule-hint"><?php esc_html_e( 'Use the other field’s ID (shown in Settings after you save once, or copy from the hidden field id). For choice fields, compare against the option ID or label.', 'speedpress-product-addons' ); ?></p>
