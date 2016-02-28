<?php

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//投稿データを確認
if (empty($_SESSION['post'])) {
    //リダイレクト
    redirect('/admin/article_form');
}

//トランザクションを開始
db_transaction();

if (empty($_SESSION['post']['article']['id'])) {
    //記事を登録
    $resource = insert_articles(array(
        'values' => array(
            'datetime' => $_SESSION['post']['article']['datetime'],
            'title'    => $_SESSION['post']['article']['title'],
            'body'     => $_SESSION['post']['article']['body'],
            'public'   => $_SESSION['post']['article']['public'],
        ),
    ), array(
        'files' => array(
            'image_01' => isset($_SESSION['file']['article']['image_01']) ? $_SESSION['file']['article']['image_01'] : array(),
            'image_02' => isset($_SESSION['file']['article']['image_02']) ? $_SESSION['file']['article']['image_02'] : array(),
        ),
    ));
    if (!$resource) {
        error('データを登録できません。');
    }
} else {
    //記事を編集
    $resource = update_articles(array(
        'set'   => array(
            'datetime' => $_SESSION['post']['article']['datetime'],
            'title'    => $_SESSION['post']['article']['title'],
            'body'     => $_SESSION['post']['article']['body'],
            'public'   => $_SESSION['post']['article']['public'],
        ),
        'where' => array(
            'id = :id',
            array(
                'id' => $_SESSION['post']['article']['id'],
            ),
        ),
    ), array(
        'id'    => intval($_SESSION['post']['article']['id']),
        'files' => array(
            'image_01' => isset($_SESSION['file']['article']['image_01']) ? $_SESSION['file']['article']['image_01'] : array(),
            'image_02' => isset($_SESSION['file']['article']['image_02']) ? $_SESSION['file']['article']['image_02'] : array(),
        ),
    ));
    if (!$resource) {
        error('データを編集できません。');
    }
}

//トランザクションを終了
db_commit();

//投稿セッションを初期化
unset($_SESSION['post']);
unset($_SESSION['file']);

//リダイレクト
redirect('/admin/article?ok=post');
