<?php
	/***************************************************************
	*  Copyright notice
	*
	*  (c) 2009 Kai Vogel  <kai.vogel(at)speedprogs.de>
	*  All rights reserved
	*
	*  This script is part of the TYPO3 project. The TYPO3 project is
	*  free software; you can redistribute it and/or modify
	*  it under the terms of the GNU General Public License as published by
	*  the Free Software Foundation; either version 2 of the License, or
	*  (at your option) any later version.
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
	***************************************************************/


	/**
	 * User item func for the 'sp_socialbookmarks' extension.
	 *
	 * @author	Kai Vogel <kai.vogel(at)speedprogs.de>
	 * @package	TYPO3
	 * @subpackage	tx_spsocialbookmarks
	 */
	class tx_spsocialbookmarks_services {


		/**
		 * Get services for flexform ( Backend )
		 *
		 * @return Array with select options
		 */
		public function aGetDefaultServices($paConfig) {
			require_once(PATH_t3lib.'class.t3lib_page.php');
			require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
			require_once(PATH_t3lib.'class.t3lib_tsparser_ext.php');

			// Get typoscript setup
			$sURL		= $_GET['returnUrl'];
			$iGetID	= intval(substr($sURL, strpos($sURL, 'id=')+3));
			$iPID		= ($paConfig['row']['pid'] > 0) ? $paConfig['row']['pid'] : $iGetID;
			$oPage	= t3lib_div::makeInstance('t3lib_pageSelect');

			if ($aLine = @$oPage->getRootLine($iPID)) {
				$oTS = t3lib_div::makeInstance('t3lib_tsparser_ext');
				$oTS->tt_track = 0;
				$oTS->init();
				$oTS->runThroughTemplates($aLine);
				$oTS->generateConfig();

				// Get services
				$aServices = $oTS->setup['plugin.']['tx_spsocialbookmarks_pi1.']['services.'];
				if (is_array($aServices) && count($aServices)) {
					foreach($aServices as $sKey => $aService) {
						$sKey = strtolower(substr($sKey, 0, -1));
						$sName = $aService['name'] ? $aService['name'] : $sKey;
						$paConfig['items'][] = array($sName, $sKey);
					}
				}
			}

			return $paConfig;
		}
	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']);
	}
?>
