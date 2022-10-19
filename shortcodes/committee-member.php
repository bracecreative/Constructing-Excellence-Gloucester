<?php 
/*
Element Description: VC Members Box
*/

 
// Element Class 
class vccommittee_member extends WPBakeryShortCode {
     
  // Element Init
  function __construct() {
      add_action( 'init', array( $this, 'vc_committee_member_mapping' ) );
      add_shortcode( 'vc_committee_member', array( $this, 'vc_committee_member_html' ) );
  }
   
// Element Mapping
public function vc_committee_member_mapping() {
         
  // Stop all if VC is not enabled
  if ( !defined( 'WPB_VC_VERSION' ) ) {
          return;
  }
       
  // Map the block with vc_map()
  vc_map( 
      array(
          'name' => __('Committee Member', 'text-domain'),
          'base' => 'vc_committee_member',
          'description' => __('Drinks menu shortcode', 'text-domain'), 
          'category' => __('My Own Custom Elements', 'text-domain'),   
          'icon' => get_template_directory_uri().'/assets/img/vc-icon.png',            
          'params' => array(   
              array(
                  'type' => 'attach_image',
                  'holder' => 'h3',
                  'class' => 'avatar-class',
                  'heading' => __( 'Avatar', 'text-domain' ),
                  'param_name' => "avatar",
                  'value' => __( '', 'text-domain' ),
                  'description' => __( 'Committee member profile picture', 'text-domain' ),
                  'admin_label' => false,
                  'weight' => 0,
              ),
              array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Name", "my-text-domain" ),
                "param_name" => "name",
                "value" => __( "Default param value", "my-text-domain" ),
                "description" => __( "Employee Name.", "my-text-domain" )
              ),
              array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Job Title", "my-text-domain" ),
                "param_name" => "title",
                "value" => __( "Committee Title", "my-text-domain" ),
                "description" => __( "Committee Title.", "my-text-domain" )
              ),
              array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Company Name", "my-text-domain" ),
                "param_name" => "company",
                "value" => __( "Company Name", "my-text-domain" ),
                "description" => __( "Company Name.", "my-text-domain" )
              ),
              array(
                "type" => "textarea",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Committee Description", "my-text-domain" ),
                "param_name" => "description",
                "value" => __( "Committee Description", "my-text-domain" ),
                "description" => __( "Committee Description", "my-text-domain" )
              ),
              array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => __( "Email", "my-text-domain" ),
                "param_name" => "email",
                "value" => __( "Email", "my-text-domain" ),
                "description" => __( "Email.", "my-text-domain" )
              )
          )
      )
  );                                    
}
   
  // Element HTML
  public function vc_committee_member_html( $atts, $content ) {
    // Params extraction
    extract(
        shortcode_atts(
            array(
                'avatar'   => '',
                'name' => '',
                'title' => '',
                'company' => '',
                'description' => '',
                'email' => '',
                'phone' => ''
            ), 
            $atts
        )
    );

    // Fill $html var with data
    $html = '
    <div class="committee-member">
      <img data-src="'. wp_get_attachment_url($avatar) .'" alt="" class="lazy">
      <div class="meta">
        <h3 class="name">
          '. $name .'
        </h3>
        <h4 class="title">
          '. $title .'
        </h4>
        <h4 class="company">
          '. $company .'
        </h4>
        <p class="job-description">
          '. $description .'
        </p>

      </div>
    </div>
    ';         
    
    return $html;
    
  }
   
} // End Element Class

// Element Class Init
new vccommittee_member();
