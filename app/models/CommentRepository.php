<?php

class CommentRepository extends DbRepository
{
    // 新規コメント
    public function insertComment($comment = [])
    {
        try {
            $sql = 'INSERT INTO comments(member_id, post_id, content) VALUES (:member_id, :post_id, :content)';
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(':member_id', (int)$comment['member_id'], PDO::PARAM_INT);
            $stmt->bindValue(':post_id', (int)$comment['post_id'], PDO::PARAM_INT);
            $stmt->bindValue(':content', $comment['content']);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('投稿コメントが保存できません');
            }

            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿コメントの取得
    public function getComment()
    {
        try {
            $sql = 'SELECT c.*, m.nickname 
                    FROM comments AS c
                    LEFT JOIN members AS m 
                        ON c.member_id = m.id 
                    WHERE c.id = :comment_id
                    '
            ;
            $stmt = $this->con->prepare($sql);
            $comment_id = $this->con->LastInsertId();
            $stmt->bindValue(':comment_id', (int)$comment_id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('投稿コメントが取得できませんでした');
            }

            $comment = $stmt->fetch(PDO::FETCH_ASSOC);
            return $comment;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}