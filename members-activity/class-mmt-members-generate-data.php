<?php
/**
 * MoMo Membership - Members Activity Data Store
 *
 * @since 1.1.0
 * @author MoMo Themes
 * @package momo-membership
 */
class Mmt_Members_Generate_Data {
	/**
	 * Members table
	 *
	 * @var string
	 */
	private $members_table = 'mmtms_members';
	/**
	 * Members Activity Table
	 *
	 * @var string
	 */
	private $activity_table = 'mmtms_member_activities';
	/**
	 * Member session table
	 *
	 * @var string
	 */
	private $session_table = 'mmtms_member_session_time';
	/**
	 * Member ID
	 *
	 * @var integer
	 */
	private $member_id;
	/**
	 * Visit Tracking to check double post
	 *
	 * @var boolean
	 */
	private $visit_counter = false;
	/**
	 * Constructor
	 *
	 * @param integer $user_id Member ID.
	 */
	public function __construct( $user_id ) {
		$this->member_id = $user_id;
	}
	/**
	 * Generate member session list
	 *
	 * @param integer $start Page number.
	 * @param integer $end End number.
	 */
	public function mmt_generate_member_session_list( $start, $end ) {
		global $wpdb;
		$table = $wpdb->prefix . $this->session_table;
		$session_list = wp_cache_get( 'mmt_ma_member_session_list' );
		if ( false === $session_list ) {
			$session_list = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM
					`$table`
					WHERE member_id = %d
					LIMIT %d, %d",
					(int) $this->member_id,
					$start,
					$end
				)
			);
			wp_cache_set( 'mmt_ma_member_session_list', $session_list );
		}
		return $session_list;
	}
	/**
	 * Total Count of Sessions
	 */
	public function mmt_get_total_session_count() {
		global $wpdb;
		$table = $wpdb->prefix . $this->session_table;
		$count = wp_cache_get( 'mmt_ma_total_session_count' );
		if ( false === $count ) {
			$count = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT COUNT(*) as cnt FROM
					`$table`
					WHERE member_id = %d",
					(int) $this->member_id
				)
			);
			wp_cache_set( 'mmt_ma_total_session_count', $count );
		}
		return $count->cnt;
	}
	/**
	 * Generate Page post list from session key
	 *
	 * @param string $session_key Session Key.
	 * @param string $type Activity type.
	 */
	public function mmt_generate_pplist_from_session_key( $session_key, $type ) {
		global $wpdb;
		$table  = $wpdb->prefix . $this->activity_table;
		$pplist = wp_cache_get( 'mmt_ma_pplist_from_sk' );
		if ( false === $pplist ) {
			$pplist = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT `wp_posts` FROM
					`$table`
					WHERE member_id = %d
					AND session_key = %s
					AND activity_type = %s",
					(int) $this->member_id,
					$session_key,
					$type
				)
			);
			wp_cache_set( 'mmt_ma_pplist_from_sk', $pplist );
		}
		return $pplist;
	}
	/**
	 * Generate Latest activity by member ID
	 *
	 * @param integer $member_id Member ID.
	 */
	public static function mmt_generate_latest_activity( $member_id ) {
		global $wpdb;
		$table           = $wpdb->prefix . 'mmtms_member_activities';
		$latest_activity = wp_cache_get( 'mmt_ma_latest_activity_by_mid' );
		if ( false === $latest_activity ) {
			$latest_activity = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT `wp_posts`, `activity_type` FROM
					`$table`
					WHERE member_id = %d",
					(int) $member_id
				)
			);
			wp_cache_set( 'mmt_ma_latest_activity_by_mid', $latest_activity );
		}
		$title = '';
		if ( empty( $latest_activity ) ) {
			return esc_html__( 'Activity not found', 'momo-membership' );
		} else {
			$posts_string = $latest_activity->wp_posts;
			$type         = $latest_activity->activity_type;
			$title        = ( 'visit' === $type ) ? esc_html__( 'View : ', 'momo-membership' ) : '';
			$posts        = explode( ',', $posts_string );
			if ( isset( $posts[0] ) ) {
				$post  = get_the_title( $posts[0] );
				$title = $title . $post;
			}
		}
		return $title;
	}
}
