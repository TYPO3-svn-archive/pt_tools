<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Fabrizio Branca <mail@fabrizio-branca.de>, Rainer Kuhn <kuhn@punkt.de>
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
 * Default renderer for quickform, based on Fabrizio Branca's QF renderer tx_tcaobjects_qfDefaultRenderer in his extension 'tcaobjects'
 *
 * $Id$
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>, Rainer Kuhn <kuhn@punkt.de>
 * @since       2009-03-16
 */



/**
 * Inclusion of PEAR resources
 */
require_once 'HTML/QuickForm/Renderer/Default.php';
require_once 'HTML/QuickForm/static.php';  // base class for form elements

/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_iQuickformRenderer.php';



/**
 * Default renderer for quickform, based on Fabrizio Branca's QF renderer tx_tcaobjects_qfDefaultRenderer in his extension 'tcaobjects'
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2009-03-16
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_qfDefaultRenderer extends HTML_QuickForm_Renderer_Default implements tx_pttools_iQuickformRenderer {
    
    /**
     * @var string  prefix for CSS classnames
     */
    protected $cssPrefix = 'tx-pttools-qf-';
    
    
    
    /**
     * Constructor
     *
     * @param   string    (optional) templateFile
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-22
     */
    public function __construct($templateFile=NULL) {
        
        parent::HTML_QuickForm_Renderer_Default(); // original constructor
        
        if (!is_null($templateFile)) {
            $this->setTemplateFile($templateFile);
        }
        
    }
    
    /**
     * Set the template from a template file
     *
     * @param   string    path to the template file (EXT:... syntax is supported here)
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since  2008-04-27
     */
    public function setTemplateFile($templateFile) {
        
        tx_pttools_assert::isFilePath($templateFile);
               
        $fileContent = $GLOBALS['TSFE']->cObj->fileResource($templateFile);
        
        $subparts = array();
        $subparts['elementTemplate']       = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###ELEMENTTEMPLATE###');
        $subparts['groupElementTemplate']  = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###GROUPELEMENTTEMPLATE###');
        $subparts['formTemplate']          = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###FORMTEMPLATE###');
        $subparts['headerTemplate']        = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###HEADERTEMPLATE###');
        $subparts['requiredNoteTemplate']  = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###REQUIREDNOTETEMPLATE###');
        $subparts['groupTemplate']         = $GLOBALS['TSFE']->cObj->getSubpart($fileContent, '###GROUPTEMPLATE###');
        
        if (!empty($subparts['elementTemplate'])) {
            $this->setElementTemplate($subparts['elementTemplate']);
        }
        if (!empty($subparts['groupElementTemplate'])) {
            $this->setGroupElementTemplate($subparts['groupElementTemplate']);
        }
        if (!empty($subparts['formTemplate'])) {
            $this->setFormTemplate($subparts['formTemplate']);
        }
        if (!empty($subparts['headerTemplate'])) {
            $this->setHeaderTemplate($subparts['headerTemplate']);
        }
        if (!empty($subparts['requiredNoteTemplate'])) {
            $this->setRequiredNoteTemplate($subparts['requiredNoteTemplate']);
        }
        if (!empty($subparts['groupTemplate'])) {
            $this->setGroupTemplate($subparts['groupTemplate']);
        }
        
    }
    
    
    
    /***************************************************************************
     * Overwrite some methods from original default renderer
     **************************************************************************/
    
    /**
     * Called when visiting a group, before processing any group elements
     *
     * @param   object     An HTML_QuickForm_group object being visited
     * @param   bool       Whether a group is required
     * @param   string     An error message associated with a group
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function startGroup (&$group, $required, $error) {
        
        $name = $group->getName();
        $this->_groupTemplate = $this->_prepareTemplate($name, $group->getLabel(), $required, $error);
        if (!empty($this->_groupTemplates[$name])) {
            $this->_groupElementTemplate = $this->_groupTemplates[$name];
        }
        if (!empty($this->_groupWraps[$name])) {
            $this->_groupWrap            = $this->_groupWraps[$name];    
        }
        
        $this->_groupElements = array();
        $this->_inGroup       = true;
        
    }
    
    /**
     * Sets element template for elements within a group
     *
     * @param       string      The HTML surrounding an element 
     * @param       string      Name of the group to apply template for
     * @return      void
     * @author      Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function setGroupElementTemplate($html, $group = '') {
        
        if (empty($group)) {
            $this->_groupElementTemplate = $html;    
        } else {
            $this->_groupTemplates[$group] = $html;
        }
        
    }

    /**
     * Sets template for a group wrapper 
     * 
     * This template is contained within a group-as-element template 
     * set via setTemplate() and contains group's element templates, set
     * via setGroupElementTemplate()
     *
     * @param       string      The HTML surrounding group elements
     * @param       string      Name of the group to apply template for
     * @return      void
     * @author      Fabrizio Branca <mail@fabrizio-branca.de>
     */
    public function setGroupTemplate($html, $group = '') {
        
        if (empty($group)) {
            $this->_groupWrap = $html;
        } else {
            $this->_groupWraps[$group] = $html;
        } 
        
    }

    /**
     * Helper method for renderElement
     *
     * @param   string      Element name
     * @param   mixed       Element label (if using an array of labels, you should set the appropriate template)
     * @param   bool        Whether an element is required
     * @param   string      Error message associated with the element
     * @param   string        (optional) id of the element that will replace {id}, default is ''
     * @see     renderElement()
     * @return  string      Html for element
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>, Daniel Lienert <lienert@punkt.de>
     */
    public function _prepareTemplate($name, $label, $required, $error, HTML_QuickForm_element $element = null) {
        
        if ($element instanceof HTML_QuickForm_static) {
            $html = '{element}';
            
        } else {
            $html = parent::_prepareTemplate($name, $label, $required, $error);
            if (!is_null($element)) {
                $html = str_replace('{id}', ($element->getAttribute('id') ? $element->getAttribute('id') : $element->getName()), $html);
                
	            if(trim($element->getComment())) {
	            	$html = str_replace('{comment}', $element->getComment(), $html);
	            	$html = str_replace('<!-- BEGIN comment -->', '', $html);
	            	$html = str_replace('<!-- END comment -->', '', $html);
	            } else {
	            	$html = preg_replace("/([ \t\n\r]*)?<!-- BEGIN comment -->.*<!-- END comment -->([ \t\n\r]*)?/isU", '', $html);
	            }
	                
                $html = str_replace('{elementclass}', $this->cssPrefix.$element->getType().' type-'.$element->getType(), $html);  // "type-..." is used by YAML default styles
                $html = str_replace('{errorclass}', (isset($error) ? ' '.$this->cssPrefix.'error' : ''), $html);
                
            } else {
                $html = str_replace('{id}', 'dummy_non-element', $html);
                $html = str_replace('{comment}', '', $html);
                $html = str_replace('{elementclass}', 'tx-pttools-qf-non-element', $html);
                $html = str_replace('{errorclass}', '', $html);
            }
        }
        
        return $html;
        
    }
    
    /**
     * Renders an element Html
     * Called when visiting an element
     * 
     * Overrides the original method 
     *
     * @param   HTML_QuickForm_element form element being visited
     * @param   bool                   Whether an element is required
     * @param   string                 An error message associated with an element
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>, Daniel Lienert <lienert@punkt.de>
     */
    public function renderElement(HTML_QuickForm_element $element, $required, $error) {
        
        if (!$this->_inGroup) {
            // passes $element to "_prepareTemplate"
            $html = $this->_prepareTemplate($element->getName(), $element->getLabel(), $required, $error, $element);
            $this->_html .= str_replace('{element}', $element->toHtml(), $html);

        } elseif (!empty($this->_groupElementTemplate)) {
            $html = str_replace('{label}', $element->getLabel(), $this->_groupElementTemplate);
            // replaces "{id}" with current element's id
            $html = str_replace('{id}', ($element->getAttribute('id') ? $element->getAttribute('id') : $element->getName()), $html);

            if(trim($element->getComment())) {
            	$html = str_replace('{comment}', $element->getComment(), $html);	
            } else {
            	$html = preg_replace("/([ \t\n\r]*)?<!-- BEGIN comment -->.*<!-- END comment -->([ \t\n\r]*)?/isU", '', $html);
            }

            if ($required) {
                $html = str_replace('<!-- BEGIN required -->', '', $html);
                $html = str_replace('<!-- END required -->', '', $html);
            } else {
                $html = preg_replace("/([ \t\n\r]*)?<!-- BEGIN required -->.*<!-- END required -->([ \t\n\r]*)?/isU", '', $html);
            }
            $this->_groupElements[] = str_replace('{element}', $element->toHtml(), $html);

        } else {
            $this->_groupElements[] = $element->toHtml();
        }
    }
    
}



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_qfDefaultRenderer.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_qfDefaultRenderer.php']);
}

?>