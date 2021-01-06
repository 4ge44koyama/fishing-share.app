<?php $this->setLayoutVar('title', '新規投稿') ?>

<div class="container">
    <br/>
    <!-- 新規ユーザー画面 -->
    <div class="row">
        <aside class="col-sm-0 col-md-2 col-lg-2 col-xl-2"></aside>
        <aside class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <div class="card">
                <article class="card-body">
                    <h4 class="card-title text-center mt-4 mb-4">釣果を投稿する</h4>
                    <hr>
                    <p class="text-success text-center">下記の項目を入力してください。</p>
                    <form action="/post/create" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <h3 style="font-size: 1.0rem">魚の種類<span class="badge badge-danger">必須</span><span style="font-size: 0.8rem;">（全角カナのみで入力）</span></h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"> <i class="fas fa-fish"></i></span>
                                </div>
                                <input class="form-control" placeholder="例: ブラックバス" type="text" name="fish_kind" id="fish_kind" value="<?php echo $this->escape($fish_kind); ?>" oninput="CheckFishKind(this)" required>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <h3 style="font-size: 1.0rem">魚の写真<span class="badge badge-danger">必須</span><span style="font-size: 0.8rem;">（jpg, gif, png形式で3MB以内）</span></h3>
                            <div class="preview-box">
                                <img id="preview-img" alt="" style="width: 100%; max-width: 100%; height: auto;">
                            </div>
                            <div class="input-group">
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        写真を選ぶ<input type="file" name="pict_file" id ="pict-preview" style="display:none" accept="image/*">
                                    </span>
                                </label>
                                <input type="text" id="img-title" class="form-control" readonly="">
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <h3 style="font-size: 1.0rem">釣ったポイント</h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                </div>
                                <input class="form-control" placeholder="" type="text" name="fish_spot" value="<?php echo $this->escape($fish_spot); ?>" >
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <h3 style="font-size: 1.0rem">使用した道具</h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-anchor"></i></span>
                                </div>
                                <textarea class="form-control" rows="5" placeholder="" name="fish_gear"><?php echo $this->escape($fish_gear); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h3 style="font-size: 1.0rem">コメント</h3>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-smile"></i></span>
                                </div>
                                <textarea class="form-control" rows="5" placeholder="" name="angler_comment"><?php echo $this->escape($angler_comment); ?></textarea>
                            </div>
                        </div>
                        <br/>
                        <div class="form-group">
                            <div class="col-4" style="margin: 0 auto;">
                                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
                                <input type="hidden" name="member_id" value="<?php echo $this->escape($member_id); ?>">
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
