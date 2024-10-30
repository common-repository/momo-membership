<?php
/**
 * Settings Page for Invoice
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php
$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
wp_enqueue_media();
?>
<div class="mmtms-admin-content-box mmtms-admin-invoice-form">
	<div class="mmtms-admin-tab-loading"></div>
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-doc-text-inv"></i><?php esc_html_e( 'Invoice Settings', 'momo-membership' ); ?></h3>
	</div>
	<div class="mmtms-admin-content-content">
		<?php $enable_invoice = isset( $mmtms_invoice_options['mmtms_enable_invoice'] ) ? $mmtms_invoice_options['mmtms_enable_invoice'] : ''; ?>
		<?php $checked = ( 'on' === $enable_invoice ) ? 'checked' : ''; ?>
		<div class="mmtms-admin-yesno">
			<span class="mmtms-admin-toggle-container" afteryes="invoice_settings">
				<label class="switch">
					<input type="checkbox" class="switch-input" name="mmtms_enable_invoice" <?php echo esc_attr( $checked ); ?>>
					<span class="switch-label" data-on="Yes" data-off="No"></span>
					<span class="switch-handle"></span>
				</label>
			</span>
			<span class="mmtms-toggle-container-label"><?php esc_html_e( 'Display Invoice after Membership Purchased?', 'momo-membership' ); ?></span>
			<div class="afteryes" id="invoice_settings">
				<label class="regular"><?php esc_html_e( 'Business Name', 'momo-membership' ); ?></label>
				<input name="mmtms_inv_bname" placeholder="<?php esc_html_e( 'Business Name', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_invoice_options['mmtms_inv_bname'] ) ? $mmtms_invoice_options['mmtms_inv_bname'] : '' ); ?>">

				<label class="regular"><?php esc_html_e( 'Email', 'momo-membership' ); ?></label>
				<input name="mmtms_inv_email" placeholder="<?php esc_html_e( 'email@yourbusiness.com', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_invoice_options['mmtms_inv_email'] ) ? $mmtms_invoice_options['mmtms_inv_email'] : '' ); ?>">

				<label class="regular"><?php esc_html_e( 'Address', 'momo-membership' ); ?></label>
				<input name="mmtms_inv_address" placeholder="<?php esc_html_e( 'Your Business Address', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_invoice_options['mmtms_inv_address'] ) ? $mmtms_invoice_options['mmtms_inv_address'] : '' ); ?>">

				<label class="regular"><?php esc_html_e( 'Phone', 'momo-membership' ); ?></label>
				<input name="mmtms_inv_phone" placeholder="<?php esc_html_e( 'Your Business Phone', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_invoice_options['mmtms_inv_phone'] ) ? $mmtms_invoice_options['mmtms_inv_phone'] : '' ); ?>">

				<label class="regular"><?php esc_html_e( 'Invoice Email Subject', 'momo-membership' ); ?></label>
				<input name="mmtms_inv_email_sub" placeholder="<?php esc_html_e( 'Invoice Email Subject', 'momo-membership' ); ?>" type="text" value="<?php echo esc_html( isset( $mmtms_invoice_options['mmtms_inv_email_sub'] ) ? $mmtms_invoice_options['mmtms_inv_email_sub'] : '' ); ?>">

				<?php $business_logo = isset( $mmtms_invoice_options['mmtms_business_logo'] ) ? $mmtms_invoice_options['mmtms_business_logo'] : ''; ?>
				<label class="regular"><?php esc_html_e( 'Your Business Logo', 'momo-membership' ); ?></label>
				<input class="business_logo_url" type="text" name="mmtms_business_logo" size="60" value="<?php echo esc_url( $business_logo ); ?>" disabled>
				<a href="#" class="invoice_business_logo_upload mmtms-admin-btn"><?php esc_html_e( 'Upload', 'momo-membership' ); ?></a>
				<div class="preview_invoice_logo">
					<img class="business_logo" src="<?php echo esc_url( $business_logo ); ?>" height="100" width="100"/>
				</div>   
			</div>
		</div>
		<div class="mmtms-admin-tab-bottom">
			<a class="mmtms-admin-btn save-invoice-settings" href="#"><?php esc_html_e( 'Save Settings', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
