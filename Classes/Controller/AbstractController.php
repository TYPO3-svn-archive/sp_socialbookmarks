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
	 * Abstract controller
	 */
	class Tx_SpSocialbookmarks_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {

		/**
		 * Translate a label
		 *
		 * @param string $label Label to translate
		 * @param array $arguments Optional arguments array
		 * @return string Translated label
		 */
		protected function translate($label, array $arguments = NULL) {
			$extensionKey = $this->request->getControllerExtensionKey();
			return Tx_Extbase_Utility_Localization::translate($label, $extensionKey, $arguments);
		}


		/**
		 * Add message to flash messages
		 *
		 * @param string $message Identifier of the message
		 * @param array $arguments Optional array of arguments
		 * @param string $severity optional severity code
		 * @return void
		 */
		protected function addMessage($message, array $arguments = NULL, $severity = 'error') {
			$constant = 't3lib_FlashMessage::' . strtoupper(trim($severity));
			if (!empty($severity) && defined($constant)) {
				$severity = constant($constant);
			} else {
				$severity = t3lib_FlashMessage::ERROR;
			}
			$this->flashMessageContainer->add($this->translate($message, $arguments), '', $severity);
		}


		/**
		 * Send flash message and redirect to given action
		 *
		 * @param string $message Identifier of the message to send
		 * @param string $action Name of the action
		 * @param string $controller Optional name of the controller
		 * @param array $arguments Optional array of arguments
		 * @param integer $pageUid Optional UID of the page to redirect to
		 * @param string $severity optional severity code
		 * @return void
		 */
		protected function redirectWithMessage($message, $action, $controller = NULL, array $arguments = NULL, $pageUid = NULL, $severity = 'error') {
			$this->addMessage($message, NULL, $severity);
			$this->redirect($action, $controller, NULL, $arguments, $pageUid);
		}


		/**
		 * Send flash message and forward to given action
		 *
		 * @param string $message Identifier of the message to send
		 * @param string $action Name of the action
		 * @param string $controller Optional name of the controller
		 * @param array $arguments Optional array of arguments
		 * @param string $severity optional severity code
		 * @return void
		 */
		protected function forwardWithMessage($message, $action, $controller = NULL, array $arguments = NULL, $severity = 'error') {
			$this->addMessage($message, NULL, $severity);
			$this->forward($action, $controller, NULL, $arguments, $pageUid);
		}


		/**
		 * Redirect to internal page
		 *
		 * @param integer $uid UID of the page
		 * @param array $arguments Arguments to pass to the target page
		 * @return void
		 */
		protected function redirectToPage($uid, array $arguments = array()) {
			$this->uriBuilder->reset();
			$this->uriBuilder->setTargetPageUid((int) $uid);
			$uri = $this->uriBuilder->uriFor(NULL, $arguments);
			$this->redirectToUri($uri);
		}

	}
?>