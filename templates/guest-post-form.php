<?php
/**
 * The template for displaying guest post submission form.
 *
 * This template can be overridden by copying it to yourtheme/guest-post/guest-post-form.php.
 *
 * @package Guest_Post\Templates
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

?>

<form id="guest-post-form" method="post" action="#" enctype="multipart/form-data">
	<table>
		<thead>
			<tr>
				<th colspan="2">
					<h2><?php echo esc_html__( 'Post Submission', 'guest-post' ); ?></h2>
				</th>  
			</tr>
		</thead>
		<tbody>
			<tr>
				<th><?php echo esc_html__( 'Title', 'guest-post' ); ?></th>
				<td><input type="text" id="gp-title" name="gp_title" size="50" maxlength="50" placeholder="<?php echo esc_attr__( 'Post title', 'guest-post' ); ?>" required /></td>
			</tr>
			<tr>
				<th><?php echo esc_html__( 'Post', 'guest-post' ); ?></th>
				<td>
					<select id="gp-post" name="gp_post" required>
						<option value="" disabled selected><?php echo esc_html__( 'Select post type', 'guest-post' ); ?></option>
						<option value="guest_post" ><?php echo esc_html__( 'Guest post', 'guest-post' ); ?></option>
					</select>
				</td>
			</tr>       
			<tr>
				<th><?php echo esc_html__( 'Description', 'guest-post' ); ?></th>
				<td><textarea id="gp-desc" name="gp_desc" placeholder="<?php echo esc_attr__( 'Post description', 'guest-post' ); ?>" rows="8" required></textarea></td>
			</tr>
			<tr>
				<th><?php echo esc_html__( 'Excerpt', 'guest-post' ); ?></th>
				<td><textarea id="gp-excerpt" name="gp_excerpt" placeholder="<?php echo esc_attr__( 'Post excerpt', 'guest-post' ); ?>" rows="3" required></textarea></td>
			</tr>
			<tr>
				<th><?php echo esc_html__( 'Featured Image', 'guest-post' ); ?></th>
				<td>
					<input type="file" id="gp_featured_image" name="gp_featured_image" accept="image/png, image/gif, image/jpeg" required />
					<br/>
					<i class="desc"><?php echo esc_html__( 'Note: Max file size upload limit is 2 MB.', 'guest-post' ); ?></i>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<p class="message hidden"></p>
					<input type="submit" id="guest-post" name="guest-post" value="<?php echo esc_attr__( 'Submit', 'guest-post' ); ?>">
				</td>
			</tr>   
		</tfoot>   
	</table>
</form>    

