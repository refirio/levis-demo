<?php

/*******************************************************************************

 設定ファイル

*******************************************************************************/

/* 設置ディレクトリ */
$GLOBALS['config']['http_path'] = dirname($_SERVER['SCRIPT_NAME']) . '/';

/* ログイン情報 */
$GLOBALS['config']['administrators'] = array(
    'admin' => array(
        'password' => '1234',
        'address'  => array(),
    ),
);

/* 表示件数 */
$GLOBALS['config']['limits'] = array(
    'article' => 10,
);

/* ページャーの幅 */
$GLOBALS['config']['pagers'] = array(
    'article' => 5,
);

/* オプション項目 */
$GLOBALS['config']['options'] = array(
    'article' => array(
        // 公開
        'publics' => array(
            0 => '非公開',
            1 => '公開',
        ),
    ),
);

/* ファイルアップロード先 */
$GLOBALS['config']['file_targets'] = array(
    'article' => 'files/articles/',
);

/* ファイルアップロード許可 */
$GLOBALS['config']['file_permissions'] = array(
    'file'  => array(
    ),
    'image' => array(
        'png' => array(
            'name'   => 'PNG',
            'ext'    => 'png',
            'regexp' => '/\.png$/i',
            'mime'   => 'image/png',
        ),
        'jpeg' => array(
            'name'   => 'JPEG',
            'ext'    => 'jpg',
            'regexp' => '/\.(jpeg|jpg|jpe)$/i',
            'mime'   => 'image/jpeg',
        ),
        'gif' => array(
            'name'   => 'GIF',
            'ext'    => 'gif',
            'regexp' => '/\.gif$/i',
            'mime'   => 'image/gif',
        ),
    ),
);

/* 代替ファイル */
$GLOBALS['config']['file_alternatives'] = array(
    'file'  => 'images/file.png',
    'image' => null,
);

/* ダミー画像ファイル */
$GLOBALS['config']['file_dummies'] = array(
    'file'  => 'images/no_file.png',
    'image' => 'images/no_file.png',
);

/* 画像リサイズ時のサイズ */
$GLOBALS['config']['resize_width']  = 100;
$GLOBALS['config']['resize_height'] = 80;

/* 画像リサイズ時のJpeg画質 */
$GLOBALS['config']['resize_quality'] = 85;

/* ログインの有効期限 */
$GLOBALS['config']['login_expire'] = 60 * 60;

/* Cookieの有効期限 */
$GLOBALS['config']['cookie_expire'] = 60 * 60;
