<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2012 Kai Vogel <kai.vogel@speedprogs.de>, Speedprogs.de
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
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ********************************************************************/

	/**
	 * Repository for Tx_SpSocialbookmarks_Domain_Model_Service
	 */
	class Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository implements t3lib_Singleton {

		/**
		 * @var array
		 */
		protected $services = array();


		/**
		 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
		 * @return void
		 */
		public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
			$settings = $configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_SETTINGS
			);
			if (!empty($settings['services']) && is_array($settings['services'])) {
				$this->services = Tx_SpSocialbookmarks_Utility_TypoScript::parse($settings['services']);
			}
		}


		/**
		 * Get service by id
		 *
		 * @param string $serviceId The id of the service
		 * @return array Service
		 */
		public function getById($serviceId) {
			if (!empty($this->services[$serviceId]) && is_array($this->services[$serviceId])) {
				return $this->services[$serviceId];
			}
			return array();
		}


		/**
		 * Returns all services
		 *
		 * @return array All services
		 */
		public function getAll() {
			if (!empty($this->services) && is_array($this->services)) {
				return $this->services;
			}
			return array();
		}

	}
?>