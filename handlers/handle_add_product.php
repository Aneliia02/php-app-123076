<?php

require_once('../functions.php');
require_once('../db.php');

//debug($_POST);
//debug($_FILES);
//exit;
if(!is_admin()){
    $_SESSION['flash']['message']['type'] = 'warning';
    $_SESSION['flash']['message']['text'] = 'Нямате достъп до тази страница!';
    header('Location: ../index.php?page=home');
    exit;
}
$title = trim($_POST['title'] ?? '');
$price = trim($_POST['price'] ?? '');

if (mb_strlen($title) == 0 || mb_strlen($price) == 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Моля попълнете всички полета!';

    header('Location: ../index.php?page=add_product');
    exit;
}

$img_uploaded = false;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $new_filename = time() . '_' . $_FILES['image']['name'];
    $upload_dir = '../uploads/';

    // проверка дали директорията съществува
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);
    }

    // качване на файла
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = 'Възникна грешка при качването на файла!';

        header('Location: ../index.php?page=edit_product&id=' . $product_id);
        exit;
    } else {
        $img_uploaded = true;
    }
}
if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Моля изберете изображение!';

    header('Location: ../index.php?page=add_product');
    exit;
}

$new_filename = time() . '_' . $_FILES['image']['name'];
$upload_dir = '../uploads/';

// проверка дали директорията съществува
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

// качване на файла
if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Възникна грешка при качването на файла!';

    header('Location: ../index.php?page=add_product');
    exit;
} else {
    // запис в базата данни
    $query = "INSERT INTO products (title, price, image) VALUES (:title, :price, :image)";
    $stmt = $pdo->prepare($query);
    $params = [
        ':title' => $title,
        ':price' => $price,
        ':image' => $new_filename
    ];
    if ($stmt->execute($params)) {
        $_SESSION['flash']['message']['type'] = 'success';
        $_SESSION['flash']['message']['text'] = 'Продуктът е добавен успешно!';

        header('Location: ../index.php?page=products');
        exit;
    } else {
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = 'Възникна грешка при добавянето на продукта!';

        header('Location: ../index.php?page=add_product');
        exit;
    }
}
?>