<?php
/**
 * MMTMS Login Widget
 *
 * @package momo-membership
 * @author  MoMo Themes
 * @since   v1.0.0
 */
class Mmtms_Login_Widget extends WP_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$wsettings = array(
			'description' => esc_html__( 'Display MMTMS Login Widget in sidebar', 'momo-membership' ),
		);
		parent::__construct( 'mmtms_login_widget', esc_html__( 'MMTMS Login Widget', 'momo-membership' ), $wsettings );
	}
	/**
	 * Widget Frontend
	 *
	 * @param array $args Arguments.
	 * @param array $instance Current Instance.
	 */
	public function widget( $args, $instance ) {
		global $mmtms;
		$loggedin_title  = ! empty( $instance['loggedin_title'] ) ? $instance['loggedin_title'] : esc_html__( 'Welcome <username>', 'momo-membership' );
		$loggedout_title = ! empty( $instance['loggedout_title'] ) ? $instance['loggedout_title'] : esc_html__( 'MMTMS Login', 'momo-membership' );
		$loggedin_title  = apply_filters( 'mmtms_loggedin_widget_title', $loggedin_title );
		$loggedout_title = apply_filters( 'mmtms_loggedout_widget_title', $loggedout_title );

		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? $args['after_title'] : '';
		$widget_id     = isset( $args['widget_id'] ) ? $args['widget_id'] : '';
		$widget_name   = isset( $args['widget_name'] ) ? $args['widget_name'] : '';

		echo $before_widget;
		if ( is_user_logged_in() ) {
			$user           = get_user_by( 'id', get_current_user_id() );
			$loggedin_title = ucwords( str_replace( '&lt;username&gt;', $user->display_name, $loggedin_title ) );
			$gravatar       = get_avatar( $user->user_email, 80 );
			echo $before_title . esc_html( $loggedin_title ) . $after_title;
			echo '<div class="widget_gravatar">';
				$this->mmtms_escape_widget_echo( $gravatar );
			echo '</div>';
			echo '<div class="mmtms_w_links">';
				echo '<a href="' . esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-profile', 'page' ) ) . '">';
					esc_html_e( 'Go to Profile', 'momo-membership' );
				echo '</a>';
			echo '</div>';
		} else {
			$mmtms_email_options = get_option( 'mmtms_email_options' );
			$penabled            = isset( $mmtms_email_options['mmtms_email_reset_password_link'] ) ? $mmtms_email_options['mmtms_email_reset_password_link'] : '';
			echo $before_title . esc_html( $loggedout_title ) . $after_title;
			echo '<form class="mmtms-form widget-login-form">';
				echo '<div class="mmtms-fe-msg"></div>';
				echo '<div class="mlf-loading"></div>';
				echo '<label for="mmtmsw-username" class="mmtms-required">';
					esc_html_e( 'Username', 'momo-membership' );
				echo '</label>';
				echo sprintf( '<input type="text" name="mmtmsw-username" placeholder="%s"/>', esc_html__( 'User Name / Email', 'momo-membership' ) );

				echo '<label for="mmtmsw-password" class="mmtms-required">';
					esc_html_e( 'Password', 'momo-membership' );
				echo '</label>';
				echo sprintf( '<input type="password" name="mmtmsw-password" placeholder="%s"/>', esc_html__( 'Password', 'momo-membership' ) );

				echo '<div class="bottom-info">';
					if ( 'on' === $penabled ) :
					echo '<a href="' . esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-reset-password', 'page' ) ) . '">';
						esc_html_e( 'Forgot Password?', 'momo-membership' );
					echo '</a>';
					endif;
					echo '<a href="' . esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-register', 'page' ) ) . '">';
						esc_html_e( 'Register', 'momo-membership' );
					echo '</a>';
				echo '</div>';
				echo '<div class="btn-line-left">';
					echo '<span class="btn btn-mmtms-widget-login">';
						esc_html_e( 'Login', 'momo-membership' );
					echo '</span>';
				echo '</div>';
			echo '</form>';
		}
		echo $after_widget;
	}

	/**
	 * Widget Backend
	 *
	 * @param array $instance Current Instance.
	 */
	public function form( $instance ) {
		if ( isset( $instance['loggedin_title'] ) ) {
			$loggedin_title = $instance['loggedin_title'];
		} else {
			$loggedin_title = esc_html__( 'Welcome <username>', 'momo-membership' );
		}
		if ( isset( $instance['loggedout_title'] ) ) {
			$loggedout_title = $instance['loggedout_title'];
		} else {
			$loggedout_title = esc_html__( 'MMTMS Login', 'momo-membership' );
		}
		?>
		<p>
		<label for="<?php echo esc_html( $this->get_field_id( 'loggedin_title' ) ); ?>"><?php esc_html_e( 'Logged in Title:', 'momo-membership' ); ?></label> 
		<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'loggedin_title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'loggedin_title' ) ); ?>" type="text" value="<?php echo esc_attr( $loggedin_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'loggedout_title' ) ); ?>"><?php esc_html_e( 'Logged Out Title:', 'momo-membership' ); ?></label> 
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'loggedout_title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'loggedout_title' ) ); ?>" type="text" value="<?php echo esc_attr( $loggedout_title ); ?>" />
		</p>
		<?php
	}

	/**
	 * Update Widget
	 *
	 * @param array $new_instance New Instance.
	 * @param array $old_instance Old Instance.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                    = array();
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['loggedin_title']  = ( ! empty( $new_instance['loggedin_title'] ) ) ? sanitize_text_field( $new_instance['loggedin_title'] ) : '';
		$instance['loggedout_title'] = ( ! empty( $new_instance['loggedout_title'] ) ) ? sanitize_text_field( $new_instance['loggedout_title'] ) : '';
		return $instance;
	}
	/**
	 * Escaping widget
	 *
	 * @param string $content Content.
	 */
	public function mmtms_escape_widget_echo( $content ) {
		$allowed = array(
			'section' => array(
				'id'    => array(),
				'class' => array(),
			),
			'h2'      => array(
				'class' => array(),
			),
			'img'     => array(
				'alt'     => array(),
				'src'     => array(),
				'class'   => array(),
				'loading' => array(),
				'height'  => array(),
				'width'   => array(),
			),
		);
		echo wp_kses( $content, $allowed );
	}
}
