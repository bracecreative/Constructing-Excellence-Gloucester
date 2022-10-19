<?php

// global $wp_query;

// $modifications = array(
//   'posts_per_page' => 12,
//   'orderby' => 'title',
//   'order'   => 'ASC',
// );
// $company_terms = get_terms('company_industry');
// $location_terms = get_terms('company_location');
// $taxonomy_query = array();

// if(!empty($_GET['industry'])) {
//   $taxonomy_query['tax_query'][] = array(
//     'taxonomy' => 'company_industry',
//     'field' => 'slug',
//     'terms' => $_GET['industry']
//   );
// }

// if(!empty($_GET['location'])) {
//   $taxonomy_query['tax_query'][] = array(
//     'taxonomy' => 'company_location',
//     'field' => 'slug',
//     'terms' => $_GET['location']
//   );
// }


// $args = array_merge(
// 	$wp_query->query_vars,
//   $modifications,
//   $taxonomy_query
// );

// query_posts( $args );

?>

<?php get_header(); ?>

  <section class="hero-members">
    <div class="container">
      <h1 class="heading">Our Members</h1>
    </div>
  </section>

  <form class='post-filters'>
    <div class="container">
      <div class="inner-form">
        <div class="row">
          <div class="col-md-3 field">
            <input placeholder="Name..." type="text" name="s" value="<?php if(!empty($_GET['s'])) echo $_GET['s']; ?>">
          </div>

          <div class="col-md-3 field">
            <select name="industry">
              <option value="">All Industries</option>
              <?php foreach($company_terms as $term) : ?>
                <option value="<?php echo $term->slug; ?>" <?php if(!empty( $_GET['industry'] ) && $_GET['industry'] == $term->slug) echo "selected='selected'" ?>>
                  <?php echo $term->name ;?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3 field">
            <select name="location">
              <option value="">All Locations</option>
              <?php foreach($location_terms as $location) : ?>
                <option value="<?php echo $location->slug; ?>" <?php if(!empty($_GET['location']) && $_GET['location'] == $location->slug) echo "selected='selected'" ;?>>
                  <?php echo $location->name; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3 text-right">
            <button type="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <section class="directory">
    <div class="container">
      <div class="row">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="col-md-4 col-sm-6">
          <?php
            $image_attributes = wp_get_attachment_image_src(get_field('company_logo'), 'full');

            $default_image = wp_get_attachment_image_src(947);
          ?>
          <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <div class="member">
              <?php if($image_attributes) : ?>
                <a href="<?php echo the_permalink(); ?>">
                  <div class="directory-thumbnail">
                    <img src="<?php echo $image_attributes[0]; ?>" alt="<?php echo the_title(); ?> Logo">
                  </div>
                </a>
              <?php else : ?>
                <a href="<?php echo the_permalink(); ?>">
                  <div class="directory-thumbnail">
                    <img src="<?php echo $default_image[0]; ?>" alt="CE Default Avatar">
                  </div>
                </a>
              <?php endif; ?>

              <div class="meta">
                <a class="name" href="<?php echo the_permalink(); ?>">
                  <?php the_title(); ?>
                </a>
              </div>
            </div>
          </article><!-- #post-## -->
        </div>
        <?php endwhile; ?>
      </div>
      <div class="pagination d-block text-center">
        <?php echo paginate_links(); ?>
      </div>

      <?php else : ?>
        <div class="col-12">
          <div class="no-content-box">
            <p>Sorry, no members have been found with your search criteria.</p>
            <a href="<?php echo esc_url(home_url('/members'));  ?>" class="btn orange-rounded">All Members</a>
          </div>
          <?php endif; ?>
        </div>
      </div>

  </section>

<?php get_footer(); ?>