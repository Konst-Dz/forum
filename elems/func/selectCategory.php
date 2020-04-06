<?php
function selectCategory($connect,$category = '',$checked='')
{
    $form = '';
    $form .= "<p>Категория:</p>
    <select name=\"category\">";
    $query = "SELECT * FROM category";
    $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
    for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

        foreach ($data as $datum) {
            if($category) {
                //Проверка на selected для edit
                if ($datum['id'] == $category) {
                    $checked = 'selected';
                }
            }

               $form .= "<option value=\"{$datum['id']}\" $checked>{$datum['category']}</option>";
    }
    $form .= "</select>";
    return $form;
}