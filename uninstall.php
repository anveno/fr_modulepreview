<?php
//rex_sql_table::get(rex::getTable('fr_modulepreview'))->drop();

// Tabelle Module um zwei Felder erleichtern
rex_sql_table::get(rex::getTable('module'))
    ->removeColumn('fr_modulepreview_thumbnail')
    ->removeColumn('fr_modulepreview_description')
    ->alter();