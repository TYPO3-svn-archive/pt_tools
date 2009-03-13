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
 * Interface for Singleton design pattern (part of the 'pt_tools' extension)
 *
 * $Id: class.tx_pttools_iSingleton.php,v 1.6 2007/10/15 13:19:07 ry37 Exp $
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-27
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Interface for Singleton design pattern - do *not* use abstract singleton class since a Singleton MUST be final (see http://de.php.net/manual/en/language.oop5.patterns.php#67066)!
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-27
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
interface tx_pttools_iSingleton {

    /**
     * Properties to use in implementing class
     */
    #private static $uniqueInstance = NULL; // (object) Singleton unique instance to use in implementing class
    
    /**
     * Constructor - to be implemented as private (or protected) class constructor in implementing class: must not be called directly in order to use getInstance() to get the unique instance of the Singleton object.
     *
     * @see     tx_pttools_iSingleton::getInstance()
     * @since   2005-09-27
     */
    #private function __construct();
    
/*    
    ### EXAMPLE METHOD TEMPLATE ###
    /**
     * Private class constructor (): use getInstance() to get the unique instance of this Singleton object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     *#/
    private function __construct() {
        
    }
*/ 
    
    /**
     * To be implemented as public static method in implementing class: Returns a unique instance of the Singleton object, use this method instead of the private/protected class constructor.
     *
     * @return  object      unique instance of the implementing class object
     * @since   2005-09-27
     */
    public static function getInstance();
    
/*    
    ### EXAMPLE METHOD TEMPLATE ###
    /**
     * Returns a unique instance of the Singleton object. Use this method instead of the private/protected class constructor.
     * @param   void
     * @return  [object_name]      unique instance of the Singleton object
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     *#/
    public static function getInstance() {
        
        if (self::$uniqueInstance === NULL) {
            $className = __CLASS__;
            self::$uniqueInstance = new $className;
        }
        
        return self::$uniqueInstance;
        
    }
*/ 
    
    /**
     * To be implemented as *final* public method to prevent object cloning (using 'clone') of the implementing class, in order to use only the unique instance of the object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-27
     */
    public function __clone();

/*    
    ### EXAMPLE METHOD TEMPLATE ###
    /**
     * Final method to prevent object cloning (using 'clone'), in order to use only the unique instance of the Singleton object.
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-15
     *#/
    public final function __clone() {
        
        trigger_error('Clone is not allowed for '.get_class($this).' (Singleton)', E_USER_ERROR);
        
    }
*/ 
    
    
}



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsashop/res/abstract/class.tx_pttools_iSingleton.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_gsashop/res/abstract/class.tx_pttools_iSingleton.php']);
}

?>