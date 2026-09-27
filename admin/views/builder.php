<?php
/**
 * Shared field-group builder.
 *
 * Expects $groups and $name.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

$types      = SPPA_Field_Types::all();
$operators  = SPPA_Conditions::operators();
$price_types = array(
	'none'       => __( 'No extra price', 'speedpress-product-addons' ),
	'flat'       => __( 'Fixed', 'speedpress-product-addons' ),
	'percentage' => __( 'Percentage of product', 'speedpress-product-addons' ),
	'quantity'   => __( 'Quantity × price', 'speedpress-product-addons' ),
	'character'  => __( 'Character × price', 'speedpress-product-addons' ),
	'length'     => __( 'Length × price', 'speedpress-product-addons' ),
	'area'       => __( 'Area × price', 'speedpress-product-addons' ),
	'weight'     => __( 'Weight × price', 'speedpress-product-addons' ),
	'negative'   => __( 'Discount (negative)', 'speedpress-product-addons' ),
);
?>
<div class="sppa-builder" data-name="<?php echo esc_attr( $name ); ?>">
	<div class="sppa-builder-toolbar">
		<div class="sppa-builder-toolbar-copy">
			<h3><?php esc_html_e( 'Product options', 'speedpress-product-addons' ); ?></h3>
			<p><?php esc_html_e( 'Build groups, add fields, then attach prices and show/hide rules. Drag to reorder.', 'speedpress-product-addons' ); ?></p>
		</div>
		<button type="button" class="button button-primary sppa-add-group"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e( 'Add option group', 'speedpress-product-addons' ); ?></button>
	</div>
	<div class="sppa-modal" hidden>
		<div class="sppa-modal-backdrop"></div>
		<div class="sppa-modal-card" role="dialog" aria-modal="true">
			<div class="sppa-modal-icon"><span class="dashicons dashicons-trash"></span></div>
			<h3 class="sppa-modal-title"><?php esc_html_e( 'Remove this field?', 'speedpress-product-addons' ); ?></h3>
			<p class="sppa-modal-text"><?php esc_html_e( 'The field and its options will be deleted from this group after you save.', 'speedpress-product-addons' ); ?></p>
			<div class="sppa-modal-actions">
				<button type="button" class="sppa-btn sppa-btn-ghost sppa-modal-cancel"><?php esc_html_e( 'Keep it', 'speedpress-product-addons' ); ?></button>
				<button type="button" class="sppa-btn sppa-btn-primary sppa-modal-ok"><?php esc_html_e( 'Remove', 'speedpress-product-addons' ); ?></button>
			</div>
		</div>
	</div>
	<div class="sppa-groups">
		<?php
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $gi => $group ) {
				include SPPA_PATH . 'admin/views/group.php';
			}
		}
		?>
	</div>
</div>
<?php
// Templates used by admin.js.
?>
<script type="text/html" id="tmpl-sppa-group">
<?php
$gi    = '__GI__';
$group = array(
	'id'          => '__GID__',
	'name'        => '',
	'description' => '',
	'status'      => 'active',
	'priority'    => 10,
	'layout'      => 'vertical',
	'style'       => 'default',
	'fields'      => array(),
);
include SPPA_PATH . 'admin/views/group.php';
?>
</script>
<script type="text/html" id="tmpl-sppa-field">
<?php
$fi    = '__FI__';
$field = array(
	'id'               => '__FID__',
	'type'             => 'text',
	'label'            => '',
	'description'      => '',
	'placeholder'      => '',
	'required'         => false,
	'required_message' => '',
	'price_type'       => 'none',
	'price'            => '',
	'min'              => '',
	'max'              => '',
	'options'          => array(),
	'conditions'       => array(
		'enabled' => false,
		'match'   => 'all',
		'action'  => 'show',
		'rules'   => array(),
	),
);
include SPPA_PATH . 'admin/views/field.php';
?>
</script>
<script type="text/html" id="tmpl-sppa-option">
<?php
$oi     = '__OI__';
$option = array(
	'id'         => '__OID__',
	'label'      => '',
	'price_type' => 'flat',
	'price'      => '',
	'image'      => 0,
	'color'      => '',
	'default'    => false,
	'stock'      => '',
	'tooltip'    => '',
);
include SPPA_PATH . 'admin/views/option.php';
?>
</script>
<script type="text/html" id="tmpl-sppa-rule">
<?php
$ri   = '__RI__';
$rule = array(
	'field'    => '',
	'operator' => 'is',
	'value'    => '',
);
include SPPA_PATH . 'admin/views/rule.php';
?>
</script>
