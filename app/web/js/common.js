// フロントで名前欄の空を判定
function CheckNameBlank(nickname) {
    // 入力値取得
    const InputValue = $('#nickname').val();
    nickname.setCustomValidity('');
    if (!InputValue) {
        nickname.setCustomValidity("名前を入力してください。");
    }
}

// フロントでメールアドレス欄のフォーマットを判定
function CheckMailAddress(mail_address) {
    // 入力値取得
    const InputValue = $('#mail_address').val();
    const mail_regex1 = new RegExp('(?:[-!#-\'*+/-9=?A-Z^-~]+\.?(?:\.[-!#-\'*+/-9=?A-Z^-~]+)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~])*")@[-!#-\'*+/-9=?A-Z^-~]+(?:\.[-!#-\'*+/-9=?A-Z^-~]+)*');
    mail_address.setCustomValidity('');
    const mail_regex2 = new RegExp( '^[^\@]+\@[^\@]+$' );
    if (!InputValue) {
        mail_address.setCustomValidity("メールアドレスを入力してください。");
    }
    if (InputValue.match( mail_regex1 ) && InputValue.match(mail_regex2)) {
        // 全角チェック
        if (InputValue.match(/[^a-zA-Z0-9\!\"\#\$\%\&\'\(\)\=\~\|\-\^\\\@\[\;\:\]\,\.\/\\\<\>\?\_\`\{\+\*\} ]/) ) 
        { 
            mail_address.setCustomValidity("正しいフォーマットで入力してください。"); 
        }
        // 末尾TLDチェック（〜.co,jpなどの末尾ミスチェック用）
        if (!InputValue.match(/\.[a-z]+$/) ) 
        { 
            mail_address.setCustomValidity("正しいフォーマットで入力してください。"); 
        }
    } else {
        mail_address.setCustomValidity("正しいフォーマットで入力してください。");
    }
}

function MailCheck(mail) {
    const mail_regex1 = new RegExp('(?:[-!#-\'*+/-9=?A-Z^-~]+\.?(?:\.[-!#-\'*+/-9=?A-Z^-~]+)*|"(?:[!#-\[\]-~]|\\\\[\x09 -~])*")@[-!#-\'*+/-9=?A-Z^-~]+(?:\.[-!#-\'*+/-9=?A-Z^-~]+)*');
    const mail_regex2 = new RegExp('^[^\@]+\@[^\@]+$');
    if (mail.match(mail_regex1) && mail.match(mail_regex2)) {
        // 全角チェック
        if (mail.match(/[^a-zA-Z0-9\!\"\#\$\%\&\'\(\)\=\~\|\-\^\\\@\[\;\:\]\,\.\/\\\<\>\?\_\`\{\+\*\} ]/)) { 
            return false; 
        }
        // 末尾TLDチェック（〜.co,jpなどの末尾ミスチェック用）
        if (!mail.match(/\.[a-z]+$/)) { 
            return false; 
        }
        return true;
    } else {
        return false;
    }
}

// フロントでパスワードの文字数と入力値をチェック
function PasswordFilter(password) {
    // 入力値取得
    const checkPassword = $('#password').val();
    const rePassword = $('#repassword').val();
    password.setCustomValidity('');
    
    if (!checkPassword) {
        password.setCustomValidity('パスワードを入力してください。');
    }
    if (!checkPassword.match(/^([a-zA-Z0-9]{6,})$/)) {
        password.setCustomValidity('パスワードは半角英数字で6文字以上です');
    }
    if (checkPassword.length < 6 ) {
        password.setCustomValidity('パスワードが短すぎます。');
    }
    if (typeof rePassword != 'undefined' && checkPassword != rePassword) {
        repassword.setCustomValidity("パスワードが一致しません。");
    }
}

// フロントでパスワードと確認用パスワードの一致を判定
function CheckPassword(repassword) {
    // 入力値取得
    const pass = $('#password').val();
    const repass = $('#repassword').val();
    repassword.setCustomValidity('');
    // パスワード比較
    if (pass != repass) {
        repassword.setCustomValidity("パスワードが一致しません。");
    }
}

// 魚の種類を全角で入力しているかチェック
function CheckFishKind(fish_kind) {
    const fishKind = $('#fish_kind').val();
    fish_kind.setCustomValidity('');
    if (!fishKind) {
        fish_kind.setCustomValidity("魚の種類を入力してください。");
    }
    if (!fishKind.match(/^[ァ-ヶー]+$/)) {
        fish_kind.setCustomValidity("全角カナで入力してください。");
    }
}

// フロントの画像プレビュー用
$(function(){
    $('#pict-preview').change(function(e){
    //ファイルオブジェクトを取得する
    const file = e.target.files[0];
    const label = file.name.replace(/\\/g, '/').replace(/.*\//, '');

    //画像でない場合は処理終了
    if (file.type.indexOf("image") < 0) {
        alert("画像ファイルを指定してください。");
        return false;
    }
    
    const reader = new FileReader();
    //アップロードした画像を設定する
    reader.onload = (function(){
    return function(e){
        $('#img-title').val(label);
        $('#preview-img').attr("src", e.target.result);
    };
    })(file);
    reader.readAsDataURL(file);
    });
});

// 投稿削除の確認用
$('#delete-post-btn').click(function(){
    if(!confirm('本当に削除してよろしいですか？')){
        return false;
    }
});

// 投稿コメントの文字カウント
$(function(){
    $('#form-comment-content').keyup(function(){
        const charCounter = $(this).val().length;
        $('.show-count').text(charCounter);
        if ($(this).val().length > 100) {
            $('.show-count').css('color', 'red');
        } else {
            $('.show-count').css('color', '');
        }
    });
});

// 投稿コメント保存用
$("#form-comment-btn").on('click', function(){
    const commentContent = $("#form-comment-content").val();
    const memberId = $("#form-member-id").val();
    const postId = $("#form-post-id").val();
    // 入力値のチェック
    if (!commentContent) {
        alert('コメントを入力してください');
        return false;
    }
    if (commentContent.length > 100) {
        alert('コメントは100文字以内で入力してください');
        return false;
    }
    // input hiddenの要素
    if (!memberId || !postId) {
        alert('送信した内容が不正です');
        return false;
    } else if (isNaN(memberId)==true || isNaN(postId)==true) {
        alert('送信した内容が不正です');
        return false;
    }
    const postData = {
                        "content": commentContent, 
                        "member_id": memberId, 
                        "post_id": postId
                    }
    $.post("http://tamagawa-library.localhost/comment/post", 
        postData, 
    )
    .done(function(data){
        let insertHtml = '<div class="text-box"  style="padding: 0;">';
                insertHtml += '<div class="card-text" style="font-size: x-small; padding: 0.2rem 0.4rem;">';
                    insertHtml += `<a>${data.nickname}さん</a>`;
                insertHtml += '</div>';

                insertHtml += `<div class="card-text" style="font-size: small; padding: 0 0.4rem;">${data.content}</div>`;
                insertHtml += `<div class="card-text" style="font-size: x-small; text-align: right;">${data.created_at}</div>`;
            insertHtml += '</div>';
        $('#wrapper-comments').prepend(insertHtml);
        $('#myModal').modal('hide');
        $('#form-comment-content').val('');
        const getCommentCount = $('#comment-count').text();
        const plusCommentCount = parseInt(getCommentCount, 10) + 1;
        $('#comment-count').text( plusCommentCount + '件' );
        $('#comment-none').remove();
        alert('コメントをしました');
        return;
    })
    .fail(function(){
        alert('コメントの書き込みに失敗しました');
        return;
    })
});

// いいねボタン
$('#like-btn-box').on('click', 'a', function(){
    const likeMemberId = $("#like-member-id").val();
    const likePostId = $("#like-post-id").val();
    // 入力値のチェック
    if (!likeMemberId || !likePostId) {
        alert('送信した内容が不正です');
        return false;
    } else if (isNaN(likeMemberId)==true || isNaN(likePostId)==true) {
        alert('送信した内容が不正です');
        return false;
    }
    const postData = {
                        "member_id": likeMemberId, 
                        "post_id": likePostId
                    }
    $.post("http://tamagawa-library.localhost/like/switch", 
        postData, 
    )
    .done(function(data){
        let insertHtml = data.component;
            insertHtml += `<input type="hidden" name="member_id" value="${likeMemberId}" id="like-member-id">`;
            insertHtml += `<input type="hidden" name="post_id" value="${likePostId}" id="like-post-id">`;
        $('#like-btn-box').empty();
        $('#like-btn-box').prepend(insertHtml);
        let likeCounter = $('#like-counter').text();
            likeCounter = parseInt(likeCounter, 10) + parseInt(data.calc, 10);
        $('#like-counter').text(likeCounter);
        return;
    })
    .fail(function(){
        alert('いいねに失敗しました');
        return;
    })
})