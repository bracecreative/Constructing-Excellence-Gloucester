<?php

$args = array(
  'post_type'      => 'company_profiles',
  'post_status'    => array('publish', 'draft'),
  'posts_per_page' => -1
);

$company_profile_id = 0;

if (is_user_logged_in()) {
  $user = wp_get_current_user();
  if(in_array('ce_member', (array) $user->roles) || in_array('administrator', (array) $user->roles)) {

    $all_posts = get_posts($args);

    $user_company = get_user_meta($user->ID, 'company', true);

    if(!empty($all_posts)){
      foreach($all_posts as $post){

        // $allowed_users = get_field('user_attachment', $post->ID);

        // if(!empty($allowed_users) && in_array( $user->ID, $allowed_users ) ) {
        //   $company_profile_id = $post->ID;
        // }

        if ($user_company == $post->ID) {
          $company_profile_id = $post->ID;
        }
      }
    }
  } else {
    // No posts found
  }
}

?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="theme-color" content="#322268"/>

  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">


  <title>
      <?php wp_title(''); ?>
  </title>

  <?php wp_head(); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134153194-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-134153194-13');
</script>

</head>

<body <?php body_class(); ?>>
  <div class="site-wrapper">
  <div class="header">
    <div class="container">
      <div class="row">
        <div class="inner-nav">
          <div class="logo my-auto">
            <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo get_template_directory_uri(); ?>/img/logo@2x.png" alt="Constructing Excellence Logo">
            </a>
          </div>

          <div class="contact-info">
            <?php if(is_user_logged_in()): ?>
            <div class="member-login">

              <?php if($company_profile_id !== 0): ?>
                <div class="dropdown">
                  <a class="dropdown-toggle" href="#" role="button" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Account
                  </a>
                  <span class="d-none d-md-inline-block">|</span>

                  <div class="dropdown-menu" aria-labelledby="accountDropdown">
                    <a class="dropdown-item" href="<?php echo esc_url(home_url('/my-account')); ?>">My Account</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo get_post_permalink($company_profile_id); ?>">
                    Company Profile
                    </a>
                    <a class="dropdown-item" href="<?php echo get_edit_post_link($company_profile_id); ?>">
                    Edit Company Profile
                    </a>
                  </div>
                </div>

              <?php else : ?>
                <a href="<?php echo esc_url(home_url('/my-account')); ?>">Account</a>
                <span class="d-none d-md-inline-block">|</span>
              <?php endif; ?>

              <a href="<?php echo wc_get_cart_url(); ?>"><i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;<?php echo WC()->cart->get_cart_total(); ?></a> <span class="d-none d-md-inline-block">|</span>
              <a href="<?php echo wp_logout_url( home_url() ); ?>">Logout</a>
            </div>
            <?php else: ?>
            <div class="member-login">
              <a href="<?php echo esc_url(home_url('/my-account')); ?>">Members Login</a>
              <span class="d-none d-md-inline-block">|</span>
              <a href="<?php echo wc_get_cart_url(); ?>"><i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;<?php echo WC()->cart->get_cart_total(); ?></a>
            </div>
            <?php endif; ?>
            <div class="contact-inner">
              <div class="social-links">
                <a href="https://twitter.com/CE_Glos" target="_blank">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.instagram.com/ceglos1/" target="_blank">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="https://www.linkedin.com/company/constructing-excellence-gloucestershire-club/" target="_blank">
                  <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="https://www.facebook.com/Constructing-Excellence-Gloucestershire-Club-616672692110900/" target="_blank">
                  <i class="fab fa-facebook-f"></i>
                </a>
              </div>

              <div class="mobile">
                <i class="fas fa-envelope"></i><a href="mailto:hello@ceglos.org.uk" onclick="gtag('event', 'click', {'event_category': 'Email Click Tracking', 'event_label': 'Contact Email'})">hello@ceglos.org.uk</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <button class="hamburger hamburger--collapse navbar-toggler" type="button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
          <span class="hamburger-box">
            <span class="hamburger-inner"></span>
          </span>
        </button>

        <?php
        wp_nav_menu( array(
          'theme_location'  => 'primary',
          'depth'	          => 2, // 1 = no dropdowns, 2 = with dropdowns.
          'container'       => 'div',
          'container_class' => 'collapse navbar-collapse',
          'container_id'    => 'navbarSupportedContent',
          'menu_class'      => 'navbar-nav',
          'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
          'walker'          => new WP_Bootstrap_Navwalker(),
        ) );
        ?>
      </div>
    </nav>
  </div>