<?php
/**
 * Settings Page for Uninstall Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php
$mmtms_plugin_options = get_option( 'mmtms_plugin_options' );
?>
<div class="mmtms_uninstall_form mmtms_admin_tab_page">
	<div class="mmtms-admin-tab-loading"></div>
	<div class="mmtms-admin-content-box">
		<div class="mmtms-admin-content-header">
			<h3><i class="mmtms-icon-trash-empty"></i><?php esc_html_e( 'Uninstall', 'momo-membership' ); ?></h3>
		</div>
	</div>
	<?php $delete_uninstall = isset( $mmtms_plugin_options['mmtms_delete_uninstall'] ) ? $mmtms_plugin_options['mmtms_delete_uninstall'] : ''; ?>
	<?php $checked = ( 'on' === $delete_uninstall ) ? 'checked' : ''; ?>
	<div class="mmtms-admin-yesno">
		<span class="mmtms-admin-toggle-container">
			<label class="switch">
				<input type="checkbox" class="switch-input" name="mmtms_delete_uninstall" <?php echo esc_attr( $checked ); ?>>
				<span class="switch-label" data-on="Yes" data-off="No"></span>
				<span class="switch-handle"></span>
			</label>
		</span>
		<span class="mmtms-toggle-container-label"><?php esc_html_e( 'Delete all plugin settings after plugin uninstall ?', 'momo-membership' ); ?></span>
	</div>
	<div class="mmtms-admin-tab-bottom">
		<a class="mmtms-admin-btn save-uninstall-settings" href="#"><?php esc_html_e( 'Save Settings', 'momo-membership' ); ?></a>
	</div>
</div>
