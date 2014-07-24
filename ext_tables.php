<?php

defined('TYPO3_MODE') or die();

$tmpCols = array(
	'tx_amazingpagenotfound_page403' => array(
		'label' => 'Page for 403 handling',
		'config' => array(
			'type' => 'input',
			'size' => '50',
			'max' => '256',
			'eval' => 'trim',
			'wizards' => array(
				'_PADDING' => 2,
				'link' => array(
					'type' => 'popup',
					'icon' => 'link_popup.gif',
					'script' => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1'
				)
			),
			'softref' => 'typolink'
		)
	),
	'tx_amazingpagenotfound_page404' => array(
		'label' => 'Page for 404 handling',
		'config' => array(
			'type' => 'input',
			'size' => '50',
			'max' => '256',
			'eval' => 'trim',
			'wizards' => array(
				'_PADDING' => 2,
				'link' => array(
					'type' => 'popup',
					'icon' => 'link_popup.gif',
					'script' => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=500,width=800,status=0,menubar=0,scrollbars=1'
				)
			),
			'softref' => 'typolink'
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_domain', $tmpCols);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_domain','--div--;Error Handling, tx_amazingpagenotfound_page403, tx_amazingpagenotfound_page404');
