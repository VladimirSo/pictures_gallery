<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/constants.php');

// var_dump($_FILES);

if (isset($_FILES))
{
    $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
    $array = [];
    $uploadError = '';

    if ( count($_FILES['uploadFile']['name']) < 6 ) 
    {
        for ($i = 0; $i < count($_FILES['uploadFile']['name']); $i++) {
            // echo $_FILES['uploadFile']['name'][$i];

            if (!empty($_FILES['uploadFile']['error'][$i]))
            { 
                if ($_FILES['uploadFile']['error'][$i] === 1 || $_FILES['uploadFile']['error'][$i] === 2)
                {
                    $uploadError = 'Попытка загрузить файл недопустимого размера.';
                } elseif ($_FILES['uploadFile']['error'][$i] === 4) {
                    $uploadError = 'Недопустимое количество файлов.';
                } else {
                    $uploadError = 'Произошла ошибка: ' . $_FILES['uploadFile']['error'][$i];
                }
            } else {
                if (array_search($_FILES['uploadFile']['type'][$i], ALLOWED_MIME_TYPES))
                {
                    $newName = preg_replace('/[^ .\w-]/', '_', $_FILES['uploadFile']['name'][$i]);
                    $_FILES['uploadFile']['name'][$i] = $newName;
                    // echo $_FILES['uploadFile']['name'][$i];
                    move_uploaded_file($_FILES['uploadFile']['tmp_name'][$i], $uploadPath . $_FILES['uploadFile']['name'][$i]);

                    array_push($array, [$_FILES['uploadFile']['name'][$i], $_FILES['uploadFile']['size'][$i]]);
                } else {
                    $uploadError = 'Попытка загрузить файл недопустимого типа.';
                }
            }
        }
    } else {
        $uploadError = 'Недопустимое количество файлов.';
    }

  array_unshift($array, $uploadError);
  echo json_encode((object)$array);
}

?>