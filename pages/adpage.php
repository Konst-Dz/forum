<?php
include '../elems/link.php';

if(isset($_GET['idAd'])) {
    function showContent($connect)
    {
        $id = $_GET['idAd'];

        $query = "SELECT *,category.id  as cat_id FROM ad 
        LEFT JOIN category ON ad.id_category = category.id LEFT JOIN 
        user ON ad.id_user = user.id WHERE ad.id = '$id'";
        $data = mysqli_query($connect, $query) or die(mysqli_error($connect));
        $user = mysqli_fetch_assoc($data);
        $adUserId = $user['id_user'];

        $content = '';
        $content .= "<p><h3>{$user['title']}</h3></p>";
        $content .= "<p>{$user['text']}</p>";
        $content .= "<span>Цена : {$user['price']}</span><br>";
        $content .= "<span>Контактный номер : {$user['phone']}</span><br>";
        $content .= "<span>Автор : {$user['login']}</span><br>";
        $content .= "<span>Ктегория : {$user['category']}</span>";
        $content .= "<br>";
        //Кнопка поднятия
        if (!empty($_SESSION['auth']) and $adUserId == $_SESSION['id']) {
            $up = $user['last_up'];
            include "../elems/func/buttonUp.php";
            $content .= buttonUp($connect,$id);
        }
        $title = $user['title'];
        include '../elems/layout.php';
    }
    showContent($connect);
    var_dump($_GET);

}

