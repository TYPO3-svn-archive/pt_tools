<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>,
*                Fabrizio Branca <branca@punkt.de>
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
 * Abstract object collection class for pt_tools
 *
 * $Id$
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>, Fabrizio Branca <branca@punkt.de>
 * @since       2006-10-24
 */



/**
 * Inclusion of extension specific resources
 */
// a concrete implementation would include the object to create the collection of

/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_collection.php'; // general item collection class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function



/**
 * Abstract object collection class
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>, Rainer Kuhn <kuhn@punkt.de>, Fabrizio Branca <branca@punkt.de>
 * @since       2006-10-24
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_pttools_objectCollection extends tx_pttools_collection {

    /**
     * Properties
     */

    /**
     * @var 	string	if set, added objects will be type checked against this classname - this property should be set by your inheriting class if want to check the object type when adding an item
     */
    protected $restrictedClassName = NULL;



    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/

    /**
     * Checks if the type of an item object matches the restrictedClassName (this property should be set in your inheriting class if want to check the object type when adding an item)
     *
     * @param   mixed       object item to validate
     * @return  boolean     true if object validation suceeded, false otherwise
     * @author  Rainer Kuhn <kuhn@punkt.de>, Fabrizio Branca <branca@punkt.de>
     * @since   2008-10-16
     */
    final protected function checkItemType($itemObj) {
        
        if (!is_null($this->restrictedClassName) && !($itemObj instanceof $this->restrictedClassName)) {
            return false;
        }
        
        return true;
        
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_objectCollection.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_objectCollection.php']);
}

?>
