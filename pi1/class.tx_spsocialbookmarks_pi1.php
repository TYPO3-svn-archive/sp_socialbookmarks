<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009-2012 Kai Vogel <kai.vogel@speedprogs.de>, Speedprogs.de
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published
	 *  by the Free Software Foundation; either version 3 of the License,
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

	require_once(PATH_tslib . 'class.tslib_pibase.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'pi1/class.tx_spsocialbookmarks_pi1_template.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_typoscript.php');

	/**
	 * Plugin for the sp_socialbookmarks extension
	 */
	class tx_spsocialbookmarks_pi1 extends tslib_pibase {
		public $prefixId      = 'tx_spsocialbookmarks_pi1';
		public $scriptRelPath = 'pi1/class.tx_spsocialbookmarks_pi1.php';
		public $extKey        = 'sp_socialbookmarks';
		public $labels        = array();
		public $setup         = array();
		public $cObj          = NULL;
		public $LLkey         = 'en';


		/**
		 * The main method
		 *
		 * @param string $content Default content
		 * @param array $setup TypoScript configuration
		 * @return string The content that is displayed on the website
		 */
		public function main($content, array $setup) {
			$this->pi_setPiVarDefaults();
			$this->setup  = $this->getSetup($setup);
			$this->labels = $this->getLabels();

				// Get template instance
			$template = t3lib_div::makeInstance('tx_spsocialbookmarks_pi1_template');
			if (empty($template)) {
				return $this->getError('template');
			}

				// Get bookmark services
			$services = $this->getServices();
			if (empty($services)) {
				return $this->getError('bookmarks');
			}

				// Add markers and generate content
			$template->initialize($this);
			$template->addDefaultMarkers();
			$template->addServices($services);
			$content = $template->getContent();
			if (empty($content)) {
				return $this->getError('content');
			}

			return $this->pi_wrapInBaseClass($content);
		}


		/**
		 * Override typoscipt settings with flexform values
		 *
		 * @param array $setup TypoScript configuration
		 * @return array TypoScript array merged with FlexForm values
		 */
		public function getSetup(array $setup) {
				// Parse setup first
			$parser = t3lib_div::makeInstance('tx_spsocialbookmarks_typoscript', $this->cObj);
			$setup = $parser->parse($setup);

				// Overrride with flexform values
			$this->pi_initPIflexForm();
			if (!empty($this->cObj->data['pi_flexform'])) {
				$flexform = $this->cObj->data['pi_flexform'];
				$sDef = current($flexform['data']);
				$lDef = array_keys($sDef);

				foreach ($flexform['data'] as $sheet => $content) {
					foreach ($content[$lDef[0]] as $key => $value) {
						$value = $this->pi_getFFvalue($flexform, $key, $sheet, $lDef[0]);
						if (!empty($value)) {
							$setup[$key] = $value;
						}
					}
				}
			}

			return $setup;
		}


		/**
		 * Get whole language array
		 *
		 * @return array All localized labels
		 */
		protected function getLabels() {
			$this->pi_loadLL();

				// Get default labels
			$locallang = $this->LOCAL_LANG[$this->LLkey];
			if (empty($this->LOCAL_LANG[$this->LLkey])) {
				$locallang = $this->LOCAL_LANG['default'];
			}

				// Get iser labels
			$userLabels = array(
				$this->setup['_LOCAL_LANG.'][$this->LLkey . '.'],
				$this->getUserLabels(),
			);

				// Add and override labels
			foreach ($userLabels as $labels) {
				if (!empty($labels)) {
					foreach ($labels as $key => $label) {
						$locallang[$key] = $label;
					}
				}
			}

			return $locallang;
		}


		/**
		 * Get user language array
		 *
		 * @return array User localized labels
		 */
		protected function getUserLabels() {
			$file = t3lib_div::getFileAbsFileName($this->setup['locallangFile']);
			$userLabels = array();

			if (!empty($file)) {
				$userLabels = t3lib_div::readLLXMLfile($file, $this->LLkey);
				if (is_array($userLabels[$this->LLkey])) {
					$userLabels = $userLabels[$this->LLkey];
				}
			}

			return $userLabels;
		}


		/**
		 * Get selected bookmark services from config
		 *
		 * @return array Services
		 */
		protected function getServices() {
			if (empty($this->setup['services']) || empty($this->setup['serviceList'])) {
				return array();
			}

			$serviceList = t3lib_div::trimExplode(',', $this->setup['serviceList']);
			$services = array();

			foreach ($serviceList as $name) {
				$services[$name] = $this->setup['services'][$name];
			}

			return $services;
		}


		/**
		 * Wrap error message for frontend rendering
		 *
		 * @param string $message Label name for error message
		 * @param string $param Param for the message
		 * @return string The error message
		 */
		protected function getError($message, $param = '') {
			$message = ($message ? $this->labels['error_' . strtolower(trim($message))] : 'Error message not found!');
			$message = 'Social Bookmarks (' . $this->extKey . '): ' . $message;

			return $this->pi_wrapInBaseClass(sprintf($message, trim($param)));
		}
	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1.php']);
	}

?>