<?php
function pagination($howMany,$connect,$page,$category=''){

//Получение кол-ва обьявлений из БД
    //если выбрали категорию
    if(!empty($category) and $category != 'all') {
        $query = "SELECT COUNT(*) as count FROM ad WHERE id_category = '$category'";
    }
    else{
        $query = "SELECT COUNT(*) as count FROM ad ";
    }
    $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $rows = mysqli_fetch_assoc($result)['count'];
//вычисление колв страниц
    $pages = ceil($rows / $howMany);

    $prev = $page-1;
    $next = $page+1;
    $pagination ='';
    $pagination .= "<div class=\"pages\">";
    //нерабочая ссылка
    if($page != 1){
        $disabled = '';
    }
    else{
        $disabled = 'disabled';
    }
    $pagination .= "<a href=\"?page=$prev\" class=\" $disabled prev\" $disabled>Назад</a>";
    for ($i = 1; $i <= $pages; $i++) {
        //текущая страница
        if($i == $page){
            $active = 'active';
        }
        else{
            $active = "";
        }
        //вывод ссылок
        if($category){
            $getList = "?list=$category&";
        }
        else{
            $getList = '?';
        }
        $pagination .= "<a class=\"$active\" href=\"{$getList}page=$i\">$i</a>";
    }
    if($page == $pages){
        $dis = 'disabled';
    }
    else{
        $dis = '';
    }
    $pagination .= "<a href=\"?page=$next\" class=\"$dis prev\">Вперед</a>";
    $pagination .= "</div>";
    return $pagination;
}