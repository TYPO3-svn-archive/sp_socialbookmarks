<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009 Kai Vogel  <kai.vogel(at)speedprogs.de>
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

	/**
	 * Services handler
	 */
	class tx_spsocialbookmarks_services {

		/**
		 * Get services for flexform
		 *
		 * @param array $paConfig The configuration array
		 * @return array Select options
		 */
		public function aGetDefaultServices(array $paConfig) {
			require_once(PATH_t3lib . 'class.t3lib_page.php');
			require_once(PATH_t3lib . 'class.t3lib_tstemplate.php');
			require_once(PATH_t3lib . 'class.t3lib_tsparser_ext.php');

				// Get TypoScript setup
			$sURL = $_GET['returnUrl'];
			$iPID = $this->getPageId();
			$iPID = (!empty($paConfig['row']['pid']) ? $paConfig['row']['pid'] : $iPID);
			$oPage = t3lib_div::makeInstance('t3lib_pageSelect');

			if ($aLine = $oPage->getRootLine($iPID)) {
				$oTS = t3lib_div::makeInstance('t3lib_tsparser_ext');
				$oTS->tt_track = 0;
				$oTS->init();
				$oTS->runThroughTemplates($aLine);
				$oTS->generateConfig();

					// Get services
				$aServices = $oTS->setup['plugin.']['tx_spsocialbookmarks_pi1.']['services.'];
				if (!empty($aServices) && is_array($aServices)) {
					foreach($aServices as $sKey => $aService) {
						$sKey = strtolower(substr($sKey, 0, -1));
						$sName = $aService['name'] ? $aService['name'] : $sKey;
						$paConfig['items'][] = array($sName, $sKey);
					}
				}
			}

			return $paConfig;
		}


		/**
		 * Get current page ID within backend
		 * 
		 * @return integer UID of current page
		 */
		public function getPageId() {
			if (isset($GLOBALS['SOBE'])) {
				return (int) $GLOBALS['SOBE']->viewId;
			}

				// Find UID in "id" param
			$pageId = t3lib_div::_GP('id');
			if ($pageId) {
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

	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']);
	}
?>
