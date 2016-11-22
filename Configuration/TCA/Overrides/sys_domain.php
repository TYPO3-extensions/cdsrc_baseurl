<?php
/**
 * @copyright Copyright (c) 2016 Code-Source
 */


$tempColumns = [
    'tx_cdsrcbaseurl_to_baseurl' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:cdsrc_baseurl/Resources/Private/Language/locallang_db.xlf:sys_domain.tx_cdsrcbaseurl_to_baseurl',
        'config' => [
            'type' => 'check',
            'default' => 1,
        ],
    ],
];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $tempColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain', 'tx_cdsrcbaseurl_to_baseurl');
