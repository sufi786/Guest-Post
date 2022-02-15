<?php
/**
 * Guest Post init
 *
 * @package Guest_Post
 * @version 1.0.1
 */

namespace Guest_Post;

defined( 'ABSPATH' ) || exit;

use Guest_Post\Shortcodes;
use Guest_Post\Functions as Functions;

/**
 * Main Guest_Post Class.
 *
 * @class Guest_Post
 */
final class Guest_Post {

	/**
	 * Guest_Post version.
	 *
	 * @since 1.0.1
	 * @var string
	 */
	public $version = '1.0.1';

	/**
	 * The single instance of the class.
	 *
	 * @var Guest_Post
	 * @since 1.0.1
	 */
	private static $instance = null;

	/**
	 * Main Guest_Post Instance.
	 *
	 * Ensures only one instance of Guest_Post is loaded or can be loaded.
	 *
	 * @since 1.0.1
	 * @static
	 * @return Guest_Post - Main instance.
	 */
	public static function instance(): Guest_Post {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Guest_Post Constructor.
	 */
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Define Guest_Post Constants.
	 *
	 * @since 1.0.1
	 */
	private function define_constants() {
		$this->define( 'GP_PLUGIN_URL', untrailingslashit( plugins_url( '/', GP_PLUGIN_FILE ) ) );
		$this->define( 'GP_ABSPATH', dirname( GP_PLUGIN_FILE ) . '/' );
		$this->define( 'GP_PLUGIN_BASENAME', plugin_basename( GP_PLUGIN_FILE ) );
		$this->define( 'GP_VERSION', $this->version );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since 1.0.1
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( string $name, string $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @since 1.0.1
	 */
	public function includes() {

		/**
		 * Core classes.
		 */
		include_once GP_ABSPATH . 'includes/gp-core-functions.php';
		include_once GP_ABSPATH . 'includes/shortcodes/class-gp-shortcode-form.php';
		include_once GP_ABSPATH . 'includes/shortcodes/class-gp-shortcode-posts.php';
		include_once GP_ABSPATH . 'includes/class-gp-shortcodes.php';
		include_once GP_ABSPATH . 'includes/class-gp-post-types.php';
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.1
	 */
	private function init_hooks() {
		add_action( 'init', array( 'Guest_Post\GP_Shortcodes', 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts_and_styles' ), 10 );
		add_action( 'after_guest_post_creation', array( $this, 'notify_admin' ), 10, 2 );
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @since 1.0.1
	 */
	public function load_scripts_and_styles() {
		wp_register_style( 'gp-common-css', GP_PLUGIN_URL . '/assets/css/gp-common.css', array(), GP_VERSION );

		wp_register_script( 'gp-form-js', GP_PLUGIN_URL . '/assets/js/frontend/gp-form.js', array( 'jquery' ), GP_VERSION, true );
		wp_localize_script(
			'gp-form-js',
			'gp_form_params',
			array(
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
				'gp_form_nonce'   => wp_create_nonce( 'guest-post-form-submission' ),
				'processing_text' => __( 'Processing...', 'guest-post' ),
			)
		);
	}

	/**
	 * Notifies email if new guest post is added.
	 *
	 * @since 1.0.1
	 * @param int $post_id   Guest post ID.
	 * @param int $author_id Author ID of the gues post.
	 */
	public function notify_admin( $post_id, $author_id ) {
		if ( $post_id && $author_id ) {

			$author = get_user_by( 'id', $author_id );

			$email_params = apply_filters(
				'gp_notify_admin_email_params',
				array(
					get_bloginfo( 'admin_email' ),
					__( 'New Guest Post Added', 'guest-post' ),
					/* translators: %1$s: guest post id %2$s: post author name */
					sprintf( __( 'A new guest post #%1$s has been added for moderation by author %2$s.', 'guest-post' ), $post_id, $author->user_login ),
					array( 'Content-Type: text/html; charset=UTF-8' ),
				),
				$post_id,
				$author_id
			);

			list( $to, $subject, $body, $headers ) = $email_params;

			ob_start();

			include Functions\gp_get_template_path( 'email-admin-notify.php' );

			$email_content = ob_get_clean();

			wp_mail( $to, $subject, $email_content, $headers );
		}
	}
}
