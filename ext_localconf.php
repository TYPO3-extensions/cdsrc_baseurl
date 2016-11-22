<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


// Add hook to configuration post processus
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['configArrayPostProc'][$_EXTKEY] = 'CDSRC\CdsrcBaseurl\Hook\BaseUrlHook->execute';
