<?php
include "elems/link.php";
/*var_dump($_SERVER['REQUEST_URI']);
$uri = $_SERVER['REQUEST_URI'];
if ($uri == '/' or $uri == 'index.php'){
    getForumList($connect);
}*/
if (isset($_GET['cat'])){
    $category = $_GET['cat'];
    getForumCategory($connect,$category);
}
else{
    getForumList($connect);
}

    function getForumList($connect){
        $query = "SELECT * FROM category";
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

        $content = "<p><h2>Категории форума</h2></p>";

        foreach ($data as $item) {
            $content .= "<p><a href=\"?cat={$item['id']}\">{$item['name']}</a></p>";
        }

        include "elems/layout.php";
    }

    function getForumCategory($connect,$category){
        if(isset($_GET['cat'])){
            $id = $_GET['cat'];
            $page = $_GET['page'] ?? 1;
            $from = ($page-1) * PAGES;
            $pages = PAGES;

            $query = "SELECT *,COUNT(*) as count FROM topic WHERE id_category = '$id' GROUP BY id HAVING BY last_post DESC LIMIT $from,$pages ;";
            $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
            $rows = mysqli_fetch_assoc($result)['count'];
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
             var_dump($rows);
            $content = "<p><h2>Темы:</h2></p>";

            foreach ($data as $item) {
                $content .= "<p><a href=\"\">{$item['name']}</a></p>";
            }
            $content .= add($connect,$category);
            $content .= pagination($connect,$rows,$page);

            include "elems/layout.php";

        }
    }

function pagination($connect,$rows,$page)
{
//вычисление колв страниц
    $pages = ceil($rows / PAGES);

    $prev = $page - 1;
    $next = $page + 1;
    $pagination = '';
    $pagination .= "<div class=\"pages\">";
    //нерабочая ссылка
    if ($page != 1) {
        $disabled = '';
    } else {
        $disabled = 'disabled';
    }
    $pagination .= "<a href=\"?page=$prev\" class=\" $disabled prev\" $disabled>Назад</a>";
    for ($i = 1; $i <= $pages; $i++) {
        //текущая страница
        if ($i == $page) {
            $active = 'active';
        } else {
            $active = "";
        }
        //вывод ссылок
        /*if ($category) {
            $getList = "?list=$category&";
        } else {
            $getList = '?';
        }*/
        $pagination .= "<a class=\"$active\" href=\"page=$i\">$i</a>";
    }
    if ($page == $pages) {
        $dis = 'disabled';
    } else {
        $dis = '';
    }
    $pagination .= "<a href=\"?page=$next\" class=\"$dis prev\">Вперед</a>";
    $pagination .= "</div>";
    return $pagination;

}

function add($connect,$category){
    if (isset($_GET['add'])){
            if (!empty($_POST['name']) and !empty($_POST['text'])) {
                $name = $_POST['name'];
                $text = $_POST['text'];
                $query = "INSERT INTO topic SET name = '$name', last_post = NOW() ,
            id_category = '$category'";
                mysqli_query($connect, $query) or die(mysqli_error($connect));
                $id = mysqli_insert_id($connect);
                $query = "INSERT INTO post SET text = '$text', date = NOW() ,
            id_topic = '$id'";
                mysqli_query($connect, $query) or die(mysqli_error($connect));
                $_SESSION['message'] = ['text' => 'Вы успешно создали тему',
                    'status' => 'success'];
                //header('Location:../index.php');
            } else {
                $_SESSION['message'] = ['text' => 'Заполните все поля',
                    'status' => 'error'];
            }
            $name = $name ?? '';
            $text = $text ?? '';

        $content = "<form method=\"POST\" action=\"\">";
        $content .= "Новая тема:<br>";
        $content .= "<input type=\"text\" name=\"name\" value=\"$name\"><br>";
        $content .= "Пост:<br>";
        $content .= "<textarea name=\"text\" cols=\"30\" rows=\"10\" >$text</textarea><br>";
        $content .= "<input type=\"submit\" ><br></form>";
        return $content;
    }
    else{
        return "<a href=\"?cat={$category}&add=in\">Создать тему</a>";
    }
}












/*var_dump($_SERVER['REQUEST_URI']);
//список категорий.
$uri = trim(preg_replace('#\?.*#','', $_SERVER['REQUEST_URI']),'/');

var_dump($uri);
$list = '';
$query = "SELECT * FROM category";
$result = mysqli_query($connect,$query) or die(mysqli_error($connect));
for($data = [];$row = mysqli_fetch_assoc($result);$data[] = $row);

$list .= "<ul class=\"list\"><li><a href=\"/all/\">Все обьвления</a></li>";

foreach ($data as $datum) {
    $category = $datum['id'];
    //$list .= "<li><a href=\"?list={$datum['id']}\">{$datum['category']}</a></li>";
    $list .= "<li><a href=\"/$category/\">{$datum['category']}</a></li>";
}
$list .= "</ul>";

//Пагинация
$page = $page ?? 1 ;


//Кол-во записей на странице
$howMany = 5;
//категория обьяв
$category = $uri ?? '' ;

//Вывод  на страницу с пагинацией.
include "elems/func/content.php";
$content = content($connect,$page,$category,$howMany);*/