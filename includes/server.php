<?php

/**
 * PHP Image uploader Script
 */

// if(isset($_POST['imgTrans'])){ $imgTrans = 1; }else{ $imgTrans = 0; }
$img_height = $_POST['imgHeight'];
$imgTrans = $_POST['imgTrans'];

$OutputFolderName = "WebOptimazeImages";
$uploadPath = exec('echo %SystemDrive%') . '\\Users\\' . get_current_user() . '\\Desktop\\'. $OutputFolderName .'\\';
$uploadPathWebP = exec('echo %SystemDrive%') . '\\Users\\' . get_current_user() . '\\Desktop\\'. $OutputFolderName .'\\webp\\';
if (!file_exists($uploadPath)) {
    mkdir($uploadPath, 0777, true);
}
if (!file_exists($uploadPathWebP)) {
    mkdir($uploadPathWebP, 0777, true);
}

$images = restructureArray($_FILES);
//echo '<pre>';print_r($images);echo '</pre>';exit;

foreach ($images as $file) {
    $fileName = $file['tmp_name'];
    $sourceProperties = getimagesize($fileName);

    $fileNameRKN = pathinfo($file['name'], PATHINFO_FILENAME);
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uploadImageType = $sourceProperties[2];
    $sourceImageWidth = $sourceProperties[0];
    $sourceImageHeight = $sourceProperties[1];

    // $optimizeImageSizes = [200, 320, 360, 420, 768, 1000, 1024, 1200, 1280, 1366, 1400];
    $optimizeImageSmSizes = [320];
    $optimizeImageMdSizes = [320, 768];
    $optimizeImageLgSizes = [320, 768, 1400];
    $optimizeImageQuality = (int)$_POST['imgQuality'];
    $optimizeImageQualityWebp = $optimizeImageQuality;

    if($_POST['imgOutSize'] == "sm"){
      $optimizeImageSizes = $optimizeImageSmSizes;
    }else if($_POST['imgOutSize'] == "md"){
      $optimizeImageSizes = $optimizeImageMdSizes;
    }else{
      $optimizeImageSizes = $optimizeImageLgSizes;
    }


    $optimizeImageThumbSize = 150;
    $optimizeImageThumbQualityJPG = 1;
    $optimizeImageThumbQualityPNG = 9;
    $optimizeImageThumbQualityWebp = 1;

    if($img_height == '1'){ $imgReW = 1400; $imgReH = 800; }
    if($img_height == '2'){ $imgReW = 1400; $imgReH = 680; }

    if($sourceImageWidth != $imgReW OR $sourceImageHeight != $imgReH){
      $sourceImageWidth = $imgReW;
      $sourceImageHeight = $imgReH;
    }

    $converTo = strtolower($fileExt);

    // exit;

  switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName);
                foreach ($optimizeImageSizes as $imgWidth) {
                  createOptImg($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageQuality, $optimizeImageQualityWebp, $imgWidth, $imgTrans, 'imagejpeg');
                }
                createOptImgThumnail($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageThumbQualityJPG, $optimizeImageThumbQualityWebp, $optimizeImageThumbSize, $imgTrans, 'imagejpeg');
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName);
                $optimizeImageQuality = $optimizeImageQuality/10;
                echo $optimizeImageQuality;
                foreach ($optimizeImageSizes as $imgWidth) {
                createOptImg($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageQuality, $optimizeImageQualityWebp, $imgWidth, $imgTrans, 'imagepng'); 
                }               
                if($imgTrans != 1){ $optimizeImageThumbQualityPNG = $optimizeImageThumbQualityJPG; }
                createOptImgThumnail($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageThumbQualityPNG, $optimizeImageThumbQualityWebp, $optimizeImageThumbSize, $imgTrans, 'imagepng');
                break;

            case IMAGETYPE_WEBP:
                $resourceType = imagecreatefromwebp($fileName);
                foreach ($optimizeImageSizes as $imgWidth) {
                  createOptImg($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageQuality, $optimizeImageQualityWebp, $imgWidth, $imgTrans, 'imagejpeg');
                }
                createOptImgThumnail($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileNameRKN, $fileExt, $optimizeImageThumbQualityJPG, $optimizeImageThumbQualityWebp, $optimizeImageThumbSize, $imgTrans, 'imagejpeg');
                break;

            default:
                $imageProcess = 0;
                break;
        }
  move_uploaded_file($fileName, $uploadPath. $fileNameRKN. ".". $fileExt);
}

/**
 * RestructureArray method
 * 
 * @param array $images array of images
 * 
 * @return array $result array of images
 */
function restructureArray(array $images)
{
    $result = array();
    foreach ($images as $key => $value) {
        foreach ($value as $k => $val) {
            for ($i = 0; $i < count($val); $i++) {
                $result[$i][$k] = $val[$i];
            }
        }
    }

    return $result;
}



function resizeImage($resourceType,$image_width,$image_height,$resizeWidth,$resizeHeight) {
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
}

function createOptImg($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileName, $fileExt, $optimizeImageQuality, $optimizeImageQualityWebp, $imgWidth, $imgTrans, $imgExt){
  list($OGWidth, $OGHeight) = $sourceProperties;
  $imgHeight = round(($sourceImageHeight/$sourceImageWidth)*$imgWidth);
  $imageLayer = resizeImage($resourceType,$OGWidth,$OGHeight, $imgWidth, $imgHeight);
  if($imgExt == 'imagejpeg'){
    imageinterlace($imageLayer, true);
  }
  if($imgExt == 'imagepng'){
    $image_p = imagecreatetruecolor($imgWidth, $imgHeight);
    if( (int)$imgTrans === 1 ){
      imagealphablending($image_p, false);
      imagesavealpha($image_p,true);
      $transparent = imagecolorallocatealpha($imageLayer, 255, 255, 255, 127);
      imagefilledrectangle($image_p, 0, 0, $imgWidth, $imgHeight, $transparent);
      imagecopyresampled($image_p, $resourceType, 0, 0, 0, 0, $imgWidth, $imgHeight, $OGWidth, $OGHeight);
    }else{
      imagefill($image_p, 0, 0, imagecolorallocate($image_p, 255, 255, 255));
      imagealphablending($image_p, TRUE);
      imageinterlace($image_p, true);
      imagecopyresampled($image_p, $resourceType, 0, 0, 0, 0, $imgWidth, $imgHeight, $OGWidth, $OGHeight);
      $imgExt = 'imagejpeg';
      $fileExt = 'jpg';
      $optimizeImageQuality = round($optimizeImageQuality * 10);
    }
    $imageLayer = $image_p;
  }
  if($fileExt == "webp"){ $fileExt = "jpg"; }
  $imgExt($imageLayer,$uploadPath.$fileName.'-'.$imgWidth.'w.'.$fileExt, $optimizeImageQuality);
    imagewebp($imageLayer, $uploadPathWebP.$fileName. '-'.$imgWidth.'w'.'.webp', $optimizeImageQualityWebp);
    imagedestroy($imageLayer);
}

function createOptImgThumnail($sourceProperties, $resourceType, $sourceImageWidth, $sourceImageHeight, $uploadPath, $uploadPathWebP, $fileName, $fileExt, $optimizeImageQuality, $optimizeImageQualityWebp, $imgWidth, $imgTrans, $imgExt){
  list($OGWidth, $OGHeight) = $sourceProperties;
  $imgHeight = round(($sourceImageHeight/$sourceImageWidth)*$imgWidth);
  $imageLayer = resizeImage($resourceType,$OGWidth,$OGHeight, $imgWidth, $imgHeight);
  if($imgExt == 'imagejpeg'){
    imageinterlace($imageLayer, true);
  }
  if($imgExt == 'imagepng'){
    $image_p = imagecreatetruecolor($imgWidth, $imgHeight);
    if( (int)$imgTrans == 1 ){
      imagealphablending($image_p, false);
      imagesavealpha($image_p,true);
      $transparent = imagecolorallocatealpha($imageLayer, 255, 255, 255, 127);
      imagefilledrectangle($image_p, 0, 0, $imgWidth, $imgHeight, $transparent);
      imagecopyresampled($image_p, $resourceType, 0, 0, 0, 0, $imgWidth, $imgHeight, $OGWidth, $OGHeight);
    }else{
      imagefill($image_p, 0, 0, imagecolorallocate($image_p, 255, 255, 255));
      imagealphablending($image_p, TRUE);
      imageinterlace($image_p, true);
      imagecopyresampled($image_p, $resourceType, 0, 0, 0, 0, $imgWidth, $imgHeight, $OGWidth, $OGHeight);
      $imgExt = 'imagejpeg';
      $fileExt = 'jpg';
    }
    $imageLayer = $image_p;
  }
  if($fileExt == "webp"){ $fileExt = "jpg"; }
  $imgExt($imageLayer,$uploadPath.$fileName.'-thumb.'.$fileExt, $optimizeImageQuality);
    imagewebp($imageLayer, $uploadPathWebP.$fileName. '-thumb'.'.webp', $optimizeImageQualityWebp);
    imagedestroy($imageLayer);
}