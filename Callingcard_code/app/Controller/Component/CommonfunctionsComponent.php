<?php
class CommonfunctionsComponent extends Component {

	public $components = array('Session');

	public function cropImageFromCenter($img_path = '', $save_path='', $widthheight=50)
    {
        // if (!is_resource($img_path)) {
        //     throw new RuntimeException('No image set');
        // }

    	$size = getimagesize($img_path);
    	$iwidth = round($size[0]/2);
    	$iheight = round($size[1]/2);

    	$wh = round($widthheight/2);

    	$x1 = $iwidth - $wh;
    	$y1 = $iheight - $wh;
    	$x2 = $iwidth + $wh;
    	$y2 = $iheight + $wh;    	

        $width = $x2 - $x1;
        $height = $y2 - $y1;
        
        $temp = imagecreatetruecolor($width, $height);
        
        if (preg_match("/.jpg/i","$img_path") or preg_match("/.jpeg/i","$img_path")) {
			$image = imagecreatefromjpeg($img_path); 
			imagecopy($temp, $image, 0, 0, $x1, $y1, $width, $height);
        	imagejpeg($temp, $save_path);

        } elseif (preg_match("/.png/i", "$img_path")) {
        	$image = imagecreatefrompng($img_path); 
			imagecopy($temp, $image, 0, 0, $x1, $y1, $width, $height);
        	imagepng($temp, $save_path);

        } elseif (preg_match("/.gif/i", "$img_path")) {
        	$image = imagecreatefromgif($img_path); 
			imagecopy($temp, $image, 0, 0, $x1, $y1, $width, $height);
        	imagegif($temp, $save_path);
        }

    }
	
	public function create_thumb($image_path,$save_path,$width_height=100, $exheight=0){
		$s_image = $image_path; // Image url set in the URL. ex: thumbit.php?image=URL
		//prd($image_path);
		$e_image = "error.jpg"; // If there is a problem using the file extension then load an error JPG.
		if($exheight > 0){
			//case when we want to create desired width/height image
			$max_width = $width_height; // Max thumbnail width.
			$max_height = $exheight; // Max thumbnail height.
		} else {
			//crop image by fix width/height
			$max_width = $width_height; // Max thumbnail width.
			$max_height =$width_height; // Max thumbnail height.
		}
		$quality = 100; // Do not change this if you plan on using PNG images.
        
        $path_info = pathinfo($s_image);



		//if (preg_match("/.jpg/i","$s_image") or preg_match("/.jpeg/i","$s_image")) 
		if($path_info['extension'] == 'jpg' ||  $path_info['extension'] == 'jpeg')
		{
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ; 
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) { 
			$new_width = $width * $ratio; 
			$new_height = $height * $ratio; 
			} else {
			$new_width = $width; 
			$new_height = $height;
			} 
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = imagecreatefromjpeg($s_image); 

			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagejpeg($image_p, $save_path, $quality); 
			imagedestroy($image_p); 
			
		} 
		//elseif (preg_match("/.png/i", "$s_image")) {
		elseif($path_info['extension'] == 'png')
		{	
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ; 
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) { 
			$new_width = $width * $ratio; 
			$new_height = $height * $ratio; 
			} else {
			$new_width = $width; 
			$new_height = $height;
			} 
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$background = imagecolorallocate($image_p, 0, 0, 0);
			
			$image = imagecreatefrompng($s_image); 
			
			if(!@$_GET['flag']==1){
			imagecolortransparent($image_p,$background) ;
			}
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagepng($image_p,$save_path); 
			imagedestroy($image_p); 
			
		} 
		//elseif (preg_match("/.gif/i", "$s_image")) {
		elseif($path_info['extension'] == 'gif'){
			list($width, $height) = getimagesize($s_image);
			$ratiow = $width/$max_width ; 
			$ratioh = $height/$max_height;
			$ratio = ($ratiow > $ratioh) ? $max_width/$width : $max_height/$height;
			if($width > $max_width || $height > $max_height) { 
			$new_width = $width * $ratio; 
			$new_height = $height * $ratio; 
			} else {
			$new_width = $width; 
			$new_height = $height;
			} 
			$image_p = imagecreatetruecolor($new_width, $new_height);
			$image = imagecreatefromgif($s_image); 
			$bgc = imagecolorallocate ($image_p, 255, 255, 255);
				imagefilledrectangle ($image_p, 0, 0, $new_width, $new_height, $bgc);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			imagegif($image_p, $save_path, $quality);
			imagedestroy($image_p); 
			
		} else {
			header('Content-type: image/jpeg');
			imagejpeg($e_image, $save_path, $quality); 
			imagedestroy($e_image); 
		}
	}
	
	function resize_with_crop($source_path='', $destination_path='', $new_image_width=200, $new_image_height=150 ){
		list( $source_width, $source_height, $source_type ) = getimagesize( $source_path );
		
		switch ( $source_type )
		{
		case IMAGETYPE_GIF:
		  $source_gdim = imagecreatefromgif( $source_path );
		  break;
		
		case IMAGETYPE_JPEG:
		  $source_gdim = imagecreatefromjpeg( $source_path );
		  break;
		
		case IMAGETYPE_PNG:
		  $source_gdim = imagecreatefrompng( $source_path );
		  break;
		}
		
		$source_aspect_ratio = $source_width / $source_height;
		$desired_aspect_ratio = $new_image_width / $new_image_height;
		
		if ( $source_aspect_ratio > $desired_aspect_ratio )
		{
		// Triggered when source image is wider
		$temp_height = $new_image_height;
		$temp_width = ( int ) ( $new_image_height * $source_aspect_ratio );
		}
		else
		{
		// Triggered otherwise (i.e. source image is similar or taller)
		$temp_width = $new_image_width;
		$temp_height = ( int ) ( $new_image_width / $source_aspect_ratio );
		}
		
		// Resize the image into a temporary GD image
		$temp_gdim = imagecreatetruecolor( $temp_width, $temp_height );
		imagecopyresampled(
			$temp_gdim,
			$source_gdim,
			0, 0,
			0, 0,
			$temp_width, $temp_height,
			$source_width, $source_height
		);
		
		// Copy cropped region from temporary image into the desired GD image
		
		$x0 = ( $temp_width - $new_image_width ) / 2;
		$y0 = ( $temp_height - $new_image_height ) / 2;
		
		$desired_gdim = imagecreatetruecolor( $new_image_width, $new_image_height );
		imagecopy(
			$desired_gdim,
			$temp_gdim,
			0, 0,
			$x0, $y0,
			$new_image_width, $new_image_height
		);
		
		//save the image
		imagejpeg( $desired_gdim, $destination_path);
		
	}
	public function download($fullPath=NULL)
		{
		  //$fullPath = WWW_ROOT.'img/course/'.$attachment;
		  if(file_exists($fullPath))
		{
       		if($fd = fopen ($fullPath, "r")) {
					$fsize 			= filesize($fullPath);
					$path_parts = pathinfo($fullPath);
					$ext 				= strtolower($path_parts["extension"]);
					switch ($ext) {
						case "doc":
						header("Content-type: application/doc"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
						case "xls":
						header("Content-type: application/xls"); // add here more headers for diff. extensions
						header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
						break;
						default;
						header("Content-type: application/octet-stream");
						header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
					}			
					header("Content-length: $fsize");
					header("Cache-control: private"); //use this to open files directly
					while(!feof($fd)) {
						$buffer = fread($fd, 2048);
						echo $buffer;
					}
					fclose ($fd);
				}
		}
		else
		{
			echo "file not found";
		}
			exit;
	} 
	

	
}
