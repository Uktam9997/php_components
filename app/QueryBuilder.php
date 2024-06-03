<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;


class QueryBuilder
{

    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo, QueryFactory $queryFactory) {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;

    }
  


    public function getAll($table = null) {
        $select = $this->queryFactory->newSelect();
        $select->cols(['*'])->from($table);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute($select->getBindValues());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }



    public function getAllUsers($table = null) {
        $selectUsers = $this->queryFactory->newSelect();
        $selectUsers->cols([
            'users.id',
            'users.name',
            'users.address_job',
            'users.phone',
            'users.address',
        
            'security_media.email',
            'security_media.password',
            'security_media.status',
            'security_media.avatar',
        
            'social_network.vk',
            'social_network.telegram',
            'social_network.instagram'
        ]); 
        
        $selectUsers->from($table['users'])
            ->join('INNER', 'security_media', 'users.id = security_media.user_id')
            ->join('INNER', 'social_network', 'users.id = social_network.user_id');
        $stmt = $this->pdo->prepare($selectUsers->getStatement());
        $stmt->execute($selectUsers->getBindValues());
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $users ? $users : false;
    
    }



    public function getUserByEmail($email, $table){
        $getUserEmail = $this->queryFactory->newSelect();
        $getUserEmail->cols(['*'])->from($table)
            ->where('email = :email')
            ->bindValue('email', $email);
        $stmt = $this->pdo->prepare($getUserEmail->getStatement());
        $stmt->execute($getUserEmail->getBindValues());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }



    public function getUserById($id, $table){
        $getUserById = $this->queryFactory->newSelect();
        $getUserById->cols(['*'])->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);
        $stmt = $this->pdo->prepare($getUserById->getStatement());
        $stmt->execute($getUserById->getBindValues());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }



    public function getByUserId($id, $table){
        $getUserById = $this->queryFactory->newSelect();
        $getUserById->cols(['*'])->from($table)
            ->where('user_id = :id')
            ->bindValue('id', $id);
        $stmt = $this->pdo->prepare($getUserById->getStatement());
        $stmt->execute($getUserById->getBindValues());
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }


    public function getUser($id, $table) {

        $select = $this->queryFactory->newSelect();

        // Выбираем нужные столбцы из всех трех таблиц
        $select->cols([
            'users.id',
            'users.name',
            'users.address_job',
            'users.phone',
            'users.address',
        
            'security_media.email',
            'security_media.password',
            'security_media.status',
            'security_media.avatar',
        
            'social_network.vk',
            'social_network.telegram',
            'social_network.instagram'
        ]);

        $select->from('users')
            ->join(
                'LEFT',
                'security_media',
                'users.id = security_media.user_id'
            )
            ->join(
                'LEFT',
                'social_network',
                'users.id = social_network.user_id'
            );

        $select->where('users.id = :id');
        $select->bindValue('id', $id);
        $sql = $select->getStatement();
        $bindValues = $select->getBindValues();

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindValues);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }



    public function insertUser($table, $userData, $securityMediaData, $socialNetworkData) {
        // insert User
        $insertUser = $this->queryFactory->newInsert();
        $insertUser->into($table['users'])
            ->cols([
                'name' => $userData['name'],
                'address_job' => $userData['address_job'],
                'phone' => $userData['phone'],
                'address' => $userData['address']
            ]);
        $statement1 = $this->pdo->prepare($insertUser->getStatement());
        $statement1->execute($insertUser->getBindValues());
        $user_id = $this->pdo->lastInsertId();
        
        if(!$user_id) {
            return false;
            exit;
        }
        // insert Security Media
        $insertSecurityMedia = $this->queryFactory->newInsert();
        $insertSecurityMedia->into($table['security_media'])
            ->cols([
                'user_id' => $user_id,
                'email' => $securityMediaData['email'],
                'password' => $securityMediaData['password'],
                'status' => $securityMediaData['status'],
                'avatar' => $securityMediaData['avatar']
            ]);
        $statement2 = $this->pdo->prepare($insertSecurityMedia->getStatement());

        if(!$statement2->execute($insertSecurityMedia->getBindValues())) {
            return false;
            exit;
        }

        // insert Social Network
        $insertSocialNetwork = $this->queryFactory->newInsert();
        $insertSocialNetwork->into($table['social_network'])
            ->cols([
                'user_id' => $user_id,
                'vk' => $socialNetworkData['vk'],
                'telegram' => $socialNetworkData['telegram'],
                'instagram' => $socialNetworkData['instagram']
            ]);

        $statement3 = $this->pdo->prepare($insertSocialNetwork->getStatement());

            if(!$statement3->execute($insertSocialNetwork->getBindValues())) {
                return false;
            }

        return true;

    }



    public function insert($data, $table) {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $stmt = $this->pdo->prepare($insert->getStatement());
        
        if(!$stmt->execute($insert->getBindValues())) {
            return false;
            exit;
        }
        return true;
    }



    public function update($id, $data, $table) {
        $update = $this->queryFactory->newUpdate();
        $update->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $stmt = $this->pdo->prepare($update->getStatement());
        if(!$stmt->execute($update->getBindValues())) {
            return false;
        }
        return true;

    }



    public function delete($id, $table) {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                 
            ->where('id = :id')           
            ->bindValue('id', $id);

        $stmt = $this->pdo->prepare($delete->getStatement());
        if(!$stmt->execute($delete->getBindValues())) {
            return false;
            exit;
        }
        return true;

    }



    public function deleteByUserId($id, $table) {
        $delete = $this->queryFactory->newDelete();

        $delete->from($table)
            ->where('user_id = :id')
            ->bindValue('id', $id);
        $stmt = $this->pdo->prepare($delete->getStatement());
        if(!$stmt->execute($delete->getBindValues())) {
            return false;
            exit;
        }
        return true;

    }

   
}


