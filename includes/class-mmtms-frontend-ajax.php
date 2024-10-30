<?php
/**
 * MMTMS Frontend Ajax
 *
 * @package momo-membership
 * @author  MoMo Themes
 */
class Mmtms_Frontend_Ajax {
	/**
	 * Constructor
	 */
	public function __construct() {
		$ajax_events = array(
			'mmtms_display_oi_edit_form'                  => 'mmtms_display_oi_edit_form',
			'mmtms_fe_add_new_user'                       => 'mmtms_fe_add_new_user',
			'mmtms_generate_payment_info_on_form'         => 'mmtms_generate_payment_info_on_form',
			'mmtms_fe_display_payment_form'               => 'mmtms_fe_display_payment_form',
			'mmtms_return_paypal_details_to_regsitration' => 'mmtms_return_paypal_details_to_regsitration',
			'mmtms_fe_login_user'                         => 'mmtms_fe_login_user',
			'mmtms_generate_name_change_form'             => 'mmtms_generate_name_change_form',
			'mmtms_change_user_fl_name'                   => 'mmtms_change_user_fl_name',
			'mmtms_generate_email_change_form'            => 'mmtms_generate_email_change_form',
			'mmtms_change_user_email'                     => 'mmtms_change_user_email',
			'mmtms_generate_password_change_form'         => 'mmtms_generate_password_change_form',
			'mmtms_change_user_password'                  => 'mmtms_change_user_password',
			'mmtms_remove_level_by_slug_uid'              => 'mmtms_remove_level_by_slug_uid',
			'mmtms_change_user_oi'                        => 'mmtms_change_user_oi',
			'mmtms_generate_image_change_form'            => 'mmtms_generate_image_change_form',
			'mmtms_change_user_image_url'                 => 'mmtms_change_user_image_url',
			'mmtms_fe_reset_password'                     => 'mmtms_fe_reset_password',
			'mmtms_fewidget_login_user'                   => 'mmtms_fewidget_login_user',
			'mmtms_generate_loggedin_payment_form'        => 'mmtms_generate_loggedin_payment_form',
			'mmtms_fe_assign_new_user_level'              => 'mmtms_fe_assign_new_user_level',
		);
		foreach ( $ajax_events as $ajax_event => $class ) {
			add_action( 'wp_ajax_' . $ajax_event, array( $this, $class ) );
			add_action( 'wp_ajax_nopriv_' . $ajax_event, array( $this, $class ) );
		}
	}
	/**
	 * Display Other Infromation Edit Form
	 */
	public function mmtms_display_oi_edit_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_display_oi_edit_form' !== $_POST['action'] ) {
			return;
		}
		$user_id = isset( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : 0;
		$content = $this->generate_oe_edit_form( $user_id );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate Other Information Edit Form
	 *
	 * @param integer $user_id User ID.
	 */
	public function generate_oe_edit_form( $user_id ) {
		$address = get_user_meta( $user_id, 'mmtms_p_address', true );
		$city    = get_user_meta( $user_id, 'mmtms_p_city', true );
		$state   = get_user_meta( $user_id, 'mmtms_p_state', true );
		$zip     = get_user_meta( $user_id, 'mmtms_p_zip', true );
		$phone   = get_user_meta( $user_id, 'mmtms_p_phone', true );
		$about   = get_user_meta( $user_id, 'mmtms_p_about', true );
		ob_start();
		?>
		<div class="mmtms-fe-lb-header">
			<?php esc_html_e( 'Edit Other Information', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-fe-lb-content">
			<div class="mmtms-fe-lb-msgbox">

			</div>
			<input type="hidden" name="fe_cp_form_user_id" value="<?php echo esc_url( $user_id ); ?>"> 
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_address" class="mmtms-lb-label"><?php esc_html_e( 'Address', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_address" value="<?php echo esc_html( $address ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_city" class="mmtms-lb-label"><?php esc_html_e( 'City', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_city" value="<?php echo esc_html( $city ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_state" class="mmtms-lb-label"><?php esc_html_e( 'State', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_state" value="<?php echo esc_html( $state ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_zip" class="mmtms-lb-label"><?php esc_html_e( 'Zip Code', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_zip" value="<?php echo esc_html( $zip ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_country" class="mmtms-lb-label"><?php esc_html_e( 'Country', 'momo-membership' ); ?></label>
				<select name="fe_cp_form_user_country">
					<?php
					global $mmtms;
					$selected  = get_user_meta( $user_id, 'mmtms_p_country', true );
					$countries = $mmtms->fhelper->mmtms_get_countries_array();
					foreach ( $countries as $key => $value ) {
						?>
						<option value="<?php echo esc_html( $key ); ?>" title="<?php echo esc_html( $value ); ?>" <?php echo esc_html( ( $selected === $key ) ? 'selected="selected"' : '' ); ?>">
							<?php echo esc_html( $value ); ?>
						</option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_phone" class="mmtms-lb-label"><?php esc_html_e( 'Phone', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_phone" value="<?php echo esc_html( $phone ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_about" class="mmtms-lb-label"><?php esc_html_e( 'About', 'momo-membership' ); ?></label>
				<textarea class="mmtms-fe-lb-input" name="fe_cp_form_user_about" rows="4">
					<?php echo esc_html( $about ); ?>
					</textarea>
			</div>
			<div class="mmtms-fe-lb-footer">
				<div class="mmtms-btn btn-change-oe"><?php esc_html_e( 'Save Changes', 'momo-membership' ); ?></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Frontend add new user
	 */
	public function mmtms_fe_add_new_user() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && ( 'mmtms_fe_add_new_user' !== $_POST['action'] ) ) {
			return;
		}
		if ( ! get_option( 'users_can_register' ) ) {
			return;
		}
		$username            = isset( $_POST['mrf_i_username'] ) ? sanitize_text_field( wp_unslash( $_POST['mrf_i_username'] ) ) : '';
		$susername           = sanitize_user( $username, true );
		$fname               = isset( $_POST['mrf_i_fname'] ) ? sanitize_text_field( wp_unslash( $_POST['mrf_i_fname'] ) ) : '';
		$lname               = isset( $_POST['mrf_i_lname'] ) ? sanitize_text_field( wp_unslash( $_POST['mrf_i_lname'] ) ) : '';
		$password            = isset( $_POST['mrf_i_password'] ) ? $_POST['mrf_i_password'] : '';
		$email               = isset( $_POST['mrf_i_email'] ) ? sanitize_email( wp_unslash( $_POST['mrf_i_email'] ) ) : '';
		$mmtms_selected_plan = isset( $_POST['mmtms_selected_plan'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_selected_plan'] ) ) : '';
		if ( $username !== $susername ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Only alphanumeric characters plus these:<em> _, space, ., -, *, and @</em> are allowed in username.', 'momo-membership' ),
					'mid'    => 1,
				)
			);
			exit;
		} else {
			$username = $susername; // sanitized username.
		}
		if ( username_exists( $username ) ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Username already exist.', 'momo-membership' ),
					'mid'    => 1,
				)
			);
			exit;
		}
		if ( email_exists( $email ) ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Email address already registered to another user.', 'momo-membership' ),
					'mid'    => 2,
				)
			);
			exit;
		}
		$user_id = wp_create_user( $username, $password, $email );
		if ( $user_id ) {
			$billing_type        = 'free';
			$mmtms_level_options = get_option( 'mmtms_level_options' );
			if ( is_array( $mmtms_level_options ) && ! empty( $mmtms_level_options ) ) {
				foreach ( $mmtms_level_options as $mmtms_level_option ) {
					if ( $mmtms_level_option['level_slug'] === $mmtms_selected_plan ) {
						$billing_type = $mmtms_level_option['billing_type'];
					}
				}
			}
			echo wp_json_encode(
				array(
					'status'  => 'good',
					'msg'     => esc_html__( 'Username Registered', 'momo-membership' ),
					'uid'     => $user_id,
					'billing' => $billing_type,
					'level'   => $mmtms_selected_plan,
				)
			);
			exit;
		}
	}

	/**
	 * Generate Level Data by level slug
	 */
	public function mmtms_generate_payment_info_on_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_payment_info_on_form' !== $_POST['action'] ) {
			return;
		}
		if ( ! isset( $_POST['level_slug'] ) ) {
			return;
		}
		$level_slug          = sanitize_text_field( wp_unslash( $_POST['level_slug'] ) );
		$mmtms_level_options = get_option( 'mmtms_level_options' );
		$content             = '';
		global $mmtms;
		foreach ( $mmtms_level_options as $mmtms_level_option ) :
			if ( $mmtms_level_option['level_slug'] === $level_slug ) {
				$content = $this->html_level_option( $mmtms_level_option );
				$form    = $mmtms->frontend->mmtms_generate_registration_form();
				echo wp_json_encode(
					array(
						'status'  => 'good',
						'content' => $content,
						'form'    => $form,
					)
				);
				exit;
			}
		endforeach;
	}

	/**
	 * Return HTML content for level payment option
	 *
	 * @param array $mlo Levels.
	 */
	public function html_level_option( $mlo ) {
		ob_start();
		?>
			<input type="hidden" name="mmtms_selected_plan" value="<?php echo esc_html( $mlo['level_slug'] ); ?>">
			<div class="mmtms-mpt-header">
				<?php esc_html_e( 'Payment Details', 'momo-membership' ); ?>
			</div>
			<span class="mmtms-mpt-pn">
				<?php esc_html_e( 'Plan Name', 'momo-membership' ); ?>
			</span>
			<span class="mmtms-mpt-pnv">
				<?php echo esc_html( $mlo['level_name'] ); ?>
			</span>
			<div class="mmtms-clear"></div>
			<span class="mmtms-mpt-pn">
				<?php esc_html_e( 'Price', 'momo-membership' ); ?>
			</span>
			<span class="mmtms-mpt-pnv">
				<?php $mmtms_payment_options = get_option( 'mmtms_payment_options' ); ?>
				<?php $currency = isset( $mmtms_payment_options['currency'] ) ? $mmtms_payment_options['currency'] : '$'; ?>
				<?php $prc = ( '' === $mlo['level_price'] ) ? esc_html__( 'Free', 'momo-membership' ) : esc_html__( 'Only ', 'momo-membership' ) . $currency . $mlo['level_price']; ?>
				<?php echo esc_html( $prc ); ?>
			</span>
			<div class="mmtms-clear"></div>
			<div class="mmtms-mpt-desc">
				<?php echo esc_html( $mlo['description'] ); ?>
			</div>
			<span class="btn mmtms-mpt-btn" href="#"><?php esc_html_e( 'Change Plan', 'momo-membership' ); ?></span>
		<?php
		return ob_get_clean();
	}
	/**
	 * Payment Form
	 */
	public function mmtms_fe_display_payment_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_fe_display_payment_form' !== $_POST['action'] ) {
			return;
		}
		$content = $this->html_display_payment_form( $_POST );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}

	/**
	 * Logged in user payment form
	 */
	public function mmtms_generate_loggedin_payment_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_loggedin_payment_form' !== $_POST['action'] ) {
			return;
		}
		$content = $this->html_display_payment_form( $_POST );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * HTML return of Payment Form
	 *
	 * @param array $post levels.
	 */
	public function html_display_payment_form( $post ) {
		ob_start();
		global $mmtms;
		$level = $mmtms->fhelper->mmtms_get_level_by_slug( $post['level_slug'] );
		?>
		<div class="payment_form_contanier">
			<div class="mrf-payment-type">        
				<input type="hidden" name="mmtms_selected_plan" value="corporate">
				<div class="mmtms-mpt-header">
					<?php esc_html_e( 'Payment Details', 'momo-membership' ); ?>        
				</div>
				<span class="mmtms-mpt-pn">
					<?php esc_html_e( 'Plan Name', 'momo-membership' ); ?>        
				</span>
				<span class="mmtms-mpt-pnv">
					<?php echo esc_html( $level['level_name'] ); ?>       
				</span>
				<div class="mmtms-clear"></div>
				<span class="mmtms-mpt-pn">
					<?php esc_html_e( 'Price', 'momo-membership' ); ?>        
				</span>
				<span class="mmtms-mpt-pnv">
					<?php $mmtms_payment_options = get_option( 'mmtms_payment_options' ); ?>
					<?php $currency = isset( $mmtms_payment_options['currency'] ) ? $mmtms_payment_options['currency'] : '$'; ?>
					<?php $output = ( '' === $level['level_price'] ) ? esc_html__( 'Free', 'momo-membership' ) : esc_html__( 'Only ', 'momo-membership' ) . $currency . $level['level_price']; ?>
					<?php echo esc_html( $output ); ?>
				</span>
				<div class="mmtms-clear"></div>
				<div class="mmtms-mpt-desc">
					<p><strong><?php echo esc_html( $level['description'] ); ?></strong></p>        
				</div>
			</div>
			<div id="paypal-button-container"></div>
			<input type="hidden" name="_payment_form_user_id" value="<?php echo esc_html( $post['user_id'] ); ?>">
			<script>
				paypal.Buttons({
					style: {
						size: 'responsive',
						color: 'blue',
						label: 'pay',
						shape: 'rect',
					},
					createOrder: function(data, actions) {
						return actions.order.create({
							purchase_units: [{
							amount: {
								value: '<?php echo esc_html( $level['level_price'] ); ?>'
							}
							}],
						});
					},
					onApprove: function(data, actions) {
					return actions.order.capture().then(function(details) {
						var $ = jQuery;
						var ajaxdata = {};
						ajaxdata.action = 'mmtms_return_paypal_details_to_regsitration',
						ajaxdata.details = details;
						ajaxdata.level = '<?php echo esc_html( $level['level_slug'] ); ?>';
						ajaxdata.user_id = '<?php echo esc_html( $post['user_id'] ); ?>';
						ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
						var $container = $('body').find('.payment_form_contanier');
						$.ajax({
							/* beforeSend: function () {
								$loading.css('display', 'block');
							}, */
							type: 'POST',
							dataType: 'json',
							url: mmtms_ajax.ajaxurl,
							data: ajaxdata,
							success: function (data) {
								$container.html(data.content);
								top.location.replace(data.permalink)
							}
						})
					});
					}
				}).render('#paypal-button-container');
			</script>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Return payment details to Registration Page
	 */
	public function mmtms_return_paypal_details_to_regsitration() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_return_paypal_details_to_regsitration' !== $_POST['action'] ) {
			return;
		}
		$details   = isset( $_POST['details'] ) ? sanitize_text_field( wp_unslash( $_POST['details'] ) ) : '';
		$user_id   = isset( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : 0;
		$level     = isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : 0;
		$permalink = $this->mmtms_add_to_invoice_cpt( $details, $user_id, $level );
		$content   = $this->html_display_payment_details( $details, $user_id, $level );
		echo wp_json_encode(
			array(
				'status'    => 'good',
				'content'   => $content,
				'permalink' => $permalink,
			)
		);
		exit;
	}

	/**
	 * Add to Invoice CPT
	 *
	 * @param array   $details Invoice Details.
	 * @param integer $user_id User ID.
	 * @param array   $level Selected Level.
	 */
	public function mmtms_add_to_invoice_cpt( $details, $user_id, $level ) {
		global $mmtms;
		$date                  = $details['update_time'];
		$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
		$invoice_no            = isset( $mmtms_invoice_options['invoice_no'] ) ? $mmtms_invoice_options['invoice_no'] : '1000';
		$invoice_no            = $invoice_no++;
		$level_details         = $mmtms->fhelper->mmtms_get_level_by_slug( $level );
		$invoice_data          = array(
			'post_title'   => wp_strip_all_tags( $invoice_no ),
			'post_content' => $level_details['description'],
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_type'    => 'mmtms-invoices',
		);
		$invoice               = wp_insert_post( $invoice_data );
		if ( ! is_wp_error( $invoice ) ) {
			$settings               = get_option( 'mmtms_invoice_options' );
			$settings['invoice_no'] = $invoice_no;
			update_option( 'mmtms_invoice_options', $settings );
			update_post_meta(
				$invoice,
				'mmtms-invoices_user-id',
				$user_id
			);
			update_post_meta(
				$invoice,
				'mmtms-invoices_user-level',
				$level_details['level_slug']
			);
			update_post_meta(
				$invoice,
				'mmtms-invoices_invoice-date',
				$date
			);
			update_post_meta(
				$invoice,
				'mmtms-invoices_invoice-price',
				$level_details['level_price']
			);
			$permalink = get_post_permalink( $invoice );
			return $permalink;
		}
	}
	/**
	 * Display Payment Details
	 *
	 * @param array   $details Payment Details.
	 * @param integer $user_id User ID.
	 * @param array   $level User Level.
	 */
	public function html_display_payment_details( $details, $user_id, $level ) {
		ob_start();
		if ( 'COMPLETED' === $details['status'] ) {
			$user     = get_user_by( 'ID', $user_id );
			$username = $user->display_name;
			global $mmtms;
			$mmtms->fhelper->mmtms_update_user( $user_id, $level, $username );
			$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
			$enable_invoice        = isset( $mmtms_invoice_options['mmtms_enable_invoice'] ) ? $mmtms_invoice_options['mmtms_enable_invoice'] : '';
			if ( 'on' === $enable_invoice ) {
				$this->display_invoice( $user_id, $level, $details );
			} else {
				$this->display_thankyou( $user_id, $level, $details );
			}
		} else {
			return;
		}
		return ob_get_clean();
	}

	/**
	 * Display Thankyou Message
	 *
	 * @param integer $user_id User ID.
	 * @param array   $level User Level.
	 * @param array   $details Payer Details.
	 */
	public function display_thankyou( $user_id, $level, $details ) {
		esc_html_e( 'Display payment return message.', 'momo-membership' );
		echo esc_html( $details['payer']['email_address'] );
	}
	/**
	 * Display Invoice
	 *
	 * @param integer $user_id User ID.
	 * @param array   $level User Level.
	 * @param array   $details Payer Details.
	 */
	public function display_invoice( $user_id, $level, $details ) {
		esc_html_e( 'Display payment return message.', 'momo-membership' );
		echo esc_html( $details['payer']['email_address'] );
		$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
		$business_name         = $mmtms_invoice_options['mmtms_inv_bname'];
		$email                 = $mmtms_invoice_options['mmtms_inv_email'];
		$address               = $mmtms_invoice_options['mmtms_inv_address'];
		$logo                  = $mmtms_invoice_options['mmtms_business_logo'];
		ob_start();
		?>
		<div class="mmtms-invoice-container">
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Login User
	 */
	public function mmtms_fe_login_user() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_fe_login_user' !== $_POST['action'] ) {
			return;
		}
		$info                  = array();
		$info['user_login']    = isset( $_POST['username'] ) ? sanitize_user( wp_unslash( $_POST['username'] ) ) : '';
		$info['user_password'] = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$referer               = isset( $_POST['referer'] ) ? sanitize_text_field( wp_unslash( $_POST['referer'] ) ) : '';
		if ( isset( $_POST['rm'] ) && 'on' === $_POST['rm'] ) {
			$info['remember'] = true;
		}
		$user_signon = wp_signon( $info, false );
		if ( is_wp_error( $user_signon ) ) {
			if ( isset( $user_signon->errors['authentication_failed'][0] ) ) {
				$content = $user_signon->errors['authentication_failed'][0];
			} else {
				$content = esc_html__( 'Wrong username or password.', 'momo-membership' ) . ' <a class="mmtms-login-lost" href="' . get_permalink( get_page_by_path( 'mmtms-reset-password' ) ) . '">' . esc_html__( 'Lost your password?', 'momo-membership' ) . '</a>';
			}
			echo wp_json_encode(
				array(
					'status'  => 'bad',
					'content' => $content,
				)
			);
			exit;
		} else {
			global $mmtms;
			$referer                   = get_permalink( $referer );
			$mmtms_redirection_options = get_option( 'mmtms_redirection_options' );
			$login_redirect            = isset( $mmtms_redirection_options['mmtms_redirect_login'] ) ? $mmtms_redirection_options['mmtms_redirect_login'] : '';
			echo wp_json_encode(
				array(
					'status'         => 'good',
					'referer'        => $referer,
					'content'        => esc_html__( 'Logged in successfully.', 'momo-membership' ),
					'login_redirect' => get_permalink( $login_redirect ),
				)
			);
			exit;
		}
	}
	/**
	 * Widget Login
	 */
	public function mmtms_fewidget_login_user() {
		global $mmtms;
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_fewidget_login_user' !== $_POST['action'] ) {
			return;
		}
		$info                  = array();
		$info['user_login']    = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
		$info['user_password'] = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$user_signon           = wp_signon( $info, false );
		if ( is_wp_error( $user_signon ) ) {
			echo wp_json_encode(
				array(
					'status'  => 'bad',
					'content' => esc_html__( 'Wrong username/email or password.', 'momo-membership' ),
				)
			);
			exit;
		} else {
			$user_id = $user_signon->ID;
			echo wp_json_encode(
				array(
					'status'  => 'good',
					'content' => $mmtms->fhelper->mmtms_generate_widget_info( $user_id ),
				)
			);
			exit;
		}
	}
	/**
	 * Ajax call for name change form
	 */
	public function mmtms_generate_name_change_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_name_change_form' !== $_POST['action'] ) {
			return;
		}
		$user    = wp_get_current_user();
		$content = $this->generate_name_change_form( $user );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate Name Change form
	 *
	 * @param User $user User object.
	 */
	public function generate_name_change_form( $user ) {
		$user_firstname = $user->user_firstname;
		$user_lastname  = $user->user_lastname;
		ob_start();
		?>
		<div class="mmtms-fe-lb-header">
			<?php esc_html_e( 'Change Name', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-fe-lb-content">
			<input type="hidden" name="fe_cn_form_user_id" value="<?php echo esc_html( $user->ID ); ?>"> 
			<div class="mmtms-fe-lb-line">
				<label for="fe_form_first_name" class="mmtms-lb-label mmtms-required"><?php esc_html_e( 'First Name', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cn_form_first_name" value="<?php echo esc_html( $user_firstname ); ?>">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_form_last_name" class="mmtms-lb-label mmtms-required"><?php esc_html_e( 'Last Name', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cn_form_last_name" value="<?php echo esc_html( $user_lastname ); ?>">
			</div>
			<div class="mmtms-fe-lb-footer">
				<div class="mmtms-btn btn-change-name"><?php esc_html_e( 'Save Changes', 'momo-membership' ); ?></div>
			</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Change user firstname and lastname
	 */
	public function mmtms_change_user_fl_name() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_change_user_fl_name' !== $_POST['action'] ) {
			return;
		}
		$user_id    = isset( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : 0;
		$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
		$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
		$user_id    = wp_update_user(
			array(
				'ID'         => $user_id,
				'first_name' => $first_name,
				'last_name'  => $last_name,
			)
		);
		if ( is_wp_error( $user_id ) ) {
			echo wp_json_encode(
				array(
					'status'   => 'bad',
					'msg'      => esc_html__( 'Error updating user profile.', 'momo-membership' ),
					'fullname' => $first_name . ' ' . $last_name,
				)
			);
			exit;
		} else {
			echo wp_json_encode(
				array(
					'status'   => 'good',
					'msg'      => esc_html__( 'User successfully updated', 'momo-membership' ),
					'fullname' => $first_name . ' ' . $last_name,
				)
			);
			exit;
		}
	}
	/**
	 * Ajax call for email change form
	 */
	public function mmtms_generate_email_change_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_email_change_form' !== $_POST['action'] ) {
			return;
		}
		$user    = wp_get_current_user();
		$content = $this->generate_email_change_form( $user );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate Email Change Form
	 *
	 * @param User $user User Object.
	 */
	public function generate_email_change_form( $user ) {
		$user_email = $user->user_email;
		ob_start();
		?>
		<div class="mmtms-fe-lb-header">
			<?php esc_html_e( 'Change Email Address', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-fe-lb-content">
			<div class="mmtms-fe-lb-msgbox">

			</div>
			<input type="hidden" name="fe_ce_form_user_id" value="<?php echo esc_html( $user->ID ); ?>"> 
			<input type="hidden" name="fe_ce_form_user_email" value="<?php echo esc_html( $user_email ); ?>">
			<div class="mmtms-fe-lb-line">
				<label for="fe_ce_form_user_email" class="mmtms-lb-label mmtms-required"><?php esc_html_e( 'Email Address', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_ce_form_user_email_new" value="<?php echo esc_html( $user_email ); ?>">
			</div>
			<div class="mmtms-fe-lb-footer">
				<div class="mmtms-btn btn-change-email"><?php esc_html_e( 'Save Changes', 'momo-membership' ); ?></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Change Email Address
	 */
	public function mmtms_change_user_email() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_change_user_email' !== $_POST['action'] ) {
			return;
		}
		$user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : '';
		$email   = isset( $_POST['new_email'] ) ? sanitize_email( wp_unslash( $_POST['new_email'] ) ) : '';
		if ( email_exists( $email ) ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Email address already registered to another user.', 'momo-membership' ),
					'mid'    => 2,
				)
			);
			exit;
		} else {
			$user_id = wp_update_user(
				array(
					'ID'         => $user_id,
					'user_email' => $email,
				)
			);
			if ( is_wp_error( $user_id ) ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Error updating user email.', 'momo-membership' ),
					)
				);
				exit;
			} else {
				echo wp_json_encode(
					array(
						'status' => 'good',
						'msg'    => esc_html__( 'Successfully updated email.', 'momo-membership' ),
						'email'  => $email,
					)
				);
				exit;
			}
		}
	}
	/**
	 * Ajax call to generate password change form
	 */
	public function mmtms_generate_password_change_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_password_change_form' !== $_POST['action'] ) {
			return;
		}
		$user    = wp_get_current_user();
		$content = $this->generate_password_change_form( $user );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate Password change form
	 *
	 * @param User $user User Object.
	 */
	public function generate_password_change_form( $user ) {
		ob_start();
		?>
		<div class="mmtms-fe-lb-header">
			<?php esc_html_e( 'Change Password', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-fe-lb-content">
			<div class="mmtms-fe-lb-msgbox">

			</div>
			<input type="hidden" name="fe_cp_form_user_id" value="<?php echo esc_html( $user->ID ); ?>"> 
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_pwd" class="mmtms-lb-label mmtms-required"><?php esc_html_e( 'Password', 'momo-membership' ); ?></label>
				<input type="password" class="mmtms-fe-lb-input" name="fe_cp_form_user_pwd" value="">
			</div>
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_pwd2" class="mmtms-lb-label mmtms-required"><?php esc_html_e( 'Confirm Password', 'momo-membership' ); ?></label>
				<input type="password" class="mmtms-fe-lb-input" name="fe_cp_form_user_pwd2" value="">
			</div>
			<div class="mmtms-fe-lb-footer">
				<div class="mmtms-btn btn-change-password"><?php esc_html_e( 'Save Changes', 'momo-membership' ); ?></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Change User Password
	 */
	public function mmtms_change_user_password() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_change_user_password' !== $_POST['action'] ) {
			return;
		}
		$user_id  = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
		$password = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$user_id  = wp_update_user(
			array(
				'ID'        => $user_id,
				'user_pass' => $password,
			)
		);
		if ( is_wp_error( $user_id ) ) {
			echo wp_json_encode(
				array(
					'status' => 'bad',
					'msg'    => esc_html__( 'Error updating user password.', 'momo-membership' ),
				)
			);
			exit;
		} else {
			echo wp_json_encode(
				array(
					'status' => 'good',
					'msg'    => esc_html__( 'Successfully updated password.', 'momo-membership' ),
				)
			);
			exit;
		}
	}
	/**
	 * Remove user level by level_slug and user_id
	 */
	public function mmtms_remove_level_by_slug_uid() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_remove_level_by_slug_uid' !== $_POST['action'] ) {
			return;
		}
		$user_id    = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
		$level_slug = isset( $_POST['level_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['level_slug'] ) ) : '';
		$post       = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
			)
		);
		$level      = '';
		if ( ! empty( $post ) ) {
			$post_id = $post[0]->ID;
			$mpmv    = get_post_custom( $post_id );
			$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
			if ( $level === $level_slug ) {
				$result = delete_post_meta( $post[0]->ID, 'mmtms-members_user-level', $level_slug );
				echo wp_json_encode(
					array(
						'status'  => 'good',
						'msg'     => esc_html__( 'Successfully deleted level.', 'momo-membership' ),
						'content' => $this->generate_empty_level_message(),
					)
				);
				exit;
			}
		}
	}
	/**
	 * Generate empty level message in Profile page
	 */
	public function generate_empty_level_message() {
		ob_start();
		global $mmtms;
		$redirect_to_subs = $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-subscription' );
		?>
			<div class="mmtms-fe-msg info">
				<?php esc_html_e( 'You\'re not subscribed to any of our levels. Please go to our subscription page to subscribe.', 'momo-membership' ); ?>
				<a href="<?php echo esc_url( $redirect_to_subs ); ?>"><?php esc_html_e( 'Subscription Page', 'momo-membership' ); ?></a>
			</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Change User Other Information
	 */
	public function mmtms_change_user_oi() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_change_user_oi' !== $_POST['action'] ) {
			return;
		}
		$user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : '';
		$address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
		$city    = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
		$state   = isset( $_POST['state'] ) ? sanitize_text_field( wp_unslash( $_POST['state'] ) ) : '';
		$zip     = isset( $_POST['zip'] ) ? sanitize_text_field( wp_unslash( $_POST['zip'] ) ) : '';
		$country = isset( $_POST['country'] ) ? sanitize_text_field( wp_unslash( $_POST['country'] ) ) : '';
		$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$about   = isset( $_POST['about'] ) ? sanitize_text_field( wp_unslash( $_POST['about'] ) ) : '';
		update_user_meta( $user_id, 'mmtms_p_address', $address );
		update_user_meta( $user_id, 'mmtms_p_city', $city );
		update_user_meta( $user_id, 'mmtms_p_state', $state );
		update_user_meta( $user_id, 'mmtms_p_zip', $zip );
		update_user_meta( $user_id, 'mmtms_p_country', $country );
		update_user_meta( $user_id, 'mmtms_p_phone', $phone );
		update_user_meta( $user_id, 'mmtms_p_about', $about );
		$content = $this->mmtms_generate_oi_content( $_POST );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate other Information Content
	 *
	 * @param array $post Post Array.
	 */
	public function mmtms_generate_oi_content( $post ) {
		ob_start();
		?>
		<span class="mmtms-profile-line mmtms-profile-address">
			<em class="mmtms_p_title"><?php esc_html_e( 'Address', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $post['address'] ); ?></em> 
		</span>
		<span class="mmtms-profile-line mmtms-profile-city">
			<em class="mmtms_p_title"><?php esc_html_e( 'City', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $post['city'] ); ?></em> 
		</span>
		<span class="mmtms-profile-line mmtms-profile-state">
			<em class="mmtms_p_title"><?php esc_html_e( 'State', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $post['state'] ); ?></em> 
		</span>
		<span class="mmtms-profile-line mmtms-profile-zip">
			<em class="mmtms_p_title"><?php esc_html_e( 'Zip Code', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $post['zip'] ); ?></em> 
		</span>
		<span class="mmtms-profile-line mmtms-profile-country">
			<?php
			global $mmtms;
			$country = $mmtms->fhelper->get_country_by_key( $post['country'] );
			?>
			<em class="mmtms_p_title"><?php esc_html_e( 'Country', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $country ); ?></em> 
		</span>
		<span class="mmtms-profile-line mmtms-profile-phone">
			<em class="mmtms_p_title"><?php esc_html_e( 'Phone', 'momo-membership' ); ?></em> : <em><?php echo esc_html( $post['phone'] ); ?></em> 
		</span>
		<?php
		return ob_get_clean();
	}
	/**
	 * Display User Profile Image change Form
	 */
	public function mmtms_generate_image_change_form() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_generate_image_change_form' !== $_POST['action'] ) {
			return;
		}
		$content = $this->generate_image_change_form( $_POST );
		echo wp_json_encode(
			array(
				'status'  => 'good',
				'content' => $content,
			)
		);
		exit;
	}
	/**
	 * Generate Image Change Form
	 *
	 * @param array $post Post array.
	 */
	public function generate_image_change_form( $post ) {
		ob_start();
		$user_id   = intval( $post['uid'] );
		$image_url = esc_url_raw( $post['image_url'] );
		?>
		<div class="mmtms-fe-lb-header">
			<?php esc_html_e( 'Change Profile Image URL', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-fe-lb-content">
			<div class="mmtms-fe-lb-msgbox">

			</div>
			<input type="hidden" name="fe_cp_form_user_id" value="<?php echo esc_html( $user_id ); ?>"> 
			<div class="mmtms-fe-lb-line">
				<label for="fe_cp_form_user_image_url" class="mmtms-lb-label"><?php esc_html_e( 'Image URL', 'momo-membership' ); ?></label>
				<input type="text" class="mmtms-fe-lb-input" name="fe_cp_form_user_image_url" value="<?php echo esc_url( $image_url ); ?>">
			</div>
			<div class="mmtms-fe-lb-footer">
				<div class="mmtms-btn btn-change-iu"><?php esc_html_e( 'Save Changes', 'momo-membership' ); ?></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Change Image URL
	 */
	public function mmtms_change_user_image_url() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_change_user_image_url' !== $_POST['action'] ) {
			return;
		}
		$user_id   = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
		$image_url = isset( $_POST['image_url'] ) ? esc_url_raw( wp_unslash( $_POST['image_url'] ) ) : '';
		update_user_meta( $user_id, 'mmtms_p_image', $image_url );
		echo wp_json_encode(
			array(
				'status' => 'good',
				'msg'    => esc_html__( 'Successfully changed image URL', 'momo-membership' ),
			)
		);
		exit;
	}
	/**
	 * Reset Password
	 */
	public function mmtms_fe_reset_password() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_fe_reset_password' !== $_POST['action'] ) {
			return;
		}
		$user_login = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : array();
		$errors     = $this->mmtms_retrieve_password( $user_login );
		if ( is_wp_error( $errors ) ) {
			$err = $errors->get_error_codes();
			if ( 'invalidcombo' === $err[0] ) {
				$msg = wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'There is no account with that username.', 'momo-membership' ),
					)
				);
				echo esc_html( $msg );
				exit;
			} elseif ( isset( $err[0] ) && 'invalid_email' === $err[0] ) {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'There is no account with that email address.', 'momo-membership' ),
					)
				);
				exit;
			} else {
				echo wp_json_encode(
					array(
						'status' => 'bad',
						'msg'    => esc_html__( 'Error retrieving user data', 'momo-membership' ),
					)
				);
				exit;
			}
		} else {
			echo wp_json_encode(
				array(
					'status' => 'good',
					'msg'    => esc_html__( 'Email sent. Check your email to change your password', 'momo-membership' ),
				)
			);
			exit;
		}
	}
	/**
	 * WP retrieve_password function
	 *
	 * @param array $user_login User login credentials.
	 */
	public function mmtms_retrieve_password( $user_login ) {
		$errors = new WP_Error();
		if ( empty( $user_login ) || ! is_string( $user_login ) ) {
			$errors->add( 'empty_username', esc_html__( 'ERROR: Enter a username or email address.', 'momo-membership' ) );
		} elseif ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
			if ( empty( $user_data ) ) {
				$errors->add( 'invalid_email', esc_html__( 'ERROR: There is no account with that username or email address.', 'momo-membership' ) );
			}
		} else {
			$login     = trim( $user_login );
			$user_data = get_user_by( 'login', $login );
		}
		if ( $errors->has_errors() ) {
			return $errors;
		}
		if ( ! $user_data ) {
			$errors->add( 'invalidcombo', esc_html__( 'ERROR: There is no account with that username or email address.', 'momo-membership' ) );
			return $errors;
		}
		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		$key        = get_password_reset_key( $user_data );
		if ( is_wp_error( $key ) ) {
			return $key;
		}
		if ( is_multisite() ) {
			$site_name = get_network()->site_name;
		} else {
			/*
				* The blogname option is escaped with esc_html on the way into the database
				* in sanitize_option we want to reverse this for the plain text arena of emails.
				*/
			$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}
		$message = esc_html__( 'Someone has requested a password reset for the following account:', 'momo-membership' ) . "\r\n\r\n";
		/* translators: %s: site name */
		$message .= sprintf( esc_html__( 'Site Name: %s', 'momo-membership' ), $site_name ) . "\r\n\r\n";
		/* translators: %s: user login */
		$message .= sprintf( esc_html__( 'Username: %s', 'momo-membership' ), $user_login ) . "\r\n\r\n";
		$message .= esc_html__( 'If this was a mistake, just ignore this email and nothing will happen.', 'momo-membership' ) . "\r\n\r\n";
		$message .= esc_html__( 'To reset your password, visit the following address:', 'momo-membership' ) . "\r\n\r\n";
		$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . ">\r\n";
		/* translators: Password reset email subject. %s: Site name */
		$title = sprintf( esc_html__( '[%s] Password Reset', 'momo-membership' ), $site_name );
		if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
			wp_die( esc_html__( 'The email could not be sent.', 'momo-membership' ) . "<br />\n" . esc_html__( 'Possible reason: your host may have disabled the mail() function.', 'momo-membership' ) );
		}
		return true;
	}
	/**
	 * Assign Level to new User
	 */
	public function mmtms_fe_assign_new_user_level() {
		check_ajax_referer( 'mmtms_ajax_nonce', 'mmtms_nonce' );
		if ( isset( $_POST['action'] ) && 'mmtms_fe_assign_new_user_level' !== $_POST['action'] ) {
			return;
		}
		global $mmtms;
		$user_id  = isset( $_POST['uid'] ) ? sanitize_text_field( wp_unslash( $_POST['uid'] ) ) : '';
		$level    = isset( $_POST['level'] ) ? sanitize_text_field( wp_unslash( $_POST['level'] ) ) : 'free';
		$userdata = get_userdata( $user_id );
		$username = $userdata->user_login;
		$post_id  = $mmtms->admin_helper->mmtms_update_user( $user_id, $level, $username );
		$content  = '<div class="info mmtms-fe-msg mmtms-form" style="display: block;">';
		$content .= '<i class="msg-icon mmtms-icon-info-circled"></i>';
		$content .= '<p>';
		$content .= esc_html__( 'Member updated successfully.', 'momo-membership' );
		$content .= '</p>';
		$content .= '</div>';
		if ( $post_id ) {
			echo wp_json_encode(
				array(
					'status'  => 'good',
					'content' => $content,
				)
			);
			exit;
		}
	}
}
new Mmtms_Frontend_Ajax();
