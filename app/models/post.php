<?php

const ENTITY_POST = 'post'; // константа - сущность пост


// получаем все посты из директории и сортируем их 
function postGetAll()  
{
    
    $posts = storageGetAll(ENTITY_POST); // получить все посты из заданной директории

    uasort($posts, function ($a, $b) { // отсортировать посты по дате создания, функция [1]uasort()

         return $b['created'] <=> $a['created']; // сравниваем даты, оператор [2]spaceship 
        

    });
    
    return $posts; // возвращаем двумерный массив со всеми постами

}


// получаем пост по идентификатору
function postGetById($id)
{
    return storageGetItemById(ENTITY_POST, $id); // функция работы с хранилищем
}


// 
function postSave(array $post, array &$errors = null)
{
    
    $post = sanitize($post, postSanitizeRules(), $errors);
    
    if ($errors) {
        return $post;
    }
    
    $status = storageSaveItem(ENTITY_POST, $post);
    
    if (!$status) {
        $errors['db'] = 'Не удалось сохранить данные в базу';
    }
    
    return $post;
}

function postSanitizeRules(){

    return [

        'id'        => [   
            'filter' => FILTER_VALIDATE_INT,
            'options' => [
                'min_range' => 1,
            ],
        ],
        'title'     => [
            'required' => true,
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        'content'   => [
            'required' => true,
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],

    ];

}


// [1] uasort() - отсортировать массив, используя пользовательскую функцию для сравнения элементов с сохранением ключей bool uasort ( array &array, callback cmp_function ) Функция сортирует массив таким образом, что его индексы сохраняют отношения с элементами, с которыми ранее были ассоциированы. Это особенно полезно при сортировке ассоциативных массивов, актуальный порядок элементов которых значим. Для сравнения используется функция, определённая пользователем. Возвращает TRUE в случае успешного завершения или FALSE в случае возникновения ошибки.

// [2] Оператор космический корабль <=>  - этот оператор предназначен для сравнения двух выражений. Он возвращает -1, 0 или 1 если $b, соответственно, меньше, равно или больше чем $b. Сравнение производится в соответствии со правилами сравнения типов PHP.