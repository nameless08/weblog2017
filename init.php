<?php

const ROOT_DIR = __DIR__; // [1] __DIR__ 

require_once ROOT_DIR . '/libs/storage.php';
require_once ROOT_DIR . '/libs/sanitize.php';
require_once ROOT_DIR . '/libs/models/user.php';
require_once ROOT_DIR . '/libs/auth.php';
require_once ROOT_DIR . '/libs/view.php';

require_once ROOT_DIR . '/app/models/post.php';
$posts = postGetAll();
session_start();


// [1] __DIR__ Директория файла. Если используется внутри подключаемого файла, то возвращается директория этого файла. Это эквивалентно вызову dirname(__FILE__). Возвращаемое имя директории не оканчивается на слэш, за исключением корневой директории.