<?php
$user = wp_get_current_user();
$memberships = wc_memberships_get_user_active_memberships($user->ID);

$terms = get_terms('resource_category');


$modifications = array(
  'posts_per_page' => 12,
  'post_type' => 'resources',
  's' => $_GET['s'],
);
$taxonomy_query = array();

if(!empty($_GET['date'])) {
  $var = $_GET['date'];
  $date = str_replace('/', '-', $var);
  $formatted_date = date('Ymd', strtotime($date));
  $modifications['meta_query'][] = array(
    'key' => 'asset_date',
    'value' => $formatted_date
  );
}

if(!empty($_GET['category'])) {
  $taxonomy_query['tax_query'][] = array(
    'taxonomy' => 'resource_category',
    'field' => 'slug',
    'terms' => $_GET['category']
  );
}

$args = array_merge(
  $modifications,
  $taxonomy_query
);

$assets = get_posts($args);

get_header();

?>

  <section class="hero-resources">
    <div class="container">
      <h1 class="heading">Resource Hub</h1>
    </div>
  </section>

  <section class="intro">
    <div class="container">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <p>
            <span>Resource Hub</span> - Take a look through the available resources.
          </p>
        </div>
      </div>
    </div>
  </section>

  <form class='post-filters'>
    <div class="container">
      <div class="inner-form">
        <div class="row">
          <div class="col-md-3 field">
            <input placeholder="Resource Name..." type="text" name="s" value="<?php if(!empty($_GET['s'])) echo $_GET['s']; ?>">
          </div>

          <div class="col-md-3 field">
            <select name="category">
              <option value="">All Categories</option>
              <?php foreach($terms as $term) : ?>
                <option value="<?php echo $term->slug; ?>" <?php if(!empty( $_GET['category'] ) && $_GET['category'] == $term->slug) echo "selected='selected'" ?>>
                  <?php echo $term->name ;?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-3 field">
            <input name="date" placeholder="Date" type="text" id="datepicker" value="<?php if(!empty($_GET['date'])) echo $_GET['date']; ?>">
          </div>

          <div class="col text-right">
            <button type="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </form>





  <section class="resource-items">
    <div class="container">
      <div class="row">
        <?php foreach($assets as $post): setup_postdata($post) ; ?>
          <div class="col-lg-4 col-md-6 single-asset-wrapper">
            <div class="single-asset">
              <h3 class="heading">
                <?php the_title(); ?>
              </h3>

              <div class="resource-info">
                <div class="thumbnail-wrapper text-center">
                  <?php
                  if(has_post_thumbnail()):
                    echo the_post_thumbnail(array(50,50));
                  else:
                    echo wp_get_attachment_image(1013, array(50,50));
                  endif;
                  ?>
                </div>

                <div class="meta-info">
                  <?php if( get_field( 'asset_date' ) ): ?>
                    <div class="meta-section">
                      <i class="fas fa-clock fa-fw"></i>
                      <p>
                        <?php the_field('asset_date'); ?>
                      </p>
                    </div>
                  <?php endif; ?>

                  <?php if( get_field( 'related_event' ) ):
                    $events = get_field('related_event');
                  ?>
                    <?php foreach($events as $event): ?>
                      <?php $title = get_the_title($event); ?>
                      <div class="meta-section">
                        <i class="fas fa-calendar-day fa-fw"></i>
                        <a href="<?php the_permalink( $event ); ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>

                  <?php
                    $terms = get_the_terms(get_the_ID(), 'resource_category');

                    if ( $terms && ! is_wp_error( $terms ) ) :
                      $category_names = array();

                      foreach ( $terms as $term ) {
                        $category_names[] = $term->name;
                      }

                    $cat_names = join( ", ", $category_names );
                  ?>
                    <div class="meta-section">
                      <i class="fas fa-tags fa-fw"></i>
                      <p>
                        <?php printf( esc_html__( '%s', 'textdomain' ), esc_html( $cat_names ) ); ?>
                      </p>
                    </div>

                  <?php endif; ?>
                </div>
              </div>

              <?php if( get_field('short_description') ) : ?>
                <div class="description">
                  <p><?php wpautop(the_field( 'short_description' )); ?></p>
                </div>
              <?php endif; ?>

              <?php
                $file = get_field('asset_file');
                $asset_link =  get_field('asset_link');

                if($asset_link):
                  $link = $asset_link;
                  $link_text = 'Visit Link';
                endif;

                if( $file ):
                  $link =  $file['url'];
                  $link_text = 'Download';
                endif;
              ?>

              <div class="link-wrapper">
                <?php if(!empty($memberships)): ?>
                  <a href="<?php echo $link; ?>" target="_blank"><?php echo $link_text; ?></a>
                <?php elseif( $user->ID !== 0 ): ?>
                  <a href="<?php echo esc_url(home_url('/membership')); ?>" target="_blank">Get Membership</a>
                <?php else:  ?>
                  <a href="<?php echo esc_url(home_url('/my-account')); ?>">Log In</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; wp_reset_postdata(); ?>

        <?php if(empty($assets)): ?>
          <div class="col-md-12">
            <div class="no-content">
              <p>Sorry, no assets could be found!</p>

              <a href="<?php echo esc_url(home_url('/resources')); ?>">Reset Search Query</a>
            </div>
          </div>
        <?php endif; ?>
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