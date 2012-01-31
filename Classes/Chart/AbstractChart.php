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
	 * Abstract chart
	 */
	abstract class Tx_SpSocialbookmarks_Chart_AbstractChart implements Tx_SpSocialbookmarks_Chart_ChartInterface {

		/**
		 * @var array
		 */
		protected $settings = array();

		/**
		 * @var string
		 */
		protected $defaultChartTag = '
			<div class="spsocialbookmarks-chart spsocialbookmarks-chart-%1$s" id="%2$s"></div>
			<script type="text/javascript">
				charts[\'%2$s\'] = {
					data: %3$s,
					options: {%4$s}
				};
			</script>
		';

		/**
		 * @var string
		 */
		protected $defaultChartId = 'spsocialbookmarks-chart-%s';


		/**
		 * Set configuration
		 *
		 * @param array $settings The TypoScript settings
		 * @return void
		 */
		public function setConfiguration(array $settings) {
			$this->settings = $settings;
		}


		/**
		 * Build the chart code
		 *
		 * @param array $data The chart data
		 * @param string $options Additional options
		 * @return string Rendered chart code
		 */
		protected function renderChart(array $data, $options = '') {
			if (empty($data)) {
				return '';
			}

			$type = substr(strtolower(get_class($this)), 0, -5);
			$type = substr($type, strrpos($type, '_') + 1);
			$id = sprintf($this->defaultChartId, uniqid());
			$data = json_encode($data);

			return sprintf($this->defaultChartTag, $type, $id, $data, $options);
		}

	}
?>