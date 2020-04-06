<?php
function content($connect,$page,$category = '',$howMany){
    $from = ($page-1) * $howMany;

    if(!empty($category) and $category != 'all') {
        $query = "SELECT title,id_user,last_up,price,category,category.id as cat_id,ad.id as ad_id FROM ad 
LEFT JOIN category ON ad.id_category = category.id WHERE category.id = '$category' ORDER BY last_up DESC LIMIT $from,$howMany";
    }
    else{
        $query = "SELECT title,id_user,last_up,price,category,category.id as cat_id,ad.id as ad_id FROM ad 
LEFT JOIN category ON ad.id_category = category.id ORDER BY last_up DESC LIMIT $from,$howMany";
    }
    $result = mysqli_query($connect,$query) or die(mysqli_error($connect)) ;
    for($data = [];$row = mysqli_fetch_assoc($result);$data[]=$row );

    $content='';
    foreach ( $data as $item) {

        $content .= "<p><a href=\"pages/adpage.php?idAd={$item['ad_id']}\"><h3>{$item['title']}</h3></a></p>";
        $content .= "<span>Цена : {$item['price']}</span><br>";
        $content .= "<span>Категория : {$item['category']}</span>";
        if (!empty($_SESSION['auth']) and $item['id_user'] == $_SESSION['id']) {
            $id = $item['ad_id'];
            //Кнопка UP
            require_once "buttonUp.php";
            $content .= buttonUp($connect,$id);
        }
        $content .= "<hr><br>";

    }
    //Пагинация
    include "pagination.php";
    $content .= pagination($howMany,$connect,$page,$category);
    return $content;
}
