<?php
require_once('./common.php');
$post = sanitize($_POST);

try {
    $nickname = $post['nickname'];
    $mail_address = $post['mail_address'];
    $password = $post['password'];

     // DB接続の記述ここから
    $dsn = 'mysql:dbname=fishing-share;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'INSERT INTO member(nickname, mail_address, password) VALUES (?, ?, ?)';
    $stmt = $dbh->prepare($sql); // prepareメソッド→引数に指定したSQL文をDBに対して発行してくれる

    $data[] = $nickname;
    $data[] = $mail_address;
    $data[] = $password;
    $stmt->execute($data); // executeメソッドでDBに値を書き込む

    $dbh = null; // DBの接続を切る（必須）

    echo $nickname;
    echo 'さんを追加しました。<br />'; // 追加成功時のメッセージ
} catch (Exception $e) {
    echo '会員登録実行エラー'.__LINE__; // 失敗時のメッセージ表示
    exit(); // 強制終了のアクション
}

?>

<a href="#">戻る</a>
</body>
</html>