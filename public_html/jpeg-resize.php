<?php
/* Image Resizer is bringing from Fero File Manager
 * Created by Luthfie
 * Luthfie@y7mail.com
 * Mime-Type: image/jpeg
 */

/* Handler for getting file */
if(!isset($_GET['file'])||!file_exists($_GET['file'])){
  header('Content-Type: text/plain');
  exit('cannot load the file');
}

/* filename variable */
$filename = (isset($_GET['file']))?$_GET['file']:'';

/* Setting image height ratio by streching image scale to image width */
$w = (empty($_GET['w']))?'150':$_GET['w'];
list($width_orig, $height_orig) = getimagesize($filename);
$ratio_orig = $width_orig/$height_orig;
if($width_orig>$height_orig){
  $width = $w;
  $height = $width/$ratio_orig;
}else{
  $height = $w;
  $width = $height*$ratio_orig;
}

/* Start process creating image */
$image_p = imagecreatetruecolor($width,$height);
$image = imagecreatefromjpeg($filename);

/* Copy image re-sample */
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

/* Image header by image type */
header('Content-Type: image/jpeg');

/* Image output */
imagejpeg($image_p, null, 100);