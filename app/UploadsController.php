<?php



namespace App;



class UploadsController
{

    public function uploadsAvatar($avatar) {
        $path = '../public/uploads/';
        $from = $avatar['tmp_name'];
        $name = $avatar['name'];
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $file_name = uniqid().'.'.$ext; 
        $fullPath = $path .= $file_name;

        if(!move_uploaded_file($from, $fullPath)){
            return false;
            exit;
        }
        return $file_name;
    }



    public function deleteImg($nameImg) {
        if(!unlink('C:/xampp/htdocs/php_components/public/uploads/' . $nameImg)) {
            return false;
        }
        return true;
    }


}
