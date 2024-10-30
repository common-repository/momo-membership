<?php
/**
 * MMTMS Frontend class
 *
 * @package momo-membership
 * @author MoMo Themes
 * @since v1.0.0
 */
class Mmtms_Frontend {
	/**
	 * Constructor
	 */
	public function __construct() {
		$mmtms_redirection_options = get_option( 'mmtms_redirection_options' );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_mmtms_scripts_styles' ), 10 );
		add_filter( 'the_content', array( $this, 'mmtms_content_filter' ), 1 );
		add_filter( 'get_avatar', array( $this, 'mmtms_gravatar_filter' ), 10, 5 );
		$this->mmtms_frontend_init();
		if ( isset( $mmtms_redirection_options['mmtms_redirect_logout'] ) ) {
			add_action( 'wp_logout', array( $this, 'mmtms_redirect_on_logout' ) );
		}

		add_filter( 'template_include', array( $this, 'mmtms_template_loader' ), 99 );
	}
	/**
	 * Load Template
	 *
	 * @param string $template Template Name.
	 */
	public function mmtms_template_loader( $template = '' ) {
		global $mmtms, $post;
		$file  = '';
		$paths = array(
			0 => get_template_directory() . '/',
			1 => get_template_directory() . '/' . $mmtms->plugin_slug . '/email/templates/response/',
			2 => get_stylesheet_directory() . '/' . $mmtms->plugin_slug . '/templates/response/',
			3 => $mmtms->plugin_path . '/email/templates/response/',
		);

		$mmtms_email_response_page = get_option( 'mmtms_email_response_page' );
		if ( is_page( 'mmtms-email-response' ) ) {
			if ( ! empty( $post ) && ! empty( $mmtms_email_response_page ) && (int) $post->ID === (int) $mmtms_email_response_page ) {
				$file = 'mmtms-email-response.php';
				wp_enqueue_style( 'mmtms_frontend' );
				wp_enqueue_script( 'mmtms_frontend' );
			}
		}
		if ( ! empty( $file ) ) {
			foreach ( $paths as $path ) {
				if ( file_exists( $path . $file ) ) {
					$template = $path . $file;
					break;
				}
			}
			if ( ! $template ) {
				$template = $mmtms->plugin_path . '/email/templates/response/' . $file;
			}
		}
		return $template;
	}
	/**
	 * Redirect on Logout
	 */
	public function mmtms_redirect_on_logout() {
		$mmtms_redirection_options = get_option( 'mmtms_redirection_options' );
		$logout_redirect           = isset( $mmtms_redirection_options['mmtms_redirect_logout'] ) ? $mmtms_redirection_options['mmtms_redirect_logout'] : '';
		wp_safe_redirect( get_permalink( $logout_redirect ) );
		exit();
	}
	/**
	 * Change Gravatar if there's mmtms avatar
	 *
	 * @param string $avatar Avatar URL.
	 * @param string $id_or_email User ID or Email.
	 * @param string $size Image Size.
	 * @param string $default Default Image.
	 * @param string $alt Alternative to Image Text.
	 */
	public function mmtms_gravatar_filter( $avatar, $id_or_email, $size, $default, $alt ) {
		$email = is_object( $id_or_email ) ? $id_or_email->comment_author_email : $id_or_email;
		if ( is_email( $email ) && ! email_exists( $email ) ) {
			return $avatar;
		}
		$custom_avatar = get_the_author_meta( 'mmtms_p_image' );
		if ( $custom_avatar ) {
			$return = '<img src="' . $custom_avatar . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" />';
		} elseif ( $avatar ) {
			$return = $avatar;
		} else {
			$return = '<img src="' . $default . '" width="' . $size . '" height="' . $size . '" alt="' . $alt . '" />';
		}
		return $return;
	}
	/**
	 * Load Scripts and Styles
	 */
	public function load_mmtms_scripts_styles() {
		global $mmtms;
		wp_enqueue_style( 'mmtms-style', $mmtms->mmtms_assets . 'css/mmtms-style.css', array(), $mmtms->version );
		wp_enqueue_style( 'mmtms-icons', $mmtms->mmtms_assets . 'css/mmtms.css', array(), $mmtms->version );
		wp_enqueue_script( 'jquery-effects-shake' );
		wp_enqueue_script( 'mmtms-script', $mmtms->mmtms_assets . 'js/mmtms-script.js', array(), $mmtms->version, true );
		$params = array(
			'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'                => wp_create_nonce( 'mmtms_ajax_nonce' ),
			'requierd_field_msg'        => esc_html__( 'Requierd Fields are missing.', 'momo-membership' ),
			'registration_email_err'    => esc_html__( 'Provided email address is incorrect.', 'momo-membership' ),
			'registration_password_err' => esc_html__( 'Provided password does not match.', 'momo-membership' ),
			'same_email_address'        => esc_html__( 'Provided email address is same as before.', 'momo-membership' ),
			'login_redirecturl'         => home_url(),
		);
		wp_localize_script( 'mmtms-script', 'mmtms_ajax', $params );
	}
	/**
	 * Load Frontend Files
	 */
	public function mmtms_frontend_init() {
		include_once 'class-mmtms-frontend-ajax.php';
		add_filter( 'single_template', array( $this, 'mmtms_invoice_template' ) );
		add_filter( 'archive_template', array( $this, 'mmtms_invoice_archive_template' ) );
	}

	/**
	 * Load Single Invoice Page
	 *
	 * @param template $template Template File.
	 */
	public function mmtms_invoice_template( $template ) {
		if ( 'mmtms-invoices' === get_post_type() ) {
			return dirname( __FILE__ ) . '/single-mmtms-invoices.php';
		}
		return $template;
	}

	/**
	 * Load Archive Invoice Page
	 *
	 * @param template $template Template File.
	 */
	public function mmtms_invoice_archive_template( $template ) {
		if ( 'mmtms-invoices' === get_post_type() ) {
			return dirname( __FILE__ ) . '/archive-mmtms-invoices.php';
		}
		return $template;
	}
	/**
	 * Restrict page displays
	 *
	 * @param string $content Post Content.
	 */
	public function mmtms_content_filter( $content ) {
		global $mmtms;
		global $post, $current_user;
		wp_enqueue_style( 'mmtms-style' );
		$levels              = $mmtms->fhelper->generate_post_levels( $post->ID );
		$mb_levels_arr_show  = get_post_meta( $post->ID, 'mb_levels_arr_show', true );
		$mb_levels_arr_block = get_post_meta( $post->ID, 'mb_levels_arr_block', true );
		global $post;
		if ( 'post' !== $post->post_type ) {
			return $content;
		}
		if ( empty( $levels['show'] ) && empty( $levels['block'] ) ) {
			return $content;
		}
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			if ( 0 === $user_id ) {
				return $mmtms->fhelper->generate_non_logged_in_message( $post );
			} else {
				$level = $this->mmtms_get_level_by_user_id( $user_id );

				$show_arr = explode( ',', $mb_levels_arr_show );
				if ( ! empty( $show_arr ) ) {
					if ( isset( $level['level_slug'] ) && in_array( $level['level_slug'], $show_arr, true ) ) {
						return $content;
					} else {
						$block_arr = explode( ',', $mb_levels_arr_block );
						if ( ! empty( $block_arr ) ) {
							if ( isset( $level['level_slug'] ) && in_array( $level['level_slug'], $block_arr, true ) ) {
								return $mmtms->fhelper->generate_blocked_user_message();
							}
						}
					}
				}
				return $mmtms->fhelper->generate_blocked_user_message();
			}
		} else {
			if ( ! empty( $levels['show'] ) ) {
				return $mmtms->fhelper->generate_non_logged_in_message( $post );
			}
		}
			return $content;
	}

	/**
	 * Get user Level by user id
	 *
	 * @param integer $user_id User ID.
	 */
	public function mmtms_get_level_by_user_id( $user_id ) {
		$post  = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
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
					return $level_;
				}
			}
		}
	}
	/**
	 * Generate Registration form.
	 */
	public function mmtms_generate_registration_form() {
		ob_start();
		?>
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
		<?php
		return ob_get_clean();
	}
}
