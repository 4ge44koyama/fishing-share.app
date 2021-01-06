<!-- ページタイトル -->
<?php $this->setLayoutVar('title', 'TOP') ?>

<div class="container">
    <!-- 投稿一覧画面 -->
    <div class="row">
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
        <aside class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="tab-content">

                <!-- 投稿一覧タブ -->
                <div class="tab-pane active" id="top" role="tabpanel" aria-labelledby="top-tab">

                <?php if ($posts): ?>
                <?php foreach ($posts as $post): ?>
                    
                    <div class="card" style="margin-top: 16px;">
                        <div class="card-header" style="padding: 0; font-family: serif;">
                            <div class="text-center" style="margin-top: 0.6rem;  line-height: 0rem;">
                                <a><i class="fas fa-fish"></i><?php echo $this->escape($post['fish_kind']); ?></a>
                            </div>
                            <div class="text-right" style="margin-right: 0.6rem;">
                                <a style="font-size: 0.6rem;">投稿者 <span style="font-size: 0.8rem; font-weight: bold;"><?php echo $this->escape($post['nickname']); ?></span>さん</span></a>
                            </div>
                        </div>
                        <div class="card-main" style="object-fit: contain; margin-bottom: 16px;">
                            <img data-src="http://tamagawa-library.localhost/img.php?file=<?php echo $this->escape($post['file_name']); ?>" class="lazyload" alt="多摩川で釣れた<?php echo $this->escape($post['fish_kind']); ?>の画像">
                        </div>
                        <div class="card-body">
                            <p class="card-text" style="margin-bottom: 0.4rem;"><?php echo $this->escape(mb_strimwidth($post['angler_comment'], 0, 200, '…', 'UTF-8')); ?></p>
                            <div class="text-right">
                                <a style="font-size: small;">投稿日: <?php echo $this->escape(substr($post['created_at'], 0, -3)); ?></a>
                            </div>
                            <div class="text-right">
                                <a href="http://tamagawa-library.localhost/post/show/<?php echo $this->escape($post['id']); ?>" class="card-link" style="font-size: small;">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php endif; ?>
                </div>

                <!-- 図鑑リストタブ -->
                <div class="tab-pane" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                    <ul class="list-group mt-4">
                        <?php foreach ($gallery_navs as $nav): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="/post/search?word=<?php echo $this->escape($nav['fish_kind']); ?>"><?php echo $this->escape($nav['fish_kind']); ?></a>
                            <span class="badge badge-primary badge-pill"><?php echo $nav['post_count']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
    </div>
</div>
