<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
} 

$baseConfArr = unserialize($TYPO3_CONF_VARS['EXT']['extConf']['pt_tools']);

if ($baseConfArr['convertErrorsToExceptions']) {
    
    require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php';
    
    $GLOBALS['tx_pttools_debug']['errors'] = error_reporting();
    
    set_error_handler(array('tx_pttools_debug', 'convertErrorToExceptionErrorHandler'));
}
    
if ($baseConfArr['catchUncaughtExceptions']) {
    
    require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php';
    
    set_exception_handler(array('tx_pttools_debug', 'catchUncaughtExceptionsExceptionHandler'));
}

// setting smarty directories
$TYPO3_CONF_VARS['EXTCONF']['smarty']['cache_dir'] = 'typo3temp/smarty_cache/';
$TYPO3_CONF_VARS['EXTCONF']['smarty']['compile_dir'] = 'typo3temp/smarty_compile/';

?>