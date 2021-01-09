<?php

class MemberController extends Controller
{
    // ログイン画面
    public function loginAction()
    {
        if ($this->session->isAuthenticated()) {
            $this->session->set('primary', 'すでにログインしています');
            return $this->redirect('/');
        }

        return $this->render(
            [
                'mail_address' => '', 
                'password'     => '', 
                '_token'       => $this->generateCsrfToken('member/login'), 
            ]
        );

    }

    // ログイン処理
    public function signinAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('member/login', $token)) {
            return $this->redirect('/member/login');
        }

        $mail_address = $this->request->getPost('mail_address');
        $password     = $this->request->getPost('password');

        $errors = [];
        if (empty($mail_address)) {
            $errors[] = 'メールアドレスが空です';
        }
        if (!filter_var($mail_address, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'メールアドレスを正しいフォーマットで入力してください';
        }
        if (empty($password)) {
            $errors[] = 'パスワードが空です';
        }
        // パスワードの正規表現チェック
        if (!preg_match("/\A[a-zA-Z0-9]+\z/u", $password)) {
            $errors[] = 'パスワードを正しいフォーマットで入力してください';
        }

        // バリデーションをパスした場合
        if (count($errors) === 0) {

            // ユーザーレコードの検索をして、取得する
            $signin = $this->db_manager->get('Member')->memberSignin($mail_address, $password);

            // ユーザーが見つからない場合
            if (empty($signin)) {
                $errors[] = 'メールアドレスまたはパスワードが間違っています';

                return $this->render(
                    [
                        'mail_address' => $mail_address, 
                        'password'     => $password, 
                        'errors'       => $errors, 
                        '_token'       => $this->generateCsrfToken('member/login')
                    ], 
                    'login'
                );
            }
            // ユーザーが見つかった場合
            $this->session->setAuthenticated(true);
            // セッションに保持
            $this->session->setArr('member', $signin[0]);

            return $this->redirect('/');
        }

        // バリデーションにパスできなかった場合
        return $this->render(
            [
                'mail_address' => $mail_address, 
                'password'     => $password, 
                'errors'       => $errors, 
                '_token'       => $this->generateCsrfToken('member/login')
            ], 
            '/member/login'
        );
        
    }
    // ログアウト
    public function signoutAction()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);
        return $this->redirect('/');
    }

    // 新規登録画面
    public function signupAction()
    {
        $member = $this->session->get('member');

        return $this->render(
            [
                'nickname'     => '', 
                'mail_address' => '', 
                '_token'       => $this->generateCsrfToken('member/signup'), 
            ]
        );
    }

    // 新規登録処理
    public function registerAction()
    {
        if (!$this->request->isPost()) {
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('member/signup', $token)) {
            return $this->redirect('/member/signup');
        }

        $nickname     = $this->request->getPost('nickname');
        $mail_address = $this->request->getPost('mail_address');
        $password     = $this->request->getPost('password');
        $repassword   = $this->request->getPost('repassword');

        $errors = [];

        if (!strlen($nickname)) {
            $errors[] = 'ニックネームを入力してください';
        }
        if (empty($mail_address)) {
            $errors[] = 'メールアドレスが空です';
        }
        if (!filter_var($mail_address, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'メールアドレスを正しいフォーマットで入力してください';
        }
        if (empty($password)) {
            $errors[] = 'パスワードが空です';
        }
        // パスワードの正規表現チェック
        if (!preg_match("/\A[a-zA-Z0-9]+\z/u", $password)) {
            $errors[] = 'パスワードを正しいフォーマットで入力してください';
        }
        // パスワードの文字数チェック
        if (!preg_match("/\A([a-zA-Z0-9]{6,})\z/u", $password)) {
            $errors[] = 'パスワードが短すぎます';
        }
        if ($password !== $repassword) {
            $errors[] = 'パスワードが一致しません';
        }
    
        // バリデーションをパスした場合
        if (count($errors) === 0) {

            // レコードを追加して追加レコードのidを格納
            $last_member_id = $this->db_manager->get('Member')->memberInsert($nickname, $mail_address, $password);

            // 追加レコードの情報を格納
            $member = $this->db_manager->get('Member')->fetchByMemberId($last_member_id);
            
            // セッションに保持
            $this->session->set('member', $member);
            $this->session->setAuthenticated(true);

            $this->session->set('primary', '登録ありがとうございました');
            return $this->redirect('/');
        }

        // バリデーションにパスできなかった場合
        return $this->render(
            [
                'nickname'     => $nickname, 
                'mail_address' => $mail_address, 
                'password'     => $password, 
                'errors'       => $errors, 
                '_token'       => $this->generateCsrfToken('member/signup')
            ], 
            'signup'
        );

    }
}