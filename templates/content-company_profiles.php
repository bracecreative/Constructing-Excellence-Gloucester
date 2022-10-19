<?php
  if( get_field('company_logo') ) {
    $logo = wp_get_attachment_image(get_field('company_logo'), 'full', '', array('class' => 'thumbnail'));
  } else {
    $logo = wp_get_attachment_image(1013, 'full', '', array('class' => 'thumbnail'));
  }

  // check for logged in user to hide team members from non-signed in users
  $user = wp_get_current_user();
  $memberships = wc_memberships_get_user_memberships($user->ID);
  $contact_access = false;

  if(!empty($memberships)) {
    $contact_access = true;

    foreach($memberships as $membership) {
      if ($membership->plan->slug === 'retired' || $membership->plan->slug === 'student') {
        $contact_access = false;
        break;
      }
    }
  }
?>

<div class="company-profile">
  <div class="container">
    <div class="company-header">
      <div class="thumbnail-wrapper">
        <?php echo $logo; ?>
      </div>

      <div class="content">
        <h1 class="company-name"><?php the_title(); ?></h1>

        <div class="social-icons">
          <ul>
            <?php if( get_field('facebook')): ?>
            <li>
              <a href="<?php the_field('facebook'); ?>">
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
            <?php endif; ?>

            <?php if( get_field('twitter')): ?>
            <li>
              <a href="<?php the_field('twitter'); ?>">
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <?php endif; ?>

            <?php if( get_field('instagram')): ?>
            <li>
              <a href="<?php the_field('instagram'); ?>">
                <i class="fab fa-instagram"></i>
              </a>
            </li>
            <?php endif; ?>

            <?php if( get_field('linked_in')): ?>
            <li>
              <a href="<?php the_field('linked_in'); ?>">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </div>

        <div class="meta-fields">
          <div class="row">
            <?php if( get_field('telephone') ) : ?>
            <div class="col-md-4">
              <div class="meta">
                <div class="icon">
                  <i class="fas fa-phone"></i>
                </div>

                <div class="meta-content">
                  <a href="tel:<?php the_field('telephone'); ?>">
                      <?php the_field('telephone'); ?>
                    </a>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <?php if( get_field('email') ) : ?>
            <div class="col-md-4">
              <div class="meta">
                <div class="icon">
                  <i class="fas fa-envelope"></i>
                </div>

                <div class="meta-content">
                  <a href="mailto:<?php the_field('email'); ?>">
                      <?php the_field('email'); ?>
                    </a>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <?php if( get_field('website') ) : ?>
            <div class="col-md-4">
              <div class="meta">
                <div class="icon">
                  <i class="fas fa-globe-europe"></i>
                </div>

                <div class="meta-content">
                  <a href="<?php the_field('website'); ?>">
                      <?php the_field('website'); ?>
                    </a>
                </div>
              </div>
            </div>
            <?php endif; ?>

            <?php if( get_field('address') ) : ?>
            <div class="col-md-12">
              <div class="meta">
                <div class="icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>

                <div class="meta-content mb-0">
                  <p>
                    <?php the_field('address'); ?>
                  </p>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="text-content">
        <?php the_content(); ?>
      </div>

      <?php if( have_rows('team_members') ) : ?>
        <div class="team-members">
          <h2 class="heading">
            Team Members
          </h2>

          <?php if( $contact_access ): ?>
          <div class="row">
            <?php while( have_rows('team_members') ) : the_row();

              // vars
              $name = get_sub_field('name');
              $job_role = get_sub_field('job_role');
              $email = get_sub_field('email');
              $phone = get_sub_field('phone');
              $phone_formatted = $str = str_replace(' ','', $phone);
            ?>
            <div class="col-md-4">
              <div class="team-member">
                <div class="icon">
                  <i class="fas fa-user"></i>
                </div>

                <div class="meta-details">
                  <h3 class="meta name"><?php echo $name; ?></h3>
                  <?php if($job_role): ?>
                    <h4 class="meta job-role"><?php echo $job_role; ?></h4>
                  <?php endif; ?>

                  <?php if($email): ?>
                    <h4 class="meta email">
                      <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                    </h4>
                  <?php endif; ?>

                  <?php if($phone): ?>
                    <h4 class="meta phone">
                      <a href="tel:<?php echo $phone_formatted; ?>"><?php echo $phone; ?></a>
                    </h4>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
          <?php else:  ?>
            <div class="non-member-notice">
              <p>Team members details are only available to Constructing Excellence Members, Corporate Members and Corporate Plus Members. If you are already a member, please <a href="<?php echo esc_url(home_url('/my-account')); ?>">log in</a>. If you're not a member, head over to the <a href="<?php echo esc_url(home_url('/memberships')); ?>">Memberships</a> page to take a look at our membership options.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>