<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

		// Load service class for itemsProcFunc in FlexForm
	require_once(t3lib_extMgm::extPath($_EXTKEY) . 'class.tx_spsocialbookmarks_services.php');

	$identifier = $_EXTKEY . '_pi1';

		// Remove some fields from default tt_content element
	t3lib_div::loadTCA('tt_content');
	$TCA['tt_content']['types']['list']['subtypes_excludelist'][$identifier] = 'layout, select_key, pages, recursive';

		// Add FlexForm field to plugin options
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$identifier] = 'pi_flexform';

		// Add static TypoScript files
	t3lib_extMgm::addStaticFile($_EXTKEY, 'res/ts/', 'Social Bookmarks Configuration');

		// Add FlexForm data structure
	t3lib_extMgm::addPiFlexFormValue($identifier, 'FILE:EXT:' . $_EXTKEY . '/flexform.xml');

		// Add plugin
	t3lib_extMgm::addPlugin(array(
			'LLL:EXT:' . $_EXTKEY . '/pi1/locallang.xml:plugin.title',
			$identifier,
			t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
		), 'list_type'
	);

		// Add backend module to web->info
	if (TYPO3_MODE == 'BE') {
		t3lib_extMgm::insertModuleFunction(
			'web_info',
			'tx_spsocialbookmarks_modfunc1',
			t3lib_extMgm::extPath($_EXTKEY) . 'modfunc1/class.tx_spsocialbookmarks_modfunc1.php',
			'LLL:EXT:' . $_EXTKEY . '/modfunc1/locallang.xml:module.title'
		);
	}

		// Add plugin to new content element wizard
	t3lib_extMgm::addPageTSConfig("
		mod.wizards.newContentElement.wizardItems.special {\n
			elements." . $identifier . " {\n
				icon        = " . t3lib_extMgm::extRelPath($_EXTKEY) . "res/images/wizard.gif\n
				title       = LLL:EXT:" . $_EXTKEY . "/pi1/locallang.xml:plugin.title\n
				description = LLL:EXT:" . $_EXTKEY . "/pi1/locallang.xml:plugin.description\n\n
				tt_content_defValues {\n
					CType = list\n
					list_type = " . $identifier . "\n
				}\n
			}\n\n
			show := addToList(" . $identifier . ")\n
		}
	");
?>