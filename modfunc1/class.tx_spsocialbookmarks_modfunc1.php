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

	require_once(PATH_t3lib . 'class.t3lib_extobjbase.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_db.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_backend.php');

	/**
	 * Module extension
	 */
	class tx_spsocialbookmarks_modfunc1 extends t3lib_extobjbase {
		public $aConfig = array();
		public $oLL     = NULL;

		/**
		 * Returns the module menu
		 *
		 * @return array Menu items
		 */
		public function modMenu() {
			return array (
				'mode'   => array (
					'all'    => $GLOBALS['LANG']->getLL('mode_all'),
					'page'   => $GLOBALS['LANG']->getLL('mode_this'),
				),
				'period' => array (
					'all'    => $GLOBALS['LANG']->getLL('period_all'),
					'year'   => $GLOBALS['LANG']->getLL('period_year'),
					'month'  => $GLOBALS['LANG']->getLL('period_month'),
					'week'   => $GLOBALS['LANG']->getLL('period_week'),
					'day'    => $GLOBALS['LANG']->getLL('period_day'),
				),
				'showBrowsers' => '',
				'showSystems'  => '',
			);
		}


		/**
		 * Main method of the module
		 *
		 * @return string HTML content
		 */
		public function main() {
			$iPID = (int) $this->pObj->id;

				// Get TyopScript configuration
			$oBackend = t3lib_div::makeInstance('tx_spsocialbookmarks_backend');
			$this->aConfig = $oBackend->aGetTS($iPID);

				// Load environment
			$sMenus  = $this->sGetFuncMenus();
			$oDoc    = $this->pObj->doc;
			$aCharts = $this->aGetActiveCharts();

				// Get data from db
			$oDB     = t3lib_div::makeInstance('tx_spsocialbookmarks_db');
			$iUID    = ($this->pObj->MOD_SETTINGS['mode'] == 'page' ? $iPID : 0);
			$iPeriod = $this->iGetPeriod();
			$aData   = $oDB->aGetData($iUID, $iPeriod);

				// Begin document
			$sContent  = $oDoc->spacer(5);
			$sContent .= $oDoc->section($GLOBALS['LANG']->getLL('title'), $sMenus, 0, 1, 0, 0);
			$sContent .= $oDoc->sectionEnd();

				// Get charts
			$oChart = t3lib_div::makeInstance('tx_spsocialbookmarks_modfunc1_chart');
			foreach ($aCharts as $sType) {
				$aCounts = $this->aGetCounts($aData, $sType);
				$aImages = $this->aGetImages($sType);
				$sChart  = $oChart->sGetChart($this->aConfig, $sType, $GLOBALS['LANG']->getLL('clicks'), $aCounts, $aImages);

				$sContent .= $oDoc->spacer(10);
				$sContent .= $oDoc->section($GLOBALS['LANG']->getLL('title_chart_'.$sType), $sChart, 1, 1, 1, 0);
				$sContent .= $oDoc->sectionEnd();
			}

				// Return document
			return $sContent;
		}


		/**
		 * Get selector for view mode and period
		 *
		 * @return string Menu
		 */
		protected function sGetFuncMenus() {
			return t3lib_BEfunc::getFuncMenu($this->pObj->id,'SET[mode]', $this->pObj->MOD_SETTINGS['mode'], $this->pObj->MOD_MENU['mode'], 'index.php') .
					t3lib_BEfunc::getFuncMenu($this->pObj->id,'SET[period]', $this->pObj->MOD_SETTINGS['period'], $this->pObj->MOD_MENU['period'], 'index.php') .
					'&nbsp;&nbsp;' . t3lib_BEfunc::getFuncCheck($this->pObj->id, 'SET[showBrowsers]', $this->pObj->MOD_SETTINGS['showBrowsers'], 'index.php') .
					$GLOBALS['LANG']->getLL('showBrowsers') . '&nbsp;' .
					'&nbsp;&nbsp;' . t3lib_BEfunc::getFuncCheck($this->pObj->id, 'SET[showSystems]', $this->pObj->MOD_SETTINGS['showSystems'], 'index.php') .
					$GLOBALS['LANG']->getLL('showSystems');
		}


		/**
		 * Get current period
		 *
		 * @return integer Time period
		 */
		protected function iGetPeriod() {
			switch (strtolower($this->pObj->MOD_SETTINGS['period'])) {
				case 'day' :
					$iPeriod = ((int) $GLOBALS['EXEC_TIME'] - 24*60*60);
				break;
				case 'week' :
					$iPeriod = ((int) $GLOBALS['EXEC_TIME'] - 7*24*60*60);
				break;
				case 'month' :
					$iPeriod = ((int) $GLOBALS['EXEC_TIME'] - 30*24*60*60);
				break;
				case 'year' :
					$iPeriod = ((int) $GLOBALS['EXEC_TIME'] - 356*24*60*60);
				break;
				case 'all' :
				default :
					$iPeriod = 0;
				break;
			}

			return $iPeriod;
		}


		/**
		 * Get active charts
		 *
		 * @return array Active chart names
		 */
		protected function aGetActiveCharts() {
			$aAvailable = array('browsers', 'systems');
			$aResult = array('services');

			foreach ($aAvailable as $sChartName) {
				$sName = 'show' . ucfirst(strtolower($sChartName));
				if ($this->pObj->MOD_SETTINGS[$sName]) {
					$aResult[] = strtolower($sChartName);
				}
			}

			return $aResult;
		}


		/**
		 * Get the counts for each service
		 *
		 * @param array $paData Services
		 * @param string $psType Count type
		 * @return array  All counts
		 */
		protected function aGetCounts(array $paData, $psType = 'services') {
			$psType = strtolower(trim($psType));

			if (empty($paData) || empty($this->aConfig[$psType . '.']) || !is_array($this->aConfig[$psType . '.'])) {
				return array();
			}

			$aCounts = array();

			if ($psType == 'services') {
				foreach ($paData as $sKey => $aService) {
					if (array_key_exists(trim($sKey) . '.', $this->aConfig[$psType . '.'])) {
						$aCounts[$sKey] = count($aService);
					}
				}
			} else {
				foreach ($paData as $aElements) {
					foreach ($aElements as $aElement) {
						$sName = 'unknown';

						foreach($this->aConfig[$psType.'.'] as $sKey => $aConfig) {
							if (preg_match('/' . addcslashes($aConfig['ident'], '/') . '/i', $aElement['agent'])) {
								$sName = substr($sKey, 0 , -1);
							}
						}
						$aCounts[$sName] = (isset($aCounts[$sName]) ? $aCounts[$sName] + 1 : 1);
					}
				}
			}

				// Sort for display
			arsort($aCounts);

			return $aCounts;
		}


		/**
		 * Get images to all services
		 *
		 * @param $psType Image type
		 * @return array Service images
		 */
		public function aGetImages($psType = 'services') {
			$psType = strtolower(trim($psType));

			if (empty($this->aConfig[$psType . '.']) || !is_array($this->aConfig[$psType . '.'])) {
				return array();
			}

				// Get configuration
			$aAllowedTypes = explode(',', str_replace(' ', '', strtolower($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])));
			$aImages = array();

				// Get images
			foreach ($this->aConfig[$psType . '.'] as $sKey => $aValue) {
				$aFileNameParts = explode('.', $aValue['image']);
				$sFileType      = strtolower(end($aFileNameParts));
				$sFileName      = t3lib_div::getFileAbsFileName($aValue['image']);

				if (!in_array($sFileType, $aAllowedTypes) || !is_readable($sFileName)) {
					continue;
				}

				$aValue['image'] = '../' . $GLOBALS['BACK_PATH'] . $this->sGetRelativePath($aValue['image']);
				$aImages[substr($sKey, 0, -1)] = $aValue;
			}

			return $aImages;
		}


		/**
		 * Check for extension relative path
		 *
		 * @param string $psFileName The file name
		 * @return String with relative file path
		 */
		protected function sGetRelativePath($psFileName) {
			if ($psFileName && substr($psFileName, 0, 4) == 'EXT:') {
				list($sExtKey, $sFilePath) = explode('/', substr($psFileName, 4), 2);
				$sExtKey = strtolower($sExtKey);

				if ($sExtKey == $this->extKey || t3lib_extMgm::isLoaded($sExtKey)) {
					$psFileName = t3lib_extMgm::siteRelPath($sExtKey).$sFilePath;
				}
			}

			return $psFileName;
		}
	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1.php']);
	}

?>