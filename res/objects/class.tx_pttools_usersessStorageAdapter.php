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
 * DEPRECATED! This legacy file will be removed in upcoming versions of pt_tools! Use class.tx_pttools_feUsersessionStorageAdapter.php instead.
 * 
 * Session Storage Adapter for TYPO3 FRONTEND _user_ sessions 
 *
 * $Id$
 *
 * @author  Rainer Kuhn <kuhn@punkt.de>
 * @since   2005-09-23
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_feUsersessionStorageAdapter.php'; // storage adapter for TYPO3 FRONTEND _user_ sessions
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iStorageAdapter.php'; // storage adapter interface
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iSingleton.php'; // interface for Singleton design pattern



/** 
 * DEPRECATED! This legacy class will be removed in upcoming versions of pt_tools! Use tx_pttools_feUsersessionStorageAdapter instead.
 * 
 * Session Storage Adapter class for TYPO3 FRONTEND _user_ sessions 
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-23
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_usersessStorageAdapter extends tx_pttools_feUsersessionStorageAdapter implements tx_pttools_iSingleton, tx_pttools_iStorageAdapter {
        
    /**
     * Properties
     */
    private static $uniqueInstance = NULL; // (tx_pttools_usersessStorageAdapter object) Singleton unique instance
    
    
    
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
    private function __construct() {
    }
    
    /**
     * Returns a unique instance (Singleton) of the object. Use this method instead of the private/protected class constructor.
     *
     * @param   void
     * @return  tx_pttools_usersessStorageAdapter      unique instance of the object (Singleton) 
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
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_usersessStorageAdapter.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_usersessStorageAdapter.php']);
}

?>