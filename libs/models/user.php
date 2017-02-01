<?php

const ENTITY_USER = 'user';

function userGetBy($attribute, $criteria) {
    return storageGetItemBy(ENTITY_USER, $attribute, $criteria);
}


function userGetById($id) {
    return storageGetItemById(ENTITY_USER, $id);
}

function userGetAll()  
{
    
    $users = storageGetAll(ENTITY_USER); // получить всех пользователей из заданной директории
    return $users; // возвращаем двумерный массив со всеми пользователями

}

function userSave(array $user, array &$errors = null) {
    
    if ($errors) {

        return $user; 
		}

    $status = storageSaveItem(ENTITY_USER, $user);

    if (!$status) { 

        $errors['db'] = 'Не удалось зарегистрироваться';

    }
    return $user;
}