<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

		// Make plugin available in frontend
	Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Bookmarks',
		array(
			'Bookmarks' => 'show, click',
		),
		array(
			'Bookmarks' => 'click',
		)
	);

		// Add save-and-new button
	t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_spsocialbookmarks_bookmarks = 1');

		// Define renderers for the charts
	if (empty($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'])) {
		$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers'] = array();
	}
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['bar'] = 'Tx_SpSocialbookmarks_Chart_BarChart';
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$_EXTKEY]['chartRenderers']['pie'] = 'Tx_SpSocialbookmarks_Chart_PieChart';

?>