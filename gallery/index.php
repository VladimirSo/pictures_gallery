<?php 
require_once ($_SERVER['DOCUMENT_ROOT'] . '/data/constants.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/src/get_readable_filesize.php');

$contentDir = $_SERVER['DOCUMENT_ROOT'] . '/upload/';
$contentArr = [];

if (isset($_POST['delete'])) {
    $arrForDel = [];

    if (isset($_POST['delAll'])) {
        $arrForDel = array_diff(scandir($contentDir), array('..', '.'));

    } else {
        foreach ( $_POST as $key => $value ) {
            if (substr($key, 0, 6) === 'delImg') {
                array_push($arrForDel, $value);
            }
        }
    }

    foreach($arrForDel as $file) {
        $delFile = $contentDir . $file;
        unlink($delFile);
    }

    header('Location: .');
} else {
    if ($handle = opendir($contentDir)) {
        while (false !== ($entry = readdir($handle))) {
            $file = $contentDir . $entry;

            if (is_file($file)) {
                if (array_search(mime_content_type($file), ALLOWED_MIME_TYPES)) {
                    array_push($contentArr, $entry);
                    sort($contentArr);
                }
            }
        }
        closedir($handle);
    }
    // var_dump($contentArr);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Галерея изображений</title>
    <link href="./styles.css" rel="stylesheet">
</head>
<body>
    <main>
        <section class="gallery">
            <h1 class="gallery__title">Галерея изображений</h1>

            <div class="upload">
                <h2 class="upload__title">Загрузите файл:</h1>

                <form class="upload__form form js-upload-form" enctype="multipart/form-data" method="POST" action="#" >
                    <span class="form__caveat">*Допустима загрузка не больше 5-ти изображений с размером не больше <?= ALLOWED_FILE_SIZE/1048576; ?> Mb!</span>

                    <input type="hidden" name="MAX_FILE_SIZE" value="<?= ALLOWED_FILE_SIZE ?>" />

                    <input class="form__select" type="file" name="uploadFile[]" multiple />

                    <input class="form__btn" type="submit" name="upload" value="Загрузить" />
                </form>
            </div>

            <ul class="gallery__list gallery-list js-gallery-list">
<?php 
for ($i = 0; $i < count($contentArr); $i++) {
    $img = $contentArr[$i];
?>
                <li class="gallery__item picture">
                    <div class="picture__wrapper">

                        <img class="picture__img" src="<?= '../upload/' . $img; ?>" alt="<?= $img; ?>"></img>
                    </div>

                    <span class="picture__data">Размер изображения: <?= getReadableFileSize( filesize('../upload/' . $img) ); ?></span>

                    <label class="picture__check">
                        Удалить изображение:&nbsp;
                        <input type="checkbox" form="deleteImgs" name="<?= 'delImg' . $i; ?>" value="<?= $img; ?>">
                    </label>
                </li>
<?php
}
?>
            </ul>
<?php 
if (count($contentArr) === 0) {
?>
            <!-- <p class="gallery__desc">Нет загруженных изображений.</p> -->
<?php
}
?>
            <form id="deleteImgs" class="gallery__form" name="deleteImgs" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <label class="gallery__check">
                    Удалить <span class="accent">все</span> изображения:&nbsp;
                    <input type="checkbox" form="deleteImgs" name="delAll" value="delAll">
                </label>

                <button class="gallery__del" name="delete" value="delImgs" type="submit">Удалить</button>
            </form>
        </section>
    </main>

    <script type="text/javascript" src="./main.js"></script>
</body>
</html>