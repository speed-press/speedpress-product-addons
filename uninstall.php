<?php
/**
 * Uninstall cleanup.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'sppa_settings' );

$posts = get_posts(
	array(
		'post_type'      => 'sppa_addon_group',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);

foreach ( $posts as $id ) {
	wp_delete_post( $id, true );
}
