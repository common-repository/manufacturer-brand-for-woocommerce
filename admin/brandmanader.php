<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

$page=sanitize_text_field($_REQUEST["page"]);
$current_url = esc_url(admin_url( "admin.php?page=".$page));




if(isset($_REQUEST['a']) && trim($_REQUEST['a'])==3)
{
	if(isset($_REQUEST['intid']) && trim($_REQUEST['intid']!=""))
	{
		$id = sanitize_text_field($_REQUEST['intid']);
		$result = $wpdb->get_results($wpdb->prepare('SELECT * FROM woo_manufacturer_brand WHERE intmanufactureid = %d', $id));
		if($result){
			foreach($result as $row){
				$brand_name = sanitize_text_field($row->varname );
				$targetpath = plugin_dir_path( __FILE__ ) . '/images/manuf/';
				$th_file = sanitize_text_field($row->varimagepath);
				$filename1=$targetpath.$th_file;
					chmod($filename1, 0777);
					unlink($filename1);
			}
		$sql_del = $wpdb->prepare( 'DELETE FROM woo_manufacturer_brand_item WHERE brand_name = %s', $brand_name );
        $wpdb->query( $sql_del );
		}
		$query = $wpdb->prepare( 'SELECT intmanufactureid FROM woo_manufacturer_brand WHERE intmanufactureid = %d', $id );
		$var = $wpdb->get_var( $query );
		if ( $var ) {
        $sql_del = $wpdb->prepare( 'DELETE FROM woo_manufacturer_brand WHERE intmanufactureid = %d', $id );
        $wpdb->query( $sql_del );
		}
		header("location:$current_url&msg=del");
		die();
	}
}
?>




  

<style>
    table, td {
		width:60%;
        border: 1px solid black;
        border-collapse: collapse;
        padding: 25px;
     }
</style>


<br /><br />
<table align="center">        
      <tr class="bg1">
        <td colspan="2" align="left"><strong>Brands Manager</strong>...</td>
		<td colspan="2" align="center"><a href="<?php echo esc_url(admin_url('admin.php?page=newbrand_woo_manufacturer_brand'));?>" class="aa">[NEW]</a></td>

      </tr>
      
      <tr>
		<td align="center"><strong>Name</strong></td>
        <td align="center"><strong>Description</strong></td>
        <td align="center"><strong>Image</strong></td>
        <td align="center"><strong>Delete</strong></td>
      </tr>
      <?php	  
	  	$query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) as num FROM woo_manufacturer_brand"));
		foreach($query as $row)	{
			$total_pages = $row->num;
		}
		$targetpage = $current_url;
		$limit = 8;
		$page = (isset($_GET['paged'])) ? (int)sanitize_text_field($_GET['paged']) : 0;
		if($page) 
		$start = ($page - 1) * $limit; 			
		else
		$start = 0;
			
		$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM woo_manufacturer_brand ORDER BY `varname` ASC LIMIT %d, %d", $start, $limit ));
	    
	    if($result)	{
		foreach($result as $row)	{
		$varname = sanitize_text_field($row->varname);
		$txtdesc = sanitize_text_field($row->txtdesc);
		$menufimage = sanitize_text_field($row->varimagepath);
		$id = sanitize_text_field($row->intmanufactureid);
		?>
      <tr>
        <td align="center"><?php echo esc_html($varname);?></td>
        <td align="center">
		<?php 
		$desc= $txtdesc;
		if(strlen($desc)>10){
			$com=substr($desc,0,10);
			echo esc_html($com."....");
		}else{echo esc_html($desc);}
		?>
		</td>
		<td align="center" width="">
		<img title="'<?php echo esc_html($varname);?>'" src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . 'images/manuf/'.$menufimage.''); ?>"
         width="100" height="100" border="0" />
		</td>
		<td align="center" width="">
               <a Title="Click here to Delete" class="link"
                  href="<?php echo esc_html($current_url); ?>&a=3&amp;intid=<?php echo esc_html($id);?>"
                   onClick="return confirm('Are you sure to delete this record ?');">
                   <img src="<?php echo esc_url(plugin_dir_url( __FILE__ ) . 'images/delete.bmp'); ?>"
                   border="0" />
               </a>
        </td>
      </tr>
      <?php }}?>
      <tr>  
			<td colspan="4">
			<div align="left" class="pagination">
			<div class="results">
			<?php
			$adjacents = 1;
			if ($page == 0) $page = 1;					
			$prev = $page - 1;							
			$next = $page + 1;							
			$lastpage = ceil($total_pages/$limit);		
			$lpm1 = $lastpage - 1;						
			$pagination = "";
			if($lastpage > 1)
			{	
			$pagination .= "<div class=\"pagination\">";
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage&paged=$prev\">&laquo; previous</a>";
			else
				$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
				
			if ($lastpage < 7 + ($adjacents * 2))	
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&paged=$counter\">$counter</a>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	
			{
				
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage&paged=$counter\">$counter</a>";					
					}
					$pagination .= "<span class=\"elipses\">...</span>";
					$pagination.= "<a href=\"$targetpage&paged=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage&paged=$lastpage\">$lastpage</a>";		
				}
				
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<a href=\"$targetpage&paged=1\">1</a>";
					$pagination.= "<a href=\"$targetpage&paged=2\">2</a>";
					$pagination .= "<span class=\"elipses\">...</span>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage&paged=$counter\">$counter</a>";					
					}
					$pagination .= "<span class=\"elipses\">...</span>";
					$pagination.= "<a href=\"$targetpage&paged=$lpm1\">$lpm1</a>";
					$pagination.= "<a href=\"$targetpage&paged=$lastpage\">$lastpage</a>";		
				}
				
				else
				{
					$pagination.= "<a href=\"$targetpage&paged=1\">1</a>";
					$pagination.= "<a href=\"$targetpage&paged=2\">2</a>";
					$pagination .= "<span class=\"elipses\">...</span>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else
							$pagination.= "<a href=\"$targetpage&paged=$counter\">$counter</a>";					
					}
				}
			}
			
			if ($page < $counter - 1) 
				$pagination.= "<a href=\"$targetpage&paged=$next\">next &raquo;</a>";
			else
				$pagination.= "<span class=\"disabled\">next &raquo;</span>";
			$pagination.= "</div>\n";		
			}
			?>

            <?php 
			$allowed_html = woo_manufacturer_brand_allowed_html();
			echo wp_kses($pagination, $allowed_html);	
			?>
			</div>
			</div>
			
			</td>
			</tr>
    </table>
 
  


