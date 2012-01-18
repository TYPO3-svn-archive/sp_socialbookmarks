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
		 * @var string
		 */
		protected $modelClass = 'Tx_SpSocialbookmarks_Domain_Model_Service';

		/**
		 * @var array
		 */
		protected $services = array();

		/**
		 * @var array
		 */
		protected $objects = array();

		/**
		 * @var Tx_SpSocialbookmarks_Object_ObjectBuilder
		 */
		protected $objectBuilder = NULL;


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
		 * @param Tx_SpSocialbookmarks_Object_ObjectBuilder $objectBuilder
		 * @return void
		 */
		public function injectObjectBuilder(Tx_SpSocialbookmarks_Object_ObjectBuilder $objectBuilder) {
			$this->objectBuilder = $objectBuilder;
		}


		/**
		 * Get service by id
		 *
		 * @param string $id The id of the service
		 * @return Tx_SpSocialbookmarks_Domain_Model_Service
		 */
		public function getById($id) {
			if (!empty($this->objects[$id])) {
				return $this->objects[$id];
			}
			if (!empty($this->services[$id]) && is_array($this->services[$id])) {
				$this->objects[$id] = $this->objectBuilder->create($this->modelClass, $this->services[$id]);
				return $this->objects[$id];
			}
			return NULL;
		}


		/**
		 * Returns all services
		 *
		 * @return array All services
		 */
		public function getAll() {
			$services = array();
			if (!empty($this->services) && is_array($this->services)) {
				foreach ($this->services as $id => $service) {
					$services[$id] = $this->getById($id);
				}
			}
			return $services;
		}

	}
?>