<?php

class PostController extends Controller
{
    // 投稿一覧
    public function indexAction()
    {
        $posts = $this->db_manager->get('Post')
            ->fetchAllLatestPost();
        $gallery_navs = $this->db_manager->get('Post')
            ->getZukanIndex();

        return $this->render(
            [
                'posts'         => $posts, 
                'gallery_navs'  => $gallery_navs, 
            ]
        );
    }

    // 新規投稿
    public function newAction()
    {
        if (!$this->session->isAuthenticated()) {
            $this->session->set('primary', 'アカウントログインが必要です');
            return $this->redirect('/');
        }
        return $this->render(
            [
                'fish_kind'      => '', 
                'fish_spot'      => '', 
                'fish_gear'      => '', 
                'angler_comment' => '', 
                '_token'         => $this->generateCsrfToken('post/new'), 
                'member_id'      => $this->session->getMemberId(), 
            ]
        );
    }

    // 投稿保存
    public function createAction()
    {
        // リクエストをチェック
        if (!$this->request->isPost()) {
            $this->forword404();
        }
        // CSRFトークンのチェック
        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('post/new', $token)) {
            return $this->redirect('/');
        }

        // POSTの中身を変数に格納
        $fish_kind = $this->request->getPost('fish_kind');
        $fish_spot = $this->request->getPost('fish_spot');
        $fish_gear = $this->request->getPost('fish_gear');
        $angler_comment = $this->request->getPost('angler_comment');
        $member_id = $this->request->getPost('member_id');

        // エラーメッセージ
        $errors = [];

        // 入力項目のバリデーション
        if (!strlen($fish_kind)) {
            $errors[] = '魚の種類が入力されていません';
        }
        if (!preg_match("/\A[ァ-ヾ]+\z/u", $fish_kind)) {
            $errors[] = '魚の種類が全角カナで入力されていません';
        }
        // errorの確認と型チェック
        if (!isset($_FILES['pict_file']['error']) || !is_int($_FILES['pict_file']['error'])) {
            $errors[] = 'パラメータが不正です';
        }
        // $_FILES['pict_file']['error'] の値を確認
        switch ($_FILES['pict_file']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                $errors[] = '写真ファイルが選択されていません';
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
                $errors[] = '写真ファイルサイズが大きすぎます';
            default:
                $errors[] = 'その他のエラーが発生しました';
        }
        // 画像ファイルサイズが3MBを超えていないかチェック
        if ($_FILES['pict_file']['size'] > 3000000) {
            $errors[] = '写真ファイルサイズが大きすぎます';
        }
        // 拡張子のチェック
        if (!$extension = array_search(
            mime_content_type($_FILES['pict_file']['tmp_name']),
            [
                'gif' => 'image/gif', 
                'jpg' => 'image/jpeg', 
                'png' => 'image/png'
            ], 
            true
        )) {
            $errors[] = '添付ファイル形式が不正です';
        }
        // 写真ファイルの処理
        $date = date('ymdhis');
        $rename_file = sprintf('member%d_%s.%s', 
            $member_id, 
            $date, 
            $extension
        );
        // ファイルのアップロード
        if (!move_uploaded_file(
            $_FILES['pict_file']['tmp_name'], 
            $path = '../upload/'.$rename_file
            )
        ) {
            $errors[] = '添付ファイル保存時にエラーが発生しました';
        }
        // アップロードした写真ファイルの権限設定
        chmod($path, 0666);

        // バリデーションチェック通過後の処理
        if (count($errors) === 0) {
            $this->db_manager->get('Post')->postInsert($member_id, $fish_kind, $rename_file, $fish_spot, $fish_gear, $angler_comment);

            $this->session->set('primary', '投稿が完了しました');
            return $this->redirect('/');
        }

        return $this->render(
            [
                'errors'         => $errors, 
                'fish_kind'      => $fish_kind, 
                'pict_file'      => $_FILES['pict_file']['tmp_name'], 
                'fish_spot'      => $fish_spot, 
                'fish_gear'      => $fish_kind, 
                'angler_comment' => $angler_comment, 
                '_token'         => $this->generateCsrfToken('post/new'), 
                'member_id'      => $this->session->getMemberId(), 
            ]
            , 'new'
        );
    }

    // 投稿編集
    public function editAction($params)
    {
        // パラメーターの確認
        if (!$params) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        $post_id = (int)$params['id'];
        if (!filter_var($post_id, FILTER_VALIDATE_INT)) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        // 投稿の取得
        $post = $this->db_manager->get('Post')->getShowPost($post_id);
        if (!is_array($post)) {
            $this->session->set('danger', $post);
            return $this->redirect('/');
        }

        $member = $this->session->get('member');
        if ($post['member_id'] === $member['id']) {

            return $this->render(
                [
                    'post'      => $post, 
                    'member_id' => $member['id'], 
                    '_token'    => $this->generateCsrfToken('post/edit'), 
                ]
                , 'edit'
            );
        }
        $this->session->set('danger', '不正なリクエストです');
        return $this->redirect('/');
    }

    // 投稿更新
    public function updateAction($params)
    {
        // リクエストをチェック
        if (!$this->request->isPost()) {
            $this->forword404();
        }
        // CSRFトークンのチェック
        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('post/edit', $token)) {
            return $this->redirect('/');
        }
        // パラメーターの確認
        if (!$params) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        if (!filter_var((int)$params['id'], FILTER_VALIDATE_INT)) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        if ($params['id'] !== $this->request->getPost('id')) {
            $this->session->set('danger', '不正なリクエストです');
            return $this->redirect('/');
        }

        // POSTの中身を変数に格納
        $post_id = (int)$params['id'];
        $fish_kind = $this->request->getPost('fish_kind');
        $fish_spot = $this->request->getPost('fish_spot');
        $fish_gear = $this->request->getPost('fish_gear');
        $angler_comment = $this->request->getPost('angler_comment');
        $member_id = $this->request->getPost('member_id');
        $file_name = $this->request->getPost('file_name');
        
        // 入力項目のバリデーション
        if (!strlen($fish_kind)) {
            $this->session->set('danger', '魚の種類が入力されていません');
            return $this->redirect('/post/show/' . $post_id);
        }
        if (!preg_match("/\A[ァ-ヾ]+\z/u", $fish_kind)) {
            $this->session->set('danger', '魚の種類が全角カナで入力されていません');
            return $this->redirect('/post/show/' . $post_id);
        }

        // 画像ファイルが添付されている場合
        if (!empty($_FILES['pict_file']['name']) && !empty($_FILES['pict_file']['tmp_name'])) {

            // errorの確認と型チェック
            if (!isset($_FILES['pict_file']['error']) || !is_int($_FILES['pict_file']['error'])) {
                $this->session->set('danger', 'パラメータが不正です');
                return $this->redirect('/post/show/' . $post_id);
            }
    
            // $_FILES['pict_file']['error'] の値を確認
            switch ($_FILES['pict_file']['error']) {
                case UPLOAD_ERR_OK: // OK
                    break;
                case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                    break;
                case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
                case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
                    $this->session->set('danger', '写真ファイルサイズが大きすぎます');
                    return $this->redirect('/post/show/' . $post_id);
                default:
                    $this->session->set('danger', 'その他のエラーが発生しました');
                    return $this->redirect('/post/show/' . $post_id);
            }
            // 画像ファイルサイズが3MBを超えていないかチェック
            if ($_FILES['pict_file']['size'] > 3000000) {
                $this->session->set('danger', '写真ファイルサイズが大きすぎます');
                return $this->redirect('/post/show/' . $post_id);
            }
            // 拡張子のチェック
            if (!$extension = array_search(
                mime_content_type($_FILES['pict_file']['tmp_name']),
                [
                    'gif' => 'image/gif', 
                    'jpg' => 'image/jpeg', 
                    'png' => 'image/png'
                ], 
                true
            )) {
                $this->session->set('danger', '添付ファイル形式が不正です');
                return $this->redirect('/post/show/' . $post_id);
            }
    
            // 画像ファイルが変更されているかの確認
            if ($_FILES['pict_file']['name'] !== $file_name) {
                // 古い写真ファイルを削除する
                $old_file_path = '../upload/' . $file_name;
                file_exists($old_file_path) && unlink($old_file_path);
    
                // 写真ファイルの処理
                $date = date('ymdhis');
                $rename_file = sprintf('member%d_%s.%s', 
                    (int)$member_id, 
                    $date, 
                    $extension
                );
                // ファイルのアップロード
                if (!move_uploaded_file(
                    $_FILES['pict_file']['tmp_name'],
                    $path = '../upload/' . $rename_file
                    )
                ) {
                    $this->session->set('danger', 'ファイル保存時にエラーが発生しました');
                    return $this->redirect('/post/show/' . $post_id);
                }
                // アップロードした写真ファイルの権限設定
                chmod($path, 0666);
            }
        }

        // バリデーション通過後
        $update = $this->db_manager->get('Post')->updatePost($post_id, $fish_kind, $rename_file, $fish_spot, $fish_gear, $angler_comment);

        // エラーメッセージが返ってきた場合
        if (!is_bool($update)) {
            $this->session->set('danger', $update);
            return $this->redirect('/');
        }

        $this->session->set('primary', '投稿を編集しました');
        return $this->redirect('/post/show/' . $post_id);

    }

    // 投稿削除
    public function destroyAction($params)
    {
        // パラメーターの確認
        if (!$params) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        $post_id = (int)$params['id'];
        if (!filter_var($post_id, FILTER_VALIDATE_INT)) {
            $this->session->set('danger', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        // 投稿の取得
        $post = $this->db_manager->get('Post')->getShowPost($post_id);
        if (!is_array($post)) {
            $this->session->set('danger', $post);
            return $this->redirect('/');
        }
        // セッションのmember
        $member = $this->session->get('member');

        // 自分の投稿の場合のみ
        if ($post['member_id'] !== $member['id']) {
            $this->session->set('danger', '不正なリクエストです');
            return $this->redirect('/');
        }

        // 投稿の削除
        $delete = $this->db_manager->get('Post')->deletePost((int)$post['id']);
        // エラーメッセージが返ってきた場合
        if (!is_bool($delete)) {
            $this->session->set('danger', $delete);
            return $this->redirect('/');
        }

        // 写真をディレクトリから削除
        if (!empty($post['file_name'])) {
            $delete_file_path = '../upload/' . $post['file_name'];
            file_exists($delete_file_path) && unlink($delete_file_path);
        }

        // 削除成功
        $this->session->set('primary', '投稿を削除しました');
        return $this->redirect('/');
        
    }

    // 投稿検索
    public function searchAction()
    {
        // リクエストをチェック
        if (!$this->request->isGet()) {
            $this->forword404();
        }
        // 検索レコードの取得
        $word = (string)filter_input(INPUT_GET, 'word');
        if (empty($word)) {
            return $this->redirect('/');
        }

        $posts = $this->db_manager->get('Post')->fetchAllSearchPost($word);
        $gallery_navs = $this->db_manager->get('Post')->getZukanIndex();
        
        if (!is_array($posts)) {
            $this->session->set('primary', $posts);
            return $this->redirect('/');
        }

        return $this->render(
            [
                'posts'        => $posts, 
                'gallery_navs' => $gallery_navs, 
            ]
            , 'index'
        );

    }

    public function showAction($params)
    {
        // パラメーターの確認
        if (!$params) {
            $this->session->set('primary', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        $post_id = (int)$params['id'];
        if (!filter_var($post_id, FILTER_VALIDATE_INT)) {
            $this->session->set('primary', '該当の投稿が見つかりません');
            return $this->redirect('/');
        }

        // 投稿の取得
        $post = $this->db_manager->get('Post')->getShowPost($post_id);
        if (!is_array($post)) {
            $this->session->set('primary', $post);
            return $this->redirect('/');
        }

        // コメントの取得
        $comments = $this->db_manager->get('Post')->getPostComments($post_id);
        if (!is_array($comments)) {
            $this->session->set('primary', $comments);
            return $this->redirect('/');
        }

        // いいね数の取得
        $likes = $this->db_manager->get('Post')->getPostLikes($post_id);
        
        $member = $this->session->get('member');
        if ($member) {

            // 自分の投稿かどうか
            if ($post['member_id'] === $member['id']) {
                $mypost = true;
            }

            // いいねをしているか確認
            $like_flg = $this->db_manager->get('Post')->checkLikeExist((int)$member['id'], $post_id);

            // 返り値がboolではない場合、エラーメッセージを返す
            if (!is_bool($like_flg)) {
                $this->session->set('danger', $like_flg);
                return $this->redirect('/');
            }
        }
        
        return $this->render(
            [
                'post'      => $post, 
                'comments'  => $comments, 
                'likes'     => $likes['count_likes'], 
                'like_flg'  => $like_flg, 
                'member_id' => $member['id'], 
                'mypost'    => $mypost, 
            ]
            , 'show'
        );

    }
}