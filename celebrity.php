<?php
/*
Plugin Name: Celebrity
Plugin URI: https://pankajbca.wordpress.com/
Description: An example plugin to demonstrate the basics of putting together a plugin in WordPress
Version: 0.1
Author: Pankaj Panchal
Author URI: https://pankajbca.wordpress.com/
License: GPL2
*/
?>
<?php
add_action('init', 'celebrity_register');

function celebrity_register() {

	$labels = array(
		'name' => _x('Celebrities', 'post type general name'),
		'singular_name' => _x('Celebrity', 'post type singular name'),
		'add_new' => _x('Add New', 'review'),
		'add_new_item' => __('Add New Celebrity'),
		'edit_item' => __('Edit Celebrity'),
		'new_item' => __('New Celebrity'),
		'view_item' => __('View Celebrity'),
		'search_items' => __('Search Celebrity'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-images-alt',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','thumbnail')
	  ); 

	register_post_type( 'celebrity' , $args );
}

// Custom Taxonomy

function add_console_taxonomies() {

	register_taxonomy('celebrity', 'celebrity', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Celebrity Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Celebrity-Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Celebrity-Categories' ),
			'all_items' => __( 'All Celebrity-Categories' ),
			'parent_item' => __( 'Parent Celebrity-Category' ),
			'parent_item_colon' => __( 'Parent Celebrity-Category:' ),
			'edit_item' => __( 'Edit Celebrity-Category' ),
			'update_item' => __( 'Update Celebrity-Category' ),
			'add_new_item' => __( 'Add New Celebrity-Category' ),
			'new_item_name' => __( 'New Celebrity-Category Name' ),
			'menu_name' => __( 'Celebrity Categories' ),
		),

		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'celebrity', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action( 'init', 'add_console_taxonomies', 0 );


add_action( 'admin_init', 'my_admin_samplepost' );
function my_admin_samplepost() {
    add_meta_box( 'samplepost_meta_box', 'Celebrity Details', 'display_celebrity_meta_box','celebrity', 'normal', 'high' );
}
function display_celebrity_meta_box( $celebrity ) {
    ?>
    <table width="100%" class="celebs_form">
        <tr>
            <td style="width: 25%">Gender</td>
            <td>
					<?php $gen = esc_html( get_post_meta( $celebrity->ID, 'gender', true ) );?>
					<input type="radio" name="meta[gender]" value="Male" <?php checked( $gen, 'Male' ); ?>  required >Male<br>
					<input type="radio" name="meta[gender]" value="Female" <?php checked( $gen, 'Female' ); ?> required >Female<br>
            </td>
        </tr>
		<tr>
            <td>Email </td>
            <td><input type="text"  name="meta[email]"  value="<?php echo esc_html( get_post_meta( $celebrity->ID, 'email', true ) );?>"  data-validation="email" />
            </td>
        </tr>
        <tr>
            <td>Phone </td>
            <td><input type="text"  name="meta[phone]"  value="<?php echo esc_html( get_post_meta( $celebrity->ID, 'phone', true ) );?>"  data-validation-length="max10" required  data-validation="number length"/>
            </td>
        </tr>
        <tr>
            <td>Education</td>
            <td>
					<?php $edu = esc_html( get_post_meta( $celebrity->ID, 'education', true ) );?>
					<select id="education" name="meta[education]" required >
						<option value="BCA" <?php selected( $edu, 'BCA' ); ?>> BCA </option>
						<option value="MCA" <?php selected( $edu, 'MCA' ); ?>> MCA </option>
						<option value="BSC" <?php selected( $edu, 'BSC' ); ?>> BSC </option>
						<option value="LLB" <?php selected( $edu, 'LLB' ); ?>> LLB</option>
						<option value="BBA" <?php selected( $edu, 'BBA' ); ?>> BBA </option>
						<option value="MBA" <?php selected( $edu, 'MBA' ); ?>>MBA</option>
					  </select>
            </td>
        </tr>
		<tr>
            <td>Description </td>
            <td><textarea   name="meta[description]"><?php echo esc_html( get_post_meta( $celebrity->ID, 'description', true ) );?></textarea>
            </td>
        </tr>
    </table>
<?php 
}
add_action( 'save_post', 'add_samplepost_fields', 10, 2 );
function add_samplepost_fields( $samplepost_id, $celebrity ) {
    if ( $celebrity->post_type == 'celebrity' ) {
        if ( isset( $_POST['meta'] ) ) {
            foreach( $_POST['meta'] as $key => $value ){
                update_post_meta( $samplepost_id, $key, $value );
            }
        }
    }
}

function wpdocs_enqueue_custom_admin_files() {
        wp_enqueue_style( 'custom_wp_admin_css',  plugins_url( '/css/celebrity.css', __FILE__ ) );
        wp_enqueue_script( 'custom_wp_admin_js',  plugins_url( '/js/jquery.form-validator.min.js', __FILE__ ) );
		wp_enqueue_script( 'custom_wp_admin_js_c',  plugins_url( '/js/celebrity.js', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_files' );


add_image_size( 'celeb-thumb', 320, 320 ); // Soft Crop Mode

/* Include Shortcode file */
include('/shortcode.php');
?>