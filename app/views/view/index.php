<?php import('app/views/header.php') ?>

        <h2><?php h(localdate('Y/m/d H:i', $_view['article']['datetime'])) ?> <?php h($_view['article']['title']) ?></h2>
        <p><?php h($_view['article']['body']) ?></p>
        <?php if ($_view['article']['image_01'] || $_view['article']['image_02']) : ?>
            <p>
                <?php if ($_view['article']['image_01']) : ?>
                <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['article'] . $_view['article']['id'] . '/' . $_view['article']['image_01']) ?>" class="image"><img src="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['article'] . $_view['article']['id'] . '/thumbnail_' . $_view['article']['image_01']) ?>" alt="画像1" /></a>
                <?php endif ?>
                <?php if ($_view['article']['image_02']) : ?>
                <a href="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['article'] . $_view['article']['id'] . '/' . $_view['article']['image_02']) ?>" class="image"><img src="<?php t($GLOBALS['config']['http_path'] . $GLOBALS['config']['file_targets']['article'] . $_view['article']['id'] . '/thumbnail_' . $_view['article']['image_02']) ?>" alt="画像2" /></a>
                <?php endif ?>
            </p>
        <?php endif ?>

<?php import('app/views/footer.php') ?>
