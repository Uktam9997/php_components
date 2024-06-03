<?php

namespace App;

use League\Plates\Engine;
use App\FlashInfo;

class SecurityMediaController
{

    private $queryBuilder;
    private $template;
    private $uploads;

    public function __construct(QueryBuilder $queryBuilder, Engine $template, UploadsController $uploads) {
        $this->queryBuilder = $queryBuilder;
        $this->template = $template;
        $this->uploads = $uploads;

    }
    


    public function editSecurity($id) {
        $table = 'security_media';
        $user = $this->queryBuilder->getByUserId($id, $table);
        echo $this->template->render('security',  ['user' => $user]);
    }



    public function updateSecurity($id) {
        $table = 'security_media';
        if($_POST['password'] !== $_POST['confirm_password']) {
            FlashInfo::error('Паролы не совпадает');
            header("Location: /users");
            exit;
        }

        $data = [
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];
        if(!$this->queryBuilder->update($id, $data, $table)) {
            FlashInfo::error('Упс что то пошло не так :( попробуйте еще раз');
            header("Location: /users");
            exit;
        }
        FlashInfo::messenger('Профил успешно обнавлен');
            header("Location: /users");
            exit;
    }



    public function editStatus($id) {
        $table = 'security_media';
        $user = $this->queryBuilder->getByUserId($id, $table);
        echo $this->template->render('status',  ['user' => $user]);
    }



    public function updateStatus($id) {
        $table = 'security_media';
        $data = [
            'status' => $_POST['status']
        ];

        if(!$this->queryBuilder->update($id, $data, $table)) {
            FlashInfo::error('Изменит статус не удалос попробуйте еще раз');
            header("Location: /users");
            exit;
        }
        FlashInfo::messenger('Cтатус обнавлен');
            header("Location: /users");
            exit;

    }



    public function editAvatar($id) {
        $table = 'security_media';
        $user = $this->queryBuilder->getByUserId($id, $table);
        echo $this->template->render('media',  ['user' => $user]);

    }



    public function updateAvatar($id) {
        $table = 'security_media';
        $nameImg = $this->uploads->uploadsAvatar($_FILES['avatar']);
        $data = [
            'avatar' => $nameImg
        ];
        $avatar = $this->queryBuilder->getUserById($id, $table);

        if(!$nameImg) {
            FlashInfo::error('Упс что то пошло не так');
            header("Location: /users");
            exit;
        }
        
        if (file_exists('C:/xampp/htdocs/php_components/public/uploads/' . $avatar[0]['avatar'])) {
            $this->uploads->deleteImg($avatar[0]['avatar']);
        }

        if(!$this->queryBuilder->update($id, $data, $table)) {
            FlashInfo::error('Упс что то пошло не так');
            header("Location: /users");
            exit;
        }
        FlashInfo::messenger('Профил обнавлен');
        header("Location: /users");
        exit;
    }

    
}
