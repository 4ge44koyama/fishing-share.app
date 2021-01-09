<?php

class LikeController extends Controller
{
    public function switchAction()
    {
        // フォームの要素を配列に格納する
        isset($_POST) && $like = $_POST;

        // セッションのメンバー情報を取得
        $member = $this->session->get('member');
        if ($member['id'] !== $like['member_id']) {
            $this->session->set('danger', '不正なリクエストです');
            return $this->redirect('/');
        }
        // 投稿にいいねをしているかチェック
        $check = $this->db_manager->get('Like')->checkLikeExists($like['member_id'], $like['post_id']);
        // エラーメッセージが返ってきた場合
        if (!is_bool($check)) {
            $this->session->set('danger', $check);
            return $this->redirect('/');
        }

        // INSERTかDELETEか分岐
        if (!$check) {
            $result = $this->db_manager->get('Like')->insertLike($like['member_id'], $like['post_id']);

            $return['component'] = '<a class="btn btn-primary" style="font-size: small; padding: 0 0.4rem;">いいね<i class="fas fa-thumbs-up"></i></a>';
            $return['calc'] = '+1';
        } else {
            $result = $this->db_manager->get('Like')->deleteLike($like['member_id'], $like['post_id']);

            $return['component'] = '<a class="btn btn-outline-primary" style="font-size: small; padding: 0 0.4rem;">いいね<i class="far fa-thumbs-up"></i></a>';
            $return['calc'] = '-1';
        }
        // エラーメッセージが返ってきた場合
        if (!is_bool($result)) {
            $this->session->set('danger', $result);
            return $this->redirect('/');
        }
        // JSONレスポンス
        header('Content-Type: application/json');
        $json = json_encode($return , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $json;
    }
}