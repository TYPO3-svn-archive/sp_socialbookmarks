<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

	// Add save-and-new button
	t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_spsocialbookmarks_bookmarks=1');

	// Add plugin to TS
	t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_spsocialbookmarks_pi1.php', '_pi1', 'list_type', 1);

	// Add eID function
	global $TYPO3_CONF_VARS;
	$TYPO3_CONF_VARS['FE']['eID_include']['tx_spsocialbookmarks_pi1'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_spsocialbookmarks_pi1_ajax.php';

	/*
	t3lib_extMgm::addTypoScript(
		$_EXTKEY,
		'setup',
		'tt_content.shortcut.20.0.conf.tx_spsocialbookmarks_bookmarks = < plugin.'.t3lib_extMgm::getCN($_EXTKEY).'_pi1
		tt_content.shortcut.20.0.conf.tx_spsocialbookmarks_bookmarks.CMD = singleView',
		43
	);
	*/
?>