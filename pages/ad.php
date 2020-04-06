<?php

include "../elems/link.php";

if (isset($_SESSION['auth'])){

    if (!empty($_POST['text']) and !empty($_POST['title'])){

        $text = mysqli_real_escape_string($connect,$_POST['text']);
        $id = $_SESSION['id'];
        $category = $_POST['category'];
        $price = $_POST['price'] ?? 'Договорная';
        $phone = trim($_POST['phone']) ?? 'none';
        $title = $_POST['title'];
        var_dump($phone);

        if (preg_match('#^\+[0-9]{1,3}\([0-9]{2,3}\)[0-9]{7}$#', $phone) == 1){

            $query = "INSERT INTO ad SET text = '$text',
            id_user = '$id' ,id_category = '$category',price = '$price',phone = '$phone',title = '$title',last_up = NOW()";
            mysqli_query($connect, $query) or die(mysqli_error($connect));

            $_SESSION['message'] = ['text' => 'Вы успешно подали обьвление.',
                'status' => 'success'];

        }
        else{
            $_SESSION['message'] = ['text' => 'Неверный формат номера.Должен быть - +xxx (xx) xxxxxxx',
                'status' => 'error'];
        }
    }
    else{
        $text = '';
        $price = '';
        $phone = '';
        $title = '';
        $category = '';
    }
    //форма отрпавки
include "../elems/form.php";

}
else{
    header('Location:../pages/login.php');
}
include "../elems/layout.php";


