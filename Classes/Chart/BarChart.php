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
	 * Renderer for bar chart
	 */
	class Tx_SpSocialbookmarks_Chart_BarChart extends Tx_SpSocialbookmarks_Chart_AbstractBarBasedChart {

		/**
		 * Render the chart
		 * 
		 * @param array $values The rows and cols to show
		 * @param string $x The attribute name for the X axis
		 * @param string $y The attribtue name for the Y axis
		 * @param string $xLabel Label for the X axis
		 * @param string $yLabel Label for the Y axis
		 * @param boolean $showLegend Show legend beside chart
		 * @return string The rendered chart
		 */
		public function render($values, $x = NULL, $y = NULL, $xLabel = '', $yLabel = '', $showLegend = FALSE) {
			
		}

	}
?>