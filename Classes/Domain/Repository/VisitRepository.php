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
	 * Repository for Tx_SpSocialbookmarks_Domain_Model_Visit
	 */
	class Tx_SpSocialbookmarks_Domain_Repository_VisitRepository extends Tx_Extbase_Persistence_Repository {

		/**
		 * Return visits by pid and crdate
		 *
		 * @param integer $pid Current page id
		 * @param integer $timestamp Earliest timestamp
		 * @return
		 */
		public function getByPidAndCrdate($pid = 0, $timestamp = 0) {
			$query = $this->createQuery();

				// Disable default storage page handling
			$query->getQuerySettings()->setRespectStoragePage(FALSE);

				// Set pid and timestamp
			if (!empty($pid) && !empty($timestamp)) {
				$query->matching($query->logicalAnd(
					$query->equals('pid', $pid),
					$query->greaterThanOrEqual('crdate', $timestamp)
				));
			} else if (!empty($pid)) {
				$query->matching($query->equals('pid', $pid));
			} else if (!empty($timestamp)) {
				$query->matching($query->greaterThanOrEqual('crdate', $timestamp));
			}

			return $query->execute();
		}


	}
?>