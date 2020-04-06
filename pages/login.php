<?php
setcookie('name','$login');

include "../elems/link.php";

if (!isset($_SESSION['auth'])) {
    if (!empty($_POST['login']) and !empty($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];

        //селект по логину
        $query = "SELECT * FROM user WHERE login = '$login' ";
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        $user = mysqli_fetch_assoc($result);

        //проверка логина
        if ($user) {
            $hash = $user['password'];
            //проверка хешей
            if (password_verify($password, $hash)) {
                //проверка на бан

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['auth'] = true;
                    $_SESSION['login'] = $user['login'];

                    $_SESSION['message'] = ['text' => 'Вы авторизованы',
                        'status' => 'success'];
                    header('Location:../index.php');
                    die();

            } else {
                echo "Wrong login or password";
            }

        } else {
            echo "Wrong login or password";
        }
    }
    $content = "<form method=\"POST\">
        <input type=\"text\" name=\"login\">Login <br>
        <input type=\"password\" name=\"password\" id=\"\">Password <br>
        <input type=\"submit\"><br>
    </form>";
} else {
    $_SESSION['message'] = ['text' => 'You are already loggined.',
        'status' => 'success'];
    header('Location:../index.php');
    die();
}


include "../elems/layout.php";