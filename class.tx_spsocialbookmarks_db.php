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
	 * Database handler
	 */
	class tx_spsocialbookmarks_db {
		public $sTable = 'tx_spsocialbookmarks';

		/**
		 * Get data for all services form db
		 *
		 * @param integer $piPID The PID
		 * @param integer $piPeriod Time period
		 * @return array Services
		 */
		public function aGetData($piPID = 0, $piPeriod = 0) {
			$aServices = array();

				// Get WHERE clause
			$sWhere  = ($piPID ? 'pid=' . intval($piPID) : '1=1');
			$sWhere .= (!empty($piPeriod) ? ' AND tstamp > ' . intval($piPeriod) : '');

				// Get services from table
			if ($oResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $this->sTable, $sWhere)) {
				while ($aService = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($oResult)) {
					$sName = strtolower($aService['name']);

					if (!empty($aServices[$sName])) {
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
		 * Add a click to stats table
		 *
		 * @param integer $piPID The PID
		 * @param string $psService Service name
		 * @return void
		 */
		public function vAddClick($piPID, $psService) {
			if (empty($piPID) || empty($psService)) {
				return;
			}

				// Insert into table
			$GLOBALS['TYPO3_DB']->exec_INSERTquery($this->sTable, array(
				'pid' => (int) $piPID,
				'name' => $GLOBALS['TYPO3_DB']->quoteStr(strtolower(trim($psService)), $this->sTable),
				'tstamp' => (int) $GLOBALS['EXEC_TIME'],
				'crdate' => (int) $GLOBALS['EXEC_TIME'],
				'ip' => $GLOBALS['TYPO3_DB']->quoteStr(strip_tags($_SERVER['REMOTE_ADDR']), $this->sTable),
				'agent' => $GLOBALS['TYPO3_DB']->quoteStr(strip_tags(t3lib_div::getIndpEnv('HTTP_USER_AGENT')), $this->sTable),
			));

			return;
		}

	}


	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php']) {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php']);
	}

?>