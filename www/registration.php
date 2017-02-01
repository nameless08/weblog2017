<?php

require_once __DIR__ . '/../init.php';

$data = $_POST['user'] ?? [];
$errors = [];
$user = [];

$id =  $data['user'] ?? $_GET['user'] ?? null;//
	if (isset($_SESSION['user']))//isAuthorized()
	{
		header('Location: index.php');//
		exit;
	} 

if ($id) {
  
    $user = userGetById((int) $id);

    if (!$user) 
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not found');
        exit('Пользователь не найден!');
    }
}

$persons = userGetAll();
if ($data) {
	//проверяем заполнение полей
	if (strlen($data['login']) < 2)
	{
		$errors = 'Не введен логин';
	}
	if (strlen($data['password']) == '')
	{
		$errors = 'Не введен пароль';
	}
for ($i = 0; $i < count($persons); $i++)
{
	  foreach ($persons[$i] as $person)
	  {
		if ( in_array($data['login'], $persons[$i]))$errors = 'Такой логин уже имеется.Выберите другой';//
		break;
	  }
}
	if ($data['password'] ) 
	{
	 if (!$errors ) 
	   $data['password']  = password_hash( $data['password'], PASSWORD_DEFAULT  );
	 }
	//
    $user = userSave( $data );//
    //
    if (!$errors) 
    {
        // 
        header('Location: index.php');// registration?'. $user['id']
        exit();
    }
	var_dump($errors);
}

?>

<?php include ROOT_DIR . '/app/views/layout/header.php'; ?>

<!--/////////////////////////////////////////////////////////////---->
<form class="reg-form" method="post">
    <div>
        <label for="user_login">Введите логин</label>
        <input name="user[login]" id="user_login" type="text" value="<?=$user['login'] ?? ''?>">
    </div>
    <div>
        <label for="user_password">Введите пароль</label>
        <input name="user[password]" id="user_password" type="password" value="<?=$user['password'] ?? ''?>">
    </div>
    <?php if (isset($user['id'])): ?>
        <input type="hidden" name="user['id']" value="<?= $user['id']?>">
    <?php endif; ?>
    <div>
        <input type="submit" value="Зарегистрироваться">
    </div>
</form>

<?php include ROOT_DIR . '/app/views/layout/footer.php'; ?>