<header>
    <nav class="navbar navbar-light bg-light" style="height: 64px; padding-top: 0; padding-bottom: 0;">
        <div class="header_title">
            <a class="navbar-brand" style="font-size: 0.8rem; padding: 0;">みんなで作る</a><br>
            <a class="navbar-brand" href="/" style="font-size: 1.2rem; padding: 0;">多摩川釣り図鑑</a>
        </div>
        <div class="header-buttons">
            <?php if (empty($session) || !$session->isAuthenticated()): ?>
            <button type="button" class="btn btn-outline-info" onclick="location.href='/member/signup'" style="font-size: 0.6px;">新規登録</button>
            <?php endif ;?>
            <button type="button" class="btn btn-outline-info" onclick="location.href='<?php echo $this->buttonPath($session->isAuthenticated()); ?>'" style="font-size: 0.6px;"><?php echo $this->escape($this->buttonText($session->isAuthenticated())); ?></button>
        </div>
    </nav>
</header>

<!-- フラッシュメッセージ -->
<?php if ($session->checkFlashMsg()): ?>
<?php echo $this->render('flash_msg', ['msg_arr' => $session->getMsg(),]); ?>
<?php endif; ?>

<!-- エラー -->
<?php if (isset($errors) && count($errors) > 0): ?>
<?php echo $this->render('errors', ['errors' => $errors]); ?>
<?php endif; ?>

<?php if ($session->isAuthenticated() && $session->get('member')): ?>
    <div class="user-name" style="text-align: right; margin-top: 0.2rem; margin-right: 0.4rem;">
        <a class="" style="font-size: 0.8rem;"><?php echo $this->escape($session->getNickname()); ?><span style="font-size: 0.6rem;">さん ログイン中</span></a>
    </div>
<?php endif; ?>

<!-- 検索窓 -->
<?php if ($search): ?>
    <form action="/post/search" method="GET" class="form-inline mt-3" style="width: 80%; margin: 0 auto;">
        <input class="form-control" name="word" type="search" placeholder="全角カナで魚種を検索" aria-label="Search" style="width: 80%; margin-right: auto; font-size: 0.8rem;">
        <button class="btn btn-outline-info" style="font-size: 0.6px;" type="submit">検索</button>
    </form>
    <ul class="nav nav-tabs nav-fill mt-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" id="top-tab" href="#top" aria-selected="true" style="font-size: 0.8rem;">最新投稿一覧</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" id="gallery-tab" href="#gallery" aria-selected="false" style="font-size: 0.8rem;">図鑑をみる</a>
        </li>
    </ul>
<?php endif; ?>
