<?php

require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
require_once get_template_directory() . '/inc/registration-form.php';
require_once get_template_directory() . '/inc/woocommerce.php';
require_once get_template_directory() . '/inc/users.php';

function ce_theme_setup() {
	register_nav_menus(array(
		'primary' => __('Primary Menu')
  ));

  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'title-tag' );
}

add_action('after_setup_theme', 'ce_theme_setup');

add_filter( 'excerpt_length', function($length) {
  return 20;
} );

require_once get_template_directory() . '/inc/events.php';
$brace_events = new BraceEvents();

// auto load shortcodes
add_action('init', 'brace_autoload_shortcodes', 1);
function brace_autoload_shortcodes(){
  $dir = get_stylesheet_directory() . '/shortcodes/visual-composer';
  $pattern = $dir . '/*.php';

  $files = glob($pattern);
  foreach($files as $file){
      $parts = pathinfo($file);
      $name = $parts['filename'];

      require_once($file);
  }
}

function ce_scripts() {
  // Load the datepicker script (pre-registered in WordPress).
  wp_enqueue_script( 'jquery-ui-datepicker' );

  // Add custom fonts, used in the main stylesheet.
  wp_enqueue_style('jquery-ui-datepicker-styles', get_template_directory_uri() . '/css/jquery-ui.min.css');

	// Add custom fonts, used in the main stylesheet.
  wp_enqueue_style( 'ce-font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800', array(), null );

	// Add custom fonts, used in the main stylesheet.
  wp_enqueue_style( 'ce-font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800', array(), null );

  // Add Genericons, used in the main stylesheet.
  wp_enqueue_style( 'gco-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css', array(), null );

	// Add Genericons, used in the main stylesheet.
  wp_enqueue_style( 'ce-secondary-font', 'https://use.typekit.net/hkw2hix.css', array(), null );

  wp_enqueue_style('ce-fa', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', array(), null);

  wp_enqueue_style('ce-burgers', get_template_directory_uri() . '/css/hamburgers.min.css');

  // wp_enqueue_style('ce-styles', get_template_directory_uri() . '/style.css', array(), filemtime( get_stylesheet_directory() .'/style.css' ));
  wp_enqueue_style('ce-styles', get_template_directory_uri() . '/dist/css/main.css', array(), filemtime( get_stylesheet_directory() .'/dist/css/main.css' ));
  // wp_enqueue_style('ce-styles', get_template_directory_uri() . '/dist/css/main.css', array(), false);


  wp_enqueue_script( 'popper-script',
  'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js', array( 'jquery' ), true );

	wp_enqueue_script( 'bootstrap-script',
  'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', array( 'jquery' ), true );

	wp_enqueue_script( 'lazyload',
  'https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.16.2/lazyload.min.js', array( 'jquery' ), true );

  wp_enqueue_script( 'ce-script', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), true);
  wp_enqueue_script( 'brace-script', get_template_directory_uri() . '/dist/js/bundle.js', array(), filemtime( get_stylesheet_directory() .'/dist/js/bundle.js' ), true);
}

add_action( 'wp_enqueue_scripts', 'ce_scripts' );

// Register 1
function homepage_hero_shortcode() {
  get_template_part('shortcodes/homepage-hero');
}
add_shortcode('homepage_hero','homepage_hero_shortcode');

function homepage_image_links_shortcode() {
  get_template_part('shortcodes/homepage-image-links');
}
add_shortcode('homepage_image_links','homepage_image_links_shortcode');

function ce_register_shortcode() {
  get_template_part('shortcodes/ce-register');
}
add_shortcode('ce_register','ce_register_shortcode');



// Case-study Post Type
function create_post_type() {
  register_post_type( 'case_studies',
    array(
      'labels' => array(
        'name' => __( 'Case Studies' ),
        'singular_name' => __( 'Case Study' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    )
  );

}
add_action( 'init', 'create_post_type' );

// Case-study Post Type
function create_resource_post_type() {
  register_post_type('resources',
   array(
	   'labels'      => array(
		   'name'          => __('Resources'),
		   'singular_name' => __('Resource'),
	   ),
	   'public'      => true,
     'has_archive' => true,
     'capabilities' => array(
        'edit_post' => 'edit_resource',
        'edit_posts' => 'edit_resources',
        'edit_others_posts' => 'edit_other_resources',
        'publish_posts' => 'publish_resources',
        'read_post' => 'read_resource',
        'read_private_posts' => 'read_private_resources',
        'delete_post' => 'delete_resource'
      ),
	   'supports'    => array('title', 'author', 'thumbnail'),
     'slug'        => 'resources',
     'map_meta_cap' => true
   )
  );

}
add_action( 'init', 'create_resource_post_type' );

// create company post type
function create_company_post_type() {
  register_post_type('company_profiles',
    array(
      'labels' => array(
        'name' => __('Company Profiles'),
        'singular_name' => __('Company Profile')
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
      'menu_icon' => 'dashicons-admin-users',
			'capability_type'    => array('company', 'companies'),
      'capabilities' => array(
        'edit_post' => 'edit_company',
        'edit_posts' => 'edit_companies',
        'edit_others_posts' => 'edit_other_companies',
        'publish_posts' => 'publish_companies',
        'read_post' => 'read_company',
        'read_private_posts' => 'read_private_companies',
        'delete_post' => 'delete_company'
      ),
      'rewrite' => array( 'slug' => 'members' ),
      // as pointed out by iEmanuele, adding map_meta_cap will map the meta correctly
      'map_meta_cap' => true
    )
    );
}
add_action('init', 'create_company_post_type');

function register_taxonomies(){
  $taxonomies = array(
    'company_industry' => array(
      'hierarchical'          => true,
      'labels'                => array(
        'name'                       => _x( 'Company Industries', 'taxonomy general name' ),
        'singular_name'              => _x( 'Company Industry', 'taxonomy singular name' ),
      ),
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'post_types'			=> array('company_profiles'),
      'capabilities' => array(
        'manage_terms'=> 'manage_industries',
        'edit_terms'=> 'manage_industries',
        'delete_terms'=> 'delete_industries',
        'assign_terms' => 'edit_industries'
      ),

    ),
    'company_location' => array(
      'hierarchical'          => true,
      'labels'                => array(
        'name'                       => _x( 'Locations', 'taxonomy general name' ),
        'singular_name'              => _x( 'Location', 'taxonomy singular name' ),
      ),
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'post_types'			=> array('company_profiles'),
      'capabilities' => array(
        'manage_terms'=> 'manage_locations',
        'edit_terms'=> 'manage_locations',
        'delete_terms'=> 'delete_locations',
        'assign_terms' => 'edit_locations'
      ),
    ),
    'resource_category' => array(
      'hierarchical'          => true,
      'labels'                => array(
        'name'                       => _x( 'Resource Categories', 'taxonomy general name' ),
        'singular_name'              => _x( 'Resource Category', 'taxonomy singular name' ),
      ),
      'show_ui'               => true,
      'show_admin_column'     => true,
      'update_count_callback' => '_update_post_term_count',
      'post_types'			=> array('resources')
    )
  );

  foreach($taxonomies as $taxonomy => $arg)
    register_taxonomy( $taxonomy, $arg['post_types'], $arg );
}
add_action('init', 'register_taxonomies');



// removes acpabilities from a CE member if they attempt to access
// a company profile they're not allowed to edit
function override_caps($allcaps, $cap, $args){
  // $post_id = get_the_ID();

  // if($post_id){
  //   $user = wp_get_current_user();
  //   $post_type = get_post_type($post_id);

  //   if($post_type === 'company_profiles'){
  //     $allowed_users = get_post_meta($post_id, 'user_attachment', true);
  //     if( !empty($allowed_users) && !in_array( $user->ID, $allowed_users ) ){
  //       // When to override caps
  //       if(in_array('ce_member', (array) $user->roles)){
  //         $role_name = 'ce_member';
  //         $role = get_role($role_name); // Get the role object by role name
  //         $allcaps = $role->capabilities;  // Get the capabilities for the role
  //         $allcaps[$role_name] = true;
  //         $allcaps['edit_company'] = false;
  //         $allcaps['edit_companies'] = false;
  //         $allcaps['edit_other_companies'] = false;
  //         $allcaps['delete_company'] = false;
  //       }
  //     }
  //   }
  // }

  if (function_exists('get_current_screen')) {

    $post_id = get_the_ID();

		if($post_id){
			$user = wp_get_current_user();
			$post_type = get_post_type($post_id);
			$screen = get_current_screen();

			if ($screen->base === 'post' && !in_array('administrator', $user->roles)) {
				if($post_type === 'company_profiles'){
					$user_company = get_user_meta($user->ID, 'company', true);

					// This just checks if the company ID saved in the DB for the member, matches with the "company_profiles" post ID
					if ($user_company != $post_id) {
						$roles = array('ce_member');

						// If it doesn't match i.e. if they're not viewing their own company, restrict access
						foreach ($roles as $the_role) {
							$role = get_role($the_role);

							$allcaps = $role->capabilities;

							$allcaps['edit_company'] = false;
							$allcaps['edit_companies'] = false;
							$allcaps['edit_other_companies'] = false;
							$allcaps['delete_company'] = false;
						}
					}
				}
			}
		}

	}

  return $allcaps;
}
add_filter( 'user_has_cap', 'override_caps', 10, 3 );


function add_theme_caps() {
  // gets the administrator role
  $admins = get_role( 'administrator' );
  $customers = get_role('customer');

  $admins->add_cap( 'edit_company' );
  $admins->add_cap( 'edit_companies' );
  $admins->add_cap( 'edit_other_companies' );
  $admins->add_cap( 'publish_companies' );
  $admins->add_cap( 'read_company' );
  $admins->add_cap( 'read_private_companies' );
  $admins->add_cap( 'delete_company' );

  $customers->add_cap( 'edit_company' );
  $customers->add_cap( 'edit_companies' );
  $customers->add_cap( 'edit_other_companies' );
  $customers->add_cap( 'publish_companies' );
  $customers->add_cap( 'read_company' );
  $customers->add_cap( 'read_private_companies' );
  $customers->add_cap( 'delete_company' );
}
add_action( 'admin_init', 'add_theme_caps');

// Before VC Init
add_action( 'vc_before_init', 'vc_before_init_actions' );

function vc_before_init_actions() {

    // Including the separate file created here
    require_once( get_template_directory().'/shortcodes/committee-member.php' );
}

/**
 * Change the "There were no results found" text on TEC.
 */
add_filter( 'tribe_the_notices', 'change_notice', 10, 2 );
function change_notice( $html ) {
	if ( stristr( $html, 'There were no results found.' ) ) {
		//Replace 'Your custom message' with the text you want.
		$html = str_replace( 'There were no results found.', 'New events are coming soon! Please check back in the future to see the different events Constructing Excellence will be hosting.', $html );
	}
	return $html;
}

add_action( 'um_members_just_after_name', 'display_membership' );
function display_membership($user_id) {
  $user = $user_id;

  $memberships = wc_memberships_get_user_active_memberships($user);
  $membership = !empty($memberships) ? $memberships[0] : '';
  $company_name = get_user_meta($user, 'billing_company', true);

  if($membership){
    echo '<p class="'.(str_replace(' ', '-', strtolower($membership->plan->name))).'">';
    echo $membership->plan->name . ' Plan';
    echo '</p>';
  }

  echo '<p>'. $company_name .'</p>';
}

function update_membership_meta($user_id) {
  $memberships = wc_memberships_get_user_active_memberships($user_id);
  $membership = !empty($memberships) ? $memberships[0] : '';
}

add_action('wp_head', 'cookie_banner');
function cookie_banner() {
  ?>
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
  <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
  <script>
  window.addEventListener("load", function(){
  window.cookieconsent.initialise({
    "palette": {
      "popup": {
        "background": "#322268"
      },
      "button": {
        "background": "#f6b431"
      }
    },
    "content": {
      "href": "https://www.ceglos.org.uk/cookie-policy"
    }
  })});
  </script>
  <?php
}


add_shortcode('brace_list_memberships', 'brace_output_sc_list_memberships');
function brace_output_sc_list_memberships($atts = array(), $content = null){
  $query = new WP_Query(array(
    'post_type' => 'wc_user_membership',
    'post_status' => 'wcm-active',
    'posts_per_page' => -1
  ));

  ob_start(); ?>

  <?php if(!$query->have_posts()): ?>

  <?php else: ?>

  Start

    <?php while($query->have_posts()): $query->the_post(); ?>

    <?php
      $the_user = get_userdata(get_the_author_id(get_the_ID()));
      $membership = wc_get_product(wp_get_post_parent_id(get_the_ID()));
    ?>

      <pre>
        <?php var_dump($the_user->display_name); ?><br>
        <?php var_dump($membership); ?>
        <hr>
      </pre>

    <?php endwhile; wp_reset_postdata(); ?>

  End

  <?php endif; ?>

  <?php return ob_get_clean();

}

add_action('init', 'brace_unregister_wc_user_membership', 20);
function brace_unregister_wc_user_membership(){
    // Get the post-type object that is created by WC Memberships plugin
    $object = get_post_type_object('wc_user_membership');

    // Change some of the arguments for that post type to allow it to be queried on the frontend
    $object->public = true;
    $object->publicly_queryable = true;
    $object->has_archive = true;

    // Set a specific slug for the archive page
    $object->rewrite = array('slug' => 'wc-members');

    // Re-register the post type
    register_post_type('wc_user_membership', $object);
}

add_action('pre_get_posts', 'brace_setup_wc_user_membership_archive', 20);
function brace_setup_wc_user_membership_archive($query){

  if ($query->is_main_query() && !is_admin() && is_post_type_archive('company_profiles')) {

    $query->set('posts_per_page', 12);
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $taxonomy_query = array();
    
    if(!empty($_GET['industry'])) {
      $taxonomy_query['tax_query'][] = array(
        'taxonomy' => 'company_industry',
        'field' => 'slug',
        'terms' => $_GET['industry']
      );
    }
    
    if(!empty($_GET['location'])) {
      $taxonomy_query['tax_query'][] = array(
        'taxonomy' => 'company_location',
        'field' => 'slug',
        'terms' => $_GET['location']
      );
    }

    if (!empty($_GET['industry']) || !empty($_GET['location'])) {
      $query->set('tax_query', $taxonomy_query);
    }
  }

    // If this is the wc_user_membership archive page
    if(is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'wc_user_membership'){
        return;
    }

    // Ensure the post status is the 'active' one that WC Memberships registers
    $query->set('post_status', 'wcm-active');
    $query->set('posts_per_page', '9');
    // $query->set('post_parent', '241');

    // If there is a plan ID passed as a GET parameter, then filter the wc_user_membership items by there post parent
    // The post parent is the membership plan that this membership is assigned to
    if(!empty($_GET['plan'])){
        $query->set('post_parent', absint($_GET['plan']));
    }
}

// make company field required
function sv_require_wc_company_field( $fields ) {
  // var_dump($fields);
  $fields['company']['required'] = true;
  return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'sv_require_wc_company_field' );


// Our hooked in function - $address_fields is passed via the filter!
// function custom_override_default_address_fields( $address_fields ) {
//      $address_fields['company']['label'] = 'Company name <small>(used to create your company profile)</small>';

//      return $address_fields;
// }
// Hook in
// add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

add_filter( 'woocommerce_billing_fields', 'brace_require_wc_company_field');
function brace_require_wc_company_field( $fields ) {
  $fields['billing_company']['required'] = false;
  return $fields;
}


// change 'Proceed to Paypal' text
add_filter( 'gettext', 'ld_custom_paypal_button_text', 20, 3 );
function ld_custom_paypal_button_text( $translated_text, $text, $domain ) {
	switch ( $translated_text ) {
		case 'Proceed to PayPal' :
			$translated_text = __( 'Proceed to payment', 'woocommerce' );
			break;
	}
	return $translated_text;
}

add_action('admin_head', 'hide_admin_notices');
function hide_admin_notices() {
  echo '<style>
   .post-type-company_profiles .updated {
     display: none !important;
   }
  </style>';
}

add_filter( 'gettext', 'translate_woocommerce_cart_message', 999, 3 );
function translate_woocommerce_cart_message( $translated, $text, $domain ) {

// STRING 1
$translated = str_ireplace( 'Sorry, this product cannot be purchased.', 'Sorry, this product cannot be purchased, this may be due to your membership level. Please ensure that you\'re a member of Constructing Excellence. If the event is after 30th April please ensure that you have renewed your membership for the following year.', $translated );

// ETC.
return $translated;
}

// // Inc
// class BraceEvents{
//   public function __construct(){
//     add_action('wp', array($this, 'method'));
//   }
//   public function method(){
//     echo 'pap';
//     exit();
//   }
// }
// // Inc

function my_relationship_query( $args, $field, $post )
{
    // add and define tribe events eventDisplay to 'all' since it's predifined only to future.
    $args['eventDisplay'] = 'custom';

    return $args;
}

// acf/fields/relationship/result - filter for every field
add_filter('acf/fields/relationship/query', 'my_relationship_query', 10, 3);
