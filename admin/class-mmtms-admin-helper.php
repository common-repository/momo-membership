<?php
/**
 * Admin Helper Class
 *
 * @package momo-membership
 * @author MoMo Themes
 * @since v1.0.0
 */
class Mmtms_Admin_Helper {
	/**
	 * Count WP Users
	 */
	public function count_wp_users() {
		$usercount = count_users();
		$result    = $usercount['total_users'];
		return $result;
	}

	/**
	 * Checks if user level name already exist
	 *
	 * @param string $level_name Level Name.
	 */
	public function check_user_level_exists( $level_name ) {
		$mmtms_level_options = get_option( 'mmtms_level_options' );
		if ( isset( $mmtms_level_options ) && is_array( $mmtms_level_options ) ) {
			foreach ( $mmtms_level_options as $mmtms_level_option ) :
				if ( $mmtms_level_option['level_name'] === $level_name ) {
					return $level_name;
				}
			endforeach;
		}
		return false;
	}

	/**
	 * Get Level Name by slug
	 *
	 * @param string $slug Level Slug.
	 */
	public function mmtms_level_name_by_slug( $slug ) {
		if ( 'mmtms-ru' === $slug ) {
			return esc_html__( 'Registered User', 'momo-membership' );
		}
		if ( 'all' === $slug ) {
			return esc_html__( 'All', 'momo-membership' );
		}
		if ( 'mmtms-uu' === $slug ) {
			return esc_html__( 'Unregistered User', 'momo-membership' );
		}
		$levels = get_option( 'mmtms_level_options' );
		foreach ( $levels as $level ) {
			if ( $level['level_slug'] === $slug ) {
				return $level['level_name'];
			}
		}
	}
	/**
	 * Prepare Slug
	 *
	 * @param string $text Level Name.
	 */
	public static function mmtms_slugify( $text ) {
		// replace non letter or digits by -.
		$text = preg_replace( '~[^\pL\d]+~u', '-', $text );
		// transliterate.
		$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );
		// remove unwanted characters.
		$text = preg_replace( '~[^-\w]+~', '', $text );
		// trim.
		$text = trim( $text, '-' );
		// remove duplicate -.
		$text = preg_replace( '~-+~', '-', $text );
		// lowercase.
		$text = strtolower( $text );
		if ( empty( $text ) ) {
			return 'n-a';
		}
		return $text;
	}

	/**
	 * Generate Admin Level Table
	 */
	public function mmtms_generate_level_table() {
		$levels  = get_option( 'mmtms_level_options' );
		$content = '';
		ob_start();
		?>
		<table id="mmtms-admin-table-levels" class="mmtms-at wp-list-table widefat fixed tags">
			<thead>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Payment Type', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'WP Capability', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Price', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Payment Type', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'WP Capability', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Price', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ( $levels as $level ) : ?>
					<tr>
						<td width="30px">
							<input type="checkbox" name="mmtms_select_level" value="<?php echo esc_attr( $level['level_name'] ); ?>"/>
						</td>
						<td>
							<?php echo esc_html( $level['level_name'] ); ?>
						</td>
						<td>
							<?php echo esc_html( $level['billing_type'] ); ?>
						</td>
						<td>
							<?php echo esc_html( $level['wp_capability'] ); ?>
						</td>
						<td>
							<?php echo esc_html( $level['level_price'] ); ?>
						</td>
						<td>
							<a href="#" class="mmtms_level_table_edit" data-slug="<?php echo esc_attr( $level['level_slug'] ); ?>">
								<i class="mmtms-icon-edit"></i>
							</a>
							<a href="#" class="mmtms_level_table_delete" data-slug="<?php echo esc_attr( $level['level_slug'] ); ?>">
								<i class="mmtms-icon-trash-empty"></i>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
	/**
	 * Get user roles
	 */
	protected function mmtms_get_roles_data() {
		$roles = new WP_Roles();
		return $roles->get_roles_data();
	}
	/**
	 * Generate Admin Level Table
	 */
	public function mmtms_generate_role_table() {
		global $wp_roles, $mmtms, $wp_user_roles;
		$user_roles = get_option( 'wp_user_roles' );
		$user_roles = array_reverse( get_editable_roles() );
		ob_start();
		?>
			<table id="mmtms-admin-table-roles" class="mmtms-at wp-list-table widefat fixed tags">
				<thead>
					<tr>
						<th width="30px">

						</th>
						<th>
							<?php esc_html_e( 'Role Title', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Role ID', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'No. of Members', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Capabilities', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Action', 'momo-membership' ); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th width="30px">

						</th>
						<th>
							<?php esc_html_e( 'Role Title', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Role ID', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'No. of Members', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Capabilities', 'momo-membership' ); ?>
						</th>
						<th>
							<?php esc_html_e( 'Action', 'momo-membership' ); ?>
						</th>
					</tr>
				</tfoot>
				<tbody>
					<?php
					if ( is_array( $user_roles ) && count( $user_roles ) > 0 ) :
						foreach ( $user_roles as $id => $user_role ) {
							$user_query  = new WP_User_Query( array( 'role' => $user_role['name'] ) );
							$users_count = (int) $user_query->get_total();
						?>
					<tr>
						<td width="30px">
							<input type="checkbox" name="mmtms_select_role" value="<?php echo esc_attr( $id ); ?>"/>
						</td>
						<td>
							<?php echo esc_html( $user_role['name'] ); ?>
						</td>
						<td>
							<?php echo esc_html( $id ); ?>
						</td>
						<td align="center">
							<?php echo esc_html( $users_count ); ?>
						</td>
						<td>
							<?php
								$count      = 0;
								$more_added = false;
							foreach ( $user_role['capabilities'] as $capability => $yesno ) {
								if ( 1 === (int) $yesno ) {
									?>
									<span class="mmtms-admin-cpb"><?php echo esc_html( $capability ); ?></span>
									<?php
									$count = $count++;
									if ( 2 === $count ) {
										$more_added = true;
										?>
										<div class="mmtms-admin-cpb-hide">
										<?php
									}
								}
							}
							if ( $more_added ) {
								?>
								</div>
								<a href="#" class="mmtms-cpb-load-more">
									<i class="mmtms-icon-angle-circled-down"></i>
								</a>
								<a href="#" class="mmtms-cpb-load-less">
									<i class="mmtms-icon-angle-circled-up"></i>
								</a>
								<?php
							}
							?>
						</td>
						<td>
							<a href="#" class="mmtms_role_table_edit" title="<?php esc_attr_e( 'Edit Role', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $id ); ?>">
								<i class="mmtms-icon-edit"></i>
							</a>
							<a href="#" class="mmtms_role_table_delete" title="<?php esc_attr_e( 'Delete Role', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $id ); ?>">
								<i class="mmtms-icon-trash-empty"></i>
							</a>
							<a href="#" class="mmtms_cap_table_edit" title="<?php esc_attr_e( 'Edit Capabilities', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $id ); ?>">
								<i class="mmtms-icon-vcard"></i>
							</a>
						</td>
					</tr>
						<?php
						}
					endif;
					?>
				</tbody>
			</table>
		<?php
			return ob_get_clean();
	}

	/**
	 * Capabilities list
	 */
	public function mmtms_wp_capabilities_list() {
		$capabilities = array(
			'super_admin'   => array(
				'desc' => esc_html__( 'Super Admin related capabilities', 'momo-membership' ),
				'caps' => array(
					'create_sites',
					'delete_sites',
					'manage_network',
					'manage_sites',
					'manage_network_users',
					'manage_network_plugins',
					'manage_network_themes',
					'manage_network_options',
					'upload_plugins',
					'upload_themes',
					'upgrade_network',
					'setup_network',
				),
			),
			'administrator' => array(
				'desc' => esc_html__( 'Administrator related capabilities', 'momo-membership' ),
				'caps' => array(
					'activate_plugins',
					'create_users',
					'delete_plugins',
					'delete_themes',
					'delete_users',
					'edit_files',
					'edit_plugins',
					'edit_theme_options',
					'edit_themes',
					'edit_users',
					'export',
					'import',
					'manage_options',
					'switch_options',
					'switch_themes',
					'edit_dashboard',
					'update_core',
					'install_plugins',
					'install_themes',
					'upload_plugins',
					'upload_themes',
					'delete_themes',
					'delete_plugins',
					'edit_plugins',
					'unfiltered_html',
				),
			),
			'editor'        => array(
				'desc' => esc_html__( 'Editor related capabilities', 'momo-membership' ),
				'caps' => array(
					'moderate_comments',
					'manage_categories',
					'manage_links',
					'edit_others_posts',
					'edit_pages',
					'edit_others_pages',
					'edit_published_pages',
					'publish_pages',
					'delete_pages',
					'delete_others_pages',
					'delete_published_pages',
					'delete_others_posts',
					'delete_public_posts',
					'edit_public_posts',
					'read_public_posts',
					'delete_public_pages',
					'edit_public_pages',
					'read_public_pages',
					'unfiltered_html',
				),
			),
			'author'        => array(
				'desc' => esc_html__( 'Author related capabilities', 'momo-membership' ),
				'caps' => array(
					'edit_published_posts',
					'upload_files',
					'publish_posts',
					'delete_published_posts',
					'edit_posts',
					'delete_posts',
				),
			),
			'subscriber'    => array(
				'desc' => esc_html__( 'Subscriber related capabilities', 'momo-membership' ),
				'caps' => array(
					'read',
				),
			),
			'deprecated'    => array(
				'desc' => esc_html__( 'WP Deprecated capabilities', 'momo-membership' ),
				'caps' => array(
					'level_0',
					'level_1',
					'level_2',
					'level_3',
					'level_4',
					'level_5',
					'level_6',
					'level_7',
					'level_8',
					'level_9',
					'level_10',
				),
			),
			'other'         => array(
				'desc' => esc_html__( 'Other WP capabilities', 'momo-membership' ),
				'caps' => array(
					'unfiltered_upload',
				),
			),
		);
		return $capabilities;
	}
	/**
	 * Other Custom Capabilities
	 */
	public function mmtms_other_custom_capabilities() {
		$_roles     = $this->mmtms_get_roles();
		$total_caps = array();
		foreach ( $_roles as $key => $value ) {
			if ( ! empty( $_roles[ $key ]['capabilities'] ) ) {
				foreach ( $_roles[ $key ]['capabilities'] as $cap_key => $cap_value ) {
					if ( ! in_array( $cap_key, $total_caps, true ) ) {
						$total_caps[] = $cap_key;
					}
				}
			}
		}
		$built_in_caps = $this->mmtms_get_builtin_caps_list();
		return array_diff( $total_caps, $built_in_caps );
	}
	/**
	 * Get Roles
	 */
	public function mmtms_get_roles() {
		global $wp_roles;
		$all_roles = $wp_roles->roles;
		$_roles    = (array) $all_roles;
		if ( isset( $except ) && '' !== $except ) {
			unset( $_roles[ $except ] );
		}
		return $_roles;
	}
	/**
	 * Get Builtin capabilities List
	 */
	public function mmtms_get_builtin_caps_list() {
		$builtin_caps    = array();
		$wp_capabilities = $this->mmtms_wp_capabilities_list();
		foreach ( $wp_capabilities as $key => $value ) {
			foreach ( $value['caps'] as $key => $cdetails ) {
				$builtin_caps[] = $cdetails;
			}
		}
		return $builtin_caps;
	}
	/**
	 * Check Capabilities by role
	 *
	 * @param string $capability Capability.
	 * @param string $role Role.
	 * @param array  $capabilities Capabilities.
	 */
	public function mmtms_check_capability_by_role( $capability, $role, $capabilities ) {
		global $mmtms;
		$status = '';
		foreach ( $capabilities as $cap => $stat ) {
			if ( $cap === $capability && 1 === (int) $stat ) {
				$status = 'checked';
			}
		}
		return $status;
	}
	/**
	 * Generate Capabilities table by role
	 *
	 * @param integer $role Role ID.
	 */
	public function mmtms_generate_capabilities_by_role( $role ) {
		global $mmtms;
		$capabilities = get_role( $role )->capabilities;
		$builtin_caps = $this->mmtms_wp_capabilities_list();
		ob_start();
		?>
		<input type="hidden" value="<?php echo esc_attr( $role ); ?>" name="mmtms-lb-role-name"/>
		<table class="mmtms-admin-role-table mmtms-at wp-list-table widefat fixed tags">
		<?php
		foreach ( $builtin_caps as $bc_id => $bc_detail ) {
			?>
			<tr class="mmtms-art-subhead">
				<td colspan="3">
					<?php echo esc_html( $bc_detail['desc'] ); ?>
				</td>
			</tr>
			<?php
				$count = 1;
			foreach ( $bc_detail['caps'] as $cap ) {
				if ( 1 === $count ) {
					echo '<tr>';
				}
						echo '<td>';
							echo '<input type="checkbox" id="' . esc_attr( $cap ) . '" name="' . esc_attr( $cap ) . '" ' . esc_attr( $this->mmtms_check_capability_by_role( $cap, $role, $capabilities ) ) . '>';
							echo '<label for="' . esc_attr( $cap ) . '">' . esc_html( $cap ) . '</label>';
						echo '</td>';
					$count = $count++;
				if ( 4 === $count ) {
					$count = 1;
					echo '</tr>';
				}
			}
			?>
			<?php
		}
		?>
		</table>
		<?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	/**
	 * Generate Users Table
	 *
	 * @param string $page Page type ordering table.
	 */
	public function mmtms_generate_user_table( $page = 'settings' ) {
		$users = get_users(
			array(
				'fields' => array(
					'ID',
					'user_nicename',
					'display_name',
					'user_email',
				),
			)
		);
		ob_start();
		?>
		<table id="mmtms-admin-table-user" class="mmtms-at wp-list-table widefat fixed tags">
			<thead>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'User Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Display Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Email', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'WP Role', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Level', 'momo-membership' ); ?>
					</th>
					<?php if ( 'ma-page' !== $page ) : ?>
					<th width="60px">
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'User Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Display Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Email', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'WP Role', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Level', 'momo-membership' ); ?>
					</th>
					<?php if ( 'ma-page' !== $page ) : ?>
					<th width="60px">
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ( $users as $user ) :
					$user_id    = $user->ID;
					$user_meta  = get_userdata( $user_id );
					$user_roles = $user_meta->roles;
					$roles      = implode( ' ', $user_roles );
					$level      = '';
					$post       = get_posts(
						array(
							'numberposts' => 1,
							'post_type'   => 'mmtms-members',
							'post_status' => 'publish',
							'meta_key'    => 'mmtms-members_user-id',
							'meta_value'  => $user_id,
						)
					);
					if ( ! empty( $post ) ) {
						$post_id = $post[0]->ID;
						$mpmv    = get_post_custom( $post_id );
						$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
					}
					?>
					<tr>
						<td width="30px">

						</td>
						<td data-userid="<?php echo esc_attr( $user_id ); ?>"  data-username="<?php echo esc_html( $user->display_name ); ?>">
							<span class="mmtms-member-activity-report-href" data-title="<?php echo esc_html( $user->display_name ); ?>">
								<?php echo esc_html( $user->user_nicename ); ?>
							</span>
						</td>
						<td>
							<?php echo esc_html( $user->display_name ); ?>
						</td>
						<td>
							<?php echo esc_html( $user->user_email ); ?>
						</td>
						<td>
							<?php echo esc_html( $roles ); ?>
						</td>
						<td>
							<?php echo esc_html( $level ); ?>
						</td>
						<?php if ( 'ma-page' !== $page ) : ?>
						<td width="60px">
							<a href="#" class="mmtms_member_table_edit" title="<?php esc_attr_e( 'Edit Member', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $user_id ); ?>">
								<i class="mmtms-icon-edit"></i>
							</a>
							<a href="#" class="mmtms_member_table_delete" title="<?php esc_attr_e( 'Delete Member', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $user_id ); ?>">
								<i class="mmtms-icon-trash-empty"></i>
							</a>
						</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
/**
	 * Generate Users Table
	 *
	 * @param string $page Page type ordering table.
	 */
	public function mmtms_generate_ma_page_table( $page = 'settings' ) {
		$users = get_users(
			array(
				'fields' => array(
					'ID',
					'user_nicename',
					'display_name',
					'user_email',
				),
			)
		);
		ob_start();
		?>
		<table id="mmtms-admin-table-user" class="mmtms-at wp-list-table widefat fixed tags">
			<thead>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'User Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Display Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Email', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Last Login', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Last Activity', 'momo-membership' ); ?>
					</th>
					<?php if ( 'ma-page' !== $page ) : ?>
					<th width="60px">
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th width="30px">

					</th>
					<th>
						<?php esc_html_e( 'User Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Display Name', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Email', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Last Login', 'momo-membership' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Last Activity', 'momo-membership' ); ?>
					</th>
					<?php if ( 'ma-page' !== $page ) : ?>
					<th width="60px">
						<?php esc_html_e( 'Action', 'momo-membership' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ( $users as $user ) :
					$user_id    = $user->ID;
					$user_meta  = get_userdata( $user_id );
					$user_roles = $user_meta->roles;
					$roles      = implode( ' ', $user_roles );
					$level      = '';
					$last_login = get_the_author_meta( '_mmtms_last_login', $user_id );
					$last_login = ! empty( $last_login ) ? gmdate( 'M j, Y h:i a', $last_login ) : '';
					$post       = get_posts(
						array(
							'numberposts' => 1,
							'post_type'   => 'mmtms-members',
							'post_status' => 'publish',
							'meta_key'    => 'mmtms-members_user-id',
							'meta_value'  => $user_id,
						)
					);
					if ( ! empty( $post ) ) {
						$post_id = $post[0]->ID;
						$mpmv    = get_post_custom( $post_id );
						$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
					}
					?>
					<tr>
						<td width="30px">

						</td>
						<td data-userid="<?php echo esc_attr( $user_id ); ?>" data-username="<?php echo esc_html( $user->display_name ); ?>">
							<span class="mmtms-member-activity-report-href" data-title="<?php echo esc_html( $user->display_name ); ?>">
								<?php echo esc_html( $user->user_nicename ); ?>
							</span>
						</td>
						<td>
							<?php echo esc_html( $user->display_name ); ?>
						</td>
						<td>
							<?php echo esc_html( $user->user_email ); ?>
						</td>
						<td>
							<?php echo esc_html( $last_login ); ?>
						</td>
						<td>
							<?php echo esc_html( Mmt_Members_Generate_Data::mmt_generate_latest_activity( $user_id ) ); ?>
						</td>
						<?php if ( 'ma-page' !== $page ) : ?>
						<td width="60px">
							<a href="#" class="mmtms_member_table_edit" title="<?php esc_attr_e( 'Edit Member', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $user_id ); ?>">
								<i class="mmtms-icon-edit"></i>
							</a>
							<a href="#" class="mmtms_member_table_delete" title="<?php esc_attr_e( 'Delete Member', 'momo-membership' ); ?>" data-slug="<?php echo esc_attr( $user_id ); ?>">
								<i class="mmtms-icon-trash-empty"></i>
							</a>
						</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		return ob_get_clean();
	}
	/**
	 * Recent User List
	 *
	 * @param integer $count User Count.
	 */
	public function mmtms_recent_user_list( $count ) {
		$user_arr = array();
		$users    = get_users(
			array(
				'fields' => array(
					'ID',
					'user_nicename',
					'display_name',
					'user_email',
					'user_registered',
				),
				'number' => $count,
			)
		);

		$i = 1;
		foreach ( $users as $user ) :
			$user_id    = $user->ID;
			$user_meta  = get_userdata( $user_id );
			$user_roles = $user_meta->roles;
			$roles      = implode( ' ', $user_roles );
			$level      = '';
			$post       = get_posts(
				array(
					'numberposts' => 1,
					'post_type'   => 'mmtms-members',
					'post_status' => 'publish',
					'meta_key'    => 'mmtms-members_user-id',
					'meta_value'  => $user_id,
				)
			);
			if ( ! empty( $post ) ) {
				$post_id = $post[0]->ID;
				$mpmv    = get_post_custom( $post_id );
				$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
			}
			$user_arr[ $i ]['username'] = $user->user_nicename;
			$user_arr[ $i ]['level']    = $level;
			$date_format                = get_option( 'date_format' );
			$joined                     = gmdate( $date_format, strtotime( $user->user_registered ) );
			$user_arr[ $i ]['joined']   = $joined;
			$i++;
		endforeach;
		return $user_arr;
	}

	/**
	 * Count user by date
	 *
	 * @param string $by Count by type.
	 */
	public function mmtms_user_count( $by ) {
		$count = 0;
		switch ( $by ) {
			case 'today':
				$args = array(
					'date_query' => array(
						array(
							'after'     => '12 hours ago',
							'inclusive' => true,
						),
					),
				);

				$user_query = new WP_User_Query( $args );
				$count      = $user_query->get_total();
				break;
			case 'month':
				$args = array(
					'date_query' => array(
						array(
							'after'     => '1 month ago',
							'inclusive' => true,
						),
					),
				);

				$user_query = new WP_User_Query( $args );
				$count      = $user_query->get_total();
				break;
			case 'year':
				$args = array(
					'date_query' => array(
						array(
							'after'     => '1 year ago',
							'inclusive' => true,
						),
					),
				);

				$user_query = new WP_User_Query( $args );
				$count      = $user_query->get_total();
				break;
			case 'alltime':
				$count = count_users();
				$count = $count['total_users'];
				break;
		}
		return $count;
	}
	/**
	 * Generate Dropdwon Levels
	 */
	public function mmtms_dropdown_level() {
		$levels = get_option( 'mmtms_level_options' );
		if ( empty( $levels ) ) {
			return;
		}
		$options = '';
		foreach ( $levels as $level ) {
			$options .= '<option value="' . esc_html( $level['level_slug'] ) . '">' . esc_html( $level['level_name'] ) . '</option>';
		}
		$allowed = array(
			'option' => array(
				'value' => array(),
			),
		);
		echo wp_kses( $options, $allowed );
	}

	/**
	 * Create MMTMS User with Level
	 *
	 * @param integer $user_id User ID.
	 * @param string  $level Level Slug.
	 * @param string  $username User Name.
	 */
	public function mmtms_create_user( $user_id, $level, $username ) {
		$posts = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
			)
		);
		if ( ! empty( $posts ) ) {
			return;
		}
		$id = wp_insert_post(
			array(
				'post_title'  => $username,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
			)
		);
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		update_post_meta( $id, 'mmtms-members_user-id', $user_id );
		update_post_meta( $id, 'mmtms-members_user-level', $level );
		return $id;
	}

	/**
	 * Update MMTMS User
	 *
	 * @param integer $user_id User ID.
	 * @param string  $level Level Slug.
	 * @param string  $username User Name.
	 */
	public function mmtms_update_user( $user_id, $level, $username ) {
		$post_id = post_exists( $username );
		if ( 0 === $post_id ) {
			$post_id = wp_insert_post(
				array(
					'post_title'  => $username,
					'post_type'   => 'mmtms-members',
					'post_status' => 'publish',
				)
			);
		}
		update_post_meta( $post_id, 'mmtms-members_user-id', $user_id );
		update_post_meta( $post_id, 'mmtms-members_user-level', $level );
		return $post_id;
	}

	/**
	 * Check if our default page exists
	 *
	 * @param string $page_slug Page Slug.
	 */
	public function check_page_by_slug( $page_slug ) {
		global $mmtms;
		global $wpdb;
		$post_statuses = get_post_stati();
		$query         = '';
		foreach ( $post_statuses as $slug => $name ) {
			if ( '' === $query ) {
				$query = 'post_status=' . $slug;
			} else {
				$query .= ' OR post_status=' . $slug;
			}
		}
		$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= 'page'", $page_slug ) );
		if ( $page ) {
			return get_post( $page, OBJECT );
		}
		return null;
	}
	/**
	 * Get pages array
	 */
	public function get_pages_array() {
		$page_definitions = array(
			'mmtms-register'       => array(
				'title'   => esc_html__( 'Registration', 'momo-membership' ),
				'content' => '[mmtms_user_register]',
			),
			'mmtms-login'          => array(
				'title'   => esc_html__( 'Login', 'momo-membership' ),
				'content' => '[mmtms_user_login]',
			),
			'mmtms-profile'        => array(
				'title'   => esc_html__( 'User Profile', 'momo-membership' ),
				'content' => '[mmtms_user_profile]',
			),
			'mmtms-logout'         => array(
				'title'   => esc_html__( 'Logout Link', 'momo-membership' ),
				'content' => '[mmtms_logout_link]',
			),
			'mmtms-reset-password' => array(
				'title'   => esc_html__( 'Reset Password', 'momo-membership' ),
				'content' => '[mmtms_reset_password]',
			),
			'mmtms-subscription'   => array(
				'title'   => esc_html__( 'Membership Subscription', 'momo-membership' ),
				'content' => '[mmtms_subscription]',
			),
		);
		return $page_definitions;
	}
	/**
	 * Returns Country Array
	 */
	public function mmtms_get_countries_array() {
		$countries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Åland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, The Democratic Republic of The',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea, Democratic People\'s Republic of',
			'KR' => 'Korea, Republic of',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia, The Former Yugoslav Republic of',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States of',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and The Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and The South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan, Province of China',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania, United Republic of',
			'TH' => 'Thailand',
			'TL' => 'Timor-leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
		return $countries;
	}
	/**
	 * Print Table
	 *
	 * @param string $table Table content.
	 */
	public function mmtms_wp_kses_echo_table( $table ) {
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
				'width'         => array(),
				'data-userid'   => array(),
				'data-username' => array(),
			),
			'span'  => array(
				'data-title' => array(),
				'class'      => array(),
			),
			'a'     => array(
				'title'     => array(),
				'href'      => array(),
				'data-slug' => array(),
				'class'     => array(),
			),
			'i'     => array(
				'class' => array(),
			),
			'input' => array(
				'type'  => array(),
				'name'  => array(),
				'value' => array(),
			),
		);
		echo wp_kses( $table, $allowed );
	}
}
