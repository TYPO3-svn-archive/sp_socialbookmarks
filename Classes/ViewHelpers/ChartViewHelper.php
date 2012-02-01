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
		 * @var Tx_Extbase_Object_ObjectManager
		 */
		protected $objectManager;


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

				// Use sp_charts to render
			if (t3lib_extMgm::isLoaded('sp_charts')) {
				$renderService = $this->objectManager->get('Tx_SpCharts_Service_ChartService');
				return $renderService->renderChart($type, $data);
			}

			return '';
		}

	}
?>