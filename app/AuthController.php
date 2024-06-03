<?php

namespace App;

use App\QueryBuilder;
use Password\Validator;
use App\FlashInfo;

class AuthController
{
    private $queryBuilder;
    private $validator;

    public function __construct(QueryBuilder $queryBuilder, Validator $validator) 
    {
        $this->queryBuilder = $queryBuilder;
        $this->validator = $validator;
    }


    public function page_register() {
        header('Location: /public/views/page_register.php');
        exit;
    }


    public function page_login() {
        header('Location: /public/views/page_login.php');
        exit;
    }
    
    

    public function register() {
        $table = 'admin';
        $validation = $this->validator->setMinLength(4);
        if(!$validation->isValid($_POST['password'])) {
            FlashInfo::error('Парол должен содержат минимум 4 символ!');
            header("Location: /page_register");
            exit;
        }

        $checkUser = $this->queryBuilder->getUserByEmail($_POST['email'], $table);
        if($checkUser) {
            FlashInfo::error('Емаил уже зарегистрирован! попробуйте с другово емаила');
            header("Location: /page_register");
            exit;
            
        }
        $user = [
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ];

        if(!$this->queryBuilder->insert($user, $table)) {
            FlashInfo::error('Упс что то пошло не так :( попробуйте еще раз');
            header("Location: /page_register");
            exit;
        }
            FlashInfo::messenger('Вы успещно зарегистрировалис :) Можете зохадит.');
            header("Location: /page_login");
            exit;
        
        
    }


    public function login() {
        $table = 'admin';
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->queryBuilder->getUserByEmail($email, $table);
        if(!$user) {
            FlashInfo::error('Не верный Емаил');
            header("Location: /page_login");
            exit;
        }

        $password_verify = password_verify($password, $user[0]['password']);
        if(!$password_verify) {
            FlashInfo::error('Не верный парол');
            header("Location: /page_login");
            exit;
        }
        foreach($user as $user_info) {
            FlashInfo::isAuth($user_info['id']);
            FlashInfo::isAdmin($user_info['is_admin']);
        }
        header('Location: /users');

    }



    public function logaut() {
        unset($_SESSION['user_id']);
        header("Location: /page_login");
    }


}
