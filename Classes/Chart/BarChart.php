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
	class Tx_SpSocialbookmarks_Chart_BarChart extends Tx_SpSocialbookmarks_Chart_AbstractGridBasedChart {

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
				// Get X and Y axis keys
			if (empty($x) || empty($y)) {
				$first = reset($values);
				$y = (empty($x) ? $first[0] : $y);
				$x = (empty($x) ? $first[1] : $x);
			}

				// Get bars
			$bars = array();
			foreach ($values as $value) {
				if (!isset($bars[$value[$y]])) {
					$bars[$value[$y]] = (int) $value[$x];
				} else {
					$bars[$value[$y]] += (int) $value[$x];
				}
			}

				// Build grid area
			$columns = 10;
			$rows = 5;
			$content = $this->renderHorizontalGrid($columns, $rows);

				// Build legend
			if (!empty($showLegend)) {
				$content .= $this->renderLegend();
			}

				// Build bars
			$content .= $this->renderBars($bars);

			return $content;
		}


		/**
		 * Render the bars of the chart
		 *
		 * @param array $bars The bars to render
		 * @return string Rendered HTML content
		 */
		protected function renderBars(array $bars) {
			return '';
		}

	}
?>