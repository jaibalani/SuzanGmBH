<?php
ini_set('memory_limit','128M');
// Input 

$s_image = $_GET['image']; // Image url set in the URL. ex: thumbit.php?image=URL

$e_image = "error.jpg"; // If there is a problem using the file extension then load an error JPG.

$max_width = $_GET['width']; // Max thumbnail width.

$max_height =$_GET['height']; // Max thumbnail height.

$quality = 100; // Do not change this if you plan on using PNG images.



// Resizing and Output : Do not edit below this line unless you know what your doing.

if (preg_match("/.jpg/i","$s_image") or preg_match("/.jpeg/i","$s_image")) {



header('Content-type: image/jpeg');

list($width, $height) = getimagesize($s_image);

//$ratio = ($width > $height) ? $max_width/$width : $max_height/$height; 
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

imagejpeg($image_p, null, $quality); 

imagedestroy($image_p); 

}

elseif (preg_match("/.png/i", "$s_image")) {



header('Content-type: image/png');

list($width, $height) = getimagesize($s_image);

//$ratio = ($width > $height) ? $max_width/$width : $max_height/$height; 
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

imagepng($image_p); 

imagedestroy($image_p); 



}

elseif (preg_match("/.gif/i", "$s_image")) {



header('Content-type: image/gif');

list($width, $height) = getimagesize($s_image);

//$ratio = ($width > $height) ? $max_width/$width : $max_height/$height; 
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

imagegif($image_p, null, $quality);

imagedestroy($image_p); 



}

else {



// Show the error JPG.

header('Content-type: image/jpeg');

imagejpeg($e_image, null, $quality); 

imagedestroy($e_image); 



}



?>

