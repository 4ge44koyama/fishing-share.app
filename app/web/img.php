<?php
// 画像を読み込む
$img_name = (string)filter_input(INPUT_GET, 'file');
if ($img_name) {
    $img_dir = '../upload/' . $img_name;
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($img_dir);
    header('Content-Type: ' . $mime_type);
    readfile($img_dir);
}
?>
