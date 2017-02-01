<?php

const STORAGE_DB_DIR = ROOT_DIR . DIRECTORY_SEPARATOR . 'db'; // ROOT_DIR == __DIR__ , [1] DIRECTORY_SEPARATOR 

const STORAGE_FILENAME_PATTERN = '%d.json'; // паттерн для имен файлов


// *** получаем полное имя файла (путь + "имя файла")
function storageCreateFilename($entity, $id)
{
    return storageGetDir($entity) . DIRECTORY_SEPARATOR . sprintf(STORAGE_FILENAME_PATTERN, $id);
}


// *** функция получения пути к файлу ???
function storageGetDir($entity)
{
    return STORAGE_DB_DIR . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], '_', $entity); 
}


// *** 
function storageGetIdFromFilename($filename)
{
    $filename = basename($filename);
    sscanf($filename, STORAGE_FILENAME_PATTERN, $id);
    return (int) $id;
}


// *** 
function storageGetNextId($entity)
{
    $dir = storageGetDir($entity);

    if (!is_readable($dir)) {
        return 0;
    }

    $ids = array_map('storageGetIdFromFilename', scandir($dir));
    $ids = array_filter($ids);

    return $ids ? max($ids) + 1 : 1;

}


// *** 
function storageGetItemBy($entity, $attribute, $criteria)
{
//    array_filter(storageGetAll($entity), function ($item) {
//        if (isset($item[$attribute])) {
//
//        }
//    });

    $items = [];
    $storedItems = storageGetAll($entity);
    
    foreach ($storedItems as $storedItem) {
        if (
            isset($storedItem[$attribute]) &&
            $storedItem[$attribute] == $criteria
        ) {
            $items[] = $storedItem;
        }
    }

    return $items;

}


// *** получение объекта по сущности по идентификатору
function storageGetItemById($entity, $id)
{
    
    $filename = storageCreateFilename($entity, $id); // получение полного имени файла по заданной сущности и идентификатору

    if (is_readable($filename)) { // проверяем доступен ли файл для четения

        return json_decode(file_get_contents($filename), true); // если файл доступен, то декодируем json формат

    }

    return null;

}


function storageGetAll($entity) // получить все сущности из заданной директории
{
    $items = [];
    $files = scandir(storageGetDir($entity)); // scandir() - получает список файлов и каталогов, расположенных по указанному пути (array scandir ( string $directory [, int $sorting_order = SCANDIR_SORT_ASCENDING [, resource $context ]] ))

    foreach ($files as $filename) {

        $id = storageGetIdFromFilename($filename);
        $item = storageGetItemById($entity, $id);

        if ($item) {
            $items[] = $item;
        }

    }

    return $items;
}


function storageSaveItem($entity, array &$item)
{
    $dir = storageGetDir($entity);
    $success = true;

    if (!file_exists($dir)) {
        $success = mkdir($dir, 0755, true);
    }

    if (!$success) {
        return false;
    }

    $id = $item['id'] ?? 0;
    $storedItem = storageGetItemById($entity, $id) ?: [];

    if ($id && !$storedItem) {
        return false;
    }

    $item = array_merge($storedItem, $item);

    if (!$id) {
        $id = storageGetNextId($entity);
        $item['created'] = time();
    }

    $item['id'] = (int) $id;
    $item['updated'] = time();

    $filename = storageCreateFilename($entity, $id);

    return file_put_contents($filename, json_encode($item), LOCK_EX);
}


// [1] DIRECTORY_SEPARATOR - В PHP есть предопределённая константа DIRECTORY_SEPARATOR, содержащая разделитель пути. Для Windows это «\», для Linux и остальных — «/». Так как Windows понимает оба разделителя, достаточно использовать в коде разделитель Linux вместо константы. Тем не менее, DIRECTORY_SEPARATOR полезен. Все функции, отдающие путь (вроде realpath), отдают его с специфичными для ОС разделителями. Чтобы разбить такой путь на составляющие как раз удобно использовать константу: $segments = explode(DIRECTORY_SEPARATOR, realpath(__FILE__));


// [2]