<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2006-2008 Rainer Kuhn (kuhn@punkt.de), Fabrizio Branca (mail@fabrizio-branca.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Adapter for the Smarty Templating engine (requires the TYPO3 extension 'smarty', replaces class.tx_smarty.php)
 *
 * $Id$
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>, completely rewritten by Fabrizio Branca <mail@fabrizio-branca.de> 2008-06-20
 * @since   2006-03-20
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php';



/**
 * The location of the smarty classes changed in the smarty extension >= 1.1.0
 * Fabrizio Branca <mail@fabrizio-branca.de> 2007-11
 */
t3lib_extMgm::isLoaded('smarty', 1); // die if smarty is not loaded
try {
    $smartyConf = tx_pttools_div::returnExtConfArray('smarty');
    if (!empty($smartyConf['smarty_dir'])) {
        $smartyPath = t3lib_div::getFileAbsFileName($smartyConf['smarty_dir'], 0) . '/libs/';  
    } else {
        $smartyPath = t3lib_extMgm::extPath('smarty');
    }
} catch (tx_pttools_exception $excObj) { // no configuration found
    $smartyPath = t3lib_extMgm::extPath('smarty');
}
define('SMARTY_DIR', $smartyPath);
require_once($smartyPath.'Smarty.class.php');   // Smarty template engine (requires the TYPO3 extension 'smarty')

require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_registry.php';
require_once t3lib_extMgm::extPath('smarty').'class.tx_smarty_wrapper.php';
require_once t3lib_extMgm::extPath('lang').'lang.php';



/**
 * Adapter class for the Smarty Templating engine to set individual directories and extend functionalities (requires the TYPO3 extension 'smarty', replaces class.tx_smarty.php)
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>, completely rewritten by Fabrizio Branca <mail@fabrizio-branca.de> 2008-06-20
 * @since       2006-03-20
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_smartyAdapter extends tx_smarty_wrapper {

    /**
     * @var string	TYPO3 language key (for the "ll" modifier)
     */
    protected $t3_languageKey;
    
    /**
     * @var string	character set for the outputting language labels (for the "ll" modifier)
     */
    protected $t3_charSet;
    
    

    /**
     * Class constructor
     *
     * @param 	mixed	(optional) calling object or [DEPRECATED!] extKey
     * @param 	mixed	(optional) configuration array or [DEPRECATED!] the directory where Smarty templates are located (relative directory path within the caller plugin's extension directory)
     * @param   string  (optional) [DEPRECATED!] the directory where Smarty compiled templates are located (relative directory path within the caller plugin's extension directory)
     * @param   string  (optional) [DEPRECATED!] the directory where Smarty config files are located (absolute webserver directory path within htdocs/)
     * @param   string  (optional) [DEPRECATED!] the directory for Smarty cache files (absolute webserver directory path within htdocs/)
     * @throws	tx_pttools_exception	if no compile_dir or cache_dir defined in one of the configuration places
     * @author	Rainer Kuhn <kuhn@punkt.de>, completely rewritten by Fabrizio Branca <mail@fabrizio-branca.de> 2008-06-16
     * @since	2006-03-20
     */
    public function __construct($extKeyOrpObj = NULL, $templateDir_relOrLocalConf='res/smarty_tpl/', $configDir_rel='res/smarty_cfg/', $compileDir_abs='typo3temp/smarty_compile/', $cacheDir_abs='typo3temp/smarty_cache/') {

        if (is_string($extKeyOrpObj) && is_string($templateDir_relOrLocalConf)) {
            $extKey = $extKeyOrpObj;
            $templateDir_rel = $templateDir_relOrLocalConf;

            // converting parameters from old smartyAdapter for compatibility reasons
            $localConf = array(
                'compile_dir'  => PATH_site.$compileDir_abs,
                'cache_dir'    => PATH_site.$cacheDir_abs,
                'config_dir'   => t3lib_extMgm::siteRelPath($extKey).$configDir_rel,
                'template_dir' => t3lib_extMgm::siteRelPath($extKey).$templateDir_rel,
            );
        } else {
            $localConf = is_array($templateDir_relOrLocalConf) ? $templateDir_relOrLocalConf : array();
            $pObj = $extKeyOrpObj;
        }

        // set new compiler class (needed to overwrite trigger_error() to throw exceptions instead of triggering errors)
        $this->compiler_class = 'tx_pttools_smartyCompiler';
        $this->compiler_file = t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_smartyCompiler.php';

        // Run Smarty's constructor
        $this->Smarty();
        
        // Register "string" resource
        $this->register_resource(
            'string',
            array(
                $this,
                'string_resource_get_template',
                'string_resource_get_timestamp',
                'string_resource_get_secure',
                'string_resource_get_trusted'
            )
        );

        // Register reference to the calling class
        if (is_object($pObj)) {
            $this->pObj = $pObj;
        }

        if (is_object($GLOBALS['TSFE'])) {

            // Register reference to tslib_cobj
            $this->cObj = $GLOBALS['TSFE']->cObj;

            // Set language key
            $this->t3_languageKey = $GLOBALS['TSFE']->lang;

        }

        $this->loadAndSetConfiguration($pObj, $localConf);

        // add own plugins
        array_unshift($this->plugins_dir, t3lib_extMgm::extPath('pt_tools').'res/smarty_plugins');
        
        // hook: allow extension to add own pluginDirectories to smarty
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pt_tools']['tx_pttools_smartyAdapter']['pluginDirectory'])) {
            foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pt_tools']['tx_pttools_smartyAdapter']['pluginDirectory'] as $pluginDirectory) {
            	array_unshift($this->plugins_dir, $pluginDirectory);
            }
        }

        // add language configuration to the registry (needed for the "ll" modifier)
        $configuration = array(
            't3_languageKey' => $this->t3_languageKey,
            't3_languageFile' => $this->t3_languageFile,
            't3_charSet' => $this->t3_charSet,
        );
        tx_pttools_registry::getInstance()->register('smarty_configuration', $configuration, true);
        
    }
    
    /**
     * Load and set configuration
     *
     * @param	object	parent object
     * @param 	array 	configuration array
     * @return 	void
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-17
     */
    public function loadAndSetConfiguration($pObj = NULL, array $localConf = array()) {

        /***********************************************************************
         * Load configuration
         **********************************************************************/
        
        // some basic conventions
        if (is_object($pObj) && !empty($pObj->extKey)) {
            $this->t3_confVars['conventions']['t3_languageFile'] = 'EXT:'.$pObj->extKey.'/locallang.xml';
        }
        
        // get configuration from extconf
        $this->t3_confVars['extconf'] = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['smarty'];

        // get configuration from typoscript (global and plugin specific) if available
        if (is_object($GLOBALS['TSFE'])) {

            // get global configuration from typoscript
            $this->t3_confVars['smarty'] = $GLOBALS['TSFE']->tmpl->setup['plugin.']['smarty.'];

            // get extension specific configuration from typoscript (config.<extKey>.smarty{})
            if(is_object($pObj) && property_exists($pObj, 'extKey')) {
                $this->t3_confVars[$pObj->extKey] = $GLOBALS['TSFE']->tmpl->setup['config.'][$pObj->extKey.'.']['smarty.'];
            }

            // get plugin specific configuration from typoscript (plugin.<prefixId>.smarty{})
            if(is_object($pObj) && property_exists($pObj, 'prefixId')) {
                $this->t3_confVars[$pObj->prefixId] = $GLOBALS['TSFE']->tmpl->setup['plugin.'][$pObj->prefixId.'.']['smarty.'];
            }

        }

        // get local configuration from constructor
        $this->t3_confVars['local'] = $localConf;

        /***********************************************************************
         * Set configuration
         **********************************************************************/
        // Set the Smarty class vars in the order defined above
        foreach($this->t3_confVars as $configuration) {

            // pathToTemplateDirectory is an alias for template_dir
            if($configuration['pathToTemplateDirectory']) {
                $configuration['template_dir'] = $configuration['pathToTemplateDirectory'];
            }

            if(is_array($configuration)) {
                foreach($configuration as $var => $value) {
                    if ($var == 't3_languageFile') {
                        $this->setPathToLanguageFile($value);
                    } elseif ($var == 't3_languageKey') {
                        $this->t3_languageKey = $value;
                    } elseif ($var == 't3_charSet') {
                        $this->t3_charSet = $value;
                    } else {
                        $this->setSmartyVar($var, $value);
                    }
                }
            }
        }
        
        // get plugin locallang if language file was not set before and the calling object is a pibase object
        if (empty($this->t3_languageFile) && is_object($this->pObj) && property_exists($this->pObj, 'extKey') && property_exists($this->pObj, 'scriptRelPath')) {
            
            // check for EXT:<extKey>/<scriptRelPath>/locallang.[xml|php]
            $basePath = t3lib_extMgm::extPath($this->pObj->extKey).dirname($this->pObj->scriptRelPath).'/locallang';
            $file = t3lib_div::getFileAbsFileName($basePath);
            if (@is_file($file.'.xml')) {
                $this->t3_languageFile = $file.'.xml';
            } elseif(@is_file($file.'.php')) {
                $this->t3_languageFile = $file.'.php';
            } else {
                // check for EXT:<extKey>/locallang.[xml|php]
                $basePath = t3lib_extMgm::extPath($this->pObj->extKey).'/locallang';
                $file = t3lib_div::getFileAbsFileName($basePath);
                if (@is_file($file.'.xml')) { 
                    $this->t3_languageFile = $file.'.xml';
                } elseif(@is_file($file.'.php')) {
                    $this->t3_languageFile = $file.'.php';
                }
            }
        }
        
        if (empty($this->t3_charSet)) {
        	$this->t3_charSet = tx_pttools_div::getSiteCharsetEncoding();
        }

        tx_pttools_assert::isDir($this->compile_dir, array('message' => 'No valid compile directory: "'.$this->compile_dir.'"'));
        tx_pttools_assert::isDir($this->cache_dir, array('message' => 'No valid cache directory: "'.$this->cache_dir.'"'));

    }

    /**
     * Override "trigger Smarty error" method to throw exceptions instead of triggering errors
     *
     * @param 	string 	error_msg
     * @param 	integer (optional) error_type, default is E_USER_WARNING
     * @throws	tx_pttools_exception
     * @return 	void
     */
    public function trigger_error($error_msg, $error_type = E_USER_WARNING) {
        
        throw new tx_pttools_exception('Smarty error: "'.$error_msg.'" (Type: "'.$error_type.'")');
        
    }
    
    /**
     * Set path to language file
     *
     * @param 	mixed	string, getFileAbsFileName or tx_lib_object object
     * @return 	void
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-06-20
     */
    public function setPathToLanguageFile($param) {
        
        $this->t3_languageFile = $param;
        
    }    
    
    /**
     * Returns a Smarty template resource from a given TypoScript resource data type filename (to use for Smarty's display() or fetch() methods). For TYPO3 frontend (FE) use only!
     *  
     * @param   string      the filename, being a TypoScript resource data type
     * @return  string      a Smarty template resource string: the 'file:' template resource type, followed by the absolute path and name of the template 
     * @throws  tx_pttools_exception    if template file for dunning mail body not found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-03-20
     */
    public function getTplResFromTsRes($typoscriptResFile) {
        
        $absTplPath = t3lib_div::getFileAbsFileName($typoscriptResFile, 0);
        tx_pttools_assert::isFilePath($absTplPath, array('message' => 'Smarty template not found in "'.$absTplPath.'"'));
        
        return 'file:'.$absTplPath;
        
    }
    
    
    /***************************************************************************
     * Methods for the "string" resource handler
     **************************************************************************/
    
    /**
     * Resource "string" helper method: get template content
     *
     * @param 	string	template name
     * @param 	string	template source, passed by reference
     * @param 	Smarty 	calling smarty object
     * @return 	bool 	true
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-10-15
     */
    public function string_resource_get_template($tpl_name, &$tpl_source, Smarty $smarty_obj) {
        
        $tpl_source = $tpl_name;
        return true;
        
    }

    /**
     * Resource "string" helper method: get timestamp
     *
     * @param 	string 	template name
     * @param 	string 	timestamp, passed by reference
     * @param 	Smarty 	calling smarty object
     * @return 	bool 	true
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-10-15
     */
    public function string_resource_get_timestamp($tpl_name, &$tpl_timestamp, Smarty $smarty_obj) {
        
        $tpl_timestamp = time();
        return true;    
        
    }

    /**
     * Resource "string" helper method: get secure flag 
     *
     * @param 	string	template name
     * @param 	Smarty 	calling smarty object
     * @return 	bool 	true
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-10-15
     */
    public function string_resource_get_secure($tpl_name, Smarty $smarty_obj) {
        
        return true;
        
    }

    /**
     * Resource "string" helper method: get trusted flag
     *
     * @param 	string	template name
     * @param 	Smarty 	calling smarty object
     * @return 	bool 	true
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-10-15
     */
    public function string_resource_get_trusted($tpl_name, Smarty $smarty_obj) {  
        
        return true;
        
    }

}




/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_smartyAdapter.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_smartyAdapter.php']);
}

?>