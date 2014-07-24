<?php

defined('TYPO3_MODE') or die();

$TYPO3_CONF_VARS['FE']['pageNotFound_handling_original'] = $TYPO3_CONF_VARS['FE']['pageNotFound_handling'];
$TYPO3_CONF_VARS['FE']['pageNotFound_handling'] = 'USER_FUNCTION:B13\\Amazingpagenotfound\\Handler->resolvePageError';
