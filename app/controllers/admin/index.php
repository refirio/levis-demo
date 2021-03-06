<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ログイン
    foreach ($GLOBALS['config']['administrators'] as $username => $information) {
        if ($_POST['username'] === $username && $_POST['password'] === $information['password']) {
            if (empty($information['address']) || in_array(clientip(), $information['address'])) {
                $_SESSION['auth']['administrator'] = array(
                    'id'   => $_POST['username'],
                    'time' => localdate(),
                );

                break;
            }
        }
    }

    if (empty($_SESSION['auth']['administrator']['id'])) {
        $_view['administrator'] = $_POST;

        $_view['warnings'] = array('ユーザ名もしくはパスワードが違います。');
    }
} else {
    $addresses = array();
    foreach ($GLOBALS['config']['administrators'] as $information) {
        if (!empty($information['address'])) {
            $addresses = array_merge($addresses, $information['address']);
        }
    }
    if (!empty($addresses) && !in_array(clientip(), $addresses)) {
        error('不正なアクセスです。');
    }

    $_view['administrator'] = array(
        'username' => '',
        'password' => '',
    );
}

// ログイン確認
if (!empty($_SESSION['auth']['administrator']['id'])) {
    if ($_REQUEST['_work'] === 'index') {
        // リダイレクト
        redirect('/admin/home');
    } else {
        error('不正なアクセスです。');
    }
}

// タイトル
$_view['title'] = '管理者用';
