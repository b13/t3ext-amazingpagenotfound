<?php
namespace B13\Amazingpagenotfound;

/***************************************************************
 *  Copyright notice - MIT License (MIT)
 *
 *  (c) 2014 Benjamin Mack <benjamin.mack@b13.de>
 *  All rights reserved
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *  
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *  
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 ***************************************************************/


class Handler {

	/**
	 * function to fetch another page record and set the according
	 * header data
	 * parameters has "currentUrl", "reasonText" and "pageAccessFailureReasons"
	 * @param array $parameters
	 * @param TypoScriptFrontendController $parentObject
	 * @return void
	 */
	public function resolvePageError($parameters, $parentObject) {
		// resolve the details to the current domain
		if (!$parentObject->sys_page) {
			$parentObject->sys_page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');
		}

		$domainRecord = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow(
			'*',
			'sys_domain',
			'redirectTo=\'\' AND domainName=' . $GLOBALS['TYPO3_DB']->fullQuoteStr(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('HTTP_HOST'), 'sys_domain') . $GLOBALS['TSFE']->sys_page->enableFields('sys_domain'),
			'',
			'sorting ASC'
		);

		// check the reason (403 / 404)
		switch ($parameters['reasonText']) {
			case 'ID was not an accessible page':
			case 'Subsection was found and not accessible':

				$targetPage = $domainRecord['tx_amazingpagenotfound_page403'] ?: $parentObject->TYPO3_CONF_VARS['FE']['pageAccessDenied_handling'];
				$header = $parentObject->TYPO3_CONF_VARS['FE']['pageAccessDenied_handling_statheader'] ?: 'HTTP/1.1 403 Forbidden';
			break;
			default:

				$targetPage = $domainRecord['tx_amazingpagenotfound_page404'];
				$header = $parentObject->TYPO3_CONF_VARS['FE']['pageNotFound_handling_statheader'] ?: 'HTTP/1.0 404 Not Found';
			break;
		}

		// nothing configured, use the original handler
		if (empty($targetPage)) {
			$parentObject->pageErrorHandler(
				$parentObject->TYPO3_CONF_VARS['FE']['pageNotFound_handling_original'],
				$header,
				$parameters['reasonText']
			);
		}

		// send the right header
		header($header);

		// @todo: resolve the language based on the Domain and realurl

		// find the page / URL to fetch, based on the
		if (is_numeric($targetPage)) {
			$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl('index.php?id=' . $targetPage);
		} else {
			$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl($targetPage);
		}

		$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($errorPage);
		$errorPage = str_replace(
			array('{currentUrl}', '{reason}'),
			array(\TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl($parameters['currentUrl']), $parameters['reason']),
		$errorPage);

		echo $errorPage;
		exit;
	}
}
