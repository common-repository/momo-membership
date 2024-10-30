<?php
/**
 * User Profile extra meta field
 *
 * @package momo-membership
 * @author MoMo Thems
 * @since v1.0.0
 */
class Mmtms_User_Profile_Meta {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'mmtms_extra_user_profile_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'mmtms_extra_user_profile_fields' ) );

		add_action( 'personal_options_update', array( $this, 'mmtms_save_extra_user_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'mmtms_save_extra_user_profile_fields' ) );
	}

	/**
	 * Add Extra Fields to user profile
	 *
	 * @param WP_User $user User Object.
	 */
	public function mmtms_extra_user_profile_fields( $user ) {
		wp_enqueue_media();
		?>
		<h3><?php esc_html_e( 'Membership extra profile information', 'momo-membership' ); ?></h3>

		<table class='form-table'>
		<tr>
			<th><label for='mmtms_p_address'><?php esc_html_e( 'Address', 'momo-membership' ); ?></label></th>
			<td>
				<input type='text' name='mmtms_p_address' id='mmtms_p_address' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_address', $user->ID ) ); ?>' class='regular-text' /><br />
				<span class='description'><?php esc_html_e( 'Please enter your address.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_city'><?php esc_html_e( 'City', 'momo-membership' ); ?></label></th>
			<td>
				<input type='text' name='mmtms_p_city' id='mmtms_p_city' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_city', $user->ID ) ); ?>' class='regular-text' /><br />
				<span class='description'><?php esc_html_e( 'Please enter your city.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_state'><?php esc_html_e( 'State', 'momo-membership' ); ?></label></th>
			<td>
				<input type='text' name='mmtms_p_state' id='mmtms_p_state' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_state', $user->ID ) ); ?>' class='regular-text' /><br />
				<span class='description'><?php esc_html_e( 'Please enter your state.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_zip'><?php esc_html_e( 'Zip Code', 'momo-membership' ); ?></label></th>
			<td>
				<input type='text' name='mmtms_p_zip' id='mmtms_p_zip' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_zip', $user->ID ) ); ?>' class='regular-text' /><br />
				<span class='description'><?php esc_html_e( 'Please enter your zip code.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_country'><?php esc_html_e( 'Country', 'momo-membership' ); ?></label></th>
			<td>
				<select name='mmtms_p_country'>
					<?php
					global $mmtms;
					$selected  = get_the_author_meta( 'mmtms_p_country', $user->ID );
					$countries = $mmtms->admin_helper->mmtms_get_countries_array();
					foreach ( $countries as $key => $value ) {
						?>
						<option value='<?php echo esc_attr( $key ); ?>' title='<?php echo esc_attr( $value ); ?>' <?php echo esc_attr( ( $selected === $key ) ? 'selected="selected"' : '' ); ?>>
							<?php echo esc_html( $value ); ?>
						</option>
						<?php
					}
					?>
				</select>
				<span class='description'><?php esc_html_e( 'Please select your country.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_phone'><?php esc_html_e( 'Phone', 'momo-membership' ); ?></label></th>
			<td>
				<input type='text' name='mmtms_p_phone' id='mmtms_p_phone' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_phone', $user->ID ) ); ?>' class='regular-text' /><br />
				<span class='description'><?php esc_html_e( 'Please enter your phone number.', 'momo-membership' ); ?></span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_image'><?php echo esc_html__( 'Profile Image', 'momo-membership' ); ?></label></th>

			<td class='mmtms-user-profile-img'>
				<div class='mmtms-img-container'>
					<img src='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_image', $user->ID ) ); ?>' style='height:100px;display:block'>
				</div>
				<input type='text' class='regular-text' name='mmtms_p_image' id='mmtms_p_image' value='<?php echo esc_attr( get_the_author_meta( 'mmtms_p_image', $user->ID ) ); ?>' class='regular-text'/><br />
				<input type='button' class='mmtms-additional-user-image button-primary' value='<?php esc_attr_e( 'Upload Image', 'momo-membership' ); ?>' data-title='<?php esc_html_e( 'Select or Upload your profile image', 'momo-membership' ); ?>' data-btext='<?php esc_attr_e( 'Use this image', 'momo-membership' ); ?>'/><br />
				<span class='description'>
					<?php esc_html_e( 'Please upload your profile image.', 'momo-membership' ); ?>
				</span>
			</td>
		</tr>
		<tr>
			<th><label for='mmtms_p_about'><?php esc_html_e( 'About Member', 'momo-membership' ); ?></label></th>
			<td>
				<textarea name='mmtms_p_about' id='mmtms_p_about' class='regular-text' rows='6'>
					<?php echo esc_attr( get_the_author_meta( 'mmtms_p_about', $user->ID ) ); ?>
				</textarea>
				<br />
			</td>
		</tr>
		</table>
		<?php
	}



	/**
	 * Save Extra user Meta
	 *
	 * @param integer $user_id User ID.
	 */
	public function mmtms_save_extra_user_profile_fields( $user_id ) {
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-user_' . $user_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		update_user_meta( $user_id, 'mmtms_p_address', isset( $_POST['mmtms_p_address'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_address'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_city', isset( $_POST['mmtms_p_city'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_city'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_state', isset( $_POST['mmtms_p_state'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_state'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_zip', isset( $_POST['mmtms_p_zip'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_zip'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_country', isset( $_POST['mmtms_p_country'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_country'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_phone', isset( $_POST['mmtms_p_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_phone'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_image', isset( $_POST['mmtms_p_image'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_image'] ) ) : '' );
		update_user_meta( $user_id, 'mmtms_p_about', isset( $_POST['mmtms_p_about'] ) ? sanitize_text_field( wp_unslash( $_POST['mmtms_p_about'] ) ) : '' );
	}
}
new Mmtms_User_Profile_Meta();
