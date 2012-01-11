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
		protected $cObj = NULL;
		protected $aLL = array();
		protected $aConfig = array();
		protected $aMarkers = array();
		protected $aTemplates = array();
		protected $extKey = '';
		protected $sLLkey = 'en';


		/**
		 * Set configuration for template object
		 *
		 * @return void
		 */
		public function vConstruct($poParent) {
			$this->cObj = $poParent->cObj;
			$this->aLL = $poParent->aLL;
			$this->aConfig = $poParent->aConfig;
			$this->extKey = $poParent->extKey;
			$this->sLLKey = $poParent->LLkey;

			$sRessource = $this->cObj->fileResource($this->aConfig['templateFile']);
			$this->aTemplates = array(
				'main' => $this->cObj->getSubpart($sRessource, '###TEMPLATE###'),
				'image' => $this->cObj->getSubpart($sRessource, '###IMAGE###'),
				'link' => $this->cObj->getSubpart($sRessource, '###SERVICE###'),
			);
		}


		/**
		 * Predefine default markers
		 *
		 * @return void
		 */
		public function vAddDefaultMarkers() {
				// Get current url
			$sLink = $this->cObj->typoLink('', array(
				'parameter' => $GLOBALS['TSFE']->id,
				'returnLast' => 'url',
			));

				// User defined markers
			if (!empty($this->aConfig['markers.']) && is_array($this->aConfig['markers.'])) {
				$sName = '';
				$sType = '';

				foreach ($this->aConfig['markers.'] as $sKey => $mValue) {
					if (substr($sKey, -1) !== '.' && is_string($mValue)) {
						$sName = $sKey;
						$sType = $mValue;
					} else if ($sName !== '' && $sType !== '') {
						$this->aMarkers['###' . strtoupper($sName) . '###'] = $this->cObj->cObjGetSingle($sType, $mValue, $sKey);
						$sName = '';
						$sType = '';
					}
				}
			}

				// Locallang markers
			foreach ($this->aLL as $sKey => $sValue) {
				$this->aMarkers['###LLL:' . $sKey . '###'] = $sValue;
			}
		}


		/**
		 * Check for extension relative path
		 *
		 * @return string Relative file path
		 */
		protected function sGetRelativePath($psFileName) {
			if (!empty($psFileName) && substr($psFileName, 0, 4) == 'EXT:') {
				list($sExtKey, $sFilePath) = explode('/', substr($psFileName, 4), 2);
				$sExtKey = strtolower($sExtKey);

				if ($sExtKey == $this->extKey || t3lib_extMgm::isLoaded($sExtKey)) {
					$psFileName = t3lib_extMgm::siteRelPath($sExtKey) . $sFilePath;
				}
			}

			return $psFileName;
		}


		/**
		 * Add bookmark services to marker array
		 *
		 * @param array $paServices The services array
		 * @return void
		 */
		public function vAddServices(array $paServices) {
			if (empty($paServices)) {
				return FALSE;
			}

				// Get configuration
			$aServices = array();
			$sURL = $this->sGetPageURL();
			$sLinkTitle = $this->sGetTitle();
			$sLinkTarget = (!empty($this->aConfig['linkTarget']) ? $this->aConfig['linkTarget'] : '_blank');

				// Get all links from configuration
			foreach ($paServices as $sKey => $aService) {
					$sImage = $this->sGetImage($aService);
					$aService['key'] = $sKey;
					$aServices[] = $this->sGetLink($aService, $sURL, $sLinkTitle, $sLinkTarget, $sImage);
			}

				// Add bookmarks to marker array
			$this->aMarkers['###SERVICES###'] = implode(PHP_EOL, $aServices);

			return TRUE;
		}


		/**
		 * Get URL to current page
		 *
		 * @return string URL to current page
		 */
		protected function sGetPageURL() {
				// Get base URL
			$sProtocol = (!empty($this->aConfig['forceSSL']) && $this->aConfig['forceSSL'] ? 'https://' : 'http://');
			$sBaseURL = (!empty($this->aConfig['baseURL.']) ? $this->cObj->cObjGetSingle($this->aConfig['baseURL'], $this->aConfig['baseURL.']) : $this->aConfig['baseURL']);
			$sBaseURL = (!empty($sBaseURL) ? $sBaseURL : $_SERVER['HTTP_HOST']);
			$sBaseURL = $sProtocol . str_replace(array('https://', 'http://'), '', rtrim($sBaseURL, '/')) . '/';

				// Get URL to current page
			$sURL = $this->cObj->typolink('', array(
				'parameter' => (int) $GLOBALS['TSFE']->id,
				'addQueryString' => 1,
				'addQueryString.' => array(
						'exclude' => 'id,cHash,no_cache',
						'method' => 'GET',
				),
				'returnLast' => 'url',
			));

				// Get final link
			if (!empty($this->aConfig['useTinyURL'])) {
				$sServiceURL = (!empty($this->aConfig['tinyServiceURL']) ? $this->aConfig['tinyServiceURL'] : 'http://tinyurl.com/api-create.php?url=###URL###');
				if ($sTinyURL = t3lib_div::getURL(str_replace('###URL###', $sBaseURL . $sURL, $sServiceURL))) {
					return $sTinyURL;
				}
			}

			return $sBaseURL . $sURL;
		}


		/**
		 * Get page title or news title if any
		 *
		 * @return string Title
		 */
		protected function sGetTitle() {
			$sTitle = '';
			$oResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'pages', 'uid=' . (int) $GLOBALS['TSFE']->id);

			if ($oResult && $aPage = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($oResult)) {
				$oPage = clone($this->cObj);
				$oPage->start($aPage, 'pages');
				$sTitle = $oPage->cObjGetSingle($this->aConfig['pageTitle'], $this->aConfig['pageTitle.']);
				unset($oPage);
			}

				// Get title (javascript will be used to get it if empty)
			$sTitle = (!empty($this->aConfig['useTSTitle']) ? urlencode($sTitle) : '###TITLE###');

			return $sTitle;
		}


		/**
		 * Get substituted image
		 *
		 * @param array $paService Service information
		 * @return string Image
		 */
		protected function sGetImage(array $paService) {
			if (empty($paService)) {
				return '';
			}

				// Get configuration
			$sFileName = (!empty($paService['image']) ? $paService['image'] : 'EXT:sp_socialbookmarks/res/images/default.png');
			$sRelFileName = $this->sGetRelativePath($sFileName);
			$sAbsFileName = t3lib_div::getFileAbsFileName($sFileName);
			$sTitle = (!empty($paService['name']) ? $paService['name'] : '');
			$sAlt = (!empty($paService['alt']) ? $paService['alt'] : $sTitle);
			$iHeight = 16;
			$iWidth = 16;

				// Get image size
			if (@file_exists($sAbsFileName)) {
				list($iWidth, $iHeight) = @getimagesize($sAbsFileName);
			}

			$aImageMarkers = array(
				'###IMAGE_URL###' => $sRelFileName,
				'###IMAGE_HEIGHT###' => $iHeight,
				'###IMAGE_WIDTH###' => $iWidth,
				'###IMAGE_ALT###' => $sAlt,
				'###IMAGE_TITLE###' => $sTitle,
			);

			return $this->cObj->substituteMarkerArray($this->aTemplates['image'], $aImageMarkers);
		}


		/**
		 * Get all markers for a link
		 *
		 * @param array $paService Service information
		 * @param string $psURL The URL
		 * @param string $psLinkTitle Title of the link
		 * @param string $psLinkTarget Target for the link
		 * @param string $psImage An optional image
		 * @return array All link markers
		 */
		protected function sGetLink(array $paService, $psURL, $psLinkTitle, $psLinkTarget = '_blank', $psImage = '') {
			if (empty($paService) || empty($psURL)) {
				return '';
			}

				// Get serialized data
			$aData = array(
				'pid' => $GLOBALS['TSFE']->id,
				'service' => strtolower(trim($paService['key'])),
			);
			$sData = base64_encode(serialize($aData));

				// Get name
			$sName = (!empty($paService['name']) ? $paService['name'] : md5(time()));

				// Fill markers
			$aLinkMarkers = array(
				'###LINK_URL###' => str_replace(array('###URL###', '###TITLE###'), array(urlencode($psURL), $psLinkTitle), $paService['url']),
				'###LINK_TITLE###' => $paService['name'],
				'###LINK_ID###' => 'bookmark_' . $sName,
				'###LINK_TARGET###' => $psLinkTarget,
				'###LINK_JS###' => (!empty($this->aConfig['useStats']) ? "javascript:bookmark('" . $sData . "');" : ''),
				'###IMAGE###' => $psImage,
			);

			return $this->cObj->substituteMarkerArray($this->aTemplates['link'], $aLinkMarkers);;
		}


		/**
		 * Get content from template and markers
		 *
		 * @return string Whole content
		 */
		public function sGetContent() {
			return $this->cObj->substituteMarkerArray($this->aTemplates['main'], $this->aMarkers);
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php']);
	}

?>