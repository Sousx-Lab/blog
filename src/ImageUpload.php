<?php

namespace App;
use App\URL;

class ImageUpload
{


    public function getImages(array $data, string $path)
    {
        $result = null;
        $files = $data;
        $fileSize = $files['size'];
        $fileTmp = $files['tmp_name'];
        if(!$this->fileType($fileTmp)){
            echo $result = "Le fichier doit ètre de type image: (.gif /.jpeg /.png / .bmp / .ico) ! ";
            exit();
        }
         if(!$this->fileSize($fileSize)){
            echo $result = "Le fichier ne doit pas dépasser les 5Mo . ";
            exit();
         }
         $newfile = $this->rename($files['name']);
         $upPath = $path . $newfile;
         if(!move_uploaded_file($fileTmp, $upPath))
         {
            echo $result = "Erreur de téléchargement du fichier !";
            exit();
         }
         $result .= "../../img/" . $newfile;
         return $result;

    }

    private function fileSize(string $files): bool
    {
        $maxSize = 50000000;
        $result = null;
        if ($files <= $maxSize) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    private function fileType(string $files)
    {
        $mime = [1, 2, 3, 6, 17];
        $result = null;
        if(in_array(exif_imagetype($files), $mime)){
            $result = true;
        }else{
            $result = false;
        }
        return$result;
    }

    private function rename(string $files): string
    {
        $ext = explode('.', $files);
        $newfile  = uniqid('blog_') . '.' . end($ext);
        return $newfile;
    }

    public function deleteImages(string $data, string $path)
    {
        $http = URL::getProtocol();
        $img_name = str_replace($http . $_SERVER['HTTP_HOST'] . "/img/", "", $data);
        if(unlink($path.$img_name)){
            echo "Fichier surpprimée avec succée";
        }
            echo "Erreur de suppression du fichier";
        }
   
}
