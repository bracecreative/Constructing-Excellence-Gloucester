<?php

/**
* Validate the extra register fields.
*
* @param object $errors   WP_Error object.
* @param string $username Current username.
* @param string $password Current password.
* @param string $email    Current email.
*
* @return void
*/

add_filter('woocommerce_process_registration_errors', 'validate_registration_fields', 10, 4 );
function validate_registration_fields($errors, $username, $password, $email) {
  
	if ( ( !isset( $_POST['member_type'] ) ) || ( isset( $_POST['member_type'] ) && $_POST['member_type'] === 'company' ) ) {

		if ( isset( $_POST['company_name'] ) && isset( $_POST['company_select'] ) && !empty( $_POST['company_name'] ) && !empty( $_POST['company_select'] ) ) {
			$errors->add( 'company_name_error', __( 'If you\'ve selected your company in the list, please leave the "Company Name" field blank.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_name'] ) && isset( $_POST['company_select'] ) && empty( $_POST['company_name'] ) && empty( $_POST['company_select'] ) ) {
			$errors->add( 'company_name_error', __( 'Please select your company, or type it in if it\'s not in the list.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_address'] ) && empty( $_POST['company_address'] ) ) {
			$errors->add( 'company_address_error', __( 'Address is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_town'] ) && empty( $_POST['company_town'] ) ) {
			$errors->add( 'company_town_error', __( 'Town/City is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_county'] ) && empty( $_POST['company_county'] ) ) {
			$errors->add( 'company_county_error', __( 'County is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_postcode'] ) && empty( $_POST['company_postcode'] ) ) {
			$errors->add( 'company_postcode_error', __( 'Postcode is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_phone'] ) && empty( $_POST['company_phone'] ) ) {
			$errors->add( 'company_phone_error', __( 'Phone Number is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['company_email'] ) && empty( $_POST['company_email'] ) || isset( $_POST['company_email'] ) && !WC_Validation::is_email( $_POST['company_email'] ) ) {
			$errors->add( 'company_email_error', __( 'Please provide a valid company email address.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['website_url'] ) && empty( $_POST['website_url'] ) ) {
			$errors->add( 'website_url_error', __( 'Website URL is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['business_type'] ) && empty( $_POST['business_type'] ) ) {
			$errors->add( 'business_type_error', __( 'Please select a type of business.', 'woocommerce' ) );
		}

	}

	if ( isset( $_POST['member_type'] ) && $_POST['member_type'] === 'student' ) {

		if ( isset( $_POST['college_name'] ) && empty( $_POST['college_name'] ) ) {
			$errors->add( 'college_name_error', __( 'College name is required.', 'woocommerce' ) );
		}

		if ( isset( $_POST['course_name'] ) && empty( $_POST['course_name'] ) ) {
			$errors->add( 'course_name_error', __( 'Course name is required.', 'woocommerce' ) );
		}

		if ( isset( $_POST['completion_year'] ) && empty( $_POST['completion_year'] ) ) {
			$errors->add( 'completion_year_error', __( 'Completion year is required.', 'woocommerce' ) );
		}

		if ( isset( $_POST['student_address'] ) && empty( $_POST['student_address'] ) ) {
			$errors->add( 'student_address_error', __( 'Address is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['student_town'] ) && empty( $_POST['student_town'] ) ) {
			$errors->add( 'student_town_error', __( 'Town/City is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['student_county'] ) && empty( $_POST['student_county'] ) ) {
			$errors->add( 'student_county_error', __( 'County is required.', 'woocommerce' ) );
		}
	
		if ( isset( $_POST['student_postcode'] ) && empty( $_POST['student_postcode'] ) ) {
			$errors->add( 'student_postcode_error', __( 'Postcode is required.', 'woocommerce' ) );
		}

	}


	if ( isset( $_POST['first_name'] ) && empty( $_POST['first_name'] ) ) {
		$errors->add( 'first_name_error', __( 'First name is required.', 'woocommerce' ) );
	}

	if ( isset( $_POST['last_name'] ) && empty( $_POST['last_name'] ) ) {
		$errors->add( 'last_name_error', __( 'Last name is required.', 'woocommerce' ) );
	}
	
	// Email empty wc-form-handler and wc-user-functions
	if ( empty( $email ) || ! WC_Validation::is_email( $email ) ) {
		$errors->add( 'personal_email_error', __( 'Please provide a valid email address.', 'woocommerce' ) );
	}
	
	// Email exists
	if ( email_exists( $email ) ) {
		$errors->add( 'personal_email_error', __( 'An account is already registered with your email address. Please log in.', 'woocommerce' ) );
	}
	
	if ( empty( $username ) ) {
		$errors->add( 'registration-error-missing-username', __( 'Please enter a username.', 'woocommerce' ) );
	}

	if ( empty( $password ) ) {
		$errors->add( 'registration-error-missing-password', __( 'Please enter an account password.', 'woocommerce' ) );
	}

	if ( isset( $_POST['membership'] ) && empty( $_POST['membership'] ) ) {
		$errors->add( 'membership_error', __( 'Please choose a membership level.', 'woocommerce' ) );
	}

	if ( !isset( $_POST['declaration_accept'] ) ) {
		$errors->add( 'declaration_accept_error', __( 'Please tick to confirm you have agreed to the declaration.', 'woocommerce' ) );
	}

	return $errors;
}

/**
* Save the extra register fields.
*
* @param int $customer_id Current customer ID.
*
* @return void
*/

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );
function wooc_save_extra_register_fields( $customer_id ) {


	if ( ( !isset( $_POST['member_type'] ) ) || ( isset( $_POST['member_type'] ) && $_POST['member_type'] === 'company' ) ) {

		update_user_meta( $customer_id, 'member_type', 'company' );
		
		if ( isset( $_POST['company_name'] ) && !empty( $_POST['company_name'] ) ) {
			update_user_meta( $customer_id, 'company', sanitize_text_field( $_POST['company_name'] ) );
		}
	
		if ( isset( $_POST['company_select'] ) && !empty( $_POST['company_select'] ) ) {
			update_user_meta( $customer_id, 'company', intval(sanitize_text_field( $_POST['company_select'] )) );
		}
	
		if ( isset( $_POST['company_address'] ) ) {
			update_user_meta( $customer_id, 'company_address', sanitize_text_field( $_POST['company_address'] ) );
		}
	
		if ( isset( $_POST['company_town'] ) ) {
			update_user_meta( $customer_id, 'company_town', sanitize_text_field( $_POST['company_town'] ) );
		}
	
		if ( isset( $_POST['company_county'] ) ) {
			update_user_meta( $customer_id, 'company_county', sanitize_text_field( $_POST['company_county'] ) );
		}
	
		if ( isset( $_POST['company_postcode'] ) ) {
			update_user_meta( $customer_id, 'company_postcode', sanitize_text_field( $_POST['company_postcode'] ) );
		}
		if ( isset( $_POST['company_phone'] ) ) {
			update_user_meta( $customer_id, 'company_phone', sanitize_text_field( $_POST['company_phone'] ) );
		}
	
		if ( isset( $_POST['company_email'] ) ) {
			update_user_meta( $customer_id, 'company_email', sanitize_text_field( $_POST['company_email'] ) );
		}
	
		if ( isset( $_POST['website_url'] ) ) {
			update_user_meta( $customer_id, 'website_url', sanitize_text_field( $_POST['website_url'] ) );
		}
	
		if ( isset( $_POST['business_type'] ) ) {
			update_user_meta( $customer_id, 'business_type', sanitize_text_field( $_POST['business_type'] ) );
		}

		if ( isset( $_POST['linkedin'] ) ) {
			update_user_meta( $customer_id, 'linkedin', sanitize_text_field( $_POST['linkedin'] ) );
		}

		if ( isset( $_POST['twitter'] ) ) {
			update_user_meta( $customer_id, 'twitter', sanitize_text_field( $_POST['twitter'] ) );
		}

		if ( isset( $_POST['instagram'] ) ) {
			update_user_meta( $customer_id, 'instagram', sanitize_text_field( $_POST['instagram'] ) );
		}

	}

	if ( isset( $_POST['member_type'] ) && $_POST['member_type'] === 'student' ) {

		update_user_meta( $customer_id, 'member_type', 'student' );

		if ( isset( $_POST['college_name'] ) ) {
			update_user_meta( $customer_id, 'college_name', sanitize_text_field( $_POST['college_name'] ) );
		}

		if ( isset( $_POST['course_name'] ) ) {
			update_user_meta( $customer_id, 'course_name', sanitize_text_field( $_POST['course_name'] ) );
		}

		if ( isset( $_POST['completion_year'] ) ) {
			update_user_meta( $customer_id, 'completion_year', sanitize_text_field( $_POST['completion_year'] ) );
		}

		if ( isset( $_POST['student_address'] ) ) {
			update_user_meta( $customer_id, 'billing_address_1', sanitize_text_field( $_POST['student_address'] ) );
		}
	
		if ( isset( $_POST['student_town'] ) ) {
			update_user_meta( $customer_id, 'billing_city', sanitize_text_field( $_POST['student_town'] ) );
		}
	
		if ( isset( $_POST['student_county'] ) ) {
			update_user_meta( $customer_id, 'billing_state', sanitize_text_field( $_POST['student_county'] ) );
		}
	
		if ( isset( $_POST['student_postcode'] ) ) {
			update_user_meta( $customer_id, 'billing_postcode', sanitize_text_field( $_POST['student_postcode'] ) );
		}

	}

	if ( isset( $_POST['first_name'] ) ) {
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
	}

	if ( isset( $_POST['last_name'] ) ) {
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
	}

	if ( isset( $_POST['membership'] ) ) {
		update_user_meta( $customer_id, 'membership', sanitize_text_field( $_POST['membership'] ) );
	}

}

// Add chosen membership to cart, create new company profile if it doesn't already exist, then redirect to cart page
add_filter('woocommerce_registration_redirect', 'brace_register_redirect');
function brace_register_redirect($redirect) {
	$user = wp_get_current_user();
	$user_id = $user->ID;

	$membership_type = get_user_meta($user_id, 'member_type', true);

	// Grab the product ID from the new user's DB & add it to their cart before redirecting them to the basket page
	$product_id = get_user_meta($user_id, 'membership', true);
	WC()->cart->add_to_cart(intval($product_id));

	// Only create a company profile if it's a company
	if ($membership_type === 'company') {
		// Get their company name
		$company = get_user_meta($user_id, 'company', true);

		// Grab all the user's metadata
		$user_meta = get_user_meta($user_id);

		// Create a new company profile if it doesn't already exist.
		if (!get_post_type($company)) {

			$post_id = wp_insert_post(array(
				'post_type' => 'company_profiles',
				'post_title' => $company,
			));

			if ($post_id) {

				$company = $post_id;

				update_user_meta( $user_id, 'company', $post_id );

				if (function_exists('update_field')) {
					update_field('telephone', $user_meta['company_phone'][0], $post_id);
					update_field('email', $user_meta['company_email'][0], $post_id);
					update_field('website', $user_meta['website_url'][0], $post_id);
					update_field('address', $user_meta['company_address'][0] . ', ' . $user_meta['company_town'][0] . ', ' . $user_meta['company_county'][0] . ', ' . $user_meta['company_postcode'][0], $post_id);
					update_field('linked_in', $user_meta['linkedin'][0], $post_id);
					update_field('twitter', $user_meta['twitter'][0], $post_id);
					update_field('instagram', $user_meta['instagram'][0], $post_id);
				}

				// Assign the industry taxonomy tag that was selected in the registration form
				$business_type = get_user_meta($user_id, 'business_type', true);
				wp_set_object_terms($post_id, intval($business_type), 'company_industry');

			}

		}
	}


	return wc_get_page_permalink('cart');
}

add_action( 'woocommerce_order_status_processing', 'brace_update_user_role', 10, 1);
function brace_update_user_role($order_id) {

	// Grab the full order data
	$order = wc_get_order($order_id);

	$has_membership = false;

	// Get all order items
	$items = $order->get_items();

	foreach ($items as $item) {
		$categories = get_the_terms( $item->get_product_id(), 'product_cat' );
		$category_slugs = wp_list_pluck( $categories, 'slug' );

		if ( in_array('membership', $category_slugs) ) {
			$has_membership = true;
			break;
		}
	}

	if ( $has_membership ) {
		$user_id = $order->get_user_id();
		$user = get_userdata( $user_id );

		$user->add_role('ce_member');
	}

}
