<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Fabrizio Branca (mail@fabrizio-branca.de)
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
 * Interface for objects, whose properties can be set at once by passing an array to a defined method (part of the 'pt_tools' extension)
 *
 * $Id$
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2009-01-22
 */



/**
 * Interface for objects, whose properties can be set at once by passing an array to a defined method 
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2009-01-22
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
interface tx_pttools_iSettableByArray {
    
    /**
     * Set the object's properties by passing an array to it 
     * 
     * @param   array	array of properties ('propertyName' => 'propertyValue');
     * @return  void
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2009-01-22
     */
    public function setPropertiesFromArray(array $dataArray);
    
}



?>