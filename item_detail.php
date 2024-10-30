<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

if( $detail && !empty($detail) ) { 
$result = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM woo_manufacturer_brand_item where item_id = %d", $itemID ) );		
		foreach ( $result as $row ) {
		$brand_name = $row->brand_name;
		}



?>

Manufacturer : <?php echo esc_html($brand_name);?>



<?php 
} 



