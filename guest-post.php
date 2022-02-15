<?php
/**
 * Plugin Name: Guest Post
 * Description: Allows guest authors to submit posts from front-end and view using Shortcode.
 * Version: 1.0.1
 * Author: Sufiyan Khan
 * Developer: Sufiyan Khan
 * Text Domain: guest-post
 * Domain Path: /lang
 *
 * @package Guest_Post
 * @since   1.0.1
 */

namespace Guest_Post;

defined( 'ABSPATH' ) || exit;

// Include the main Guest Post class.
if ( ! class_exists( 'Guest_Post', false ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-guest-post.php';
}

if ( ! defined( 'GP_PLUGIN_FILE' ) ) {
	define( 'GP_PLUGIN_FILE', __FILE__ );
}

/**
 * Returns the main instance of Guest_Post.
 *
 * @since  1.0.1
 * @return Guest_Post
 */
function g_p() : Guest_Post {
	return Guest_Post::instance();
}

g_p();
