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

	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_db.php');

	/**
	 * Ajax handler
	 */
	class tx_spsocialbookmarks_ajax implements t3lib_Singleton {

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
			$iPID  = intval($aData['pid']);
			$sName = (ctype_print($aData['service']) ? $aData['service'] : 'unknown');

				// Add click to DB
			tslib_eidtools::connectDB();
			$oDB = t3lib_div::makeInstance('tx_spsocialbookmarks_db');
			$oDB->vAddClick($iPID, $sName);
			unset($oDB);
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_ajax.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_ajax.php']);
	}


	/**
	 * Make an instance of the ajax class
	 * We need to do that because its called without a relation to the frontent process
	 */
	$oSPSocialBookmarksAJAX = t3lib_div::makeInstance('tx_spsocialbookmarks_ajax');
	$oSPSocialBookmarksAJAX->main();

?>