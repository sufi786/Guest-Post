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
 * Shortcode posts class.
 */
class GP_Shortcode_Posts {

	/**
	 * Output the guest posts.
	 *
	 * @since 1.0.1
	 * @param array $atts Shortcode attributes.
	 */
	public static function output( array $atts = array() ) {
		$user = wp_get_current_user();

		if ( is_user_logged_in() && in_array( 'author', (array) $user->roles, true ) ) {
			wp_enqueue_style( 'gp-common-css' );

			// Setup query args.
			$query_args = array(
				'post_type'      => 'guest_post',
				'post_status'    => $atts['status'],
				'no_found_rows'  => false === Functions\gp_string_to_bool( $atts['paginate'] ),
				'orderby'        => 'ASC',
				'page'           => absint( empty( $_GET['gp-page'] ) ? 1 : $_GET['gp-page'] ), // phpcs:ignore
				'posts_per_page' => intval( $atts['limit'] ),
				'fields'         => 'ids',
				'author'         => $user->ID,
			);

			if ( 1 < $query_args['page'] ) {
				$query_args['paged'] = absint( $query_args['page'] );
			}

			// Fetch result.
			$query = new \WP_Query( $query_args );

			$paginated = ! $query->get( 'no_found_rows' );

			$guest_posts = (object) array(
				'ids'          => wp_parse_id_list( $query->posts ),
				'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
				'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
				'per_page'     => (int) $query->get( 'posts_per_page' ),
				'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
			);

			ob_start();

			if ( $guest_posts && $guest_posts->ids ) {
				// Setup the loop.
				Functions\gp_setup_loop(
					array(
						'columns'      => 3,
						'name'         => 'guest_posts',
						'is_paginated' => Functions\gp_string_to_bool( $atts['paginate'] ),
						'total'        => $guest_posts->total,
						'total_pages'  => $guest_posts->total_pages,
						'per_page'     => $guest_posts->per_page,
						'current_page' => $guest_posts->current_page,
					)
				);
				?>

				<table class="guest-posts">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Post ID', 'guest_posts' ); ?></th>
							<th><?php echo esc_html__( 'Featured Image', 'guest_posts' ); ?></th>
							<th><?php echo esc_html__( 'Title', 'guest_posts' ); ?></th>
							<th><?php echo esc_html__( 'Description', 'guest_posts' ); ?></th>
							<th><?php echo esc_html__( 'Excerpt', 'guest_posts' ); ?></th>
						<tr>
					</thead>
					<tbody>
						<?php

						$original_post = $GLOBALS['post'];

						if ( Functions\gp_get_loop_prop( 'total' ) ) {
							foreach ( $guest_posts->ids as $gp_id ) {
								$GLOBALS['post'] = get_post( $gp_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								setup_postdata( $GLOBALS['post'] );

								// Render single  guest post template.
								include Functions\gp_get_template_path( 'content-guest-post.php' );
							}
						}

						$GLOBALS['post'] = $original_post; // phpcs:ignore

						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<?php Functions\guest_post_pagination(); ?>
							</td>
						</tr>
					</tfoot>	
				</table>	
				<?php
			} else {
				echo esc_html( apply_filters( 'gp_no_result', __( 'You don\'t have any posts in pending status.', 'guest-posts' ) ) );
			}
		} else {
			echo esc_html( apply_filters( 'gp_view_login_required', __( 'You must be looged in as author to view the list of your pending posts.', 'guest-posts' ) ) );
		}
	}
}
