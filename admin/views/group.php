<?php
/**
 * One option group.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$gid    = $group['id'] ?? ( 'grp_' . $gi );
$gname  = $group['name'] ?? '';
$layout = $group['layout'] ?? 'vertical';
$style  = $group['style'] ?? 'default';

$layouts = array(
	'vertical'    => array( __( 'Vertical', 'speedpress-product-addons' ), 'editor-justify' ),
	'horizontal'  => array( __( 'Horizontal', 'speedpress-product-addons' ), 'image-flip-horizontal' ),
	'grid'        => array( __( 'Grid', 'speedpress-product-addons' ), 'grid-view' ),
	'two-columns' => array( __( 'Two columns', 'speedpress-product-addons' ), 'columns' ),
	'compact'     => array( __( 'Compact', 'speedpress-product-addons' ), 'editor-kitchensink' ),
	'stacked'     => array( __( 'Stacked', 'speedpress-product-addons' ), 'list-view' ),
);
$styles  = array(
	'default'  => array( __( 'Default', 'speedpress-product-addons' ), 'admin-appearance' ),
	'cards'    => array( __( 'Cards', 'speedpress-product-addons' ), 'format-aside' ),
	'pills'    => array( __( 'Pills', 'speedpress-product-addons' ), 'tag' ),
	'swatches' => array( __( 'Swatches', 'speedpress-product-addons' ), 'art' ),
	'minimal'  => array( __( 'Minimal', 'speedpress-product-addons' ), 'minus' ),
	'bordered' => array( __( 'Bordered', 'speedpress-product-addons' ), 'button' ),
	'soft'     => array( __( 'Soft panel', 'speedpress-product-addons' ), 'cloud' ),
);
?>
<div class="sppa-group" data-index="<?php echo esc_attr( $gi ); ?>">
	<div class="sppa-group-header">
		<span class="sppa-handle dashicons dashicons-move" title="<?php esc_attr_e( 'Drag to reorder', 'speedpress-product-addons' ); ?>"></span>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][id]" value="<?php echo esc_attr( $gid ); ?>" />
		<input type="text" class="sppa-group-name" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][name]" value="<?php echo esc_attr( $gname ); ?>" placeholder="<?php esc_attr_e( 'Group title (optional — shown on the product)', 'speedpress-product-addons' ); ?>" />
		<label class="sppa-inline">
			<span class="dashicons dashicons-sort"></span>
			<?php esc_html_e( 'Priority', 'speedpress-product-addons' ); ?>
			<input type="number" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][priority]" value="<?php echo esc_attr( $group['priority'] ?? 10 ); ?>" />
		</label>
		<label class="sppa-inline">
			<span class="dashicons dashicons-yes-alt"></span>
			<select name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][status]">
				<option value="active" <?php selected( ( $group['status'] ?? 'active' ), 'active' ); ?>><?php esc_html_e( 'Active', 'speedpress-product-addons' ); ?></option>
				<option value="inactive" <?php selected( ( $group['status'] ?? '' ), 'inactive' ); ?>><?php esc_html_e( 'Inactive', 'speedpress-product-addons' ); ?></option>
			</select>
		</label>
		<button type="button" class="sppa-icon-btn sppa-remove-group" title="<?php esc_attr_e( 'Remove group', 'speedpress-product-addons' ); ?>">
			<span class="dashicons dashicons-trash"></span>
		</button>
	</div>
	<div class="sppa-group-body">
		<div class="sppa-picker-block">
			<div class="sppa-picker-label"><span class="dashicons dashicons-editor-table"></span> <?php esc_html_e( 'Layout', 'speedpress-product-addons' ); ?></div>
			<input type="hidden" class="sppa-layout-input" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][layout]" value="<?php echo esc_attr( $layout ); ?>" />
			<div class="sppa-tile-grid" data-target="layout">
				<?php foreach ( $layouts as $key => $meta ) : ?>
					<button type="button" class="sppa-tile<?php echo $layout === $key ? ' is-active' : ''; ?>" data-value="<?php echo esc_attr( $key ); ?>">
						<span class="sppa-tile-preview sppa-preview-<?php echo esc_attr( $key ); ?>"></span>
						<span><?php echo esc_html( $meta[0] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="sppa-picker-block">
			<div class="sppa-picker-label"><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Style', 'speedpress-product-addons' ); ?></div>
			<input type="hidden" class="sppa-style-input" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][style]" value="<?php echo esc_attr( $style ); ?>" />
			<div class="sppa-tile-grid" data-target="style">
				<?php foreach ( $styles as $key => $meta ) : ?>
					<button type="button" class="sppa-tile<?php echo $style === $key ? ' is-active' : ''; ?>" data-value="<?php echo esc_attr( $key ); ?>">
						<span class="sppa-tile-preview sppa-style-preview-<?php echo esc_attr( $key ); ?>"></span>
						<span><?php echo esc_html( $meta[0] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<p>
			<textarea class="widefat" rows="2" name="<?php echo esc_attr( $name ); ?>[<?php echo esc_attr( $gi ); ?>][description]" placeholder="<?php esc_attr_e( 'Optional group description shown on the product page', 'speedpress-product-addons' ); ?>"><?php echo esc_textarea( $group['description'] ?? '' ); ?></textarea>
		</p>
		<div class="sppa-fields">
			<?php
			if ( ! empty( $group['fields'] ) ) {
				foreach ( $group['fields'] as $fi => $field ) {
					include SPPA_PATH . 'admin/views/field.php';
				}
			}
			?>
		</div>
		<p>
			<button type="button" class="button sppa-add-field">
				<span class="dashicons dashicons-plus-alt2"></span>
				<?php esc_html_e( 'Add field', 'speedpress-product-addons' ); ?>
			</button>
		</p>
	</div>
</div>
