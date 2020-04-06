<?php
include "../elems/link.php";
if(!empty($_SESSION['auth'])) {


    function showEditForm($connect)
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM ad WHERE id = '$id' ";
            $data = mysqli_query($connect, $query) or die(mysqli_error($connect));
            $user = mysqli_fetch_assoc($data);

            if (!empty($user)){

                if (!empty($_POST['text']) and !empty($_POST['price']) and !empty($_POST['phone']) and !empty($_POST['title'])) {

                    $text = mysqli_real_escape_string($connect, $_POST['text']);
                    $category = $_POST['category'];
                    $price = $_POST['price'];
                    $phone = $_POST['phone'];
                    $title = $_POST['title'];
                }
                else{
                    $text = $user['text'];
                    $category = $user['id_category'];
                    $price = $user['price'];
                    $phone = $user['phone'];
                    $title = $user['title'];
                }
                //ob_start();
                include "../elems/form.php";
                //$content = ob_get_clean();
            }
            else{
                $content = 'Page Not Found';
            }
        } else {
            $content = 'Page Not Found';
        }
        include "../elems/layout.php";
    }

    function updAd($connect)
    {
        if (!empty($_POST['text']) and !empty($_POST['title'])) {

            $text = $_POST['text'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $phone = $_POST['phone'];
            $title = $_POST['title'];

            if(isset($_GET['id'])) {

                $id = $_GET['id'];

                if (preg_match('#^\+[0-9]{1,3}\([0-9]{2,3}\)[0-9]{7}$#', $phone) == 1) {

                    $query = "UPDATE ad SET text = '$text',
                     id_category = '$category',price = '$price',phone = '$phone',title = '$title' WHERE id = '$id' ";
                    mysqli_query($connect, $query) or die(mysqli_error($connect));

                    $_SESSION['message'] = ['text' => 'Обьявление изменено',
                        'status' => 'success'];

                } else {
                    $_SESSION['message'] = ['text' => 'Неверный формат номера.Должен быть - +xxx (xx) xxxxxxx',
                        'status' => 'error'];
                }
            }
        }
    }
    updAd($connect);
    showEditForm($connect);
}
else{
    header('Location:login.php');
}