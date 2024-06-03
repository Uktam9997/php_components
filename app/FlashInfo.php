<?php


namespace App;



class FlashInfo
{

    public static function error($error) {
        $_SESSION['error'] = $error;
        // unset($_SESSION['error']);

    }



    public static function errorValidation($error = null) {
        $res = $_SESSION['error_val'] = $error;
        return $res ? true : false;
        // unset($_SESSION['error']);

    }


    public static function messenger($messenger) {
        $_SESSION['messenger'] = $messenger;
        // unset($_SESSION['messenger']);

    }


    public static function isAdmin($is_admin) {
        $_SESSION['is_admin'] = $is_admin;
    }


    public static function isAuth($user_id = null) {
        $_SESSION['user_id'] = $user_id;
    }



    
}