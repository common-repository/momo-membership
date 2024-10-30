<?php
/**
 * Settings Page for Pages
 *
 * @package momo-membership
 * @author MoMo Themes
 */

?>
<?php global $mmtms; ?>
<div class="mmtms-admin-content-box">
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-website"></i><?php esc_html_e( 'Default Pages', 'momo-membership' ); ?></h3>
	</div>
	<div class="mmtms-admin-content-content" id="mmtms-admin-form">
		<table id="mmtms-admin-table-pages" class="mmtms-at wp-list-table widefat fixed tags">
			<thead>
				<tr>
					<th>
						<?php esc_html_e( 'Page Title', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Page Slug', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Status', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				<th>
					<?php esc_html_e( 'Page Title', 'momo-membership' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Page Slug', 'momo-membership' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Status', 'momo-membership' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Action', 'momo-membership' ); ?>
				</th>
				</tr>
			</tfoot>
			<tbody>
			<?php
				$mpages = $mmtms->admin_helper->get_pages_array();
			foreach ( $mpages as $slug => $mpage ) :
				?>
					<tr>
					<?php
					if ( $mmtms->admin_helper->check_page_by_slug( $slug ) ) {
						$page_ = $mmtms->admin_helper->check_page_by_slug( $slug );
						?>
						<td><?php echo esc_html( $page_->post_title ); ?></td>
						<td><?php echo esc_html( $slug ); ?></td>
						<td>
						<?php if ( 'publish' === $page_->post_status ) { ?>
							<span class="mmtms_admin_p_status">
								<?php echo esc_html( $page_->post_status ); ?>
							</span>
						<?php } else { ?>
							<span class="mmtms_admin_p_status danger">
							<?php echo esc_html( $page_->post_status ); ?>
							</span>
						<?php } ?>
						</td>
						<td>
						<?php if ( 'publish' === $page_->post_status ) { ?>
							<a href="<?php echo esc_url( $page_->guid ); ?>" target="_blank">
								<?php esc_html_e( 'View', 'momo-membership' ); ?>
							</a>
						<?php } else { ?>
							<a href="#" class="mmtms-admin-reinstall-page" data-slug="<?php echo esc_attr( $slug ); ?>" data-pid="<?php echo esc_attr( $page_->ID ); ?>">
								<?php esc_html_e( 'RePublish', 'momo-membership' ); ?>
							</a>
						<?php } ?>
						</td>
					<?php } else { ?>
						<td><?php echo esc_html( $mpage['title'] ); ?></td>
						<td><?php echo esc_html( $slug ); ?></td>
						<td>
							<span class="mmtms_admin_p_status danger">
								<?php esc_html_e( 'Unpublished', 'momo-membership' ); ?>
							</span>
						</td>
						<td>
							<a href="#" class="mmtms-admin-reinstall-page" data-slug="<?php echo esc_attr( $slug ); ?>" data-pid="na">
								<?php esc_html_e( 'RePublish', 'momo-membership' ); ?>
							</a>
						</td>
					<?php } ?>
					</tr>
					<?php
				endforeach;
			?>
			</tbody>
		</table>
	</div>
</div>
