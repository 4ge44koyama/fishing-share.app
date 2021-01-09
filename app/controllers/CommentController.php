<?php

class CommentController extends Controller
{
    // コメント投稿
    public function postAction()
    {
        // フォームの要素を配列に格納する
        isset($_POST) && $comment = $_POST;

        // セッションのメンバー情報を取得
        $member = $this->session->get('member');
        if ($member['id'] !== $comment['member_id']) {
            $this->session->set('danger', '不正なリクエストです');
            return $this->redirect('/');
        }

        if (!strlen($comment['content'])) {
            $this->session->set('danger', '空のコメントはできません');
            return $this->redirect('/');
        }
        // 引数に配列を渡す
        $insert = $this->db_manager->get('Comment')->insertComment($comment);
        // エラーメッセージが返ってきた場合
        if (!is_bool($insert)) {
            $this->session->set('danger', $insert);
            return $this->redirect('/');
        }
        // INSERTしたコメントを取得
        $select = $this->db_manager->get('Comment')->getComment();

        // XSS対策をしてJSONでフロントへ返す
        foreach($select as $key => $val){
            $select[$key] = htmlspecialchars($select[$key], ENT_QUOTES, 'UTF-8');
        }
        
        $select['content'] = nl2br($select['content']);
        $select['created_at'] = substr($select['created_at'], 0, -3);
        header('Content-Type: application/json');
        $json = json_encode($select , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $json;
        
    }
}