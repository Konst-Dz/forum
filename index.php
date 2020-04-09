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
elseif(isset($_GET['topic'])){
    $category = $_GET['topic'];
    getTopicPosts($connect,$category);
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

            $query = "
            SELECT *,(SELECT COUNT(*) as count FROM topic WHERE id_category = '$id') as count FROM topic 
            WHERE id_category = '$id' ORDER BY last_post DESC LIMIT $from,$pages ";
            $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
            $rows = mysqli_fetch_assoc($result)['count'];
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
             var_dump($rows);
            $content = "<p><h2>Темы:</h2></p>";

            foreach ($data as $item) {
                $content .= "<p><a href=\"?topic={$item['id']}\">{$item['name']}</a></p>";
            }
            $content .= add($connect,$category);
            $content .= pagination($connect,$rows,$page);

            include "elems/layout.php";

        }
    }

function pagination($connect,$rows,$page,$partHref)
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
    $pagination .= "<a href=\"{$partHref}page=$prev\" class=\" $disabled prev\" $disabled>Назад</a>";
    for ($i = 1; $i <= $pages; $i++) {
        //текущая страница
        if ($i == $page) {
            $active = 'active';
        } else {
            $active = "";
        }
        $pagination .= "<a class=\"$active\" href=\"{$partHref}page=$i\">$i</a>";
    }
    if ($page == $pages) {
        $dis = 'disabled';
    } else {
        $dis = '';
    }
    $pagination .= "<a href=\"{$partHref}page=$next\" class=\"$dis prev\">Вперед</a>";
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

function getTopicPosts($connect,$category){
    if(isset($_GET['topic'])) {
        $page = $_GET['page'] ?? 1;
        $from = ($page - 1) * PAGES;
        $pages = PAGES;

        $query = "
            SELECT *,(SELECT COUNT(*) as count FROM post WHERE id_topic = '$category') as count
                 FROM post LEFT JOIN user ON user.id = post.id_user WHERE id_topic = '$category' ORDER BY date LIMIT $from,$pages ";
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        $rows = mysqli_fetch_assoc($result)['count'];
        for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;
        $query = "SELECT id,name FROM topic WHERE id = '$category'";
        $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
        $topic = mysqli_fetch_assoc($result);
        $title = $topic['name'];
        $id = $topic['id'];
        $partHref = "?topic={$id}&";

        $content = "<p><h2>Тема:$title</h2></p>";

        foreach ($data as $item) {
            $content .= "<p>{$item['login']} написал :</p>";
            $content .= "<p>{$item['text']}</p><hr>";


        }
        $content .= addPost($connect,$id);
        $content .= pagination($connect, $rows, $page,$partHref);

        include "elems/layout.php";
    }
}

function addPost($connect,$id){
    if (isset($_GET['post'])){
        if (!empty($_POST['text'])) {
            $text = $_POST['text'];
            $query = "INSERT INTO post SET text = '$text', date = NOW() ,
            id_topic = '$id'";
            mysqli_query($connect, $query) or die(mysqli_error($connect));
            $_SESSION['message'] = ['text' => 'Вы успешно отправили сообщение',
                'status' => 'success'];
            //header('Location:../index.php');
        } else {
            $_SESSION['message'] = ['text' => 'Заполните все поля',
                'status' => 'error'];
        }

        $content = "<form method=\"POST\" action=\"\">";
        $content .= "Пост:<br>";
        $content .= "<textarea name=\"text\" cols=\"30\" rows=\"10\" ></textarea><br>";
        $content .= "<input type=\"submit\" ><br></form>";
        return $content;
    }
    else{
        return "<a href=\"?topic={$id}&post=in\">Ответить в тему</a>";
    }
}









