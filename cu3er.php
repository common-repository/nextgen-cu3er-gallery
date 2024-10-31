<?php
/*
Plugin Name: NextGen Cu3er Gallery
Plugin URI: http://wordpress.org/extend/plugins/nextgen-cu3er-gallery/
Description: Create a simple but fancy Cu3er based Gallery
Version:  0.1
Author: SchattenMann
*/

//error_reporting(E_ALL);
//add_action("widgets_init", array('Cu3er', 'register'));

define( 'CU3ER_PLUGIN_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );

class Cu3er extends WP_Widget {
	
	/** constructor */
    function Cu3er() {
		parent::WP_Widget(false, $name = 'NextGen Cu3er Gallery');	
    }
  
  function update ($new_instance, $old_instance) {				
        $instance = $old_instance;
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['galID'] = strip_tags($new_instance['galID']);
        return $instance;
    }
  
  function form ($instance){
	  //$data = get_option('Cu3er');
	  $width = esc_attr($instance['width']);
	  $height = esc_attr($instance['height']);
	  $galID = esc_attr($instance['galID']);
	  global $wpdb;
	  $tables = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY 'name' ASC ");
	  ?>
	<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" style="padding: 3px; width: 45px;" value="<?php echo $width; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" style="padding: 3px; width: 45px;" value="<?php echo $height; ?>" /></p>
	 <p><label for="<?php echo $this->get_field_id('galID'); ?>">Select Gallery:</label>
		<select size="1" name="<?php echo $this->get_field_name('galID'); ?>" id="<?php echo $this->get_field_id('galID'); ?>" class="widefat">
		<?
		if($tables) {
			foreach($tables as $table) {
			echo '<option value="'.$table->gid.'" ';
			if ($table->gid == $galID) echo "selected='selected' ";
			echo '>'.$table->name.'</option>'."\n\t"; 
			}
		}
		?>
		</select>
	</p>
	   <?php 
	}
	
  function widget($args, $instance){
	//echo '<!--before widget-->';
	//echo $args['before_widget'];
	//echo '<!--end before widget-->';
	//$data = get_option('Cu3er');
	$width = esc_attr($instance['width']);
	$height = esc_attr($instance['height']);
	$galID = esc_attr($instance['galID']);
   // echo $args['before_title'] . $data['width'] . $args['after_title'];
	
	/**************************************************/
	
	echo '<script type="text/javascript">
				var flashvars = {};
				flashvars.xml = "'.WP_PLUGIN_URL.'/'.CU3ER_PLUGIN_NAME.'/xml/cu3er.php?id='.$galID.'";
				flashvars.font = "'.WP_PLUGIN_URL.'/'.CU3ER_PLUGIN_NAME.'/font.swf";
				var attributes = {};
				attributes.wmode = "transparent";
				attributes.id = "slider";
				swfobject.embedSWF("'.WP_PLUGIN_URL.'/'.CU3ER_PLUGIN_NAME.'/swf/cu3er.swf", "cu3er-container-'.$galID.'", "'.$width.'", "'.$height.'", "9", "'.WP_PLUGIN_URL.'/'.CU3ER_PLUGIN_NAME.'/swf/expressInstall.swf", flashvars, attributes);
			</script>
			<div id="cu3er-container-'.$galID.'" style="height:'.$height.'px;outline:0 none;width:'.$width.'px;" ></div>';
	
	/**************************************************/
	//echo '<!--after widget-->';
    //echo $args['after_widget'];
	//echo '<!--end after widget-->';
  }
  
} /*end class*/

add_action('widgets_init', create_function('', 'return register_widget("Cu3er");'));

// [cu3er id="1" width="100" height="200" align="center"]
function cu3er_func($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'width' => '400',
		'height' => '200',
		'align' => ''
	), $atts));
	
	if($atts['id'] == ''){
		return '[Invalid NextGen Gallery]';
	}
	
	$aligns = array("center", "left", "right");
	if($atts['align'] != ''){
		if(!in_array($atts['align'], $aligns)){
			return '[Invalid Align Value]';
		}
	}

	return "<script type=\"text/javascript\">
				var flashvars = {};
				flashvars.xml = \"".WP_PLUGIN_URL."/".CU3ER_PLUGIN_NAME."/xml/cu3er.php?id={$id}\";
				flashvars.font = \"".WP_PLUGIN_URL."/".CU3ER_PLUGIN_NAME."/font.swf\";
				var attributes = {};
				attributes.wmode = \"transparent\";
				attributes.id = \"slider\";
				swfobject.embedSWF(\"".WP_PLUGIN_URL."/".CU3ER_PLUGIN_NAME."/swf/cu3er.swf\", \"cu3er-container-{$id}\", \"{$width}\", \"{$height}\", \"9\", \"".WP_PLUGIN_URL."/".CU3ER_PLUGIN_NAME."/swf/expressInstall.swf\", flashvars, attributes);
			</script>
			<div style=\"text-align:{$align}\"><div id=\"cu3er-container-{$id}\" style=\"height:{$height}px;outline:0 none;width:{$width}px;\" ></div></div>";
}

add_shortcode('cu3er', 'cu3er_func');

function cu3er_js() {

    /* The xhtml header code needed for gallery to work: */
	$cu3er = "
	<!-- begin cu3er scripts -->
	<script type=\"text/javascript\" src=\"".WP_PLUGIN_URL."/".CU3ER_PLUGIN_NAME."/js/swfobject.js\"></script>
	<!-- end cu3er scripts -->\n";
	
	/* Output $galleryscript as text for our web pages: */
	echo($cu3er);
}

add_action('wp_head', 'cu3er_js');

?>