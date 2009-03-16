<?php

########################################################################
# Extension Manager/Repository config file for ext: "pt_tools"
#
# Auto generated 18-12-2008 10:41
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'punkt.de Tools',
	'description' => 'PHP5 toolbox and class library for extension development using PHP 5.1+ and TYPO3 4.0+ (or higher). This library is a dependency for several other pt_* extensions.',
	'category' => 'misc',
	'author' => 'Rainer Kuhn',
	'author_email' => 'kuhn@punkt.de',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'punkt.de GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.4.2dev3',
	'_md5_values_when_last_written' => 'a:70:{s:10:".cvsignore";s:4:"9538";s:9:"ChangeLog";s:4:"cb39";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"c394";s:12:"ext_icon.gif";s:4:"4546";s:17:"ext_localconf.php";s:4:"e929";s:14:"ext_tables.php";s:4:"8d35";s:14:"doc/DevDoc.txt";s:4:"a199";s:14:"doc/manual.sxw";s:4:"0c65";s:40:"doc/manual_collectionElementSelector.txt";s:4:"9e7a";s:37:"doc/manual_formTemplateHandler_de.txt";s:4:"d24f";s:19:"doc/wizard_form.dat";s:4:"31e4";s:20:"doc/wizard_form.html";s:4:"3906";s:41:"res/abstract/class.tx_pttools_address.php";s:4:"efba";s:45:"res/abstract/class.tx_pttools_beSubmodule.php";s:4:"5df2";s:44:"res/abstract/class.tx_pttools_collection.php";s:4:"d69b";s:44:"res/abstract/class.tx_pttools_iSingleton.php";s:4:"7067";s:49:"res/abstract/class.tx_pttools_iStorageAdapter.php";s:4:"4b4f";s:50:"res/abstract/class.tx_pttools_objectCollection.php";s:4:"12eb";s:43:"res/abstract/class.tx_pttools_singleton.php";s:4:"ff3a";s:21:"res/css/exception.css";s:4:"0f1a";s:45:"res/css/ie6_txpttools_formTemplateHandler.css";s:4:"5808";s:42:"res/css/tx_pttools_formTemplateHandler.css";s:4:"7244";s:16:"res/img/left.gif";s:4:"7d3a";s:21:"res/img/msg_error.gif";s:4:"3ec7";s:20:"res/img/msg_info.gif";s:4:"449c";s:24:"res/img/msg_question.gif";s:4:"af05";s:23:"res/img/msg_warning.gif";s:4:"e6e5";s:24:"res/inc/faketsfe.inc.php";s:4:"5646";s:40:"res/js/tx_pttools_formTemplateHandler.js";s:4:"028c";s:43:"res/objects/class.tx_pttools_cliHandler.php";s:4:"368c";s:58:"res/objects/class.tx_pttools_collectionElementSelector.php";s:4:"7de7";s:42:"res/objects/class.tx_pttools_exception.php";s:4:"4f2c";s:60:"res/objects/class.tx_pttools_feUsersessionStorageAdapter.php";s:4:"3d73";s:50:"res/objects/class.tx_pttools_formReloadHandler.php";s:4:"c2c0";s:52:"res/objects/class.tx_pttools_formTemplateHandler.php";s:4:"2a71";s:44:"res/objects/class.tx_pttools_formchecker.php";s:4:"5080";s:39:"res/objects/class.tx_pttools_msgBox.php";s:4:"1108";s:41:"res/objects/class.tx_pttools_registry.php";s:4:"7a31";s:54:"res/objects/class.tx_pttools_sessionStorageAdapter.php";s:4:"9c63";s:46:"res/objects/class.tx_pttools_smartyAdapter.php";s:4:"2601";s:47:"res/objects/class.tx_pttools_smartyCompiler.php";s:4:"99f1";s:55:"res/objects/class.tx_pttools_usersessStorageAdapter.php";s:4:"a5bc";s:25:"res/objects/locallang.xml";s:4:"89ad";s:62:"res/objects/exceptions/class.tx_pttools_exceptionAssertion.php";s:4:"5f14";s:67:"res/objects/exceptions/class.tx_pttools_exceptionAuthentication.php";s:4:"a774";s:66:"res/objects/exceptions/class.tx_pttools_exceptionConfiguration.php";s:4:"dc5d";s:61:"res/objects/exceptions/class.tx_pttools_exceptionDatabase.php";s:4:"d686";s:61:"res/objects/exceptions/class.tx_pttools_exceptionInternal.php";s:4:"18aa";s:63:"res/objects/exceptions/class.tx_pttools_exceptionWebservice.php";s:4:"6856";s:44:"res/smarty_plugins/function.assign_array.php";s:4:"cf8a";s:37:"res/smarty_plugins/modifier.absfn.php";s:4:"8b1c";s:42:"res/smarty_plugins/modifier.formatsize.php";s:4:"d3e3";s:34:"res/smarty_plugins/modifier.ll.php";s:4:"0710";s:40:"res/smarty_plugins/modifier.vsprintf.php";s:4:"7427";s:36:"res/smarty_plugins/modifier.wrap.php";s:4:"ca98";s:41:"res/staticlib/class.tx_pttools_assert.php";s:4:"a33d";s:40:"res/staticlib/class.tx_pttools_debug.php";s:4:"bddd";s:38:"res/staticlib/class.tx_pttools_div.php";s:4:"5e93";s:42:"res/staticlib/class.tx_pttools_finance.php";s:4:"68cb";s:51:"res/staticlib/class.tx_pttools_staticInfoTables.php";s:4:"6605";s:44:"res/tmpl/tx_pttools_formTemplateHandler.html";s:4:"ad32";s:31:"res/tmpl/tx_pttools_msgBox.html";s:4:"4db8";s:20:"static/constants.txt";s:4:"2970";s:16:"static/setup.txt";s:4:"28e6";s:42:"tests/class.tx_pttools_assert_testcase.php";s:4:"a22c";s:39:"tests/class.tx_pttools_div_testcase.php";s:4:"ebe1";s:52:"tests/class.tx_pttools_objectCollection_testcase.php";s:4:"2185";s:44:"tests/class.tx_pttools_registry_testcase.php";s:4:"c202";s:49:"tests/class.tx_pttools_smartyAdapter_testcase.php";s:4:"8219";}',
	'constraints' => array(
		'depends' => array(
			'php' => '5.1.0-0.0.0',
			'typo3' => '4.0.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'static_info_tables' => '',
			'smarty' => '',
            'abz_developer' => '',
            'cc_debug' => '',
            'geshilib' => '',
            'fb_devlog' => '',
			'PHP with --enable-bcmath (THIS IS JUST A HINT for tx_pttools_finance, please ignore if your server is correctly configured)' => '',
            'PEAR HTML_QuickForm (THIS IS JUST A HINT, please ignore if your server is correctly configured)' => '',
		),
	),
	'suggests' => array(
	),
);

?>