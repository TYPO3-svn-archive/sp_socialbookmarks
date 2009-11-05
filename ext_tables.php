<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

	// Get default images
	require_once(t3lib_extMgm::extPath($_EXTKEY).'class.tx_spsocialbookmarks_services.php');

	// Remove some fields from default tt_content element
	t3lib_div::loadTCA('tt_content');
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout, select_key, pages, recursive';

	// Add flexform field to plugin options
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';

	// Add flexform Data-Structure
	t3lib_extMgm::addPiFlexFormValue(
		$_EXTKEY.'_pi1',
		'FILE:EXT:' . $_EXTKEY . '/flexform.xml'
	);

	// Add plugin
	t3lib_extMgm::addPlugin(
		array(
			'LLL:EXT:sp_socialbookmarks/locallang_db.xml:tt_content.list_type_pi1',
			$_EXTKEY.'_pi1',
			t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
		),
		'list_type'
	);

	if (TYPO3_MODE == 'BE') {
		// Wizard icon
		$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_spsocialbookmarks_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_spsocialbookmarks_pi1_wizicon.php';

		// Add backend module to web->info
		t3lib_extMgm::insertModuleFunction(
			'web_info',
			'tx_spsocialbookmarks_modfunc1',
			t3lib_extMgm::extPath($_EXTKEY).'modfunc1/class.tx_spsocialbookmarks_modfunc1.php',
			'LLL:EXT:sp_socialbookmarks/locallang_db.xml:moduleFunction.tx_spsocialbookmarks_modfunc1'
		);
	}
?>