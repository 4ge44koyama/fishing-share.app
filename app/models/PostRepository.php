<?php

class PostRepository extends DbRepository
{
    // TOPの投稿一覧取得
    public function fetchAllLatestPost()
    {
        $sql = 'SELECT p.*, m.nickname 
                FROM posts AS p 
                LEFT JOIN members AS m 
                    ON p.member_id = m.id 
                ORDER BY p.id DESC
                ';
        return $this->fetchAll($sql);
    }

    // 検索用レコードの取得
    public function fetchAllSearchPost($str)
    {
        try {
            $sql = "SELECT p.*, m.nickname 
                    FROM posts AS p 
                    LEFT JOIN members AS m 
                        ON p.member_id = m.id 
                    WHERE p.fish_kind LIKE ? ESCAPE '!'
                    ORDER BY p.modified DESC
            ";
            
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, '%'.preg_replace('/(?=[!_%])/', '!', $str).'%');
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('検索結果が取得できません');
            }

            $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$all) {
                throw new Exception('検索条件に該当するものがありません');
            }
            return $all;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 図鑑一覧
    public function getZukanIndex()
    {
        $sql = 'SELECT fish_kind, COUNT(id) AS post_count
                FROM posts
                GROUP BY fish_kind
                ORDER BY fish_kind
        ';
        return $this->fetchAll($sql);
    }

    // 新規投稿の処理
    public function postInsert($member_id, $fish_kind, $file_name, $fish_spot, $fish_gear, $angler_comment)
    {
        $sql = 'INSERT INTO posts(member_id, fish_kind, file_name, fish_spot, fish_gear, angler_comment) 
                VALUES (:member_id, :fish_kind, :file_name, :fish_spot, :fish_gear, :angler_comment)
        ';
        $this->execute($sql, [
                                ':member_id' => $member_id, 
                                ':fish_kind' => $fish_kind, 
                                ':file_name' => $file_name, 
                                ':fish_spot' => $fish_spot, 
                                ':fish_gear' => $fish_gear, 
                                'angler_comment' => $angler_comment, 
                            ]
        );
    }

    // 投稿詳細を取得
    public function getShowPost($post_id)
    {
        try {

            $sql = 'SELECT p.*, m.nickname 
                    FROM posts AS p 
                    LEFT JOIN members AS m 
                        ON p.member_id = m.id 
                    WHERE p.id = ?'
            ;
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $post_id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('投稿が取得できません');
            }

            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$post) {
                throw new Exception('投稿が取得できません');
            }
            return $post;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿へのコメントを取得
    public function getPostComments($post_id)
    {
        try {

            $sql = 'SELECT c.*, m.nickname 
                    FROM comments AS c
                    LEFT JOIN members AS m
                        ON c.member_id = m.id
                    WHERE post_id = ?
                    ORDER BY c.id DESC
            ';

            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $post_id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('コメントが取得できません');
            }

            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $comments;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿のいいね数を取得
    public function getPostLikes($post_id)
    {
        $sql = 'SELECT COUNT(*) AS count_likes 
                FROM likes
                WHERE post_id = :post_id
        ';

        $likes = $this->execute($sql, [':post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);
        return $likes;
    }

    // 投稿にいいねをしているかの確認
    // 返り値：bool
    public function checkLikeExist($member_id, $post_id)
    {
        try {
            $sql = 'SELECT *
                    FROM likes
                    WHERE member_id = ?
                    AND post_id = ?
            ';
    
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $member_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $post_id, PDO::PARAM_INT);

            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('いいねの確認ができません');
            }

            $like_rec = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($like_rec)) {
                return false;
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿の更新
    public function updatePost($post_id, $fish_kind, $rename_file = null, $fish_spot, $fish_gear, $angler_comment)
    {
        try {
            // 写真の変更があるかないか
            if ($rename_file) {
                $sql = 'UPDATE posts 
                        SET fish_kind = :fish_kind, 
                            file_name = :file_name, 
                            fish_spot = :fish_spot, 
                            fish_gear = :fish_gear, 
                            angler_comment = :angler_comment 
                        WHERE posts.id = :post_id'
                ;
            } else {
                $sql = 'UPDATE posts 
                        SET fish_kind = :fish_kind, 
                            fish_spot = :fish_spot, 
                            fish_gear = :fish_gear, 
                            angler_comment = :angler_comment 
                        WHERE posts.id = :post_id'
                ;
            }
            $stmt = $this->con->prepare($sql);
            
            $stmt->bindValue(':fish_kind', $fish_kind);
            isset($rename_file) && $stmt->bindValue(':file_name', $rename_file);
            $stmt->bindValue(':fish_spot', $fish_spot);
            $stmt->bindValue(':fish_gear', $fish_gear);
            $stmt->bindValue(':angler_comment', $angler_comment);
            $stmt->bindValue(':post_id', (int)$post_id, PDO::PARAM_INT);
    
            $result = $stmt->execute();
    
            if (!$result) {
                throw new Exception('投稿の編集に失敗しました');
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // 投稿の削除
    public function deletePost($post_id)
    {
        try {

            $sql = 'DELETE FROM posts WHERE posts.id = ?';
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $post_id, PDO::PARAM_INT);
    
            $result = $stmt->execute();
            if (!$result) {
                throw new Exception('投稿の削除に失敗しました');
            }
            return true;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

?>