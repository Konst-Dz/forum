<?php
include "../elems/link.php";

if(!empty($_SESSION['auth'])){
    function showAds($connect){
        $id = $_SESSION['id'];

        $query = "SELECT id,title FROM ad WHERE id_user = '$id' ";
        $result = mysqli_query($connect,$query) or die(mysqli_error($connect)) ;
        for($data = [];$row = mysqli_fetch_assoc($result);$data[]=$row );

        $content = "<table>";
        $content .= "<tr><th>Заголовок</th><th>Редактировать</th><th>Удалить</th></tr>";
        foreach ($data as $page) {
        $content .= "<tr>
        <td><a href=\"adpage.php?idAd={$page['id']}\">{$page['title']}</a></td>
        <td><a href=\"edit.php?id={$page['id']}\">Редактировать</a></td>
        <td><a href=\"?delete={$page['id']}\">Удалить</a></td>
        </tr>";
        }
        $content .= "</table>";
        $title = 'Управление обьявлениями';
        include "../elems/layout.php";
    }

    function deleteAd($connect){
        if (!empty($_GET['delete'])) {
            $id = $_GET['delete'];
            $query = "DELETE FROM ad WHERE id='$id'";
            $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
            $_SESSION['message'] = ['text' => 'Обьявление удалено',
                'status' => 'success'];
        } else {
            return false;
        }
    }
    deleteAd($connect);
    showAds($connect);
    }
else{
    header('Location:login.php');
}