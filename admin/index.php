<?php
include "../elems/link.php";

function getUsers($connect){

     $query = "SELECT * FROM usere LEFT JOIN status ON user.id_status = status.id";
     $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
     for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

         $content = '';
         $content = "<table><tr><th>Логин</th><th>Статус</th><th>Смена статуса</th>
         <th>Блокировка</th><th>Удаление</th></tr>";
         foreach ($data as $item) {

         $content .= "<tr><td>{$item['login']}</td><td>{$item['name']}</td><td>{$item['login']}</td>
         <td><a href=\"?add={$item['joke_id']}\">Принять</a>
         <td><a href=\"dir/edit.php?edit={$item['joke_id']}&catId={$item['cat_id']}\">Редактировать</a></td>
         <td><a href=\"?delete={$item['joke_id']}\">Удалить</a></td></tr>";

                 }
         $content .= "</table>";
}