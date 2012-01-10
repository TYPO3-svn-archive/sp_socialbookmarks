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

	require_once(PATH_tslib . 'class.tslib_content.php');
	require_once(PATH_tslib . 'class.tslib_fe.php');
	require_once(PATH_t3lib . 'class.t3lib_page.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'pi1/class.tx_spsocialbookmarks_pi1.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_db.php');

	/**
	 * Ajax handler
	 */
	class tx_spsocialbookmarks_pi1_ajax {

		/**
		 * The main method for the ajax call
		 *
		 * @return void
		 */
		public function main() {
				// Get submitted data
			if (!$sParams = t3lib_div::_GP('data')) {
				return;
			}
			$aData = unserialize(base64_decode($sParams));
			$iUID  = intval($aData['id']);
			$sLang = (ctype_alpha($aData['lang']) ? trim(strtolower($aData['lang'])) : 'en');
			$sName = (ctype_print($aData['service']) ? $aData['service'] : 'unknown');

				// Use eID tools
			tslib_eidtools::connectDB();
			tslib_eidtools::initFeUser();

				// Get plugin data from DB
			$GLOBALS['TYPO3_DB']->connectDB();
			$oResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tt_content', 'uid=' . $iUID);
			$aPlugin = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($oResult);
			$iPID = intval($aPlugin['pid']);

				// Get TSFE
			$sClassName = t3lib_div::makeInstanceClassName('tslib_fe');
			$GLOBALS['TSFE'] = new $sClassName($GLOBALS['TYPO3_CONF_VARS'], $iPID, 0, TRUE);
			$GLOBALS['TSFE']->connectToDB();
			$GLOBALS['TSFE']->initFEuser();
			$GLOBALS['TSFE']->determineId();
			$GLOBALS['TSFE']->getCompressedTCarray();
			$GLOBALS['TSFE']->initTemplate();
			$GLOBALS['TSFE']->getConfigArray();

				// Get main plugin (we need it for TS and flexform config)
			$aConfig = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_spsocialbookmarks_pi1.'];
			$oMain = t3lib_div::makeInstance('tx_spsocialbookmarks_pi1');
			$oMain->cObj = t3lib_div::makeInstance('tslib_cObj');
			$oMain->cObj->data = $aPlugin;
			$oMain->main('', $aConfig);

				// Add click to DB
			$oDB = t3lib_div::makeInstance('tx_spsocialbookmarks_db');
			if ($oMain->aConfig['useStats']) {
				$oDB->vAddClick($iPID, $sName);
			}

				// Remove objects from memory
			unset($oMain);
			unset($oDB);
		}

	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_ajax.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_ajax.php']);
	}


	/**
	 * Make an instance of the sp_bettercontact-ajax class
	 * We need to do that because its called without a relation to the frontent process
	 */
	$oSPSocialBookmarksAJAX = t3lib_div::makeInstance('tx_spsocialbookmarks_pi1_ajax');
	$oSPSocialBookmarksAJAX->main();

?>