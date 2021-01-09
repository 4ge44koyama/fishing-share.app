<?php $this->setLayoutVar('title', 'ログイン'); ?>

<div class="container">
    <br/>
    <!-- 新規ユーザー画面 -->
    <div class="row">
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
        <aside class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card">
                <article class="card-body">
                    <h4 class="card-title text-center mt-4 mb-4">ユーザーログイン</h4>
                    <hr>
                    <p class="text-success text-center">下記の項目を入力してください。</p>
                    <form action="<?php $base_url;?>/member/signin" method="POST">
                        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
                        <div class="form-group">
                            <h3 style="font-size: 1.2rem">メールアドレス<span class="badge badge-danger">必須</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fas fa-signature"></i> </span>
                                </div>
                                <input class="form-control" placeholder="メールアドレスを入力してください。" type="text" name="mail_address" id="mail_address" oninput="CheckMailAddress(this)" required>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <h3 style="font-size: 1.2rem">パスワード<span class="badge badge-danger">必須</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                </div>
                                <input class="form-control" placeholder="パスワードを入力してください。" type="password" name="password">
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <div class="col-4" style="margin: 0 auto;">
                                <input type="submit" class="btn btn-primary btn-block" value="送信">
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </aside>
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
    </div>
</div>
