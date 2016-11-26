<?php import('app/views/header.php') ?>

        <h2>記事一覧</h2>
        <ul>
            <?php foreach ($_view['articles'] as $article) : ?>
            <li><?php h(localdate('Y/m/d H:i', $article['datetime'])) ?> <a href="<?php t(MAIN_FILE) ?>/view/<?php t($article['id']) ?>"><?php h($article['title']) ?></a></li>
            <?php endforeach ?>
        </ul>

        <?php if ($_view['article_page'] > 1) : ?>
            <h3>ページ移動</h3>
            <p><?php e($_view['article_pager']) ?></p>
        <?php endif ?>

<?php import('app/views/footer.php') ?>
