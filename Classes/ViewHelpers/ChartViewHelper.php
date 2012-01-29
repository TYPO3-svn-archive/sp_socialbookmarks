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
	 * View helper for charts
	 */
	class Tx_SpSocialbookmarks_ViewHelpers_ChartViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * @var string
		 */
		protected $extensionKey = 'sp_socialbookmarks';

		/**
		 * @var array
		 */
		protected $settings = array();

		/**
		 * @var Tx_Extbase_Object_ObjectManager
		 */
		protected $objectManager;


		/**
		 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
		 * @return void
		 */
		public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) {
			$settings = $configurationManager->getConfiguration(
				Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
			);
			if (!empty($settings)) {
				$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::parse($settings);
			}
		}


		/**
		 * @var Tx_Extbase_Object_ObjectManager $objectManager
		 * @return void
		 */
		public function injectObjectManager(Tx_Extbase_Object_ObjectManager $objectManager) {
			$this->objectManager = $objectManager;
		}


		/**
		 * Renders a chart
		 *
		 * @param array $data The rows and cols to show
		 * @param string $type The type of chart to render
		 * @return string The rendered chart
		 */
		public function render($data = NULL, $type = 'bar') {
			if ($data === NULL) {
				$data = $this->renderChildren();
			}
			if (!is_array($data) && !$data instanceof ArrayAccess) {
				throw new Exception('Given data is not an array');
			}

				// Find renderers
			if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'])
			 || !is_array($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'])) {
				throw new Exception('No chart renderers definined');
			}
			$renderers = $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$this->extensionKey]['chartRenderers'];

				// Make an instance of the selected chart renderer
			if (empty($type) || empty($renderers[$type])) {
				throw new Exception('No chart renderer found for type "' . $type . '"');
			}
			$renderer = $this->objectManager->get($renderers[$type]);
			if (empty($renderer) || !$renderer instanceof Tx_SpSocialbookmarks_Chart_ChartInterface) {
				throw new Exception('Class "' . $renderers[$type] . '" is a not valid chart renderer');
			}

				// Set configuration
			$renderer->setConfiguration($this->settings);

				// Render...
			return $renderer->render($data);
		}

	}
?>