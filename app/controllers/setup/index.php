<?php

if (DATABASE_TYPE === 'pdo_mysql' || DATABASE_TYPE === 'mysql') {
    //MySQL用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'articles(
            id       INT UNSIGNED        NOT NULL AUTO_INCREMENT COMMENT \'代理キー\',
            created  DATETIME            NOT NULL                COMMENT \'作成日時\',
            modified DATETIME            NOT NULL                COMMENT \'更新日時\',
            deleted  DATETIME                                    COMMENT \'削除日時\',
            datetime DATETIME            NOT NULL                COMMENT \'日時\',
            title    VARCHAR(255)        NOT NULL                COMMENT \'タイトル\',
            body     TEXT                                        COMMENT \'本文\',
            image_01 VARCHAR(80)                                 COMMENT \'画像1\',
            image_02 VARCHAR(80)                                 COMMENT \'画像2\',
            public   TINYINT(1) UNSIGNED NOT NULL                COMMENT \'公開\',
            PRIMARY KEY(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT \'記事\';
    ');
} elseif (DATABASE_TYPE === 'pdo_pgsql' || DATABASE_TYPE === 'pgsql') {
    //PostgreSQL用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'articles(
            id       SERIAL       NOT NULL,
            created  TIMESTAMP    NOT NULL,
            modified TIMESTAMP    NOT NULL,
            deleted  TIMESTAMP,
            datetime TIMESTAMP    NOT NULL,
            title    VARCHAR(255) NOT NULL,
            body     TEXT,
            image_01 VARCHAR(80),
            image_02 VARCHAR(80),
            public   BOOLEAN      NOT NULL,
            PRIMARY KEY(id)
        );
    ');
} else {
    //SQLite用のテーブルを作成
    db_query('
        CREATE TABLE IF NOT EXISTS ' . DATABASE_PREFIX . 'articles(
            id       INTEGER,
            created  DATETIME         NOT NULL,
            modified DATETIME         NOT NULL,
            deleted  DATETIME,
            datetime DATETIME         NOT NULL,
            title    VARCHAR          NOT NULL,
            body     TEXT,
            image_01 VARCHAR,
            image_02 VARCHAR,
            public   INTEGER UNSIGNED NOT NULL,
            PRIMARY KEY(id)
        );
    ');
}

ok();
