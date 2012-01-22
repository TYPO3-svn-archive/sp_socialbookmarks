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
	 * View helper for action menu items
	 */
	class Tx_SpSocialbookmarks_ViewHelpers_Be_ActionMenuItemViewHelper extends Tx_Fluid_ViewHelpers_Be_Menus_ActionMenuItemViewHelper {

		/**
		 * Renders an ActionMenu option tag
		 *
		 * @param string $label Label of the option tag
		 * @param string $controller Controller to be associated with this ActionMenuItem
		 * @param string $action The action to be associated with this ActionMenuItem
		 * @param array $arguments Additional controller arguments to be passed to the action when this ActionMenuItem is selected
		 * @param string $parameter Optional paramter name to check in request
		 * @return string The rendered option tag
		 */
		public function render($label, $controller, $action, array $arguments = array(), $parameter = '') {
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$uri = $uriBuilder
				->reset()
				->uriFor($action, $arguments, $controller);
			$this->tag->addAttribute('value', $uri);

			$currentRequest = $this->controllerContext->getRequest();
			$currentController = $currentRequest->getControllerName();
			$currentAction = $currentRequest->getControllerActionName();

			$currentParameter = '';
			if ($currentRequest->hasArgument($parameter)) {
				$currentParameter = $currentRequest->getArgument($parameter);
			}
			$parameter = (!empty($arguments[$parameter]) ? $arguments[$parameter] : '');

			if ($action === $currentAction && $controller === $currentController) {
				if (!empty($currentParameter) && $currentParameter === $parameter) {
					$this->tag->addAttribute('selected', TRUE);
				}
			}

			$this->tag->setContent($label);

			return $this->tag->render();
		}
	}
?>