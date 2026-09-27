<?php
/**
 * One field editor.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$types       = isset( $types ) ? $types : SPPA_Field_Types::all();
$price_types = isset( $price_types ) ? $price_types : array();
$fid         = $field['id'] ?? ( 'fld_' . $fi );
$prefix      = $name . '[' . $gi . '][fields][' . $fi . ']';
$has_opts    = SPPA_Field_Types::has_options( $field['type'] ?? 'text' );
$cond        = $field['conditions'] ?? array(
	'enabled' => false,
	'match'   => 'all',
	'action'  => 'show',
	'rules'   => array(),
);
?>
<div class="sppa-field" data-index="<?php echo esc_attr( $fi ); ?>" data-type="<?php echo esc_attr( $field['type'] ?? 'text' ); ?>">
	<div class="sppa-field-header">
		<span class="sppa-handle dashicons dashicons-menu"></span>
		<input type="hidden" name="<?php echo esc_attr( $prefix ); ?>[id]" value="<?php echo esc_attr( $fid ); ?>" class="sppa-field-id" />
		<div class="sppa-type-picker">
			<select name="<?php echo esc_attr( $prefix ); ?>[type]" class="sppa-field-type">
				<?php foreach ( $types as $type_key => $type_def ) : ?>
					<option value="<?php echo esc_attr( $type_key ); ?>" <?php selected( ( $field['type'] ?? 'text' ), $type_key ); ?>><?php echo esc_html( $type_def['label'] ); ?></option>
				<?php endforeach; ?>
			</select>
			<button type="button" class="sppa-type-trigger">
				<span class="dashicons dashicons-plus-alt"></span>
				<span class="sppa-type-trigger-label"><?php echo esc_html( $types[ $field['type'] ?? 'text' ]['label'] ?? __( 'Text', 'speedpress-product-addons' ) ); ?></span>
				<span class="dashicons dashicons-arrow-down-alt2"></span>
			</button>
			<div class="sppa-type-panel" hidden>
				<?php
				$is_pro  = class_exists( 'SPPA_License' ) && SPPA_License::is_premium();
				$free_ok = class_exists( 'SPPA_License' ) ? SPPA_License::free_types() : array();
				foreach ( $types as $type_key => $type_def ) :
					$locked = ! $is_pro && ! in_array( $type_key, $free_ok, true );
					?>
					<button type="button" class="sppa-type-card<?php echo ( $field['type'] ?? 'text' ) === $type_key ? ' is-active' : ''; ?><?php echo $locked ? ' is-locked' : ''; ?>" data-value="<?php echo esc_attr( $type_key ); ?>" <?php echo $locked ? 'aria-disabled="true"' : ''; ?>>
						<span class="sppa-type-card-icon dashicons dashicons-<?php echo esc_attr( 'heading' === $type_key ? 'heading' : ( 'file' === $type_key ? 'upload' : ( 'date' === $type_key ? 'calendar-alt' : ( 'color' === $type_key || 'color_swatch' === $type_key ? 'art' : ( 'checkbox' === $type_key || 'checkbox_group' === $type_key ? 'yes' : ( 'radio' === $type_key || 'image_radio' === $type_key ? 'marker' : ( 'select' === $type_key || 'multiselect' === $type_key ? 'arrow-down-alt2' : ( 'email' === $type_key ? 'email' : ( 'number' === $type_key || 'quantity' === $type_key || 'customer_price' === $type_key ? 'calculator' : ( 'textarea' === $type_key ? 'editor-paragraph' : 'editor-textcolor' ) ) ) ) ) ) ) ) ) ); ?>"></span>
						<span><?php echo esc_html( $type_def['label'] ); ?></span>
						<?php if ( $locked ) : ?>
							<span class="sppa-pro-badge">PRO</span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<input type="text" class="sppa-field-label" name="<?php echo esc_attr( $prefix ); ?>[label]" value="<?php echo esc_attr( $field['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Field label', 'speedpress-product-addons' ); ?>" />
		<label class="sppa-switch" title="<?php esc_attr_e( 'Required field', 'speedpress-product-addons' ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[required]" value="1" <?php checked( ! empty( $field['required'] ) ); ?> />
			<span class="sppa-switch-ui"></span>
			<span class="sppa-switch-label"><?php esc_html_e( 'Required', 'speedpress-product-addons' ); ?></span>
		</label>
		<code class="sppa-field-id-display" title="<?php esc_attr_e( 'Use this ID in conditional rules', 'speedpress-product-addons' ); ?>"><?php echo esc_html( $fid ); ?></code>
		<button type="button" class="button-link sppa-toggle-field"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Settings', 'speedpress-product-addons' ); ?></button>
		<button type="button" class="sppa-icon-btn sppa-remove-field" title="<?php esc_attr_e( 'Remove field', 'speedpress-product-addons' ); ?>"><span class="dashicons dashicons-trash"></span></button>
	</div>
	<div class="sppa-field-settings" hidden>
		<div class="sppa-grid">
			<p class="sppa-set" data-for="text,textarea,number,email,select,radio,checkbox,checkbox_group,multiselect,image_radio,image_checkbox,color,color_swatch,file,date,time,quantity,customer_price,heading,paragraph,hidden">
				<label><?php esc_html_e( 'Description', 'speedpress-product-addons' ); ?></label>
				<textarea class="widefat" rows="2" name="<?php echo esc_attr( $prefix ); ?>[description]"><?php echo esc_textarea( $field['description'] ?? '' ); ?></textarea>
			</p>
			<p class="sppa-set" data-for="text,textarea,number,email,quantity,customer_price,hidden,color">
				<label><?php esc_html_e( 'Placeholder / default', 'speedpress-product-addons' ); ?></label>
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[placeholder]" value="<?php echo esc_attr( $field['placeholder'] ?? '' ); ?>" />
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[default]" value="<?php echo esc_attr( is_array( $field['default'] ?? '' ) ? implode( ',', $field['default'] ) : ( $field['default'] ?? '' ) ); ?>" placeholder="<?php esc_attr_e( 'Default value', 'speedpress-product-addons' ); ?>" />
			</p>
			<p class="sppa-set" data-for="text,textarea,number,email,select,radio,checkbox,checkbox_group,multiselect,image_radio,image_checkbox,color,color_swatch,file,date,time,quantity,customer_price,hidden">
				<label><?php esc_html_e( 'Price type', 'speedpress-product-addons' ); ?></label>
				<select name="<?php echo esc_attr( $prefix ); ?>[price_type]" class="widefat">
					<?php foreach ( $price_types as $pk => $pl ) : ?>
						<option value="<?php echo esc_attr( $pk ); ?>" <?php selected( ( $field['price_type'] ?? 'none' ), $pk ); ?>><?php echo esc_html( $pl ); ?></option>
					<?php endforeach; ?>
				</select>
			</p>
			<p class="sppa-set" data-for="text,textarea,number,email,select,radio,checkbox,checkbox_group,multiselect,image_radio,image_checkbox,color,color_swatch,file,date,time,quantity,customer_price,hidden">
				<label><?php esc_html_e( 'Price', 'speedpress-product-addons' ); ?></label>
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[price]" value="<?php echo esc_attr( $field['price'] ?? '' ); ?>" placeholder="0.00" />
			</p>
			<p class="sppa-set" data-for="text,textarea,number,quantity,customer_price">
				<label><?php esc_html_e( 'Min / Max', 'speedpress-product-addons' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $prefix ); ?>[min]" value="<?php echo esc_attr( $field['min'] ?? '' ); ?>" placeholder="min" style="width:48%" />
				<input type="text" name="<?php echo esc_attr( $prefix ); ?>[max]" value="<?php echo esc_attr( $field['max'] ?? '' ); ?>" placeholder="max" style="width:48%" />
			</p>
			<p class="sppa-set" data-for="text,textarea">
				<label><?php esc_html_e( 'Character pricing (optional)', 'speedpress-product-addons' ); ?></label>
				<input type="number" name="<?php echo esc_attr( $prefix ); ?>[char_base_count]" value="<?php echo esc_attr( $field['char_base_count'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'First N chars', 'speedpress-product-addons' ); ?>" style="width:32%" />
				<input type="text" name="<?php echo esc_attr( $prefix ); ?>[char_base_price]" value="<?php echo esc_attr( $field['char_base_price'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Base price', 'speedpress-product-addons' ); ?>" style="width:32%" />
				<input type="text" name="<?php echo esc_attr( $prefix ); ?>[char_extra_price]" value="<?php echo esc_attr( $field['char_extra_price'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Each extra', 'speedpress-product-addons' ); ?>" style="width:32%" />
			</p>
			<p class="sppa-set" data-for="text,textarea">
				<label><?php esc_html_e( 'Allowed characters / regex', 'speedpress-product-addons' ); ?></label>
				<select name="<?php echo esc_attr( $prefix ); ?>[allowed_chars]" class="widefat">
					<option value=""><?php esc_html_e( 'Any', 'speedpress-product-addons' ); ?></option>
					<option value="letters" <?php selected( ( $field['allowed_chars'] ?? '' ), 'letters' ); ?>><?php esc_html_e( 'Letters only', 'speedpress-product-addons' ); ?></option>
					<option value="numbers" <?php selected( ( $field['allowed_chars'] ?? '' ), 'numbers' ); ?>><?php esc_html_e( 'Numbers only', 'speedpress-product-addons' ); ?></option>
					<option value="alphanumeric" <?php selected( ( $field['allowed_chars'] ?? '' ), 'alphanumeric' ); ?>><?php esc_html_e( 'Letters + numbers', 'speedpress-product-addons' ); ?></option>
				</select>
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[regex]" value="<?php echo esc_attr( $field['regex'] ?? '' ); ?>" placeholder="^[A-Z]{3}-[0-9]{4}$" />
			</p>
			<p class="sppa-set" data-for="select,radio,checkbox_group,multiselect,image_radio,image_checkbox,color_swatch">
				<label><?php esc_html_e( 'Display style / swatch shape', 'speedpress-product-addons' ); ?></label>
				<select name="<?php echo esc_attr( $prefix ); ?>[display_style]">
					<option value="default" <?php selected( ( $field['display_style'] ?? '' ), 'default' ); ?>><?php esc_html_e( 'Default', 'speedpress-product-addons' ); ?></option>
					<option value="cards" <?php selected( ( $field['display_style'] ?? '' ), 'cards' ); ?>><?php esc_html_e( 'Cards', 'speedpress-product-addons' ); ?></option>
					<option value="pills" <?php selected( ( $field['display_style'] ?? '' ), 'pills' ); ?>><?php esc_html_e( 'Pills', 'speedpress-product-addons' ); ?></option>
					<option value="swatches" <?php selected( ( $field['display_style'] ?? '' ), 'swatches' ); ?>><?php esc_html_e( 'Swatches', 'speedpress-product-addons' ); ?></option>
				</select>
				<select name="<?php echo esc_attr( $prefix ); ?>[swatch_shape]">
					<option value="circle" <?php selected( ( $field['swatch_shape'] ?? '' ), 'circle' ); ?>><?php esc_html_e( 'Circle', 'speedpress-product-addons' ); ?></option>
					<option value="square" <?php selected( ( $field['swatch_shape'] ?? '' ), 'square' ); ?>><?php esc_html_e( 'Square', 'speedpress-product-addons' ); ?></option>
					<option value="rounded" <?php selected( ( $field['swatch_shape'] ?? '' ), 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'speedpress-product-addons' ); ?></option>
				</select>
			</p>
			<p class="sppa-set" data-for="file">
				<label><?php esc_html_e( 'File upload', 'speedpress-product-addons' ); ?></label>
				<input type="number" name="<?php echo esc_attr( $prefix ); ?>[max_files]" value="<?php echo esc_attr( $field['max_files'] ?? 1 ); ?>" min="1" style="width:30%" /> <?php esc_html_e( 'max files', 'speedpress-product-addons' ); ?>
				<input type="number" name="<?php echo esc_attr( $prefix ); ?>[max_file_mb]" value="<?php echo esc_attr( $field['max_file_mb'] ?? 10 ); ?>" min="1" style="width:30%" /> MB
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[allowed_types]" value="<?php echo esc_attr( $field['allowed_types'] ?? 'jpg,jpeg,png,pdf' ); ?>" />
			</p>
			<p class="sppa-set" data-for="date,time">
				<label><?php esc_html_e( 'Date restrictions', 'speedpress-product-addons' ); ?></label>
				<input type="number" name="<?php echo esc_attr( $prefix ); ?>[min_days]" value="<?php echo esc_attr( $field['min_days'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Min days ahead', 'speedpress-product-addons' ); ?>" style="width:48%" />
				<input type="number" name="<?php echo esc_attr( $prefix ); ?>[max_days]" value="<?php echo esc_attr( $field['max_days'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Max days ahead', 'speedpress-product-addons' ); ?>" style="width:48%" />
				<textarea class="widefat" rows="2" name="<?php echo esc_attr( $prefix ); ?>[blocked_dates]" placeholder="<?php esc_attr_e( 'Blocked dates YYYY-MM-DD, one per line', 'speedpress-product-addons' ); ?>"><?php echo esc_textarea( $field['blocked_dates'] ?? '' ); ?></textarea>
			</p>
			<p class="sppa-set" data-for="paragraph">
				<label><?php esc_html_e( 'HTML (paragraph field)', 'speedpress-product-addons' ); ?></label>
				<textarea class="widefat" rows="3" name="<?php echo esc_attr( $prefix ); ?>[html]"><?php echo esc_textarea( $field['html'] ?? '' ); ?></textarea>
			</p>
			<p class="sppa-set" data-for="text,textarea,number,email,select,radio,checkbox,checkbox_group,multiselect,image_radio,image_checkbox,color,color_swatch,file,date,time,quantity,customer_price">
				<label><?php esc_html_e( 'Custom required message', 'speedpress-product-addons' ); ?></label>
				<input type="text" class="widefat" name="<?php echo esc_attr( $prefix ); ?>[required_message]" value="<?php echo esc_attr( $field['required_message'] ?? '' ); ?>" />
			</p>
			<p class="sppa-set" data-for="text,textarea,number,email,select,radio,checkbox,checkbox_group,multiselect,image_radio,image_checkbox,color,color_swatch,file,date,time,quantity,customer_price">
				<label><input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[hide_price]" value="1" <?php checked( ! empty( $field['hide_price'] ) ); ?> /> <?php esc_html_e( 'Hide price on product page', 'speedpress-product-addons' ); ?></label><br />
				<label><input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[hide_in_cart]" value="1" <?php checked( ! empty( $field['hide_in_cart'] ) ); ?> /> <?php esc_html_e( 'Hide this field in cart', 'speedpress-product-addons' ); ?></label>
			</p>
			<p class="sppa-set" data-for="select,radio,checkbox_group,multiselect,image_radio,image_checkbox,color_swatch">
				<label><input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[stock_enabled]" value="1" <?php checked( ! empty( $field['stock_enabled'] ) ); ?> /> <?php esc_html_e( 'Track option stock', 'speedpress-product-addons' ); ?></label>
				<label><input type="checkbox" name="<?php echo esc_attr( $prefix ); ?>[hide_out_of_stock]" value="1" <?php checked( ! empty( $field['hide_out_of_stock'] ) ); ?> /> <?php esc_html_e( 'Hide out-of-stock options', 'speedpress-product-addons' ); ?></label>
			</p>
		</div>

		<div class="sppa-options-wrap" <?php echo $has_opts ? '' : 'hidden'; ?>>
			<h4><?php esc_html_e( 'Choices', 'speedpress-product-addons' ); ?></h4>
			<div class="sppa-options">
				<?php
				if ( ! empty( $field['options'] ) ) {
					foreach ( $field['options'] as $oi => $option ) {
						include SPPA_PATH . 'admin/views/option.php';
					}
				}
				?>
			</div>
			<p><button type="button" class="button sppa-add-option"><?php esc_html_e( '+ Add choice', 'speedpress-product-addons' ); ?></button></p>
		</div>

		<div class="sppa-conditions-wrap">
			<h4><?php esc_html_e( 'Conditional logic', 'speedpress-product-addons' ); ?></h4>
			<?php if ( class_exists( 'SPPA_License' ) && ! SPPA_License::is_premium() ) : ?>
				<p class="description"><?php esc_html_e( 'Conditional logic is a Premium feature. Free licenses can use basic fields only.', 'speedpress-product-addons' ); ?></p>
			<?php else : ?>
			<label>
				<input type="checkbox" class="sppa-cond-enabled" name="<?php echo esc_attr( $prefix ); ?>[conditions][enabled]" value="1" <?php checked( ! empty( $cond['enabled'] ) ); ?> />
				<?php esc_html_e( 'Enable conditions for this field', 'speedpress-product-addons' ); ?>
			</label>
			<div class="sppa-cond-body" <?php echo empty( $cond['enabled'] ) ? 'hidden' : ''; ?>>
				<p>
					<select name="<?php echo esc_attr( $prefix ); ?>[conditions][action]">
						<option value="show" <?php selected( ( $cond['action'] ?? 'show' ), 'show' ); ?>><?php esc_html_e( 'Show this field if', 'speedpress-product-addons' ); ?></option>
						<option value="hide" <?php selected( ( $cond['action'] ?? '' ), 'hide' ); ?>><?php esc_html_e( 'Hide this field if', 'speedpress-product-addons' ); ?></option>
					</select>
					<select name="<?php echo esc_attr( $prefix ); ?>[conditions][match]">
						<option value="all" <?php selected( ( $cond['match'] ?? 'all' ), 'all' ); ?>><?php esc_html_e( 'ALL rules match (AND)', 'speedpress-product-addons' ); ?></option>
						<option value="any" <?php selected( ( $cond['match'] ?? '' ), 'any' ); ?>><?php esc_html_e( 'ANY rule matches (OR)', 'speedpress-product-addons' ); ?></option>
					</select>
				</p>
				<div class="sppa-rules">
					<?php
					if ( ! empty( $cond['rules'] ) ) {
						foreach ( $cond['rules'] as $ri => $rule ) {
							include SPPA_PATH . 'admin/views/rule.php';
						}
					}
					?>
				</div>
				<p><button type="button" class="button sppa-add-rule"><?php esc_html_e( '+ Add condition', 'speedpress-product-addons' ); ?></button></p>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
