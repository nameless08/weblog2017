<?php
require_once __DIR__ . '/../init.php';

$data = $_POST['user'] ?? [];
$errors = [];
$user = [];
if ($data)
{
	$username = $data['login'];
	$password = $data['password'];
	authorize($username, $password);
	if (isAuthorized())
	{
		header('Location: index.php');//
		exit;
	}

}
?>
<?php include ROOT_DIR . '/app/views/layout/header.php'; ?>
<h2>Авторизация</h2>
		<form  class="auth-form" name="auth" action="" method="post">
			<table>
				<tr>
					<td>Логин:</td>
					<td>
						<input type="text" name="user[login]" />
					</td>
				</tr>
				<tr>
					<td>Пароль:</td>
					<td>
						<input type="password" name="user[password]" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="Войти" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<a href="/www/registration.php">Регистрация</a>
					</td>
				</tr>
			</table>
		</form>
		
<?php include ROOT_DIR . '/app/views/layout/footer.php'; ?>
