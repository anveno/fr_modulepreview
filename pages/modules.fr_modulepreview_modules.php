<?php

if (rex_request('func', 'string') !== "edit") {
    $list = rex_list::factory("SELECT id,name,fr_modulepreview_description,fr_modulepreview_thumbnail  FROM " . rex::getTable("module") . " ORDER BY name ASC", 100);
    #dump($list);
    $list->addTableColumnGroup([40, '33%', '*', 180, 40]);

    // Optionen der Liste
    $list->addTableAttribute('class', 'table-hover');

    // Columns
    $list->setColumnLabel('id', rex_i18n::msg('id'));
    $list->setColumnLabel('name', rex_i18n::msg('module_description'));

    // Description
    $list->setColumnLabel('fr_modulepreview_description', rex_i18n::msg('description'));

    // Preview-Column setzen
    $list->removeColumn('fr_modulepreview_thumbnail');

    $list->addColumn($this->i18n('fr_modulepreview_thumbnail'), '', 3, ['<th>###VALUE###</th>', '<td class="rex-table-thumbnail">###VALUE###</td>']);
    $list->setColumnLabel($this->i18n('fr_modulepreview_thumbnail'), $this->i18n('fr_modulepreview_thumbnail'));
    $list->setColumnFormat($this->i18n('fr_modulepreview_thumbnail'), 'custom', function ($params) {
        //dump($params['list']);
        if($params['list']->getValue('fr_modulepreview_thumbnail')) {
            $fr_modulepreview_thumbnail = rex_url::media('rex_media_small/' . $params['list']->getValue('fr_modulepreview_thumbnail'));
        }
        else {
            $fr_modulepreview_thumbnail = $this->getAssetsUrl('module_image_missing.svg');
        }
        return '<img src="'.$fr_modulepreview_thumbnail.'" width="150" alt="Thumbnail ###name###">';
    });

    // Funktionen der Liste
    $list->setColumnLabel('edit', '');
    $list->addColumn('edit', rex_i18n::msg('edit'));
    $list->setColumnParams('name', ['func' => 'edit', 'id' => '###id###', 'start' => rex_request('start', 'int', 0)]);
    $list->setColumnParams('edit', ['func' => 'edit', 'id' => '###id###', 'start' => rex_request('start', 'int', 0)]);
    $list = $list->get();

    // Ins Fragment packen
    $fragment = new rex_fragment();
    $fragment->setVar('title', "Liste der angelegten Module", false);
    $fragment->setVar('content', $list, false);
    echo $fragment->parse('core/page/section.php');
}

// If edit
if (rex_request('func', 'string') === "edit" && rex_request('id', 'int') !== "") {
    //rex_extension::register('REX_FORM_SAVED', ['nvModulePreview', 'handleThumbnailUploads']);

    $id = rex_request('id', 'int');

    $form = rex_form::factory(rex::getTable('module'), '', 'id=' . $id);

    $sModuleName = "";
    $oDb = rex_sql::factory();
    $oDb->setQuery("SELECT * FROM " . rex::getTable("module") . " WHERE id = :id LIMIT 1", ["id" => $id]);
    if ($oDb->getRows()) {
        $sModuleName = $oDb->getValue("name");
    }

    $formLabel = $sModuleName . ' | ID: ' . rex_get('id') . ' | ' . rex_i18n::msg('edit');

    $field = $form->addTextField('fr_modulepreview_description');
    $field->setLabel(rex_i18n::msg('description'));

    $field = $form->addMediaField('fr_modulepreview_thumbnail');
    $field->setLabel($this->i18n('fr_modulepreview_thumbnail'));
    $field->setCategoryId('29');
    $field->setTypes('jpg,jpeg,gif,png');
    //$field->setNotice("Optimal: 16:9 Format");

    // if ($form->getSql()->getValue("fr_modulepreview_thumbnail")) {
    $media_value = $field->getValue();
    if (!$media_value == '') {
        $field->setSuffix('<img src="'.rex_url::media('rex_media_small/'.$media_value).'" alt="">');
    }

    $form->addParam('id', $id);

    $sForm = $form->get();

    $sForm = str_replace("<form ", "<form enctype=\"multipart/form-data\" ", $sForm);

    $content = $sForm;

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $formLabel, false);
    $fragment->setVar('body', $content, false);
    echo $fragment->parse('core/page/section.php');

    // Bisschen hacky den Löschen-Button ausblenden
    echo '<style>.btn.btn-apply, #rex-addon-editmode .btn-delete{display: none !important;}</style>';
}
