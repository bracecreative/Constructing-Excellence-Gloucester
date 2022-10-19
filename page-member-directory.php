<?php get_header(); ?>

  <section class="hero-members">
    <div class="container">
      <h1 class="heading">Member Directory</h1>
    </div>
  </section>

  <section class="intro">
    <div class="container">
      <div class="row">
        <div class="col-md-10 mx-auto">
          <p>
            <span>Members</span> - Search through the members of constructing excellence.
          </p>
        </div>
      </div>
    </div>
  </section>


    <section class="case-study-items">
        <div class="container">
            <div class="row">

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Company</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $args = array( 
                        'post_type' => 'wc_user_membership',
                        'post_status' => 'wcm-active',
                        'posts_per_page' => -1
                        );

                        $my_query = new WP_Query($args);

                        // The Loop
                        while ( $my_query->have_posts() ) : $my_query->the_post();
                        $user = get_userdata(get_the_author_meta('ID')); 

                        $user_id = $user->ID;

                        $allmeta = get_user_meta( $user_id );
                        
                        $firstname = $allmeta['first_name'][0];
                        $lastname = $allmeta['last_name'][0];
                        $company = $allmeta['billing_company'][0];
                        
                        echo '<tr>';
                            echo '<td>' . $user_id . '</td>';
                            echo '<td>' . $firstname . '</td>';
                            echo '<td>' . $lastname . '</td>';
                            echo '<td>' . $company . '</td>';
                        echo '</tr>';

                        endwhile;

                        // Reset Query
                        wp_reset_query();
                        ?>
                    </tbody>
                </table>
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
            <a href="<?php echo esc_url(home_url('/membership')) ?>" class="btn blue-rounded mx-1">Membership</a>
            <a href="<?php echo esc_url(home_url('/contact')) ?>" class="btn orange-rounded mx-1">Contact Us</a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>