<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Rainer Kuhn (kuhn@punkt.de)
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
 * Interface for templateable classes (part of the 'pt_tools' extension)
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-01-14
 */



/**
 * Interface for templateable classes
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-01-14
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
interface tx_pttools_iTemplateable {
    
    /**
     * Returns the complete array of all template markers for the implementing object 
     * 
     * The implementation of this method should _always_ contain a hook before returning the marker array, e.g.
     * // HOOK: allow multiple hooks to manipulate $markerArray
     * if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['your_extkey']['classname_hooks']['getMarkerArray_Hook'])) {
     *      foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['your_extkey']['classname_hooks']['getMarkerArray_Hook'] as $className) {
     *          $hookObj = t3lib_div::getUserObj($className); // returns an object instance from the given class name
     *          $markerArray = $hookObj->getMarkerArray_Hook($this, $markerArray); // $this is passed as a reference
     *      }
     * }
     * 
     * @param   void
     * @return  array   array of template markers
     */
    public function getMarkerArray();
    
}



?>