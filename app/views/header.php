<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?php t(MAIN_CHARSET) ?>">
        <title><?php isset($_view['title']) ? h($_view['title'] . ' | ') : '' ?>デモ</title>
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/common.css">
        <link rel="stylesheet" href="<?php t($GLOBALS['config']['http_path']) ?>css/jquery.subwindow.css">
        <?php isset($_view['link']) ? e($_view['link']) : '' ?>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/jquery.subwindow.js"></script>
        <script src="<?php t($GLOBALS['config']['http_path']) ?>js/common.js"></script>
        <?php isset($_view['script']) ? e($_view['script']) : '' ?>
    </head>
    <body>
        <h1>デモ</h1>
        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/">記事一覧</a></li>
            <li><a href="<?php t(MAIN_FILE) ?>/admin">管理者用</a></li>
        </ul>
