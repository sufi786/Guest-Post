<?php
/**
 * Guest Post Core Functions.
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Guest_Post\Functions
 * @version 1.0.1
 */

namespace Guest_Post\Functions;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'gp_get_template_path' ) ) {
	/**
	 * Get the path of PHP template for guest post
	 *
	 * @since 1.0.1
	 * @param string $template_name Name of the template.
	 * @param string $template_path Template path/subdirectory to look into.
	 * @return string
	 */
	function gp_get_template_path( $template_name, $template_path = '' ) : string {
		// Default Template Path.
		$default_path = GP_ABSPATH . 'templates' . trailingslashit( $template_path );

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				'guest-post' . trailingslashit( $template_path ) . $template_name,
			)
		);

		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'gp_locate_template', $template, $template_path );
	}
}

if ( ! function_exists( 'gp_setup_loop' ) ) {
	/**
	 * Sets up the gp_loop global from the passed args or from the main query.
	 *
	 * @since 1.0.1
	 * @param array $args Args to pass into the global.
	 */
	function gp_setup_loop( $args = array() ) {
		$default_args = array(
			'loop'         => 0,
			'columns'      => 3,
			'name'         => '',
			'is_paginated' => true,
			'total'        => 0,
			'total_pages'  => 0,
			'per_page'     => 0,
			'current_page' => 1,
		);

		// Merge any existing values.
		if ( isset( $GLOBALS['gp_loop'] ) ) {
			$default_args = array_merge( $default_args, $GLOBALS['gp_loop'] );
		}

		$GLOBALS['gp_loop'] = wp_parse_args( $args, $default_args );
	}
}

if ( ! function_exists( 'gp_get_loop_prop' ) ) {
	/**
	 * Gets a property from the gp_loop global.
	 *
	 * @since 1.0.1
	 * @param string $prop Prop to get.
	 * @param string $default Default if the prop does not exist.
	 * @return string
	 */
	function gp_get_loop_prop( $prop, $default = '' ) : string {
		gp_setup_loop(); // Ensure shop loop is setup.

		return isset( $GLOBALS['gp_loop'], $GLOBALS['gp_loop'][ $prop ] ) ? $GLOBALS['gp_loop'][ $prop ] : $default;
	}
}

if ( ! function_exists( 'guest_post_pagination' ) ) {

	/**
	 * Output the pagination.
	 *
	 * @since 1.0.1
	 */
	function guest_post_pagination() {
		if ( ! gp_get_loop_prop( 'is_paginated' ) ) {
			return;
		}

		$total   = gp_get_loop_prop( 'total_pages' );
		$current = gp_get_loop_prop( 'current_page' );
		$base    = esc_url_raw( add_query_arg( 'gp-page', '%#%', false ) );
		$format  = '?gp-page=%#%';

		if ( $total <= 1 ) {
			return;
		}
		?>
		<nav class="guest-post-pagination">
			<?php
			echo paginate_links( // phpcs:ignore.
				apply_filters(
					'guests_post_pagination_args',
					array(
						'base'      => $base,
						'format'    => $format,
						'add_args'  => false,
						'current'   => max( 1, $current ),
						'total'     => $total,
						'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
						'next_text' => is_rtl() ? '&larr;' : '&rarr;',
						'type'      => 'list',
						'end_size'  => 3,
						'mid_size'  => 3,
					)
				)
			);
			?>
		</nav>
		<?php
	}
}

if ( ! function_exists( 'gp_string_to_bool' ) ) {
	/**
	 * Converts a string (e.g. 'yes' or 'no') to a bool.
	 *
	 * @since 1.0.1
	 * @param string|bool $string String to convert. If a bool is passed it will be returned as-is.
	 * @return bool
	 */
	function gp_string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 'yes' === strtolower( $string ) || 1 === $string || 'true' === strtolower( $string ) || '1' === $string );
	}
}
