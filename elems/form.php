<?php

$category = $category ?? '';
$content = "<form method=\"POST\" action=\"\">";
include "func/selectCategory.php";
$content .= selectCategory($connect,$category);
$content .= "<br>";
$content .= "Контактный номер:<br>";
$content .= "<input type=\"phone\" name=\"phone\" placeholder=\"+375 44 1111111\" value=\"$phone\"><br>";
$content .= "Заголовок:<br>";
$content .= "<input type=\"text\" name=\"title\" value=\"$title\"><br>";
$content .= "Ваше обьвление:<br>";
$content .= "<textarea name=\"text\" cols=\"30\" rows=\"10\" >$text</textarea><br>";
$content .= "Цена:<br>";
$content .= "<input type=\"number\" name=\"price\" max='100000' value='$price'><br>";
$content .= "<input type=\"submit\" ><br></form>";