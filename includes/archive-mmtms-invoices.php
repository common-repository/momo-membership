<?php
/**
 * The template for displaying Invoice Archive.
 *
 * @package momo-membership
 * @author MoMo Themes
 */

get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="archive-invoice-wrapper">
			<table class="archive-invoice-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Invoice No', 'momo-membership' ); ?></th>
						<th><?php esc_html_e( 'Invoice Date', 'momo-membership' ); ?></th>
						<th><?php esc_html_e( 'Price', 'momo-membership' ); ?></th>
					</tr>
				</thead>
		<?php
		while ( have_posts() ) :
			the_post();
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

			if ( get_current_user_id() === (int) $user_id || current_user_can( 'administrator' ) ) {
				$date_ = gmdate( $date_format, strtotime( $date ) );
				$time  = gmdate( $time_format, strtotime( $date ) );
				$date  = $date_ . ' ' . $time;
				?>
				<tr>
					<td><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></td>
					<td><?php echo esc_html( $date ); ?></td>
					<td><?php echo esc_html( $price ); ?></td>
				</tr>
				<?php

			}
		endwhile;
		?>
			</table>
		</div>
	</main>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
