<?php
require_once('../functions.php');
require_once('../db.php');
//Да проверим дали взема данните чрез метода POST 
//проверяваме чрез функцията debug

//debug($_POST,true);
//exit;

$error = '';
foreach($_POST as $key => $value){
    if(mb_strlen($value) == 0 ){
        $error = 'Моля попълнете всички полета!';
        break;
    }
}

if(mb_strlen($error)>0){
    $error="Възникна грешка";
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text']= $error;
    $_SESSION['flash']['data']= $_POST;
    header('Location: ../index.php?page=register');
    exit;
}
else {
    $names=trim($_POST['names']);
    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
    $repeat_password=trim($_POST['repeat_password']);
    $is_admin=intval($_POST['is_admin'] ?? 0);
    //ще проверим дали съществува потребител с този имейл
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt ->execute([$email]);
    $user = $stmt->fetch();

  
    if($user){
        $error="Възникна грешка";
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text']= $error;
        $_SESSION['flash']['data']= $_POST;
        header('Location: ../index.php?page=register');
        exit;
    }
    if (!in_array($is_admin, [1,2])) {
        $error = 'Невалиден тип потребител!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        $_SESSION['flash']['data'] = $_POST;

        header('Location: ../index.php?page=register');
        exit;
    }
    if($password != $repeat_password){
        $error="Паролите не съвпадат";
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text']= $error;
        $_SESSION['flash']['data']= $_POST;
        header('Location: ../index.php?page=register');
        exit;
    }
    else{
        //echo "Всичко е ок!"; exit;
        $password = password_hash($password, PASSWORD_ARGON2I);
        $query="INSERT INTO users (names, email, `password`, is_admin) VALUES (:names, :email, :password, :is_admin)";
        //debug($query,true);
        $stmt = $pdo ->prepare($query);
        $params = [
            ':names' => $names,
            ':email' => $email,
            ':password' => $password,
            ':is_admin' => $is_admin
        ];
        if($stmt -> execute($params) ){
            $_SESSION['flash']['message']['type'] = 'success';
            $_SESSION['flash']['message']['text']= 'Успешна регистрация';
            $_SESSION['flash']['data']= $_POST;
            header('Location: ../index.php?page=home');
            exit;
        }
        else{
            $error="Възникна грешка";
            $_SESSION['flash']['message']['type'] = 'danger';
            $_SESSION['flash']['message']['text']= $error;
            $_SESSION['flash']['data']= $_POST;
            header('Location: ../index.php?page=register');
            exit;
        }
        
    }

}


?>