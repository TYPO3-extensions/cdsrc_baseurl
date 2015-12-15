<?php
if (!defined('TYPO3_MODE')) die('Access denied.');


$tempColumns = Array(
    'tx_cdsrcbaseurl_to_baseurl' => Array(
        'exclude' => 1,
        'label' => 'LLL:EXT:cdsrc_baseurl/locallang_db.xml:sys_domain.tx_cdsrcbaseurl_to_baseurl',
        'config' => Array(
            'type' => 'check',
            'default' => 1,
        )
    ),
);


if (class_exists('\\TYPO3\\CMS\\Core\Utility\\GeneralUtility') && \TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('6.0')) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $tempColumns);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain', 'tx_cdsrcbaseurl_to_baseurl;;;;1-1-1');
} else {
    t3lib_div::loadTCA('sys_domain');
    t3lib_extMgm::addTCAcolumns('sys_domain', $tempColumns, 1);
    t3lib_extMgm::addToAllTCAtypes('sys_domain', 'tx_cdsrcbaseurl_to_baseurl;;;;1-1-1');
}
?>