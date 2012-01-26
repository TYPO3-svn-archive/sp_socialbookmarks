<?php
	$extensionImagePath = t3lib_extMgm::extRelPath('sp_socialbookmarks') . 'Resources/Public/Images/';

	return array(
		'browser' => $extensionImagePath . 'Backend/Browser.gif',
		'service' => $extensionImagePath . 'Backend/Service.gif',
		'system'  => $extensionImagePath . 'Backend/System.gif',
		'bar'     => $extensionImagePath . 'Chart/Bar.gif',
		'line'    => $extensionImagePath . 'Chart/Line.gif',
		'pie'     => $extensionImagePath . 'Chart/Pie.gif',
	);
?>