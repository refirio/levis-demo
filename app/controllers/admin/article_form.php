<?php

import('libs/plugins/file.php');
import('libs/plugins/ui.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ワンタイムトークン
    if (!token('check')) {
        error('不正なアクセスです。');
    }

    // 入力データを整理
    $post = array(
        'article' => normalize_articles(array(
            'id'       => isset($_POST['id'])       ? $_POST['id']       : '',
            'datetime' => isset($_POST['datetime']) ? $_POST['datetime'] : '',
            'title'    => isset($_POST['title'])    ? $_POST['title']    : '',
            'body'     => isset($_POST['body'])     ? $_POST['body']     : '',
            'public'   => isset($_POST['public'])   ? $_POST['public']   : '',
        ))
    );

    if (isset($_POST['view']) && $_POST['view'] === 'preview') {
        // プレビュー
        $_view['article'] = $post['article'];
    } else {
        // 入力データを検証＆登録
        $warnings = validate_articles($post['article']);
        if (empty($warnings)) {
            $_SESSION['post']['article'] = $post['article'];

            // フォワード
            forward('/admin/article_post');
        } else {
            $_view['article'] = $post['article'];

            $_view['warnings'] = $warnings;
        }
    }
} else {
    // 初期データを取得
    if (empty($_GET['id'])) {
        $_view['article'] = default_articles();
    } else {
        $articles = select_articles(array(
            'where' => array(
                'id = :id',
                array(
                    'id' => $_GET['id'],
                ),
            ),
        ));
        if (empty($articles)) {
            warning('編集データが見つかりません。');
        } else {
            $_view['article'] = $articles[0];
        }
    }

    if (isset($_GET['_type']) && $_GET['_type'] === 'json') {
        // 記事情報を取得
        header('Content-Type: application/json; charset=' . MAIN_CHARSET);

        echo json_encode(array(
            'status' => 'OK',
            'data'   => $_view,
            'files'  => array(
                'image_01' => $_view['article']['image_01'] ? file_mimetype($_view['article']['image_01']) : null,
                'image_02' => $_view['article']['image_02'] ? file_mimetype($_view['article']['image_02']) : null,
            ),
        ));

        exit;
    } else {
        // 投稿セッションを初期化
        unset($_SESSION['post']);
        unset($_SESSION['file']);
    }
}

if ((empty($_POST['view']) || $_POST['view'] !== 'preview')) {
    // 記事の表示用データ作成
    $_view['article'] = view_articles($_view['article']);
}

// タイトル
if (empty($_GET['id'])) {
    $_view['title'] = '記事登録';
} else {
    $_view['title'] = '記事編集';
}
