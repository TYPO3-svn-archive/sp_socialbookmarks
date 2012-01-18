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
	 * Visits
	 */
	class Tx_SpSocialbookmarks_Domain_Model_Visit extends Tx_Extbase_DomainObject_AbstractEntity {

		/**
		 * IP address of the client
		 *
		 * @var string
		 */
		protected $ip;

		/**
		 * User agent
		 *
		 * @var string
		 */
		protected $agent;

		/**
		 * Service name
		 *
		 * @var string
		 */
		protected $service;


		/**
		 * @param string $ip
		 * @return void
		 */
		public function setIp($ip) {
			$this->ip = $ip;
		}


		/**
		 * @return string
		 */
		public function getIp() {
			return $this->ip;
		}


		/**
		 * @param string $agent
		 * @return void
		 */
		public function setAgent($agent) {
			$this->agent = $agent;
		}


		/**
		 * @return string
		 */
		public function getAgent() {
			return $this->agent;
		}


		/**
		 * @param string $service
		 * @return void
		 */
		public function setService($service) {
			$this->service = $service;
		}


		/**
		 * @return string
		 */
		public function getService() {
			return $this->service;
		}

	}
?>