<?php get_header(); ?>

 <section class="hero-page">
    <div class="container">
      <h1 class="heading">Our Members</h1>
    </div>
  </section>



  <section class="directory">
    <div class="controls">
      <div class="container">
        <div class="row">
          <div class="col-md-6 mx-auto">
            <!-- <div class="plan-search">
              <form>
                <div class="row">
                  <div class="col-md-9">
                    <label class="text-muted" for="inputPlan">Membership Plan</label>
                    <?php

                      // Get list of all plans
                      $plans = wc_memberships_get_membership_plans();

                      // Get the currently filtered plan, if any
                      $filtered_plan = !empty($_GET['plan']) ? absint($_GET['plan']) : false;

                    ?>
                    <select class="form-control" id="inputPlan" name="plan">
                      <option value="">Any</option>

                      <?php /* Loop through the plans, rendering an option for each one,
                      setting it selected if it matches the filtered plan above */ ?>

                      <?php foreach($plans as $plan): ?>
                      <option value="<?php echo $plan->get_id(); ?>" <?php selected($filtered_plan, $plan->get_id()); ?>>
                        <?php echo $plan->get_name(); ?>
                      </option>
                      <?php endforeach; ?>

                    </select>
                  </div>
                  <div class="col-md-3 search-submit">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                  </div>
                </div>
              </form>
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <?php while(have_posts()): the_post(); ?>
        <div class="col-md-4 col-sm-6">
          <?php /* Use template part specifically created for wc_user_membership posts */ ?>
          <?php get_template_part('templates/content-wc_user_membership'); ?>
        </div>
        <?php endwhile; ?>
      </div>
      <div class="pagination d-block text-center">
        <?php echo paginate_links(); ?>
      </div>
    </div>

  </section>

<?php get_footer(); ?>