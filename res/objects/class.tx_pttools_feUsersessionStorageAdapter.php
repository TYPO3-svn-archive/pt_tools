<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2005-2008 Rainer Kuhn (kuhn@punkt.de)
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
 * Session Storage Adapter for TYPO3 FRONTEND _user_ sessions (part of the library extension 'pt_tools')
 *
 * $Id: class.tx_pttools_feUsersessionStorageAdapter.php,v 1.2 2008/11/28 10:43:02 ry37 Exp $
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2007-10-19 (based on tx_pttools_usersessStorageAdapter from 2005-09-23)
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iStorageAdapter.php'; // storage adapter interface
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iSingleton.php'; // interface for Singleton design pattern
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';



/**
 * Session Storage Adapter class for TYPO3 FRONTEND _user_ sessions 
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2007-10-19 (based on tx_pttools_usersessStorageAdapter from 2005-09-23)
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_feUsersessionStorageAdapter implements tx_pttools_iSingleton, tx_pttools_iStorageAdapter {
        
    /**
     * Properties
     */
    private static $uniqueInstance = NULL; // (tx_pttools_feUsersessionStorageAdapter object) Singleton unique instance
    
    
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor: must not be called directly in order to use getInstance() to get the unique instance of the object
     *  
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-26
     */
    private function __construct() {}
    
    /**
     * Returns a unique instance (Singleton) of the object. Use this method instead of the private/protected class constructor.
     *
     * @param   void
     * @return  tx_pttools_feUsersessionStorageAdapter      unique instance of the object (Singleton) 
     * @global     
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-26
     */
    public static function getInstance() {
        
        if (self::$uniqueInstance === NULL) {
            $className = __CLASS__;
            self::$uniqueInstance = new $className;
        }
        
        return self::$uniqueInstance;
        
    }
    
    /**
     * Final method to prevent object cloning (using 'clone'), in order to use only the unique instance of the Singleton object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     */
    public final function __clone() {
        
        trigger_error('Clone is not allowed for '.get_class($this).' (Singleton)', E_USER_ERROR);
        
    }
    
    
    
    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/
    
    /**
     * Gets the value of a key from the TYPO3 FRONTEND user session (if the session value is serialized it will be returned unserialized)
     *
     * @param   string      name of session key to get the value of
     * @return  mixed       associated value from session
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function read($key) { 
        
        tx_pttools_assert::isInstanceOf($GLOBALS['TSFE']->fe_user, 'tslib_feUserAuth', array('message' => 'No valid frontend user found!'));
        
        $val = $GLOBALS['TSFE']->fe_user->getKey('user', $key);
        if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Reading "%s" from FE user session in "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');
        
        if (is_string($val) && unserialize($val) != false) {
            $val = unserialize($val);
        }
        
        return $val;
        
    }
    
    /**
     * Saves a value (objects and arrays will be serialized before) into a session key of the the TYPO3 FRONTEND user session *immediately* (does not wait for complete script execution)
     *
     * @param   string      name of session key to save value into
     * @param   string      value to be saved with session key
     * @return  void
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function store($key, $val) { 
        
        tx_pttools_assert::isInstanceOf($GLOBALS['TSFE']->fe_user, 'tslib_feUserAuth', array('message' => 'No valid frontend user found!'));
        
        if (is_object($val) || is_array($val)) {
            $val = serialize($val);
        }
        
        $GLOBALS['TSFE']->fe_user->setKey('user', $key, $val);
        $GLOBALS['TSFE']->fe_user->userData_change = 1;
        $GLOBALS['TSFE']->fe_user->storeSessionData();
        if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Storing "%s" into FE user session using "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');
        
    }
    
    /**
     * Deletes a session value from the TYPO3 FRONTEND user session *immediately* (does not wait for complete script execution)
     *
     * @param   string      name of session key to delete (array key)
     * @return  void
     * @global  object      $GLOBALS['TSFE']->fe_user: tslib_feUserAuth Object
     * @throws  tx_pttools_exceptionAssertion   if no valid frontend user found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-23
     */
    public function delete($key) { 
        
        tx_pttools_assert::isInstanceOf($GLOBALS['TSFE']->fe_user, 'tslib_feUserAuth', array('message' => 'No valid frontend user found!'));
        
        unset($GLOBALS['TSFE']->fe_user->uc[$key]);
        $GLOBALS['TSFE']->fe_user->userData_change = 1;
        $GLOBALS['TSFE']->fe_user->storeSessionData();
        if (TYPO3_DLOG) t3lib_div::devLog(sprintf('Deleting "%s" from FE user session in "$GLOBALS[\'TSFE\']->fe_user"', $key), 'pt_tools');
        
    }
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_feUsersessionStorageAdapter.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_feUsersessionStorageAdapter.php']);
}

?>