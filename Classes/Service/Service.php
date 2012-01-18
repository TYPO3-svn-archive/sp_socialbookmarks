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
	 * Services service
	 */
	class Tx_SpSocialbookmarks_Service_Service implements t3lib_Singleton {

		/**
		 * @var Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository
		 */
		protected $serviceRepository;


		/**
		 * Returns an instance of the service repository
		 *
		 * @return Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository
		 */
		protected function getServiceRepository() {
			if (empty($this->serviceRepository)) {
				$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
				$configuration = Tx_SpGallery_Utility_TypoScript::getSetup('plugin.tx_spsocialbookmarks');
				$configurationManager = $objectManager->get('Tx_Extbase_Configuration_ConfigurationManager');
				$configurationManager->setConfiguration($configuration);
				$this->serviceRepository = $objectManager->get('Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository');
			}
			return $this->serviceRepository;
		}


		/**
		 * Returns all available services
		 *
		 * @param array $setup TCA configuration
		 * @param object $parent Reference to calling instance
		 * @return string
		 */
		public function getServices(array $setup, $parent) {
			if (TYPO3_MODE != 'BE') {
				return array();
			}

				// Get services
			$serviceRepository = $this->getServiceRepository();
			$services = $serviceRepository->getAll();
			foreach ($services as $id => $service) {
				$id = strtolower(rtrim($id, '. '));
				$name = ($service['name'] ? $service['name'] : $id);
				$setup['items'][] = array($name, $id);
			}

				// Hook to modify the content
			$this->callHook('getServices', array(
				'parent'            => $parent,
				'setup'             => &$setup,
				'services'          => $services,
				'serviceRepository' => $this->serviceRepository,
			));

			return $setup;
		}


		/**
		 * Checks the SC_OPTIONS for valid hooks
		 *
		 * @param string $method The method name
		 * @param array $parameters The parameters for the hook
		 * @return void
		 */
		protected function callHook($method, array $parameters) {
			$hookClasses = NULL;
			if (!empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Tx_SpSocialbookmarks_Service_Tca'][$method])) {
				$hookClasses = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['Tx_SpSocialbookmarks_Service_Tca'][$method];
			}
			if (is_array($hookClasses)) {
				foreach ($hookClasses as $class) {
					t3lib_div::callUserFunction($class, $parameters, $this);
				}
			}
		}

	}
?>