<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// A way of getting the current user membership
$user_membership = wc_memberships_get_user_membership(get_the_ID());

// A way of getting the user data from the membership
$user = get_userdata($user_membership->get_user_id());
$user_id = $user_membership->get_user_id();
$company_name = get_user_meta($user_id, 'billing_company', true);

// We can use $user_membership->get_plan() to get the plan for this membership (see below)

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

    <div class="member">
        <?php echo get_wp_user_avatar($user_membership->get_user_id(), 'original'); ?>
        <div class="meta">

            <h2 class="name"><?php echo $user->display_name; ?></h2>
            <h5 class="plan text-muted"><?php echo $user_membership->get_plan()->get_name(); ?></h5>
            <h5 class="plan text-muted text-capitalize"><?php echo $company_name; ?></h5>
        </div>
    </div>

</article><!-- #post-## -->
