<?php

import('libs/plugins/ui.php');

//ページを取得
if (isset($_GET['page'])) {
    $_GET['page'] = intval($_GET['page']);
} else {
    $_GET['page'] = 1;
}

//記事を取得
$view['articles'] = select_articles(array(
    'where'    => 'public = 1',
    'order_by' => 'datetime DESC',
    'limit'    => array(
        ':offset, :limit',
        array(
            'offset' => $GLOBALS['limits']['article'] * ($_GET['page'] - 1),
            'limit'  => $GLOBALS['limits']['article'],
        ),
    ),
));

$view['article_count'] = select_articles(array(
    'select' => 'COUNT(*) AS count',
    'where'  => 'public = 1',
));
$view['article_count'] = $view['article_count'][0]['count'];
$view['article_page']  = ceil($view['article_count'] / $GLOBALS['limits']['article']);

//ページャー
$pager = ui_pager(array(
    'key'   => 'page',
    'count' => $view['article_count'],
    'size'  => $GLOBALS['limits']['article'],
    'width' => $GLOBALS['pagers']['article'],
    'query' => '?',
));
$view['article_pager'] = $pager['first'] . ' ' . $pager['back'] . ' ' . implode(' | ', $pager['pages']) . ' ' . $pager['next'] . ' ' . $pager['last'];
