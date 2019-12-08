<?php
require '../vendor/autoload.php';
use App\ImageUpload;
use App\Auth;


Auth::check();
$img = new ImageUpload();
$imgPath = (__DIR__) . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR;

if(!empty($_POST['src'])){
  $img->deleteImages($_POST['src'], $imgPath);
  exit();
}

if(!empty($_FILES['img'])){
   $link = $img->getImages($_FILES['img'], $imgPath); 
   echo $link;
  }
?>
