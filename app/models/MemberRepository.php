<?php

class MemberRepository extends DbRepository
{
    const HASH_ALGO = 'sha256';

    // 新規登録
    public function memberInsert($nickname, $mail_address, $password)
    {
        // パスワードのハッシュ化
        $password = $this->hashPassword($password);

        $sql = 'INSERT INTO members(nickname, mail_address, password) 
                VALUES (:nickname, :mail_address, :password)
        ';

        // DBへの書き込み
        $stmt = $this->execute($sql, 
            [
                ':nickname'     => $nickname, 
                ':mail_address' => $mail_address, 
                ':password'     => $password, 
            ]
        );
        // 追加したレコードのidを取得
        $last_member_id = $this->con->lastInsertId();
        return $last_member_id;
    }

    // ログイン
    public function memberSignin($mail_address, $password)
    {
        $password = $this->hashPassword($password);

        $sql = 'SELECT id, nickname
                FROM members 
                WHERE mail_address = :mail_address 
                AND password = :password
        ';
        return $this->fetchAll($sql, [':mail_address' => $mail_address, ':password' => $password]);
    }

    public function fetchByMemberId($id)
    {
        $sql = 'SELECT id, nickname
                FROM members
                WHERE id = :id
        ';
        return $this->fetchAll($sql, [':id' => $id]);
    }

    public function hashPassword($password)
    {
        return hash(self::HASH_ALGO, $password);
    }

    public function fetchByMailAddress($mail_address)
    {
        $sql = 'SELECT * 
                FROM members
                WHERE mail_address = :mail_address
        ';

        return $this->fetch($sql, [':mail_address' => $mail_address]);
    }

    public function isUniqueMailAddress($mail_address)
    {
        $sql = 'SELECT COUNT(id) as count
                FROM members
                WHERE mail_address = :mail_address
        ';

        $row = $this->fetch($sql, [':mail_address' => $mail_address]);
        if ($row['count'] === '0') {
            return true;
        }
        return false;
    }

}