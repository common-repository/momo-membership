<?php
/**
 * MMTMS Email Template for Invoice
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<div class="mmtms-invoice-container" style="background: #FFF;
											margin-right: 20px;
											margin-left: 20px;
											padding: 20px; text-align: center">
	<div class="mmtms-invoice-header" style="text-align:left;">
		<div class="invoice-header-logo" style="width:50%;">
			<img src="<?php echo esc_url( $business_logo ); ?>" width="100px" height="100px">
		</div>
		<div class="invoice-header-info" style="width:50%;">
			<div class="inv-header-div business-name" style="display: block;
																text-transform: uppercase;
																font-weight: bold;"><?php echo esc_html( $business_name ); ?></div>
			<div class="inv-header-div" style="display: block;"><?php echo esc_html( $business_address ); ?></div>
			<div class="inv-header-div" style="display: block;"><?php echo esc_html( $business_email ); ?></div>
			<div class="inv-header-div" style="display: block;"><?php echo esc_html( $business_phone ); ?></div>
		</div>
	</div>
	<div class="mmtms-invoice-cust" style="margin-top: 60px;padding: 60px;border: 1px solid #cecece;text-align: center;">
		<div class="invoice-cust-info" style="width:50%;">
			<div class="inv-header-div cust-name" style="display: block;
															text-transform: uppercase;
															font-weight: bold;"><?php echo esc_html( $user->display_name ); ?></div>
			<div class="inv-header-div" style="display: block;"><?php echo esc_html( $user->user_email ); ?></div>
		</div>
		<div class="invoice-order-info" style="width:50%;">
			<div class="inv-header-div cust-name" style="display: block;
															text-transform: uppercase;
															font-weight: bold;"><?php esc_html_e( 'INVOICE : # ', 'momo-membership' ); ?><?php the_title(); ?></div>
			<div class="inv-header-div"><?php echo esc_html( $date ); ?></div>
		</div>
	</div>
	<div style="margin-top: 65px; text-align: center">
		<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php esc_html_e( 'Click here to view your invoice', 'momo-membership' ); ?></a>
	</div>
</div>
