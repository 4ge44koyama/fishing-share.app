<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php if (isset($title)) : echo $this->escape($title) . ' - '; endif; ?>多摩川釣り図鑑</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5/css/all.min.css">
    <style type="text/css">
    .post-button {
        background-color: #007bff;
        border-radius: 50%;
        text-align: center;
        position: fixed;
        z-index: 1;
        opacity: 0.9;
    }
    .lazyload {
        width:100%;
        max-width: 100%;
        height: auto;
        border-radius: 0;
    }
    .card-body {
        padding-top: 0;
        padding-bottom: 0.6rem;
    }
    .text-box {
        padding: 0.5rem 0.75rem;
        margin-bottom: 1.0rem;
        border: 1px solid rgba(0,0,0,.125);
    }

    </style>
</head>

<body>
    <!-- ヘッダー -->
    <?php echo $this->render($this->importHeader($request->isSmartPhone()), ['session' => $session, 'request' => $request, 'search' => $request->checkPathInfo()]); ?>

    <!-- コンテンツ -->
    <?php echo $_content; ?>

    <!-- 投稿ボタン -->
    <?php if ($request->checkPathInfo() && $session->isAuthenticated()): ?>
    <?php echo $this->render($this->importPostButton($request->isSmartPhone())); ?>
    <?php endif; ?>

    <!-- フッター -->
    <?php echo $this->render($this->importFooter($request->isSmartPhone())); ?>

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.min.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script>lazyload();</script>
    <script>
        $('#myTab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    </script>
</body>

</html>

