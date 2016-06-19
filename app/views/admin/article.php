<?php import('app/views/admin/header.php') ?>

        <h3><?php h($view['title']) ?></h3>

        <ul>
            <li><a href="<?php t(MAIN_FILE) ?>/admin/article_form">記事登録</a></li>
        </ul>
        <?php if (isset($_GET['ok'])) : ?>
        <ul class="ok">
            <?php if ($_GET['ok'] === 'post') : ?>
            <li>データを登録しました。</li>
            <?php elseif ($_GET['ok'] === 'delete') : ?>
            <li>データを削除しました。</li>
            <?php endif ?>
        </ul>
        <?php endif ?>

        <table summary="記事一覧">
            <thead>
                <tr>
                    <th>日時</th>
                    <th>タイトル</th>
                    <th>公開</th>
                    <th>作業</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>日時</th>
                    <th>タイトル</th>
                    <th>公開</th>
                    <th>作業</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach ($view['articles'] as $article) : ?>
                <tr>
                    <td><?php h(localdate('Y/m/d H:i', $article['datetime'])) ?></td>
                    <td><?php h($article['title']) ?></td>
                    <td><?php h($GLOBALS['config']['options']['article']['publics'][$article['public']]) ?></td>
                    <td><a href="<?php t(MAIN_FILE) ?>/admin/article_form?id=<?php t($article['id']) ?>">編集</a></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <?php if ($view['article_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <p><?php e($view['article_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
