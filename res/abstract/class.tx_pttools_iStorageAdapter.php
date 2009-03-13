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
 * Storage adapter interface (part of the 'pt_tools' extension)
 *
 * $Id: class.tx_pttools_iStorageAdapter.php,v 1.3 2007/09/13 11:45:23 ry37 Exp $
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-23
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Storage adapter interface
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2005-09-23
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
interface tx_pttools_iStorageAdapter {
    
    /**
     * Returns the value of a storage key
     */
    public function read($key);
    
    /**
     * Stores a value into a storage key
     */
    public function store($key, $value);
    
    /**
     * Deletes/unsets a storage key
     */
    public function delete($key);
    
}



?>