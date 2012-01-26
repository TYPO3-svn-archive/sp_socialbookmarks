<?php
	$extensionClassesPath = t3lib_extMgm::extPath('sp_socialbookmarks', 'Classes/');

	return array(
		'tx_spsocialbookmarks_controller_abstractcontroller'           => $extensionClassesPath . 'Controller/AbstractController.php',
		'tx_spsocialbookmarks_controller_chartcontroller'              => $extensionClassesPath . 'Controller/ChartController.php',
		'tx_spsocialbookmarks_controller_bookmarkscontroller'          => $extensionClassesPath . 'Controller/BookmarksController.php',
		'tx_spsocialbookmarks_domain_model_service'                    => $extensionClassesPath . 'Domain/Model/Service.php',
		'tx_spsocialbookmarks_domain_model_visit'                      => $extensionClassesPath . 'Domain/Model/Visit.php',
		'tx_spsocialbookmarks_domain_repository_servicerepository'     => $extensionClassesPath . 'Domain/Repository/ServiceRepository.php',
		'tx_spsocialbookmarks_domain_repository_visitrepository'       => $extensionClassesPath . 'Domain/Repository/VisitRepository.php',
		'tx_spsocialbookmarks_object_objectbuilder'                    => $extensionClassesPath . 'Object/ObjectBuilder.php',
		'tx_spsocialbookmarks_service_service'                         => $extensionClassesPath . 'Service/Service.php',
		'tx_spsocialbookmarks_utility_backend'                         => $extensionClassesPath . 'Utility/Backend.php',
		'tx_spsocialbookmarks_utility_persistence'                     => $extensionClassesPath . 'Utility/Persistence.php',
		'tx_spsocialbookmarks_utility_typoscript'                      => $extensionClassesPath . 'Utility/TypoScript.php',
		'tx_spsocialbookmarks_viewhelpers_be_actionmenuitemviewhelper' => $extensionClassesPath . 'ViewHelpers/Be/ActionMenuItemViewHelper.php',
		'tx_spsocialbookmarks_viewhelpers_chartviewhelper'             => $extensionClassesPath . 'ViewHelpers/ChartViewHelper.php',
	);
?>