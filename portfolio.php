<?php
/*
Plugin Name: Timmy Gelles Portfolio
Plugin URI:  http://timmygelles.com
Description:Custom Content Type Portfolio
Version: 1.0
Author: Timmy Gelles
Author URI: mailto:timothygelles@gmail.com
License: GPL2
*/

// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'create_portfolio_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_portfolio_taxonomies() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
			'name' 					=> _x( 'Portfolio Types', 'taxonomy general name' ),
			'singular_name' 		=> _x( 'Portfolio Type', 'taxonomy singular name' ),
			'add_new' 				=> _x( 'Add New Portfolio Type', 'Portfolio Type'),
			'add_new_item' 			=> __( 'Add New Portfolio Type' ),
			'edit_item' 			=> __( 'Edit Portfolio Type' ),
			'new_item' 				=> __( 'New Portfolio Type' ),
			'view_item' 			=> __( 'View Portfolio Type' ),
			'search_items' 			=> __( 'Search Portfolio Types' ),
			'not_found' 			=> __( 'No Portfolio Type found' ),
			'not_found_in_trash' 	=> __( 'No Portfolio Type found in Trash' ),
		);

		$args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Portfolio Type'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'hierarchical' 		=> true,
			'show_tagcloud' 	=> false,
			'show_in_nav_menus' => false,
			'rewrite' 			=> array('slug' => 'Portfolio', 'with_front' => false ),
		 );
	register_taxonomy( 'portfolio_type', 'portfolio', $args );
}

function register_portfolio_posttype() {
	$labels = array(
			'name' 				=> _x( 'Portfolios', 'post type general name' ),
			'singular_name'		=> _x( 'Portfolio', 'post type singular name' ),
			'add_new' 			=> __( 'Add Portfolio' ),
			'add_new_item' 		=> __( 'Add Portfolio' ),
			'edit_item' 		=> __( 'Edit Portfolio' ),
			'new_item' 			=> __( 'New Portfolio' ),
			'view_item' 		=> __( 'View Portfolio' ),
			'search_items' 		=> __( 'Search Portfolio' ),
			'not_found' 		=> __( 'No Portfolio found' ),
			'not_found_in_trash'=> __( 'No Portfolio found in Trash' ),
			'parent_item_colon' => __( '' ),
			'menu_name'			=> __( 'Portfolios' )
		);

		//$taxonomies = array( 'exhibition_type' );
		
		$supports = array('title','revisions','thumbnail' );

		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Portfolio'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type' 	=> 'post',
			'has_archive' 		=> false,
			'hierarchical' 		=> true,
			'rewrite' 			=> array('slug' => 'portfolio', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			//'taxonomies'		=> $taxonomies,
			'show_in_nav_menus' => true
		 );
	register_post_type('portfolio',$post_type_args);
}
add_action('init', 'register_portfolio_posttype');

$portfolioinformation_5_metabox = array(
	'id' => 'portfolioinformation',
	'title' => 'Portfolio Information',
	'page' => array('portfolio'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

		array(
			'title' 		=> 'Portfolio Item Title',
			'desc' 			=> '',
			'id' 				=> 'ecpt_portfolio_title',
			'class' 			=> 'ecpt_portfolio_title',
			'type'  		=> 'text',
			'rich_editor' 	=> 0,
			'max' 			=> 0,
			'std' 			=> ''
			),

		array(
			'name' 			=> 'Overview',
			'desc' 			=> '',
			'id' 				=> 'ecpt_portfolio_overview',
			'class' 			=> 'ecpt_portfolio_overview',
			'type' 			=> 'textarea',
			'rich_editor' 	=> 1,			
			'max' 			=> 0,
			'std'			=> ''	
			),

		array(
			'name' 			=> 'Role',
			'desc' 			=> '',
			'id' 				=> 'ecpt_role',
			'class' 			=> 'ecpt_role',
			'type' 			=> 'text',
			'rich_editor' 	=> 0,			
			'max' 			=> 0,
			'std'			=> ''			
			),

		array(
			'name' 			=> 'Link',
			'desc' 			=> '',
			'id' 				=> 'ecpt_link',
			'class' 			=> 'ecpt_link',
			'type' 			=> 'text',
			'rich_editor' 	=> 0,			
			'max' 			=> 0,
			'std'			=> ''			
			),
		)
	);

add_action('admin_menu', 'ecpt_add_portfolioinformation_5_meta_box');

function ecpt_add_portfolioinformation_5_meta_box() {

	global $portfolioinformation_5_metabox;		

	foreach($portfolioinformation_5_metabox['page'] as $page) {
		add_meta_box($portfolioinformation_5_metabox['id'], $portfolioinformation_5_metabox['title'], 'ecpt_show_portfolioinformation_5_box', $page, 'normal', 'default', $portfolioinformation_5_metabox);
	}
}

//function to show meta boxes
function ecpt_show_portfolioinformation_5_box() {
global $post;
	global $portfolioinformation_5_metabox;
	global $ecpt_prefix;
	global $wp_version;

// Use nonce for verification
	echo '<input type="hidden" name="ecpt_portfolioinformation_5_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

	echo '<table class="form-table">';

	foreach ($portfolioinformation_5_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}

// Save data from meta box
add_action('save_post', 'ecpt_portfolioinformation_5_save');
function ecpt_portfolioinformation_5_save($post_id) {
	global $post;
	global $portfolioinformation_5_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_portfolioinformation_5_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_portfolioinformation_5_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($portfolioinformation_5_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

function define_portfolio_type_terms() {
	$terms = array(
		'0' => array( 'name' => 'Web','slug' => 'web'),
		'1' => array( 'name' => 'Project','slug' => 'project'),
		'2' => array( 'name' => 'Other','slug' => 'other'),
    	);
    return $terms;
}

function check_portfolio_type_terms(){

	//see if we already have populated any terms
	$terms = get_terms ('portfolio_type', array( 'hide_empty' => false ) );

	//if no terms then lets add our terms
	  if( empty( $terms ) ){
	$terms = array(
		'0' => array( 'name' => 'Web','slug' => 'web'),
		'1' => array( 'name' => 'Project','slug' => 'project'),
		'2' => array( 'name' => 'Other','slug' => 'other'),
    	);
        foreach( $terms as $term ){
            if( !term_exists( $term['name'], 'portfolio_type' ) ){
                wp_insert_term( $term['name'], 'portfolio_type', array( 'slug' => $term['slug'] ) );
            }
        }
    }

}

add_action ( 'init', 'check_portfolio_type_terms' );



add_filter( 'manage_edit-portfolio_columns', 'my_portfolio_columns' ) ;

function my_portfolio_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name' ),
		'portfolio' => __( 'Portfolio Type' ),
		'date' => __( 'Date' ),
	);

	return $columns;
}

add_action( 'manage_studyfields_posts_custom_column', 'my_manage_portfolio_columns', 10, 2 );

function my_manage_program_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'program_type' column. */

		case 'portfolio' :

			/* Get the program_types for the post. */
			$terms = get_the_terms( $post_id, 'portfolio_type' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'portfolio_type' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'portfolio_type', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Portfolios Available' );
			}

			break;
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

?>