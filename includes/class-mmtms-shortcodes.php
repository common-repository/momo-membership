<?php
/**
 * MMTMS Shortcodes
 *
 * @package momo-membership
 * @author MoMo Themes
 */
class Mmtms_Shortcodes {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'mmtms_user_register', array( $this, 'display_user_registration_block' ) );
		add_shortcode( 'mmtms_user_login', array( $this, 'display_user_login_block' ) );
		add_shortcode( 'mmtms_user_profile', array( $this, 'display_user_profile' ) );
		add_shortcode( 'mmtms_logout_link', array( $this, 'display_user_logout_link' ) );
		add_shortcode( 'mmtms_reset_password', array( $this, 'display_pasword_reset_form' ) );
		add_shortcode( 'mmtms_subscription', array( $this, 'display_subscription_page' ) );
		add_action( 'wp_footer', array( $this, 'mmtms_create_fe_lightbox' ) );
	}
	/**
	 * Displays User Login Block
	 */
	public function display_user_login_block() {
		global $mmtms;
		wp_enqueue_script( 'jquery-effects-core', false, array( 'jquery' ), $mmtms->version, true );
		if ( is_user_logged_in() ) {
			$content = $mmtms->fhelper->mmtms_refer_to_profile_page( 'login' );
			return $content;
		}
		$referer = '';
		if ( isset( $_GET['referer'] ) ) {
			$referer = sanitize_text_field( wp_unslash( $_GET['referer'] ) );
		}
		$mmtms_email_options = get_option( 'mmtms_email_options' );
		$penabled            = isset( $mmtms_email_options['mmtms_email_reset_password_link'] ) ? $mmtms_email_options['mmtms_email_reset_password_link'] : '';
		ob_start();
		?>
			<div class="mmtms-login-form" data-referer=<?php echo esc_html( $referer ); ?>>
				<div class="mlf-loading"></div>
				<div class="mmtms-login-page">
					<div class="mmtms-login-msg"></div>
					<form method="post" action="" id="mmtms-login-form" class="mmtms-form">
						<label for="mmtms_login_name" class="mmtms-required">
							<?php esc_html_e( 'Username', 'momo-membership' ); ?>
						</label>
						<input type="text" placeholder="<?php esc_html_e( 'User Name / Email', 'momo-membership' ); ?>" name="mmtms_login_name" class="mmtms-req"/>
						<label for="mmtms_login_password" class="mmtms-required">
							<?php esc_html_e( 'Password', 'momo-membership' ); ?>
						</label>
						<input type="password" placeholder="<?php esc_html_e( 'password', 'momo-membership' ); ?>" name="mmtms_login_password" class="mmtms-req"/>
						<div class="bottom-info">
							<?php if ( 'on' === $penabled ) : ?>
							<a href="<?php echo esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-reset-password', 'page' ) ); ?>">
								<?php esc_html_e( 'Forgot Password?', 'momo-membership' ); ?>
							</a>
							<?php endif; ?>
							<a href="<?php echo esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-register', 'page' ) ); ?>">
								<?php esc_html_e( 'Register', 'momo-membership' ); ?>
							</a>
						</div>
						<div class="btn-line-left">
							<span class="btn mmtms-submit-login"><?php esc_html_e( 'login', 'momo-membership' ); ?></span>
						</div>
					</form>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Displays User Registration Block
	 */
	public function display_user_registration_block() {
		global $mmtms;
		if ( ! get_option( 'users_can_register' ) ) {
			return;
		}
		if ( is_user_logged_in() ) {
			$content = $mmtms->fhelper->mmtms_refer_to_subscription_page( 'register' );
			return $content;
		}
		ob_start();
		wp_enqueue_style( 'mmtms-style' );
		wp_enqueue_script( 'mmtms-script' );
		$mmtms_payment_options = get_option( 'mmtms_payment_options' );
		$pp_enable_sandbox     = isset( $mmtms_payment_options['pp_enable_sandbox'] ) ? $mmtms_payment_options['pp_enable_sandbox'] : '';
		if ( 'on' === $pp_enable_sandbox ) {
			$cid = isset( $mmtms_payment_options['pp_sandbox_id'] ) ? $mmtms_payment_options['pp_sandbox_id'] : '';
		} else {
			$cid = isset( $mmtms_payment_options['pp_live_id'] ) ? $mmtms_payment_options['pp_live_id'] : '';
		}
		?>
<?php if ( ! empty( $cid ) ) : ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo esc_html( $cid ); ?>"></script>
<?php endif; ?>
		<div class="mmtms-registration-form-wrapper">
			<div class="mrf-loading"></div>
			<div class="mmtms-subscription-plan">
				<div class="mmtms-subscription-wrap">
					<?php $mmtms_level_options = get_option( 'mmtms_level_options' ); ?>
					<?php $currency = isset( $mmtms_payment_options['currency'] ) ? $mmtms_payment_options['currency'] : '$'; ?>
					<?php if ( is_array( $mmtms_level_options ) && count( $mmtms_level_options ) > 0 ) : ?>
						<?php foreach ( $mmtms_level_options as $mmtms_level_option ) : ?>
							<?php
								$mls  = $mmtms_level_option['level_slug'];
								$mln  = $mmtms_level_option['level_name'];
								$mbt  = $mmtms_level_option['billing_type'];
								$mwpc = $mmtms_level_option['wp_capability'];
								$mlp  = $mmtms_level_option['level_price'];
								$mld  = $mmtms_level_option['description'];
							?>
							<div class="mmtms-subs-level-item">
								<div class="mmtms-subs-level-top">
									<?php echo esc_html( $mln ); ?>
								</div>
								<div class="mmtms-subs-level-bt">
									<?php echo esc_html( $mbt ); ?>
								</div>
								<div class="mmtms-subs-level-lp">
									<?php $msg = ( '' === $mlp ) ? esc_html__( 'Sign Up Now', 'momo-membership' ) : esc_html__( 'Only ', 'momo-membership' ) . $currency . $mlp; ?>
									<?php echo esc_html( $msg ); ?>
								</div>
								<div class="mmtms-subs-level-desc">
									<?php
									$allowed = array(
										'a'      =>
											array(
												'href'  => array(),
												'title' => array(),
											),
										'br'     => array(),
										'em'     => array(),
										'strong' => array(),
										'p'      => array(),
									);
									?>
									<?php echo wp_kses( $mld, $allowed ); ?>
								</div>
								<div class="mmtms-subs-level-btn">
									<a href="#" class="mslb-sun" data-mls="<?php echo esc_attr( $mls ); ?>"><?php esc_html_e( 'Sign Up', 'momo-membership' ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<span class="mmtms-small mmtms-dnone">
							<?php esc_html_e( 'Level not created yet. Please contact site administrator.', 'momo-membership' ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
			<div class="mmtms-registration-form mmtms-form "><!-- .mmtms-no-level-visible -->
				<div class="mrf-payment-type">
				</div>
				<div class="mrf-form-wrapper">
					<div class="mrf-content">
						<div class="mrf-form-field mrf-err-box"></div>

						<label for="mrf_i_fname" class="mmtms-required"><?php esc_html_e( 'First Name', 'momo-membership' ); ?></label>
						<input type="text" name="mrf_i_fname" placeholder="<?php esc_html_e( 'First Name', 'momo-membership' ); ?>">

						<label for="mrf_i_lname" class="mmtms-required"><?php esc_html_e( 'Last Name', 'momo-membership' ); ?></label>
						<input type="text" name="mrf_i_lname" placeholder="<?php esc_html_e( 'Last Name', 'momo-membership' ); ?>">

						<label for="mrf_i_username" class="mmtms-required"><?php esc_html_e( 'Username', 'momo-membership' ); ?></label>
						<input type="text" name="mrf_i_username" placeholder="<?php esc_html_e( 'Userame', 'momo-membership' ); ?>">

						<label for="mrf_i_email" class="mmtms-required"><?php esc_html_e( 'Email', 'momo-membership' ); ?></label>
						<input type="email" name="mrf_i_email" placeholder="<?php esc_html_e( 'Email', 'momo-membership' ); ?>">

						<label for="mrf_i_password" class="mmtms-required"><?php esc_html_e( 'Password', 'momo-membership' ); ?></label>
						<input type="password" name="mrf_i_password" placeholder="<?php esc_html_e( 'Password', 'momo-membership' ); ?>">

						<label for="mrf_i_cpassword" class="mmtms-required"><?php esc_html_e( 'Confirm Password', 'momo-membership' ); ?></label>
						<input type="password" name="mrf_i_cpassword" placeholder="<?php esc_html_e( 'Confirm Password', 'momo-membership' ); ?>">

					</div>
					<div class="mrf-footer">
						<div class="btn-line-left">
							<span class="btn mrf-footer-signup"><?php esc_html_e( 'Register', 'momo-membership' ); ?></span>
						</div>
					</div>
				</div>
			</div>
			<div class="mmtms_payment_form">
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Displays User Profile
	 */
	public function display_user_profile() {
		global $mmtms;
		if ( ! is_user_logged_in() ) {
			$content  = wp_kses_post( $mmtms->fhelper->mmtms_refer_to_login_page( 'profile' ) );
			$content .= '<style>.mmtms-fe-msg{display:block;}</style>';
			return $content;
		}
		$user       = wp_get_current_user();
		$image_url  = get_user_meta( $user->ID, 'mmtms_p_image', true );
		$user_name  = $user->user_firstname . ' ' . $user->user_lastname;
		$registered = $user->user_registered;
		$email      = $user->user_email;
		$phone      = get_user_meta( $user->ID, 'mmtms_p_phone', true );
		$address    = get_user_meta( $user->ID, 'mmtms_p_address', true );
		$city       = get_user_meta( $user->ID, 'mmtms_p_city', true );
		$state      = get_user_meta( $user->ID, 'mmtms_p_state', true );
		$zip        = get_user_meta( $user->ID, 'mmtms_p_zip', true );
		$country    = get_user_meta( $user->ID, 'mmtms_p_country', true );
		$about      = get_user_meta( $user->ID, 'mmtms_p_about', true );
		if ( '' === $country ) {
			$country = esc_html__( '-', 'momo-membership' );
		} else {
			$country = $mmtms->fhelper->get_country_by_key( $country );
		}
		$date_format = get_option( 'date_format' );
		ob_start();
		?>
		<div class="mmtms-fe-user-profile">
			<div class="mrf-loading"></div>
			<div class="mmtms-up-image" style="background: url(<?php echo esc_url( $image_url ); ?>) center center no-repeat;background-size: cover;">
				<i class="mmtms-icon-edit fe-edit-p-image" data-uid="<?php echo esc_attr( $user->ID ); ?>" data-image="<?php echo esc_attr( $image_url ); ?>"></i>
				<div class="mmtms-up-uname">
					<?php echo esc_html( $user_name ); ?>
					<i class="mmtms-icon-edit fe-edit-p-name" data-uid="<?php echo esc_attr( $user->ID ); ?>"></i>
				</div>
			</div>
			<div class="mmtms-clear"></div>
			<div class="mmtms-up-details">
				<div class="mmtms-up-ms">
					<?php
					$msince = gmdate( $date_format, strtotime( $registered ) );
					/* translators: %s: member since */
					printf( esc_html__( 'member since : %s', 'momo-membership' ), esc_html( $msince ) );
					?>
				</div>
				<div class="mmtms-two-column-row">
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-phone"></i><span><?php echo esc_html( $phone ); ?></span>
						</div>
					</div>
				</div>

				<div class="mmtms-two-column-row">
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-address"></i><span><?php echo esc_html( ( '' === $address ) ? '-' : $address ); ?></span>
						</div>
					</div>
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-industrial-building"></i><span><?php echo esc_html( ( '' === $city ) ? '-' : $city ); ?></span>
						</div>
					</div>
				</div>

				<div class="mmtms-two-column-row">
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-address"></i><span><?php echo esc_html( ( '' === $state ) ? '-' : $state ); ?></span>
						</div>
					</div>
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-location"></i><span><?php echo esc_html( ( '' === $zip ) ? '-' : $zip ); ?></span>
						</div>
					</div>
				</div>

				<div class="mmtms-two-column-row">
					<div class="mmtms-two-column">
						<div class="mmtms-tc-content">
							<i class="mmtms-icon-flag"></i><span><?php echo esc_html( $country ); ?></span>
						</div>
					</div>
				</div>

				<div class="mmtms-up-about">
					<div class="mmtms-upa-header">
						<?php esc_html_e( 'about', 'momo-membership' ); ?>
					</div>
					<div class="mmtms-upa-content">
						<?php echo wp_kses( $about, $mmtms->fhelper->mmtms_allowed_html() ); ?>
					</div>
					<div class="mmtms-upa-footer">
						<span class="mmtms_pe_oi" data-uid="<?php echo esc_attr( $user->ID ); ?>">
							<?php esc_html_e( 'Edit Other Information', 'momo-membership' ); ?>
						</span>
					</div>
				</div>

				<div class="mmtms-up-subscription">
					<div class="mmtms-ups-header">
						<?php esc_html_e( 'subscription', 'momo-membership' ); ?>
					</div>
					<div class="mmtms-ups-content">
					<?php
						$post  = get_posts(
							array(
								'numberposts' => 1,
								'post_type'   => 'mmtms-members',
								'post_status' => 'publish',
								'meta_key'    => 'mmtms-members_user-id',
								'meta_value'  => $user->ID,
							)
						);
						$level = '';
					if ( ! empty( $post ) ) {
						$post_id = $post[0]->ID;
						$mpmv    = get_post_custom( $post_id );
						$lev     = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
						$levels  = get_option( 'mmtms_level_options' );
						foreach ( $levels as $level ) {
							if ( $lev === $level['level_slug'] ) {
								$level_ = $level;
							}
						}
					}
					if ( isset( $level_ ) ) {
						$description = str_replace( '<p>', '', $level_['description'] );
						$description = str_replace( '</p>', '', $description );
						?>
						<div>
							<span class="mmtms-up-stitle"><?php esc_html_e( 'Membership Name', 'momo-membership' ); ?></span>
							<span class="mmtms-up-sdetail"><?php echo esc_html( $level_['level_name'] ); ?></span>
						</div>
						<div>
							<span class="mmtms-up-stitle"><?php esc_html_e( 'About', 'momo-membership' ); ?></span>
							<span class="mmtms-up-sdetail"><?php echo wp_kses( $description, $mmtms->fhelper->mmtms_allowed_html() ); ?></span>
						</div>
						<div>
							<span class="mmtms-up-stitle"><?php esc_html_e( 'Payment Type', 'momo-membership' ); ?></span>
							<span class="mmtms-up-sdetail"><?php echo esc_html( $level_['billing_type'] ); ?></span>
						</div>
						<div>    
							<span class="mmtms-up-stitle"><?php esc_html_e( 'Price', 'momo-membership' ); ?></span>
							<span class="mmtms-up-sdetail"><?php echo esc_html( $level_['level_price'] ); ?></span>
						</div>

						<div class="user-subs-delete" data-slug="<?php echo esc_attr( $level_['level_slug'] ); ?>" data-uid="<?php echo esc_attr( $user->ID ); ?>"><?php esc_html_e( 'Remove Subscription', 'momo-membership' ); ?></div>
						<?php
					} else {
						$redirect_to_subs = $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-subscription' );
						?>
						<div class="mmtms-btn user-subs-add" ><a href="<?php echo esc_url( $redirect_to_subs ); ?>"><?php esc_html_e( 'Choose Subscription', 'momo-membership' ); ?></a></div>
						<?php
					}
					?>
				</div>
				<div class="mmtms-up-invoices">
					<div class="mmtms-upi-header">
						<?php esc_html_e( 'invoices', 'momo-membership' ); ?>
					</div>
					<div class="mmtms-upi-content">
						<?php
						$args      = array(
							'posts_per_page' => -1,
							'post_type'      => 'mmtms-invoices',
							'meta_key'       => 'mmtms-invoices_user-id',
							'meta_value'     => $user->ID,
						);
						$the_query = new WP_Query( $args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								echo wp_kses( '<div>Invoice No : <a href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . get_the_title() . '</a></div>', $mmtms->fhelper->mmtms_allowed_html() );
							}
						}
						?>
					</div>
				</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Display Logout Link
	 */
	public function display_user_logout_link() {
		if ( ! is_user_logged_in() ) {
			wp_get_referer();
		}
		ob_start();
		?>
		<?php esc_html_e( 'You are trying to logout of ', 'momo-membership' ) . get_bloginfo( 'name' ); ?>
		<a href="<?php echo esc_url( wp_logout_url() ); ?>"><?php esc_html_e( 'Logout', 'momo-membership' ); ?></a>
		<?php
		return ob_get_clean();
	}
	/**
	 * Creates Lightbox container
	 */
	public function mmtms_create_fe_lightbox() {
		ob_start();
		?>
		<div class="mmtms-fe-lightbox">
			<div class="mmtms-fe-lb-wrapper">
				<div class="mmtms-fe-lb-loader"></div>
				<span class="mmtms-fe-lb-close"><i class="mmtms-icon-cancel-circled"></i></span>
				<div class="mmtms-fe-lb-container">

				</div>
			</div>
		</div>
		<?php
		$content = ob_get_clean();
		$allowed = array(
			'div'  => array(
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'i'    => array(
				'class' => array(),
			),
		);
		echo wp_kses( $content, $allowed );

	}
	/**
	 * Display Password reset form
	 */
	public function display_pasword_reset_form() {
		wp_enqueue_script( 'jquery-effects-core', false, array( 'jquery' ), true, true );
		if ( is_user_logged_in() ) {
			ob_start();
			$user = wp_get_current_user();
			?>
			<div class="mmtms-fe-msg info" style="display: block">
				<i class="msg-icon mmtms-icon-info-circled"></i>
				<p>
					<?php esc_html_e( 'Password Reset form will not show when you are logged in.', 'momo-membership' ); ?>
				</p>
			</div>
			<?php
			return ob_get_clean();
		} else {
			ob_start();
			?>
			<div class="mmtms-pwd-reset-form mmtms-form">
				<div class="mlf-loading"></div>
				<div class="mmtms-login-page">
					<div class="mmtms-fe-msg" style="display:none"></div>
					<form method="post" action="" id="mmtms-pwd-reset-form">
					<label for="mmtms_pr_ue" class="mmtms-required"><?php esc_html_e( 'Username / Email', 'momo-membership' ); ?></label>
						<input type="text" placeholder="<?php esc_html_e( 'username or email', 'momo-membership' ); ?>" name="mmtms_pr_ue" class="mmtms-req"/>
						<div class="btn-line-left">
							<span class="btn mmtms-submit-pwd-reset"><?php esc_html_e( 'send email', 'momo-membership' ); ?></span>
						</div>
					</form>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	/**
	 * Display Subscription Page
	 */
	public function display_subscription_page() {
		global $mmtms;
		if ( ! is_user_logged_in() ) {
			$content = wp_kses_post( $mmtms->fhelper->mmtms_refer_to_login_page( 'subscription-page' ) );
			return $content;
		} else {
			if ( $mmtms->fhelper->check_user_is_subscribed( get_current_user_id() ) ) {
				$redirect_to_profile = $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-profile' );
				ob_start();
				?>
				<div class="mmtms-fe-msg info" style="display:block">
				<?php esc_html_e( 'You\'ve already been subscribed to one of the level. Please check your profile page to change your level', 'momo-membership' ); ?>
				<a href="<?php echo esc_url( $redirect_to_profile ); ?>?referer=subscription"><?php esc_html_e( 'Profile Page', 'momo-membership' ); ?></a>
				</div>
				<?php
				return ob_get_clean();
			} else {
				ob_start();
				$mmtms_payment_options = get_option( 'mmtms_payment_options' );
				$enable_pp             = isset( $mmtms_payment_options['enable_pp'] ) ? $mmtms_payment_options['enable_pp'] : '';
				$pp_title              = isset( $mmtms_payment_options['pp_title'] ) ? $mmtms_payment_options['pp_title'] : '';
				$pp_desc               = isset( $mmtms_payment_options['pp_desc'] ) ? $mmtms_payment_options['pp_desc'] : '';
				$pp_email              = isset( $mmtms_payment_options['pp_email'] ) ? $mmtms_payment_options['pp_email'] : '';
				$pp_enable_sandbox     = isset( $mmtms_payment_options['pp_enable_sandbox'] ) ? $mmtms_payment_options['pp_enable_sandbox'] : '';
				$pp_sandbox_id         = isset( $mmtms_payment_options['pp_sandbox_id'] ) ? $mmtms_payment_options['pp_sandbox_id'] : '';
				$pp_sandbox_secret     = isset( $mmtms_payment_options['pp_sandbox_secret'] ) ? $mmtms_payment_options['pp_sandbox_secret'] : '';
				$mmtms_payment_options = get_option( 'mmtms_payment_options' );
				$pp_enable_sandbox     = isset( $mmtms_payment_options['pp_enable_sandbox'] ) ? $mmtms_payment_options['pp_enable_sandbox'] : '';
				if ( 'on' === $pp_enable_sandbox ) {
					$cid = $mmtms_payment_options['pp_sandbox_id'];
				} else {
					$cid = $mmtms_payment_options['pp_live_id'];
				}
				?>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo esc_html( $cid ); ?>"></script>
				<div class="mmtms-payment-form-wrapper">
					<div class="mrf-loading"></div>
					<div class="mmtms-subscription-plan">
						<div class="mmtms-subscription-wrap">
							<?php $mmtms_level_options = get_option( 'mmtms_level_options' ); ?>
							<?php $currency = isset( $mmtms_payment_options['currency'] ) ? $mmtms_payment_options['currency'] : '$'; ?>
							<?php if ( is_array( $mmtms_level_options ) && count( $mmtms_level_options ) > 0 ) : ?>
								<?php foreach ( $mmtms_level_options as $mmtms_level_option ) : ?>
									<?php
										$mls  = $mmtms_level_option['level_slug'];
										$mln  = $mmtms_level_option['level_name'];
										$mbt  = $mmtms_level_option['billing_type'];
										$mwpc = $mmtms_level_option['wp_capability'];
										$mlp  = $mmtms_level_option['level_price'];
										$mld  = $mmtms_level_option['description'];
									?>
									<div class="mmtms-subs-level-item">
										<div class="mmtms-subs-level-top">
											<?php echo esc_html( $mln ); ?>
										</div>
										<div class="mmtms-subs-level-bt">
											<?php echo esc_html( $mbt ); ?>
										</div>
										<div class="mmtms-subs-level-lp">
											<?php $msg = ( '' === $mlp ) ? esc_html__( 'Sign Up Now', 'momo-membership' ) : esc_html__( 'Only ', 'momo-membership' ) . $currency . $mlp; ?>
											<?php echo esc_html( $msg ); ?>
										</div>
										<div class="mmtms-subs-level-desc">
											<?php
											$allowed = array(
												'a'      =>
													array(
														'href'  => array(),
														'title' => array(),
													),
												'br'     => array(),
												'em'     => array(),
												'strong' => array(),
												'p'      => array(),
											);
											?>
											<?php echo wp_kses( $mld, $allowed ); ?>
										</div>
										<div class="mmtms-subs-level-btn">
											<a href="#" class="mslb-loggedin-subs" data-mls="<?php echo esc_attr( $mls ); ?>" data-uid="<?php echo esc_html( get_current_user_id() ); ?>"><?php esc_html_e( 'Sign Up', 'momo-membership' ); ?></a>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="mmtms_payment_form">
					</div>
				</div>
				<?php
			}
		}
	}
}
new Mmtms_Shortcodes();
