<?php

import('libs/plugins/validator.php');
import('libs/plugins/file.php');
import('libs/plugins/directory.php');

/**
 * 記事の取得
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function select_articles($queries, $options = array())
{
    $queries = db_placeholder($queries);

    //記事を取得
    $queries['from'] = DATABASE_PREFIX . 'articles';

    //削除済みデータは取得しない
    if (!isset($queries['where'])) {
        $queries['where'] = 'TRUE';
    }
    $queries['where'] = 'deleted IS NULL AND (' . $queries['where'] . ')';

    //データを取得
    $results = db_select($queries);

    return $results;
}

/**
 * 記事の登録
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function insert_articles($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'files' => isset($options['files']) ? $options['files'] : array(),
    );

    //初期値を取得
    $defaults = default_articles();

    if (isset($queries['values']['created'])) {
        if ($queries['values']['created'] === false) {
            unset($queries['values']['created']);
        }
    } else {
        $queries['values']['created'] = $defaults['created'];
    }
    if (isset($queries['values']['modified'])) {
        if ($queries['values']['modified'] === false) {
            unset($queries['values']['modified']);
        }
    } else {
        $queries['values']['modified'] = $defaults['modified'];
    }

    //データを登録
    $queries['insert_into'] = DATABASE_PREFIX . 'articles';

    $resource = db_insert($queries);
    if (!$resource) {
        return $resource;
    }

    //IDを取得
    $id = db_last_insert_id();

    if (!empty($options['files'])) {
        //関連するファイルを削除
        remove_articles($id, $options['files']);

        //関連するファイルを保存
        save_articles($id, $options['files']);
    }

    return $resource;
}

/**
 * 記事の編集
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function update_articles($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'id'    => isset($options['id'])     ? $options['id']     : null,
        'files' => isset($options['files'])  ? $options['files']  : array(),
    );

    //初期値を取得
    $defaults = default_articles();

    if (isset($queries['set']['modified'])) {
        if ($queries['set']['modified'] === false) {
            unset($queries['set']['modified']);
        }
    } else {
        $queries['set']['modified'] = $defaults['modified'];
    }

    //データを編集
    $queries['update'] = DATABASE_PREFIX . 'articles';

    $resource = db_update($queries);
    if (!$resource) {
        return $resource;
    }

    //IDを取得
    $id = $options['id'];

    if (!empty($options['files'])) {
        //関連するファイルを削除
        remove_articles($id, $options['files']);

        //関連するファイルを保存
        save_articles($id, $options['files']);
    }

    return $resource;
}

/**
 * 記事の削除
 *
 * @param  array  $queries
 * @param  array  $options
 * @return resource
 */
function delete_articles($queries, $options = array())
{
    $queries = db_placeholder($queries);
    $options = array(
        'softdelete' => isset($options['softdelete']) ? $options['softdelete'] : true,
        'file'       => isset($options['file'])       ? $options['file']       : false,
    );

    //削除するデータのIDを取得
    $articles = db_select(array(
        'select' => 'id',
        'from'   => DATABASE_PREFIX . 'articles AS articles',
        'where'  => isset($queries['where']) ? $queries['where'] : '',
        'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
    ));

    $deletes = array();
    foreach ($articles as $article) {
        $deletes[] = intval($article['id']);
    }

    if ($options['softdelete'] === true) {
        //データを編集
        $resource = db_update(array(
            'update' => DATABASE_PREFIX . 'articles AS articles',
            'set'    => array(
                'deleted' => localdate('Y-m-d H:i:s'),
            ),
            'where'  => isset($queries['where']) ? $queries['where'] : '',
            'limit'  => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    } else {
        //データを削除
        $resource = db_delete(array(
            'delete_from' => DATABASE_PREFIX . 'articles AS articles',
            'where'       => isset($queries['where']) ? $queries['where'] : '',
            'limit'       => isset($queries['limit']) ? $queries['limit'] : '',
        ));
        if (!$resource) {
            return $resource;
        }
    }

    if ($options['file'] === true) {
        //関連するファイルを削除
        foreach ($deletes as $delete) {
            directory_rmdir($GLOBALS['file_targets']['article'] . $delete . '/');
        }
    }

    return $resource;
}

/**
 * 記事の正規化
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function normalize_articles($queries, $options = array())
{
    //日時
    if (isset($queries['datetime'])) {
        if (is_array($queries['datetime'])) {
            $queries['datetime'] = $queries['datetime']['year']
                                   . '-' .
                                   $queries['datetime']['month']
                                   . '-' .
                                   $queries['datetime']['day']
                                   . ' ' .
                                   $queries['datetime']['hour']
                                   . ':' .
                                   $queries['datetime']['minute'];
        }
        $queries['datetime'] = mb_convert_kana($queries['datetime'], 'a', MAIN_INTERNAL_ENCODING) . ':00';
    }

    return $queries;
}

/**
 * 記事の検証
 *
 * @param  array  $queries
 * @param  array  $options
 * @return array
 */
function validate_articles($queries, $options = array())
{
    $messages = array();

    //日時
    if (isset($queries['datetime'])) {
        if (!validator_required($queries['datetime'])) {
        } elseif (!validator_datetime($queries['datetime'])) {
            $messages['datetime'] = '日時の値が不正です。';
        }
    }

    //タイトル
    if (isset($queries['title'])) {
        if (!validator_required($queries['title'])) {
            $messages['title'] = 'タイトルが入力されていません。';
        } elseif (!validator_max_length($queries['title'], 20)) {
            $messages['title'] = 'タイトルは20文字以内で入力してください。';
        }
    }

    //本文
    if (isset($queries['body'])) {
        if (!validator_required($queries['body'])) {
        } elseif (!validator_max_length($queries['body'], 1000)) {
            $messages['body'] = '本文は1000文字以内で入力してください。';
        }
    }

    //公開
    if (isset($queries['public'])) {
        if (!validator_boolean($queries['public'])) {
            $messages['public'] = '公開の書式が不正です。';
        }
    }

    return $messages;
}

/**
 * ファイルの保存
 *
 * @param  string  $id
 * @param  array  $files
 * @return void
 */
function save_articles($id, $files)
{
    foreach (array_keys($files) as $file) {
        if (empty($files[$file]['delete']) && !empty($files[$file]['name'])) {
            if (preg_match('/\.(.*)$/', $files[$file]['name'], $matches)) {
                $directory = $GLOBALS['file_targets']['article'] . intval($id) . '/';
                $filename  = $file . '.' . $matches[1];

                directory_mkdir($directory);

                if (file_put_contents($directory . $filename, $files[$file]['data']) === false) {
                    error('ファイル ' . $filename . ' を保存できません。');
                } else {
                    $resource = db_update(array(
                        'update' => DATABASE_PREFIX . 'articles',
                        'set'    => array(
                            $file => $filename,
                        ),
                        'where'  => array(
                            'id = :id',
                            array(
                                'id' => $id,
                            ),
                        ),
                    ));
                    if (!$resource) {
                        error('データを編集できません。');
                    }

                    file_resize($directory . $filename, $directory . 'thumbnail_' . $filename, $GLOBALS['resize_width'], $GLOBALS['resize_height'], $GLOBALS['resize_quality']);
                }
            } else {
                error('ファイル ' . $files[$file]['name'] . ' の拡張子を取得できません。');
            }
        }
    }
}

/**
 * ファイルの削除
 *
 * @param  string  $id
 * @param  array  $files
 * @return void
 */
function remove_articles($id, $files)
{
    foreach (array_keys($files) as $file) {
        if (!empty($files[$file]['delete']) || !empty($files[$file]['name'])) {
            $articles = db_select(array(
                'select' => $file,
                'from'   => DATABASE_PREFIX . 'articles',
                'where'  => array(
                    'id = :id',
                    array(
                        'id' => $id,
                    ),
                ),
            ));
            if (empty($articles)) {
                error('編集データが見つかりません。');
            } else {
                $article = $articles[0];
            }

            if (is_file($GLOBALS['file_targets']['article'] . intval($id) . '/' . $article[$file])) {
                if (is_file($GLOBALS['file_targets']['article'] . intval($id) . '/thumbnail_' . $article[$file])) {
                    unlink($GLOBALS['file_targets']['article'] . intval($id) . '/thumbnail_' . $article[$file]);
                }
                unlink($GLOBALS['file_targets']['article'] . intval($id) . '/' . $article[$file]);

                $resource = db_update(array(
                    'update' => DATABASE_PREFIX . 'articles',
                    'set'    => array(
                        $file => null,
                    ),
                    'where'  => array(
                        'id = :id',
                        array(
                            'id' => $id,
                        ),
                    ),
                ));
                if (!$resource) {
                    error('データを編集できません。');
                }
            }
        }
    }
}

/**
 * 記事の表示用データ作成
 *
 * @param  array  $data
 * @return array
 */
function view_articles($data)
{
    //日時
    if (isset($data['datetime'])) {
        if (preg_match('/^(\d\d\d\d\-\d\d\-\d\d \d\d:\d\d):\d\d$/', $data['datetime'], $matches)) {
            $data['datetime'] = $matches[1] . ':00';
        }
    }

    return $data;
}

/**
 * 記事の初期値
 *
 * @return array
 */
function default_articles()
{
    return array(
        'id'       => null,
        'created'  => localdate('Y-m-d H:i:s'),
        'modified' => localdate('Y-m-d H:i:s'),
        'deleted'  => null,
        'datetime' => localdate('Y-m-d H:00:00'),
        'title'    => '',
        'body'     => null,
        'image_01' => null,
        'image_02' => null,
        'public'   => 1,
    );
}
