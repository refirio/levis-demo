<?php import('app/views/admin/header.php') ?>

    <?php if (isset($_POST['preview']) && $_POST['preview'] == 'yes') : ?>
        <h3>確認</h3>
        <dl>
            <dt>日時</dt>
                <dd><?php h(localdate('Y/m/d H:i', $view['article']['datetime'])) ?></dd>
            <dt>タイトル</dt>
                <dd><?php h($view['article']['title']) ?></dd>
            <dt>本文</dt>
                <dd><?php h($view['article']['body']) ?></dd>
            <dt>画像1</dt>
                <dd><img src="<?php t(MAIN_FILE) ?>/admin/file?target=article&amp;key=image_01&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" /></dd>
            <dt>画像2</dt>
                <dd><img src="<?php t(MAIN_FILE) ?>/admin/file?target=article&amp;key=image_02&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" /></dd>
            <dt>公開</dt>
                <dd><?php h($GLOBALS['options']['article']['publics'][$view['article']['public']]) ?></dd>
        </dl>
        <p><a href="#" class="close">閉じる</a></p>
    <?php else : ?>
        <h3><?php h($view['title']) ?></h3>

        <?php if (isset($view['warnings'])) : ?>
        <ul class="warning">
            <?php foreach ($view['warnings'] as $warning) : ?>
            <li><?php h($warning) ?></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>

        <form action="<?php t(MAIN_FILE) ?>/admin/article_form<?php $view['article']['id'] ? t('?id=' . $view['article']['id']) : '' ?>" method="post">
            <fieldset>
                <legend>登録フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />
                <input type="hidden" name="id" value="<?php t($view['article']['id']) ?>" />
                <input type="hidden" name="preview" value="no" />
                <dl>
                    <dt>日時</dt>
                        <dd>
                            <select name="datetime[year]">
                                <?php e(ui_datetime($view['article']['datetime'], 'year', '', '年', localdate('Y') - 5, localdate('Y') + 5)) ?>
                            </select>
                            <select name="datetime[month]">
                                <?php e(ui_datetime($view['article']['datetime'], 'month', '', '月')) ?>
                            </select>
                            <select name="datetime[day]">
                                <?php e(ui_datetime($view['article']['datetime'], 'day', '', '日')) ?>
                            </select>
                            <select name="datetime[hour]">
                                <?php e(ui_datetime($view['article']['datetime'], 'hour', '', '時')) ?>
                            </select>
                            <select name="datetime[minute]">
                                <?php e(ui_datetime($view['article']['datetime'], 'minute', '', '分', 0, 59, 5)) ?>
                            </select>
                        </dd>
                    <dt>タイトル</dt>
                        <dd><input type="text" name="title" size="30" value="<?php t($view['article']['title']) ?>" /></dd>
                    <dt>本文</dt>
                        <dd><textarea name="body" rows="10" cols="50"><?php t($view['article']['body']) ?></textarea></dd>
                    <dt>画像1</dt>
                        <dd class="upload">
                            <a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=article&amp;key=image_01&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?target=article&amp;key=image_01&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" id="image_01" /></a>
                            <div class="file_menu" id="image_01_menu">
                                <ul>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=article&amp;key=image_01&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=article&amp;key=image_01&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>&amp;token=<?php t($view['token']) ?>" id="image_01_delete">削除</a></li>
                                </ul>
                            </div>
                        </dd>
                    <dt>画像2</dt>
                        <dd class="upload">
                            <a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=article&amp;key=image_02&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" title="アップロード" class="file_upload"><img src="<?php t(MAIN_FILE) ?>/admin/file?target=article&amp;key=image_02&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" id="image_02" /></a>
                            <div class="file_menu" id="image_02_menu">
                                <ul>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_upload?target=article&amp;key=image_02&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>" title="アップロード" class="file_upload">差替</a></li>
                                    <li><a href="<?php t(MAIN_FILE) ?>/admin/file_delete?target=article&amp;key=image_02&amp;format=image<?php $view['article']['id'] ? t('&id=' . $view['article']['id']) : '' ?>&amp;token=<?php t($view['token']) ?>" id="image_02_delete">削除</a></li>
                                </ul>
                            </div>
                        </dd>
                    <dt>公開</dt>
                        <dd>
                            <select name="public">
                                <?php foreach ($GLOBALS['options']['article']['publics'] as $key => $value) : ?>
                                <option value="<?php t($key) ?>"<?php $key == $view['article']['public'] ? e(' selected="selected"') : '' ?>><?php t($value) ?></option>
                                <?php endforeach ?>
                            </select>
                        </dd>
                </dl>
                <p>
                    <input type="button" value="確認する" class="preview" />
                    <input type="submit" value="登録する" />
                </p>
            </fieldset>
        </form>

        <?php if (!empty($_GET['id'])) : ?>
        <h3>記事削除</h3>
        <form action="<?php t(MAIN_FILE) ?>/admin/article_delete" method="post" class="delete">
            <fieldset>
                <legend>削除フォーム</legend>
                <input type="hidden" name="token" value="<?php t($view['token']) ?>" />
                <input type="hidden" name="id" value="<?php t($view['article']['id']) ?>" />
                <p><input type="submit" value="削除する" /></p>
            </fieldset>
        </form>
        <?php endif ?>
    <?php endif ?>

<?php import('app/views/admin/footer.php') ?>
