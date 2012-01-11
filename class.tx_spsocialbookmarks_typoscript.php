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

	require_once(PATH_t3lib . 'class.t3lib_page.php');
	require_once(PATH_t3lib . 'class.t3lib_tstemplate.php');
	require_once(PATH_t3lib . 'class.t3lib_tsparser_ext.php');
	require_once(PATH_tslib . 'class.tslib_content.php');

	/**
	 * TypoScript handler
	 */
	class tx_spsocialbookmarks_typoscript implements t3lib_Singleton {
		protected $contentObject = NULL;


		/**
		 * Initialize
		 *
		 * @param tslib_cObj $contentObject Content object
		 * @return void
		 */
		public function __construct(tslib_cObj $contentObject = NULL) {
			$this->contentObject = $contentObject;
		}


		/**
		 * Load content object for given pid
		 *
		 * @param integer $pid The page id
		 * @return void
		 */
		public function initializeContentObject($pid) {
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'pages', 'uid=' . (int) $pid, '', '', '1');
			if (!empty($result)) {
				$this->contentObject = t3lib_div::makeInstance('tslib_cObj');
				$this->contentObject->start(reset($result), 'pages');
			}
		}


		/**
		 * Get TypoScript array
		 *
		 * @param integer $pid The ID of current page
		 * @return array TypoScript configuration
		 */
		public function getSetup($pid = 0) {
			$pageSelect = t3lib_div::makeInstance('t3lib_pageSelect');
			$line = $pageSelect->getRootLine((int) $pid);
			$tsParser = t3lib_div::makeInstance('t3lib_tsparser_ext');
			$tsParser->tt_track = 0;
			$tsParser->init();
			$tsParser->runThroughTemplates($line);
			$tsParser->generateConfig();

				// Get plugin setup
			if (empty($tsParser->setup['plugin.']['tx_spsocialbookmarks_pi1.'])) {
				return $tsParser->setup['plugin.']['tx_spsocialbookmarks_pi1.'];
			}

			return array();
		}


		/**
		 * Parse TypoScript array
		 *
		 * @param array $configuration TypoScript configuration array
		 * @return array Parsed configuration
		 */
		public function parse(array $configuration) {
			$typoScriptArray = array();

			if (is_array($configuration)) {
				foreach ($configuration as $key => $value) {
					$ident = rtrim($key, '.');
					if (is_array($value)) {
						if (!empty($configuration[$ident])) {
							$typoScriptArray[$ident] = $this->contentObject->cObjGetSingle($configuration[$ident], $value);
						} else {
							$typoScriptArray[$ident] = $this->parse($value);
						}
						unset($configuration[$key]);
					} else if (is_string($value) && $key === $ident) {
						$typoScriptArray[$key] = $value;
					}
				}
			}

			return $typoScriptArray;
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_typoscript.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_typoscript.php']);
	}
?>
