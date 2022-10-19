<?php get_header(); ?>

  <section class="hero-blog">
    <div class="container">
      <h1 class="heading">Blog</h1>
    </div>
  </section>

  <section class="intro">
    <div class="container">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <p>
            <span>Blog</span> - Read about our events and what we've been up to recently.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="blog-items">
    <div class="container">
      <div class="row">
      <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
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
                <?php the_excerpt(); ?>
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
                <?php the_excerpt(); ?>
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
      <?php endwhile; ?>
        <div class="nav-previous alignleft"><?php previous_posts_link( 'Older posts' ); ?></div>
        <div class="nav-next alignright"><?php next_posts_link( 'Newer posts' ); ?></div>

      <?php else : ?>
        <p class="mb-0"><?php _e("Sorry, we currently don't have any posts to show"); ?></p>
      <?php endif; ?>
      </div>
    </div>
  </section>

<?php get_footer(); ?>