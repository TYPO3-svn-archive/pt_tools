<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2003-2008 Rainer Kuhn (kuhn@punkt.de)
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
 * Message Box class (part of the library extension 'pt_tools')
 *
 * Provides methods for displaying information and messages in a HTML-Messagebox.
 *
 * $Id$
 *
 * @author   Rainer Kuhn <kuhn@punkt.de> (thanks to Tino Bickel for inspiration :)
 * @since    2005-09-12
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */

require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';

/**
 * Provides a method for displaying user information and error messages in a HTML-Messagebox (could be used for arbitrary output).
 * 
 * The Messagebox uses selectable icons (found in the 'res' directory of this extension), depending on the passed type. 
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-12, made independent from tslib_pibase and removed inline styles, 2008-06-19 by Fabrizio Branca <branca@punkt.de>
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_msgBox {
    
    /***************************************************************************
     * Msgbox type constants: used for icon file names and locallang entities
     **************************************************************************/
    const MSGBOX_TYPE_ERROR = 'error'; // (string)
    const MSGBOX_TYPE_WARNING = 'warning'; // (string)
    const MSGBOX_TYPE_QUESTION = 'question'; // (string)
    const MSGBOX_TYPE_INFO = 'info'; // (string)
    
    
    
    /***************************************************************************
     * General properties
     **************************************************************************/
    
    /**
     * @var string    type of required msgbox (currently allowed types: 'error'|'warning'|'question'|'info')
     */
    protected $type = ''; 
    
    /**
     * @var string    message text to display
     */
    protected $message = '';
    
    /**
     * @var string    headline text for msgbox 
     */
    protected $headline = '';
    
    /**
     * @var string    relative path (originating from htdocs) to the msgbox icon to use
     */
    protected $iconPath = '';
    
    
    
    /***************************************************************************
     * Configuration properties (currently configurable via Constant Editor)
     **************************************************************************/
    
    /**
     * @var string    relative path (originating from htdocs) to html template for messageBox
     */    
    protected $templateFilePath = ''; 
    
    /**
     * @var string    relative path (originating from htdocs) to directory containing the msgbox icons, depending on the passed type (see MSGBOX_TYPE_* constants)
     */
    protected $iconDirPath = '';
    
    
    
    /***************************************************************************
        CONSTRUCTOR
    ***************************************************************************/
    
    /**
     * Class Constructor: sets the properties of a message box using given params and internal setter methods
     * 
     * @param   string      type of required msgbox (currently allowed types: 'error'|'warning'|'question'|'info')
     * @param   string      message text to display
     * @param   string      optional alternative headline text for msgbox (if not set, a default headline depending on type will be used)
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-12
     */
    public function __construct($type, $message, $alternativeHeadline='') {
    
        trace('***** Creating new '.__CLASS__.' object. *****');
        
        // check the msgbox type: set properties for error display on wrong msgbox type
        $typeArr = array(self::MSGBOX_TYPE_ERROR, self::MSGBOX_TYPE_WARNING, self::MSGBOX_TYPE_QUESTION, self::MSGBOX_TYPE_INFO);
        
        tx_pttools_assert::isInArray($type, $typeArr, array('message' => '"'.$type.'" is an invalid message type!'));
        tx_pttools_assert::isObject($GLOBALS['TSFE'], array('message' => 'No TSFE found!'));
        
        // set MsgBox external configuration
        $this->setMsgBoxConfig();
        
        // set MsgBox properties
        $this->type = (string)$type;
        $this->message = (string)$message;
        $this->headline = (string)(!empty($alternativeHeadline) ? $alternativeHeadline : $GLOBALS['TSFE']->sL('LLL:EXT:pt_tools/res/objects/locallang.xml:msgbox_'.$type));
        
        // set icon path
        $this->iconPath = $GLOBALS['TSFE']->absRefPrefix.$GLOBALS['TSFE']->tmpl->getFileName($this->iconDirPath.'msg_'.$this->type.'.gif');
        
    }
    
    
    
    /***************************************************************************
       MSGBOX METHODS
    ***************************************************************************/
    
    /**
     * Returns the HTML string representation of the messagebox
     *
     * @param   void
     * @return  string      HTML formatted msgbox string (or empty string if no message text entered)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-12
     */
    public function __toString() {
        
        return (empty($this->message) ? '' : $this->generateMsgBoxHTML());
        
    }
    
    /**
     * Sets the external configuration properties of a msgbox, currently using data retrieved from TYPO3 Constant Editor
     *
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-12 (original code from 2003-05)
     */
    protected function setMsgBoxConfig() {

        // template file for Message Box 
        $this->templateFilePath = (string)$GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['msgboxTemplate'];
        
        // directory path for MessageBox icons
        $this->iconDirPath = (string)$GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['msgboxIconDir'];
        
    } 
    
    /**
     * Returns a HTML Messagebox string (display box with icon, headline and message) as CSS based HTML layout
     *
     * @param   void
     * @return  string      HTML msgbox string
     * @global  GLOBALS['TSFE']->cObj
     * @author  Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>, Fabrizio Branca <branca@punkt.de>
     * @since   2005-09-15, switched to template based version 2008-04-28, removed inline styles 2008-06-19
     */
    protected function generateMsgBoxHTML() {
 
        $templateFile = $GLOBALS['TSFE']->cObj->fileResource($this->templateFilePath);
        $htmlTemplate = $GLOBALS['TSFE']->cObj->getSubpart($templateFile, '###MSGBOX###');
        
        $formMarkerArray = array();
        $formMarkerArray['###ICONPATH###'] = $this->iconPath;
        $formMarkerArray['###ICONTYPE###'] = $this->type;
        $formMarkerArray['###HEADLINE###'] = $this->headline;
        $formMarkerArray['###MSG###']      = $this->message;
        
        $msgboxString = $GLOBALS['TSFE']->cObj->substituteMarkerArray($htmlTemplate, $formMarkerArray);

        return $msgboxString;
        
    }



} // end class



/*******************************************************************************
    TYPO3 XCLASS INCLUSION (for class extension/overriding)
*******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_msgBox.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_msgBox.php']);
}

?>
