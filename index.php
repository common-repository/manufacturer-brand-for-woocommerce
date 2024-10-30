<?php
/*
 Plugin Name: Manufacturer Brand for WooCommerce
 Plugin URI:  https://profiles.wordpress.org/walexconcepts/
 Description: This plugin extends the product details for WooCommerce to embed manufacture name so-called brand.
 Version:     1.0
 Author:      Adewale Emmanuel Awodeyi
 Author URI:  https://www.walexconcepts.com/
 License:     GPLv2+
 */
if ( ! defined( 'ABSPATH' ) ) exit; 
define('WOO_MANUFACTURER_BRAND_PATH', dirname(__FILE__) . '/'); //plugin directory path
$installpath = explode('plugins', WOO_MANUFACTURER_BRAND_PATH);
define('WOO_MANUFACTURER_BRAND_INSTALLATION_PATH', dirname($installpath[0]) . '/'); 




function woo_manufacturer_brand_call_after_install(){
$path = plugin_dir_path( __FILE__ ) . 'system/woo_manufacturer_brand.sql';
$sql = file_get_contents($path);
require_once( WOO_MANUFACTURER_BRAND_INSTALLATION_PATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
}
register_activation_hook( __FILE__, 'woo_manufacturer_brand_call_after_install' );






function woo_manufacturer_brand_call_after_uninstall() {
global $wpdb;
$wpdb->query( 'DROP TABLE IF EXISTS woo_manufacturer_brand' );
$wpdb->query( 'DROP TABLE IF EXISTS woo_manufacturer_brand_item' );
}
register_uninstall_hook( __FILE__, 'woo_manufacturer_brand_call_after_uninstall' );






function woo_manufacturer_brand_my_custom_upload_directory( $param ) {
$folder = '/manufacturer-brand-for-woocommerce/admin/images/manuf';
$param['path'] = WP_PLUGIN_DIR . $folder;
$param['url'] = WP_PLUGIN_URL . $folder;
return $param;
}	
add_filter('upload_dir', 'woo_manufacturer_brand_my_custom_upload_directory');



function woo_manufacturer_brand_filename_rename($filename) {
global $wpdb;
$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM woo_manufacturer_brand WHERE varimagepath = %s", $filename ) );
if($result){
foreach($result as $row){
$manufactureid = sanitize_text_field($row->intmanufactureid);
				
}
}
$ext = explode( '.', $filename );
$fl_db=$ext[0].$manufactureid.".".$ext[1];   
return $fl_db;
}
add_filter('sanitize_file_name', 'woo_manufacturer_brand_filename_rename', 10);





function woo_manufacturer_brand_selectbox_form($post_id) {
	global $wpdb;
	global $post_id;
	$product = wc_get_product($post_id);
	$itemID = $product->id; 
	$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM woo_manufacturer_brand_item WHERE item_id = %d", $itemID ) );		
	foreach ( $result as $row ) {
	$value = $row->brand_name;	
	}		
    $remaining_times = array();
    $required = true;
	$default[''] = sprintf(esc_html__( '%s', 'manufacturer-brand-for-woocommerce' ),esc_html( $value ));
	
	$result = $wpdb->get_results( $wpdb->prepare( "SELECT varname FROM woo_manufacturer_brand" ) );		
	foreach ( $result as $row ) {
	$value = $row->varname;	
	$remaining_times[$value] = $value;
	}	
    $options = array_merge( $default, $remaining_times );
    
	$args = array(
		'type'          => 'select',
		'id' => 'manufacturer',
		'name' => 'manufacturer',
        'wrapper_class' => '',
		'desc_tip' => true,
		'description' => __('Enter your brand here', 'woocommerce'),
        'label'         => __( 'Brand(Select your options)', 'woocommerce' ),
        'required'      => $required,  
        'options'       => $options,
		
  );
	woocommerce_wp_select( $args );
}
add_action( 'woocommerce_product_options_general_product_data', 'woo_manufacturer_brand_selectbox_form', 10, 1 );





function woo_manufacturer_brand_form_post($post_id) {
	    require_once( WOO_MANUFACTURER_BRAND_INSTALLATION_PATH . 'wp-includes/pluggable.php' );
		global $wpdb;
		global $post;
		global $post_id;
		$product = wc_get_product($post_id);
		$itemID = $product->id;
		$itemNAME = $product->name;
		$nonce = wp_create_nonce( 'get-manufacturer_'.$itemID );
		if ( wp_verify_nonce( $nonce, 'get-manufacturer_'.$itemID ) ) {
		$manufacturer_name = isset( $_POST[ 'manufacturer' ] ) ? sanitize_text_field( $_POST[ 'manufacturer' ] ) : '';
        }else{
			exit; 
		}
		if (empty($manufacturer_name))
            return false;
		if ($post->post_status == 'draft' && $post->post_type == 'product') {
		$wpdb->query( $wpdb->prepare( "INSERT INTO woo_manufacturer_brand_item (item_id, item_name, brand_name) VALUES ( %d, %s, %s)", $itemID, $itemNAME, $manufacturer_name ) );
		}else {
		$query = $wpdb->prepare( 'SELECT item_id FROM woo_manufacturer_brand_item WHERE item_id = %d', $itemID );
		$var = $wpdb->get_var( $query );
		if ($var){
		$wpdb->query($wpdb->prepare( "UPDATE woo_manufacturer_brand_item SET  
						item_id = %d,
						item_name = %s,
						brand_name = %s where item_id = %d", $itemID, $itemNAME, $manufacturer_name, $itemID ));
			}
		 }
		 
		 
    }
add_action('save_post_product','woo_manufacturer_brand_form_post');



function woo_manufacturer_brand_product_summary() {
	    global $wpdb;
		$itemID = get_the_ID();
		$query = $wpdb->prepare( 'SELECT item_id FROM woo_manufacturer_brand_item WHERE item_id = %d', $itemID );
		$detail = $wpdb->get_var( $query );
		require_once( dirname( __FILE__ ) . '/item_detail.php' );

}

add_action( 'woocommerce_single_product_summary', 'woo_manufacturer_brand_product_summary', 15 );




function woo_manufacturer_brand_new_custom_product_tab( $tabs ) {

$tabs['brand_tab'] = array(
'title' => __( 'Manufacturer', 'woocommerce' ),
'priority' => 44,
'callback' => 'woo_manufacturer_brand_custom_product_tab_content'
);

return $tabs;
}
function woo_manufacturer_brand_custom_product_tab_content() {
global $wpdb;
$itemID = get_the_ID();
$query = $wpdb->prepare( 'SELECT item_id FROM woo_manufacturer_brand_item WHERE item_id = %d', $itemID );
$detail = $wpdb->get_var( $query );
require plugin_dir_path( __FILE__ ) . 'item_tab.php';
}
add_filter( 'woocommerce_product_tabs', 'woo_manufacturer_brand_new_custom_product_tab' );



function woo_manufacturer_brand_reorder_tabs( $tabs ) {
    $tabs['reviews']['priority'] = 5;           
    $tabs['description']['priority'] = 10;      
    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_manufacturer_brand_reorder_tabs', 98 );


function woo_manufacturer_brand_allowed_html() {
	$allowed_tags = array(
		'a' => array(
			'class' => array(),
			'href'  => array(),
			'rel'   => array(),
			'title' => array(),
		),
		'span' => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		
	);
return $allowed_tags;
}



function woo_manufacturer_brand_delete_item( $post_id ) {
    global $wpdb;
    global $post_id;
	$product = wc_get_product($post_id);
	$itemID = $product->id; 
    $query = $wpdb->prepare( 'SELECT item_id FROM woo_manufacturer_brand_item WHERE item_id = %d', $itemID );
    $var = $wpdb->get_var( $query );
    if ( $var ) {
        $query2 = $wpdb->prepare( 'DELETE FROM woo_manufacturer_brand_item WHERE item_id = %d', $itemID );
        $wpdb->query( $query2 );
    }
}
add_action( 'delete_post', 'woo_manufacturer_brand_delete_item', 10 );




function woo_manufacturer_brand_admin_menu() {
    add_menu_page( 'Woocommerce Brand', 'Woocommerce Brand', null, 'administrator_woo_manufacturer_brand', '', plugin_dir_url( __FILE__ ) . 'adminicon.png');
	add_submenu_page( 'administrator_woo_manufacturer_brand', 'Add New Brand', 'Add New Brand', 'manage_options', 'newbrand_woo_manufacturer_brand', 'woo_manufacturer_brand_newbrand' );
	add_submenu_page( 'administrator_woo_manufacturer_brand', 'Brand Manager', 'Brand Manager', 'manage_options', 'brandmanader_woo_manufacturer_brand', 'woo_manufacturer_brand_brandmanader' );
	add_submenu_page( 'administrator_woo_manufacturer_brand', __( 'Help', 'administrator_woo_manufacturer_brand' ), __( 'Help', 'administrator_woo_manufacturer_brand' ), 'manage_options', 'help_woo_manufacturer_brand', 'woo_manufacturer_brand_help');
	wp_enqueue_style( 'formstyle', plugins_url( 'admin/css/formstyle.css', __FILE__ ));
	wp_enqueue_script('menufjs', plugins_url('admin/js/menuf.js', __FILE__ ));

}

function woo_manufacturer_brand_newbrand(){
	global $wpdb;
	require plugin_dir_path( __FILE__ ) . 'admin/system/msg.inc.php';
	require plugin_dir_path( __FILE__ ) . 'admin/newbrand.php';
}
function woo_manufacturer_brand_brandmanader(){
	global $wpdb;
	require plugin_dir_path( __FILE__ ) . 'admin/system/msg.inc.php';
	require plugin_dir_path( __FILE__ ) . 'admin/brandmanader.php';
}
function woo_manufacturer_brand_help(){
	require plugin_dir_path( __FILE__ ) . 'admin/brand_help.php';
}
add_action('admin_menu', 'woo_manufacturer_brand_admin_menu');










function woo_manufacturer_brand_settings_link( $links){
	$links[] = '<a href="admin.php?page=help_woo_manufacturer_brand">Help</a>' ;		
	$links[] = '<a target="_blank" href="https://walexconcepts.com/index.php?page=item&id=21">Go Premium!</a>' ;
	return $links;
}
add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'woo_manufacturer_brand_settings_link');






