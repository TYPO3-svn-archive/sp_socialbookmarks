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
	class Tx_SpSocialbookmarks_Controller_ChartController extends Tx_Extbase_MVC_Controller_ActionController {

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

				parent::processRequest($request, $response);
/*
				$pageHeader = $this->template->startpage(
						$GLOBALS['LANG']->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang.xml:module.title')
				);
				$pageEnd = $this->template->endPage();

				$response->setContent($pageHeader . $response->getContent() . $pageEnd);
*/
		}


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Pre-parse TypoScript setup
			$this->pageId = Tx_SpSocialbookmarks_Utility_Backend::getPageId();
			$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::getSetupForPid($this->pageId, 'plugin.tx_spsocialbookmarks');
			$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::parse($this->settings);
			$this->pageRenderer->addInlineLanguageLabelFile('EXT:sp_socialbookmarks/Resources/Private/Language/locallang_mod.xml');
		}


		/**
		 * Display the chart
		 *
		 * @return void
		 */
		public function showAction() {
			$this->view->assign('visits',   $visits);
			$this->view->assign('settings', $this->settings);
		}

	}
?>