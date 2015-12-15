<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cdsrc_baseurl".
 *
 * Auto generated 02-07-2015 11:01
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Automatic base url',
	'description' => 'Add automaticly base url based on current domain',
	'category' => 'fe',
	'version' => '3.2.0',
	'state' => 'stable',
	'uploadfolder' => true,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Matthias Toscanelli',
	'author_email' => 'm.toscanelli@code-source.ch',
	'author_company' => 'Code-Source',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '4.5.0-7.3.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
);

