<?php
/**
 * Settings Page for Level Settings
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>

<?php
	$levels = get_option( 'mmtms_level_options' );
	global $mmtms;
?>
<div class="mmtms-admin-content-box">
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-universal-access"></i><?php esc_html_e( 'Members Level', 'momo-membership' ); ?></h3>
		<div class="mmtms-admin-button">
			<a href="#admin_add_new_level" class="mmtms-add-new-level mmtms-display-popup">
				<i class="mmtms-icon-plus"></i><?php esc_html_e( 'Add New Level', 'momo-membership' ); ?>
			</a>
		</div>
		<?php
		if ( $levels && count( $levels ) ) {
			$allowed = array(
				'table' =>
					array(
						'id'    => array(),
						'class' => array(),
					),
				'thead' => array(),
				'tfoot' => array(),
				'tbody' => array(),
				'tr'    => array(),
				'th'    => array(
					'width' => array(),
				),
				'td'    => array(
					'width' => array(),
				),
			);
			?>
				<div class="mmtms-admin-levels-table">
					<?php $table = $mmtms->admin_helper->mmtms_generate_level_table(); ?>
					<?php $mmtms->admin_helper->mmtms_wp_kses_echo_table( $table ); ?>
				</div>
			<?php
		}
		?>
	</div>
</div>
<div class="mmtms-admin-lightbox" id="admin_add_new_level">
	<div class="mmtms-admin-lb-wrapper">
		<div class="mmtms-admin-loader"></div>
		<span class="mmtms-admin-lb-close">
			<i class="mmtms-icon-cancel-circled"></i>
		</span>
		<div class="mmtms-light-header">
			<?php esc_html_e( 'Add new Level', 'momo-membership' ); ?>
		</div>
		<div class="mmtms-admin-lb-content">
			<div class="mmtms-lb-msgbox"></div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_new_level_name"><?php esc_html_e( 'Level Name', 'momo-membership' ); ?></label>
				<input type="text" name="mmtms_new_level_name" class="mmtms-lb-input"/>
				<span class="mmtms-lb-note">
					<?php esc_html_e( '* Level Name should be unique and cannot be repeated.', 'momo-membership' ); ?>
				</span>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_billing_type"><?php esc_html_e( 'Billing Option', 'momo-membership' ); ?></label>
				<select name="mmtms_billing_type" class="mmtms-lb-input">
					<option value='payment'><?php esc_html_e( 'Payment', 'momo-membership' ); ?></option>
					<option value='free'><?php esc_html_e( 'Free', 'momo-membership' ); ?></option>
					<?php do_action( 'mmtms_add_billing_type_option' ); ?>
				</select>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_wp_level_role"><?php esc_html_e( 'Default WP Capabilities', 'momo-membership' ); ?></label>
				<select name="mmtms_wp_level_role" class="mmtms-lb-input">
					<?php wp_dropdown_roles(); ?>
				</select>
			</div>
			<div class="mmtms-lb-form-line mmtms_payment_box">
				<label class="mmtms-lb-label" for="mmtms_new_level_price"><?php esc_html_e( 'Level Price', 'momo-membership' ); ?></label>
				<input type="number" name="mmtms_new_level_price" class="mmtms-lb-input"/>
			</div>
			<div class="mmtms-lb-form-line">
				<label class="mmtms-lb-label" for="mmtms_new_level_desc"><?php esc_html_e( 'Level Description', 'momo-membership' ); ?></label>
				<?php wp_editor( '', 'mmtms_new_level_desc', array( 'textarea_rows' => 5 ) ); ?>
			</div>
		</div>
		<div class="mmtms-admin-lb-footer">
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-add-new-level"><?php esc_html_e( 'Save Level', 'momo-membership' ); ?></a>
			<a href="#" class="mmtms-lb-fbutton mmtms-ajax-edit-level" style="display: none"><?php esc_html_e( 'Save Level', 'momo-membership' ); ?></a>
		</div>
	</div>
</div>
