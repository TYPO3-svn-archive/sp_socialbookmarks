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

	require_once(PATH_t3lib . 'class.t3lib_extobjbase.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_db.php');
	require_once(t3lib_extMgm::extPath('sp_socialbookmarks') . 'class.tx_spsocialbookmarks_typoscript.php');

	/**
	 * Module extension
	 */
	class tx_spsocialbookmarks_modfunc1 extends t3lib_extobjbase {
		public $setup = array();

		/**
		 * Returns the module menu
		 *
		 * @return array Menu items
		 */
		public function modMenu() {
			return array (
				'mode'   => array (
					'all'    => $GLOBALS['LANG']->getLL('mode_all'),
					'page'   => $GLOBALS['LANG']->getLL('mode_this'),
				),
				'period' => array (
					'all'    => $GLOBALS['LANG']->getLL('period_all'),
					'year'   => $GLOBALS['LANG']->getLL('period_year'),
					'month'  => $GLOBALS['LANG']->getLL('period_month'),
					'week'   => $GLOBALS['LANG']->getLL('period_week'),
					'day'    => $GLOBALS['LANG']->getLL('period_day'),
				),
				'showBrowsers' => '',
				'showSystems'  => '',
			);
		}


		/**
		 * Main method of the module
		 *
		 * @return string HTML content
		 */
		public function main() {
			$pid = (int) $this->pObj->id;

				// Get TyopScript configuration
			$parser = t3lib_div::makeInstance('tx_spsocialbookmarks_typoscript');
			$parser->initializeContentObject($pid);
			$this->setup = $parser->parse($parser->getSetup($pid));

				// Load environment
			$menus    = $this->getFuncMenus($pid);
			$document = $this->pObj->doc;
			$charts   = $this->getActiveCharts();

				// Get data from db
			$database = t3lib_div::makeInstance('tx_spsocialbookmarks_db');
			$uid      = ($this->pObj->MOD_SETTINGS['mode'] == 'page' ? $pid : 0);
			$data     = $database->getData($uid, $this->getPeriod());

				// Begin document
			$content  = $document->spacer(5);
			$content .= $document->section($GLOBALS['LANG']->getLL('title'), $menus, 0, 1, 0, 0);
			$content .= $document->sectionEnd();

				// Get charts
			$chartService = t3lib_div::makeInstance('tx_spsocialbookmarks_modfunc1_chart');
			foreach ($charts as $type) {
				$counts = $this->getCounts($data, $type);
				$images = $this->getImages($type);
				$chart  = $chartService->getChart($this->setup, $type, $GLOBALS['LANG']->getLL('clicks'), $counts, $images);

				$content .= $document->spacer(10);
				$content .= $document->section($GLOBALS['LANG']->getLL('title_chart_' . $type), $chart, 1, 1, 1, 0);
				$content .= $document->sectionEnd();
			}

				// Return document
			return $content;
		}


		/**
		 * Get selector for view mode and period
		 *
		 * @param integer $pid Current page id
		 * @return string Menu
		 */
		protected function getFuncMenus($pid) {
			$settings = $this->pObj->MOD_SETTINGS;
			$menu = $this->pObj->MOD_MENU;

			return '
				' . t3lib_BEfunc::getFuncMenu($pid, 'SET[mode]', $settings['mode'], $menu['mode'], 'index.php') . '
				' . t3lib_BEfunc::getFuncMenu($pid, 'SET[period]', $settings['period'], $menu['period'], 'index.php') . '&nbsp;&nbsp;
				' . t3lib_BEfunc::getFuncCheck($pid, 'SET[showBrowsers]', $settings['showBrowsers'], 'index.php') . '
				' . $GLOBALS['LANG']->getLL('showBrowsers') . '&nbsp;&nbsp;&nbsp;
				' . t3lib_BEfunc::getFuncCheck($pid, 'SET[showSystems]', $settings['showSystems'], 'index.php') . '
				' . $GLOBALS['LANG']->getLL('showSystems') . '
			';
		}


		/**
		 * Get current period
		 *
		 * @return integer Time period
		 */
		protected function getPeriod() {
			switch (strtolower($this->pObj->MOD_SETTINGS['period'])) {
				case 'day' :
					$period = ((int) $GLOBALS['EXEC_TIME'] - 24*60*60);
				break;
				case 'week' :
					$period = ((int) $GLOBALS['EXEC_TIME'] - 7*24*60*60);
				break;
				case 'month' :
					$period = ((int) $GLOBALS['EXEC_TIME'] - 30*24*60*60);
				break;
				case 'year' :
					$period = ((int) $GLOBALS['EXEC_TIME'] - 356*24*60*60);
				break;
				case 'all' :
				default :
					$period = 0;
				break;
			}

			return $period;
		}


		/**
		 * Get active charts
		 *
		 * @return array Active chart names
		 */
		protected function getActiveCharts() {
			$available = array('browsers', 'systems');
			$result = array('services');

			foreach ($available as $chartName) {
				$name = 'show' . ucfirst(strtolower($chartName));
				if (!empty($this->pObj->MOD_SETTINGS[$name])) {
					$result[] = strtolower($chartName);
				}
			}

			return $result;
		}


		/**
		 * Get the counts for each service
		 *
		 * @param array $data Services
		 * @param string $type Count type
		 * @return array  All counts
		 */
		protected function getCounts(array $data, $type = 'services') {
			$type = strtolower(trim($type));

			if (empty($data) || empty($this->setup[$type]) || !is_array($this->setup[$type])) {
				return array();
			}

			$counts = array();

			if ($type == 'services') {
				foreach ($data as $key => $service) {
					if (array_key_exists(trim($key) . '.', $this->setup[$type])) {
						$counts[$key] = count($service);
					}
				}
			} else {
				foreach ($data as $elements) {
					foreach ($elements as $element) {
						$name = 'unknown';

						foreach($this->setup[$type] as $key => $setup) {
							if (preg_match('/' . addcslashes($setup['ident'], '/') . '/i', $element['agent'])) {
								$name = substr($key, 0 , -1);
							}
						}
						$counts[$name] = (isset($counts[$name]) ? $counts[$name] + 1 : 1);
					}
				}
			}

				// Sort for output
			arsort($counts);

			return $counts;
		}


		/**
		 * Get images to all services
		 *
		 * @param $type Image type
		 * @return array Service images
		 */
		public function getImages($type = 'services') {
			$type = strtolower(trim($type));

			if (empty($this->setup[$type]) || !is_array($this->setup[$type])) {
				return array();
			}

				// Get configuration
			$allowedTypes = strtolower($GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']);
			$images = array();

				// Get images
			foreach ($this->setup[$type] as $key => $value) {
				$fileName = t3lib_div::getFileAbsFileName($value['image']);
				$fileType = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
				if (!t3lib_div::inList($allowedTypes, $fileType) || !is_readable($fileName)) {
					continue;
				}

				$value['image'] = '../' . $GLOBALS['BACK_PATH'] . $this->getRelativePath($fileName);
				$images[substr($key, 0, -1)] = $value;
			}

			return $images;
		}


		/**
		 * Check for extension relative path
		 *
		 * @param string $fileName Path to the file
		 * @return string Relative file path
		 */
		protected function getRelativePath($fileName) {
			$fileName = t3lib_div::getFileAbsFileName($fileName);
			return str_replace(PATH_site, '', $fileName);
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/modfunc1/class.tx_spsocialbookmarks_modfunc1.php']);
	}

?>