<?php
/**
Plugin Name: Custom Post Type Filter
Author: Boone Gorges
Version: 0.1
Author URI: http://boonebgorges.com
**/

if ( !class_exists( 'CPT_Filter' ) ) :

class CPT_Filter {
	function __construct() {
		$this->cpt_filter();
	}
	
	function cpt_filter() {
		$this->includes();
		
	}
	
	function includes() {
		require_once( dirname(__FILE__) . '/includes/widgets.php' );
	}
	
	function get_post_types() {
		$all_post_types = get_post_types( false, false );
		
		$excluded_post_types = apply_filters( 'cptf_excluded_post_types', array(
			'attachment',
			'revision',
			'nav_menu_item'
		) );
		
		$types = array();
		foreach( $all_post_types as $name => $post_type ) {
			if ( !in_array( $name, $excluded_post_types ) )
				$types[$name] = isset( $post_type->labels->name ) ? $post_type->labels->name : $name;
		}
				
		return apply_filters( 'cptf_available_post_types', $types );
	}
	
	function get_taxonomies_for_post_types( $post_types ) {

		$excluded_taxonomies = apply_filters( 'cptf_excluded_taxonomies', array(
			'nav_menu',
			'link_category'
		) );

		// Get a list of unique taxonomies
		$taxonomies = array();
		foreach( (array)$post_types as $post_type => $post_type_label ) {
			$p_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach( $p_taxonomies as $p_tax => $tax_object ) {
				if ( !in_array( $p_tax, $excluded_taxonomies ) && !isset( $taxonomies[$p_tax] ) ) {
					$tax_label = isset( $tax_object->labels->name ) ? $tax_object->labels->name : $p_tax;
					
					// Get the taxonomy terms
					$tax_terms = get_terms( $p_tax );
					
					$taxonomies[$p_tax] = array( 
						'label' => apply_filters( 'cptf_tax_label', $tax_label, $p_tax ),
						'terms' => apply_filters( 'cptf_tax_terms', $tax_terms, $p_tax ) 
					);
					
					}
			}
		}
		
		
		return apply_filters( 'cptf_available_taxonomies', $taxonomies );
	}
}

endif;

$CPT_Filter = new CPT_Filter;


?>