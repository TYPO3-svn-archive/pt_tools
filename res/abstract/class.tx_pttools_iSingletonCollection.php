<?php 

/***************************************************************
*  Copyright notice
*  
*  (c) 2005-2009 Michael Knoll (knoll@punkt.de)
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
 * Interface definition file for a Singleton Collection interface
 *
 * $Id: class.tx_pttools_iSingletonCollection.php,v 1.2 2009/03/11 09:48:44 ry21 Exp $
 *
 * @author  Michael Knoll <knoll@punkt.de>
 * @since   2009-03-09
 */ 

/**
 * Interface for Singleton Collection.
 * 
 * Singleton Collections are collections of objects that should be instantiated only once
 * and can be referenced by an ID
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage tx_pttools
 * @since 2009-03-09
 *
 */
interface tx_pttools_iSingletonCollection {
	
	
	
	/**
	 * Returns a object for a given ID
	 * 
	 * @param 	$objectId	ID of object to be returned from collection
	 * @return 	mixed		Object referenced by given objectId
	 * @author	Michael Knoll <knoll@punkt.de>
	 * @since	2009-03-09
	 */
	public function getInstanceById($objectId);
	
	
	
}

?>