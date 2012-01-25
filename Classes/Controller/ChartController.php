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
		 * @var array
		 */
		protected $settings = array();

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
		 * Processes a general request. The result can be returned by altering the given response.
		 *
		 * @param Tx_Extbase_MVC_RequestInterface $request The request object
		 * @param Tx_Extbase_MVC_ResponseInterface $response The response, modified by this handler
		 * @throws Tx_Extbase_MVC_Exception_UnsupportedRequestType if the controller doesn't support the current request type
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

			$uxPath = $this->doc->backpath . '../t3lib/js/extjs/ux/';
			$this->pageRenderer->addJsFile($uxPath . 'Ext.ux.FitToParent.js');

			parent::processRequest($request, $response);
		}


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Pre-parse TypoScript setup
			$this->pageId = Tx_SpSocialbookmarks_Utility_Backend::getPageId();
			$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::getSetupForPid($this->pageId, 'plugin.tx_spsocialbookmarks.settings');
			$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::parse($this->settings);

				// Add chart configuration to ExtJS settings
			if (!empty($this->settings['charts']['options']) && is_array($this->settings['charts']['options'])) {
				$this->pageRenderer->addInlineSettingArray('Socialbookmarks', $this->settings['charts']['options']);
			}

				// Add labels
			if (!empty($this->settings['charts']['languageFile'])) {
				$file = $this->getRelativePath($this->settings['charts']['languageFile']);
				$this->pageRenderer->addInlineLanguageLabelFile($file);
			}

				// Add JS file
			if (!empty($this->settings['charts']['javascriptFile'])) {
				$file = $this->getRelativePath($this->settings['charts']['javascriptFile']);
				$this->pageRenderer->addJsFile($file);
			}
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
			$visits = $this->visitRepository->getByPidAndCrdate($pid, $timestamp);
			$this->view->assign('visits',   $visits);
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