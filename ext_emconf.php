<?php

########################################################################
# Extension Manager/Repository config file for ext "sp_socialbookmarks".
#
# Auto generated 10-01-2012 11:12
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Social Bookmarks',
	'description' => 'Allow visitors to bookmark your website. Provides a chart to analyse the clicks in backend',
	'category' => 'plugin',
	'author' => 'Kai Vogel',
	'author_email' => 'kai.vogel@speedprogs.de',
	'author_company' => 'Speedprogs.de',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '1.2.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '1.3.0-0.0.0',
			'fluid' => '1.3.0-0.0.0',
			'typo3' => '4.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:103:{s:9:"ChangeLog";s:4:"821f";s:33:"class.tx_spsocialbookmarks_db.php";s:4:"0689";s:39:"class.tx_spsocialbookmarks_services.php";s:4:"3e0e";s:12:"ext_icon.gif";s:4:"9c60";s:17:"ext_localconf.php";s:4:"bcd5";s:14:"ext_tables.php";s:4:"cf46";s:14:"ext_tables.sql";s:4:"f6bb";s:24:"ext_typoscript_setup.txt";s:4:"6d0e";s:12:"flexform.xml";s:4:"3412";s:13:"locallang.xml";s:4:"9450";s:16:"locallang_db.xml";s:4:"3920";s:14:"doc/manual.sxw";s:4:"a00e";s:48:"modfunc1/class.tx_spsocialbookmarks_modfunc1.php";s:4:"14ca";s:54:"modfunc1/class.tx_spsocialbookmarks_modfunc1_chart.php";s:4:"bd1e";s:22:"modfunc1/locallang.xml";s:4:"e6f8";s:14:"pi1/ce_wiz.gif";s:4:"4b2c";s:38:"pi1/class.tx_spsocialbookmarks_pi1.php";s:4:"f43f";s:43:"pi1/class.tx_spsocialbookmarks_pi1_ajax.php";s:4:"5743";s:47:"pi1/class.tx_spsocialbookmarks_pi1_template.php";s:4:"c094";s:46:"pi1/class.tx_spsocialbookmarks_pi1_wizicon.php";s:4:"d046";s:17:"pi1/locallang.xml";s:4:"756e";s:22:"res/images/default.png";s:4:"70c7";s:30:"res/images/browsers/chrome.png";s:4:"1418";s:31:"res/images/browsers/firefox.png";s:4:"78ac";s:32:"res/images/browsers/firefox2.png";s:4:"7b5d";s:32:"res/images/browsers/firefox3.png";s:4:"b9ea";s:32:"res/images/browsers/firefox4.png";s:4:"d5e0";s:30:"res/images/browsers/galeon.png";s:4:"e80e";s:26:"res/images/browsers/ie.png";s:4:"be2c";s:27:"res/images/browsers/ie4.png";s:4:"1737";s:27:"res/images/browsers/ie5.png";s:4:"598a";s:27:"res/images/browsers/ie6.png";s:4:"a9f9";s:27:"res/images/browsers/ie7.png";s:4:"a850";s:27:"res/images/browsers/ie8.png";s:4:"bf07";s:33:"res/images/browsers/konqueror.png";s:4:"5c41";s:28:"res/images/browsers/lynx.png";s:4:"d14f";s:31:"res/images/browsers/mozilla.png";s:4:"3885";s:28:"res/images/browsers/myie.png";s:4:"10fb";s:29:"res/images/browsers/opera.png";s:4:"7396";s:30:"res/images/browsers/safari.png";s:4:"5d8c";s:31:"res/images/services/addthis.png";s:4:"77cf";s:27:"res/images/services/ask.png";s:4:"3c28";s:32:"res/images/services/backflip.png";s:4:"3570";s:33:"res/images/services/blinkbits.png";s:4:"544a";s:33:"res/images/services/blinklist.png";s:4:"3c9b";s:33:"res/images/services/blogmarks.png";s:4:"4593";s:31:"res/images/services/bluedot.png";s:4:"e71c";s:32:"res/images/services/connotea.png";s:4:"d19c";s:33:"res/images/services/delicious.png";s:4:"07a9";s:33:"res/images/services/delirious.png";s:4:"d5f3";s:28:"res/images/services/digg.png";s:4:"5d3f";s:32:"res/images/services/facebook.png";s:4:"4881";s:28:"res/images/services/fark.png";s:4:"fd3d";s:35:"res/images/services/feedmelinks.png";s:4:"2eab";s:29:"res/images/services/folkd.png";s:4:"0a1a";s:28:"res/images/services/furl.png";s:4:"7056";s:30:"res/images/services/google.png";s:4:"523c";s:28:"res/images/services/hype.png";s:4:"9236";s:33:"res/images/services/linkagogo.png";s:4:"d7c8";s:33:"res/images/services/linkarena.png";s:4:"596e";s:28:"res/images/services/live.png";s:4:"90e7";s:32:"res/images/services/magnolia.png";s:4:"eda8";s:32:"res/images/services/mylinkde.png";s:4:"de0d";s:32:"res/images/services/netscape.png";s:4:"f977";s:31:"res/images/services/netvouz.png";s:4:"84bb";s:32:"res/images/services/newsvine.png";s:4:"f2a8";s:31:"res/images/services/oneview.png";s:4:"d013";s:32:"res/images/services/rawsugar.png";s:4:"47bb";s:30:"res/images/services/reddit.png";s:4:"fddf";s:31:"res/images/services/scuttle.png";s:4:"000a";s:29:"res/images/services/simpy.png";s:4:"e389";s:32:"res/images/services/smarking.png";s:4:"7614";s:29:"res/images/services/spurl.png";s:4:"d5db";s:26:"res/images/services/su.png";s:4:"e3d0";s:31:"res/images/services/tagthat.png";s:4:"00ab";s:32:"res/images/services/tailrank.png";s:4:"0084";s:34:"res/images/services/technorati.png";s:4:"c943";s:31:"res/images/services/twitter.png";s:4:"a10b";s:31:"res/images/services/webnews.png";s:4:"1e31";s:28:"res/images/services/wink.png";s:4:"d753";s:29:"res/images/services/wists.png";s:4:"db99";s:28:"res/images/services/wong.png";s:4:"9ff2";s:34:"res/images/services/yahoomyweb.png";s:4:"4bad";s:30:"res/images/services/yiggit.png";s:4:"fd6f";s:28:"res/images/systems/linux.png";s:4:"db40";s:28:"res/images/systems/macos.png";s:4:"5020";s:28:"res/images/systems/sunos.png";s:4:"a8cb";s:29:"res/images/systems/ubuntu.png";s:4:"aeaf";s:31:"res/images/systems/ubuntu10.png";s:4:"fb9d";s:30:"res/images/systems/ubuntu6.png";s:4:"11da";s:30:"res/images/systems/ubuntu7.png";s:4:"0753";s:30:"res/images/systems/ubuntu8.png";s:4:"563b";s:30:"res/images/systems/ubuntu9.png";s:4:"7260";s:30:"res/images/systems/windows.png";s:4:"c598";s:31:"res/images/systems/windows5.png";s:4:"2a42";s:31:"res/images/systems/windows6.png";s:4:"7797";s:31:"res/images/systems/windows7.png";s:4:"3716";s:32:"res/images/systems/windows98.png";s:4:"f489";s:32:"res/images/systems/windowsnt.png";s:4:"9b49";s:35:"res/images/systems/windowsvista.png";s:4:"c4cf";s:25:"res/js/socialbookmarks.js";s:4:"4d4d";s:27:"res/template/stylesheet.css";s:4:"d41d";s:26:"res/template/template.html";s:4:"6329";}',
);

?>