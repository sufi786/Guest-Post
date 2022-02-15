<?php
/**
 * The template for displaying guest posts content within loops.
 *
 * This template can be overridden by copying it to yourtheme/guest-post/content-guest-post.php.
 *
 * @package Guest_Post\Templates
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

global $post;

// Ensure visibility.
if ( empty( $post ) ) {
	return;
}
?>
<tr>
	<td class="gp-id">
		<?php echo '#' . absint( $post->ID ); ?>
	</td>
	<td class="gp-thumbnail">
		<?php echo get_the_post_thumbnail( $post->ID, array( 75, 75 ) ); ?>
	</td>
	<td class="gp-title">
		<?php echo esc_html( get_the_title( $post->ID ) ); ?>
	</td>
	<td class="gp-desc">
		<?php echo get_the_content( $post->ID ); // phpcs:ignore ?>
	</td>
	<td class="gp-excerpt">
		<?php echo get_the_excerpt( $post->ID ); // phpcs:ignore ?>
	</td>
</tr>	
