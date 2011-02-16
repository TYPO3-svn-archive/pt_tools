<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007-2008 Rainer Kuhn <kuhn@punkt.de>
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
 * Abstract backend submodules parent class
 * IMPORTANT: This class is experimental and still under development. Implementation is not final, APIs may change anytime - so don't use this class for your extensions yet!
 *
 * $Id$
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2008-02-06 (based on class.tx_ptgsaadmin_submodules.php, since 2007-08-24)
 */


/**
 * Inclusion of TYPO3 resources
 */
require_once(PATH_t3lib.'class.t3lib_scbase.php'); // parent class for 'ScriptClasses' in backend modules
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_formReloadHandler.php'; // web form reload handler class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';



/**
 * Abstract backend submodules parent class
 * IMPORTANT: This class is experimental and still under development. Implementation is not final, APIs may change anytime - so don't use this class for your extensions yet!
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2008-02-06 (based on class tx_ptgsaadmin_submodules, since 2007-08-24)
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_pttools_beSubmodule extends t3lib_SCbase {

    /**
     * @var mixed   works as an access check that returns an array of the page record if access was granted, otherwise returns false
     * @see t3lib_BEfunc::readPageAccess
     */
    public $pageinfo;

    /**
     * @var string   (required) extension key of the inheriting child class
     */
    protected $extKey = '';

    /**
     * @var string   (required) extension prefix of the inheriting child class (used for CSS classes, session keys etc.)
     */
    protected $extPrefix = '';

    /**
     * @var string   (optional) path to the CSS file to use  - this may be set from the inheriting child class (relative path from the module's index.php file)
     */
    protected $cssRelPath = '';

    /**
     * @var array   (optional) configuration array - this may be set from the inheriting child class (relative path from the module's index.php file)
     */
    protected $conf = array();

    /**
     * @var tx_pttools_formReloadHandler    web form reload handler object of type tx_pttools_formReloadHandler
     */
    protected $formReloadHandler = NULL;

    /**
     * @var template
     */
    public $doc;

    /**
     * @var array	will be imploded and included in the head of the html page
     */
    protected $jsArray = array();




    /***************************************************************************
     *   TYPO3 BACKEND MODULE API / EXTENDED KICKSTARTER API METHODS
     **************************************************************************/

    /**
     * Initializes the module
     *
     * @param       void
     * @return      void
     * @throws      tx_pttools_exceptionAssertion   if no extension key has been set (for the inheriting child class)
     * @throws      tx_pttools_exceptionAssertion   if no extension prefix has been set (for the inheriting child class)
     * @author      Rainer Kuhn <kuhn@punkt.de> (based on code from TYPO3 kickstarter)
     * @since       2007-08-24
     */
    public function init() {

        // global var usage as defined by TYPO3 API (kickstarter code)
        global $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $CLIENT, $TYPO3_CONF_VARS;

        // check requirements (throw exception for developper of the inheriting child class)
        tx_pttools_assert::isNotEmptyString($this->extKey, array('message' => 'No extension key set for '.__CLASS__));
        tx_pttools_assert::isNotEmptyString($this->extPrefix, array('message' => 'No extension prefix set for '.__CLASS__));

        parent::init();

        /*
        if (t3lib_div::_GP('clear_all_cache')) {
            $this->include_once[] = PATH_t3lib.'class.t3lib_tcemain.php';
        }
        */
    }

    /**
     * Main function of the module: write the content to $this->content
     * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
     *
     * @param       void
     * @return      void
     * @author      Rainer Kuhn <kuhn@punkt.de> (based on code from TYPO3 kickstarter)
     * @since       2007-08-24
     */
    public function main() {

        // global var usage as defined by TYPO3 API (kickstarter code)
        global $BE_USER, $LANG, $BACK_PATH, $TCA_DESCR, $TCA, $CLIENT, $TYPO3_CONF_VARS;

        /* @var $LANG language */
        /* @var $BE_USER t3lib_beUserAuth */

        try {
            // Dev output
            trace(unserialize($GLOBALS['BE_USER']->user['ses_data']), 0, 'ses_data');

            // access check: the page will show only if there is a valid page and if this page may be viewed by the user
            $this->pageinfo = t3lib_BEfunc::readPageAccess($this->id, $this->perms_clause);
            $access = is_array($this->pageinfo) ? 1 : 0;

            // set $this->doc as an instance of the 'template' class from typo3/template.php (see 'EXAMPLE PROTOTYPE' in t3lib_SCbase)
            $this->doc = t3lib_div::makeInstance('noDoc'); // 'noDoc' = TYPO3 backend template class used for backend pages which were medium wide (see file typo3/template.php)
            $this->doc->backPath = $BACK_PATH;

            // set individual properties
            $this->formReloadHandler = new tx_pttools_formReloadHandler;

            // generate page content for valid access
            if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id) || ($BE_USER->user['uid'] && !$this->id)) {

                $moduleContent = $this->moduleContent(); // this needs to be placed here if js has to be set from within this module

                // add header
                $this->setDocCodePropertiesHTML($this->doc, $this->cssRelPath); // $this->doc (object of type template) is passed by reference
                $headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']).'<br />'.
                                 $LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.path').': '.
                                 t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'], 50);
                $this->content .= $this->doc->startPage($LANG->getLL('title'));
                $this->content .= $this->doc->header($LANG->getLL('title'));
                $this->content .= $this->doc->spacer(5);
                $this->content .= $this->doc->section('', $this->doc->funcMenu($headerSection, t3lib_BEfunc::getFuncMenu($this->id,
                                                                                                                         'SET[jumpMenuFunction]',
                                                                                                                         $this->MOD_SETTINGS['jumpMenuFunction'],
                                                                                                                         $this->MOD_MENU['jumpMenuFunction']
                                                                                                                        )));
                $this->content .= $this->doc->divider(5);

                // add module content
                $this->content .= $moduleContent;

                // add shortcut icon (if allowed for BE user) and spacer
                if ($BE_USER->mayMakeShortcut()) {
                    $this->content .= $this->doc->spacer(20).$this->doc->section('', $this->doc->makeShortcutIcon('id', implode(',', array_keys($this->MOD_MENU)), $this->MCONF['name']));
                }
                $this->content .= $this->doc->spacer(10);

            // generate empty page (header only) if user has no access or if $this->id is zero/unset
            } else {
                $this->content .= $this->doc->startPage($LANG->getLL('title'));
                $this->content .= $this->doc->header($LANG->getLL('title'));
                $this->content .= $this->doc->spacer(15);
                $this->content .= 'You don\'t have access to this module!';
            }
        } catch (tx_pttools_exception $excObj) {
            // if an exception has been catched, handle it and overwrite module content with error message
            $excObj->handleException();
            $this->content = '<i>'.$excObj->__toString().'</i>';
        }
    }

    /**
     * Puts $this->jsArray to $this->doc->JScode which will be printed in the head of the html page
     *
     * @return	void
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2007-12-10
     */
    protected function printJS() {

        if (count($this->jsArray)) {
            $this->doc->JScode .= chr(10).'<!-- BEGIN: additional javascript from module -->'.chr(10);
            $this->doc->JScode .= implode(chr(10), $this->jsArray);
            $this->doc->JScode .= chr(10).'<!-- END: additional javascript from module -->'.chr(10);
        }
    }

    /**
     * Outputs a search form.
     * The form will be submitted to index.php?action=search by http-post.
     * The field "search_field" contains the query
     *
     * @param 	string		(optional) search string for prefilling the field
     * @param 	string		(optional) field label, default; "Search string"
     * @param 	string		(optional) button label, default: "Search"
     * @return 	string		HTML Output
     * @author 	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since 	2008-01-18
     */
    protected function printSearchForm($searchString='', $fieldLabel='Search string', $buttonLabel='Search') {
        // Search Box:
        $content .= '
        <form action="index.php?action=search" method="post">
            <table border="0" cellpadding="0" cellspacing="0" class="bgColor4" id="typo3-dblist-search">
        		<tr>
        			<td>'.$fieldLabel.'&nbsp;</td>
        			<td><input type="text" name="search_field" value="'.$searchString.'" style="width:96px;" />&nbsp;</td>
        			<td><input type="submit" name="search" value="'.$buttonLabel.'" /></td>
        		</tr>
        	</table>
		</form>';

        return $content;
    }

    /**
     * Sets the HTML/JS code properties of a passed-by-reference object of type template (see file typo3/template.php)
     * TODO: outsource HTML/JSelements into a BE module library?
     *
     * @param       template    object of type template (see file typo3/template.php): passed by reference!
     * @param       string      (optional) path to the CSS file to use for this module (relative path from the module's index.php file)
     * @param       string      (optional) action attribute for the HTML form tag of form property of $docObj - if not set (=default), the form property of $docObj will not be set at all. See template::form.
     * @param       boolean     (optional) flag whether the module containing the $docObj should use the Jump Menu Selector in the TYPO3 backend (default=true). See template::JScode.
     * @param       boolean     (optional) flag whether the module containing the $docObj is a sub-module of main module 'Web' in the TYPO3 backend (default=false). See template::postCode.
     * @return      void
     * @see         template
     * @author      Rainer Kuhn <kuhn@punkt.de> (JavaScript code fragments from TYPO3 kickstarter)
     * @since       2007-08-27
     */
    protected function setDocCodePropertiesHTML($docObj, $cssRelPath='', $formAction='', $useJumpMenu=1, $isWebModule=0) {

        // rk: set individual CSS for backend module
        if (!empty($cssRelPath)) {
            $docObj->JScode .= '
                <link rel="stylesheet" type="text/css" href="'.$cssRelPath.'">
                ';
        }

        // create form open tag if a form action is given (if set, the form close tag will be created by template::endPage())
        if (!empty($formAction)) {
            $docObj->form = '<form action="'.(string)$formAction.'" method="post">';
        }

        // JavaScript for Jump Menu Selector in the TYPO3 backend
        if ($useJumpMenu == 1) {
            $docObj->JScode .= '
                <script language="javascript" type="text/javascript">
                    script_ended = 0;
                    function jumpToUrl(URL) {
                        document.location = URL;
                    }
                </script>
                ';
        }

        // TODO: this seems to be used for sub-modules of main module 'Web' only
        if ($isWebModule == 1) {
            $docObj->postCode .= '
                <script language="javascript" type="text/javascript">
                    script_ended = 1;
                    if (top.fsMod) {
                        top.fsMod.recentIds["web"] = 0;
                    }
                </script>
                ';
        }
    }

    /**
     * Prints out the module's HTML content (done as last action of the module script, see "default module finalization" below)
     *
     * @param       void
     * @return      void
     * @see         t3lib_SCbase: 'EXAMPLE PROTOTYPE'
     * @author      TYPO3 kickstarter ;-)
     * @since       2007-08-24
     */
    public function printContent() {

        $this->content .= $this->doc->endPage();
        echo $this->content;
    }

    /**
     * DUMMY METHOD - THIS METHOD SHOULD BE OVERWRITTEN BY INHERITING CLASS! If you don't want to add a jump menu to your module, just overwrite this method with an empty method...
     * Adds items to the ->MOD_MENU array (used for the function menu selector).
     *
     * @param       boolean     flag whether this dummy method is called from an inheriting child class
     * @return      void
     * @global      $GLOBALS['LANG']
     * @author      Rainer Kuhn <kuhn@punkt.de> (based on code from TYPO3 kickstarter)
     * @since       2007-11-02
     */
    public function menuConfig($calledfromChildClass=false) {

        // output dummy text only if not called from an inheriting child class
        if ($calledfromChildClass == false) {
            $this->MOD_MENU = array(
                'jumpMenuFunction' => array(
                    '1' => __METHOD__.'() should be overwritten by inheriting class!',
                    '2' => __METHOD__.'() should be overwritten by inheriting class!',
                )
            );
        }

        parent::menuConfig();
    }

    /**
     * DUMMY METHOD - THIS METHOD SHOULD BE OVERWRITTEN BY INHERITING CLASS!
     * "Controller": Calls the appropriate action and returns the module's HTML content
     *
     * @param       void
     * @return      string      the module's HTML content
     * @global      $GLOBALS['LANG']
     * @author      Rainer Kuhn <kuhn@punkt.de>
     * @since       2007-11-02
     */
    public function moduleContent() {

        $moduleContent = __METHOD__.'() should be overwritten by inheriting class!';

        return $moduleContent;
    }

    /**
     * Shorthand notation for $GLOBALS['LANG']->getLL(): Returns the LocalLang translation for a given label from the corresponding module locallang file
     *
     * @param       string      label name to translate
     * @return      string      LocalLang translation
     * @global      $GLOBALS['LANG']
     * @author      Rainer Kuhn <kuhn@punkt.de>
     * @since       2007-10-11
     */
    protected function ll($label) {

        return $GLOBALS['LANG']->getLL($label);
    }

} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_beSubmodule.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_beSubmodule.php']);
}

?>