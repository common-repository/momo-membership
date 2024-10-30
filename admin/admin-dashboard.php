<?php
/**
 * Admin DashBoard
 *
 * @package momo-membership
 * @author MoMo Themes
 */

global $mmtms;
?>
<div class="mmtms-admin-content-box">
	<div class="mmtms-admin-content-header">
		<h3><i class="mmtms-icon-gauge"></i><?php esc_html_e( 'Dashboard', 'momo-membership' ); ?></h3>
	</div>

	<div class="mmtms-admin-content-main">
		<div class="mmtms-two-column-row">
			<div class="mmtms-two-column mmtms-two-column-left">
				<h2><?php esc_html_e( 'Documentation & Support', 'momo-membership' ); ?>
				<p>
					<?php esc_html_e( 'To get started, please visit our detailed ', 'momo-membership' ); ?><a href="<?php echo esc_url( 'http://momothemes.com/documentationMembership/' ); ?>" target="_blank"><?php esc_html_e( 'documentation here', 'momo-membership' ); ?></a>
				</p>
				<p>
					<?php esc_html_e( 'If you need further help using the program, you can open a support ticket in ', 'momo-membership' ); ?>
					<a href="mailto:info@momothemes.com" target="_blank"><?php esc_html_e( 'our helpdesk. ', 'momo-membership' ); ?></a>
					<?php esc_html_e( 'You will get assistance from one of our staffs as soon as possible.', 'momo-membership' ); ?>
			</div>
			<div class="mmtms-two-column dashboard">
				<h2><?php esc_html_e( 'First Setup', 'momo-membership' ); ?></h2>
				<span>
					<i class="mmtms-icon-user"></i>
					<a href="#levels" class="tab_click"><?php esc_html_e( 'Create a Membership Level', 'momo-membership' ); ?></a>
				</span>
				<span>
					<i class="mmtms-icon-window-restore"></i>
					<a href="#pages" class="tab_click"><?php esc_html_e( 'Generate Membership Pages', 'momo-membership' ); ?></a>
				</span>
				<span>
					<i class="mmtms-icon-basket"></i>
					<a class="tab_click" href="#payments"><?php esc_html_e( 'Configure Payment Settings', 'momo-membership' ); ?></a>
				</span>

				<h2><?php esc_html_e( 'Other Settings', 'momo-membership' ); ?></h2>
				<span>
					<i class="mmtms-icon-doc-text-inv"></i>
					<a href="#invoice" class="tab_click"><?php esc_html_e( 'Invoice Settings', 'momo-membership' ); ?></a>
				</span>
				<span>
					<i class="mmtms-icon-sliders"></i>
					<a href="#redirection" class="tab_click"><?php esc_html_e( 'View Advance Setting', 'momo-membership' ); ?></a>
				</span>
				<span>
					<i class="mmtms-icon-mail"></i>
					<a href="#emails" class="tab_click"><?php esc_html_e( 'Other Email Settings', 'momo-membership' ); ?></a>
				</span>
			</div>
		</div>
		<div class="mmtms-two-column-row">
			<div class="mmtms-two-column mmtms-two-column-left">
				<div class="metabox-holder">
					<div class="postbox-container">
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle ui-sortable-handle">
										<span><?php esc_html_e( 'Membership Stats', 'momo-membership' ); ?></span>
									</h2>
									<div class="handle-actions">
										<button type="button" class="mmtms-handlediv" aria-expanded="true">
											<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Membership Stats', 'momo-membership' ); ?></span>
											<span class="toggle-indicator" aria-hidden="true"></span>
										</button>
									</div>
								</div>
								<div class="inside">
									<table class="mmtms-dashboard-table">
										<thead>
											<tr>
												<th>
												</th>
												<th>
													<?php esc_html_e( 'Signup', 'momo-membership' ); ?>
												</th>
												<th>
													<?php esc_html_e( 'All Cancellations', 'momo-membership' ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<?php esc_html_e( 'Today', 'momo-membership' ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html( $mmtms->admin_helper->mmtms_user_count( 'today' ) ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html_e( '-', 'momo-membership' ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<?php esc_html_e( 'This Month', 'momo-membership' ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
												<?php esc_html( $mmtms->admin_helper->mmtms_user_count( 'month' ) ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html_e( '-', 'momo-membership' ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<?php esc_html_e( 'This Year', 'momo-membership' ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html( $mmtms->admin_helper->mmtms_user_count( 'year' ) ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html_e( '-', 'momo-membership' ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<?php esc_html_e( 'All Time', 'momo-membership' ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html( $mmtms->admin_helper->mmtms_user_count( 'alltime' ) ); ?>
												</td>
												<td style="font-weight:bold;text-align:center;">
													<?php esc_html_e( '-', 'momo-membership' ); ?>
												</td>
											</tr>
										</tbody>
									</table>
									<div class="table-footer">
										<a href="#members" class="tab_click table-footer-btn"><?php esc_html_e( 'Details', 'momo-membership' ); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="mmtms-two-column">
				<div class="metabox-holder">
					<div class="postbox-container">
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="postbox-header">
									<h2 class="hndle ui-sortable-handle">
										<span><?php esc_html_e( 'Recent Members', 'momo-membership' ); ?></span>
									</h2>
									<div class="handle-actions">
										<button type="button" class="mmtms-handlediv" aria-expanded="true">
											<span class="screen-reader-text"><?php esc_html_e( 'Toggle panel: Recent Members', 'momo-membership' ); ?></span>
											<span class="toggle-indicator" aria-hidden="true"></span>
										</button>
									</div>
								</div>
								<div class="inside">
									<table class="mmtms-dashboard-table">
										<thead>
											<tr>
												<th>
													<?php esc_html_e( 'Username', 'momo-membership' ); ?>
												</th>
												<th>
													<?php esc_html_e( 'Membership', 'momo-membership' ); ?>
												</th>
												<th>
													<?php esc_html_e( 'Joined', 'momo-membership' ); ?>
												</th>
												<th>
													<?php esc_html_e( 'Expires', 'momo-membership' ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php
												global $mmtms;
												$user_arr = $mmtms->admin_helper->mmtms_recent_user_list( 5 );
											?>
											<?php if ( empty( $user_arr ) && count( $user_arr ) > 0 ) { ?>
											<tr>
												<td colspan="4">
													<?php esc_html_e( 'No members found.', 'momo-membership' ); ?>
												</td>
											</tr>
											<?php } else { ?>
												<?php foreach ( $user_arr as $user ) { ?>
													<tr>
														<td>
															<?php esc_html( $user['username'] ); ?>
														</td>
														<td>
															<?php esc_html( $user['level'] ); ?>
														</td>
														<td>
															<?php esc_html( $user['joined'] ); ?>
														</td>
														<td>
															<?php esc_html_e( '-', 'momo-membership' ); ?>
														</td>
													</tr>
												<?php } // ends foreach. ?>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
