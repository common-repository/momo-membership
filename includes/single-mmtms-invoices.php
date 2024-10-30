<?php
/**
 * The template for displaying Invoice.
 *
 * @package momo-membership
 * @author MoMo Themes
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php
				global $mmtms;
				$date_format           = get_option( 'date_format' );
				$time_format           = get_option( 'time_format' );
				$mmtms_invoice_options = get_option( 'mmtms_invoice_options' );
				$business_logo         = isset( $mmtms_invoice_options['mmtms_business_logo'] ) ? $mmtms_invoice_options['mmtms_business_logo'] : '';
				$business_name         = isset( $mmtms_invoice_options['mmtms_inv_bname'] ) ? $mmtms_invoice_options['mmtms_inv_bname'] : '';
				$business_address      = isset( $mmtms_invoice_options['mmtms_inv_address'] ) ? $mmtms_invoice_options['mmtms_inv_address'] : '';
				$business_email        = isset( $mmtms_invoice_options['mmtms_inv_email'] ) ? $mmtms_invoice_options['mmtms_inv_email'] : '';
				$business_phone        = isset( $mmtms_invoice_options['mmtms_inv_phone'] ) ? $mmtms_invoice_options['mmtms_inv_phone'] : '';
				$meta                  = get_post_custom( get_the_ID() );
				$user_id               = isset( $meta['mmtms-invoices_user-id'] ) ? $meta['mmtms-invoices_user-id'][0] : '';
				$date                  = isset( $meta['mmtms-invoices_invoice-date'] ) ? $meta['mmtms-invoices_invoice-date'][0] : '';
				$level_slug            = isset( $meta['mmtms-invoices_user-level'] ) ? $meta['mmtms-invoices_user-level'][0] : '';
				$price                 = isset( $meta['mmtms-invoices_invoice-price'] ) ? $meta['mmtms-invoices_invoice-price'][0] : '';
				$level                 = $mmtms->fhelper->mmtms_get_level_by_slug( $level_slug );
				$user                  = get_user_by( 'id', $user_id );

			if ( get_current_user_id() !== (int) $user_id || ! current_user_can( 'administrator' ) ) {
					die( 'You\'re not allowed to view this page' );
			}
				$date_ = gmdate( $date_format, strtotime( $date ) );
				$time  = gmdate( $time_format, strtotime( $date ) );
				$date  = $date_ . ' ' . $time;
			?>
			<div class="mmtms-invoice-container">
				<div class="mmtms-invoice-header">
					<div class="invoice-header-logo">
						<img src="<?php echo esc_url( $business_logo ); ?>" width="100px" height="100px">
					</div>
					<div class="invoice-header-info">
						<span class="inv-header-span business-name"><?php echo esc_html( $business_name ); ?></span>
						<span class="inv-header-span"><i class="mmtms-icon-address"></i><?php echo esc_html( $business_address ); ?></span>
						<span class="inv-header-span"><i class="mmtms-icon-mail"></i><?php echo esc_html( $business_email ); ?></span>
						<span class="inv-header-span"><i class="mmtms-icon-phone"></i><?php echo esc_html( $business_phone ); ?></span>
					</div>
				</div>
				<div class="mmtms-invoice-cust">
					<div class="invoice-cust-info">
						<span class="inv-header-span cust-name"><?php echo esc_html( $user->display_name ); ?></span>
						<span class="inv-header-span"><?php echo esc_html( $user->user_email ); ?></span>
					</div>
					<div class="invoice-order-info">
						<span class="inv-header-span cust-name"><?php esc_html_e( 'INVOICE : # ', 'momo-membership' ); ?><?php the_title(); ?></span>
						<span class="inv-header-span"><?php echo esc_html( $date ); ?></span>
					</div>
				</div>
				<table class="invoice-table">
					<thead>
						<tr>
							<th width="20%">
								<?php esc_html_e( 'Membership', 'momo-membership' ); ?>
							</th>
							<th width="60%">
								<?php esc_html_e( 'Level Description', 'momo-membership' ); ?>
							</th>
							<th width="20%">
								<?php esc_html_e( 'Price', 'momo-membership' ); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php echo esc_html( $level['level_name'] ); ?>
							</td>
							<td>
								<?php echo wp_kses( $level['description'], $mmtms->fhelper->mmtms_allowed_html() ); ?>
							</td>
							<td>
								<?php echo esc_html( $price ); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
