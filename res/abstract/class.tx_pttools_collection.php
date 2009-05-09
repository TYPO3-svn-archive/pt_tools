<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2008 Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>, 
*                Fabrizio Branca <mail@fabrizio-branca.de>
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
 * Abstract item collection class for pt_tools
 *
 * $Id$
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>, Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2008-10-16 (based on code from former tx_pttools_objectCollection and tx_ptgsashop_*Collection, since 2005)
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/exceptions/class.tx_pttools_exceptionInternal.php'; // internal exception class



/**
 * Abstract item collection class
 *
 * @author      Rainer Kuhn <kuhn@punkt.de>, Wolfgang Zenker <zenker@punkt.de>, Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2008-10-16 (based on code from former tx_pttools_objectCollection and tx_ptgsashop_*Collection, since 2005)
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
abstract class tx_pttools_collection implements IteratorAggregate, Countable, ArrayAccess {

    /**
     * Properties
     */

    /**
     * @var 	array	array containing items as values
     */
    protected $itemsArr = array();

    /**
     * @var 	int		uid of last selected collection item
     */
    protected $selectedId;




    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/

    /**
     * Implement the constructor in your inheriting class if you need one
     */
    
    
    
    /***************************************************************************
     *   IMPLEMENTED METHODS: IteratorAggregate INTERFACE API METHODS
     **************************************************************************/

    /**
     * Defined by IteratorAggregate interface: returns an iterator for the object
     *
     * @param   void
     * @return  ArrayIterator     object of type ArrayIterator: Iterator for items within this collection
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05
     */
    public function getIterator() {

        $itemIterator = new ArrayIterator($this->itemsArr);
        #trace($itemIterator, 0, '$itemIterator');

        return $itemIterator;

    }



    /***************************************************************************
     *   IMPLEMENTED METHODS: Countable INTERFACE API METHODS
     **************************************************************************/

    /**
     * Defined by Countable interface: Returns the number of items
     *
     * @param   void
     * @return  integer     number of items in the items array
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-03-10
     */
    public function count() {

        return count($this->itemsArr);

    }



    /***************************************************************************
     *   IMPLEMENTED METHODS: ArrayAccess INTERFACE API METHODS
     **************************************************************************/

    /**
     * Checks if an offset is in the array
     *
     * @param 	mixed	offset
     * @return 	bool
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-05-29
     */
    public function offsetExists($offset) {

        return $this->hasItem($offset);

    }

    /**
     * Returns the value for a given offset
     *
     * @param 	mixed	offset
     * @return 	mixed	element of the collection
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-05-29
     */
    public function offsetGet($offset) {

        return $this->getItemById($offset);

    }
    
    /**
     * Adds an element to the collection
     *
     * @param 	mixed	offset
     * @param 	mixed	value
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-05-29
     */
    public function offsetSet($offset, $value) {

        $this->addItem($value, $offset);

    }
    
    /**
     * Deletes an element from the collection
     *
     * @param 	mixed	offset
     * @author	Fabrizio Branca <mail@fabrizio-branca.de>
     * @since	2008-05-29
     */
    public function offsetUnset($offset) {

        $this->deleteItem($offset);

    }
    
    

    /***************************************************************************
     *  IMPLEMENTED METHODS: array_{shift|unshift|pop|push|keys} equivalents
     **************************************************************************/
    /*
     * array_{shift|unshift|pop|push|keys} functions do not work on objects
     * (even if - like this - they implement the ArrayAccess Interface)
     * Here are the equivalent methods:
     */

    /**
     * Shift an element off the beginning of the collection
     *
     * @param   bool    (optional) if true key won't be modified, else numerical keys will be renumbered, default if false
     * @return  mixed   item or NULL if collection is empty
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-07
     */
    public function shift($doNotModifyKeys = false) {

        if (empty($this->itemsArr)) {
            return NULL;
        } elseif ($doNotModifyKeys == true) {
            $keys = array_keys($this->itemsArr);
            $element = $this->itemsArr[$keys[0]];
            unset($this->itemsArr[$keys[0]]);
            return $element;
        } else {
            $keys = array_keys($this->itemsArr);
            if ($this->selectedId == $keys[0]) {
                $this->clear_selectedId();
            }
            return array_shift($this->itemsArr);
        }

    }

    /**
     * Pop the element off the end of the collection
     *
     * @param   void
     * @return  mixed   item or NULL if collection is empty
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-07
     */
    public function pop() {

        if (empty($this->itemsArr)) {
            return NULL;
        } else {
            $keys = array_keys($this->itemsArr);
            if ($this->selectedId == $keys[count($this->itemsArr)-1]) {
                $this->clear_selectedId();
            }
            return array_pop($this->itemsArr);
        }

    }

    /**
     * Prepend one or more elements to the beginning of the collection
     * Multiple elements (like in array_unshift) are not supported!
     *
     * @param   mixed   element to prepend
     * @param   bool    (optional) if true key won't be modified, else numerical keys will be renumbered, default if false
     * @return  int     Returns the new number of elements in the collection
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-07
     */
    public function unshift($element, $doNotModifyKeys = false) {
    
        $this->checkItemType($element);
    
        if ($doNotModifyKeys == true) {
            $this->itemsArr = array($element) + $this->itemsArr;
        } else {
            array_unshift($this->itemsArr, $element);
        }
        return $this->count();
    
    }
    
    /**
     * Push one or more elements onto the end of collection
     * Multiple elements (like in array_push) are not supported!
     *
     * @param   mixed   element to append
     * @return  int     Returns the new number of elements in the collection
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-07
     */
    public function push($element) {

        $this->checkItemType($element);
        
        array_push($this->itemsArr, $element);
        return $this->count();
    
    }
    
    /**
     * Return all the ids of this collection
     *
     * @param   mixed   (optional) if specified, then only keys containing these values are returned.
     * @param   bool    (optional) determines if strict comparision (===) should be used during the search.
     * @return  array   Returns an array of all the keys
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-12
     */
    public function keys($search_value = '', $strict = false) {
        
        if ($search_value != '') {
            $result = array_keys($this->itemsArr, $search_value, $strict);
        } else {
            $result = array_keys($this->itemsArr);
        }
        
        return $result;
        
    }



    /***************************************************************************
     *   GENERAL METHODS
     **************************************************************************/

    /**
     * Adds one item to the collection
     *
     * @param	mixed	item to add
     * @param	mixed	(optional) array key
     * @return	void
     * @throws	tx_pttools_exceptionInternal   if item to add to collection is of wrong type
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-16
     */
    public function addItem($itemObj, $id=0) {

        // add item if item type is validated
        if ($this->checkItemType($itemObj) == true) {
            
            if ($id === 0) {
                $this->itemsArr[] = $itemObj;
            } else {
                $this->itemsArr[$id] = $itemObj;
            }
         
        // throw exception if item type is not validated
        } else {
            throw new tx_pttools_exceptionInternal('Item to add to collection is of wrong type');
        }

    }

    /**
     * Deletes one item from the collection
     *
     * @param	mixed	id of item to remove
     * @return	void
     * @throws  tx_pttools_exceptionInternal    if trying to delete invalid id
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05
     */
    public function deleteItem($id) {

        if (isset($this->selectedId) && ($id == $this->selectedId)) {
            $this->clear_selectedId();
        }
        if ($this->hasItem($id)) {
            unset($this->itemsArr[$id]);
        } else {
            throw new tx_pttools_exceptionInternal('Trying to delete invalid id');
        }

    }

    /**
     * Clears all items of the collection
     *
     * @param   void
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-10-05
     */
    public function clearItems() {

        $this->clear_selectedId();
        $this->itemsArr = array();

    }

    /**
     * Checks if item exists in collection
     *
     * @param   mixed   key of item to check for existance
     * @return  boolean item with this key exists
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-05-19
     */
    public function hasItem($id) {

        return array_key_exists($id, $this->itemsArr);

    }

    /**
     * Get item from collection by Id
     *
     * @param   integer     Id of Collection Item
     * @return  mixed       item that has been requested
     * @throws	tx_pttools_exceptionInternal	if requesting an invalid id
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2005-10-18
     */
    public function &getItemById($id) {

        if ($this->hasItem($id)) {
            return $this->itemsArr[$id];
        } else {
            throw new tx_pttools_exceptionInternal(sprintf('Trying to get an invalid id "%s"', $id));
        }

    }

    /**
     * Get item from collection by Index
     *
     * @param   integer     index (position in array) of Collection Item
     * @return  mixed       item that has been requested
     * @remarks index starts with 0 for first element
     * @throws  tx_pttools_exceptionInternal if idx is invalid
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2007-02-26
     */
    public function &getItemByIndex($idx) {

        // check parameters
        $idx = intval($idx);
        if (($idx < 0) || ($idx >= $this->count())) {
            throw new tx_pttools_exceptionInternal('Invalid index');
        }
        $itemArr = array_values($this->itemsArr);

        return $itemArr[$idx];

    }

    /**
     * Checks if the type of an item is allowed for the collection - this method should be overwritren by inheriting classes
     *
     * @param   mixed       $itemObj
     * @return  boolean     true by default in this parent class - individual implementations of this method (in inheriting classes) should return the item validation result as true or false
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-16
     */
    protected function checkItemType($itemObj) {
        
        return true;
        
    }
    
    
    
    /***************************************************************************
     *   PROPERTY GETTER/SETTER METHODS
     **************************************************************************/

    /**
     * Returns the property value
     *
     * @param   void
     * @return  flexible        property value
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-05-19
     */
    public function get_selectedId() {

        return $this->selectedId;

    }

    /**
     * Sets the property value
     *
     * @param   flexible
     * @return  void
     * @throws  tx_pttools_exceptionInternal    when parameter is not a valid item id
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-05-19
     */
    public function set_selectedId($selectedId) {

        if ($this->hasItem($selectedId)) {
            $this->selectedId = $selectedId;
        } else {
            throw new tx_pttools_exceptionInternal('Invalid id to set');
        }

    }

    /**
     * Clears the property value
     *
     * @param   void
     * @return  void
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-05-19
     */
    public function clear_selectedId() {

        unset($this->selectedId);

    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_collection.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/abstract/class.tx_pttools_collection.php']);
}

?>