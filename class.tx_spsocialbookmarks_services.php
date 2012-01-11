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

	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_typoscript.php');

	/**
	 * Services handler
	 */
	class tx_spsocialbookmarks_services implements t3lib_Singleton {

		/**
		 * Get services for flexform
		 *
		 * @param array $configuration The configuration array
		 * @return array Select options
		 */
		public function getServices(array $configuration) {
				// Get current page id
			if (!empty($configuration['row']['pid'])) {
				$pid = (int) $configuration['row']['pid'];
			} else {
				$pid = $this->getPageId();
			}

				// Get TypoScript configuration
			$parser = t3lib_div::makeInstance('tx_spsocialbookmarks_typoscript');
			$parser->initializeContentObject($pid);
			$setup  = $parser->parse($parser->getSetup($pid));
			if (empty($setup)) {
				return $configuration;
			}

				// Get services
			if (!empty($setup['services.']) && is_array($setup['services.'])) {
				foreach($setup['services.'] as $key => $service) {
					$key  = strtolower(rtrim($key, '. '));
					$name = ($service['name'] ? $service['name'] : $key);
					$configuration['items'][] = array($name, $key);
				}
			}

			return $configuration;
		}


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

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']);
	}
?>
