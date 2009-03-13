<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005 Rainer Kuhn (kuhn@punkt.de)
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
 * Web form reload handler (part of the library extension 'pt_tools')
 *
 * $Id: class.tx_pttools_formReloadHandler.php,v 1.7 2008/06/26 21:13:57 ry44 Exp $
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2005-12-01
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_sessionStorageAdapter.php'; // storage adapter for TYPO3 _browser_ sessions



/**
 * Web form reload handler class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-12-01
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_formReloadHandler {
    
    /**
     * Class Constants
     */
    const TOKEN_ARRAY_SESSION_NAME = 'tx_pttools_formReloadHandler_tokenArr'; // (string) name of the session key to store the token array (containing all used tokens)
    const DEFAULT_TOKEN_INPUT_NAME = '__formToken';    // (string) default name for the "hidden" form input that contains the token
    
    
    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/
    
    /**
     * Creates and returns a unique token
     *
     * @param   void
     * @return  string      a 32 character identifier (a 128 bit hex number)
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-12-01
     */
    public static function createToken() {
        
        $token = md5(uniqid(rand(), true));
        return $token;
        
    }
    
    /**
     * Checks if a specified token can be used for a form related action
     *
     * @param   string      token to check (see tx_pttools_formReloadHandler::createToken())
     * @param   boolean     (optional) flag whether the check should be performed without storage of checked token in token array (default: false)
     * @return  boolean     TRUE if the token can be used (has not been used so far), FALSE if token has been used before
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-12-01
     */
    public static function checkToken($token, $checkWithoutStorage=false) {
        
        $sessionTokenArr = tx_pttools_sessionStorageAdapter::getInstance()->read(self::TOKEN_ARRAY_SESSION_NAME);
        $tokenArr = (is_array($sessionTokenArr) ? $sessionTokenArr : array());
        
        if (array_key_exists($token, $tokenArr)) {
            return false; // related action should be blocked
            
        } else {
            // store checked token in token array if not configured contrary
            if ($checkWithoutStorage == false) {
                $tokenArr[$token] = true;
                tx_pttools_sessionStorageAdapter::getInstance()->store(self::TOKEN_ARRAY_SESSION_NAME, $tokenArr);
            }
            return true;  // related action can be processed
        }
        
    }
    
    
    
    /***************************************************************************
     *   PRESENTATION METHODS
     **************************************************************************/
    
    /**
     * Returns an HTML hidden input form field tag with a newly created token as value
     *
     * @param   string      name of the input field, used as HTML 'name' attribute
     * @return  string      HTML hidden input form field tag with a newly created token as value
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-12-01
     */
    public static function returnTokenHiddenInputTag($tokenInputName=self::DEFAULT_TOKEN_INPUT_NAME) {
        
        $formInputTag = '<input type="hidden" name="'.$tokenInputName.'" value="'.self::createToken().'" />';
        return $formInputTag;
        
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formReloadHandler.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formReloadHandler.php']);
}

?>