<?php

namespace App;

use App\QueryBuilder;


class AuthController
{
    private $queryBuilder;

    public function __construct(QueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
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

        $checkUser = $this->queryBuilder->getUserByEmail($_POST['email'], $table);
        if($checkUser) {
            $this->page_register();
            //Flash пол уже сушевстует ...
        }
        
        $user = [
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ];

        if($this->queryBuilder->insert($user, $table)) {
            $this->page_login();
            //Flash! рег успешно ...
            exit;
        } else {
            $this->page_register();
            //Flash !!! повторите еще раз ...
            exit;
        }
        
    }


    public function login() {
        $table = 'admin';
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->queryBuilder->getUserByEmail($email, $table);
        if(!$user) {
            $this->page_login();
            //Flash Не правилно лог или пар ...
        }

        $password_verify = password_verify($password, $user[0]['password']);
        if(!$password_verify) {
            $this->page_login();
            //Flash Не правилно лог или пар ...
        }
        header('Location:public/views/users.php');

    }


}
