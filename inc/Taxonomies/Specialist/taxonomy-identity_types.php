<?php
namespace DrPlus\Backend\Taxonomies;

use DrPlus\Utils;

if( !defined( 'ABSPATH' ) ) exit;

class IdentityTypes {
	public static function add() {
		$labels = [
			'name'			=> __( 'Identity types', 'drplus' ),
			'singular_name'	=> __( 'Identity type', 'drplus' ),
			'search_items'	=> __( 'Search types', 'drplus' ),
			'all_items'		=> __( 'All types', 'drplus' ),
			'edit_item'		=> __( 'Edit type', 'drplus' ),
			'update_item'	=> __( 'Update type', 'drplus' ),
			'add_new_item'	=> __( 'Add New type', 'drplus' ),
			'new_item_name'	=> __( 'New type Name', 'drplus' ),
			'menu_name'		=> __( 'Identity types', 'drplus' ),
			'not_found'		=> __( 'No type found.', 'drplus' ),
		];
		$args = [
			'labels'				=> $labels,
			'public'				=> false,
			'publicly_queryable'	=> false,
			'show_in_rest'			=> false,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> true,
			'show_in_quick_edit'	=> false,
			'hierarchical'			=> false,
			'rewrite'				=> false,
			'query_var'				=> false,
		];

		register_taxonomy( 'identity_type', [], $args );
	}

	public static function custom_columns( $columns ) {
		$columns = Utils::unset( $columns, ['slug', 'posts'] );
		return $columns;
	}
}
IdentityTypes::add();

if( is_admin() ) {
	add_filter( "manage_edit-identity_type_columns", [IdentityTypes::class, 'custom_columns'] );
}