<?php
/**
 * Persistence for product and global add-on groups.
 *
 * @package SpeedPress\ProductAddons
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SPPA_Repository
 */
class SPPA_Repository {

	/**
	 * Product-level groups.
	 *
	 * @param int $product_id Product ID.
	 * @return array
	 */
	public static function get_product_groups( $product_id ) {
		$groups = get_post_meta( $product_id, SPPA_META_PRODUCT, true );
		if ( ! is_array( $groups ) ) {
			return array();
		}
		return $groups;
	}

	/**
	 * Save product-level groups.
	 *
	 * @param int   $product_id Product ID.
	 * @param array $groups     Groups.
	 */
	public static function save_product_groups( $product_id, $groups ) {
		update_post_meta( $product_id, SPPA_META_PRODUCT, SPPA_Helpers::sanitize_groups( $groups ) );
	}

	/**
	 * Whether a product excludes global add-ons.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public static function excludes_global( $product_id ) {
		return 'yes' === get_post_meta( $product_id, SPPA_META_EXCLUDE_GLOBAL, true );
	}

	/**
	 * All published global groups.
	 *
	 * @return array
	 */
	public static function get_global_groups() {
		$posts = get_posts(
			array(
				'post_type'      => 'sppa_addon_group',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);

		$groups = array();
		foreach ( $posts as $post ) {
			$groups[] = self::hydrate_global( $post );
		}
		return $groups;
	}

	/**
	 * Single global group.
	 *
	 * @param int $id Post ID.
	 * @return array|null
	 */
	public static function get_global_group( $id ) {
		$post = get_post( $id );
		if ( ! $post || 'sppa_addon_group' !== $post->post_type ) {
			return null;
		}
		return self::hydrate_global( $post );
	}

	/**
	 * Hydrate a CPT into the group schema used everywhere else.
	 *
	 * @param WP_Post $post Post.
	 * @return array
	 */
	public static function hydrate_global( $post ) {
		$fields  = get_post_meta( $post->ID, '_sppa_fields', true );
		$apply   = get_post_meta( $post->ID, '_sppa_apply', true );
		$include = get_post_meta( $post->ID, '_sppa_include_ids', true );
		$exclude = get_post_meta( $post->ID, '_sppa_exclude_ids', true );
		$cats    = get_post_meta( $post->ID, '_sppa_include_cats', true );
		$tags    = get_post_meta( $post->ID, '_sppa_include_tags', true );
		$types   = get_post_meta( $post->ID, '_sppa_include_types', true );
		$excats  = get_post_meta( $post->ID, '_sppa_exclude_cats', true );

		$title  = get_post_meta( $post->ID, '_sppa_group_title', true );
		$desc   = get_post_meta( $post->ID, '_sppa_group_desc', true );
		$status = get_post_meta( $post->ID, '_sppa_status', true );

		return array(
			'id'           => 'global_' . $post->ID,
			'global_id'    => $post->ID,
			'name'         => is_string( $title ) ? $title : '',
			'description'  => is_string( $desc ) ? $desc : '',
			'status'       => ( 'inactive' === $status ) ? 'inactive' : 'active',
			'priority'     => absint( get_post_meta( $post->ID, '_sppa_priority', true ) ?: $post->menu_order ),
			'layout'       => get_post_meta( $post->ID, '_sppa_layout', true ) ?: 'vertical',
			'style'        => get_post_meta( $post->ID, '_sppa_style', true ) ?: 'default',
			'apply'        => $apply ?: 'all',
			'include_ids'  => is_array( $include ) ? array_map( 'absint', $include ) : array(),
			'exclude_ids'  => is_array( $exclude ) ? array_map( 'absint', $exclude ) : array(),
			'include_cats' => is_array( $cats ) ? array_map( 'absint', $cats ) : array(),
			'include_tags' => is_array( $tags ) ? array_map( 'absint', $tags ) : array(),
			'include_types'=> is_array( $types ) ? $types : array(),
			'exclude_cats' => is_array( $excats ) ? array_map( 'absint', $excats ) : array(),
			'fields'       => is_array( $fields ) ? $fields : array(),
			'source'       => 'global',
		);
	}

	/**
	 * Persist a global group from admin form.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $data    Data.
	 */
	public static function save_global_meta( $post_id, $data ) {
		if ( isset( $data['fields'] ) ) {
			$sanitized = SPPA_Helpers::sanitize_groups( array( array( 'fields' => $data['fields'] ) ) );
			$fields    = ! empty( $sanitized[0]['fields'] ) ? $sanitized[0]['fields'] : array();
			update_post_meta( $post_id, '_sppa_fields', $fields );
		}
		if ( isset( $data['apply'] ) ) {
			update_post_meta( $post_id, '_sppa_apply', sanitize_key( $data['apply'] ) );
		}
		foreach ( array( 'include_ids', 'exclude_ids', 'include_cats', 'include_tags', 'exclude_cats' ) as $key ) {
			$raw = $data[ $key ] ?? array();
			if ( is_string( $raw ) ) {
				$raw = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
			}
			update_post_meta( $post_id, '_sppa_' . $key, array_map( 'absint', (array) $raw ) );
		}
		$types = $data['include_types'] ?? array();
		update_post_meta( $post_id, '_sppa_include_types', array_map( 'sanitize_key', (array) $types ) );
		if ( isset( $data['layout'] ) ) {
			update_post_meta( $post_id, '_sppa_layout', sanitize_key( $data['layout'] ) );
		}
		if ( isset( $data['style'] ) ) {
			update_post_meta( $post_id, '_sppa_style', sanitize_key( $data['style'] ) );
		}
		if ( isset( $data['name'] ) ) {
			update_post_meta( $post_id, '_sppa_group_title', sanitize_text_field( $data['name'] ) );
		}
		if ( isset( $data['description'] ) ) {
			update_post_meta( $post_id, '_sppa_group_desc', wp_kses_post( $data['description'] ) );
		}
		if ( isset( $data['status'] ) ) {
			$status = in_array( $data['status'], array( 'active', 'inactive' ), true ) ? $data['status'] : 'active';
			update_post_meta( $post_id, '_sppa_status', $status );
		}
		if ( isset( $data['priority'] ) ) {
			update_post_meta( $post_id, '_sppa_priority', absint( $data['priority'] ) );
		}
	}
}
