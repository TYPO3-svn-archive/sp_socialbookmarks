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
	 * Database handler
	 */
	class tx_spsocialbookmarks_db implements t3lib_Singleton {
		public $table = 'tx_spsocialbookmarks';

		/**
		 * Get data for all services form db
		 *
		 * @param integer $pid The PID
		 * @param integer $period Time period
		 * @return array Services
		 */
		public function getData($pid = 0, $period = 0) {
				// Get WHERE clause
			$where  = ($pid ? 'pid=' . (int) $pid : '1=1');
			$where .= (!empty($period) ? ' AND tstamp > ' . (int) $period : '');

				// Get services from db
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', $this->table, $where);
			if (empty($result)) {
				return array();
			}

				// Build services
			$services = array();
			foreach ($result as $service) {
				$name = strtolower($service['name']);
				if (!empty($services[$name])) {
					$services[$name][] = $service;
				} else {
					$services[$name] = array($service);
				}
			}

			return $services;
		}

		/**
		 * Add a click to stats table
		 *
		 * @param integer $pid The PID
		 * @param string $service Service name
		 * @return void
		 */
		public function addClick($pid, $service) {
			if (empty($pid) || empty($service)) {
				return;
			}

				// Insert into table
			$GLOBALS['TYPO3_DB']->exec_INSERTquery($this->table, array(
				'pid'    => (int) $pid,
				'name'   => $GLOBALS['TYPO3_DB']->quoteStr(strtolower(trim($service)), $this->table),
				'tstamp' => (int) $GLOBALS['EXEC_TIME'],
				'crdate' => (int) $GLOBALS['EXEC_TIME'],
				'ip'     => $GLOBALS['TYPO3_DB']->quoteStr(strip_tags($_SERVER['REMOTE_ADDR']), $this->table),
				'agent'  => $GLOBALS['TYPO3_DB']->quoteStr(strip_tags(t3lib_div::getIndpEnv('HTTP_USER_AGENT')), $this->table),
			));
		}

	}


	if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php']) {
		include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/sp_socialbookmarks/class.tx_spsocialbookmarks_db.php']);
	}

?>