<div class="post-content">
  <div class="post-hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo get_the_post_thumbnail_url(); ?>') ">
    <div class="container">
      <h1 class="heading mb-0">
        <?php the_title(); ?>
      </h1>
    </div>
  </div>

  <?php if(get_field('vimeo_id')) : ?>
    <div class="case-study-video mt-5 mb-0">
      <div class="container">
        <div class="case-study-video__wrapper">
          <iframe src="https://player.vimeo.com/video/<?php echo get_field('vimeo_id'); ?>" width="640" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="post-text my-5">
    <div class="container">
      <?php the_content(); ?>
    </div>
  </div>

  <div class="container">
    <?php $associated_company = get_field('associated_company'); if( $associated_company ): ?>
    <div class="associated-company">
      <h3 class="associated-company__title"><?php echo count($associated_company) == 1 ? 'Associated Company' : 'Associated Companies'; ?></h3>
        <?php foreach( $associated_company as $post ): setup_postdata($post); ?>
          <?php if( get_field('company_logo') ) {
            $logo = wp_get_attachment_image(get_field('company_logo'), 'full', '', array('class' => 'thumbnail'));
          } else {
            $logo = wp_get_attachment_image(1013, 'full', '', array('class' => 'thumbnail'));
          }; ?>
          <div class="associated-company__company company-profile">
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
            </div>
          </div>
        <?php endforeach; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>
    
    <?php if(have_rows('resources')) : ?>
      <div class="cs-resources mb-5">
        <h3 class="cs-resources__title">Case Study Resources</h3>

        <div class="cs-resources__grid">
          <?php while(have_rows('resources')) : the_row(); ?>            
            <div class="cs-resources__resource">
              <div class="cs-resources__resource-inner">             
                <div class="cs-resources__meta">
                  <div class="cs-resources__icon">
                    <?php if(get_sub_field('resource_type') === 'media') : ?>
                      <i class="fa fa-file"></i>
                    <?php elseif(get_sub_field('resource_type') === 'video') : ?>
                      <i class="fa fa-video"></i>
                    <?php elseif(get_sub_field('resource_type') === 'link') : ?>
                      <i class="fa fa-link"></i>
                    <?php endif; ?>
                  </div>

                  <h4 class="cs-resources__resource-title">
                    <?php the_sub_field('resource_title'); ?>
                  </h4>
                </div>

                <div class="cs-resources__link-wrapper">
                  <?php if(get_sub_field('resource_type') === 'media') : ?>
                    <a href="<?php echo get_sub_field('media'); ?>" target="blank" rel="nofollower noreferrer" class="btn orange">
                      Download
                    </a>
                  <?php elseif(get_sub_field('resource_type') === 'video') : ?>
                    <a href="<?php echo get_sub_field('video_url'); ?>" class="btn orange" target="blank" rel="nofollower noreferrer">
                      View
                    </a>
                  <?php elseif(get_sub_field('resource_type') === 'link') : ?>
                    <a href="<?php echo get_sub_field('url'); ?>" class="btn orange" target="blank" rel="nofollower noreferrer">
                      Visit
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

