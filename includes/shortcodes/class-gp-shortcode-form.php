<?php
/**
 * Guests post form shortcode
 *
 * @package Guest_Post\Shortcodes
 * @version 1.0.1
 */

namespace Guest_Post\Shortcodes;

defined( 'ABSPATH' ) || exit;

use Guest_Post\Functions as Functions;

/**
 * Shortcode form class.
 */
class GP_Shortcode_Form {

	// 2MB is the max upload limit.
	const MAX_UPLOAD_FILE_SIZE = 2000000;

	/**
	 * Save the submitted guest post data.
	 *
	 * @since 1.0.1
	 * @throws \Exception When unable to submit guest post.
	 */
	public static function save() {
		check_ajax_referer( 'guest-post-form-submission', 'security' );

		try {

			$_POST = array_map( 'sanitize_text_field', $_POST );

			// Make sure all required data is present.
			if ( empty( $_POST['gp_title'] ) || empty( $_POST['gp_post'] ) || empty( $_POST['gp_desc'] ) || empty( $_POST['gp_excerpt'] ) ) {
				throw new \Exception( __( 'All the above fields are required.', 'guest-post' ) );
			}

			// Check if the uploaded file is valid.
			if (
			! isset( $_FILES['gp_featured_image'] ) || ! isset( $_FILES['gp_featured_image']['tmp_name'] ) || ! is_uploaded_file( $_FILES['gp_featured_image']['tmp_name'] ) || ! is_file( $_FILES['gp_featured_image']['tmp_name'] ) ) { // phpcs:ignore.
				throw new \Exception( __( 'Specified file failed upload test.', 'guest-post' ) );
			}

			// Make sure the file size is not greater than required file size.
			if ( isset( $_FILES['gp_featured_image'] ) && $_FILES['gp_featured_image']['size'] > self::MAX_UPLOAD_FILE_SIZE ) { // phpcs:ignore.
				throw new \Exception( __( 'File size should not be greater than 2 MB.', 'guest-post' ) );
			}

			// Confirm that the file type is only gif/jpeg/png.
			$info = getimagesize( $_FILES['gp_featured_image']['tmp_name'] ); // phpcs:ignore.

			if ( false === $info ) {
				throw new \Exception( __( 'Unable to determine image type of the uploaded file.', 'guest-post' ) );
			}

			if ( ( IMAGETYPE_GIF !== $info[2] ) && ( IMAGETYPE_JPEG !== $info[2] ) && ( IMAGETYPE_PNG !== $info[2] ) ) {
				throw new \Exception( __( 'Not a gif/jpeg/png image. Please upload a valid image format.', 'guest-post' ) );
			}

			// Handle attachement upload.
			$attach_id = media_handle_upload( 'gp_featured_image', 0 );

			if ( is_wp_error( $attach_id ) ) {
				throw new \Exception( __( 'Error saving featured_image! Cannot submit your post.', 'guest-post' ) );
			}

			$author_id = get_current_user_id();

			// Post data required to create guest post.
			$post_data = apply_filters(
				'gp_post_data',
				array(
					'post_type'    => wp_unslash( $_POST['gp_post'] ), // phpcs:ignore.
					'post_title'   => wp_unslash( $_POST['gp_title'] ), // phpcs:ignore.
					'post_content' => wp_unslash( $_POST['gp_desc'] ), // phpcs:ignore.
					'post_excerpt' => wp_unslash( $_POST['gp_excerpt'] ), // phpcs:ignore.
					'post_status'  => 'draft',
					'post_author'  => $author_id,
				)
			);

			do_action( 'before_gp_post_creation' );

			$post_id = wp_insert_post( $post_data );

			if ( ! $post_id ) {
				throw new \Exception( __( 'Error creating guest post.', 'guest-post' ) );
			}

			set_post_thumbnail( $post_id, $attach_id );

			do_action( 'after_guest_post_creation', $post_id, $author_id );

			wp_send_json_success( array( 'message' => __( 'Thank You! Your post has been successfuly submitted for moderation.', 'guest-post' ) ) );

		} catch ( \Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * Output the guest post submission form.
	 *
	 * @since 1.0.1
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( $atts = array() ) {
		$user = wp_get_current_user();

		if ( is_user_logged_in() && in_array( 'author', (array) $user->roles, true ) ) {
			wp_enqueue_style( 'gp-common-css' );
			wp_enqueue_script( 'gp-form-js' );

			include Functions\gp_get_template_path( 'guest-post-form.php' );
		} else {
			echo esc_html( apply_filters( 'gp_invalid_login', __( 'This post submission form is only accessible to logged-in user with author privileges. Please login as author to submit your post.', 'guest-post' ) ) );
		}
	}
}
