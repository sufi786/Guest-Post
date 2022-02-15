<?php
/**
 * Shortcodes
 *
 * @package Guest_Post
 * @version 1.0.1
 */

namespace Guest_Post;

defined( 'ABSPATH' ) || exit;

use Guest_Post\Shortcodes;

/**
 * Guest_Post Shortcodes class.
 */
class GP_Shortcodes {

	/**
	 * Init shortcodes.
	 *
	 * @since 1.0.1
	 */
	public static function init() {
		$shortcodes = array(
			'guest_post_form' => __CLASS__ . '::guest_post_form',
			'guest_posts'     => __CLASS__ . '::guest_posts',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}

		add_action( 'wp_ajax_guest_post_form_submission', array( 'Guest_Post\Shortcodes\GP_Shortcode_Form', 'save' ), 10 );
	}

	/**
	 * Shortcode Wrapper.
	 *
	 * @since 1.0.1
	 * @param string[] $function Callback function.
	 * @param array    $atts     Attributes. Default to empty array.
	 * @param array    $wrapper  Customer wrapper data.
	 *
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'guest-post',
			'before' => null,
			'after'  => null,
		)
	) : string {

		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before']; // phpcs:ignore.

		// @codingStandardsIgnoreStart
		call_user_func( $function, $atts );
		// @codingStandardsIgnoreEnd

		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after']; // phpcs:ignore.

		return ob_get_clean();
	}

	/**
	 * Guest post form shortcode.
	 *
	 * @since 1.0.1
	 * @return string
	 */
	public static function guest_post_form() : string {
		return self::shortcode_wrapper( array( 'Guest_Post\Shortcodes\GP_Shortcode_Form', 'output' ), array() );
	}

	/**
	 * Diplay guests posts. By default all guest posts will be displayed, use 'status' attribute to show pending guest posts.
	 *
	 * @since 1.0.1
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function guest_posts( $atts = array() ) : string {

		$atts = shortcode_atts(
			array(
				'limit'    => '10',
				'page'     => 1,
				'status'   => 'any',
				'paginate' => true,
			),
			$atts,
			'guest_post'
		);

		return self::shortcode_wrapper( array( 'Guest_Post\Shortcodes\GP_Shortcode_Posts', 'output' ), $atts );
	}
}
