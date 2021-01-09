<!-- いいねボタン -->
<?php if ($session->isAuthenticated()): ?>
    <?php if ($mypost): ?>
    <div class="text-center">
        <a href="http://tamagawa-library.localhost/post/edit/<?php echo $this->escape($post['id']); ?>" class="btn btn-outline-success" style="font-size: small; padding: 0.25rem 0.5rem; margin: 0.5rem 0.25rem;">編集</a>
        <a href="http://tamagawa-library.localhost/post/delete/<?php echo $this->escape($post['id']); ?>" class="btn btn-outline-danger" style="font-size: small; padding: 0.25rem 0.5rem; margin: 0.5rem 0.25rem;" id="delete-post-btn">削除</a>
    </div>
    <?php else: ?>
    <div class="text-center" id="like-btn-box">
        <?php if ($like_flg): ?>
        <a class="btn btn-primary" style="font-size: small; padding: 0 0.4rem;" id="like-btn-on">いいね<i class="fas fa-thumbs-up"></i></a>
        <?php else: ?>
        <a class="btn btn-outline-primary" style="font-size: small; padding: 0 0.4rem;" id="like-btn-off">いいね<i class="far fa-thumbs-up"></i></a>
        <?php endif; ?>
    </div>
    <input type="hidden" name="member_id" value="<?php echo $this->escape($member_id); ?>" id="like-member-id">
    <input type="hidden" name="post_id" value="<?php echo $this->escape($post['id']); ?>" id="like-post-id">
    <?php endif; ?>
<?php endif; ?>

<div class="container">
    <!-- 投稿詳細画面 -->
    <div class="row">
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
        <aside class="col-sm-12 col-md-8 col-lg-8 col-xl-8">

        <div class="card" style="margin-top: 16px;">
            <div class="card-header" style="padding: 0; font-family: serif;">
                <div class="text-center" style="margin-top: 0.6rem;  line-height: 0rem;">
                    <a><i class="fas fa-fish"></i> <?php echo $this->escape($post['fish_kind']); ?></a>
                </div>
                <div class="text-right" style="margin-right: 0.6rem;">
                    <a style="font-size: 0.6rem;">投稿者 <span style="font-size: 0.8rem; font-weight: bold;"><?php  echo $this->escape($post['nickname']); ?></span>さん</span></a>
                </div>
            </div>
            <div class="card-main" style="object-fit: contain; margin-bottom: 16px;">
                <a style="position: absolute; top: 52px; left: 8px;"><span class="badge badge-pill badge-light"><i class="fas fa-thumbs-up"></i> <span id="like-counter"><?php echo $this->escape($likes); ?></span></span></a>
                <img src="http://tamagawa-library.localhost/img.php?file=<?php echo $this->escape($post['file_name']); ?>" class="card-img" alt="多摩川で釣れた<?php echo $this->escape($post['fish_kind']); ?>の画像" style="border-radius: 0;">
            </div>
            <div class="container">
                <div class="padding-reset" style="padding: 0;">
                    <div class="card-header" style="padding-top: 0.2rem; padding-bottom: 0.2rem;">
                        <a>釣った場所</a>
                    </div>
                    <div class="text-box">
                        <div class="card-text"><?php echo $this->escape($post['fish_spot']); ?></div>
                    </div>
                    <div class="card-header" style="padding-top: 0.2rem; padding-bottom: 0.2rem;">
                        <a>使用道具</a>
                    </div>
                    <div class="text-box">
                        <div class="card-text"><?php echo nl2br($this->escape($post['fish_gear'])); ?></div>
                    </div>
                    <div class="card-header" style="padding-top: 0.2rem; padding-bottom: 0.2rem;">
                        <a>感想</a>
                    </div>
                    <div class="text-box">
                        <div class="card-text"><?php echo nl2br($this->escape($post['angler_comment'])); ?></div>
                    </div>
                    <div class="card-header" style="padding-top: 0.2rem; padding-bottom: 0.2rem;">
                        <a>投稿日時</a>
                    </div>
                    <div class="text-box">
                        <div class="card-text"><?php echo $this->escape($post['created_at']); ?></div>
                    </div>

                    <div class="card-header" style="padding-top: 0.2rem; padding-bottom: 0.2rem; display: flex; justify-content: space-between;">
                        <div>
                            <a>コメント<span class="badge badge-pill badge-secondary" id="comment-count"><?php echo $this->escape(count($comments)); ?>件</span></a>
                        </div>
                        <?php if($session->isAuthenticated()): ?>
                        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#myModal" style="font-size: x-small; padding: 0 0.4rem;">
                            書く
                        </button>
                        <?php endif; ?>
                        <!-- モーダル・ダイアログ -->
                        <div class="modal fade" id="myModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="modal-title">
                                            <a style="font-size: 0.8rem;">コメントする</a>
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div>
                                            <textarea class="form-control" rows="6" placeholder="100文字以内" name="content" id="form-comment-content"></textarea>
                                            <div class="char-counter" style="text-align: right;">
                                                <a style="font-size: small"><span class="show-count">0</span>/100</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal" style="font-size: x-small; padding: 0.4rem 0.6rem;">閉じる</button>
                                        <input type="hidden" name="member_id" value="<?php echo $this->escape($member_id); ?>" id="form-member-id">
                                        <input type="hidden" name="post_id" value="<?php echo $this->escape($post['id']); ?>" id="form-post-id">
                                        <button type="button" class="btn btn-primary" style="font-size: x-small; padding: 0.4rem 0.6rem;" id="form-comment-btn">送信</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- モーダル・ダイアログ -->
                    </div>

                    <!-- コメント一覧ここから -->
                    <div class="comment-lists" id="wrapper-comments">
                    <?php if(!empty($comments)): ?>
                        <?php foreach($comments as $comment): ?>
                        <div class="text-box" style="padding: 0;">
                            <div class="card-text" style="font-size: x-small; padding: 0.2rem 0.4rem;">
                                <a><?php echo $this->escape($comment['nickname']); ?> さん</a>
                            </div>
                            <div class="card-text" style="font-size: small; padding: 0 0.4rem;"><?php echo nl2br($this->escape($comment['content'])); ?></div>
                            <div class="card-text" style="font-size: x-small; text-align: right;"><?php echo $this->escape(substr($comment['created_at'], 0, -3)); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-box" id="comment-none">
                            <div class="card-text">
                                <a style="font-size: small;">この投稿にまだコメントはありません</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                    <!-- コメント一覧ここまで -->

                </div>
            </div>
        </div>
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
    </div>
</div>
