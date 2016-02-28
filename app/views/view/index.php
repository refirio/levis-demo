<?php import('app/views/header.php') ?>

        <h2><?php h(localdate('Y/m/d H:i', $view['article']['datetime'])) ?> <?php h($view['article']['title']) ?></h2>
        <p><?php h($view['article']['body']) ?></p>
        <?php if ($view['article']['image_01'] || $view['article']['image_02']) : ?>
            <p>
                <?php if ($view['article']['image_01']) : ?>
                <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['article'] . $view['article']['id'] . '/' . $view['article']['image_01']) ?>" class="image"><img src="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['article'] . $view['article']['id'] . '/thumbnail_' . $view['article']['image_01']) ?>" alt="画像1" /></a>
                <?php endif ?>
                <?php if ($view['article']['image_02']) : ?>
                <a href="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['article'] . $view['article']['id'] . '/' . $view['article']['image_02']) ?>" class="image"><img src="<?php t($GLOBALS['http_path'] . $GLOBALS['file_targets']['article'] . $view['article']['id'] . '/thumbnail_' . $view['article']['image_02']) ?>" alt="画像2" /></a>
                <?php endif ?>
            </p>
        <?php endif ?>

<?php import('app/views/footer.php') ?>
