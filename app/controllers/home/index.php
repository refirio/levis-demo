<?php

import('libs/plugins/ui.php');

// ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;
}

// 記事を取得
$_view['articles'] = select_articles(array(
    'where'    => 'public = 1',
    'order_by' => 'datetime DESC',
    'limit'    => array(
        ':offset, :limit',
        array(
            'offset' => $GLOBALS['config']['limits']['article'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['config']['limits']['article'],
        ),
    ),
));

$_view['article_count'] = select_articles(array(
    'select' => 'COUNT(*) AS count',
    'where'  => 'public = 1',
));
$_view['article_count'] = $_view['article_count'][0]['count'];
$_view['article_page']  = ceil($_view['article_count'] / $GLOBALS['config']['limits']['article']);

// ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $_view['article_count'],
    'size'  => $GLOBALS['config']['limits']['article'],
    'width' => $GLOBALS['config']['pagers']['article'],
    'query' => '?',
));
$_view['article_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];
