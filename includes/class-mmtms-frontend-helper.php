<?php
/**
 * Frontend Helper Class
 *
 * @package momo-membership
 * @author MoMo Themes
 */
class Mmtms_Frontend_Helper {
	/**
	 * Get Level Details by slug
	 *
	 * @param string $slug Level slug.
	 */
	public function mmtms_get_level_by_slug( $slug ) {
		$mmtms_level_options = get_option( 'mmtms_level_options' );
		if ( isset( $mmtms_level_options ) && is_array( $mmtms_level_options ) ) {
			foreach ( $mmtms_level_options as $mmtms_level_option ) :
				if ( $mmtms_level_option['level_slug'] === $slug ) {
					return $mmtms_level_option;
				}
			endforeach;
		}
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
	 * Generates Post Levels
	 *
	 * @param integer $post_id Post ID.
	 */
	public function generate_post_levels( $post_id ) {
		$mb_levels_arr_show  = get_post_meta( $post_id, 'mb_levels_arr_show', true );
		$mb_levels_arr_block = get_post_meta( $post_id, 'mb_levels_arr_block', true );
		$levels['show']      = $mb_levels_arr_show;
		$levels['block']     = $mb_levels_arr_block;
		return $levels;
	}
	/**
	 * Get current user level
	 *
	 * @param integer $user_id User ID.
	 */
	public function get_current_user_level( $user_id ) {
		$user_meta = get_userdata( $user_id );
		$post      = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
			)
		);

		$level = '';
		if ( ! empty( $post ) ) {
			$post_id = $post[0]->ID;
			$mpmv    = get_post_custom( $post_id );
			$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
		}
		return $level;
	}
	/**
	 * Generate Non Logged in Message
	 *
	 * @param Post $post Post Object.
	 */
	public function generate_non_logged_in_message( $post ) {
		ob_start();
		global $mmtms;
		?>
		<div class="mmtms-fe-nl-message">
			<div class="mmtms-fe-nl-msg">
				<?php esc_html_e( 'Please Login to view this page', 'momo-membership' ); ?>
			</div>
			<div class="mmtms-fe-20p">
				<div class="mmtms-fe-login-btn mmtms-btn mmtms-form">
					<a class="btn" data-referer="<?php echo esc_html( $post->ID ); ?>" href="<?php echo esc_url( $mmtms->fhelper->mmtms_get_permalink_by_slug( 'mmtms-login' ) ); ?>?referer=<?php echo esc_html( $post->ID ); ?>"><?php esc_html_e( 'Login', 'momo-membership' ); ?></a>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate Blocked User Level Message
	 */
	public function generate_blocked_user_message() {
		ob_start();
		?>
		<div class="mmtms-fe-bu-message">
			<div class="mmtms-fe-bu-msg">
				<?php esc_html_e( 'You are not allowed to view this post', 'momo-membership' ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Get Permalink by Slug Name
	 *
	 * @param string $slug Level Slug.
	 * @param string $post_type Post Type.
	 */
	public function mmtms_get_permalink_by_slug( $slug, $post_type = 'page' ) {
		$permalink = null;
		$args      = array(
			'name'          => $slug,
			'max_num_posts' => 1,
			'post_type'     => $post_type,
		);

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			$query->the_post();
			$permalink = get_permalink( get_the_ID() );
			wp_reset_postdata();
		}
		return $permalink;
	}
	/**
	 * Check if user is subscribed to any level
	 *
	 * @param integer $user_id User ID.
	 */
	public function check_user_is_subscribed( $user_id ) {
		$user_meta = get_userdata( $user_id );
		$post      = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'mmtms-members',
				'post_status' => 'publish',
				'meta_key'    => 'mmtms-members_user-id',
				'meta_value'  => $user_id,
			)
		);

		$level = '';
		if ( ! empty( $post ) ) {
			$post_id = $post[0]->ID;
			$mpmv    = get_post_custom( $post_id );
			$level   = isset( $mpmv['mmtms-members_user-level'] ) ? $mpmv['mmtms-members_user-level'][0] : '';
		}
		if ( empty( $level ) || '' === $level ) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Return to Profile Page
	 *
	 * @param string $ref Referer Page.
	 */
	public function mmtms_refer_to_profile_page( $ref = '' ) {
		ob_start();
		?>
		<div class="info mmtms-fe-msg" style="display: block;">
			<i class="msg-icon mmtms-icon-info-circled"></i>
			<p>
			<?php esc_html_e( 'You\'ve already logged in. Please go to your profile page for any changes', 'momo-membership' ); ?>
			</p>
			<p>
				<a class="no-btn-link" href="<?php echo esc_url( $this->mmtms_get_permalink_by_slug( 'mmtms-profile', 'page' ) . '?referer=' . $ref ); ?>">
					<?php esc_html_e( 'My Profile', 'momo-membership' ); ?>
				</a>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Return to MMTMS Registration Page
	 *
	 * @param string $ref Referer Page.
	 */
	public function mmtms_refer_to_login_page( $ref = '' ) {
		ob_start();
		?>
		<div class="info mmtms-fe-msg mmtms-form" style="display: block;">
			<i class="msg-icon mmtms-icon-info-circled"></i>
			<p>
				<?php esc_html_e( 'Please Login first to display Subscription page', 'momo-membership' ); ?>
			</p>
			<p>
				<a class="no-btn-link" href="<?php echo esc_url( $this->mmtms_get_permalink_by_slug( 'mmtms-login', 'page' ) . '?referer=' . $ref ); ?>">
					<?php esc_html_e( 'Log In', 'momo-membership' ); ?>
				</a>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Return to MMTMS Subscription Page
	 *
	 * @param string $ref Referer Page.
	 */
	public function mmtms_refer_to_subscription_page( $ref = '' ) {
		ob_start();
		?>
		<div class="info mmtms-fe-msg mmtms-form" style="display: block;">
			<i class="msg-icon mmtms-icon-info-circled"></i>
			<p>
				<?php esc_html_e( 'You\'ve already registered. Please choose your desired subscription', 'momo-membership' ); ?>
			</p>
			<p>
			<a class="no-btn-link" href="<?php echo esc_url( $this->mmtms_get_permalink_by_slug( 'mmtms-subscription', 'page' ) . '?referer=' . $ref ); ?>">
				<?php esc_html_e( 'Subscription Page', 'momo-membership' ); ?>
			</a>
			</p>
		</div>
		<?php
		return ob_get_clean();
	}
	/**
	 * Return User Info to widget
	 *
	 * @param integer $user_id User ID.
	 */
	public function mmtms_generate_widget_info( $user_id ) {
		$user           = get_user_by( 'id', $user_id );
		$loggedin_title = esc_html__( 'Welcome <username>', 'momo-membership' );
		$loggedin_title = ucwords( str_replace( '<username>', $user->display_name, $loggedin_title ) );
		$gravatar       = get_avatar( $user->user_email, 80 );
		$content        = '';
		$content       .= '<h2 class="widget-title">' . $loggedin_title . '</h2>';
		$content       .= '<div class="widget_gravatar">';
			$content   .= $gravatar;
		$content       .= '</div>';
		$content       .= '<div class="mmtms_w_links">';
			$content   .= '<a href="' . $this->mmtms_get_permalink_by_slug( 'mmtms-profile', 'page' ) . '">';
			$content   .= esc_html__( 'Go to Profile', 'momo-membership' );
			$content   .= '</a>';
		$content       .= '</div>';
		return $content;
	}
	/**
	 * Returns allowed html
	 */
	public function mmtms_allowed_html() {
		$html = array(
			'a'      => array(
				'href'  => array(),
				'title' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'div'    => array(),
		);
		return $html;
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
	 * Get Country name by key
	 *
	 * @param string $ckey Country Key.
	 */
	public function get_country_by_key( $ckey ) {
		$countries = $this->mmtms_get_countries_array();
		foreach ( $countries as $key => $value ) {
			if ( $key === $ckey ) {
				return $value;
			}
		}
	}
}
