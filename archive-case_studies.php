<?php get_header(); ?>

  <section class="hero-case-studies">
    <div class="container">
      <h1 class="heading">Case Studies</h1>
    </div>
  </section>

  <section class="intro">
    <div class="container">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <p>
            <span>Case Studies</span> - Read more about the projects we have been involved in.
          </p>
        </div>
      </div>
    </div>
  </section>

  <div class="featured-cs">
    <div class="container">
      <div class="featured-cs__inner">
        <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
          <?php if(!get_field('featured')) {
            continue;
          } ?>
          <div class="featured-cs__item">
            <div class="featured-cs__image-wrapper">
              <a href="<?php echo get_the_permalink(); ?>">
                <div class="featured-cs__inner-image-wrapper">
                  <?php the_post_thumbnail( 'large' ); ?>
                </div>
              </a>
            </div>

            <div class="featured-cs__content">
              <div class="featured-cs__content-inner">            
                <h2 class="featured-cs__title">
                  <a href="<?php echo get_the_permalink(); ?>">
                    <?php the_title(); ?>
                  </a>
                </h2>

                <div class="featured-cs__excerpt">
                  <?php the_excerpt(); ?>
                </div>

                <div class="featured-cs__link-wrapper">
                  <a href="<?php echo the_permalink (); ?>" class="btn orange-rounded">Read More</a>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; endif; ?>
      </div>
    </div>
  </div>

  <section class="case-study-items">
    <div class="container">
      <div class="row">
      <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <?php if(get_field('featured')) {
          continue; 
        }; ?>

        <?php if ($wp_query->current_post % 2 == 0): ?>
        <div class="col-md-6 col-lg-4 mx-auto">
          <div class="case-study">
            <a href="<?php echo the_permalink(); ?>">
              <div class="img-content" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>')">
              </div>
            </a>
            <div class="text-content">
              <div class="inner-text">
                <h3 class="heading">
                  <?php the_title(); ?>
                </h3>
                <p><?php the_excerpt(); ?></p>
                <a href="<?php echo the_permalink (); ?>" class="btn orange-rounded mx-auto mt-4">READ MORE</a>
              </div>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="col-md-6 col-lg-4 mx-auto">
          <div class="case-study alt">  
            <div class="text-content">
              <div class="inner-text">
                <h3 class="heading">
                  <?php the_title(); ?>
                </h3>
                <p><?php the_excerpt(); ?></p>
                <a href="<?php echo the_permalink(); ?>" class="btn blue-rounded mx-auto mt-4">READ MORE</a>
              </div>
            </div>
            <a href="<?php echo the_permalink(); ?>">
              <div class="img-content" style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>')">
                <!-- Add Inline Background Image! -->
              </div>
            </a>
          </div>
        </div>
        <?php endif ?>
      <?php endwhile; endif; ?>
      </div>
    </div>
  </section>

  <div class="container">
    <hr class="mb-0">
  </div>

  <section class="case-study-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto text-center py-5">
          <div class="button-block">
            <a href="<?php echo esc_url(home_url('/membership')) ?>" class="btn blue-rounded m-1">Membership</a>
            <a href="<?php echo esc_url(home_url('/contact')) ?>" class="btn orange-rounded m-1">Contact Us</a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>