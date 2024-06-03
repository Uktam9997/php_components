<?php

// namespace App;
// $val = [
//     'name' => [
//         'required' => true,
//         'min' => 2,
//         'max' => 20,
//     ],
//     'email' => [
//         'required' => true,
//         'email' => true,
//         'unique' => $table,
//     ],
//     'password' => [
//         'required' => true,
//         'min' => 3,
//     ],
//     'confirm_password' => [
//         'required' => true,
//         'matches' => 'password'
//     ]
// ];

// use App\QueryBuilder;
// use App\FlashInfo;


// class Validation
// {
//     private $passed = false;
//     private $errors = [];
//     private $queryBuilder;

    
//     public function __construct(QueryBuilder $queryBuilder) {
//         $this->queryBuilder = $queryBuilder;
//     }


//     public function check($source, $items = []) {
//         foreach($items as $item => $rules) {
//             foreach($rules as $rule => $rule_value) {
//                 $value = $source[$item];

//                 if($rule === 'required' && empty($value)) {
//                     FlashInfo::error("{$item} is required");
//                 } else if(!empty($value)) {
//                     switch($rule) {
//                         case 'min':
//                             if(strlen($value) < $rule_value) {
//                                 FlashInfo::errorValidation("{$item} must be a minimumof {$rule_value} characters");
//                             }
//                             break;

//                         case 'max':
//                             if(strlen($value) > $rule_value) {
//                                 FlashInfo::error("{$item} must be a maximum {$rule_value} characters");
//                             }
//                             break;

//                         case 'matches':
//                             if($value != $source[$rule_value]) {
//                                 FlashInfo::error("{$rule_value} must match {$item}");
//                             }
//                             break;

//                         case 'email':
//                             if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
//                                 FlashInfo::error("{$item} is not an email");
//                             }
//                             break;

//                         case 'unique':
//                             $check = $this->queryBuilder->getUserByEmail($rule_value, [$item, '=', $value]);
//                             if($check) {
//                                 FlashInfo::errorValidation("{$item} already exists.");
//                             }
//                             break;
//                     }
//                 }
//             }
//         }
//         if(FlashInfo::errorValidation()) {
//             return false;
//         }
//         return true;
//         // if(empty($this->errors)) {
//         //     $this->passed = true;
//         // }
//     }


//     // public function addError($error) {
//     //     $this->errors[] = $error;
//     // }


//     // public function errors() {
//     //     return $this->errors;
//     // }


//     // public function passed() {
//     //     return $this->passed;
//     // }
// }

