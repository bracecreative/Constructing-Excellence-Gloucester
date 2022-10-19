<?php
  /* Custom Element for Website */

// Element Class
class memTable extends WPBakeryShortCode {

  // Element Init
  function __construct() {
    add_action( 'init', array( $this, 'mem_table_mapping' ) );
    add_shortcode( 'mem_table', array( $this, 'mem_table_html' ) );
  }

  // Element Mapping
  public function mem_table_mapping() {

    // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
      return;
    }

    // Map the block with vc_map()
    vc_map(
      array(
        'name' => __('Membership Table', 'text-domain'),
        'base' => 'mem_table',
        'category' => __('Brace Elements', 'text-domain'),
        'icon' => get_template_directory_uri().'/shortcodes/visual-composer/vc-brace-icon.png',
        'params' => array(

        ),
      )
    );
  }


  // Element HTML
  public function mem_table_html( $atts, $content = null ) {

    // Params extraction
    extract(
      shortcode_atts(
        array(
          // 'cta_heading'   => 'Black Tie Award Ceremony',
          // 'cta_subheading' => '12 April 2019, 6:30 - 11:00pm, Cheltenham',
        ),
        $atts
      )
    );

    $url = esc_url(home_url($button_url));

    // Fill $html var with data
    $html = '
    <div class="membership-table">
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>Student <span>from Today - 30 April 2022</span></th>
            <th>Sole Trader<span>from Today - 30 April 2022</span></th>
            <th>Corporate<span>from Today - 30 April 2022</span></th>
          </tr>
        </thead>

        <tbody>
		
		
          <tr class="cost">
            <th>Membership Fee</th>
            <td><strong>CONTACT NOW</strong></td>
            <td><strong>£50</strong></td>
            <td><strong>£1100</strong></td>
          </tr>
		  
          <tr>
            <th>Tickets</th>
            <td>1</td>
            <td>1</td>
            <td>Unlimited</td>
          </tr>
          <tr>
            <th>Member only events</th>
            <td><i class="fas fa-check"></i></td>
            <td>
              <i class="fas fa-check"></i>
              <span>priority booking</span>
            </td>
            <td>
              <i class="fas fa-check"></i>
              <span>priority booking</span>
            </td>
          </tr>
          <tr>
            <th>Full access to member directory</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Opportunity to have full company profile in member directory</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Opportunity to contribute to “Resource Area”</th>
            <td><i class="fas fa-times"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Opportunity to host CEG events/profile own projects</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Eligible to be on Committee</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Tweets about the member</th>
            <td><i class="fas fa-times"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>RTs of member\'s own tweets</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Instagram – will we feature the member’s project?</th>
            <td><i class="fas fa-times"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>LinkedIn Group Access</th>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>Featured in LinkedIn posts</th>
            <td><i class="fas fa-times"></i></td>
            <td><i class="fas fa-check"></i></td>
            <td><i class="fas fa-check"></i></td>
          </tr>
          <tr>
            <th>We advertise your events</th>
            <td><i class="fas fa-times"></i></td>
            <td>Web only (in resource area of website)</td>
            <td>Web + social media + £50 per mailshot</td>
          </tr>
          <tr>
            <th>Stand at conference</th>
           <td><i class="fas fa-times"></i></td>
            <td>
              <i class="fas fa-check"></i>
              <span>single only</span>
            </td>
            <td>
              <i class="fas fa-check"></i>
              <span>larger/key position if required</span>
            </td>
          </tr>
          <tr class="buy-now">
            <th></th>
            <td>
              <a href="'. esc_url(home_url('/student-membership')) .'">
                Contact Now
              </a>
            </td>
            <td>
              <a href="'. esc_url(home_url('/join-now')) .'">
                Buy Now
              </a>
            </td>
            <td>
              <a href="'. esc_url(home_url('/join-now')) .'">
                Buy Now
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    ';

    return $html;
  }

} // End Element Class


// Element Class Init
new memTable();
?>

