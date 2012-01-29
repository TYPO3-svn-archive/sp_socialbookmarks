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
	 * Renderer for column chart
	 */
	class Tx_SpSocialbookmarks_Chart_ColumnChart extends Tx_SpSocialbookmarks_Chart_AbstractChart {

		/**
		 * @var string
		 */
		protected $options = '
			seriesDefaults:{
				renderer: jQuery.jqplot.BarRenderer,
				pointLabels: {
					show: true,
					location: \'e\',
					edgeTolerance: -15
				},
				shadowAngle: 135,
				rendererOptions: {
					fillToZero: true,
					barDirection: \'horizontal\'
				}
			},
			axes: {
				yaxis: {
					renderer: jQuery.jqplot.CategoryAxisRenderer,
					ticks: %1$s
				}
			}
		';


		/**
		 * Render the chart
		 *
		 * @param array $values The data to show
		 * @return string The rendered chart
		 */
		public function render($data) {
				// Get bars
			$bars = array();
			foreach ($data as $value) {
				if (!isset($bars[$value[0]])) {
					$bars[$value[0]] = (int) $value[1];
				} else {
					$bars[$value[0]] += (int) $value[1];
				}
			}

			$data = array(array_values($bars));
			$options = sprintf($this->options, json_encode(array_keys($bars)));

			return $this->renderChart($data, $options);
		}

	}
?>