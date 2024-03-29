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
	 * View helper for sprite icons
	 */
	class Tx_SpSocialbookmarks_ViewHelpers_SpriteIconViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

		/**
		 * Renders a sprite icon
		 *
		 * @param string $name The name of the sprite icon
		 * @param string $prefix An optional prefix
		 * @return string The rendered icon
		 */
		public function render($name = NULL, $prefix = 'extensions-') {
			if ($name === NULL) {
				$name = $this->renderChildren();
			}

			if (empty($name)) {
				throw new Exception('Given name is not valid');
			}

			return t3lib_iconWorks::getSpriteIcon($prefix . $name);
		}

	}
?>