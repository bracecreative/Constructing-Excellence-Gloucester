<?php


class BraceEvents {
  public function __construct() {
    // Add extra fields into the ticket creation screen in the backend
    add_action('tribe_events_tickets_metabox_edit_main', array($this, 'meta_box_extra'), 10, 2);

    // Save extra ticket data in the backend
    add_action('wp_insert_post', array($this, 'update_ticket_extra'), 999);

    // Ensure there aren't more than the allowed amount of member's tickets in the cart
    add_action('wp', array($this, 'member_ticket_check'), 80);
  }

  public function meta_box_extra($post_id, $ticket_id){
    $ticket = get_post($ticket_id);

    $members_only = get_post_meta($ticket_id, '_members_only', true);
    $members_only = $members_only === 'yes' ? 'yes' : 'no';

    ?>
    <div class="input_block">
      <label for="ticket_member" class="ticket_form_label ticket_form_left">Member Required:</label>
      <input type="checkbox" id="ticket_member" name="ticket_member" class="ticket_field ticket_form_right" value="yes" <?php checked($members_only, 'yes'); ?>>
      <p class="description ticket_form_right">If checked, users will only be able to tickets for members that are currently assigned to them.</p>
    </div>
    <?php
  }

  public function update_ticket_extra($post_id){
    $ticket = get_post($post_id);
    if($ticket->post_type !== 'product'){
        return;
    }
    
    // $event = get_post(get_post_meta($ticket->ID, '_tribe_wooticket_for_event', true));
    // if(!$event || $event->post_type !== 'tribe_events'){
    //   return;
    // }

    if(empty($_POST['data'])){
        return;
    }

    $data = wp_parse_args($_POST['data']);

    $ticket_member = !empty($data['ticket_member']) && $data['ticket_member'] === 'yes' ? 'yes' : 'no';
    update_post_meta($post_id, '_members_only', $ticket_member);
  }

  public function member_ticket_check(){
    $cart = WC()->cart;
    
    if(empty($cart)){
      return;
    }
    
    if(!is_cart() && !is_checkout()){
      return;
    }

    $cart_items = $cart->get_cart();
    foreach($cart_items as $cart_item_key => $cart_item){
        // $members = $this->get_available_ticket_members($cart_item['product_id']);
        // $member_count = count($members);

        $product = wc_get_product($cart_item['product_id']);

        if(get_post_meta($product->get_id(), '_members_only', true) !== 'yes'){
            continue;
        }

        $user = get_current_user_id();
        $memberships = wc_memberships_get_user_active_memberships($user);
				$membership = !empty($memberships) ? $memberships[0] : '';

        

        if(empty($memberships)) {
          wc_add_notice(sprintf('You are either not currently a member or you\'re not logged in to a members account, the %s has been removed from your cart.',
          $product->get_name()
          ), 'notice');
          $cart->remove_cart_item($cart_item_key);
        } else if($membership->plan->slug == 'individual' || $membership->plan->slug == 'individual-19' || $membership->plan->slug == 'individual-20-21') {
          // check quantity
          if($cart_item['quantity'] > 1) {
            wc_add_notice(sprintf('You are on the Individual Plan and are limited to 1 Member\'s ticket per event, quantity of %s has been changed to 1',
            $product->get_name()
            ), 'notice');

            $cart->set_quantity($cart_item_key, 1);
          }
        } else if($membership->plan->slug == 'corporate' || $membership->plan->slug == 'corporate-19' || $membership->plan->slug == 'corporate-20-21' || $membership->plan->slug == 'corporate-22-23') {
          // check quantity
          if($cart_item['quantity'] > 3) {
            wc_add_notice(sprintf('You are on the Corporate Plan and are limited to 3 Member\'s ticket per event, quantity of %s has been changed to 3',
            $product->get_name()
            ), 'notice');

            $cart->set_quantity($cart_item_key, 3);
          }
        }

      
    }

  }
}
