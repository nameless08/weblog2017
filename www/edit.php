<?php

require_once __DIR__ . '/../init.php';

// echo ROOT_DIR;

// if (!isAuthorized()) {
//     header('location: login.php');
//     exit;
// }

/*
 * Мы попадаем сюда в четырех случаях:
 * 1) форма не была отправлена  id не найден  => форма добавить новую запись
 *    edit.php
 * 2) форма не была отправлена  id найден     => форма редактировать запись
 *    edit.php?id=1
 * 3) форма была отправлена     id не найден  => сохранить новую запись в БД
 * 4) форма была отправлена     id найден     => обновить запись в БД
 */

// $data = isset($_POST['post'] ? $_POST['post'] : []
$data = $_POST['post'] ?? []; // PHP7.0
$errors = [];
$post = [];
$id = $data['id'] ?? $_GET['id'] ?? null;

if ($id) {
    $post = postGetById((int) $id);
    
    if (!$post) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not found');
        exit('Запись не найдена!');
    }
}

if ($data) {
    $msg = 'Запись успешно ' . ($id ? 'обновлена' : 'добавлена');
    $post = postSave($data, $errors);

    if (!$errors) {
        // всплывающее сообщение об успехе
        header('Location: edit.php?id=' . $post['id']);
        exit;
    }

    var_dump($errors);
}

?>

<?php include ROOT_DIR . '/app/views/layout/header.php'; ?>

<h1>
    <?= isset($post['id']) ? 'Редактировать запись' : 'Новая запись' ?>
</h1>

<form method="post">
    <div>
        <label for="post_title">Заголовок</label>
        <input name="post[title]" id="post_title" type="text" value="<?= $post['title'] ?? '' ?>">
    </div>
    <div>
        <label for="post_content">Содержимое</label>
        <textarea name="post[content]" id="post_content"><?= $post['content'] ?? '' ?></textarea>
    </div>
    <?php if (isset($post['id'])): ?>
        <input type="hidden" name="post[id]" value="<?= $post['id'] ?>">
    <?php endif; ?>
    <div>
        <input type="submit" value="Отправить">
    </div>
</form>

<?php include ROOT_DIR . '/app/views/layout/footer.php'; ?>
