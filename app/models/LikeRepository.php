<?php

class LikeRepository extends DbRepository
{
    // ユーザーが投稿にいいねをしているかを確認
    public function checkLikeExists($member_id, $post_id)
    {
        try {

            $sql = 'SELECT id
                    FROM likes
                    WHERE member_id = ?
                    AND post_id = ?
                    '
            ;

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, (int)$member_id, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$post_id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('いいねが確認できません');
            }
            $like = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($like['id'])) {
                return false;
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿にLikeをする
    public function insertLike($member_id, $post_id)
    {
        try {
            $sql = 'INSERT INTO likes (member_id, post_id) 
                    VALUES (?, ?)
                    '
            ;

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, (int)$member_id, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$post_id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('いいねが保存できません');
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿からLikeを取り消す
    public function deleteLike($member_id, $post_id)
    {
        try {
            $sql = 'DELETE 
                    FROM likes
                    WHERE member_id = ?
                    AND post_id = ?
                    '
            ;

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, (int)$member_id, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$post_id, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('いいねが取り消しできません');
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}