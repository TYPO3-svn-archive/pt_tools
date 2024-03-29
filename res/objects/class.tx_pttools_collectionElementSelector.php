<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Wolfgang Zenker (zenker@punkt.de)
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
 * Collection element selector (part of the library extension 'pt_tools')
 *
 * $Id$
 *
 * @author  Wolfgang Zenker <zenker@punkt.de>
 * @since   2008-05-19
 */ 
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of external resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_debug.php'; // debugging class with trace() function
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // helper class, we use htmlOutput() function



/**
 * Collection element selector class
 *
 * This class is intended for frontend use and provides for the generation
 * and handling of forms that allow to select one element of an object
 * collection for a specific action. The elements of the collection are
 * enumerated in a list where the list elements are generated by a usersupplied
 * method which is called with the element object as parameter.
 * Optional wrapper descriptions can be defined as parameters of _construct()
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>
 * @since       2008-05-19
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_collectionElementSelector {
	
	/**
	 * class-local constants
	 */
	const selectorNameTag = 'tx_pttools_collectionElementSelector_SelectorName';
	const selectorElementId = 'tx_pttools_collectionElementSelector_ElementId';
	const selectorMetaKey = '###tx_pttools_collectionElementSelector_MetaData###';
	const defaultElementWrapper = '###CONTENT###';
	const defaultExtraWrapper = '###CONTENT###';
	const defaultGlobalWrapper = "###ELEMENTS###\n###EXTRA###";
	const templateHidden = '<input type="hidden" name="###PREFIX###[###NAME###]" value="###VALUE###" />';

	/**
	 * Properties
	 */
	protected $plugin;		// (object) plugin that we are working for
	protected $templates;	// (string) templatecollection containing all wrappers
	protected $wrapperDesc;	// (array) description of wrapper templates

	/**
	 * Class Constructor: initializes class properties
	 * 
	 * @param   object	plugin that we are working for, usually $this
	 * @param   string	(optional) html containing all wrapper templates
	 * @param   array	(optional) description listing wrapper template names for all selectors
	 * @return  void
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	 */

	public function __construct($plugin, $templates = '', $wrapperDesc = array()) {
	
		trace('***** Creating new '.__CLASS__.' object. *****');
		
		if (! isset($plugin)) {
			throw new tx_pttools_exception('Parameter error', tx_pttools_exception::EXCP_INTERNAL, __CLASS__.' constructor needs at least $plugin');
		}
		$this->plugin = $plugin;
		$this->templates = $templates;
		$this->wrapperDesc = $wrapperDesc;

	}

	/***************************************************************************
	 *   BUSINESS METHODS
	 **************************************************************************/
	 
	/*
	 * construct Collection Element Selector html
	 *
	 * @param	string	Selector Name
	 * @param	object	Object Collection
	 * @param	string	name of plugin method to generate single element html
	 * @param	string	(optional) name of plugin method to create extra html
	 * @return	string	resulting html output
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	*/
	public function displayCES($selectorName, $objCollection, $elementDisplay, $extraDisplay = '') {
		trace('[CMD] '.__METHOD__);

		// get templates, initalise results
		if (isset($this->wrapperDescs[$selectorName])) {
			$wrapperDesc = $this->wrapperDescs[$selectorName];
		} else {
			$wrapperDesc = array();
		}
		if (isset($wrapperDesc[0])) {
			$globalWrapper = $this->plugin->cObj->getSubpart($this->templates, '###'.$wrapperDesc[0].'###');
		} else {
			$globalWrapper = self::defaultGlobalWrapper;
		}
		if (isset($wrapperDesc[1])) {
			$extraWrapper = $this->plugin->cObj->getSubpart($this->templates, '###'.$wrapperDesc[1].'###');
		} else {
			$extraWrapper = self::defaultExtraWrapper;
		}
		if (isset($wrapperDesc[2])) {
			$elementWrapper = $this->plugin->cObj->getSubpart($this->templates, '###'.$wrapperDesc[2].'###');
		} else {
			$elementWrapper = self::defaultElementWrapper;
		}
		$elementsHtml = '';
		$extraHtml = '';

		// traverse Collection
		foreach ($objCollection as $objectId => $dataObj) {
			$elementRaw = $this->plugin->$elementDisplay($dataObj);
			// add MetaData
			$formMarkerArray = array(
				'###PREFIX###' => $this->plugin->prefixId,
				'###NAME###' => self::selectorNameTag,
				'###VALUE###' => $selectorName,
			);
			$metaSNT = $this->plugin->cObj->substituteMarkerArray(self::templateHidden, $formMarkerArray);
			$formMarkerArray = array(
				'###PREFIX###' => $this->plugin->prefixId,
				'###NAME###' => self::selectorElementId,
				'###VALUE###' => $objectId,
			);
			$metaSEI = $this->plugin->cObj->substituteMarkerArray(self::templateHidden, $formMarkerArray);
			$formMarkerArray = array(
				self::selectorMetaKey => $metaSNT.$metaSEI,
			);
			$elementCooked = $this->plugin->cObj->substituteMarkerArray($elementRaw, $formMarkerArray);
			// wrap and add to result
			$elementsHtml .= $this->plugin->cObj->substituteMarkerArray($elementWrapper, array('###CONTENT###' => $elementCooked));
		}

		// Evaluate extraDisplay
		if ($extraDisplay != '') {
			$extraRaw = $this->plugin->$extraDisplay($objCollection);
			// add MetaData
			$formMarkerArray = array(
				'###PREFIX###' => $this->plugin->prefixId,
				'###NAME###' => self::selectorNameTag,
				'###VALUE###' => $selectorName,
			);
			$metaSNT = $this->plugin->cObj->substituteMarkerArray(self::templateHidden, $formMarkerArray);
			$formMarkerArray = array(
				'###PREFIX###' => $this->plugin->prefixId,
				'###NAME###' => self::selectorElementId,
				'###VALUE###' => '',
			);
			$metaSEI = $this->plugin->cObj->substituteMarkerArray(self::templateHidden, $formMarkerArray);
			$formMarkerArray = array(
				self::selectorMetaKey => $metaSNT.$metaSEI,
			);
			$extraCooked = $this->plugin->cObj->substituteMarkerArray($extraRaw, $formMarkerArray);
			$extraHtml = $this->plugin->cObj->substituteMarkerArray($extraWrapper, array('###CONTENT###' => $extraCooked));
		}

		// Wrap using Global Wrapper
		$formMarkerArray = array(
			'###ELEMENTS###' => $elementsHtml,
			'###EXTRA###' => $extraHtml,
		);

		return $this->plugin->cObj->substituteMarkerArray($globalWrapper, $formMarkerArray);
	}

	/*
	 * check if we were called from a Collection Element Selector form
	 *
	 * @param	void
	 * @return	boolean	we were called from a Collection Element Selector form
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	*/
	public function isCESReturn() {
		trace('[CMD] '.__METHOD__);

		return isset($this->plugin->piVars[self::selectorNameTag]);
	}

	/*
	 * get name of Selector that we were called from
	 *
	 * @param	void
	 * @return	string	name of Collection Element Selector form we were called from
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	*/
	public function getCESName() {
		trace('[CMD] '.__METHOD__);

		return $this->plugin->piVars[self::selectorNameTag];
	}

	/*
	 * get Id of Collection Element that was selected
	 *
	 * @param	object	Object Collection
	 * @return	string	id of Collection Element that was used in submitted form
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	*/
	public function getCESId($objCollection) {
		trace('[CMD] '.__METHOD__);

		$CESId = $this->plugin->piVars[self::selectorElementId];
		if (($CESId != '') && ! ($objCollection->hasItem($CESId))) {
			throw new tx_pttools_exception('Id not in Collection', tx_pttools_exception::EXCP_AUTH);
		}
		return $CESId;
	}

	/*
	 * process input on Collection Element Selector form
	 *
	 * @param	object	Object Collection
	 * @param	string	name of plugin method to process single element input
	 * @param	string	(optional) name of plugin method to process extra input
	 * @return	string	html error message, empty string if no error
	 * @author  Wolfgang Zenker <zenker@punkt.de>
	 * @since   2008-05-19
	*/
	public function processCESInput($objCollection, $elementProcess, $extraProcess = '') {
		trace('[CMD] '.__METHOD__);

		$result = '';
		$selectedId = $this->getCESId($objCollection);
		if ($selectedId != '') {
			// process selected element
			$objCollection->set_selectedId($selectedId);
			$dataObj = $objCollection->getItemById($selectedId);
			$result = $this->plugin->$elementProcess($dataObj);
		} else {
			// process extra data
			$objCollection->clear_selectedId();
			if ($extraProcess != '') {
				$result = $this->plugin->$extraProcess($objCollection);
			}
		}
		return $result;
	}

} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_collectionElementSelector.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_collectionElementSelector.php']);
}

?>
