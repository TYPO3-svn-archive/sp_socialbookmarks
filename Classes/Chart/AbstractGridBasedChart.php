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
	 * Abstract grid based chart
	 */
	abstract class Tx_SpSocialbookmarks_Chart_AbstractGridBasedChart extends Tx_SpSocialbookmarks_Chart_AbstractChart {

		/**
		 * @var string
		 */
		protected $area = '
			<div class="chart-grid-area-%s">
				<div class="chart-grid-area-inner">%s</div>%s
			</div>
		';

		/**
		 * @var string
		 */
		protected $verticalLine = '<div class="chart-grid-vertical-line" style="width:%s%%"></div>';

		/**
		 * @var string
		 */
		protected $verticalEdge = '<div class="chart-grid-vertical-edge" style="left:%s%%"></div>';

		/**
		 * @var string
		 */
		protected $horizontalLine = '<div class="chart-grid-horizontal-line" style="height:%s%%"></div>';

		/**
		 * @var string
		 */
		protected $horizontalEdge = '<div class="chart-grid-horizontal-edge" style="top:%s%%"></div>';


		/**
		 * Render a vertical grid area
		 *
		 * @param integer $columns Column count
		 * @param integer $rows Row count
		 * @return string Rendered HTML content
		 */
		protected function renderVerticalGrid($columns, $rows) {
			$objects = array();

			return sprintf($this->area, 'vertical', implode(PHP_EOL, $objects));
		}


		/**
		 * Render a horizontal grid area
		 *
		 * @param integer $columns Column count
		 * @param integer $rows Row count
		 * @return string Rendered HTML content
		 */
		protected function renderHorizontalGrid($columns, $rows) {
			$inner = array();
			$outer = array();
			$columns = (int) $columns;
			$rows = (int) $rows;

				// Columns
			$multiplier = (100 / $columns);
			for ($i = 1; $i < $columns; $i++) {
				$inner[] = sprintf($this->verticalLine, $i * $multiplier);
				$outer[] = sprintf($this->verticalEdge, $i * $multiplier);
			}

				// Rows
			$multiplier = (100 / $rows);
			for ($i = 1; $i < $rows; $i++) {
				$inner[] = sprintf($this->horizontalLine, $i * $multiplier);
			}

			return sprintf($this->area, 'horizontal', implode(PHP_EOL, $inner), implode(PHP_EOL, $outer));
		}

	}
?>