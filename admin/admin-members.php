<?php
/**
 * Settings Page for Members Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php
$users_count = count_users();
?>
<div class="mmtms-admin-content-box">
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-users"></i><?php esc_html_e( 'Members', 'momo-membership' ); ?></h3>
	</div>
	<?php if ( current_user_can( 'create_users' ) ) : ?>
		<div class="mmtms-admin-button">
			<a href="#admin_add_new_user" class="mmtms-add-new-user mmtms-display-user-lb">
				<i class="mmtms-icon-plus"></i><?php esc_html_e( 'Add New User', 'momo-membership' ); ?>
			</a>
		</div>
	<?php endif; ?>
	<?php if ( count( $users_count ) ) { ?>
			<div class="mmtms-admin-user-table">
				<?php $user_table = $mmtms->admin_helper->mmtms_generate_user_table(); ?>
				<?php $mmtms->admin_helper->mmtms_wp_kses_echo_table( $user_table ); ?>
			</div>
	<?php } ?>
</div>

<div class="mmtms-admin-lightbox" id="admin_add_new_user">
	<div class="mmtms-admin-lb-wrapper">
		<div class="mmtms-admin-loader"></div>
		<span class="mmtms-admin-lb-close">
			<i class="mmtms-icon-cancel-circled"></i>
		</span>
		<div class="mmtms-light-header">
			<?php esc_html_e( 'Add new user', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-admin-lb-content">
			<div class="mmtms-lb-msgbox"></div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_name"><?php esc_html_e( 'Username', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_user_name" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_email"><?php esc_html_e( 'Email', 'momo-membership' ); ?></label>
				<input type="email" name="mmtms_new_user_email" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_fname"><?php esc_html_e( 'First Name', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_user_fname" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_lname"><?php esc_html_e( 'Last Name', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_user_lname" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-password-hide-edit">
				<div class="mmtms-lb-form-line">
					<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_password"><?php esc_html_e( 'Password', 'momo-membership' ); ?></label>
					<input type="password" name="mmtms_new_user_password" class="mmtms-lb-input"/>
				</div>
				<div class="mmtms-lb-form-line">
					<label class="mmtms-lb-label mmtms-required" for="mmtms_new_user_cpassword"><?php esc_html_e( 'Confirm Password', 'momo-membership' ); ?></label>
					<input type="password" name="mmtms_new_user_cpassword" class="mmtms-lb-input"/>
				</div>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_wp_new_user_role"><?php esc_html_e( 'WP Role', 'momo-membership' ); ?></label>
				<select name="mmtms_wp_new_user_role" class="mmtms-lb-input">
					<?php wp_dropdown_roles(); ?>
				</select>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label mmtms-required" for="mmtms_wp_new_user_level"><?php esc_html_e( 'User Level', 'momo-membership' ); ?></label>
				<select name="mmtms_wp_new_user_level" class="mmtms-lb-input">
					<?php $mmtms->admin_helper->mmtms_dropdown_level(); ?>
				</select>
			</div>
		</div>

		<div class="mmtms-admin-lb-footer">
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-add-new-user"><?php esc_html_e( 'Save User', 'momo-membership' ); ?></a>
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-edit-user" style="display: none"><?php esc_html_e( 'Update User', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
