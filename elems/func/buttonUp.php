<?php
//Кнопка поднять
function buttonUp($connect,$id){
    if(isset($_POST['up']) and $_POST['up'] == $id){

        $query = "UPDATE ad SET last_up = NOW() WHERE id = '$id' ";
        mysqli_query($connect, $query) or die(mysqli_error($connect));

        $_SESSION['message'] = ['text' => 'Обьявление успешно поднято.Следующее через 24 часа',
        'status' => 'success'];
    }
    //вычисление последнего UP
    $query = "SELECT last_up FROM ad WHERE id = '$id' ";
    $data = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $up = mysqli_fetch_assoc($data)['last_up'];

    require_once "daylyUp.php";
    $dayUp = daylyUp($up);

    if ($dayUp) {
        $disabled = '';
    } else {
        $disabled = 'disabled';
    }
    return "<form method=\"POST\" action = \"\"><button $disabled name=\"up\" value=\"$id\">Поднять обьявление</button></form>";
}
