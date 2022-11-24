<?php
if (rex::isBackend() && is_object(rex::getUser())) {
    rex_perm::register('fr_modulepreview[]');
}

if ( rex::isBackend() && rex::getUser() ) {
    // CSS/JS
    rex_view::addCssFile($this->getAssetsUrl('fr_modulepreview.css'));
    rex_view::addJsFile($this->getAssetsUrl('fr_modulepreview.js'));
}