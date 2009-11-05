<?php
	/***************************************************************
	*	Copyright notice
	*
	*	(c) 2009 Kai Vogel  <kai.vogel(at)speedprogs.de>
	*	All rights reserved
	*
	*	This script is part of the TYPO3 project. The TYPO3 project is
	*	free software; you can redistribute it and/or modify
	*	it under the terms of the GNU General Public License as published by
	*	the Free Software Foundation; either version 2 of the License, or
	*	(at your option) any later version.
	*
	*	The GNU General Public License can be found at
	*	http://www.gnu.org/copyleft/gpl.html.
	*
	*	This script is distributed in the hope that it will be useful,
	*	but WITHOUT ANY WARRANTY; without even the implied warranty of
	*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
	*	GNU General Public License for more details.
	*
	*	This copyright notice MUST APPEAR in all copies of the script!
	***************************************************************/


	/**
	 * Template class for the 'sp_socialbookmarks' extension.
	 *
	 * @author		Kai Vogel <kai.vogel(at)speedprogs.de>
	 * @package		TYPO3
	 * @subpackage	tx_spsocialbookmarks
	 */
	class tx_spsocialbookmarks_pi1_template {
		protected $cObj			= NULL;
		protected $aLL			= array();
		protected $aConfig		= array();
		protected $aMarkers		= array();
		protected $aTemplates	= array();
		protected $extKey		= '';
		protected $sLLkey		= 'en';


		/**
		 * Set configuration for template object
		 *
		 */
		public function vConstruct($poParent) {
			$this->cObj 	= $poParent->cObj;
			$this->aLL		= $poParent->aLL;
			$this->aConfig	= $poParent->aConfig;
			$this->extKey	= $poParent->extKey;
			$this->sLLKey	= $poParent->LLkey;

			$sRessource = $this->cObj->fileResource($this->aConfig['templateFile']);
			$this->aTemplates = array(
				'main'	=> $this->cObj->getSubpart($sRessource, '###TEMPLATE###'),
				'image'	=> $this->cObj->getSubpart($sRessource, '###IMAGE###'),
				'link'	=> $this->cObj->getSubpart($sRessource, '###SERVICE###'),
			);
		}


		/**
		 * Predefine default markers
		 *
		 */
		public function vAddDefaultMarkers () {
			// Get current url
			$sLink = $this->cObj->typoLink('', array(
				'parameter' => $GLOBALS['TSFE']->id,
				'returnLast' => 'url',
			));

			// User defined markers
			if (count($this->aConfig['markers.'])) {
				$sName = '';
				$sType = '';

				foreach ($this->aConfig['markers.'] as $sKey => $mValue) {
					if (substr($sKey, -1) !== '.' && is_string($mValue)) {
						$sName = $sKey;
						$sType = $mValue;
					} else if ($sName !== '' && $sType !== '') {
						$this->aMarkers['###'.strtoupper($sName).'###'] = $this->cObj->cObjGetSingle($sType, $mValue, $sKey);
						$sName = '';
						$sType = '';
					}
				}
			}

			// Locallang markers
			foreach ($this->aLL as $sKey => $sValue) {
				$this->aMarkers['###LLL:'.$sKey.'###'] = $sValue;
			}
		}


		/**
		 * Add stylesheet or JavaScript to HTML head
		 *
		 * @return True if file could be pushed into header
		 */
		public function bAddFileToHeader ($psFileName, $psType='css') {
			if (!$psFileName || !$psFileName = $this->sGetRelativePath($psFileName)) {
				return false;
			}

			if (strtolower($psType) == 'css') {
				$GLOBALS['TSFE']->additionalHeaderData[md5(microtime().$psFileName)] = '<link rel="stylesheet" href="'.$psFileName.'" type="text/css" />';
			} elseif (strtolower($psType) == 'js') {
				$GLOBALS['TSFE']->additionalHeaderData[md5(microtime().$psFileName)] = '<script src="'.$psFileName.'" type="text/javascript"></script>';
			}

			return true;
		}


		/**
		 * Check for extension relative path
		 *
		 * @return String with relative file path
		 */
		protected function sGetRelativePath ($psFileName) {
			if ($psFileName && substr($psFileName, 0, 4) == 'EXT:') {
				list($sExtKey, $sFilePath) = explode('/', substr($psFileName, 4), 2);
				$sExtKey = strtolower($sExtKey);

				if ($sExtKey == $this->extKey || t3lib_extMgm::isLoaded($sExtKey)) {
					$psFileName = t3lib_extMgm::siteRelPath($sExtKey).$sFilePath;
				}
			}

			return $psFileName;
		}


		/**
		 * Add bookmark services to marker array
		 *
		 */
		public function vAddServices ($paServices) {
			if (!is_array($paServices) || !count($paServices)) {
				return false;
			}

			// Get configuration
			$sBaseURL		= $this->aConfig['baseURL']		? $this->aConfig['baseURL'] 	: $_SERVER['HTTP_HOST'];
			$sBaseURL		= (substr($sBaseURL,-1) == '/')	? $sBaseURL				 		: $sBaseURL.'/';
			$sLinkTitle		= $this->sGetTitle();
			$sLinkTarget	= $this->aConfig['linkTarget'] 	? $this->aConfig['linkTarget']	: '_blank';
			$aServices		= array();

			// Get all links from configuration
			foreach ($paServices as $sKey => $aService) {
					$sImage	= $this->sGetImage($aService);
					$aService['key'] = $sKey;
					$aServices[] = $this->sGetLink($aService, $sBaseURL, $sLinkTitle, $sLinkTarget, $sImage);
			}

			// Add bookmarks to marker array
			$this->aMarkers['###SERVICES###'] = implode(PHP_EOL, $aServices);

			return true;
		}


		/**
		 * Get page title or news title if any
		 *
		 * @return String with Title
		 */
		protected function sGetTitle () {
			$sTitle		= '';
			$oResult	= $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'pages', 'uid='.$GLOBALS['TSFE']->id);

			if ($oResult && $aPage = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($oResult)) {
				$oPage = clone $this->cObj;
				$oPage->start($aPage, 'pages');
				$sTitle = $oPage->cObjGetSingle($this->aConfig['pageTitle'], $this->aConfig['pageTitle.']);
				unset($oPage);
			}

			$sTitle = $this->aConfig['useTSTitle'] ? str_replace(array("'",'"',' '), '+', $sTitle) : '';

			return $sTitle;
		}


		/**
		 * Get all markers for an image
		 *
		 * @return Array with all image markers
		 */
		protected function sGetImage($paService) {
			if (!is_array($paService) || !count($paService)) {
				return '';
			}

			// Get configuration
			$sFileName		= $paService['image']	? $paService['image']	: 'EXT:sp_socialbookmarks/res/images/default.png';
			$sRelFileName	= $this->sGetRelativePath($sFileName);
			$sAbsFileName	= t3lib_div::getFileAbsFileName($sFileName);
			$sTitle			= $paService['name']	? $paService['name']	: '';
			$sAlt			= $paService['alt']		? $paService['alt']		: $sTitle;
			$iHeight		= 16;
			$iWidth			= 16;

			// Get image size
			if (@file_exists($sAbsFileName)) {
				list($iWidth,$iHeight) = @getimagesize($sAbsFileName);
			}

			$aImageMarkers = array(
				'###IMAGE_URL###'		=> $sRelFileName,
				'###IMAGE_HEIGHT###'	=> $iHeight,
				'###IMAGE_WIDTH###'		=> $iWidth,
				'###IMAGE_ALT###'		=> $sAlt,
				'###IMAGE_TITLE###'		=> $sTitle,
			);

			return $this->cObj->substituteMarkerArray($this->aTemplates['image'], $aImageMarkers);
		}


		/**
		 * Get all markers for a link
		 *
		 * @return Array with all link markers
		 */
		protected function sGetLink($paService, $psBaseURL, $psLinkTitle, $psLinkTarget='_blank', $psImage='') {
			if (!is_array($paService) || !count($paService) || !$psBaseURL) {
				return '';
			}

			// Get serialized data
			$aData = array(
				'id'		=> $this->cObj->data['uid'],
				'lang'		=> $this->sLLKey,
				'service'	=> strtolower(trim($paService['key'])),
			);
			$sData = base64_encode(serialize($aData));

			// Get url
			$sURL = rawurlencode($psBaseURL.$this->cObj->typolink('', array(
					'parameter'			=> $GLOBALS['TSFE']->id,
					'addQueryString'	=> 1,
					'addQueryString.'	=> array(
							'exclude'	=> 'id,cHash,no_cache',
							'method'	=> 'GET',
					),
					'returnLast'		=> 'url',
			)));

			// Get name
			$sName = $paService['name'] ? $paService['name'] : md5(time());

			// Get title (javascript will be used to get it if empty)
			$psLinkTitle = strlen($psLinkTitle) ? $psLinkTitle : '###TITLE###';

			// Fill markers
			$aLinkMarkers = array(
				'###LINK_URL###' 	=> str_replace(array('###URL###', '###TITLE###'), array($sURL, $psLinkTitle), $paService['url']),
				'###LINK_TITLE###' 	=> $paService['name'],
				'###LINK_ID###'		=> 'bookmark_'.$sName,
				'###LINK_TARGET###'	=> $psLinkTarget,
				'###LINK_JS###'		=> "javascript:bookmark('".$sData."');",
				'###IMAGE###'		=> $psImage,
			);

			return $this->cObj->substituteMarkerArray($this->aTemplates['link'], $aLinkMarkers);;
		}


		/**
		 * Get content from template and markers
		 *
		 * @return	Whole content
		 */
		public function sGetContent () {
			return $this->cObj->substituteMarkerArray($this->aTemplates['main'], $this->aMarkers);
		}

	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/pi1/class.tx_spsocialbookmarks_pi1_template.php']);
	}

?>