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
        $stmt->execute($update->getBindValues());

    }


    public function delete($id, $table) {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                 
            ->where('id = :id')           
            ->bindValue('id', $id);

        $stmt = $this->pdo->prepare($delete->getStatement());
        $stmt->execute($delete->getBindValues());
    }
   
}


