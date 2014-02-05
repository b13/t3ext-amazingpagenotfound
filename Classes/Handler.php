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
		$domainRecord = $parentObject->getDomainDataForPid($parentObject->domainStartPage);

		// @todo: check the reason (403 / 404)

		// @todo: resolve the language based on the Domain and realurl

		// send the right header
		header('HTTP/1.0 404 Not Found');

		// find the page / URL to fetch, based on the 
		$targetPage = $domainRecord['tx_amazingpagenotfound_page404'];
		if (is_numeric($targetPage)) {
			$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl('index.php?id=' . $targetPage);
		} else {
			$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::locationHeaderUrl($targetPage);
		}

		$errorPage = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($errorPage);
		$errorPage = str_replace(
			array('{currentUrl}', '{reason}'),
			array($params['currentUrl'], $params['reason'])
		);

		echo $errorPage;
		exit;
	}
}
