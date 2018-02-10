<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/master.css">
        <title><?= $data['title'] ?></title>
    </head>
    <body>
        <?php if (!isset($data['isHome'])) { ?>
            <header>
                <ul class="container-fluid breadcrumb">
                    <li><a href="/"><i class="fa fa-home home" aria-hidden="true"></i></a></li>
                    <?php foreach ($data['breadcrumb'] as $value) { ?>
                        <?php if (isset($value['url'])) { ?>
                            <li><a href="<?= $value['url'] ?>"><?= $value['str'] ?></a></li>
                        <?php } else { ?>
                            <li><?= $value['str'] ?></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <ul class="container-fluid breadcrumb" id="breadcrumb">
                    <li><a href="/"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                    <?php foreach ($data['breadcrumb'] as $value) { ?>
                        <?php if (isset($value['url'])) { ?>
                            <li><a href="<?= $value['url'] ?>"><?= $value['str'] ?></a></li>
                        <?php } else { ?>
                            <li><?= $value['str'] ?></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </header>
        <?php } ?>
        <script type="text/javascript">
        $(function(){
            var breadcrumb = $(".breadcrumb");
            var height = breadcrumb.height();
            var isOpened = false;
            $(window).scroll(function () {
                if ($(this).scrollTop() > height + 25) {
                    if (~isOpened) {
                        $("#breadcrumb").slideDown('fast');
                        isOpened = true;
                    }
                } else {
                    if (isOpened) {
                        $("#breadcrumb").slideUp('fast');
                        isOpened = false;
                    }
                }
            });
        });
        </script>
        <div class="container">
