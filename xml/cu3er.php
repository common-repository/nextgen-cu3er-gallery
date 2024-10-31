<?

require_once ('../../../../wp-config.php');
require_once ('../../../../wp-includes/wp-db.php');
require_once ('../../nextgen-gallery/lib/core.php');
require_once ('../../nextgen-gallery/lib/ngg-db.php');
require_once ('../../nextgen-gallery-custom-fields/ngg-custom-fields.php');

$slides = "";
$cu3er = "";
$dir = "cu3er";

function effect(){
srand((double)microtime()*1234567);
$effects = array(
'num="3" slicing="vertical" direction="down" cube_color="0xFFFFFF"',
'num="4" direction="right" shader="flat" cube_color="0xFFFFFF"',
'num="6" slicing="vertical" direction="up" shader="flat" delay="0.05" z_multiplier="4" cube_color="0xFFFFFF"',
'num="4" direction="right" shader="phong" cube_color="0xFFFFFF"');
return $effects[mt_rand(0,count($effects)-1)];
}

/* $folder = scandir($dir);
foreach($folder as $file){
if(strpos(strtolower($file),".jpg")>0){
$slides[]= '<slide><url>/'.$dir.'/'.$file.'</url></slide><transition '.effect().' />'."\n";
}
} */

$pieces = explode("?", $_SERVER['REQUEST_URI']);

parse_str($pieces[1]);

$galID = empty($id) ? 1 : $id;

//die($galID);

$picturelist = nggdb::get_gallery($galID);

$i = 0;
		
// get all picture with this galleryid
if ( is_array($picturelist) ) {

	foreach ($picturelist as $key => $picture) {
	
		if($picture->exclude != 1){
			
			if(function_exists('nggcf_get_field')){
				$url = nggcf_get_field($key, 'url');
			}else{
				$url = '';
			}
		
			$slides[$i] = '<slide>';
			$slides[$i].= '<url>'.$picture->imageURL.'</url>';
			if($url != ''){
				$slides[$i].= '<link>'.$url.'</link>';
			}
			if($picture->alttext != '' || $picture->description != ''){
				$slides[$i].= '<description>';
				if($url != ''){
					$slides[$i].= '<link>'.$url.'</link>';
				}
				if($picture->alttext != ''){
					$slides[$i].= '<heading>'.$picture->alttext.'</heading>';
				}
				if($picture->description != ''){
					$slides[$i].= '<paragraph>'.$picture->description.'</paragraph>';
				}
				$slides[$i].= '</description>';
			}
			$slides[$i].= '</slide>';
			$slides[$i].= '<transition '.effect().'/>'."\n";
			
			$i = $i + 1;
		}
		
	}
}

shuffle($slides);
foreach($slides as $slide){$cu3er.= $slide;}

header("Content-Type: text/xml");
echo('<?xml version="1.0" encoding="utf-8" ?>
<cu3er>
<settings>
<auto_play>
  <defaults symbol="circular"/>
  <tweenIn />
  <tweenOut />
  <tweenOver />
</auto_play>

<prev_button>
	<defaults round_corners="5,5,5,5"/>
	<tweenOver tint="0xFFFFFF" scaleX="1.1" scaleY="1.1"/>
	<tweenOut tint="0x000000" />
</prev_button>

<prev_symbol>
	<defaults type="1"/>
	<tweenOver tint="0x000000" />			
</prev_symbol>

<next_button>
	<defaults round_corners="5,5,5,5"/>			
	<tweenOver tint="0xFFFFFF"  scaleX="1.1" scaleY="1.1"/>
	<tweenOut tint="0x000000" />
</next_button>

<next_symbol>
	<defaults type="1"/>
	<tweenOver tint="0x000000" />
</next_symbol>	
	

<description>
  <defaults round_corners="0,0,5,5" heading_font="Georgia" paragraph_font="Tahoma"/>
  <tweenIn height="65" y="285" alpha="0.25"/>
  <tweenOut />
  <tweenOver />
</description>


</settings>

<slides>
'.$cu3er.'
</slides>
</cu3er>');
?> 