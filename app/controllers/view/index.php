<?php

import('libs/plugins/file.php');

// IDを取得
if (isset($_params[1])) {
    $_GET['id'] = $_params[1];
}
if (!isset($_GET['id']) || !preg_match('/^\d+$/', $_GET['id'])) {
    error('不正なアクセスです。');
}

// 記事を取得
$articles = select_articles(array(
    'where' => array(
        'id = :id',
        array(
            'id' => $_GET['id'],
        ),
    ),
));
if (empty($articles)) {
    warning('記事が見つかりません。');
} else {
    $_view['article'] = $articles[0];
}
