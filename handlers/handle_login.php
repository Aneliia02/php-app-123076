<?php
//<!-- масив с потребителски данни за логин  -->

require_once('../functions.php');
require_once('../db.php');
$email = $_POST['email'];
$password = $_POST['password'] ?? '';
//проверяваме дали потребителя съществува
$query = "SELECT * FROM users WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt ->execute([':email' => $email]);
$user = $stmt->fetch();
//var_dump($user);
//debug($user,true);

if(!$user){
    header('Location: ../index.php?page=register&error=Грешен имейл или парола');
    exit;

}
if(!password_verify($password,$user['password'])){
    header('Location: ../index.php?page=register&error=Грешен имейл или парола');
    exit;
}

session_start();
$_SESSION['user_name']=$user['names'];
$_SESSION['user_email']=$user['email'];
$_SESSION['user_id']=$user['id'];
$_SESSION['is_admin']=$user['is_admin'] == 2;



        //setvame biskvitkA
setcookie('user_email',$user['email'], time()+3600,'/','localhost',false,true);
/*$users = [
    [
        'email' => 'john@gmail.com',
        'password' => '123456',
        'name' => 'John Jones',
        'hash' => '$argon2i$v=19$m=65536,t=4,p=1$R25hdm9yUmtTd1NuU0NKag$dbEY5YvbGCEK0M9/S3z8zbnFBXjjhFQkG+0lQzjh0+E', 
    ],
    [
        'email' => 'ana@gmail.com',
        'password' => 'qwerty',
        'name' => 'Ana Smith',
        'hash' => '$argon2i$v=19$m=65536,t=4,p=1$bEEwVFk5MXVsc3FDa2tXUg$igQhnqLynuNdW/r2l2JNEexfsl5huLnKa1/7oeMKIr4', 
    ],
    [
        'email' => 'ivan@gmail.com',
        'password' => 'asd123',
        'name' => 'Ivan Ivanov',
        'hash' => '$argon2i$v=19$m=65536,t=4,p=1$R3pZSEouUTNNbXF5OXVSdA$2MQ6pziXmL3ix4Cw31vGG3INY5Gu8D/BTZ2RXRebw2c', 
    ],
];
*/
/*foreach($users as $user){
    if($user['email']==$_POST['email'] && password_verify($_POST['password'], $user['hash'])){

        session_start();
        $_SESSION['user_name']=$user['name'];
        $_SESSION['use_email']=$user['email'];

        //setvame biskvitkA
        setcookie('user_email',$user['email'], time()+3600,'/','localhost',false,true);
       
    }
    else{
        //debug('fail');
    }

}*/

header('Location: ../index.php');

exit;

?>