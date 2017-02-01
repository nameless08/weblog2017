<?php

require_once __DIR__ . '/../init.php';

/**/

// $post = isset($posts[$_GET['id']) ? $_GET['id'] : null;
$post = $posts[$_GET['id']] ?? null;

if (!$post) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not found');
    exit('Запись не найдена');
}
?>

<?php include ROOT_DIR . '/app/views/layout/header.php'; ?>

<article>
    <header>
        <h1><?= $post['title'] ?></h1>
        <time datetime="<?= date('Y-m-d', $post['created']) ?>">
            <?= date('Y-m-d H:i', $post['created']) ?>
        </time>
    </header>
    <div>
        <?= $post['content'] ?>
    </div>
    <footer>
        <p>
            Эта статья была обновлена
            <time datetime="<?= date('Y-m-d', $post['updated']) ?>">
                <?= date('Y-m-d H:i', $post['updated']) ?>
            </time>
        </p>
    </footer>
</article>

<?php include ROOT_DIR . '/app/views/layout/footer.php'; ?>
