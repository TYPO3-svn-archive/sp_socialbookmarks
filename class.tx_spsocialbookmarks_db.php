<?php
	/***************************************************************
	*  Copyright notice
	*
	*  (c) 2009 Kai Vogel  <kai.vogel(at)speedprogs.de>
	*  All rights reserved
	*
	*  This script is part of the TYPO3 project. The TYPO3 project is
	*  free software; you can redistribute it and/or modify
	*  it under the terms of the GNU General Public License as published by
	*  the Free Software Foundation; either version 2 of the License, or
	*  (at your option) any later version.
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
	***************************************************************/


	/**
	 * Session class for the 'sp_socialbookmarks' extension.
	 *
	 * @author		Kai Vogel  <kai.vogel(at)speedprogs.de>
	 * @package		TYPO3
	 * @subpackage	tx_spsocialbookmarks
	 */
	class tx_spsocialbookmarks_db {
		public $sTable = 'tx_spsocialbookmarks';


		/**
		 * Get data for all services form db ( Backend )
		 *
		 */
		public function aGetData($piPID=0, $psPeriod='') {
			$aServices = array();

			// Get WHERE clause
			$sWhere  = $piPID ? 'pid='.intval($piPID) : '1=1';
			$sWhere .= strlen($psPeriod) ? ' AND tstamp > '.$psPeriod : '';

			// Get services from table
			if ($oResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $this->sTable, $sWhere)) {
				while ($aService = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($oResult)) {
					$sName = strtolower($aService['name']);

					if (isset($aServices[$sName])) {
						$aServices[$sName][] = $aService;
					} else {
						$aServices[$sName] = array();
						$aServices[$sName][] = $aService;
					}
				}
			}

			return $aServices;
		}

		/**
		 * Add a click to stats-table ( AJAX )
		 *
		 */
		public function vAddClick($piPID, $psService) {
			if (!$piPID || !$psService) {
				return;
			}

			// Insert into Table
			@$GLOBALS['TYPO3_DB']->exec_INSERTquery ($this->sTable, array(
				'pid'		=> (int) $piPID,
				'name'		=> $GLOBALS['TYPO3_DB']->quoteStr(strtolower(trim($psService)), $this->sTable),
				'tstamp'	=> time(),
				'crdate'	=> time(),
				'ip'		=> $GLOBALS['TYPO3_DB']->quoteStr(strip_tags($_SERVER['REMOTE_ADDR']), $this->sTable),
				'agent'		=> $GLOBALS['TYPO3_DB']->quoteStr(strip_tags(t3lib_div::getIndpEnv('HTTP_USER_AGENT')), $this->sTable),
			));

			return;
		}

	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php'])	{
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php']);
	}

?>