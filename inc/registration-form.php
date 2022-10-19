<?php

class BraceFormHandler {

	private static $instance = null;	

	private $errors = null;

	public function __construct() {
		// Remove default process registration
		remove_action( 'wp_loaded', array( 'WC_Form_Handler', 'process_registration' ), 20 );

		// Add custom process registration
		add_action( 'wp_loaded', array( $this, 'process_registration' ), 20 );
	}

	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new BraceFormHandler();
		}

		return self::$instance;
	}

	public function process_registration() {

		$nonce_value = isset( $_POST['_wpnonce'] ) ? wp_unslash( $_POST['_wpnonce'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.NoNonceVerification
		$nonce_value = isset( $_POST['woocommerce-register-nonce'] ) ? wp_unslash( $_POST['woocommerce-register-nonce'] ) : $nonce_value; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.NoNonceVerification

		if ( isset( $_POST['register'], $_POST['email'] ) && wp_verify_nonce( $nonce_value, 'woocommerce-register' ) ) {
			$username = 'no' === get_option( 'woocommerce_registration_generate_username' ) && isset( $_POST['username'] ) ? wp_unslash( $_POST['username'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$password = 'no' === get_option( 'woocommerce_registration_generate_password' ) && isset( $_POST['password'] ) ? $_POST['password'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			$email    = wp_unslash( $_POST['email'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			try {
				$validation_error  = new WP_Error();
				$validation_error  = apply_filters( 'woocommerce_process_registration_errors', $validation_error, $username, $password, $email );
				
				if ( !empty( $validation_error->errors ) ) {
					$this->errors = $validation_error->errors;
					throw new Exception();
				}

				$new_customer = wc_create_new_customer( sanitize_email( $email ), wc_clean( $username ), $password );

				if ( is_wp_error( $new_customer ) ) {
					throw new Exception( $new_customer->get_error_message() );
				}

				if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) ) {
					wc_add_notice( __( 'Your account was created successfully and a password has been sent to your email address.', 'woocommerce' ) );
				} else {
					wc_add_notice( __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' ) );
				}

				// Only redirect after a forced login - otherwise output a success notice.
				if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $new_customer ) ) {
					wc_set_customer_auth_cookie( $new_customer );

					if ( ! empty( $_POST['redirect'] ) ) {
						$redirect = wp_sanitize_redirect( wp_unslash( $_POST['redirect'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					} elseif ( wc_get_raw_referer() ) {
						$redirect = wc_get_raw_referer();
					} else {
						$redirect = wc_get_page_permalink( 'myaccount' );
					}

					wp_redirect( wp_validate_redirect( apply_filters( 'woocommerce_registration_redirect', $redirect ), wc_get_page_permalink( 'myaccount' ) ) ); //phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
					exit;
				}
			} catch ( Exception $e ) {
				if ( $e->getMessage() ) {
					wc_add_notice( '<strong>' . __( 'Error:', 'woocommerce' ) . '</strong> ' . $e->getMessage(), 'error' );
				}
			}
		}
	}

	public function get_error($key) {
		if ( !isset( $this->errors[$key] ) ) {
			return;
		}

		return $this->errors[$key];
	}

	public function get_errors() {
		return $this->errors;
	}
}

BraceFormHandler::getInstance();

function brace_separate_registration_form_full() {
	if ( is_admin() ) return;
	// if ( is_user_logged_in() ) return;

	$industries = get_terms(array(
		'taxonomy' => 'company_industry',
		'hide_empty' => false,
	));

	$product_args = array(
		'post_type' => 'product',
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => 'membership'
			),
		),
	);
	$memberships = get_posts($product_args);

	$company_args = array(
		'post_type' => 'company_profiles',
		'posts_per_page' => -1,
		'orderby' => 'title',
		'order' => 'ASC',
	);
	$companies = get_posts($company_args);

	$brace_errors = BraceFormHandler::getInstance()->get_errors();

	ob_start();
  
	// NOTE: The following form has been copied (and extended) from woocommerce\templates\myaccount\form-login.php
  
	do_action( 'woocommerce_before_customer_login_form' );
  
	?>
	   	<form method="post" class="woocommerce-form woocommerce-form-register register custom-registration-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<div class="custom-registration-form__heading">
				<h4>New Member Application Form</h4>
			</div>
			<div class="custom-registration-form__subheading">
				<h5>Please Note</h5>
				<p>The CEGlos committee reserve the right to decline membership (and refund any fees paid) if you are not from a construction related industry.</p>
				<p>Full-time students studying a construction-related course are eligible for free “individual” membership – please email <a href="mailto:hello@ceglos.org.uk">hello@ceglos.org.uk</a> from your school, university or college email address to obtain a discount code.</p>
			</div>

			<h2>Type of Membership</h2>

			<div class="registration-form-section">
				<div>

					<div>
						<label for="membership"><?php esc_html_e( 'Select level of membership', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<div>
							<select name="membership" id="membership" class="<?php echo $brace_errors['membership_error'] ? 'is-invalid' : ''; ?>">
								<option value="">-- Select --</option>
								<?php if (!empty($memberships)): foreach ($memberships as $membership): ?>
								<?php
									$product = wc_get_product($membership->ID);
									$price = $product->is_on_sale() ? $product->get_sale_price() : $product->get_regular_price();
									$price = '£' . $price;
								?>

									<option value="<?php echo $membership->ID; ?>" <?php echo (isset($_POST['membership']) && $_POST['membership'] == $membership->ID) ? 'selected="selected"' : ''; ?> ><?php echo $membership->post_title . ' - ' . $price; ?></option>

								<?php endforeach; endif; ?>
							</select>
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
							</div>
						</div>
						<?php if ($brace_errors['membership_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['membership_error'][0]; ?></small>
						<?php endif; ?>
					</div>

				</div>
				<div></div>
			</div>

			<h2>Member Information</h2>

			<div class="custom-registration-form__subheading">
				<p>This form is for new joiners only and it is important that each organisation only has one login/profile. Please use the “Find your company” list below first to check if your organisation ha n existing membership. If your organisation has a membership but you are having any difficulty logging in or booking tickets please contact us – <a href="mailto:hello@ceglos.org.uk">hello@ceglos.org.uk</a></p>
			</div>

			<div class="registration-form-section">
				<div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="college_name"><?php esc_html_e( 'College/University Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="college_name" id="college_name" class="<?php echo $brace_errors['college_name_error'] ? 'is-invalid' : ''; ?>" autocomplete="college_name" value="<?php echo ( ! empty( $_POST['college_name'] ) ) ? esc_attr( wp_unslash( $_POST['college_name'] ) ) : ''; ?>" />
						<?php if ($brace_errors['college_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['college_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="course_name"><?php esc_html_e( 'Course Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="course_name" id="course_name" class="<?php echo $brace_errors['course_name_error'] ? 'is-invalid' : ''; ?>" autocomplete="course_name" value="<?php echo ( ! empty( $_POST['course_name'] ) ) ? esc_attr( wp_unslash( $_POST['course_name'] ) ) : ''; ?>" />
						<?php if ($brace_errors['course_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['course_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="completion_year"><?php esc_html_e( 'Anticipated Year of Completion', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="number" name="completion_year" id="completion_year" class="<?php echo $brace_errors['completion_year_error'] ? 'is-invalid' : ''; ?>" autocomplete="completion_year" value="<?php echo ( ! empty( $_POST['completion_year'] ) ) ? esc_attr( wp_unslash( $_POST['completion_year'] ) ) : ''; ?>" />
						<?php if ($brace_errors['completion_year_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['completion_year_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_select"><?php esc_html_e( 'Find your company', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<div>
							<select name="company_select" id="company_select" class="<?php echo $brace_errors['company_name_error'] ? 'is-invalid' : ''; ?>">
								<option value="">-- Select --</option>
								<?php if (!empty($companies)): foreach ($companies as $company): ?>

									<option value="<?php echo $company->ID; ?>" <?php echo (!empty($_POST['company_select']) && $_POST['company_select'] == $company->ID) ? 'selected="selected"' : ''; ?>><?php echo $company->post_title; ?></option>

								<?php endforeach; endif; ?>
							</select>
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
							</div>
						</div>
						<?php if ($brace_errors['company_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_name"><?php esc_html_e( 'Company Name', 'woocommerce' ); ?></label>
						<input type="text" name="company_name" id="company_name" class="<?php echo $brace_errors['company_name_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_name" value="<?php echo ( ! empty( $_POST['company_name'] ) ) ? esc_attr( wp_unslash( $_POST['company_name'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_address"><?php esc_html_e( 'Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="company_address" id="company_address" class="<?php echo $brace_errors['company_address_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_address" value="<?php echo ( ! empty( $_POST['company_address'] ) ) ? esc_attr( wp_unslash( $_POST['company_address'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_address_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_address_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_town"><?php esc_html_e( 'Town/City', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="company_town" id="company_town" class="<?php echo $brace_errors['company_town_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_town" value="<?php echo ( ! empty( $_POST['company_town'] ) ) ? esc_attr( wp_unslash( $_POST['company_town'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_town_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_town_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_county"><?php esc_html_e( 'County', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="company_county" id="company_county" class="<?php echo $brace_errors['company_county_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_county" value="<?php echo ( ! empty( $_POST['company_county'] ) ) ? esc_attr( wp_unslash( $_POST['company_county'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_county_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_county_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_postcode"><?php esc_html_e( 'Postcode', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="company_postcode" id="company_postcode" class="<?php echo $brace_errors['company_postcode_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_postcode" value="<?php echo ( ! empty( $_POST['company_postcode'] ) ) ? esc_attr( wp_unslash( $_POST['company_postcode'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_postcode_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_postcode_error'][0]; ?></small>
						<?php endif; ?>
					</div>

				</div>

				<div class='flex-1/2'>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="student_address"><?php esc_html_e( 'Billing Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="student_address" id="student_address" class="<?php echo $brace_errors['student_address_error'] ? 'is-invalid' : ''; ?>" autocomplete="student_address" value="<?php echo ( ! empty( $_POST['student_address'] ) ) ? esc_attr( wp_unslash( $_POST['student_address'] ) ) : ''; ?>" />
						<?php if ($brace_errors['student_address_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['student_address_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="student_town"><?php esc_html_e( 'Billing Town/City', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="student_town" id="student_town" class="<?php echo $brace_errors['student_town_error'] ? 'is-invalid' : ''; ?>" autocomplete="student_town" value="<?php echo ( ! empty( $_POST['student_town'] ) ) ? esc_attr( wp_unslash( $_POST['student_town'] ) ) : ''; ?>" />
						<?php if ($brace_errors['student_town_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['student_town_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="student_county"><?php esc_html_e( 'Billing County', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="student_county" id="student_county" class="<?php echo $brace_errors['student_county_error'] ? 'is-invalid' : ''; ?>" autocomplete="student_county" value="<?php echo ( ! empty( $_POST['student_county'] ) ) ? esc_attr( wp_unslash( $_POST['student_county'] ) ) : ''; ?>" />
						<?php if ($brace_errors['student_county_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['student_county_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-student" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] === '226' ) ? '' : 'display: none;'; ?>">
						<label for="student_postcode"><?php esc_html_e( 'Billing Postcode', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="student_postcode" id="student_postcode" class="<?php echo $brace_errors['student_postcode_error'] ? 'is-invalid' : ''; ?>" autocomplete="student_postcode" value="<?php echo ( ! empty( $_POST['student_postcode'] ) ) ? esc_attr( wp_unslash( $_POST['student_postcode'] ) ) : ''; ?>" />
						<?php if ($brace_errors['student_postcode_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['student_postcode_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_phone"><?php esc_html_e( 'Phone Number', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="company_phone" id="company_phone" class="<?php echo $brace_errors['company_phone_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_phone" value="<?php echo ( ! empty( $_POST['company_phone'] ) ) ? esc_attr( wp_unslash( $_POST['company_phone'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_phone_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_phone_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="company_email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<small class="label-info">(for display on our Member Directory. We recommend you use a central email address such as "hello@")</small>
						<input type="text" name="company_email" id="company_email" class="<?php echo $brace_errors['company_email_error'] ? 'is-invalid' : ''; ?>" autocomplete="company_email" value="<?php echo ( ! empty( $_POST['company_email'] ) ) ? esc_attr( wp_unslash( $_POST['company_email'] ) ) : ''; ?>" />
						<?php if ($brace_errors['company_email_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['company_email_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="website_url"><?php esc_html_e( 'Website URL', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="website_url" id="website_url" class="<?php echo $brace_errors['website_url_error'] ? 'is-invalid' : ''; ?>" autocomplete="website_url" value="<?php echo ( ! empty( $_POST['website_url'] ) ) ? esc_attr( wp_unslash( $_POST['website_url'] ) ) : ''; ?>" />
						<?php if ($brace_errors['website_url_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['website_url_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="business_type"><?php esc_html_e( 'Type of Business', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<div>
							<select name="business_type" id="business_type" class="<?php echo $brace_errors['business_type_error'] ? 'is-invalid' : ''; ?>">
								<option value="">-- Select --</option>
								<?php if (!empty($industries)): foreach ($industries as $industry): $industry_children = get_term_children($industry->term_id, $industry->taxonomy); ?>

									<?php if (!empty($industry_children)): ?>

										<option disabled value="<?php echo $industry->term_id; ?>" <?php echo (!empty($_POST['business_type']) && $_POST['business_type'] == $industry->term_id) ? 'selected="selected"' : ''; ?>>-- <?php echo $industry->name; ?> --</option>

										<?php
											$sub_industries = get_terms(array(
												'taxonomy' => $industry->taxonomy,
												'include' => $industry_children,
												'hide_empty' => false
											));

											foreach ($sub_industries as $sub_industry):
										?>
											<option value="<?php echo $sub_industry->term_id; ?>" <?php echo (!empty($_POST['business_type']) && $_POST['business_type'] == $sub_industry->term_id) ? 'selected="selected"' : ''; ?>>-- <?php echo $sub_industry->name; ?></option>
										<?php endforeach; ?>

									<?php elseif ($industry->parent === 0): ?>
										<option value="<?php echo $industry->term_id; ?>" <?php echo (!empty($_POST['business_type']) && $_POST['business_type'] == $industry->term_id) ? 'selected="selected"' : ''; ?>><?php echo $industry->name; ?></option>
									<?php endif; ?>

								<?php endforeach; endif; ?>
							</select>
							<div>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
							</div>
						</div>
						<?php if ($brace_errors['business_type_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['business_type_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="linkedin"><?php esc_html_e( 'LinkedIn', 'woocommerce' ); ?></label>
						<input type="text" name="linkedin" id="linkedin" class="<?php echo $brace_errors['linkedin_error'] ? 'is-invalid' : ''; ?>" autocomplete="linkedin" value="<?php echo ( ! empty( $_POST['linkedin'] ) ) ? esc_attr( wp_unslash( $_POST['linkedin'] ) ) : ''; ?>" />
						<?php if ($brace_errors['linkedin_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['linkedin_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="twitter"><?php esc_html_e( 'Twitter', 'woocommerce' ); ?></label>
						<input type="text" name="twitter" id="twitter" class="<?php echo $brace_errors['twitter_error'] ? 'is-invalid' : ''; ?>" autocomplete="twitter" value="<?php echo ( ! empty( $_POST['twitter'] ) ) ? esc_attr( wp_unslash( $_POST['twitter'] ) ) : ''; ?>" />
						<?php if ($brace_errors['twitter_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['twitter_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div class="form-field-company" style="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? '' : 'display: none;'; ?>">
						<label for="instagram"><?php esc_html_e( 'Instagram', 'woocommerce' ); ?></label>
						<input type="text" name="instagram" id="instagram" class="<?php echo $brace_errors['instagram_error'] ? 'is-invalid' : ''; ?>" autocomplete="instagram" value="<?php echo ( ! empty( $_POST['instagram'] ) ) ? esc_attr( wp_unslash( $_POST['instagram'] ) ) : ''; ?>" />
						<?php if ($brace_errors['instagram_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['instagram_error'][0]; ?></small>
						<?php endif; ?>
					</div>

				</div>
			</div>

			<h2>Contact Details</h2>

			<div class="custom-registration-form__subheading">
				<p>We strongly recommend you choose a username that relates to the organisation, not the individual, eg “CEGlos” rather than “Bob Smith”. Each organisation should only need one login which is then used by all individuals in that organisation. The First Name/Last Name should be for the key contact. The email address is the one that will receive tickets and also used for password reset so needs to be centrally accessible. If multiple people within your organisation wish to receive our emails they can sign up here.</p>
			</div>

			<div class="registration-form-section">
				<div>

					<div>
						<label for="first_name"><?php esc_html_e( 'First Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="first_name" id="first_name" class="<?php echo $brace_errors['first_name_error'] ? 'is-invalid' : ''; ?>" autocomplete="first_name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" />
						<?php if ($brace_errors['first_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['first_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div>
						<label for="last_name"><?php esc_html_e( 'Last Name', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="last_name" id="last_name" class="<?php echo $brace_errors['last_name_error'] ? 'is-invalid' : ''; ?>" autocomplete="last_name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" />
						<?php if ($brace_errors['last_name_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['last_name_error'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div>
						<label for="email"><?php esc_html_e( 'Email Address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="email" id="email" class="<?php echo $brace_errors['personal_email_error'] ? 'is-invalid' : ''; ?>" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
						<?php if ($brace_errors['personal_email_error']): ?>
							<small class="is-invalid"><?php echo $brace_errors['personal_email_error'][0]; ?></small>
						<?php endif; ?>
					</div>

				</div>
				<div>

					<div>
						<label for="reg_username"><?php esc_html_e( 'Username (for logging in)', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" name="username" id="reg_username" class="<?php echo $brace_errors['registration-error-missing-username'] ? 'is-invalid' : ''; ?>" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						<?php if ($brace_errors['registration-error-missing-username']): ?>
							<small class="is-invalid"><?php echo $brace_errors['registration-error-missing-username'][0]; ?></small>
						<?php endif; ?>
					</div>

					<div>
						<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" name="password" id="reg_password" class="<?php echo $brace_errors['registration-error-missing-password'] ? 'is-invalid' : ''; ?>" autocomplete="new-password" />
						<?php if ($brace_errors['registration-error-missing-password']): ?>
							<small class="is-invalid"><?php echo $brace_errors['registration-error-missing-password'][0]; ?></small>
						<?php endif; ?>
					</div>

				</div>
			</div>

			<div class="registration-form-bottom">
				<h3>Declarations</h3>
				<p>By completing my details above I give my consent for Constructing Excellence Gloucestershire to contact me about my membership.</p>
				<div class="registration-form-bottom__declaration">
					<input type="checkbox" id="accept" name="declaration_accept" value="accept" <?php echo isset( $_POST['declaration_accept'] ) ? 'checked="checked"' : ''; ?>>
					<label for="accept"><?php esc_html_e( 'I have read, understood and agree to the above declaration', 'woocommerce' ); ?></label>
				</div>
				<?php if ($brace_errors['declaration_accept_error']): ?>
					<small class="is-invalid"><?php echo $brace_errors['declaration_accept_error'][0]; ?></small>
				<?php endif; ?>
			</div>

			<div class="registration-form-bottom">
				<h3>Privacy Policy</h3>
				<p>Please <a href="<?php echo home_url('/privacy-policy'); ?>" target="_blank" rel="noopener noreferrer">click here to view our privacy policy</a></p>
			</div>

			<input type="hidden" name="member_type" id="member-type" value="<?php echo ( !empty( $_POST['membership'] ) && $_POST['membership'] !== '226' ) || empty( $_POST['membership'] ) ? 'company' : 'student'; ?>">

			<?php do_action( 'woocommerce_register_form' ); ?>

			<div class="registration-form-submit">
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
				<p>Please click the apply button only once.</p>
			</div>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>
  
	<?php
	  
	echo ob_get_clean();
}