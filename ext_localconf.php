<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$TYPO3_CONF_VARS['FE']['pageNotFound_handling'] = 'USER_FUNCTION:B13\\Amazingpagenotfound\\Handler->resolvePageError';
