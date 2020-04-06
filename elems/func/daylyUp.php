<?php
//Проверка на 24часа
function daylyUp($up){
    $up = strtotime($up);
    $up = time() - $up;
    $oneDay = 60*60*24 ;
    if ($oneDay <= $up){
        return true;
    }
    else{
        return false;
    }
}