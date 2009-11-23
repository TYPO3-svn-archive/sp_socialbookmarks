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


	require_once(PATH_tslib.'class.tslib_pibase.php');


	/**
	 * Plugin 'Social Bookmarks' for the 'sp_socialbookmarks' extension.
	 *
	 * @author	Kai Vogel <kai.vogel(at)speedprogs.de>
	 * @package	TYPO3
	 * @subpackage	tx_spsocialbookmarks
	 */
	class tx_spsocialbookmarks_pi1 extends tslib_pibase {
		public $prefixId		= 'tx_spsocialbookmarks_pi1';
		public $scriptRelPath	= 'pi1/class.tx_spsocialbookmarks_pi1.php';
		public $extKey			= 'sp_socialbookmarks';
		public $sPrototype		= 'typo3/contrib/prototype/prototype.js';
		public $aLL				= array();
		public $aConfig			= array();
		public $oTemplate		= NULL;
		public $cObj			= NULL;
		public $LLkey			= 'en';


		/**
		 * The main method of the PlugIn
		 *
		 * @param	string		$content: The PlugIn content
		 * @param	array		$conf: The PlugIn configuration
		 * @return	The content that is displayed on the website
		 */
		public function main ($psContent, $paConf) {
			$this->aConfig = $paConf;
			$this->pi_setPiVarDefaults();
			$this->pi_initPIflexForm();

			// Override typoscript config with flexform values
			$this->vFlexOverride();

			// Set default templates if set
			if ($this->aConfig['useDefaultTemplate']) {
				$this->aConfig['templateFile'] 		= 'EXT:'.$this->extKey.'/res/template/template.html';
				$this->aConfig['stylesheetFile']	= 'EXT:'.$this->extKey.'/res/template/stylesheet.css';
				$this->aConfig['javascriptFile']	= 'EXT:'.$this->extKey.'/res/js/socialbookmarks.js';
			}

			// Get local language labels
			$this->aLL = $this->aGetLL();

			// Get template instance
			if (!$this->oTemplate = $this->oMakeInstance('template')) {
				return $this->sError('template');
			}

			// Add stylesheet
			if (isset($this->aConfig['stylesheetFile']) && strlen($this->aConfig['stylesheetFile'])) {
				if (!$this->oTemplate->bAddFileToHeader($this->aConfig['stylesheetFile'], 'css')) {
					return $this->sError('file', $this->aConfig['stylesheetFile']);
				}
			}

			// Add prototype
			if ($this->aConfig['loadPrototype'] && !$this->oTemplate->bAddFileToHeader($this->sPrototype, 'js')) {
				return $this->sError('file', $this->sPrototype);
			}

			// Add javascript
			if (isset($this->aConfig['javascriptFile']) && strlen($this->aConfig['javascriptFile'])) {
				if (!$this->oTemplate->bAddFileToHeader($this->aConfig['javascriptFile'], 'js')) {
					return $this->sError('file', $this->aConfig['javascriptFile']);
				}
			}

			// Add default markers to marker array
			$this->oTemplate->vAddDefaultMarkers();

			// Add bookmark services to marker array
			if ($aServices = $this->aGetServices()) {
				$this->oTemplate->vAddServices($aServices);
			} else {
				return $this->sError('bookmarks');
			}

			// Return whole content
			if ($sContent = $this->oTemplate->sGetContent()) {
				return $this->pi_wrapInBaseClass($sContent);
			} else {
				return $this->sError('content');
			}
		}


		/**
		 * Override typoscipt settings with flexform values
		 *
		 */
		public function vFlexOverride () {
			if (isset($this->cObj->data['pi_flexform']) && strlen($this->cObj->data['pi_flexform']) > 0) {
				$aFlexValues = $this->cObj->data['pi_flexform'];

				$sDef = current($aFlexValues['data']);
				$lDef = array_keys($sDef);

				foreach ($aFlexValues['data'] as $sSheet => $aData) {
					foreach ($aData[$lDef[0]] as $sKey => $aVal) {
						$sValue = $this->pi_getFFvalue($aFlexValues, $sKey, $sSheet, $lDef[0]);

						if (strlen($sValue) > 0) {
							$this->aConfig[$sKey] = $sValue;
						}
					}
				}
			}
		}


		/**
		 * Get user language array
		 *
		 * @return Array of user localized labels
		 */
		protected function aGetUserLabels () {
			$sFile		= t3lib_div::getFileAbsFileName($this->aConfig['locallangFile']);
			$aOwnLabels	= array();

			if (!empty($sFile)) {
				$aOwnLabels = t3lib_div::readLLXMLfile($sFile, $this->LLkey);

				if (is_array($aOwnLabels[$this->LLkey])) {
					$aOwnLabels = $aOwnLabels[$this->LLkey];
				}
			}

			return $aOwnLabels;
		}


		/**
		 * Get whole language array
		 *
		 * @return Array of all localized labels
		 */
		protected function aGetLL() {
			$this->pi_loadLL();

			$aLocalLang 	= $this->LOCAL_LANG[$this->LLkey];
			$aOtherLabels	= array(
				$this->aConfig['_LOCAL_LANG.'][$this->LLkey.'.'],
				$this->aGetUserLabels(),
			);

			if (!count($this->LOCAL_LANG[$this->LLkey])) {
				$aLocalLang = $this->LOCAL_LANG['default'];
			}

			// Add and override labels
			foreach ($aOtherLabels as $aLabels) {
				if (count($aLabels) > 0) {
					foreach ($aLabels as $sKey => $sLabel) {
						$aLocalLang[$sKey] = $sLabel;
					}
				}
			}

			return $aLocalLang;
		}


		/**
		 * Make an instance of any class
		 *
		 * @return Instance of the new object
		 */
		public function oMakeInstance ($psClassPostfix) {
			if (strlen($psClassPostfix) == 0) {
				return NULL;
			}

			$sClassName	= strtolower($this->prefixId.'_'.$psClassPostfix);
			$sFileName	= t3lib_extMgm::extPath($this->extKey).'pi1/class.'.$sClassName.'.php';

			if (file_exists($sFileName)) {
				include_once($sFileName);

				$oResult = t3lib_div::makeInstance($sClassName);
				$oResult->vConstruct($this);

				return $oResult;
			} else {
				die($this->sError('file', $sFileName));
			}

			return NULL;
		}


		/**
		 * Get selected bookmark services from config
		 *
		 * @return Array of services
		 */
		protected function aGetServices() {
			if (!is_array($this->aConfig['services.']) || !count($this->aConfig['services.']) || !strlen($this->aConfig['serviceList'])) {
				return array();
			}

			$aServiceList	= explode(',', str_replace(' ', '', $this->aConfig['serviceList']));
			$aServices		= array();

			foreach ($aServiceList as $sName) {
				$aServices[$sName] = $this->aConfig['services.'][$sName.'.'];
			}

			return $aServices;
		}


		/**
		 * Wrap error message for frontend rendering
		 *
		 * @param		string	$psError: Label name for error message
		 * @param		string	$psInsert: Insert value to message
		 */
		protected function sError($psError, $psInsert='') {
			$sMessage = $psError ? $this->aLL['error_'.strtolower(trim($psError))] : 'Error message not found!';
			$sMessage = 'Social Bookmarks ('.$this->extKey.'): '.$sMessage;

			return $this->pi_wrapInBaseClass(sprintf($sMessage, trim($psInsert)));
		}
	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1.php']);
	}

?>