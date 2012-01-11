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

	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_backend.php');

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
				// Get TypoScript configuration
			$backend = t3lib_div::makeInstance('tx_spsocialbookmarks_backend');
			$pid     = $backend->getPageId();
			$pid     = (!empty($configuration['row']['pid']) ? $configuration['row']['pid'] : $pid);
			$setup   = $backend->getTypoScriptSetup($pid);

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

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_services.php']);
	}
?>
