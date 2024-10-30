<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$page=sanitize_text_field($_REQUEST["page"]);
$current_url = esc_url(admin_url( "admin.php?page=".$page));
define('WOO_MANUFACTURER_BRAND_PATH', __FILE__ . '/'); 
$installpath = explode('plugins', WOO_MANUFACTURER_BRAND_PATH);
define('WOO_MANUFACTURER_BRAND_INSTALLATION_PATH', dirname($installpath[0]) . '/');  
require_once( WOO_MANUFACTURER_BRAND_INSTALLATION_PATH . 'wp-includes/pluggable.php' );

$nonce = sanitize_text_field($_REQUEST['_wpnonce']);



if(isset($_REQUEST['Submit']) && trim($_REQUEST['Submit']) == "Submit" && wp_verify_nonce( $nonce, 'submit_picture' )) {
$client=sanitize_text_field($_REQUEST['manuf']);
$varthpath = sanitize_file_name($_FILES['imagefile']['name']);
$desc=sanitize_text_field($_REQUEST['desc']);
$head=sanitize_text_field($_REQUEST['heading']);				
$dtadded=date('Y-m-d H:i:s');
$dtmodified=date('Y-m-d H:i:s');
							
if(($_FILES['imagefile']['size'] > 2000000))	{					
header("location:$current_url&msg=imgszbg");
die();
}else if(($_FILES['imagefile']['size'] <= 0))	{					
header("location:$current_url&msg=imgszup");
die();
} else {
$insert=$wpdb->query( $wpdb->prepare( "INSERT INTO woo_manufacturer_brand (varname, varimagepath, txtdesc, varheading, dtdateadded, dtlastmodified) VALUES ( %s, %s, %s, %s, %d, %d)", $client, $varthpath, $desc, $head, $dtadded, $dtmodified) );

				
$result = $wpdb->get_results($wpdb->prepare( 'SELECT * FROM woo_manufacturer_brand WHERE varimagepath = %s', $varthpath ));

if($result){
			foreach($result as $row){
				$manufid = sanitize_text_field($row->intmanufactureid);
				
			}
		}

if(isset($_REQUEST['uploading']) && trim($_REQUEST['uploading']) == "imageupload" && $_FILES['imagefile']['name']!="")	{
if ( ! function_exists( 'wp_handle_upload' ) ) {
	require_once( WOO_MANUFACTURER_BRAND_INSTALLATION_PATH . 'wp-admin/includes/file.php' );
}
$targetpath = plugin_dir_path( __FILE__ ) . '/images/manuf/';


$ext=explode(".",$varthpath);
if($ext[1]=="jpg" || $ext[1]=="gif" || $ext[1]=="jpeg" || $ext[1]=="png" || $ext[1]=="bmp" || $ext[1]=="wbmp" || $ext[1]=="JPEG" || $ext[1]=="JPG")	{
				
if($_FILES['imagefile']['size'] <= 2000000)	{
$ext=explode(".",$varthpath);
$filename=$targetpath.$ext[0].$manufid.".".$ext[1];


if(file_exists($filename))	{
				chmod($filename, 0777);
				unlink($filename);
}
$fl_db=$ext[0].$manufid.".".$ext[1];

$upload_overrides = array(
'test_form' => false
);

if (wp_handle_upload( $_FILES['imagefile'], $upload_overrides )) {
$result = $wpdb->query($wpdb->prepare( "UPDATE woo_manufacturer_brand SET  
varimagepath = %s where intmanufactureid = %d", $fl_db, $manufid ));
							
}					
					
}
}
}
header("location:$current_url&msg=add");
die();
}
}

?>

<br>
<br>
<div style="float:none" class="well center-block col-md-7"> 
  
	
<form action="" method="post" enctype="multipart/form-data" name="manufa">
		
<span style="color:red">
<?php 
if(isset($_REQUEST['msg'])){
	echo esc_html(sanitize_text_field($mess[$_REQUEST['msg']])); 
}
?>
</span>
<br>
 <h3>Add New Brand... (* All fields are Required)</h3>
<br>        
<label>*Main Heading </label>
<input style="width:100%" name="heading" type="text" id="heading" value="<?php echo esc_html($p_head);?>" />
<br>
          
<label>*Name</label>
<input style="width:100%" name="manuf" type="text" id="manuf" value="<?php echo esc_html($p_manuf);?>" />
<br>
<label>*Image</label>
<input style="width:100%" name="imagefile" type="file" id="imagefile" />
<br>
<label>*Description</label><br>
<textarea style="width:100%;max-width:100%" name="desc" cols="#30" rows="10" id="desc"><?php echo esc_html($p_desc);?></textarea>
<br>
<input type="hidden" name="uploading" value="imageupload" />
<br>
<input name="Submit" type="submit" class="btn" id="Submit" value="<?php echo ($action==2) ? "Update":"Submit"; ?>"  onclick="return check();"/>
<br>

<?php wp_nonce_field( 'submit_picture' ); ?>
</form>

    
</div>	
  



