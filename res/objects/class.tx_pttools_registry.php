<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Fabrizio Branca (branca@punkt.de)
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
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php';  
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; 
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php';



/**
 * Singleton registry
 * 
 * $Id: class.tx_pttools_registry.php,v 1.4 2008/10/16 11:52:40 ry37 Exp $
 * 
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-05-27
 * @see 	http://www.patternsforphp.com/wiki/Registry
 */
final class tx_pttools_registry extends tx_pttools_collection implements tx_pttools_iSingleton {
	
	
	/**
	 * @var 	tx_pttools_registry	Singleton unique instance
	 */
	private static $uniqueInstance = NULL;
	
	
	/***************************************************************************
	 * Methods for the "tx_pttools_iSingleton" interface
	 **************************************************************************/
	
    /**
     * Returns a unique instance of the Singleton object. Use this method instead of the private/protected class constructor.
     * 
     * @param   void
     * @return  tx_pttools_registry      unique instance of the Singleton object
     * @author 	Fabrizio Branca <branca@punkt.de>
     * @since   2008-05-27
     */
    public static function getInstance() {
        
        if (self::$uniqueInstance === NULL) {
            self::$uniqueInstance = new tx_pttools_registry();
            
	        if ($GLOBALS['TT'] instanceof t3lib_timeTrack) {
            	$GLOBALS['TT']->setTSlogMessage('Creating the registry object', 1);
	        }
        } 
        return self::$uniqueInstance;
        
    }
    
    
    
    /**
     * Final method to prevent object cloning (using 'clone'), in order to use only the unique instance of the Singleton object.
     * 
     * @param   void
     * @return  void
     * @author 	Fabrizio Branca <branca@punkt.de>
     * @since   2008-05-27
     */
    public final function __clone() {
        
        trigger_error('Clone is not allowed for '.get_class($this).' (Singleton)', E_USER_ERROR);
        
    }
    
    
    /***************************************************************************
	 * Overriding tx_pttools_objectCollection Methods
	 **************************************************************************/    


    /**
     * Adds one item to the collection
     *
     * @param	object	object to add
     * @param	mixed	array key / label (use namespaces here to avoid conflicts!)
     * @param	bool	(optional) overwrite existing object, default is false
     * @return	void
     * @throws	tx_pttools_exception	if the given label already exists and overwrite if false
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-06-19
     */
    public function addItem($object, $label, $overwrite = false) {
    	
    	tx_pttools_assert::isNotEmpty($label, array('message' => 'Registry keys cannot be empty!'));
    	
    	if (!$this->hasItem($label) || $overwrite == true) {
    		
    		// add object to the collection
            parent::addItem($object, $label);
        
	        // write to TS log if available
	        if ($GLOBALS['TT'] instanceof t3lib_timeTrack) {
	            $GLOBALS['TT']->setTSlogMessage('[Registry] Adding/Updating "'.$label.'"', 0);
	        }
        } else {
        	throw new tx_pttools_exception('There is already an element stored with the label "'.$label.'" (and overwriting not permitted)!');
        }
    }
    
    
    
    
    /***************************************************************************
	 * Methods for the registry pattern
	 **************************************************************************/
    
    
    /**
     * Registers an object to the registry
     *
     * @param 	mixed	label, use namespaces here to avoid conflicts
     * @param 	mixed 	object
     * @param	bool	(optional) overwrite existing object, default is false
     * @return 	void
     * @throws	tx_pttools_exception	if the given label already exists and overwrite if false
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-05-27
     */
    public function register($label, $object, $overwrite = false) {
    	
    	// swapping $label (id) and $object parameters 
    	$this->addItem($object, $label, $overwrite);
        
    }
    
    
    
    /**
     * Unregisters a label
     *
     * @param 	mixed 	label
     * @throws	tx_pttools_exception 	if the label does not exists (uncaught exception from "deleteItem")
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-05-27
     */
    public function unregister($label) {
       	$this->deleteItem($label);
    }
 
    
    
    /**
     * Gets the object for a given label
     *
     * @param 	mixed	label
     * @return 	mixed	object
     * @throws	tx_pttools_exception 	if the label does not exists (uncaught exception from "getItemById")
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-05-27
     */
    public function get($label) {
    	return $this->getItemById($label);
    }
 
    
    
    /**
     * Checks if the label exists
     *
     * @param 	mixed	label
     * @return 	bool
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-05-27
     */
    public function has($label) {
        return $this->hasItem($label);
    }
    
    
    /***************************************************************************
	 * Magic methods wrappers for registry pattern methods
	 * 
	 * $reg = tx_pttools_registry::getInstance();
	 * $reg->myObject = new SomeObject();
	 * if (isset($reg->myObject)) {
	 * 		// there is a myObject value
	 * } else {
	 * 		// there is not a myObject value
	 * }
	 * $obj = $reg->myObject;
	 * unset($reg->myObject);
	 **************************************************************************/
    
    /**
     * @see 	tx_pttools_registry::register
     */
    public function __set($label, $object) {
    	$this->register($label, $object);
    }
    
    
    
    /**
     * @see 	tx_pttools_registry::unregister
     */
    public function __unset($label) {
        $this->unregister($label);
    }
    
    
    
    /**
     * @see 	tx_pttools_registry::get
     */
    public function __get($label) {
        return $this->get($label);
    }
    
    
    
    /**
     * @see 	tx_pttools_registry::has
     */
    public function __isset($label) {
    	return $this->has($label);
    }
    
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_registry.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_registry.php']);
}

?>