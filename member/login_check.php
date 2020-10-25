<?php
session_start();
session_regenerate_id(true);

if(isset($_SESSION['login'])==false){
    echo 'ログインされていません。<br />';
    echo '<a href="#">ログイン画面へ<a/>';
    exit();
}else{
    echo $_SESSION['staff_name'];
    echo 'さんログイン中<br />';
    echo '<br />';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ろくまる農園</title>
</head>
<body>

<?php

require_once('../common/common.php');

$post = sanitize($_POST);
$staff_name=$post['name']; //入力フォームで受け取ったデータを変数にコピー
$staff_pass=$post['pass'];
$staff_pass2=$post['pass2'];


if ($staff_name==''){ // スタッフ名が空の場合
    echo 'スタッフ名が入力されていません。<br />';

}else{ //スタッフ名が正常な場合
    echo 'スタッフ名:';
    echo $staff_name;
    echo '<br />';
}

if($staff_pass==''){ // パスワードが空の場合
    echo 'パスワードが入力されていません。<br />';
}

if($staff_pass!=$staff_pass2){ //パスワードと確認用パスワードが一致しない場合
    echo 'パスワードが一致しません。<br />';
}

if($staff_name==''||$staff_pass==''||$staff_pass!=$staff_pass2){ //入力に不備がある場合、戻るボタンだけ表示させる

    echo '<form>';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '</form>';
}else{ //入力が問題ない場合は確認画面に「OK」と「戻る」ボタンを表示させる

  $staff_pass=md5($staff_pass); //パスワードをMD5に変換して暗号化する
    echo '<form method="post" action="staff_add_done.php">';
    echo '<input type="hidden" name="name" value="'.$staff_name.'">';
    echo '<input type="hidden" name="pass" value="'.$staff_pass.'">';
    echo '<br />';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '<input type="submit" value="OK">';
    echo '</form>';
}

?>

</body>
</html>