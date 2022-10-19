<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package understrap
 */

get_header();
?>

<?php
$container   = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

			<main class="site-main" id="main">
        
				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
						?>
					</header><!-- .page-header -->

          <?php /* Example Filters - Put in a template of it's own */ ?>

          <?php
            $plans = wc_memberships_get_membership_plans();
            $current_filter = !empty($_GET['plan']) ? absint($_GET['plan']) : false;

            if(!empty($plans)){
              echo '<ul>';
              foreach($plans as $plan){
                $filter_url = add_query_arg('plan', $plan->get_id(), get_post_type_archive_link('wc_user_membership'));

                echo sprintf('<li %s><a href="%s">%s</a></li>',
                  $current_filter === $plan->get_id() ? 'style="font-weight:bold"' : '',
                  $filter_url,
                  $plan->get_name()
                );
              }
              echo '</ul>';
            }
            
          ?>

          <?php /* Example Filters - End */ ?>

					<?php /* Start the Loop */ ?>
          <?php while ( have_posts() ) : the_post(); ?>
          
						<?php

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'templates/content-wc_user_membership' );
						?>

					<?php endwhile; ?>

				<?php else : ?>

					<?php get_template_part( 'templates/content', 'none' ); ?>

				<?php endif; ?>

			</main><!-- #main -->

		</div><!-- #primary -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
