<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Простой блог на PHP</title>
</head>

<body>
<?php if (isAuthorized()): ?>
	<p>Вы зашли как <?php echo $_SESSION['user']['login']; ?></p>
	<a href="/www/logout.php">Выйти</a>
<?php endif ?>	
