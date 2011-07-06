<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2006-2008 Wolfgang Zenker (zenker@punkt.de)
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
 * Web form template handler (part of the library extension 'pt_tools')
 *
 * @author  Wolfgang Zenker <zenker@punkt.de>
 * @since   2006-04-20
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
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_formchecker.php'; // library class with form check methods
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php';


/**
 * Web form template handler class
 *
 * This class is intended for frontend use and provides handling of
 * forms, that relate directly to one single data object.
 * Fields in forms are filled in by calling dataobject->get_fieldname()
 * and input is returned to the object with dataobject->set_fieldname(value)
 * Fields and form sections are labeled with appropriate tags from locallang.php
 * Form descriptions are handed down in a configuration array to _construct()
 * Input processing uses pttools_formchecker to verify input data.
 *
 * @author      Wolfgang Zenker <zenker@punkt.de>
 * @since       2006-04-20
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_formTemplateHandler {
    
	/**
	 * class-local constants
	 */
    const CHECKPWPREFIX = 'tx_pttools_formTemplateHandler_pwcheckfield_';

    
	/**
	 * Properties
	 */
	private $plugin;		// plugin that we are working for
	private $formDesc;		// description of forms handled by this object
	private $prefixId;		// plugin prefix Id
	private $tmplInHidden;	// template for hidden input field
	private $tmplInTextArea;	// template for text area input
	private $tmplDispTextArea;	// template for text area display
	private $tmplInText;	// template for text input
	private $tmplDispText;	// template for text display
	private $tmplInPasswd;	// template for passwd input
	private $tmplDispPasswd;	// template for passwd display
	private $tmplInSelect;	// template for selection box
	private $tmplInMultiSelect;	// template for selection box
	private $tmplInCombo;	// template for Combo Box
	private $tmplInRadio;	// template for Radio Buttons
	private $tmplInButton;	// template for Submit/Reset/Klick Buttons
	private $tmplInCheckbox;	// template for single element Check boxes
	private $tmplInSplitDate;	// template for SplitDate input
	private $tmplDispSplitDate;	// template for SplitDate display
	private $tmplLabelField;	// template for creating field labels
	private $tmplLabelDisplay;	// template for creating display labels
	private $tmplMarkRequired;	// template for marking required fields
	private $tmplMarkNotRequired;	// template for marking not required fields
	private $tmplMarkHelp;	// template for help marker (and function)
	private $tmplMarkNoHelp;	// template for empty help marker
	private $tmplSelectOptionEmpty;	// template for empty select option
	private $tmplSelectOption;	// template for select option
	private $tmplRadioOption;	// template for single radiobutton input
	private $tmplCheckboxOption;	// template for single checkbox input
	private $tmplSectionStart;	// template for start of labeled fieldset
	private $tmplSectionEnd;	// template for end of labeled fieldset
	private $tmplTextArrayWrapper = NULL;	// optional wrapper for textArray element

    /**
     * Class Constructor: initializes class properties
     * 
     * @param   object	plugin that we are working for, usually $this
     * @param   array	configuration data for all forms handled by this object
     * @param   string	(optional) path to file containing the form element templates
     * @return  void
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */

	public function __construct($plugin, $formDesc, $templateFilePath = '') {
    
        trace('***** Creating new '.__CLASS__.' object. *****');
		
        if (!(isset($plugin) && isset($formDesc))) {
            throw new tx_pttools_exception('Parameter error', 3, __CLASS__.' constructor needs at least $plugin and $formDesc');
        }
		$this->plugin = $plugin;
		$this->formDesc = $formDesc;
		$this->prefixId = $plugin->prefixId;

		$cssFile = (string)$GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['fthCssFilePath'];
		if ($cssFile) {
			$cssPath = $GLOBALS['TSFE']->absRefPrefix.$GLOBALS['TSFE']->tmpl->getFileName($cssFile);
			$GLOBALS['TSFE']->additionalHeaderData['tx_pttools_formTemplateHandler_css'] = '<link rel="stylesheet" type="text/css" href="'.$cssPath.'" />';
		}
		$jsHelper = (string)$GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['fthJsHelperFilePath'];
		if ($jsHelper) {
			$jsPath = $GLOBALS['TSFE']->absRefPrefix.$GLOBALS['TSFE']->tmpl->getFileName($jsHelper);
			$GLOBALS['TSFE']->additionalHeaderData['tx_pttools_formTemplateHandler_js'] = '<script type="text/javascript" src="'.$jsPath.'"> </script>';
		}
		if (! $templateFilePath) {
			$templateFilePath = (string)$GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.']['fthElementTemplatesFilePath'];
		}

        $templateFile = $plugin->cObj->fileResource($templateFilePath);
        $this->tmplInTextArea = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTTEXTAREA###');
        $this->tmplDispTextArea = $plugin->cObj->getSubpart($templateFile, '###FORMDISPLAYTEXTAREA###');
        $this->tmplInText = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTTEXT###');
        $this->tmplDispText = $plugin->cObj->getSubpart($templateFile, '###FORMDISPLAYTEXT###');
        $this->tmplInPasswd = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTPASSWD###');
        $this->tmplDispPasswd = $plugin->cObj->getSubpart($templateFile, '###FORMDISPLAYPASSWD###');
        $this->tmplInSelect = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTSELECT###');
        $this->tmplInMultiSelect = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTMULTISELECT###');
        $this->tmplInCombo = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTCOMBO###');
        $this->tmplInRadio = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTRADIO###');
        $this->tmplInCheckbox = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTCHECKBOX###');
        $this->tmplInHidden = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTHIDDEN###');
        $this->tmplInButton = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTBUTTON###');
        $this->tmplInSplitDate = $plugin->cObj->getSubpart($templateFile, '###FORMINPUTSPLITDATE###');
        $this->tmplDispSplitDate = $plugin->cObj->getSubpart($templateFile, '###FORMDISPLAYSPLITDATE###');
        $this->tmplLabelField = $plugin->cObj->getSubpart($templateFile, '###LABELFIELD###');
        $this->tmplLabelDisplay = $plugin->cObj->getSubpart($templateFile, '###LABELDISPLAY###');
        $this->tmplMarkRequired = $plugin->cObj->getSubpart($templateFile, '###MARKREQUIRED###');
        $this->tmplMarkNotRequired = $plugin->cObj->getSubpart($templateFile, '###MARKNOTREQUIRED###');
        $this->tmplMarkHelp = $plugin->cObj->getSubpart($templateFile, '###MARKHELP###');
        $this->tmplMarkNoHelp = $plugin->cObj->getSubpart($templateFile, '###MARKNOHELP###');
        $this->tmplSelectOptionEmpty = $plugin->cObj->getSubpart($templateFile, '###SELECTOPTIONEMPTY###');
        $this->tmplSelectOption = $plugin->cObj->getSubpart($templateFile, '###SELECTOPTION###');
        $this->tmplRadioOption = $plugin->cObj->getSubpart($templateFile, '###RADIOOPTION###');
        $this->tmplCheckboxOption = $plugin->cObj->getSubpart($templateFile, '###CHECKBOXOPTION###');
        $this->tmplSectionStart = $plugin->cObj->getSubpart($templateFile, '###SECTIONSTART###');
        $this->tmplSectionEnd = $plugin->cObj->getSubpart($templateFile, '###SECTIONEND###');

	}

    /***************************************************************************
       helper functions
    ***************************************************************************/

    /**
     * strlen: check length of string in characters
     * 
     * @param   string	string to get the length of
     * @return  int		length of string in characters (maybe != bytes!)
     * @throws	tx_pttools_exception	if function mb_strlen does not exist
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-04-24
     */

    private function strlen($str) {
		if (tx_pttools_div::getSiteCharsetEncoding() == 'utf-8') {
			// use multibyte function
			if (!function_exists('mb_strlen')) {
				throw new tx_pttools_exception('Function "mb_strlen" not found');
			}
			$result = mb_strlen($str);
		} else {
			// just count bytes
			$result = strlen($str);
		}
		return $result;
	}

	/**
	 * Build a choice array from locallang
	 *
	 * @param   string	field name
	 * @param   boolean	(optional) use choice key (else only choice values)
	 * @return	array	choices for use in select etc.
	 * @since	2007-03-08
	*/
	private function ll_choices($ilabel, $usekeys = true) {
		trace('[CMD] '.__METHOD__);

		$cnt = intval($this->plugin->pi_getLL('fc_'.$ilabel.'_count'));
		$choices = array();
		for ($i = 1; $i <= $cnt; $i++) {
			$cval = $this->plugin->pi_getLL('fc_'.$ilabel.'_val'.$i, $this->plugin->pi_getLL('fc_'.$ilabel.'_'.$i, '[fc_'.$ilabel.'_val'.$i.']'));
			if ($usekeys) {
				$ckey = $this->plugin->pi_getLL('fc_'.$ilabel.'_key'.$i, $this->plugin->pi_getLL('fc_'.$ilabel.'_'.$i, '[fc_'.$ilabel.'_key'.$i.']'));
				$choices[$ckey] = $cval;
			}
			else {
				$choices[] = $cval;
			}
		}
		trace($choices);
		return $choices;
	}

    /**
     * labelField: create HTML for input field label
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   boolean	(optional) field is required
     * @param   string	(optional) help text for field
     * @return  string	HTML Code for field label
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-10
     */

    private function labelField($fname, $flabel, $frequired = false, $fhelp = '') {
		$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABELTEXT###'] = tx_pttools_div::htmlOutput($flabel);
        $fieldMarkerArray['###REQUIREDMARKER###'] = $frequired ? $this->tmplMarkRequired : $this->tmplMarkNotRequired;
        $fieldMarkerArray['###HELPMARKER###'] = $this->helpMark($fname, $fhelp);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplLabelField, $fieldMarkerArray);
	}

    /**
     * labelDisplay: create HTML for display field label
     * 
     * @param   string	name of display field
     * @param   string	label text for display field
     * @return  string	HTML Code for field label
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-10
     */

    private function labelDisplay($flabel) {
		$fieldMarkerArray['###FIELDLABELTEXT###'] = tx_pttools_div::htmlOutput($flabel);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplLabelDisplay, $fieldMarkerArray);
	}

    /**
     * selectOption: create HTML for select option field
     * 
     * @param   string	value for this option
     * @param   string	visible text for this option
     * @param   boolean	(optional) option is selected
     * @param   boolean	(optional) option is disabled
     * @return  string	HTML Code for option field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-12
     */

    private function selectOption($optvalue, $opttext, $optSelected = false, $optDisabled = false) {
		$fieldMarkerArray['###SELOPTVALUE###'] = tx_pttools_div::htmlOutput($optvalue);
		$fieldMarkerArray['###SELOPTTEXT###'] = tx_pttools_div::htmlOutput($opttext);
		$fieldMarkerArray['###SELOPTSELECTED###'] = $optSelected ? 'selected="selected"' : '';
		$fieldMarkerArray['###SELOPTDISABLED###'] = $optDisabled ? 'disabled="disabled"' : '';

        return $this->plugin->cObj->substituteMarkerArray($this->tmplSelectOption, $fieldMarkerArray);
	}

    /**
     * radioOption: create HTML for single radiobutton field
     * 
     * @param   string	name of input field
     * @param   string	value for this option
     * @param   string	visible text for this option
     * @param   boolean	(optional) option is selected
     * @param   boolean	(optional) option is disabled
     * @param   string	(optional) text for JavaScript eventHandler(s)
     * @return  string	HTML Code for option field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-12
     */

    private function radioOption($fname, $optvalue, $opttext, $optSelected = false, $optDisabled = false, $jscript = '') {
		$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
		$fieldMarkerArray['###RADOPTVALUE###'] = tx_pttools_div::htmlOutput($optvalue);
		$fieldMarkerArray['###RADOPTTEXT###'] = tx_pttools_div::htmlOutput($opttext);
		$fieldMarkerArray['###RADOPTSELECTED###'] = $optSelected ? 'checked="checked"' : '';
		$fieldMarkerArray['###RADOPTDISABLED###'] = $optDisabled ? 'disabled="disabled"' : '';
		$fieldMarkerArray['###RADOPTSCRIPT###'] = $jscript;

        return $this->plugin->cObj->substituteMarkerArray($this->tmplRadioOption, $fieldMarkerArray);
	}

    /**
     * checkboxOption: create HTML for single checkbox field
     * 
     * @param   string	name of input field
     * @param   string	value for this option
     * @param   string	visible text for this option
     * @param   boolean	(optional) option is selected
     * @param   boolean	(optional) option is disabled
	 * @param	boolean	(optional) is part of multi element checkbox
	 * @param	string	(optional) JavaScript eventHandler(s)
     * @return  string	HTML Code for option field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-12
     */

    private function checkboxOption($fname, $optvalue, $opttext, $optSelected = false, $optDisabled = false, $isMulti = false, $jscript = '') {
		if ($isMulti) {
			$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.'][]';
		}
		else {
			$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
		}
		$fieldMarkerArray['###CHECKOPTVALUE###'] = tx_pttools_div::htmlOutput($optvalue);
		$fieldMarkerArray['###CHECKOPTTEXT###'] = tx_pttools_div::htmlOutput($opttext);
		$fieldMarkerArray['###CHECKOPTSELECTED###'] = $optSelected ? 'checked="checked"' : '';
		$fieldMarkerArray['###CHECKOPTDISABLED###'] = $optDisabled ? 'disabled="disabled"' : '';
		$fieldMarkerArray['###CHECKOPTSCRIPT###'] = $jscript;

        return $this->plugin->cObj->substituteMarkerArray($this->tmplCheckboxOption, $fieldMarkerArray);
	}

    /**
     * helpMark: create HTML for field help Marker
     * 
     * @param   string	name of input field
     * @param   string	(optional) field help text
     * @return  string	HTML Code for Field Marker
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-17
     */

    private function helpMark($fname, $fhelp = '') {
		$fieldMarkerArray['###HELPFIELDNAME###'] = $this->prefixId.'['.$fname.']';
		$fieldMarkerArray['###HELPTEXT###'] = tx_pttools_div::htmlOutput($fhelp);

        return $this->plugin->cObj->substituteMarkerArray($fhelp == '' ? $this->tmplMarkNoHelp : $this->tmplMarkHelp, $fieldMarkerArray);
	}

    /***************************************************************************
       low level functions: build a single form element
    ***************************************************************************/

    /**
     * substInHidden: perform substitutions for hidden input field
     * 
     * @param   string	name of input field
     * @param   string	(optional) value of input field
     * @return  string	HTML Code for single hidden field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */

    public function substInHidden($fname, $fvalue = '') {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInHidden, $fieldMarkerArray);
    }

    /**
     * substInTextArea: perform substitutions for textarea input field
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   string	(optional) value of input field
     * @param   integer	(optional) number of rows for field
     * @param   integer	(optional) number of columns for field
     * @param   bool	(optional) field is required
     * @param   string	(optional) type of expected input (-> formchecker)
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for single text input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-02
     */	
    public function substInTextArea($fname, $flabel, $fvalue = '', $frows = 5, $fcols = 30, $frequired = false, $ftype = 'Text', $fdisabled = false, $fhelp = '', $fscript = array()) {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        $fieldMarkerArray['###FIELDROWS###'] = intval($frows);
        $fieldMarkerArray['###FIELDCOLS###'] = intval($fcols);
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'readonly="readonly"' : '';
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInTextArea, $fieldMarkerArray);
    }

    /**
     * substDispTextArea: perform substitutions for textarea display field
     * 
     * @param   string	label text for display field
     * @param   string	(optional) value of display field
     * @return  string	HTML Code for single textarea display field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-02
     */	
    public function substDispTextArea($flabel, $fvalue = '') {
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelDisplay($flabel);
        $fieldMarkerArray['###FIELDVALUE###'] = nl2br(tx_pttools_div::htmlOutput($fvalue));
        return $this->plugin->cObj->substituteMarkerArray($this->tmplDispTextArea, $fieldMarkerArray);
    }

    /**
     * substInText: perform substitutions for text input field
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   string	(optional) value of input field
     * @param   integer	(optional) field size
     * @param   integer	(optional) maximum input length
     * @param   bool	(optional) field is required
     * @param   string	(optional) type of expected input (-> formchecker)
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for single text input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */	
    public function substInText($fname, $flabel, $fvalue = '', $fsize = 30, $flen = 80, $frequired = false, $ftype = 'Text', $fdisabled = false, $fhelp = '', $fscript = array()) {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        $fieldMarkerArray['###FIELDSIZE###'] = intval($fsize);
        $fieldMarkerArray['###FIELDLENGTH###'] = intval($flen);
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'disabled="disabled"' : '';
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInText, $fieldMarkerArray);
    }

    /**
     * substDispText: perform substitutions for text display field
     * 
     * @param   string	label text for display field
     * @param   string	(optional) value of input field
     * @param   integer	(optional) display field length
     * @return  string	HTML Code for single text display field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-09-23
     */	
    public function substDispText($flabel, $fvalue = '', $fsize = 30) {
		$fvalue = substr($fvalue, 0, intval($fsize));
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelDisplay($flabel);
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplDispText, $fieldMarkerArray);
    }

    /**
     * substInPasswd: perform substitutions for password input field
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   string	(optional) label text for check input field
     * @param   string	(optional) value of input field
     * @param   integer	(optional) field size
     * @param   integer	(optional) maximum input length
     * @param   bool	(optional) field is required
     * @param   string	(optional) type of expected input (-> formchecker)
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   string	(optional) help text for check input field
     * @param   array	(optional) javascript for onEvent Handlers, bound to verification field only!
     * @return  string	HTML Code for two password inputs (should match)
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-09-23
     */	
    public function substInPasswd($fname, $flabel, $checklabel = '', $fvalue = '', $fsize = 30, $flen = 80, $frequired = false, $ftype = 'Text', $fdisabled = false, $fhelp = '', $checkhelp = '', $fscript = array()) {
		$checkfname = self::CHECKPWPREFIX.$fname;
		$checklabel = tx_pttools_div::htmlOutput($checklabel);
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###CHECKFIELDNAME###'] = $this->prefixId.'['.$checkfname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###CHECKFIELDLABEL###'] = $this->labelField($checkfname, $checklabel, $frequired, $checkhelp);
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        $fieldMarkerArray['###FIELDSIZE###'] = intval($fsize);
        $fieldMarkerArray['###FIELDLENGTH###'] = intval($flen);
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'disabled="disabled"' : '';
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInPasswd, $fieldMarkerArray);
    }

    /**
     * substDispPasswd: perform substitutions for password display field
     * 
     * @param   string	label text for display field
     * @param   string	(optional) value of display field
     * @param   integer	(optional) display field length
     * @return  string	HTML Code for single passwd display field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-09-23
     */	
    public function substDispPasswd($flabel, $fvalue = '', $fsize = 30) {
		$fsize = intval($fsize);
		$i = $this->strlen($fvalue);
		if ($i > $fsize) {
			$i = $fsize;
		}
		$fvalue = '';
		for (; $i > 0; $i--) {
			$fvalue .= '*';
		}
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelDisplay($flabel);
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplDispPasswd, $fieldMarkerArray);
    }

    /**
     * substInSelect: perform substitutions for select input field
     * 
     * @param   string	name of select field
     * @param   string	label text for select field
     * @param   integer	number of selections to display
     * @param   bool	add empty selection at beginning
     * @param   array	possible selections as key => value pairs
	 * @param	string	(optional) key of preselected field
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) list of disabled options
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for single select input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */ 
    public function substInSelect($fname, $flabel, $fssize, $femptyok, $fchoicearray, $fvalue = '', $frequired = false, $fdisabled = false, $fhelp = '', $disoptarray = array(), $fscript = array()) {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###FIELDMULTIOK###'] = '';
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'disabled="disabled"' : '';
        $fieldMarkerArray['###FIELDSSIZE###'] = intval($fssize);
        $optString = $femptyok ? $this->tmplSelectOptionEmpty : '';
        foreach ($fchoicearray as $value => $text) {
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
            $optString .= $this->selectOption($value, $text, $value == $fvalue, in_array($value, $disoptarray));
        }
        $fieldMarkerArray['###FIELDOPTIONS###'] = $optString;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInSelect, $fieldMarkerArray);
    }

    /**
     * substInMultiSelect: perform substitutions for multiselect input field
     * 
     * @param   string	name of select field
     * @param   string	label text for select field
     * @param   integer	number of selections to display
     * @param   bool	add empty selection at beginning
     * @param   array	possible selections as key => value pairs
	 * @param	array	keys of preselected fields
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) list of disabled options
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for single multiselect input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */ 
    public function substInMultiSelect($fname, $flabel, $fssize, $femptyok, $fchoicearray, $fvalues = array(), $frequired = false, $fdisabled = false, $fhelp = '', $disoptarray = array(), $fscript = array()) {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.'][]';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###FIELDMULTIOK###'] = 'multiple="multiple"';
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'disabled="disabled"' : '';
        $fieldMarkerArray['###FIELDSSIZE###'] = intval($fssize);
        $optString = $femptyok ? $this->tmplSelectOptionEmpty : '';
        foreach ($fchoicearray as $value => $text) {
            $optString .= $this->selectOption($value, $text, in_array($value, $fvalues), in_array($value, $disoptarray));
        }
        $fieldMarkerArray['###FIELDOPTIONS###'] = $optString;
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInMultiSelect, $fieldMarkerArray);
    }

    /**
     * substInCombo: perform substitutions for text input combo field
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   bool	add empty field at beginning of choices
	 * @param	array	list of suggested field inputs
	 * @param	strin	(optional) intro for suggestions in <noscript> version
     * @param   string	(optional) value of input field
     * @param   integer	(optional) field size
     * @param   integer	(optional) maximum input length
     * @param   bool	(optional) field is required
     * @param   string	(optional) type of expected input (-> formchecker)
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) javascript for onEvent Handlers, bound to the text input field only!
     * @return  string	HTML Code for single text input field with suggestion pulldown
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
     */ 
    public function substInCombo($fname, $flabel, $femptyok, $choiceArray = array(), $fexintro = '', $fvalue = '', $fsize = 30, $flen = 80, $frequired = false, $ftype = 'Text', $fdisabled = false, $fhelp = '', $fscript = array()) {
		if ($fdisabled) {
			// treat as disabled text input
			return $this->substInText($fname, $flabel, $fvalue, $fsize, $flen, $frequired, $ftype, $fdisabled);
		}
		$fchoices = '';
		foreach ($choiceArray as $choice) {
			if ($fchoices == '') {
				$fchoices .= '"';
			}
			else {
				$fchoices .= '","';
			}
			$fchoices .= tx_pttools_div::htmlOutput($choice);
		}
		$fchoices .= '"';
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDID###'] = $fname;
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###FIELDEMPTYOK###'] = $femptyok ? 'true' : 'false';
        $fieldMarkerArray['###FIELDCHOICES###'] = $fchoices;
        $fieldMarkerArray['###FIELDVALUE###'] = tx_pttools_div::htmlOutput($fvalue);
        $fieldMarkerArray['###FIELDSIZE###'] = intval($fsize);
        $fieldMarkerArray['###FIELDLENGTH###'] = intval($flen);
        $fieldMarkerArray['###FIELDEXINTRO###'] = tx_pttools_div::htmlOutput($fexintro);
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInCombo, $fieldMarkerArray);
    }

    /**
     * substInRadio: perform substitutions for radio buttons
     * 
     * @param   string	name of radio button
     * @param   string	label text for radio buttons
     * @param   array	button values as key => value pairs
	 * @param	string	(optional) key of preselected field
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) list of disabled options
     * @param   array	(optional) javascript for onEvent Handlers, bound to all input elements individualy
     * @return  string	HTML Code for radio button input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-05-19
     */ 
    public function substInRadio($fname, $flabel, $fchoicearray, $fvalue = '', $frequired = false, $fdisabled = false, $fhelp = '', $disoptarray = array(), $fscript = array()) {
        $fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$optString = '';
        foreach ($fchoicearray as $value => $text) {
            $optString .= $this->radioOption($fname, $value, $text, $value == $fvalue, $fdisabled || in_array($value, $disoptarray), $jscript);
		}
        $fieldMarkerArray['###FIELDOPTIONS###'] = $optString;
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInRadio, $fieldMarkerArray);
    }

    /**
     * substInCheckbox: perform substitutions for single checkbox
     * 
     * @param   string	name of checkbox
     * @param   string	label text for checkbox
     * @param   string	visible text for box
	 * @param	bool	(optional) box is checked
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for single checkbox input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-05-19
     */ 
    public function substInCheckbox($fname, $flabel, $ftext = '', $fvalue = false, $frequired = false, $fdisabled = false, $fhelp = '', $fscript = array()) {
		$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$optString = $this->checkboxOption($fname, 1, $ftext, $fvalue, $fdisabled, false, $jscript);
		$fieldMarkerArray['###FIELDOPTIONS###'] = $optString;
		return $this->plugin->cObj->substituteMarkerArray($this->tmplInCheckbox, $fieldMarkerArray);
    }

    /**
     * substInMultiCheckbox: perform substitutions for multi-element checkboxes
     * 
     * @param   string	name of checkbox
     * @param   string	label text for checkbox
     * @param   array	value => display text pairs for checkboxes
	 * @param	array	current field values
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) list of disabled options
     * @param   array	(optional) javascript for onEvent Handlers, bound to all input elements individualy
     * @return  string	HTML Code for multi element checkbox input fields
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-15
     */ 
    public function substInMultiCheckbox($fname, $flabel, $choiceArray = array(), $fvalues = array(), $frequired = false, $fdisabled = false, $fhelp = '', $disoptarray = array(), $fscript = array()) {
		$fieldMarkerArray['###FIELDNAME###'] = $this->prefixId.'['.$fname.']';
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$optString = '';
		foreach ($choiceArray as $value => $text) {
			$optString .= $this->checkboxOption($fname, $value, $text, in_array($value, $fvalues), $fdisabled || in_array($value, $disoptarray), true, $jscript);
		}
		$fieldMarkerArray['###FIELDOPTIONS###'] = $optString;
		return $this->plugin->cObj->substituteMarkerArray($this->tmplInCheckbox, $fieldMarkerArray);
    }

    /**
     * substInButton: perform substitutions for buttons
     * 
     * @param   string	name of button
     * @param   string	text for button
     * @param   string	(optional) type of button
     * @param   string	(optional) image src for button
     * @param   array	(optional) javascript for onEvent Handlers
     * @return  string	HTML Code for button input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-10-16
     */ 
    public function substInButton($fname, $ftext, $ftype = 'submit', $fsrc = '', $fscript = array()) {
		$fieldMarkerArray['###BUTTONNAME###'] = $this->prefixId.'['.$fname.']';
		$textparm = 'value';
		switch ($ftype) {
			case 'click':
				$ftype = 'button';
			case 'submit':
			case 'reset':
				$fsrc = '';
				break;
			case 'image':
				$textparm = 'alt';
				break;
			default:
				$ftype = 'unknown';
				break;
		}
		$fieldMarkerArray['###BUTTONTEXT###'] = $textparm.'="'.tx_pttools_div::htmlOutput($ftext).'"';
		
		$fieldMarkerArray['###BUTTONTYPE###'] = $ftype;
		if ($fsrc != '') {
			$fieldMarkerArray['###BUTTONSRC###'] = 'src="'.$fsrc.'"';
		}
		else {
			$fieldMarkerArray['###BUTTONSRC###'] = '';
		}
		$jscript = '';
		$dblfound = false;
		foreach ($fscript as $fsevent => $fsprog) {
			if (strcasecmp('onDblClick', $fsevent) == 0) {
				$dblfound = true;
			}
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		if (($ftype == 'reset') && ! $dblfound) {
			$jscript .= ' onDblClick="return tx_pttools_formTemplateHandler_resetForm(this)"';
		}
		$fieldMarkerArray['###BUTTONSCRIPT###'] = $jscript;

		return $this->plugin->cObj->substituteMarkerArray($this->tmplInButton, $fieldMarkerArray);
    }

    /**
     * substInSplitDate: perform substitutions for SplitDate input field
     * 
     * @param   string	name of input field
     * @param   string	label text for input field
     * @param   string	(optional) value of input field (format: yyyy-mm-dd)
     * @param   bool	(optional) field is required
     * @param   bool	(optional) input is disabled
     * @param   string	(optional) help text for input field
     * @param   array	(optional) javascript for onEvent Handlers, bound to all individual component fields
     * @return  string	HTML Code for single text input field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-06-03
     */	
    public function substInSplitDate($fname, $flabel, $fvalue = '', $frequired = false, $fdisabled = false, $fhelp = '', $fscript = array()) {

		// check field value
		if (($fvalue == '') || ($fvalue == '0000-00-00')) {
			$fvyear = '';
			$fvmonth = '';
			$fvday = '';
		} else {
			$dateObj = date_create($fvalue);
			$fvyear = $dateObj->format('Y');
			$fvmonth = $dateObj->format('m');
			$fvday = $dateObj->format('d');
		}

		// create substitutions
		$jscript = '';
		foreach ($fscript as $fsevent => $fsprog) {
			$jscript .= ' '.$fsevent.'="'.$fsprog.'"';
		}
		$fieldMarkerArray['###FIELDSCRIPT###'] = $jscript;
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelField($fname, $flabel, $frequired, $fhelp);
        $fieldMarkerArray['###YEARFIELDNAME###'] = $this->prefixId.'['.$fname.'][year]';
        $fieldMarkerArray['###MONTHFIELDNAME###'] = $this->prefixId.'['.$fname.'][month]';
        $fieldMarkerArray['###DAYFIELDNAME###'] = $this->prefixId.'['.$fname.'][day]';
        $fieldMarkerArray['###YEARFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvyear);
        $fieldMarkerArray['###MONTHFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvmonth);
        $fieldMarkerArray['###DAYFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvday);
        $fieldMarkerArray['###YEARFIELDSIZE###'] = 4;
        $fieldMarkerArray['###YEARFIELDLENGTH###'] = 4;
        $fieldMarkerArray['###MONTHFIELDSIZE###'] = 2;
        $fieldMarkerArray['###MONTHFIELDLENGTH###'] = 2;
        $fieldMarkerArray['###DAYFIELDSIZE###'] = 2;
        $fieldMarkerArray['###DAYFIELDLENGTH###'] = 2;
        $fieldMarkerArray['###FIELDDISABLED###'] = $fdisabled ? 'disabled="disabled"' : '';
        return $this->plugin->cObj->substituteMarkerArray($this->tmplInSplitDate, $fieldMarkerArray);
    }

    /**
     * substDispSplitDate: perform substitutions for SplitDate display field
     * 
     * @param   string	label text for display field
     * @param   string	(optional) value of input field
     * @return  string	HTML Code for single text display field
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-06-03
     */	
    public function substDispSplitDate($flabel, $fvalue = '') {

		// check field value
		if (($fvalue == '') || ($fvalue == '0000-00-00')) {
			$fvyear = '';
			$fvmonth = '';
			$fvday = '';
		} else {
			$dateObj = date_create($fvalue);
			$fvyear = $dateObj->format('Y');
			$fvmonth = $dateObj->format('m');
			$fvday = $dateObj->format('d');
		}

		// create substitutions
        $fieldMarkerArray['###FIELDLABEL###'] = $this->labelDisplay($flabel);
        $fieldMarkerArray['###YEARFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvyear);
        $fieldMarkerArray['###MONTHFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvmonth);
        $fieldMarkerArray['###DAYFIELDVALUE###'] = tx_pttools_div::htmlOutput($fvday);
        return $this->plugin->cObj->substituteMarkerArray($this->tmplDispSplitDate, $fieldMarkerArray);
    }

    /**
     * substSection: perform substitutions for field section header/footer
     * 
     * @param   string	label text for section
     * @param   boolean	use footer template instead of header
     * @return  string	HTML Code for section header / footer
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-07-17
     */	
    public function substSection($slabel, $footer) {

		// create substitutions
        $fieldMarkerArray['###SECTIONLABEL###'] = tx_pttools_div::htmlOutput($this->plugin->pi_getLL('sl_'.$slabel, '[sl_'.$slabel.']'));
        return $this->plugin->cObj->substituteMarkerArray($footer ? $this->tmplSectionEnd : $this->tmplSectionStart, $fieldMarkerArray);
    }

    /***************************************************************************
       high level functions: handle a complete form
    ***************************************************************************/

	/**
	 * prepareConfSubst: prepare Marker Array for configured Substitutions
     * 
     * @param   string	name of form to prepare
     * @param   object	data object to obtain field values (via get_fieldname())
     * @param   array	(optional) selection values for select and combo fields, supercede locallang values
     * @param   array	(optional) list of disabled inputs
     * @param   array	(optional) list of fields to hide completely
     * @param   array	(optional) list of fields with relaxed requirements (i.e. fields that are not required even if listed as required in formdesc)
     * @param   array	(optional) urlparameter for formactionlink
     * @return  array	Marker array to use on form template
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
	 */

	public function prepareConfSubst($formname, $dataObject, $choiceArray = array(), $disableArray = array(), $hideArray = array(), $relaxArray = array(), $urlParams = array()) {
		trace('[CMD] '.__METHOD__);

        $formMarkerArray['###FORMACTION###'] = $this->plugin->pi_getPageLink($GLOBALS['TSFE']->id, '', $urlParams);
		$explanation = $this->plugin->pi_getLL('msg_explain_required');
		if ($explanation != '') {
			$explanation = $this->tmplMarkRequired.': '.$explanation;
		}
        $formMarkerArray['###FORMEXPLANATION###'] = $explanation;

		if (isset($this->formDesc[$formname]['statictexts'])) {
			foreach ($this->formDesc[$formname]['statictexts'] as $stext) {
				if (in_array($stext, $hideArray)) {
					$formMarkerArray['###STATICTEXT'.strtoupper($stext).'###'] = '';
				}
				else {
					$formMarkerArray['###STATICTEXT'.strtoupper($stext).'###']
						= tx_pttools_div::htmlOutput($this->plugin->pi_getLL('st_'.$stext, '[st_'.$stext.']'));
				}
			}
		}

		if (isset($this->formDesc[$formname]['sections'])) {
			foreach ($this->formDesc[$formname]['sections'] as $slabel) {
				if (in_array($slabel, $hideArray)) {
					$formMarkerArray['###SECTIONLABEL'.strtoupper($slabel).'###'] = '';
					$formMarkerArray['###SECTIONSTART'.strtoupper($slabel).'###'] = '';
					$formMarkerArray['###SECTIONEND'.strtoupper($slabel).'###'] = '';
				}
				else {
					$formMarkerArray['###SECTIONLABEL'.strtoupper($slabel).'###'] = tx_pttools_div::htmlOutput($this->plugin->pi_getLL('sl_'.$slabel, '[sl_'.$slabel.']'));
					$formMarkerArray['###SECTIONSTART'.strtoupper($slabel).'###'] = $this->substSection($slabel, false);
					$formMarkerArray['###SECTIONEND'.strtoupper($slabel).'###'] = $this->substSection($slabel, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemshidden'])) {
			foreach ($this->formDesc[$formname]['itemshidden'] as $ilabel) {
				$getter = 'get_'.$ilabel;
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInHidden($ilabel, $dataObject->$getter());
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarea'])) {
			foreach ($this->formDesc[$formname]['itemstextarea'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
					$rows = (isset($ivalues[2])) ? $ivalues[2] : 5;
					$cols = (isset($ivalues[3])) ? $ivalues[3] : 30;
					$script = (isset($ivalues[4])) ? $ivalues[4] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInTextArea($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $rows, $cols, $required, $checktype, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstext'])) {
			foreach ($this->formDesc[$formname]['itemstext'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
					$len = (isset($ivalues[2])) ? $ivalues[2] : 30;
					$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
					$script = (isset($ivalues[4])) ? $ivalues[4] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInText($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $len, $maxlen, $required, $checktype, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarray'])) {
			foreach ($this->formDesc[$formname]['itemstextarray'] as $alabel => $avalues) {
				if (in_array($alabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($alabel).'###'] = '';
				}
				else {
					$arraySubstString = '';
					$getter = 'get_'.$alabel;
					$required = (in_array($alabel, $relaxArray)) ? false : $avalues[0];
					$checktype = (isset($avalues[1])) ? $avalues[1] : 'Text';
					$len = (isset($avalues[2])) ? $avalues[2] : 30;
					$maxlen = (isset($avalues[3])) ? $avalues[3] : 80;
					$mincount = (isset($avalues[4])) ? $avalues[4] : 1;
					$maxcount = (isset($avalues[5])) ? $avalues[5] : 1;
					$script = (isset($avalues[6])) ? $avalues[6] : array();
					$textvalues = $dataObject->$getter();
					for ($textnum = 0; $textnum < $maxcount; $textnum++) {
						if ($textnum == $mincount) {
							$required = false;
						}
						$fieldSubstString = $this->substInText($alabel.'][', $this->plugin->pi_getLL('fl_'.$alabel, '[fl_'.$alabel.']'), $textvalues[$textnum], $len, $maxlen, $required, $checktype, in_array($alabel, $disableArray), $this->plugin->pi_getLL('fh_'.$alabel), $script);
						if ($this->tmplTextArrayWrapper) {
							$wrapSubstArray = array(
								'###FIELDNAME###' => $alabel,
								'###CONTENT###' => $fieldSubstString,
							);
							$arraySubstString .= $this->plugin->cObj->substituteMarkerArray($this->tmplTextArrayWrapper, $wrapSubstArray);
						} else {
							$arraySubstString .= $fieldSubstString.'<br />'."\n";
						}
					}
					$formMarkerArray['###ITEM'.strtoupper($alabel).'###'] = $arraySubstString;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemspasswd'])) {
			foreach ($this->formDesc[$formname]['itemspasswd'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
					$len = (isset($ivalues[2])) ? $ivalues[2] : 30;
					$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
					$script = (isset($ivalues[4])) ? $ivalues[4] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInPasswd($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $this->plugin->pi_getLL('cl_'.$ilabel), $dataObject->$getter(), $len, $maxlen, $required, $checktype, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $this->plugin->pi_getLL('ch_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsselect'])) {
			foreach ($this->formDesc[$formname]['itemsselect'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsselect'][$ilabel])) {
						$choices = $choiceArray['itemsselect'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					if (isset($choiceArray['disabledselect'][$ilabel])) {
						$nochoices = $choiceArray['disabledselect'][$ilabel];
					}
					else {
						$nochoices = array();
					}
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$substMethod = ($ivalues[1]) ? 'substInMultiSelect' : 'substInSelect';
					$size = $ivalues[2];
					$emptyok = $ivalues[3];
					$script = (isset($ivalues[4])) ? $ivalues[4] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->$substMethod($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $size, $emptyok, $choices, $dataObject->$getter(), $required, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $nochoices, $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscombo'])) {
			foreach ($this->formDesc[$formname]['itemscombo'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemscombo'][$ilabel])) {
						$choices = $choiceArray['itemscombo'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel, false);
					}
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
					$emptyok = $ivalues[2];
					$len = (isset($ivalues[3])) ? $ivalues[3] : 30;
					$maxlen = (isset($ivalues[4])) ? $ivalues[4] : 80;
					$script = (isset($ivalues[5])) ? $ivalues[5] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInCombo($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $emptyok, $choices, $this->plugin->pi_getLL('fei_'.$ilabel, '[fei_'.$ilabel.']'), $dataObject->$getter(), $len, $maxlen, $required, $checktype, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsradio'])) {
			foreach ($this->formDesc[$formname]['itemsradio'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsradio'][$ilabel])) {
						$choices = $choiceArray['itemsradio'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					if (isset($choiceArray['disabledradio'][$ilabel])) {
						$nochoices = $choiceArray['disabledradio'][$ilabel];
					}
					else {
						$nochoices = array();
					}
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$script = (isset($ivalues[1])) ? $ivalues[1] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInRadio($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $choices, $dataObject->$getter(), $required, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $nochoices, $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscheckbox'])) {
			foreach ($this->formDesc[$formname]['itemscheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$script = (isset($ivalues[1])) ? $ivalues[1] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInCheckbox($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $this->plugin->pi_getLL('ft_'.$ilabel, '[ft_'.$ilabel.']'), $dataObject->$getter(), $required, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsmulticheckbox'])) {
			foreach ($this->formDesc[$formname]['itemsmulticheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsmulticheckbox'][$ilabel])) {
						$choices = $choiceArray['itemsmulticheckbox'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					if (isset($choiceArray['disabledmulticheckbox'][$ilabel])) {
						$nochoices = $choiceArray['disabledmulticheckbox'][$ilabel];
					}
					else {
						$nochoices = array();
					}
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$script = (isset($ivalues[1])) ? $ivalues[1] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInMultiCheckbox($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $choices, $dataObject->$getter(), $required, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $nochoices, $script);
				}
			}
		}


		if (isset($this->formDesc[$formname]['itemssplitdate'])) {
			foreach ($this->formDesc[$formname]['itemssplitdate'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
					$script = (isset($ivalues[1])) ? $ivalues[1] : array();
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInSplitDate($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $required, in_array($ilabel, $disableArray), $this->plugin->pi_getLL('fh_'.$ilabel), $script);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsbutton'])) {
			foreach ($this->formDesc[$formname]['itemsbutton'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				} else {
					$btype = (isset($ivalues[0])) ? $ivalues[0] : 'submit';
					$bsrc = $ivalues[1];
					$bscript = $ivalues[2];
					if (! is_array($bscript)) {
						// old call syntax, fix it
						$bscript = array();
						$bscript['onClick'] = $ivalues[2];
					}
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
						= $this->substInButton($ilabel.'_'.$formname, $this->plugin->pi_getLL('bl_'.$ilabel.'_'.$formname, $this->plugin->pi_getLL('bl_'.$ilabel, '[bl_'.$ilabel.']')), $btype, $bsrc, $bscript);
				}
			}
		}

		return $formMarkerArray;
	}

	/**
	 * prepareDisplaySubst: prepare Marker Array for configured Substitutions using display mode
	 *
	 * This function differs from prepareConfSubst in the following ways:
	 * + text-, password- and combo-items use display template
	 * + most other input types are set to "disabled"
	 *   EXCEPT hidden and submit items, these are fully functional!
     * 
     * @param   string	name of form to prepare
     * @param   object	data object to obtain field values (via get_fieldname())
     * @param   array	(optional) selection values for select and combo fields, supercede locallang values
     * @param   array	(optional) list of fields to hide completely
     * @return  array	Marker array to use on form template
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-09-23
	 */

	public function prepareDisplaySubst($formname, $dataObject, $choiceArray = array(), $hideArray = array()) {
		trace('[CMD] '.__METHOD__);

        $formMarkerArray['###FORMACTION###'] = $this->plugin->pi_getPageLink($GLOBALS['TSFE']->id);     

        $formMarkerArray['###FORMEXPLANATION###'] = '';

		if (isset($this->formDesc[$formname]['statictexts'])) {
			foreach ($this->formDesc[$formname]['statictexts'] as $stext) {
				if (in_array($stext, $hideArray)) {
					$formMarkerArray['###STATICTEXT'.strtoupper($stext).'###'] = '';
				}
				else {
					$formMarkerArray['###STATICTEXT'.strtoupper($stext).'###']
						= tx_pttools_div::htmlOutput($this->plugin->pi_getLL('st_'.$stext, '[st_'.$stext.']'));
				}
			}
		}

		if (isset($this->formDesc[$formname]['sections'])) {
			foreach ($this->formDesc[$formname]['sections'] as $slabel) {
				if (in_array($slabel, $hideArray)) {
					$formMarkerArray['###SECTIONLABEL'.strtoupper($slabel).'###'] = '';
					$formMarkerArray['###SECTIONSTART'.strtoupper($slabel).'###'] = '';
					$formMarkerArray['###SECTIONEND'.strtoupper($slabel).'###'] = '';
				}
				else {
					$formMarkerArray['###SECTIONLABEL'.strtoupper($slabel).'###'] = tx_pttools_div::htmlOutput($this->plugin->pi_getLL('sl_'.$slabel, '[sl_'.$slabel.']'));
					$formMarkerArray['###SECTIONSTART'.strtoupper($slabel).'###'] = $this->substSection($slabel, false);
					$formMarkerArray['###SECTIONEND'.strtoupper($slabel).'###'] = $this->substSection($slabel, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemshidden'])) {
			foreach ($this->formDesc[$formname]['itemshidden'] as $ilabel) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInHidden($ilabel, $dataObject->$getter());
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarea'])) {
			foreach ($this->formDesc[$formname]['itemstextarea'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substDispTextArea($this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter());
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstext'])) {
			foreach ($this->formDesc[$formname]['itemstext'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$len = (isset($ivalues[2])) ? $ivalues[2] : 30;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substDispText($this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $len);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemspasswd'])) {
			foreach ($this->formDesc[$formname]['itemspasswd'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$len = (isset($ivalues[2])) ? $ivalues[2] : 30;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substDispPasswd($this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $len);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsselect'])) {
			foreach ($this->formDesc[$formname]['itemsselect'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsselect'][$ilabel])) {
						$choices = $choiceArray['itemsselect'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					$getter = 'get_'.$ilabel;
					$required = $ivalues[0];
					$substMethod = ($ivalues[1]) ? 'substInMultiSelect' : 'substInSelect';
					$size = $ivalues[2];
					$emptyok = $ivalues[3];
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->$substMethod($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $size, $emptyok, $choices, $dataObject->$getter(), $required, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscombo'])) {
			foreach ($this->formDesc[$formname]['itemscombo'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$len = (isset($ivalues[3])) ? $ivalues[3] : 30;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substDispText($this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter(), $len);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsradio'])) {
			foreach ($this->formDesc[$formname]['itemsradio'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsradio'][$ilabel])) {
						$choices = $choiceArray['itemsradio'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					$getter = 'get_'.$ilabel;
					$required = $ivalues[0];
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInRadio($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $choices, $dataObject->$getter(), $required, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscheckbox'])) {
			foreach ($this->formDesc[$formname]['itemscheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$required = $ivalues[0];
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInCheckbox($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $this->plugin->pi_getLL('ft_'.$ilabel, '[ft_'.$ilabel.']'), $dataObject->$getter(), $required, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsmulticheckbox'])) {
			foreach ($this->formDesc[$formname]['itemsmulticheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					if (isset($choiceArray['itemsmulticheckbox'][$ilabel])) {
						$choices = $choiceArray['itemsmulticheckbox'][$ilabel];
					}
					else {
						$choices = $this->ll_choices($ilabel);
					}
					$getter = 'get_'.$ilabel;
					$required = $ivalues[0];
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substInMultiCheckbox($ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $choices, $dataObject->$getter(), $required, true);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemssplitdate'])) {
			foreach ($this->formDesc[$formname]['itemssplitdate'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				}
				else {
					$getter = 'get_'.$ilabel;
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
            			= $this->substDispSplitDate($this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']'), $dataObject->$getter());
				}
			}
		}


		if (isset($this->formDesc[$formname]['itemsbutton'])) {
			foreach ($this->formDesc[$formname]['itemsbutton'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###'] = '';
				} else {
					$btype = (isset($ivalues[0])) ? $ivalues[0] : 'submit';
					$bsrc = $ivalues[1];
					$bscript = $ivalues[2];
					if (! is_array($bscript)) {
						// old call syntax, fix it
						$bscript = array();
						$bscript['onClick'] = $ivalues[2];
					}
					$formMarkerArray['###ITEM'.strtoupper($ilabel).'###']
						= $this->substInButton($ilabel.'_'.$formname, $this->plugin->pi_getLL('bl_'.$ilabel.'_'.$formname, $this->plugin->pi_getLL('bl_'.$ilabel, '[bl_'.$ilabel.']')), $btype, $bsrc, $bscript);
				}
			}
		}

		return $formMarkerArray;
	}

	/**
	 * fillFormIntoObject: fill Object with configured Data from Form
     * 
     * @param   string	name of form to process
     * @param   object	data object to fill with field values (via set_fieldname())
     * @param   array	(optional) selection values for select and combo fields, supercede locallang values
     * @param   array	(optional) fields to ignore
     * @param   array	(optional) more fields to ignore
     * @return  array	array of items that could not be filled into object (e.g. password fields that didn't match their checkfield)
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-09-23
	 */

	public function fillFormIntoObject($formname, $dataObject, $choiceArray = array(), $disableArray = array(), $hideArray = array()) {
		trace('[CMD] '.__METHOD__);

		$failArray = array();

		if (isset($this->formDesc[$formname]['itemshidden'])) {
			foreach ($this->formDesc[$formname]['itemshidden'] as $ilabel) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				$dataObject->$setter($fvalue);
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarea'])) {
			foreach ($this->formDesc[$formname]['itemstextarea'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				$dataObject->$setter($fvalue);
			}
		}

		if (isset($this->formDesc[$formname]['itemstext'])) {
			foreach ($this->formDesc[$formname]['itemstext'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
				if ($this->strlen($fvalue) <= $maxlen) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarray'])) {
			foreach ($this->formDesc[$formname]['itemstextarray'] as $alabel => $avalues) {
				if (in_array($alabel, $disableArray) || in_array($alabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$alabel;
				$fvalues = array();
				if (isset($this->plugin->piVars[$alabel])) {
					$fvalues = $this->plugin->piVars[$alabel];
				}
				$maxlen = (isset($avalues[3])) ? $avalues[3] : 80;
				$lenOk = true;
				foreach ($fvalues as $fvalue) {
					if ($this->strlen($fvalue) > $maxlen) {
						$lenOk = false;
						break;
					}
				}
				if ($lenOk) {
					$dataObject->$setter($fvalues);
				} else {
					$failArray[] = $alabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemspasswd'])) {
			foreach ($this->formDesc[$formname]['itemspasswd'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$checklabel = self::CHECKPWPREFIX.$ilabel;
				$fvalue = '';
				$checkvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				if (isset($this->plugin->piVars[$checklabel])) {
					$checkvalue = $this->plugin->piVars[$checklabel];
				}
				$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
				if (($fvalue === $checkvalue) && ($this->strlen($fvalue) <= $maxlen)) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsselect'])) {
			foreach ($this->formDesc[$formname]['itemsselect'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				if ($ivalues[1]) {
					$fvalue = array();
				}
				else {
					$fvalue = '';
				}
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				if (isset($choiceArray['itemsselect'][$ilabel])) {
					$choices = $choiceArray['itemsselect'][$ilabel];
				}
				else {
					$choices = $this->ll_choices($ilabel);
				}
				if (isset($choiceArray['disabledselect'][$ilabel])) {
					$nochoices = $choiceArray['disabledselect'][$ilabel];
				}
				else {
					$nochoices = array();
				}
				$choicevalid = true;
				if ($ivalues[1]) {
					// check all array components
					foreach ($fvalue as $vkey) {
						if (! isset($choices[$vkey])) {
							$choicevalid = false;
							break;
						}
						if (in_array($vkey, $nochoices)) {
							$choicevalid = false;
							break;
						}
					}
				}
				else {
					// check if fvalue contains valid choice
					if (!(($fvalue == '') || (isset($choices[$fvalue])))) {
						$choicevalid = false;
					}
					if (in_array($fvalue, $nochoices)) {
						$choicevalid = false;
					}
				}
				if ($choicevalid) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscombo'])) {
			foreach ($this->formDesc[$formname]['itemscombo'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel]))
					$fvalue = $this->plugin->piVars[$ilabel];
				$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
				if ($this->strlen($fvalue) <= $maxlen) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemsradio'])) {
			foreach ($this->formDesc[$formname]['itemsradio'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				if (isset($choiceArray['itemsradio'][$ilabel])) {
					$choices = $choiceArray['itemsradio'][$ilabel];
				}
				else {
					$choices = $this->ll_choices($ilabel);
				}
				if (isset($choiceArray['disabledradio'][$ilabel])) {
					$nochoices = $choiceArray['disabledradio'][$ilabel];
				}
				else {
					$nochoices = array();
				}
				$choicevalid = true;
				// check if fvalue contains valid choice
				if (!(($fvalue == '') || (isset($choices[$fvalue])))) {
					$choicevalid = false;
				}
					if (in_array($fvalue, $nochoices)) {
						$choicevalid = false;
					}
				if ($choicevalid) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemscheckbox'])) {
			foreach ($this->formDesc[$formname]['itemscheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				$dataObject->$setter($fvalue);
			}
		}

		if (isset($this->formDesc[$formname]['itemsmulticheckbox'])) {
			foreach ($this->formDesc[$formname]['itemsmulticheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = array();
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvalue = $this->plugin->piVars[$ilabel];
				}
				if (isset($choiceArray['itemsmulticheckbox'][$ilabel])) {
					$choices = $choiceArray['itemsmulticheckbox'][$ilabel];
				}
				else {
					$choices = $this->ll_choices($ilabel);
				}
				if (isset($choiceArray['disabledmulticheckbox'][$ilabel])) {
					$nochoices = $choiceArray['disabledmulticheckbox'][$ilabel];
				}
				else {
					$nochoices = array();
				}
				$choicevalid = true;
				foreach ($fvalue as $vkey) {
					if (! isset($choices[$vkey])) {
						$choicevalid = false;
						break;
					}
					if (in_array($vkey, $nochoices)) {
						$choicevalid = false;
						break;
					}
				}
				if ($choicevalid) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemssplitdate'])) {
			foreach ($this->formDesc[$formname]['itemssplitdate'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $disableArray) || in_array($ilabel, $hideArray)) {
					continue;
				}
				$setter = 'set_'.$ilabel;
				$fvalue = '';
				if (isset($this->plugin->piVars[$ilabel])) {
					$fvarray = $this->plugin->piVars[$ilabel];
					$fvyear = intval($fvarray['year']);
					$fvmonth = intval($fvarray['month']);
					$fvday = intval($fvarray['day']);
					if (($fvyear == 0) && ($fvmonth == 0) && ($fvday == 0)) {
						$fvalue = '';
					} else {
						if (($fvyear > 9999) || ($fvmonth < 1) || ($fvmonth > 12) || ($fvday < 1) || ($fvday > 31)) {
							$failArray[] = $ilabel;
							$fvalue = '';
						} else {
							$fvalue = sprintf("%04d-%02d-%02d", $fvyear, $fvmonth, $fvday);
						}
					}
				}
				$maxlen = (isset($ivalues[3])) ? $ivalues[3] : 80;
				if ($this->strlen($fvalue) <= $maxlen) {
					$dataObject->$setter($fvalue);
				}
				else {
					$failArray[] = $ilabel;
				}
			}
		}

		return $failArray;
	}

	/**
	 * checkObjectInForm: check if Object Data conforms to requirements
     *
     * Attention: field values are read from $dataObject, not field inputs!
     * 
     * @param   string	name of form to check
     * @param   object	data object to obtain field values (via get_fieldname())
     * @param   array	(optional) text of additional check messages to display
     * @param   array	(optional) fields not to check
     * @param   array	(optional) fields that are not required even if declared required in formdesc
     * @return  string	empty if ok, else HTML code for MessageBox
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2006-04-20
	 */

	public function checkObjectInForm($formname, $dataObject, $msgArray = array(), $hideArray = array(), $relaxArray = array()) {
		trace('[CMD] '.__METHOD__);

        // create instance of formcheck object (from pt_tools extension)
        $formcheckerObj = new tx_pttools_formchecker;
		$checkArray = array();

		if (isset($this->formDesc[$formname]['itemstextarea'])) {
			foreach ($this->formDesc[$formname]['itemstextarea'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'None';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemstext'])) {
			foreach ($this->formDesc[$formname]['itemstext'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemstextarray'])) {
			foreach ($this->formDesc[$formname]['itemstextarray'] as $alabel => $avalues) {
				if (in_array($alabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$alabel;
				$required = (in_array($alabel, $relaxArray)) ? false : $avalues[0];
				$checktype = (isset($avalues[1])) ? $avalues[1] : 'Text';
				$mincount = (isset($avalues[4])) ? $avalues[4] : 1;
				$maxcount = (isset($avalues[5])) ? $avalues[5] : 1;
				$textvalues = $dataObject->$getter();
				for ($textnum = 0; $textnum < $maxcount; $textnum++) {
					if ($textnum == $mincount) {
						$required = false;
					}
					$checkArray[] = array($checktype, $textvalues[$textnum], $this->plugin->pi_getLL('el_'.$alabel, $this->plugin->pi_getLL('fl_'.$alabel, '[fl_'.$alabel.']')), $required);
				}
			}
		}

		if (isset($this->formDesc[$formname]['itemspasswd'])) {
			foreach ($this->formDesc[$formname]['itemspasswd'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemsselect'])) {
			foreach ($this->formDesc[$formname]['itemsselect'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = ($ivalues[1]) ? 'Multiselect' : 'Pulldown';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemscombo'])) {
			foreach ($this->formDesc[$formname]['itemscombo'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = (isset($ivalues[1])) ? $ivalues[1] : 'Text';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemsradio'])) {
			foreach ($this->formDesc[$formname]['itemsradio'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checkArray[] = array('Pulldown', $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemsmulticheckbox'])) {
			foreach ($this->formDesc[$formname]['itemsmulticheckbox'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checkArray[] = array('Multiselect', $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required);
			}
		}

		if (isset($this->formDesc[$formname]['itemssplitdate'])) {
			foreach ($this->formDesc[$formname]['itemssplitdate'] as $ilabel => $ivalues) {
				if (in_array($ilabel, $hideArray)) {
					continue;
				}
				$getter = 'get_'.$ilabel;
				$required = (in_array($ilabel, $relaxArray)) ? false : $ivalues[0];
				$checktype = 'Date';
				$checkArray[] = array($checktype, $dataObject->$getter(), $this->plugin->pi_getLL('el_'.$ilabel, $this->plugin->pi_getLL('fl_'.$ilabel, '[fl_'.$ilabel.']')), $required, false, false, false);
			}
		}

		// form single message string from msgArray
		$msg = '';
		foreach ($msgArray as $msgtext) {
			if ($msg != '') {
				$msg .= "<br />";
			}
			$msg .= $msgtext;
		}

        return $formcheckerObj->doFormCheck($checkArray, $msg);
	}


    /***************************************************************************
     *   PROPERTY GETTER/SETTER METHODS
     **************************************************************************/
     
	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInHidden() {
		return $this->tmplInHidden;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInTextArea() {
		return $this->tmplInTextArea;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplDispTextArea() {
		return $this->tmplDispTextArea;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInText() {
		return $this->tmplInText;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplDispText() {
		return $this->tmplDispText;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInPasswd() {
		return $this->tmplInPasswd;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplDispPasswd() {
		return $this->tmplDispPasswd;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInSelect() {
		return $this->tmplInSelect;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInMultiSelect() {
		return $this->tmplInMultiSelect;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInCombo() {
		return $this->tmplInCombo;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInRadio() {
		return $this->tmplInRadio;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplInCheckbox() {
		return $this->tmplInCheckbox;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-17
	*/
	public function get_tmplInButton() {
		return $this->tmplInButton;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2008-06-03
	*/
	public function get_tmplInSplitDate() {
		return $this->tmplInSplitDate;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2008-06-03
	*/
	public function get_tmplDispSplitDate() {
		return $this->tmplDispSplitDate;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplLabelField() {
		return $this->tmplLabelField;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplLabelDisplay() {
		return $this->tmplLabelDisplay;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplMarkRequired() {
		return $this->tmplMarkRequired;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-10
	*/
	public function get_tmplMarkNotRequired() {
		return $this->tmplMarkNotRequired;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-17
	*/
	public function get_tmplMarkHelp() {
		return $this->tmplMarkHelp;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-17
	*/
	public function get_tmplMarkNoHelp() {
		return $this->tmplMarkNoHelp;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-12
	*/
	public function get_tmplSelectOptionEmpty() {
		return $this->tmplSelectOptionEmpty;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-12
	*/
	public function get_tmplSelectOption() {
		return $this->tmplSelectOption;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-12
	*/
	public function get_tmplRadioOption() {
		return $this->tmplRadioOption;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2006-10-12
	*/
	public function get_tmplCheckboxOption() {
		return $this->tmplCheckboxOption;
	}

	/**
	 * Returns the property value
	 *
	 * @param   void
	 * @return	string	property value
	 * @since	2011-01-24
	*/
	public function get_tmplTextArrayWrapper() {
		return $this->tmplTextArrayWrapper;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInHidden($tmplInHidden) {
		$this->tmplInHidden = $tmplInHidden;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInTextArea($tmplInTextArea) {
		$this->tmplInTextArea = $tmplInTextArea;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplDispTextArea($tmplDispTextArea) {
		$this->tmplDispTextArea = $tmplDispTextArea;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInText($tmplInText) {
		$this->tmplInText = $tmplInText;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplDispText($tmplDispText) {
		$this->tmplDispText = $tmplDispText;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInPasswd($tmplInPasswd) {
		$this->tmplInPasswd = $tmplInPasswd;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplDispPasswd($tmplDispPasswd) {
		$this->tmplDispPasswd = $tmplDispPasswd;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInSelect($tmplInSelect) {
		$this->tmplInSelect = $tmplInSelect;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInMultiSelect($tmplInMultiSelect) {
		$this->tmplInMultiSelect = $tmplInMultiSelect;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInCombo($tmplInCombo) {
		$this->tmplInCombo = $tmplInCombo;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInRadio($tmplInRadio) {
		$this->tmplInRadio = $tmplInRadio;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplInCheckbox($tmplInCheckbox) {
		$this->tmplInCheckbox = $tmplInCheckbox;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-17
	*/
	public function set_tmplInButton($tmplInButton) {
		$this->tmplInButton = $tmplInButton;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2008-06-03
	*/
	public function set_tmplInSplitDate($tmplInSplitDate) {
		$this->tmplInSplitDate = $tmplInSplitDate;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2008-06-03
	*/
	public function set_tmplDispSplitDate($tmplDispSplitDate) {
		$this->tmplDispSplitDate = $tmplDispSplitDate;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplLabelField($tmplLabelField) {
		$this->tmplLabelField = $tmplLabelField;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplLabelDisplay($tmplLabelDisplay) {
		$this->tmplLabelDisplay = $tmplLabelDisplay;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplMarkRequired($tmplMarkRequired) {
		$this->tmplMarkRequired = $tmplMarkRequired;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-10
	*/
	public function set_tmplMarkNotRequired($tmplMarkNotRequired) {
		$this->tmplMarkNotRequired = $tmplMarkNotRequired;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-17
	*/
	public function set_tmplMarkHelp($tmplMarkHelp) {
		$this->tmplMarkHelp = $tmplMarkHelp;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-17
	*/
	public function set_tmplMarkNoHelp($tmplMarkNoHelp) {
		$this->tmplMarkNoHelp = $tmplMarkNoHelp;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-12
	*/
	public function set_tmplSelectOptionEmpty($tmplSelectOptionEmpty) {
		$this->tmplSelectOptionEmpty = $tmplSelectOptionEmpty;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-12
	*/
	public function set_tmplSelectOption($tmplSelectOption) {
		$this->tmplSelectOption = $tmplSelectOption;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-12
	*/
	public function set_tmplRadioOption($tmplRadioOption) {
		$this->tmplRadioOption = $tmplRadioOption;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2006-10-12
	*/
	public function set_tmplCheckboxOption($tmplCheckboxOption) {
		$this->tmplCheckboxOption = $tmplCheckboxOption;
	}

	/**
	 * Set the property value
	 *
	 * @param   string
	 * @return	void
	 * @since	2011-01-24
	*/
	public function set_tmplTextArrayWrapper($tmplTextArrayWrapper) {
		$this->tmplTextArrayWrapper = $tmplTextArrayWrapper;
	}


} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formTemplateHandler.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formTemplateHandler.php']);
}

?>
