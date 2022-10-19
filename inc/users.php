<?php

add_action('show_user_profile', 'brace_add_extra_user_fields');
add_action('edit_user_profile', 'brace_add_extra_user_fields');
function brace_add_extra_user_fields($user) {

	$viewing_user = wp_get_current_user();
	
	if (in_array('administrator', $viewing_user->roles)) {

		$chosen_theme_groups = get_user_meta($user->ID, 'theme_group', true);

		$theme_groups = get_terms(array(
			'taxonomy' => 'theme_group',
			'hide_empty' => false,
		));

		$args = array(
			'post_type' => 'company_profiles',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		);

		$query = new WP_Query($args); ?>
		
		<h3>Select Your Company</h3>
		<select name="company">
			<option value="">Company</option>
			<?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post(); ?>
				<option value="<?php the_ID(); ?>" <?php echo get_user_meta($user->ID, 'company', true) == get_the_ID() ? 'selected="selected"' : ''; ?>>
					<?php the_title(); ?>
				</option>
			<?php endwhile; endif; ?>
		</select>

	<?php }
}

add_action('edit_user_profile_update', 'brace_save_extra_user_fields');
function brace_save_extra_user_fields($user_id) {
	update_user_meta($user_id, 'company', sanitize_text_field($_POST['company']));
}
