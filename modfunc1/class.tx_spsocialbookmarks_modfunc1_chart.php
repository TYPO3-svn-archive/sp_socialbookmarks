<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009 Kai Vogel  <kai.vogel(at)speedprogs.de>
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published
	 *  by the Free Software Foundation; either version 2 of the License,
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
		public $sContent = '';
		public $aStyles = array(
			'grid' => array(
				'width' => '100%',
				'height' => '20px',
				'left' => '35px',
				'border-top' => '1px solid #B5B5C9',
				'margin' => '0',
				'padding' => '0',
				'position' => 'absolute',
				'z-index' => '0',
			),
			'scale' => array(
				'width' => '25px',
				'height' => '20px',
				'left' => '5px',
				'line-height' => '20px',
				'font-size' => '8px',
				'text-align' => 'right',
				'color' => '#000000',
				'margin' => '0',
				'padding' => '0',
				'position' => 'absolute',
				'z-index' => '1',
			),
			'bar' => array(
				'bottom' => '20px',
				'border' => '1px solid #666666',
				'border-right' => '2px solid #666666',
				'margin' => '0',
				'padding' => '0',
				'position' => 'absolute',
				'z-index' => '2',
			),
			'chart' => array(
				'width' => '99%',
				'height' => '200px',
				'border' => '1px solid #8E9395',
				'margin' => '0',
				'padding' => '0',
				'position' => 'relative',
				'overflow' => 'hidden',
			),
			'image' => array(
				'margin' => '0',
				'padding' => '0',
				'border' => '1px solid #AAAAAA',
				'position' => 'relative',
				'z-index' => '2',
				'cursor' => 'help',
			),
			'image_wrap' => array(
				'bottom' => '2px',
				'margin' => '0',
				'padding' => '0 auto',
				'text-align' => 'center',
				'position' => 'absolute',
				'z-index' => '2',
			),
		);


		/**
		 * Create an chart for a list of values
		 *
		 * @param array $paConfig Configuration array
		 * @param $psType The type
		 * @param $psLabel Chart label
		 * @param array $paBars The bars to show
		 * @param array $paImages Images
		 * @return String with complete chart
		 */
		public function sGetChart(array $paConfig, $psType = 'services', $psLabel = '', array $paBars = array(), array $paImages = array()) {
			if (empty($paConfig)) {
				return '';
			}

				// Get configuration
			$this->sContent = '';
			$iLastLeft = 50;
			$aChart = $paConfig['chart.'];
			$iImageWidth = ($aChart['imageWidth'] ? $aChart['imageWidth'] : 14);
			$iImageHeight = ($aChart['imageHeight'] ? $aChart['imageHeight'] : 14);
			$iSpace = ($aChart['spaceBetween'] ? $aChart['spaceBetween'] : 15);
			$sBarColor = ($aChart['barColor'] ? $aChart['barColor'] : '#6F9AE3');
			$sChartColor = ($aChart['chartColor'] ? $aChart['chartColor'] : '#E6EBFA');
			$iBarWidth = ($aChart['barWidth'] ? ($aChart['barWidth'] -3) : 17); // substract 3px border
			$sBarColor = ((substr($sBarColor,0,1) == '#') ? $sBarColor : '#'.$sBarColor);
			$sChartColor = ((substr($sChartColor,0,1) == '#') ? $sChartColor : '#'.$sChartColor);
			$iChartHeight = str_replace(array('px', '%', ' '), '', strtolower($this->aStyles['chart']['height'])) - 40;

				// Get styles
			$sBarStyle = $this->sGetStyle('bar');
			$sChartStyle = $this->sGetStyle('chart');
			$sImageStyle = $this->sGetStyle('image');
			$sWrapStyle = $this->sGetStyle('image_wrap');

				// Add grid and scale
			$this->vAddGrid();

				// Add bars and images
			if (!empty($paBars) && is_array($paBars)) {
					// Get 100%
				$iMax = array_sum($paBars);

					// Walk through the bars
				foreach ($paBars as $sKey => $iCount) {
					$iPercent = round(($iCount / ($iMax / 100)));
					$iBarHeight = floor(($iChartHeight / 100) * $iPercent) - 1; // substract 1px border
					$iBarHeight = ($iBarHeight > 0) ? $iBarHeight : 0;
					$sImage = ($paImages[$sKey]['image'] ? $paImages[$sKey]['image'] : '');
					$sAlt = ($paImages[$sKey]['alt'] ? $paImages[$sKey]['alt'] : $paConfig[$psType.'.'][$sKey.'.']['name']);
					$sTitle  = ($paImages[$sKey]['title'] ? $paImages[$sKey]['title'] : $paConfig[$psType.'.'][$sKey.'.']['name']);
					$sTitle .= ' [ ' . $iPercent . ' %' . ($psLabel ? ' / ' . $iCount . ' ' . $psLabel : '') . ' ]';

						// Add image
					if ($sImage) {
						$this->sContent .= '
							<div style="' . $sWrapStyle . ' left:' . $iLastLeft . 'px; width:' . ($iBarWidth + 3) . 'px;">
								<img src="' . $sImage.'" style="' . $sImageStyle . '" height="' . $iImageHeight . '" width="' . $iImageWidth . '" alt="' . $sAlt . '" title="' . $sTitle . '" />
							</div>
						';
					}

						// Add bar
					$this->sContent .= '
						<div style="' . $sBarStyle . ' width:' . $iBarWidth . 'px; height:' . $iBarHeight . 'px; left:' . $iLastLeft . 'px; background:' . $sBarColor . ';" title="' . $sTitle . '">
						</div>
					';
					$iLastLeft += ($iBarWidth + $iSpace);
				}
			}

				// Return complete diagram
			return '<div style="' . $sChartStyle . ' background:' . $sChartColor . ';">' . $this->sContent . '</div>';
		}


		/**
		 * Add grid and scale to content
		 *
		 * @return void
		 */
		protected function vAddGrid() {
			$sGridStyle = $this->sGetStyle('grid');
			$sScaleStyle = $this->sGetStyle('scale');

			// Add grid
			$this->sContent .= '
				<div style="' . $sGridStyle . ' bottom:0px; border-top:1px solid #7A7A89;"></div>
				<div style="' . $sGridStyle . ' bottom:20px;"></div>
				<div style="' . $sGridStyle . ' bottom:40px;"></div>
				<div style="' . $sGridStyle . ' bottom:60px;"></div>
				<div style="' . $sGridStyle . ' bottom:80px;"></div>
				<div style="' . $sGridStyle . ' bottom:100px;"></div>
				<div style="' . $sGridStyle . ' bottom:120px;"></div>
				<div style="' . $sGridStyle . ' bottom:140px;"></div>
				<div style="' . $sGridStyle . ' bottom:160px; border-top:1px solid #7A7A89;"></div>
			';

				// Add scale
			$this->sContent .= '
				<div style="' . $sScaleStyle . ' bottom:10px;">0%</div>
				<div style="' . $sScaleStyle . ' bottom:50px;">25%</div>
				<div style="' . $sScaleStyle . ' bottom:90px;">50%</div>
				<div style="' . $sScaleStyle . ' bottom:130px;">75%</div>
				<div style="' . $sScaleStyle . ' bottom:170px;">100%</div>
			';
		}


		/**
		 * Get style from configuration
		 *
		 * @param string $psType The type
		 * @return string Complete style information
		 */
		protected function sGetStyle($psType) {
			if (empty($psType)) {
				return '';
			}

				// Get configuration
			$aStyle = $this->aStyles[strtolower(trim($psType))];
			$sResult = '';

				// Combine all attributes
			if (!empty($aStyle) && is_array($aStyle)) {
				foreach ($aStyle as $sKey => $sValue) {
					$sResult .= $sKey . ':' . $sValue . '; ';
				}
			}

				// Return style information
			return trim($sResult);
		}
	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php']);
	}

?>