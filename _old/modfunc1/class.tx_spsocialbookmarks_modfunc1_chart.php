<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009-2012 Kai Vogel <kai.vogel@speedprogs.de>, Speedprogs.de
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
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ********************************************************************/

	/**
	 * Chart module
	 */
	class tx_spsocialbookmarks_modfunc1_chart {
		public $content = '';
		public $styles = array(
			'grid' => array(
				'width'        => '100%',
				'height'       => '20px',
				'left'         => '35px',
				'border-top'   => '1px solid #B5B5C9',
				'margin'       => '0',
				'padding'      => '0',
				'position'     => 'absolute',
				'z-index'      => '0',
			),
			'scale' => array(
				'width'        => '25px',
				'height'       => '20px',
				'left'         => '5px',
				'line-height'  => '20px',
				'font-size'    => '8px',
				'text-align'   => 'right',
				'color'        => '#000000',
				'margin'       => '0',
				'padding'      => '0',
				'position'     => 'absolute',
				'z-index'      => '1',
			),
			'bar' => array(
				'bottom'       => '20px',
				'border'       => '1px solid #666666',
				'border-right' => '2px solid #666666',
				'margin'       => '0',
				'padding'      => '0',
				'position'     => 'absolute',
				'z-index'      => '2',
			),
			'chart' => array(
				'width'        => '99%',
				'height'       => '200px',
				'border'       => '1px solid #8E9395',
				'margin'       => '0',
				'padding'      => '0',
				'position'     => 'relative',
				'overflow'     => 'hidden',
			),
			'image' => array(
				'margin'       => '0',
				'padding'      => '0',
				'border'       => '1px solid #AAAAAA',
				'position'     => 'relative',
				'z-index'      => '2',
				'cursor'       => 'help',
			),
			'image_wrap' => array(
				'bottom'       => '2px',
				'margin'       => '0',
				'padding'      => '0 auto',
				'text-align'   => 'center',
				'position'     => 'absolute',
				'z-index'      => '2',
			),
		);


		/**
		 * Create an chart for a list of values
		 *
		 * @param array $setup Configuration array
		 * @param $type The type
		 * @param $label Chart label
		 * @param array $bars The bars to show
		 * @param array $images Images
		 * @return String with complete chart
		 */
		public function getChart(array $setup, $type = 'services', $label = '', array $bars = array(), array $images = array()) {
			if (empty($setup)) {
				return '';
			}

				// Get configuration
			$this->content = '';
			$lastLeft    = 50;
			$chart       = (!empty($setup['chart'])  ? $setup['chart']         : array());
			$imageWidth  = ($chart['imageWidth']     ? $chart['imageWidth']    : 14);
			$imageHeight = ($chart['imageHeight']    ? $chart['imageHeight']   : 14);
			$space       = ($chart['spaceBetween']   ? $chart['spaceBetween']  : 15);
			$barColor    = ($chart['barColor']       ? $chart['barColor']      : '#6F9AE3');
			$chartColor  = ($chart['chartColor']     ? $chart['chartColor']    : '#E6EBFA');
			$barWidth    = ($chart['barWidth']       ? ($chart['barWidth'] -3) : 17); // substract 3px border
			$barColor    = '#' . ltrim($barColor, '#');
			$chartColor  = '#' . ltrim($chartColor);
			$chartHeight = ((int) $this->styles['chart']['height'] - 40);

				// Get styles
			$barStyle   = $this->getStyle('bar');
			$chartStyle = $this->getStyle('chart');
			$imageStyle = $this->getStyle('image');
			$wrapStyle  = $this->getStyle('image_wrap');

				// Add grid and scale
			$this->addGrid();

				// Add bars and images
			if (!empty($bars) && is_array($bars)) {
					// Get 100%
				$max = array_sum($bars);

					// Walk through the bars
				foreach ($bars as $key => $count) {
					$percent   = round(($count / ($max / 100)));
					$barHeight = floor(($chartHeight / 100) * $percent) - 1; // substract 1px border
					$barHeight = ($barHeight > 0 ? $barHeight : 0);
					$image     = (!empty($images[$key]['image']) ? $images[$key]['image'] : '');
					$alt       = (!empty($images[$key]['alt'])   ? $images[$key]['alt']   : $setup[$type][$key]['name']);
					$title     = (!empty($images[$key]['title']) ? $images[$key]['title'] : $setup[$type][$key]['name']);
					$title    .= ' [ ' . $percent . ' %' . (!empty($label) ? ' / ' . $count . ' ' . $label : '') . ' ]';

						// Add image
					if ($image) {
						$this->content .= '
							<div style="' . $wrapStyle . ' left:' . $lastLeft . 'px; width:' . ($barWidth + 3) . 'px;">
								<img src="' . $image . '" style="' . $imageStyle . '" height="' . $imageHeight . '" width="' . $imageWidth . '" alt="' . $alt . '" title="' . $title . '" />
							</div>
						';
					}

						// Add bar
					$this->content .= '
						<div style="' . $barStyle . ' width:' . $barWidth . 'px; height:' . $barHeight . 'px; left:' . $lastLeft . 'px; background:' . $barColor . ';" title="' . $title . '">
						</div>
					';
					$lastLeft += ($barWidth + $space);
				}
			}

				// Return complete diagram
			return '<div style="' . $chartStyle . ' background:' . $chartColor . ';">' . $this->content . '</div>';
		}


		/**
		 * Add grid and scale to content
		 *
		 * @return void
		 */
		protected function addGrid() {
			$gridStyle  = $this->getStyle('grid');
			$scaleStyle = $this->getStyle('scale');

			// Add grid
			$this->content .= '
				<div style="' . $gridStyle . ' bottom:0px; border-top:1px solid #7A7A89;"></div>
				<div style="' . $gridStyle . ' bottom:20px;"></div>
				<div style="' . $gridStyle . ' bottom:40px;"></div>
				<div style="' . $gridStyle . ' bottom:60px;"></div>
				<div style="' . $gridStyle . ' bottom:80px;"></div>
				<div style="' . $gridStyle . ' bottom:100px;"></div>
				<div style="' . $gridStyle . ' bottom:120px;"></div>
				<div style="' . $gridStyle . ' bottom:140px;"></div>
				<div style="' . $gridStyle . ' bottom:160px; border-top:1px solid #7A7A89;"></div>
			';

				// Add scale
			$this->content .= '
				<div style="' . $scaleStyle . ' bottom:10px;">0%</div>
				<div style="' . $scaleStyle . ' bottom:50px;">25%</div>
				<div style="' . $scaleStyle . ' bottom:90px;">50%</div>
				<div style="' . $scaleStyle . ' bottom:130px;">75%</div>
				<div style="' . $scaleStyle . ' bottom:170px;">100%</div>
			';
		}


		/**
		 * Get style from configuration
		 *
		 * @param string $type The type
		 * @return string Complete style information
		 */
		protected function getStyle($type) {
			if (empty($type)) {
				return '';
			}

				// Get configuration
			$style = $this->styles[strtolower(trim($type))];
			$result = '';

				// Combine all attributes
			if (!empty($style) && is_array($style)) {
				foreach ($style as $key => $value) {
					$result .= $key . ':' . $value . '; ';
				}
			}

				// Return style information
			return trim($result);
		}
	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php']);
	}

?>