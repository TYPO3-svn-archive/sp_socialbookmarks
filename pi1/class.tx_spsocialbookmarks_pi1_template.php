<?php
	/*********************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2009 - 2012 Kai Vogel  <kai.vogel(at)speedprogs.de>
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
	 * Template handler
	 */
	class tx_spsocialbookmarks_pi1_template {
		protected $cObj       = NULL;
		protected $labels     = array();
		protected $setup      = array();
		protected $markers    = array();
		protected $templates  = array();
		protected $extKey     = '';
		protected $LLkey      = 'en';


		/**
		 * Set configuration for template object
		 *
		 * @return void
		 */
		public function initialize($parent) {
			$this->cObj    = $parent->cObj;
			$this->labels  = $parent->labels;
			$this->setup   = $parent->setup;
			$this->extKey  = $parent->extKey;
			$this->LLKey   = $parent->LLkey;

			$ressource = $this->cObj->fileResource($this->setup['templateFile']);
			$this->templates = array(
				'main'  => $this->cObj->getSubpart($ressource, '###TEMPLATE###'),
				'image' => $this->cObj->getSubpart($ressource, '###IMAGE###'),
				'link'  => $this->cObj->getSubpart($ressource, '###SERVICE###'),
			);
		}


		/**
		 * Predefine default markers
		 *
		 * @return void
		 */
		public function addDefaultMarkers() {
				// User defined markers
			if (!empty($this->setup['markers.']) && is_array($this->setup['markers.'])) {
				$name = '';
				$type = '';

				foreach ($this->setup['markers.'] as $key => $value) {
					if (substr($key, -1) !== '.' && is_string($value)) {
						$name = $key;
						$type = $value;
					} else if ($name !== '' && $type !== '') {
						$this->markers['###' . strtoupper($name) . '###'] = $this->cObj->cObjGetSingle($type, $value, $key);
						$name = '';
						$type = '';
					}
				}
			}

				// Locallang markers
			foreach ($this->labels as $key => $value) {
				$this->markers['###LLL:' . $key . '###'] = $value;
			}
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


		/**
		 * Add bookmark services to marker array
		 *
		 * @param array $services The services array
		 * @return void
		 */
		public function addServices(array $services) {
			if (empty($services)) {
				return FALSE;
			}

				// Get configuration
			$url    = $this->getPageUrl();
			$title  = $this->getTitle();
			$target = (!empty($this->setup['linkTarget']) ? $this->setup['linkTarget'] : '_blank');
			$links  = array();

				// Get all links from configuration
			foreach ($services as $key => $service) {
					$image = $this->getImage($service);
					$service['key'] = $key;
					$links[] = $this->getLink($service, $url, $title, $target, $image);
			}

				// Add bookmarks to marker array
			$this->markers['###SERVICES###'] = implode(PHP_EOL, $links);

			return TRUE;
		}


		/**
		 * Get URL to current page
		 *
		 * @return string URL to current page
		 */
		protected function getPageUrl() {
				// Get baseURL
			$protocol = (!empty($this->setup['forceSSL']) && $this->setup['forceSSL'] ? 'https://' : 'http://');
			$baseUrl  = (!empty($this->setup['baseURL.']) ? $this->cObj->cObjGetSingle($this->setup['baseURL'], $this->setup['baseURL.']) : $this->setup['baseURL']);
			$baseUrl  = (!empty($baseUrl) ? $baseUrl : $_SERVER['HTTP_HOST']);
			$baseUrl  = $protocol . str_replace(array('https://', 'http://'), '', rtrim($baseUrl, '/')) . '/';

				// Get URL to current page
			$url = $this->cObj->typolink('', array(
				'parameter'       => (int) $GLOBALS['TSFE']->id,
				'returnLast'      => 'url',
				'addQueryString'  => 1,
				'addQueryString.' => array(
						'exclude'       => 'id,cHash,no_cache',
						'method'        => 'GET',
				),
			));

				// Get final link
			if (!empty($this->setup['useTinyURL'])) {
				$serviceUrl = (!empty($this->setup['tinyServiceURL']) ? $this->setup['tinyServiceURL'] : 'http://tinyurl.com/api-create.php?url=###URL###');
				$tinyUrl    = t3lib_div::getURL(str_replace('###URL###', $baseUrl . $url, $serviceUrl));
				if (!empty($tinyUrl)) {
					return $tinyUrl;
				}
			}

			return $baseUrl . $url;
		}


		/**
		 * Get page title or news title if any
		 *
		 * @return string Title
		 */
		protected function getTitle() {
			$title = '';

			$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'pages', 'uid=' . (int) $GLOBALS['TSFE']->id, '', '', '1');
			if (!empty($result)) {
				$cObj = clone($this->cObj);
				$cObj->start(reset($result), 'pages');
				$title = $cObj->cObjGetSingle($this->setup['pageTitle'], $this->setup['pageTitle.']);
				unset($cObj);
			}

				// Get title (javascript will be used to get it if empty)
			$title = (!empty($this->setup['useTSTitle']) ? urlencode($title) : '###TITLE###');

			return $title;
		}


		/**
		 * Get substituted image
		 *
		 * @param array $service Service information
		 * @return string Image
		 */
		protected function getImage(array $service) {
			if (empty($service)) {
				return '';
			}

				// Get configuration
			$fileName = (!empty($service['image']) ? $service['image'] : 'EXT:sp_socialbookmarks/res/images/default.png');
			$fileName = t3lib_div::getFileAbsFileName($fileName);
			$height   = 16;
			$width    = 16;

				// Get image size
			if (@file_exists($fileName)) {
				list($width, $height) = @getimagesize($fileName);
			}

			$imageMarkers = array(
				'###IMAGE_URL###'    => $this->getRelativePath($fileName),
				'###IMAGE_HEIGHT###' => $height,
				'###IMAGE_WIDTH###'  => $width,
				'###IMAGE_ALT###'    => (!empty($service['alt'])  ? $service['alt']  : ''),
				'###IMAGE_TITLE###'  => (!empty($service['name']) ? $service['name'] : ''),
			);

			return $this->cObj->substituteMarkerArray($this->templates['image'], $imageMarkers);
		}


		/**
		 * Get all markers for a link
		 *
		 * @param array $service Service information
		 * @param string $url The URL
		 * @param string $title Title of the link
		 * @param string $target Target for the link
		 * @param string $image An optional image
		 * @return array All link markers
		 */
		protected function getLink(array $service, $url, $title, $target = '_blank', $image = '') {
			if (empty($service) || empty($url)) {
				return '';
			}

				// Get serialized data
			$data = array(
				'pid'     => (int) $GLOBALS['TSFE']->id,
				'service' => strtolower(trim($service['key'])),
			);
			$data = base64_encode(serialize($data));

				// Fill markers
			$linkMarkers = array(
				'###LINK_URL###'    => str_replace(array('###URL###', '###TITLE###'), array(urlencode($url), $title), $service['url']),
				'###LINK_TITLE###'  => $service['name'],
				'###LINK_ID###'     => 'bookmark_' . (!empty($service['name']) ? $service['name'] : md5($GLOBALS['EXEC_TIME'])),
				'###LINK_TARGET###' => $target,
				'###LINK_JS###'     => (!empty($this->setup['useStats']) ? "javascript:bookmark('" . $data . "');" : ''),
				'###IMAGE###'       => $image,
			);

			return $this->cObj->substituteMarkerArray($this->templates['link'], $linkMarkers);
		}


		/**
		 * Get content from template and markers
		 *
		 * @return string Whole content
		 */
		public function getContent() {
			return $this->cObj->substituteMarkerArray($this->templates['main'], $this->markers);
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php']);
	}

?>