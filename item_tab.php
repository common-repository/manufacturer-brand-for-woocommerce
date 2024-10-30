<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

if( $detail && !empty($detail) ) {
$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM woo_manufacturer_brand_item where item_id = %d", $itemID ) );		
foreach ( $result as $row ) {
$brand_name = $row->brand_name;
}
$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM woo_manufacturer_brand where varname = %s", $brand_name ) );		
foreach ( $result as $row ) {
$dec = $row->txtdesc;
$brand_image = $row->varimagepath;			
}


		
?>
<div align="center">
<h2><?php echo esc_html($brand_name);?></h2>
<p><?php echo esc_html($dec); ?></p>
<img align="center" src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . "admin/images/manuf/$brand_image"); ?>";
</div>
<?php
}