<?php


namespace App;



class FlashInfo
{

    public static function error($error) {
        $_SESSION['error'] = $error;
    }


    public static function messenger($messenger) {
        $_SESSION['messenger'] = $messenger;
    }


    public static function isAdmin($is_admin) {
        $_SESSION['is_admin'] = $is_admin;
    }


    public static function isAuth($user_id = null) {
        $_SESSION['user_id'] = $user_id;
    }



    
}