<?php
/**
 * Admin Taxonomy Class
 *
 * @author MoMo Themes
 * @package mmtms
 * @since v1.0.0
 */
class Mmtms_Admin_Taxonomy {
	/**
	 * Constructer
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'mmtms_register_levels_taxonomies' ), 5 );

		add_action( 'levels_membership_type_edit_form_fields', array( $this, 'levels_membership_type_taxonomy_custom_fields' ), 10, 2 );
		add_action( 'levels_membership_type_add_form_fields', array( $this, 'levels_membership_type_taxonomy_custom_fields' ), 10, 1 );
		add_action( 'edited_levels_membership_type', array( $this, 'save_levels_membership_type_taxonomy_custom_fields' ), 10, 2 );
		add_action( 'created_levels_membership_type', array( $this, 'save_levels_membership_type_taxonomy_custom_fields' ), 10, 2 );
		add_filter( 'manage_edit-levels_membership_type_columns', array( $this, 'add_levels_membership_type_column_header' ), 10, 3 );
		add_action( 'manage_levels_membership_type_custom_column', array( $this, 'add_levels_membership_type_column_content' ), 10, 3 );
	}

	/**
	 * Add Metabox to CPT Levels
	 */
	public function mmtms_register_levels_taxonomies() {
		register_taxonomy(
			'levels_membership_type',
			array( 'mmtms-levels' ),
			array(
				'hierarchical' => true,
				'label'        => esc_html__( 'Membership Type', 'momo-membership' ),
				'show_ui'      => true,
				'query_var'    => true,
			)
		);
	}

	/**
	 * Add Custom Fields to levels_membership_type
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	public function levels_membership_type_taxonomy_custom_fields( $taxonomy ) {

	}

	/**
	 * Save levels_membership_type taxonomy
	 *
	 * @param int $term_id Term ID.
	 * @param int $term_taxonomy_id Term taxonomy ID.
	 */
	public function save_levels_membership_type_taxonomy_custom_fields( $term_id, $term_taxonomy_id ) {

	}

	/**
	 * Add Column Header to levels_membership_type
	 *
	 * @param string[] $columns The column header labels keyed by column ID.
	 */
	public function add_levels_membership_type_column_header( $columns ) {

	}
	/**
	 * Add Content to levels_membership_type header
	 *
	 * @param string $content Blank string.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id Term ID.
	 */
	public function add_levels_membership_type_column_content( $content, $column_name, $term_id ) {

	}

}
new Mmtms_Admin_Taxonomy();
