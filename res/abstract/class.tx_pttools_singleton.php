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
 * OBSOLETE/DEPRECATED! - should not be used anymore since a Singleton MUST be final (see http://de.php.net/manual/en/language.oop5.patterns.php#67066).
 * This legacy file will be removed in upcoming versions of pt_tools! 
 * 
 * Abstract class for Singleton design pattern (part of the 'pt_tools' extension). 
 *
 * $Id: class.tx_pttools_singleton.php,v 1.7 2008/03/14 16:27:02 ry37 Exp $
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-15
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * DEPRECATED! - should not be used anymore since a Singleton MUST be final (see http://de.php.net/manual/en/language.oop5.patterns.php#67066)
 * This legacy file will be removed in upcoming versions of pt_tools! 
 * 
 * Abstract class for Singleton design pattern. 
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-15
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_pttools_singleton {
     // OBSOLETE/DEPRECATED! - should not be used anymore since a Singleton MUST be final (see http://de.php.net/manual/en/language.oop5.patterns.php#67066)
     
    /**
     * Properties to use in inheriting class
     */
    #private static $uniqueInstance = NULL; // (object) Singleton unique instance to use in inheriting class
    
    
    
    /***************************************************************************
     *   ABSTRACT METHODS
     **************************************************************************/
    
    /**
     * To be implemented as private or protected class constructor in inheriting class: must not be called directly in order to use getInstance() to get the unique instance of the object.
     *
     * @see     tx_pttools_singleton::getInstance()
     * @since   2005-09-15
     */
    #abstract private function __construct(); // commented out since abstract private methods are no longer allowed since PHP 5.0.5
    
    /**
     * To be implemented as public static method in inheriting class: Returns a unique instance of the inheriting class object, use this method instead of the private/protected class constructor.
     *
     * @return  object      unique instance of the inheriting class object
     * @since   2005-09-15
     */
    abstract public static function getInstance();
    
    
    
    /***************************************************************************
     *   IMPLEMENTED METHODS
     **************************************************************************/
    
    /**
     * Final method to prevent object cloning (using 'clone') of the inheriting class, in order to use only the singleton unique instance of the object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     */
    public final function __clone() {
        
        trigger_error('Clone is not allowed for '.get_class($this).' (Singleton)', E_USER_ERROR);
        
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsashop/res/abstract/class.tx_pttools_singleton.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsashop/res/abstract/class.tx_pttools_singleton.php']);
}

?>