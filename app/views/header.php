<header>
    <nav class="navbar navbar-light bg-light" style="width: 100%; height: 80px; padding: 0 2.5%;">
        <div class="header_title">
            <a class="navbar-brand" style="font-size: 1.0rem; padding: 0;">みんなで作る</a>
            <br>
            <a class="navbar-brand" href="/" style="font-size: 1.4rem; padding: 0;">多摩川釣り図鑑</a>
        </div>
        <!-- PCはヘッダーの中に検索窓 -->
        <?php if ($search): ?>
        <form action="/post/search" method="GET" class="form-inline my-2 my-lg-0" style="width: 30%;">
            <input class="form-control mr-sm-2" name="word" type="search" placeholder="全角カナで魚種を検索" aria-label="Search" style="width: 80%;">
            <button class="btn btn-outline-info my-2 my-sm-0" type="submit">検索</button>
        </form>
        <?php endif; ?>

        <div class="header-buttons">
            <?php if (empty($session) || !$session->isAuthenticated()): ?>
            <button type="button" class="btn btn-outline-info" onclick="location.href='/member/signup'">新規登録</button>
            <?php endif; ?>
            <button type="button" class="btn btn-outline-info" onclick="location.href='<?php echo $this->buttonPath($session->isAuthenticated()); ?>'"><?php echo $this->escape($this->buttonText($session->isAuthenticated())); ?></button>
        </div>
    </nav>
</header>

<?php if (isset($errors) && count($errors) > 0): ?>
<?php $this->render('errors', ['errors' => $errors])?>
<?php endif; ?>

<?php if (isset($session) && $session->get('nickname')): ?>
    <div class="user-name" style="text-align: right; margin-top: 0.2rem; margin-right: 0.4rem;">
        <a class="" style="font-size: 0.8rem; background-color: "><?php echo $this->escape($session->get('nickname')); ?><span style="font-size: 0.6rem;">さん ログイン中</span></a>
    </div>
<?php endif; ?>

<?php if ($search): ?>
    <ul class="nav nav-tabs nav-fill mt-3" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" id="top-tab" href="#top" aria-selected="true" style="font-size: 0.8rem;">最新投稿一覧</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" id="gallery-tab" href="#gallery" aria-selected="false" style="font-size: 0.8rem;">図鑑をみる</a>
        </li>
    </ul>
<?php endif; ?>