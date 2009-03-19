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
 * Interface for pageable classes (part of the 'pt_tools' extension)
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-01-14
 */



/**
 * Interface for pageable classes: usually a collection of objects that can be splitted up into pages (using a pager)
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-01-14
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
interface tx_pttools_iPageable {
    
    /**
     * Returns the number count of total items
     * 
     * @return  integer     number of total items
     */
    public function getTotalItemCount();
    
    /**
     * Returns the items for the given params
     * 
     * @param   string  (optional) SQL snippet of LIMIT clause to use
     * @return  tx_pttools_collection   iterable item collection (should implement tx_pttools_iTemplateable)
     */
    public function getItems($limit='');
    
}



?>