<?php

namespace App; 

use App\QueryBuilder;
use League\Plates\Engine;
use App\UploadsController;

class UsersController
{
    

    private $queryBuilder;
    private $template;
    private $uploads;


    public function __construct(Engine $template, QueryBuilder $queryBuilder, UploadsController $uploads) {
        $this->queryBuilder = $queryBuilder;
        $this->template = $template;
        $this->uploads = $uploads;
    }



    public function getUsers() {
        if(!$_SESSION['user_id']) {
            header("Location: /page_login");
        }
        $table = [
            'users' => 'users',
            'security_media' => 'security_media',
            'social_network' => 'social_network'
        ];
        $users = $this->queryBuilder->getAllUsers($table);
        echo $this->template->render('users', ['users' => $users]);

    }



    public function pageCreateUser(){
        echo $this->template->render('create_user');
        exit;
    }



    public function insertUser() {
        $nameAvatar = $this->uploads->uploadsAvatar($_FILES['avatar']);
        if(!$nameAvatar) {
            FlashInfo::error('Загрузит фото не удалос');
            header("Location: /users");
            exit;
        }
        $table = [
            'users' => 'users',
            'security_media' => 'security_media',
            'social_network' => 'social_network'
        ];      

        $userData = [
            'name' => $_POST['name'],
            'address_job' => $_POST['address_job'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address']
        ];

        $securityMediaData = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'status' => $_POST['status'],
            'avatar' => $nameAvatar
        ];

        $socialNetworkData = [
            'vk' => $_POST['vk'],
            'telegram' => $_POST['telegram'],
            'instagram' => $_POST['instagram']
        ];

        $insertUser = $this->queryBuilder
            ->insertUser($table, $userData, $securityMediaData, $socialNetworkData);

        if(!$insertUser) {
            FlashInfo::error('Добавит ползовател не удалос');
            header("Location: /users");
            exit;
        }
        FlashInfo::messenger('Ползовател добавлен');
        header("Location: /users");
        exit;

    }


    public function editUser($id) {
        $users = $this->queryBuilder->getUserById($id, 'users');
        echo $this->template->render('edit', ['users' => $users]);
        exit;
    }


    public function updateUser($id) {
        $data = [
            'name' => $_POST['name'],
            'address_job' => $_POST['address_job'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
        ];
        $updateUser = $this->queryBuilder->update($id, $data, 'users');
        if(!$updateUser) {
            FlashInfo::error('Упс что то пошло не так');
            header("Location: /users");
            exit;
        }
        FlashInfo::messenger('Профил успешно обнавлен');
        header("Location: /users");
        exit;

    }



    public function pageProfile($id) {
        $table = [
            'users' => 'users',
            'security_media' => 'security_media',
            'social_network' => 'social_network'
        ];
        $user = $this->queryBuilder->getUser($id, $table);
        echo $this->template->render('/page_profile', ['user' => $user]);
        
    }



    public function deleteUser($id) {
        $table = [
            'users' => 'users',
            'security_media' => 'security_media',
            'social_network' => 'social_network'
        ];
        $nameImg = $this->queryBuilder->getByUserId($id, $table['security_media']);

        if(!$this->queryBuilder->delete($id, $table['users'])) {
            FlashInfo::error('Упс что то пошло не так');
            header("Location: /users");
            exit;
        }
        if (file_exists('C:/xampp/htdocs/php_components/public/uploads/' . $nameImg[0]['avatar'])) {
            $this->uploads->deleteImg($nameImg[0]['avatar']);
        }
        $this->queryBuilder->deleteByUserId($id, $table['security_media']);
        $this->queryBuilder->deleteByUserId($id, $table['social_network']);

        FlashInfo::messenger('Ползовател удален');
        header("Location: /users");
        exit;
    }



}