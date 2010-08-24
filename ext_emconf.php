<?php

########################################################################
# Extension Manager/Repository config file for ext "pt_tools".
#
# Auto generated 24-08-2010 17:41
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'punkt.de Tools',
	'description' => 'PHP5 toolbox and class library for extension development using PHP 5.1+ and TYPO3 4.0+ (or higher). This library is a dependency for several other pt_* extensions.',
	'category' => 'misc',
	'author' => 'Rainer Kuhn',
	'author_email' => 't3extensions@punkt.de',
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
	'version' => '1.0.2',
	'_md5_values_when_last_written' => 'a:92:{s:9:"ChangeLog";s:4:"e9a6";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"c394";s:12:"ext_icon.gif";s:4:"4546";s:17:"ext_localconf.php";s:4:"e929";s:14:"ext_tables.php";s:4:"8d35";s:14:"doc/DevDoc.txt";s:4:"1e2c";s:14:"doc/manual.sxw";s:4:"48c7";s:40:"doc/manual_collectionElementSelector.txt";s:4:"9e7a";s:37:"doc/manual_formTemplateHandler_de.txt";s:4:"1305";s:19:"doc/wizard_form.dat";s:4:"31e4";s:20:"doc/wizard_form.html";s:4:"3906";s:41:"res/abstract/class.tx_pttools_address.php";s:4:"94ce";s:45:"res/abstract/class.tx_pttools_beSubmodule.php";s:4:"ecac";s:44:"res/abstract/class.tx_pttools_collection.php";s:4:"9f51";s:43:"res/abstract/class.tx_pttools_iPageable.php";s:4:"d508";s:52:"res/abstract/class.tx_pttools_iQuickformRenderer.php";s:4:"12a9";s:50:"res/abstract/class.tx_pttools_iSettableByArray.php";s:4:"e3f7";s:44:"res/abstract/class.tx_pttools_iSingleton.php";s:4:"a499";s:54:"res/abstract/class.tx_pttools_iSingletonCollection.php";s:4:"545c";s:49:"res/abstract/class.tx_pttools_iStorageAdapter.php";s:4:"cd3d";s:47:"res/abstract/class.tx_pttools_iTemplateable.php";s:4:"6fef";s:50:"res/abstract/class.tx_pttools_objectCollection.php";s:4:"5353";s:43:"res/abstract/class.tx_pttools_singleton.php";s:4:"72c0";s:21:"res/css/exception.css";s:4:"0f1a";s:45:"res/css/ie6_txpttools_formTemplateHandler.css";s:4:"5808";s:42:"res/css/tx_pttools_formTemplateHandler.css";s:4:"7244";s:16:"res/img/left.gif";s:4:"7d3a";s:21:"res/img/msg_error.gif";s:4:"3ec7";s:20:"res/img/msg_info.gif";s:4:"449c";s:24:"res/img/msg_question.gif";s:4:"af05";s:23:"res/img/msg_warning.gif";s:4:"e6e5";s:24:"res/inc/faketsfe.inc.php";s:4:"4882";s:40:"res/js/tx_pttools_formTemplateHandler.js";s:4:"2e04";s:43:"res/objects/class.tx_pttools_cliHandler.php";s:4:"a0fb";s:58:"res/objects/class.tx_pttools_collectionElementSelector.php";s:4:"a423";s:42:"res/objects/class.tx_pttools_exception.php";s:4:"4a94";s:60:"res/objects/class.tx_pttools_feUsersessionStorageAdapter.php";s:4:"cc16";s:50:"res/objects/class.tx_pttools_formReloadHandler.php";s:4:"7ec9";s:52:"res/objects/class.tx_pttools_formTemplateHandler.php";s:4:"4f08";s:44:"res/objects/class.tx_pttools_formchecker.php";s:4:"0a8c";s:39:"res/objects/class.tx_pttools_msgBox.php";s:4:"3d50";s:58:"res/objects/class.tx_pttools_paymentRequestInformation.php";s:4:"1909";s:57:"res/objects/class.tx_pttools_paymentReturnInformation.php";s:4:"cf7d";s:50:"res/objects/class.tx_pttools_qfDefaultRenderer.php";s:4:"1250";s:41:"res/objects/class.tx_pttools_registry.php";s:4:"fa60";s:54:"res/objects/class.tx_pttools_sessionStorageAdapter.php";s:4:"b6a6";s:46:"res/objects/class.tx_pttools_smartyAdapter.php";s:4:"304f";s:47:"res/objects/class.tx_pttools_smartyCompiler.php";s:4:"9f9d";s:55:"res/objects/class.tx_pttools_usersessStorageAdapter.php";s:4:"0918";s:25:"res/objects/locallang.xml";s:4:"89ad";s:62:"res/objects/exceptions/class.tx_pttools_exceptionAssertion.php";s:4:"f330";s:67:"res/objects/exceptions/class.tx_pttools_exceptionAuthentication.php";s:4:"f62b";s:66:"res/objects/exceptions/class.tx_pttools_exceptionConfiguration.php";s:4:"2ad8";s:61:"res/objects/exceptions/class.tx_pttools_exceptionDatabase.php";s:4:"c55c";s:61:"res/objects/exceptions/class.tx_pttools_exceptionInternal.php";s:4:"6f6d";s:67:"res/objects/exceptions/class.tx_pttools_exceptionNotImplemented.php";s:4:"d04d";s:63:"res/objects/exceptions/class.tx_pttools_exceptionWebservice.php";s:4:"6267";s:44:"res/smarty_plugins/function.assign_array.php";s:4:"c5e3";s:44:"res/smarty_plugins/function.img_resource.php";s:4:"f996";s:46:"res/smarty_plugins/function.includeCssFile.php";s:4:"51d4";s:45:"res/smarty_plugins/function.includeJsFile.php";s:4:"d05c";s:37:"res/smarty_plugins/modifier.absfn.php";s:4:"e787";s:43:"res/smarty_plugins/modifier.convertDate.php";s:4:"d776";s:47:"res/smarty_plugins/modifier.explodeAndPrint.php";s:4:"5052";s:42:"res/smarty_plugins/modifier.formatsize.php";s:4:"a32e";s:34:"res/smarty_plugins/modifier.ll.php";s:4:"4832";s:40:"res/smarty_plugins/modifier.multiply.php";s:4:"9f42";s:44:"res/smarty_plugins/modifier.numberFormat.php";s:4:"6b14";s:45:"res/smarty_plugins/modifier.registerValue.php";s:4:"4450";s:39:"res/smarty_plugins/modifier.stdWrap.php";s:4:"3bdf";s:37:"res/smarty_plugins/modifier.trace.php";s:4:"eb5a";s:41:"res/smarty_plugins/modifier.urlencode.php";s:4:"82d5";s:40:"res/smarty_plugins/modifier.vsprintf.php";s:4:"77df";s:36:"res/smarty_plugins/modifier.wrap.php";s:4:"6371";s:41:"res/staticlib/class.tx_pttools_assert.php";s:4:"f19c";s:40:"res/staticlib/class.tx_pttools_debug.php";s:4:"0b99";s:38:"res/staticlib/class.tx_pttools_div.php";s:4:"3e50";s:42:"res/staticlib/class.tx_pttools_finance.php";s:4:"3425";s:51:"res/staticlib/class.tx_pttools_staticInfoTables.php";s:4:"b5bb";s:44:"res/tmpl/tx_pttools_formTemplateHandler.html";s:4:"43b2";s:31:"res/tmpl/tx_pttools_msgBox.html";s:4:"0d5b";s:49:"res/tmpl/tx_pttools_quickformRendererDefault.html";s:4:"7ac5";s:45:"res/tmpl/tx_pttools_quickformRendererDiv.html";s:4:"67ca";s:49:"res/tmpl/tx_pttools_quickformRendererMinimal.html";s:4:"1a6e";s:20:"static/constants.txt";s:4:"2970";s:16:"static/setup.txt";s:4:"28e6";s:42:"tests/class.tx_pttools_assert_testcase.php";s:4:"a22c";s:39:"tests/class.tx_pttools_div_testcase.php";s:4:"1190";s:52:"tests/class.tx_pttools_objectCollection_testcase.php";s:4:"80d6";s:44:"tests/class.tx_pttools_registry_testcase.php";s:4:"c202";s:49:"tests/class.tx_pttools_smartyAdapter_testcase.php";s:4:"79dd";}',
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
			'PEAR Console_Getopt (THIS IS JUST A HINT, please ignore if your server is correctly configured)' => '',
			'PEAR HTML_QuickForm (THIS IS JUST A HINT, please ignore if your server is correctly configured)' => '',
		),
	),
	'suggests' => array(
	),
);

?>