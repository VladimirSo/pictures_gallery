<?php 
// echo 'constatns.php';

define('ALLOWED_MIME_TYPES',
    [
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        // 'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        // 'svg' => 'image/svg+xml',
        // 'svgz' => 'image/svg+xml',
    ]
);

//2 Mb = 2 097 152 b
//1 Mb = 1 048 576 b
define('ALLOWED_FILE_SIZE', 2097152);
// define('ALLOWED_FILE_SIZE', 1048576);

?>