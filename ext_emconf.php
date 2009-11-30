<?php

########################################################################
# Extension Manager/Repository config file for ext: "pt_tools"
#
# Auto generated 27-11-2009 17:40
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
	'version' => '1.0.1',
	'_md5_values_when_last_written' => 'a:335:{s:8:".project";s:4:"9763";s:9:"ChangeLog";s:4:"e9a6";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"c394";s:12:"ext_icon.gif";s:4:"4546";s:17:"ext_localconf.php";s:4:"e929";s:14:"ext_tables.php";s:4:"8d35";s:16:".svn/all-wcprops";s:4:"5e82";s:18:".svn/dir-prop-base";s:4:"9dc2";s:12:".svn/entries";s:4:"a196";s:11:".svn/format";s:4:"c30f";s:38:".svn/tmp/text-base/README.txt.svn-base";s:4:"ee2d";s:49:".svn/tmp/text-base/ext_conf_template.txt.svn-base";s:4:"c394";s:45:".svn/tmp/text-base/ext_localconf.php.svn-base";s:4:"e929";s:42:".svn/tmp/text-base/ext_tables.php.svn-base";s:4:"8d35";s:33:".svn/prop-base/ChangeLog.svn-base";s:4:"f2ea";s:34:".svn/prop-base/README.txt.svn-base";s:4:"c65f";s:45:".svn/prop-base/ext_conf_template.txt.svn-base";s:4:"c65f";s:38:".svn/prop-base/ext_emconf.php.svn-base";s:4:"ff5c";s:36:".svn/prop-base/ext_icon.gif.svn-base";s:4:"1131";s:41:".svn/prop-base/ext_localconf.php.svn-base";s:4:"ff5c";s:38:".svn/prop-base/ext_tables.php.svn-base";s:4:"ff5c";s:33:".svn/text-base/ChangeLog.svn-base";s:4:"e9a6";s:34:".svn/text-base/README.txt.svn-base";s:4:"ee2d";s:45:".svn/text-base/ext_conf_template.txt.svn-base";s:4:"c394";s:38:".svn/text-base/ext_emconf.php.svn-base";s:4:"3d14";s:36:".svn/text-base/ext_icon.gif.svn-base";s:4:"4546";s:41:".svn/text-base/ext_localconf.php.svn-base";s:4:"e929";s:38:".svn/text-base/ext_tables.php.svn-base";s:4:"8d35";s:42:"tests/class.tx_pttools_assert_testcase.php";s:4:"a22c";s:39:"tests/class.tx_pttools_div_testcase.php";s:4:"9929";s:52:"tests/class.tx_pttools_objectCollection_testcase.php";s:4:"80d6";s:44:"tests/class.tx_pttools_registry_testcase.php";s:4:"c202";s:49:"tests/class.tx_pttools_smartyAdapter_testcase.php";s:4:"c0c2";s:22:"tests/.svn/all-wcprops";s:4:"81e2";s:18:"tests/.svn/entries";s:4:"dd50";s:17:"tests/.svn/format";s:4:"c30f";s:70:"tests/.svn/tmp/text-base/class.tx_pttools_assert_testcase.php.svn-base";s:4:"a22c";s:72:"tests/.svn/tmp/text-base/class.tx_pttools_registry_testcase.php.svn-base";s:4:"c202";s:66:"tests/.svn/prop-base/class.tx_pttools_assert_testcase.php.svn-base";s:4:"ff5c";s:63:"tests/.svn/prop-base/class.tx_pttools_div_testcase.php.svn-base";s:4:"ff5c";s:76:"tests/.svn/prop-base/class.tx_pttools_objectCollection_testcase.php.svn-base";s:4:"ff5c";s:68:"tests/.svn/prop-base/class.tx_pttools_registry_testcase.php.svn-base";s:4:"ff5c";s:73:"tests/.svn/prop-base/class.tx_pttools_smartyAdapter_testcase.php.svn-base";s:4:"ff5c";s:66:"tests/.svn/text-base/class.tx_pttools_assert_testcase.php.svn-base";s:4:"a22c";s:63:"tests/.svn/text-base/class.tx_pttools_div_testcase.php.svn-base";s:4:"1190";s:76:"tests/.svn/text-base/class.tx_pttools_objectCollection_testcase.php.svn-base";s:4:"80d6";s:68:"tests/.svn/text-base/class.tx_pttools_registry_testcase.php.svn-base";s:4:"c202";s:73:"tests/.svn/text-base/class.tx_pttools_smartyAdapter_testcase.php.svn-base";s:4:"79dd";s:14:"doc/DevDoc.txt";s:4:"09c1";s:14:"doc/manual.sxw";s:4:"48c7";s:40:"doc/manual_collectionElementSelector.txt";s:4:"9e7a";s:37:"doc/manual_formTemplateHandler_de.txt";s:4:"1305";s:19:"doc/wizard_form.dat";s:4:"31e4";s:20:"doc/wizard_form.html";s:4:"3906";s:20:"doc/.svn/all-wcprops";s:4:"9356";s:22:"doc/.svn/dir-prop-base";s:4:"0c95";s:16:"doc/.svn/entries";s:4:"0583";s:15:"doc/.svn/format";s:4:"c30f";s:68:"doc/.svn/tmp/text-base/manual_collectionElementSelector.txt.svn-base";s:4:"9e7a";s:65:"doc/.svn/tmp/text-base/manual_formTemplateHandler_de.txt.svn-base";s:4:"1305";s:48:"doc/.svn/tmp/text-base/wizard_form.html.svn-base";s:4:"3906";s:38:"doc/.svn/prop-base/DevDoc.txt.svn-base";s:4:"c65f";s:38:"doc/.svn/prop-base/manual.sxw.svn-base";s:4:"1131";s:64:"doc/.svn/prop-base/manual_collectionElementSelector.txt.svn-base";s:4:"c65f";s:61:"doc/.svn/prop-base/manual_formTemplateHandler_de.txt.svn-base";s:4:"c65f";s:43:"doc/.svn/prop-base/wizard_form.dat.svn-base";s:4:"f2ea";s:44:"doc/.svn/prop-base/wizard_form.html.svn-base";s:4:"c65f";s:38:"doc/.svn/text-base/DevDoc.txt.svn-base";s:4:"56ba";s:38:"doc/.svn/text-base/manual.sxw.svn-base";s:4:"1494";s:64:"doc/.svn/text-base/manual_collectionElementSelector.txt.svn-base";s:4:"9e7a";s:61:"doc/.svn/text-base/manual_formTemplateHandler_de.txt.svn-base";s:4:"1305";s:43:"doc/.svn/text-base/wizard_form.dat.svn-base";s:4:"31e4";s:44:"doc/.svn/text-base/wizard_form.html.svn-base";s:4:"3906";s:20:"static/constants.txt";s:4:"2970";s:16:"static/setup.txt";s:4:"28e6";s:23:"static/.svn/all-wcprops";s:4:"5f21";s:19:"static/.svn/entries";s:4:"121e";s:18:"static/.svn/format";s:4:"c30f";s:48:"static/.svn/tmp/text-base/constants.txt.svn-base";s:4:"2970";s:44:"static/.svn/tmp/text-base/setup.txt.svn-base";s:4:"28e6";s:44:"static/.svn/prop-base/constants.txt.svn-base";s:4:"c65f";s:40:"static/.svn/prop-base/setup.txt.svn-base";s:4:"c65f";s:44:"static/.svn/text-base/constants.txt.svn-base";s:4:"2970";s:40:"static/.svn/text-base/setup.txt.svn-base";s:4:"28e6";s:20:"res/.svn/all-wcprops";s:4:"12f4";s:16:"res/.svn/entries";s:4:"965a";s:15:"res/.svn/format";s:4:"c30f";s:44:"res/smarty_plugins/function.assign_array.php";s:4:"c5e3";s:46:"res/smarty_plugins/function.includeCssFile.php";s:4:"51d4";s:45:"res/smarty_plugins/function.includeJsFile.php";s:4:"d05c";s:37:"res/smarty_plugins/modifier.absfn.php";s:4:"e787";s:43:"res/smarty_plugins/modifier.convertDate.php";s:4:"d776";s:47:"res/smarty_plugins/modifier.explodeAndPrint.php";s:4:"5052";s:42:"res/smarty_plugins/modifier.formatsize.php";s:4:"a32e";s:34:"res/smarty_plugins/modifier.ll.php";s:4:"941f";s:44:"res/smarty_plugins/modifier.numberFormat.php";s:4:"6b14";s:45:"res/smarty_plugins/modifier.registerValue.php";s:4:"4450";s:39:"res/smarty_plugins/modifier.stdWrap.php";s:4:"3bdf";s:37:"res/smarty_plugins/modifier.trace.php";s:4:"eb5a";s:41:"res/smarty_plugins/modifier.urlencode.php";s:4:"82d5";s:40:"res/smarty_plugins/modifier.vsprintf.php";s:4:"77df";s:36:"res/smarty_plugins/modifier.wrap.php";s:4:"6371";s:35:"res/smarty_plugins/.svn/all-wcprops";s:4:"d471";s:31:"res/smarty_plugins/.svn/entries";s:4:"e736";s:30:"res/smarty_plugins/.svn/format";s:4:"c30f";s:68:"res/smarty_plugins/.svn/prop-base/function.assign_array.php.svn-base";s:4:"ff5c";s:61:"res/smarty_plugins/.svn/prop-base/modifier.absfn.php.svn-base";s:4:"ff5c";s:67:"res/smarty_plugins/.svn/prop-base/modifier.convertDate.php.svn-base";s:4:"ff5c";s:71:"res/smarty_plugins/.svn/prop-base/modifier.explodeAndPrint.php.svn-base";s:4:"ff5c";s:66:"res/smarty_plugins/.svn/prop-base/modifier.formatsize.php.svn-base";s:4:"ff5c";s:58:"res/smarty_plugins/.svn/prop-base/modifier.ll.php.svn-base";s:4:"ff5c";s:63:"res/smarty_plugins/.svn/prop-base/modifier.stdWrap.php.svn-base";s:4:"ff5c";s:61:"res/smarty_plugins/.svn/prop-base/modifier.trace.php.svn-base";s:4:"a94a";s:65:"res/smarty_plugins/.svn/prop-base/modifier.urlencode.php.svn-base";s:4:"ff5c";s:64:"res/smarty_plugins/.svn/prop-base/modifier.vsprintf.php.svn-base";s:4:"ff5c";s:60:"res/smarty_plugins/.svn/prop-base/modifier.wrap.php.svn-base";s:4:"ff5c";s:68:"res/smarty_plugins/.svn/text-base/function.assign_array.php.svn-base";s:4:"c5e3";s:70:"res/smarty_plugins/.svn/text-base/function.includeCssFile.php.svn-base";s:4:"51d4";s:69:"res/smarty_plugins/.svn/text-base/function.includeJsFile.php.svn-base";s:4:"d05c";s:61:"res/smarty_plugins/.svn/text-base/modifier.absfn.php.svn-base";s:4:"e787";s:67:"res/smarty_plugins/.svn/text-base/modifier.convertDate.php.svn-base";s:4:"d776";s:71:"res/smarty_plugins/.svn/text-base/modifier.explodeAndPrint.php.svn-base";s:4:"5052";s:66:"res/smarty_plugins/.svn/text-base/modifier.formatsize.php.svn-base";s:4:"a32e";s:58:"res/smarty_plugins/.svn/text-base/modifier.ll.php.svn-base";s:4:"941f";s:68:"res/smarty_plugins/.svn/text-base/modifier.numberFormat.php.svn-base";s:4:"6b14";s:69:"res/smarty_plugins/.svn/text-base/modifier.registerValue.php.svn-base";s:4:"4450";s:63:"res/smarty_plugins/.svn/text-base/modifier.stdWrap.php.svn-base";s:4:"3bdf";s:61:"res/smarty_plugins/.svn/text-base/modifier.trace.php.svn-base";s:4:"eb5a";s:65:"res/smarty_plugins/.svn/text-base/modifier.urlencode.php.svn-base";s:4:"82d5";s:64:"res/smarty_plugins/.svn/text-base/modifier.vsprintf.php.svn-base";s:4:"77df";s:60:"res/smarty_plugins/.svn/text-base/modifier.wrap.php.svn-base";s:4:"6371";s:41:"res/abstract/class.tx_pttools_address.php";s:4:"4ea8";s:45:"res/abstract/class.tx_pttools_beSubmodule.php";s:4:"1e3c";s:44:"res/abstract/class.tx_pttools_collection.php";s:4:"0a42";s:43:"res/abstract/class.tx_pttools_iPageable.php";s:4:"177a";s:52:"res/abstract/class.tx_pttools_iQuickformRenderer.php";s:4:"5455";s:50:"res/abstract/class.tx_pttools_iSettableByArray.php";s:4:"c38a";s:44:"res/abstract/class.tx_pttools_iSingleton.php";s:4:"7481";s:54:"res/abstract/class.tx_pttools_iSingletonCollection.php";s:4:"e0f1";s:49:"res/abstract/class.tx_pttools_iStorageAdapter.php";s:4:"999d";s:47:"res/abstract/class.tx_pttools_iTemplateable.php";s:4:"b565";s:50:"res/abstract/class.tx_pttools_objectCollection.php";s:4:"c733";s:43:"res/abstract/class.tx_pttools_singleton.php";s:4:"4525";s:29:"res/abstract/.svn/all-wcprops";s:4:"4fd9";s:25:"res/abstract/.svn/entries";s:4:"80f0";s:24:"res/abstract/.svn/format";s:4:"c30f";s:65:"res/abstract/.svn/prop-base/class.tx_pttools_address.php.svn-base";s:4:"ff5c";s:69:"res/abstract/.svn/prop-base/class.tx_pttools_beSubmodule.php.svn-base";s:4:"ff5c";s:68:"res/abstract/.svn/prop-base/class.tx_pttools_collection.php.svn-base";s:4:"ff5c";s:67:"res/abstract/.svn/prop-base/class.tx_pttools_iPageable.php.svn-base";s:4:"ff5c";s:76:"res/abstract/.svn/prop-base/class.tx_pttools_iQuickformRenderer.php.svn-base";s:4:"ff5c";s:74:"res/abstract/.svn/prop-base/class.tx_pttools_iSettableByArray.php.svn-base";s:4:"ff5c";s:68:"res/abstract/.svn/prop-base/class.tx_pttools_iSingleton.php.svn-base";s:4:"ff5c";s:78:"res/abstract/.svn/prop-base/class.tx_pttools_iSingletonCollection.php.svn-base";s:4:"ff5c";s:73:"res/abstract/.svn/prop-base/class.tx_pttools_iStorageAdapter.php.svn-base";s:4:"ff5c";s:71:"res/abstract/.svn/prop-base/class.tx_pttools_iTemplateable.php.svn-base";s:4:"ff5c";s:74:"res/abstract/.svn/prop-base/class.tx_pttools_objectCollection.php.svn-base";s:4:"ff5c";s:67:"res/abstract/.svn/prop-base/class.tx_pttools_singleton.php.svn-base";s:4:"ff5c";s:65:"res/abstract/.svn/text-base/class.tx_pttools_address.php.svn-base";s:4:"94ce";s:69:"res/abstract/.svn/text-base/class.tx_pttools_beSubmodule.php.svn-base";s:4:"ecac";s:68:"res/abstract/.svn/text-base/class.tx_pttools_collection.php.svn-base";s:4:"673d";s:67:"res/abstract/.svn/text-base/class.tx_pttools_iPageable.php.svn-base";s:4:"d508";s:76:"res/abstract/.svn/text-base/class.tx_pttools_iQuickformRenderer.php.svn-base";s:4:"12a9";s:74:"res/abstract/.svn/text-base/class.tx_pttools_iSettableByArray.php.svn-base";s:4:"e3f7";s:68:"res/abstract/.svn/text-base/class.tx_pttools_iSingleton.php.svn-base";s:4:"a499";s:78:"res/abstract/.svn/text-base/class.tx_pttools_iSingletonCollection.php.svn-base";s:4:"545c";s:73:"res/abstract/.svn/text-base/class.tx_pttools_iStorageAdapter.php.svn-base";s:4:"cd3d";s:71:"res/abstract/.svn/text-base/class.tx_pttools_iTemplateable.php.svn-base";s:4:"6fef";s:74:"res/abstract/.svn/text-base/class.tx_pttools_objectCollection.php.svn-base";s:4:"5353";s:67:"res/abstract/.svn/text-base/class.tx_pttools_singleton.php.svn-base";s:4:"72c0";s:21:"res/css/exception.css";s:4:"0f1a";s:45:"res/css/ie6_txpttools_formTemplateHandler.css";s:4:"5808";s:42:"res/css/tx_pttools_formTemplateHandler.css";s:4:"7244";s:24:"res/css/.svn/all-wcprops";s:4:"36e1";s:20:"res/css/.svn/entries";s:4:"ebf2";s:19:"res/css/.svn/format";s:4:"c30f";s:49:"res/css/.svn/tmp/text-base/exception.css.svn-base";s:4:"0f1a";s:73:"res/css/.svn/tmp/text-base/ie6_txpttools_formTemplateHandler.css.svn-base";s:4:"5808";s:70:"res/css/.svn/tmp/text-base/tx_pttools_formTemplateHandler.css.svn-base";s:4:"7244";s:45:"res/css/.svn/prop-base/exception.css.svn-base";s:4:"c65f";s:69:"res/css/.svn/prop-base/ie6_txpttools_formTemplateHandler.css.svn-base";s:4:"c65f";s:66:"res/css/.svn/prop-base/tx_pttools_formTemplateHandler.css.svn-base";s:4:"c65f";s:45:"res/css/.svn/text-base/exception.css.svn-base";s:4:"0f1a";s:69:"res/css/.svn/text-base/ie6_txpttools_formTemplateHandler.css.svn-base";s:4:"5808";s:66:"res/css/.svn/text-base/tx_pttools_formTemplateHandler.css.svn-base";s:4:"7244";s:24:"res/inc/faketsfe.inc.php";s:4:"3437";s:24:"res/inc/.svn/all-wcprops";s:4:"de89";s:20:"res/inc/.svn/entries";s:4:"5c30";s:19:"res/inc/.svn/format";s:4:"c30f";s:48:"res/inc/.svn/prop-base/faketsfe.inc.php.svn-base";s:4:"ff5c";s:48:"res/inc/.svn/text-base/faketsfe.inc.php.svn-base";s:4:"4882";s:43:"res/objects/class.tx_pttools_cliHandler.php";s:4:"8e4e";s:58:"res/objects/class.tx_pttools_collectionElementSelector.php";s:4:"854b";s:42:"res/objects/class.tx_pttools_exception.php";s:4:"bd25";s:60:"res/objects/class.tx_pttools_feUsersessionStorageAdapter.php";s:4:"b09e";s:50:"res/objects/class.tx_pttools_formReloadHandler.php";s:4:"dd7b";s:52:"res/objects/class.tx_pttools_formTemplateHandler.php";s:4:"aee6";s:44:"res/objects/class.tx_pttools_formchecker.php";s:4:"cfa9";s:39:"res/objects/class.tx_pttools_msgBox.php";s:4:"33c1";s:58:"res/objects/class.tx_pttools_paymentRequestInformation.php";s:4:"7b1d";s:57:"res/objects/class.tx_pttools_paymentReturnInformation.php";s:4:"2721";s:50:"res/objects/class.tx_pttools_qfDefaultRenderer.php";s:4:"abb8";s:41:"res/objects/class.tx_pttools_registry.php";s:4:"e87c";s:54:"res/objects/class.tx_pttools_sessionStorageAdapter.php";s:4:"b45e";s:46:"res/objects/class.tx_pttools_smartyAdapter.php";s:4:"d985";s:47:"res/objects/class.tx_pttools_smartyCompiler.php";s:4:"9f9d";s:55:"res/objects/class.tx_pttools_usersessStorageAdapter.php";s:4:"9591";s:25:"res/objects/locallang.xml";s:4:"89ad";s:28:"res/objects/.svn/all-wcprops";s:4:"38f7";s:24:"res/objects/.svn/entries";s:4:"0d4e";s:23:"res/objects/.svn/format";s:4:"c30f";s:53:"res/objects/.svn/tmp/text-base/locallang.xml.svn-base";s:4:"89ad";s:67:"res/objects/.svn/prop-base/class.tx_pttools_cliHandler.php.svn-base";s:4:"ff5c";s:82:"res/objects/.svn/prop-base/class.tx_pttools_collectionElementSelector.php.svn-base";s:4:"ff5c";s:66:"res/objects/.svn/prop-base/class.tx_pttools_exception.php.svn-base";s:4:"ff5c";s:84:"res/objects/.svn/prop-base/class.tx_pttools_feUsersessionStorageAdapter.php.svn-base";s:4:"ff5c";s:74:"res/objects/.svn/prop-base/class.tx_pttools_formReloadHandler.php.svn-base";s:4:"ff5c";s:76:"res/objects/.svn/prop-base/class.tx_pttools_formTemplateHandler.php.svn-base";s:4:"ff5c";s:68:"res/objects/.svn/prop-base/class.tx_pttools_formchecker.php.svn-base";s:4:"ff5c";s:63:"res/objects/.svn/prop-base/class.tx_pttools_msgBox.php.svn-base";s:4:"ff5c";s:82:"res/objects/.svn/prop-base/class.tx_pttools_paymentRequestInformation.php.svn-base";s:4:"ff5c";s:81:"res/objects/.svn/prop-base/class.tx_pttools_paymentReturnInformation.php.svn-base";s:4:"ff5c";s:74:"res/objects/.svn/prop-base/class.tx_pttools_qfDefaultRenderer.php.svn-base";s:4:"ff5c";s:65:"res/objects/.svn/prop-base/class.tx_pttools_registry.php.svn-base";s:4:"ff5c";s:78:"res/objects/.svn/prop-base/class.tx_pttools_sessionStorageAdapter.php.svn-base";s:4:"ff5c";s:70:"res/objects/.svn/prop-base/class.tx_pttools_smartyAdapter.php.svn-base";s:4:"ff5c";s:71:"res/objects/.svn/prop-base/class.tx_pttools_smartyCompiler.php.svn-base";s:4:"ff5c";s:79:"res/objects/.svn/prop-base/class.tx_pttools_usersessStorageAdapter.php.svn-base";s:4:"ff5c";s:49:"res/objects/.svn/prop-base/locallang.xml.svn-base";s:4:"c65f";s:67:"res/objects/.svn/text-base/class.tx_pttools_cliHandler.php.svn-base";s:4:"a0fb";s:82:"res/objects/.svn/text-base/class.tx_pttools_collectionElementSelector.php.svn-base";s:4:"a423";s:66:"res/objects/.svn/text-base/class.tx_pttools_exception.php.svn-base";s:4:"bdd8";s:84:"res/objects/.svn/text-base/class.tx_pttools_feUsersessionStorageAdapter.php.svn-base";s:4:"cc16";s:74:"res/objects/.svn/text-base/class.tx_pttools_formReloadHandler.php.svn-base";s:4:"7ec9";s:76:"res/objects/.svn/text-base/class.tx_pttools_formTemplateHandler.php.svn-base";s:4:"4f08";s:68:"res/objects/.svn/text-base/class.tx_pttools_formchecker.php.svn-base";s:4:"0a8c";s:63:"res/objects/.svn/text-base/class.tx_pttools_msgBox.php.svn-base";s:4:"3d50";s:82:"res/objects/.svn/text-base/class.tx_pttools_paymentRequestInformation.php.svn-base";s:4:"1909";s:81:"res/objects/.svn/text-base/class.tx_pttools_paymentReturnInformation.php.svn-base";s:4:"cf7d";s:74:"res/objects/.svn/text-base/class.tx_pttools_qfDefaultRenderer.php.svn-base";s:4:"1250";s:65:"res/objects/.svn/text-base/class.tx_pttools_registry.php.svn-base";s:4:"fa60";s:78:"res/objects/.svn/text-base/class.tx_pttools_sessionStorageAdapter.php.svn-base";s:4:"b6a6";s:70:"res/objects/.svn/text-base/class.tx_pttools_smartyAdapter.php.svn-base";s:4:"304f";s:71:"res/objects/.svn/text-base/class.tx_pttools_smartyCompiler.php.svn-base";s:4:"9f9d";s:79:"res/objects/.svn/text-base/class.tx_pttools_usersessStorageAdapter.php.svn-base";s:4:"0918";s:49:"res/objects/.svn/text-base/locallang.xml.svn-base";s:4:"89ad";s:62:"res/objects/exceptions/class.tx_pttools_exceptionAssertion.php";s:4:"767e";s:67:"res/objects/exceptions/class.tx_pttools_exceptionAuthentication.php";s:4:"a795";s:66:"res/objects/exceptions/class.tx_pttools_exceptionConfiguration.php";s:4:"2b90";s:61:"res/objects/exceptions/class.tx_pttools_exceptionDatabase.php";s:4:"52e4";s:61:"res/objects/exceptions/class.tx_pttools_exceptionInternal.php";s:4:"739e";s:67:"res/objects/exceptions/class.tx_pttools_exceptionNotImplemented.php";s:4:"7c80";s:63:"res/objects/exceptions/class.tx_pttools_exceptionWebservice.php";s:4:"e7c6";s:39:"res/objects/exceptions/.svn/all-wcprops";s:4:"10f7";s:35:"res/objects/exceptions/.svn/entries";s:4:"bff9";s:34:"res/objects/exceptions/.svn/format";s:4:"c30f";s:86:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionAssertion.php.svn-base";s:4:"ff5c";s:91:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionAuthentication.php.svn-base";s:4:"ff5c";s:90:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionConfiguration.php.svn-base";s:4:"ff5c";s:85:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionDatabase.php.svn-base";s:4:"ff5c";s:85:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionInternal.php.svn-base";s:4:"ff5c";s:91:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionNotImplemented.php.svn-base";s:4:"ff5c";s:87:"res/objects/exceptions/.svn/prop-base/class.tx_pttools_exceptionWebservice.php.svn-base";s:4:"ff5c";s:86:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionAssertion.php.svn-base";s:4:"6606";s:91:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionAuthentication.php.svn-base";s:4:"f62b";s:90:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionConfiguration.php.svn-base";s:4:"2ad8";s:85:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionDatabase.php.svn-base";s:4:"c55c";s:85:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionInternal.php.svn-base";s:4:"6f6d";s:91:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionNotImplemented.php.svn-base";s:4:"d04d";s:87:"res/objects/exceptions/.svn/text-base/class.tx_pttools_exceptionWebservice.php.svn-base";s:4:"6267";s:16:"res/img/left.gif";s:4:"7d3a";s:21:"res/img/msg_error.gif";s:4:"3ec7";s:20:"res/img/msg_info.gif";s:4:"449c";s:24:"res/img/msg_question.gif";s:4:"af05";s:23:"res/img/msg_warning.gif";s:4:"e6e5";s:24:"res/img/.svn/all-wcprops";s:4:"01e6";s:20:"res/img/.svn/entries";s:4:"5954";s:19:"res/img/.svn/format";s:4:"c30f";s:40:"res/img/.svn/prop-base/left.gif.svn-base";s:4:"1131";s:45:"res/img/.svn/prop-base/msg_error.gif.svn-base";s:4:"1131";s:44:"res/img/.svn/prop-base/msg_info.gif.svn-base";s:4:"1131";s:48:"res/img/.svn/prop-base/msg_question.gif.svn-base";s:4:"1131";s:47:"res/img/.svn/prop-base/msg_warning.gif.svn-base";s:4:"1131";s:40:"res/img/.svn/text-base/left.gif.svn-base";s:4:"7d3a";s:45:"res/img/.svn/text-base/msg_error.gif.svn-base";s:4:"3ec7";s:44:"res/img/.svn/text-base/msg_info.gif.svn-base";s:4:"449c";s:48:"res/img/.svn/text-base/msg_question.gif.svn-base";s:4:"af05";s:47:"res/img/.svn/text-base/msg_warning.gif.svn-base";s:4:"e6e5";s:40:"res/js/tx_pttools_formTemplateHandler.js";s:4:"a7d6";s:23:"res/js/.svn/all-wcprops";s:4:"5d7e";s:19:"res/js/.svn/entries";s:4:"3503";s:18:"res/js/.svn/format";s:4:"c30f";s:64:"res/js/.svn/prop-base/tx_pttools_formTemplateHandler.js.svn-base";s:4:"c65f";s:64:"res/js/.svn/text-base/tx_pttools_formTemplateHandler.js.svn-base";s:4:"2e04";s:44:"res/tmpl/tx_pttools_formTemplateHandler.html";s:4:"86c5";s:31:"res/tmpl/tx_pttools_msgBox.html";s:4:"932f";s:49:"res/tmpl/tx_pttools_quickformRendererDefault.html";s:4:"7ac5";s:45:"res/tmpl/tx_pttools_quickformRendererDiv.html";s:4:"67ca";s:49:"res/tmpl/tx_pttools_quickformRendererMinimal.html";s:4:"1a6e";s:25:"res/tmpl/.svn/all-wcprops";s:4:"8a2a";s:21:"res/tmpl/.svn/entries";s:4:"eb9f";s:20:"res/tmpl/.svn/format";s:4:"c30f";s:68:"res/tmpl/.svn/prop-base/tx_pttools_formTemplateHandler.html.svn-base";s:4:"c65f";s:55:"res/tmpl/.svn/prop-base/tx_pttools_msgBox.html.svn-base";s:4:"c65f";s:73:"res/tmpl/.svn/prop-base/tx_pttools_quickformRendererDefault.html.svn-base";s:4:"c65f";s:69:"res/tmpl/.svn/prop-base/tx_pttools_quickformRendererDiv.html.svn-base";s:4:"f2ea";s:73:"res/tmpl/.svn/prop-base/tx_pttools_quickformRendererMinimal.html.svn-base";s:4:"c65f";s:68:"res/tmpl/.svn/text-base/tx_pttools_formTemplateHandler.html.svn-base";s:4:"43b2";s:55:"res/tmpl/.svn/text-base/tx_pttools_msgBox.html.svn-base";s:4:"0d5b";s:73:"res/tmpl/.svn/text-base/tx_pttools_quickformRendererDefault.html.svn-base";s:4:"7ac5";s:69:"res/tmpl/.svn/text-base/tx_pttools_quickformRendererDiv.html.svn-base";s:4:"67ca";s:73:"res/tmpl/.svn/text-base/tx_pttools_quickformRendererMinimal.html.svn-base";s:4:"1a6e";s:41:"res/staticlib/class.tx_pttools_assert.php";s:4:"73fe";s:40:"res/staticlib/class.tx_pttools_debug.php";s:4:"921a";s:38:"res/staticlib/class.tx_pttools_div.php";s:4:"b7c1";s:42:"res/staticlib/class.tx_pttools_finance.php";s:4:"5faa";s:51:"res/staticlib/class.tx_pttools_staticInfoTables.php";s:4:"fc4b";s:30:"res/staticlib/.svn/all-wcprops";s:4:"7ec8";s:26:"res/staticlib/.svn/entries";s:4:"8112";s:25:"res/staticlib/.svn/format";s:4:"c30f";s:65:"res/staticlib/.svn/prop-base/class.tx_pttools_assert.php.svn-base";s:4:"ff5c";s:64:"res/staticlib/.svn/prop-base/class.tx_pttools_debug.php.svn-base";s:4:"ff5c";s:62:"res/staticlib/.svn/prop-base/class.tx_pttools_div.php.svn-base";s:4:"ff5c";s:66:"res/staticlib/.svn/prop-base/class.tx_pttools_finance.php.svn-base";s:4:"ff5c";s:75:"res/staticlib/.svn/prop-base/class.tx_pttools_staticInfoTables.php.svn-base";s:4:"ff5c";s:65:"res/staticlib/.svn/text-base/class.tx_pttools_assert.php.svn-base";s:4:"73fe";s:64:"res/staticlib/.svn/text-base/class.tx_pttools_debug.php.svn-base";s:4:"d3a3";s:62:"res/staticlib/.svn/text-base/class.tx_pttools_div.php.svn-base";s:4:"a719";s:66:"res/staticlib/.svn/text-base/class.tx_pttools_finance.php.svn-base";s:4:"3425";s:75:"res/staticlib/.svn/text-base/class.tx_pttools_staticInfoTables.php.svn-base";s:4:"b5bb";s:44:".settings/com.zend.php.javabridge.core.prefs";s:4:"573a";s:51:".settings/org.eclipse.php.core.projectOptions.prefs";s:4:"6922";s:17:".cache/.dataModel";s:4:"ab55";s:21:".cache/.wsdlDataModel";s:4:"90da";}',
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