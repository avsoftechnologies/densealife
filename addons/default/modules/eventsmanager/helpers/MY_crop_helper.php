<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Events Manager image cropping helper
*
* @author       Tristan Jahier - tristan-jahier.fr
*/

// ------------------------------------------------------------------------

/**
* Crop an image
*/
function imageCrop($path, $name, $src, $src_disp_w, $src_disp_h, $x1, $y1, $x2, $y2) {
	
	$target_w = $target_h = 200;
	$jpeg_quality = 100;

	// Getting image information
	$image_info = getimagesize($src);
	$type = $image_info['mime'];
	$img_width = $image_info[0];
	$img_height = $image_info[1];

	// Calculating the ratios
	$ratio_w = (int)$img_width/$src_disp_w;
	$ratio_h = (int)$img_height/$src_disp_h;
	
	switch($type)
	{
		case 'image/jpeg':
			$extension = 'jpg';
			$img_r = imagecreatefromjpeg($src);
			$dst_r = imagecreatetruecolor($target_w,$target_h);

			$src_width = $x2 - $x1;
			$src_height = $y2 - $y1;

			imagecopyresampled($dst_r,$img_r,0,0,$x1*$ratio_w,$y1*$ratio_h,$target_w,$target_h,$src_width*$ratio_w,$src_height*$ratio_h);
			
			imagejpeg($dst_r, $path.$name.'.'.$extension, $jpeg_quality);
			imagedestroy($dst_r);
			imagedestroy($img_r);
			break;
		case 'image/png':
			$extension = 'png';
			$img_r = imagecreatefrompng($src);
			$dst_r = imagecreatetruecolor($target_w,$target_h);

			$src_width = $x2 - $x1;
			$src_height = $y2 - $y1;

			imagecopyresampled($dst_r,$img_r,0,0,$x1*$ratio_w,$y1*$ratio_h,$target_w,$target_h,$src_width*$ratio_w,$src_height*$ratio_h);
			
			imagepng($dst_r, $path.$name.'.'.$extension, 0);
			imagedestroy($dst_r);
			imagedestroy($img_r);
			break;
	}
}

?>