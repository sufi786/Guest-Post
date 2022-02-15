<?php
/**
 * Post Types
 *
 * Registers required post types.
 *
 * @package Guest_Post
 * @version 1.0.1
 */

namespace Guest_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Post types Class.
 */
class GP_Post_Types {
	/**
	 * Hook in methods.
	 *
	 * @since 1.0.1
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 10 );
	}

	/**
	 * Register core post types.
	 *
	 * @since 1.0.1
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() || post_type_exists( 'guest_post' ) ) {
			return;
		}

		do_action( 'gp_register_post_type' );

		register_post_type(
			'guest_post',
			apply_filters(
				'gp_register_post_type_guest_post',
				array(
					'labels'              => array(
						'name'                  => __( 'Guest Posts', 'guest-post' ),
						'singular_name'         => __( 'Guest Post', 'guest-post' ),
						'all_items'             => __( 'All guest posts', 'guest-post' ),
						'menu_name'             => _x( 'Guest Posts', 'Admin menu name', 'guest-post' ),
						'add_new'               => __( 'Create new guest post', 'guest-post' ),
						'add_new_item'          => __( 'Create new guest post', 'guest-post' ),
						'edit'                  => __( 'Edit', 'guest-post' ),
						'edit_item'             => __( 'Edit guest post', 'guest-post' ),
						'new_item'              => __( 'New guest post', 'guest-post' ),
						'view_item'             => __( 'View guest post', 'guest-post' ),
						'view_items'            => __( 'View guest posts', 'guest-post' ),
						'search_items'          => __( 'Search guest posts', 'guest-post' ),
						'not_found'             => __( 'No guest posts found', 'guest-post' ),
						'not_found_in_trash'    => __( 'No guest posts found in trash', 'guest-post' ),
						'parent'                => __( 'Parent guest post', 'guest-post' ),
						'featured_image'        => __( 'Feature image', 'guest-post' ),
						'set_featured_image'    => __( 'Set featured image', 'guest-post' ),
						'remove_featured_image' => __( 'Remove featured image', 'guest-post' ),
						'use_featured_image'    => __( 'Use as featured image', 'guest-post' ),
						'insert_into_item'      => __( 'Insert into guest post', 'guest-post' ),
						'uploaded_to_this_item' => __( 'Uploaded to this guest post', 'guest-post' ),
						'filter_items_list'     => __( 'Filter guest posts', 'guest-post' ),
						'items_list_navigation' => __( 'Guest Posts navigation', 'guest-post' ),
						'items_list'            => __( 'Guest Posts list', 'guest-post' ),
					),
					'capabilities'        => array(
						'edit_post'          => 'update_core',
						'read_post'          => 'update_core',
						'delete_post'        => 'update_core',
						'edit_posts'         => 'update_core',
						'edit_others_posts'  => 'update_core',
						'delete_posts'       => 'update_core',
						'publish_posts'      => 'update_core',
						'read_private_posts' => 'update_core',
					),
					'description'         => __( 'This is where you can add new guest posts to your store.', 'guest-post' ),
					'public'              => true,
					'menu_icon'           => 'dashicons-admin-post',
					'show_ui'             => true,
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => false,
					'show_in_nav_menus'   => false,
					'rewrite'             => true,
					'query_var'           => true,
					'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
					'has_archive'         => true,
				)
			)
		);
	}
}

GP_Post_Types::init();
