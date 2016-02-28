<?php

//ワンタイムトークン
if (!token('check')) {
    error('不正なアクセスです。');
}

//トランザクションを開始
db_transaction();

//記事を削除
$resource = delete_articles(array(
    'where' => array(
        'id = :id',
        array(
            'id' => $_POST['id'],
        ),
    ),
));
if (!$resource) {
    error('データを削除できません。');
}

//トランザクションを終了
db_commit();

//リダイレクト
redirect('/admin/article?ok=delete');
