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
?>