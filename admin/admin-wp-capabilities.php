<?php
/**
 * Settings Page for WP Capabilities Settings
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
		<h3><i class="mmtms-icon-wordpress"></i><?php esc_html_e( 'WP Capabilities', 'momo-membership' ); ?></h3>
		<?php if ( current_user_can( 'mmtms_add_new_role' ) ) : ?>
			<div class="mmtms-admin-button">
				<a href="#admin_add_new_role" class="mmtms-add-new-role mmtms-display-role-lb">
					<i class="mmtms-icon-plus"></i><?php esc_html_e( 'Add New Role', 'momo-membership' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php
		if ( count( $users_count ) ) {
			?>
				<div class="mmtms-admin-roles-table">
					<?php $role_table = $mmtms->admin_helper->mmtms_generate_role_table(); ?>
					<?php $mmtms->admin_helper->mmtms_wp_kses_echo_table( $role_table ); ?>
				</div>
			<?php
		}
		?>
	</div>
</div>

<div class="mmtms-admin-lightbox" id="admin_add_new_role">
	<div class="mmtms-admin-lb-wrapper">
		<div class="mmtms-admin-loader"></div>
		<span class="mmtms-admin-lb-close">
			<i class="mmtms-icon-cancel-circled"></i>
		</span>
		<div class="mmtms-light-header">
			<?php esc_html_e( 'Add new Role', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-admin-lb-content">
			<div class="mmtms-lb-msgbox"></div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_new_role_id"><?php esc_html_e( 'Role ID', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_role_id" class="mmtms-lb-input"/>
				<span class="mmtms-lb-note">
					<?php esc_html_e( '* Role ID can contain characters, digits, hyphens or underscore only (No Spaces).', 'momo-membership' ); ?>
				</span>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_new_role_name"><?php esc_html_e( 'Display Name', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_role_name" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-lb-form-line">
				<span class="mmtms-lb-note">
					<strong><?php esc_html_e( '* By default, only "read" capabilities is added to role.', 'momo-membership' ); ?></strong>
				</span>
				<span class="mmtms-lb-note">
					<?php
					/* translators: %s: icon */
					printf( esc_html__( '* You can change capabilities by clicking %s on WP Capabilities page', 'momo-membership' ), '<i class="mmtms-icon-vcard"></i>' );
					?>
				</span>
			</div>
		</div>
		<div class="mmtms-admin-lb-footer">
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-add-new-role"><?php esc_html_e( 'Add Role', 'momo-membership' ); ?></a>
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-edit-role"><?php esc_html_e( 'Edit Role', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
<div class="mmtms-admin-lightbox" id="admin_edit_role_capabilities">
	<div class="mmtms-admin-lb-wrapper">
		<div class="mmtms-admin-loader"></div>
		<span class="mmtms-admin-lb-close">
			<i class="mmtms-icon-cancel-circled"></i>
		</span>
		<div class="mmtms-light-header">
			<?php esc_html_e( 'Edit Capabilities', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-admin-lb-content">
			<div class="mmtms-lb-msgbox"></div>
		</div>
		<div class="mmtms-admin-lb-footer">
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-edit-capabilities"><?php esc_html_e( 'Save Capabilities', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
