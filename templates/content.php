<div class="post-content main-blog">
  <div class="post-hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo get_the_post_thumbnail_url(); ?>') ">
    <div class="container">
      <div class="header">
        <h1 class="heading mb-0">
          <?php the_title(); ?>
        </h1>
        <h3 class="date">
          <?php echo the_date(); ?>
        </h3>
      </div>
    </div>
  </div>

  <div class="post-text my-5">
    <div class="container">
      <?php the_content(); ?>
    </div>
  </div>
</div>