<?php

// Create tables
/*rex_sql_table::get(rex::getTable('fr_modulepreview'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('module_key', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('article_id', 'int(11)', true))
    ->ensure();*/

// Tabelle Module um zwei Felder erweitern
rex_sql_table::get(rex::getTable('module'))
    ->ensureColumn(new rex_sql_column('fr_modulepreview_thumbnail', 'text', true))
    ->ensureColumn(new rex_sql_column('fr_modulepreview_description', 'text'))
    ->alter();