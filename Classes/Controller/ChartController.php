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
	 * Controller for the visits chart
	 */
	class Tx_SpSocialbookmarks_Controller_ChartController extends Tx_SpSocialbookmarks_Controller_AbstractController {

		/**
		 * @var string
		 */
		protected $extensionName = 'SpSocialbookmarks';

		/**
		 * @var integer
		 */
		protected $pageId = 0;

		/**
		 * @var Tx_SpSocialbookmarks_Domain_Repository_VisitRepository
		 */
		protected $visitRepository;


		/**
		 * @param Tx_SpSocialbookmarks_Domain_Repository_VisitRepository $galleryRepository
		 * @return void
		 */
		public function injectGalleryRepository(Tx_SpSocialbookmarks_Domain_Repository_VisitRepository $visitRepository) {
			$this->visitRepository = $visitRepository;
		}


		/**
		 * Process a request
		 *
		 * @param Tx_Extbase_MVC_RequestInterface $request The request object
		 * @param Tx_Extbase_MVC_ResponseInterface $response The response
		 * @return void
		 */
		public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
			$this->template = t3lib_div::makeInstance('template');
			$this->pageRenderer = $this->template->getPageRenderer();

			if (empty($GLOBALS['SOBE'])) {
				$GLOBALS['SOBE'] = new stdClass();
			}

			if (empty($GLOBALS['SOBE']->doc)) {
				$GLOBALS['SOBE']->doc = $this->template;
			}

			parent::processRequest($request, $response);
		}


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
			$this->pageId = Tx_SpSocialbookmarks_Utility_Backend::getPageId();

				// Forward to list action if page id is empty
			$action = $this->request->getControllerActionName();
			if (empty($this->pageId) && $action !== 'list') {
				$this->forward('list');
			}

				// Pre-parse TypoScript setup
			if (!empty($this->settings) && is_array($this->settings)) {
				$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::parse($this->settings);
			}

				// Add stylesheets
			if (!empty($this->settings['stylesheet']) && is_array($this->settings['stylesheet'])) {
				foreach($this->settings['stylesheet'] as $file) {
					$this->pageRenderer->addCssFile($this->getRelativePath($file));
				}
			}

				// Add javascript libraries
			if (!empty($this->settings['javascript']) && is_array($this->settings['javascript'])) {
				$libraries = array_reverse($this->settings['javascript']);
				foreach($libraries as $key => $file) {
					$file = $this->getRelativePath($file);
					$this->pageRenderer->addJsLibrary($key, $file, 'text/javascript', FALSE, TRUE);
				}
			}
		}


		/**
		 * Display a list of bookmarks pages
		 *
		 * @return void
		 */
		public function listAction() {

		}


		/**
		 * Display the chart
		 *
		 * @param string $mode Show all or only current page
		 * @param string $period Time period to show
		 * @return void
		 */
		public function showAction($mode = '', $period = '') {
			$pid = (int) ($mode === 'this' ? $this->pageId : 0);
			$timestamp = $this->getTimestamp($period);
			//$visits = $this->visitRepository->getByPidAndCrdate($pid, $timestamp);

			$testData = array(
				array('AddThis',   180),
				array('AddThis',   112),
				array('Ask',       684),
				array('Bluedot',   84),
				array('Bluedot',   200),
				array('Delicious', 480),
				array('Delicidous', 480),
				array('Deliscious', 480),
				array('Delicdious', 480),
				array('Delsicious', 480),
				array('Delsicious', 480),
				array('Delsicfious', 480),
				array('Delicihous', 480),
				array('Delficious', 480),
				array('Delicious', 480),
				array('Deliscious', 480),
				array('Delgicious', 4860),
				array('Deliecious', 4580),
			);

			$this->view->assign('services', $testData);
			$this->view->assign('systems',  $testData);
			$this->view->assign('browsers', $testData);
			$this->view->assign('settings', $this->settings);
		}


		/**
		 * Get timestamp for current period
		 *
		 * @param string $period Time period
		 * @return integer Timestamp
		 */
		protected function getTimestamp($period) {
			if (empty($period)) {
				return 0;
			}

			switch ($period) {
				case 'day' :
					return ((int) $GLOBALS['EXEC_TIME'] - 24*60*60);
				break;
				case 'week' :
					return ((int) $GLOBALS['EXEC_TIME'] - 7*24*60*60);
				break;
				case 'month' :
					return ((int) $GLOBALS['EXEC_TIME'] - 30*24*60*60);
				break;
				case 'year' :
					return ((int) $GLOBALS['EXEC_TIME'] - 356*24*60*60);
				break;
				case 'all' :
				default :
					return 0;
			}
		}


		/**
		 * Get relative path
		 *
		 * @param string $fileName The file name
		 * @return string Relative path
		 */
		protected function getRelativePath($fileName) {
			if (empty($fileName)) {
				return '';
			}

			$backPath = $this->doc->backpath . '../';
			$fileName = t3lib_div::getFileAbsFileName($fileName);

			return str_replace(PATH_site, $backPath, $fileName);
		}

	}
?>