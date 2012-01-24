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
	 * Controller for the bookmarks
	 */
	class Tx_SpSocialbookmarks_Controller_BookmarksController extends Tx_SpSocialbookmarks_Controller_AbstractController {

		/**
		 * @var array
		 */
		protected $plugin;

		/**
		 * @var Tx_SpSocialbookmarks_Domain_Repository_VisitRepository
		 */
		protected $visitRepository;

		/**
		 * @var Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository
		 */
		protected $serviceRepository;

		/**
		 * @var Tx_SpSocialbookmarks_Object_ObjectBuilder
		 */
		protected $objectBuilder;


		/**
		 * @param Tx_SpSocialbookmarks_Domain_Repository_VisitRepository $galleryRepository
		 * @return void
		 */
		public function injectGalleryRepository(Tx_SpSocialbookmarks_Domain_Repository_VisitRepository $visitRepository) {
			$this->visitRepository = $visitRepository;
		}


		/**
		 * @param Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository $serviceRepository
		 * @return void
		 */
		public function injectServiceRepository(Tx_SpSocialbookmarks_Domain_Repository_ServiceRepository $serviceRepository) {
			$this->serviceRepository = $serviceRepository;
		}


		/**
		 * @param Tx_SpSocialbookmarks_Object_ObjectBuilder $objectBuilder
		 * @return void
		 */
		public function injectObjectBuilder(Tx_SpSocialbookmarks_Object_ObjectBuilder $objectBuilder) {
			$this->objectBuilder = $objectBuilder;
		}


		/**
		 * Initialize the current action
		 *
		 * @return void
		 */
		protected function initializeAction() {
				// Pre-parse TypoScript setup
			$this->settings = Tx_SpSocialbookmarks_Utility_TypoScript::parse($this->settings);

				// Get information about current plugin
			$contentObject = $this->configurationManager->getContentObject();
			$this->plugin = (!empty($contentObject->data) ? $contentObject->data : array());

				// Check / set storagePid
			$action = $this->request->getControllerActionName();
			$pid = $GLOBALS['TSFE']->id;
			if ($action === 'click') {
				$extensionKey = $this->request->getControllerExtensionKey();
				if (!Tx_SpSocialbookmarks_Utility_Persistence::hasStoragePage($extensionKey, 'visit', $pid)) {
					$this->setStoragePid($pid);
				}
			}
		}


		/**
		 * Display the bookmarks
		 *
		 * @return void
		 */
		public function showAction() {
				// Get selected services
			$serviceIds = array();
			if (!empty($this->settings['serviceList'])) {
				$serviceIds = t3lib_div::trimExplode(',', $this->settings['serviceList'], TRUE);
			}
			$services = array();
			foreach ($serviceIds as $id) {
				$services[$id] = $this->serviceRepository->getById($id);
			}

			$this->view->assign('services', $services);
			$this->view->assign('settings', $this->settings);
			$this->view->assign('plugin',   $this->plugin);
		}


		/**
		 * Handle a click
		 *
		 * @param string $service The id of the service
		 * @return void
		 */
		public function clickAction($service) {
			if (empty($service) || !ctype_alnum($service)) {
				return;
			}

				// Get service information
			$service = $this->serviceRepository->getById($service);
			if (empty($service)) {
				return;
			}

				// Add visit to storage
			$attributes = array(
				'ip'      => t3lib_div::getIndpEnv('REMOTE_ADDR'),
				'agent'   => t3lib_div::getIndpEnv('HTTP_USER_AGENT'),
				'service' => trim($serviceId),
			);
			$visit = $this->objectBuilder->create('Tx_SpSocialbookmarks_Domain_Model_Visit', $attributes);
			$this->visitRepository->add($visit);

				// Redirect to service URL
			$serviceUrl = $this->buildServiceUrl($service);
			$this->redirectToURI($serviceUrl);
		}


		/**
		 * Build service url
		 *
		 * @param Tx_SpSocialbookmarks_Domain_Model_Service $service The service
		 * @return string Service url
		 */
		protected function buildServiceUrl(Tx_SpSocialbookmarks_Domain_Model_Service $service) {
				// Get current page url
			$pageUrl = '';
			if (!empty($this->settings['pageUrl'])) {
				$pageUrl = trim($this->settings['pageUrl']);
			}

				// Force SSL
			if (!empty($this->settings['forceSSL'])) {
				$pageUrl = 'https://' . str_replace(array('http://', 'https://'), '', $pageUrl);
			}

				// Get current page title
			$pageTitle = '';
			if (!empty($this->settings['pageTitle'])) {
				$pageTitle = trim($this->settings['pageTitle']);
			}

				// Build final url
			$url = $service->getUrl();
			$url = str_replace(array('###URL###', '###TITLE###'), array(urlencode($pageUrl), urlencode($pageTitle)), $url);

				// Use tiny url service
			if (!empty($this->settings['useTinyURL']) && !empty($this->settings['tinyServiceURL'])) {
				$tinyUrl = $this->settings['tinyServiceURL'];
				$url = t3lib_div::getURL(str_replace('###URL###', $url, $tinyUrl));
			}

			return $url;
		}

	}
?>