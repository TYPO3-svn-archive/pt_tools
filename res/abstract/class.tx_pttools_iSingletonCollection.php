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
 * $Id$
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
 * Here is an example of a implementation:
 *
 * <code>
 * class singletonCollection implements iSingletonCollection {
 * 
 *     protected static $iniqueInstances = array();
 *     
 *     public static function getInstanceById($objectId) {
 *             
 *          if (array_key_exists($objectId, self::$uniqueInstances)) {
 *	            return self::$uniqueInstances[$objectId];
 *	        } else {
 *              $instance = new className();
 *	            self::$uniqueInstances[$objectId] = $instance;
 *	            return self::$uniqueInstances[$objectId];
 *	        }
 *	        
 *	    }
 *	
 *	    
 *	    public function __clone() {
 *	        throw new tx_pttools_exceptionInternal('Cannot instantiate static class!');
 *	    }
 *	    
 *	    
 *	    public function __construct() {
 *	        throw new tx_pttools_exceptionInternal('Cannot instantiate static class!');
 *	    }
 *	    
 * }
 * </code>
 *
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
	public static function getInstanceById($objectId);
	
	
	
}

?>