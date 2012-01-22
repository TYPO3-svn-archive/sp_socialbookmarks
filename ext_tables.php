<?php
	if (!defined ('TYPO3_MODE')) {
		die ('Access denied.');
	}

		// Add plugin to list
	Tx_Extbase_Utility_Extension::registerPlugin(
		$_EXTKEY,
		'Bookmarks',
		'Social Bookmarks'
	);

		// Add flexform field
	$identifier = str_replace('_', '', $_EXTKEY) . '_bookmarks';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$identifier] = 'layout,select_key,recursive,pages';
	$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$identifier] = 'pi_flexform';
	t3lib_extMgm::addPiFlexFormValue($identifier, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Bookmarks.xml');

		// Add static TypoScript files
	t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Social Bookmarks Configuration');

		// Add help text to the backend form
	t3lib_extMgm::addLLrefForTCAdescr('tx_spsocialbookmarks_domain_model_visit', 'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_visit.xml');

		// Allow datasets on standard pages
	t3lib_extMgm::allowTableOnStandardPages('tx_spsocialbookmarks_domain_model_visit');

		// Add table configuration
	$TCA['tx_spsocialbookmarks_domain_model_visit'] = array(
		'ctrl' => array(
			'title'                    => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_spsocialbookmarks_domain_model_visit',
			'label'                    => 'name',
			'tstamp'                   => 'tstamp',
			'crdate'                   => 'crdate',
			'cruser_id'                => 'cruser_id',
			'dividers2tabs'            => TRUE,
			'versioningWS'             => 2,
			'versioning_followPages'   => TRUE,
			'origUid'                  => 't3_origuid',
			'languageField'            => 'sys_language_uid',
			'transOrigPointerField'    => 'l10n_parent',
			'transOrigDiffSourceField' => 'l10n_diffsource',
			'delete'                   => 'deleted',
			'enablecolumns'            => array(
				'disabled'                 => 'hidden',
				'starttime'                => 'starttime',
				'endtime'                  => 'endtime',
			),
			'dynamicConfigFile'        => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Visit.php',
			'iconfile'                 => t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
		),
	);

		// Add plugin to new content element wizard
	t3lib_extMgm::addPageTSConfig("
		mod.wizards.newContentElement.wizardItems.special {\n
			elements." . $identifier . " {\n
				icon        = " . t3lib_extMgm::extRelPath($_EXTKEY) . "Resources/Public/Images/Wizard.gif\n
				title       = LLL:EXT:" . $_EXTKEY . "/Resources/Private/Language/locallang.xml:plugin.title\n
				description = LLL:EXT:" . $_EXTKEY . "/Resources/Private/Language/locallang.xml:plugin.description\n\n
				tt_content_defValues {\n
					CType = list\n
					list_type = " . $identifier . "\n
				}\n
			}\n\n
			show := addToList(" . $identifier . ")\n
		}
	");

		// Add charts module to backend
	if (TYPO3_MODE == 'BE') {
		Tx_Extbase_Utility_Extension::registerModule(
			$_EXTKEY,
			'web',
			$identifier,
			'',
			array(
				'Chart' => 'show',
			),
			array(
				'access' => 'user,group',
				'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
				'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml',
				'navigationComponentId' => 'typo3-pagetree',
			)
		);
	}

?>