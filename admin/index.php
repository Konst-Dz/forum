<?php
include "../elems/link.php";

function getUsers($connect){

     $title = 'Админка';
     $query = "SELECT *,user.id as usid FROM user LEFT JOIN status ON user.id_status = status.id";
     $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
     for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

         $content = '';
         $content = "<table><tr><th>Логин</th><th>Статус</th><th>Блокировка</th><th>Смена статуса</th>
         <th>Блокировка</th></tr>";
         foreach ($data as $item) {

         $block = $item['banned'];
         $block = $block ? "Забанен" : "Нет";

         $content .= "<tr><td>{$item['login']}</td><td>{$item['name']}</td>
         <td>$block</td>
         <td><a href=\"?change={$item['usid']}\">Сменить статус</a></td>
         <td><a href=\"?block={$item['usid']}\">Заблокировать</a></td></tr>";

                 }
         $content .= "</table>";

         include_once "dir/layout.php";
}
if(isset($_SESSION['auth']) and $_SESSION['status'] == 'admin'){
    getUsers($connect);
    }
else{
    header('Location:../pages/login.php');die();
}