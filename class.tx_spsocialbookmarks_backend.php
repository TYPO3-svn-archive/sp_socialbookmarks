<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009 - 2012 Kai Vogel  <kai.vogel(at)speedprogs.de>
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published
	 *  by the Free Software Foundation; either version 2 of the License,
	 *  or (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ********************************************************************/

	require_once(PATH_t3lib . 'class.t3lib_page.php');
	require_once(PATH_t3lib . 'class.t3lib_tstemplate.php');
	require_once(PATH_t3lib . 'class.t3lib_tsparser_ext.php');

	/**
	 * Backend handler
	 */
	class tx_spsocialbookmarks_backend implements t3lib_Singleton {

		/**
		 * Get current page ID within backend
		 *
		 * @return integer UID of current page
		 */
		public function getPageId() {
			if (!empty($GLOBALS['SOBE']) && !empty($GLOBALS['SOBE']->viewId)) {
				return (int) $GLOBALS['SOBE']->viewId;
			}

				// Find UID in "id" param
			$pageId = t3lib_div::_GP('id');
			if (!empty($pageId)) {
				return (int) $pageId;
			}

				// Find UID in "returnUrl" param
			$url = urldecode(t3lib_div::_GP('returnUrl'));
			if ($url) {
				preg_match('/id=([0-9]*)/', $url, $parts);
				if (!empty($parts[1])) {
					return (int) $parts[1];
				}
			}

			return 0;
		}


		/**
		 * Get TypoScript array
		 *
		 * @param integer $pid The ID of current page
		 * @return array TypoScript configuration
		 */
		public function getTypoScriptSetup($pid) {
			$page = t3lib_div::makeInstance('t3lib_pageSelect');
			$line = $page->getRootLine((int) $pid);
			$parser = t3lib_div::makeInstance('t3lib_tsparser_ext');
			$parser->tt_track = 0;
			$parser->init();
			$parser->runThroughTemplates($line);
			$parser->generateConfig();

			if (!empty($parser->setup['plugin.']['tx_spsocialbookmarks_pi1.'])) {
				return $parser->setup['plugin.']['tx_spsocialbookmarks_pi1.'];
			}

			return array();
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_backend.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_backend.php']);
	}
?>
