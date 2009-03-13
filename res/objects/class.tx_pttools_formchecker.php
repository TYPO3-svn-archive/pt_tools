<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2003-2008 Rainer Kuhn (kuhn@punkt.de)
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
 * General webform checker library (part of the library extension 'pt_tools')
 *
 * Contains a class for frontend use, including methods for checking input content, usually originating from web forms.
 *
 * $Id: class.tx_pttools_formchecker.php,v 1.27 2008/09/19 11:52:38 ry37 Exp $
 *
 * @author  Rainer Kuhn <kuhn@punkt.de> (thanks to Tino Bickel for spadework and inspiration :)
 * @since   2005-09-12 (based on original code from 2003-05)
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */



/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php'; // general exception class
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_msgBox.php'; // message box class
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php'; // general static library class



/**
 * Class for frontend use, provides methods for checking input content, usually originating from web forms.
 * This includes several special type checks like text, digits, email, zip etc.
 *
 * @author      Rainer Kuhn <kuhn@punkt.de> (thanks to Tino Bickel for spadework and inspiration)
 * @since       2005-09-12 (based on original code from 2003-05)
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_formchecker {
    
    /**
     * tx_pttools_formchecker instance variables
     */
    protected $configArr = array(); // (array) configuration for tx_pttools_formchecker
    protected $llArray = array(); //   (array) array containing locallang labels for tx_pttools_formchecker
    
    /**
     * Class Constants
     */
    const EXT_KEY     = 'pt_tools';                         // (string) the extension key
    const LL_FILEPATH = 'res/objects/locallang.xml';        // (string) path to the locallang file to use within this class
    
    
    
    
    
    /***************************************************************************
        CONSTRUCTOR
    ***************************************************************************/
    
    /**
     * Class Constructor: initializes the localization features and reads the configuration
     * 
     * @param   void
     * @return  void
     * @global  void
     * @throws  tx_pttools_exception   if no configuration found
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-12 (original code from 2004-07)
     */
    public function __construct() {
        
        // Loading the LOCAL_LANG labels for localization of messages
        $llFile = t3lib_extMgm::extPath(self::EXT_KEY).self::LL_FILEPATH;
        $this->llArray = tx_pttools_div::readLLfile($llFile);
        
        // get and check configuration values
        $this->configArr = $GLOBALS['TSFE']->tmpl->setup['config.']['pt_tools.'];
        trace($this->configArr, 0, 'tx_pttools_formchecker::configArr');
        if (!is_array($this->configArr) || empty($this->configArr)) {
            throw new tx_pttools_exception('No configuration found for Formchecker!', 2,
                                           'No valid configuration found for tx_pttools_formchecker');
            
        }
 
    }
    
    
    
    /***************************************************************************
       FORM CHECKER METHOD
    ***************************************************************************/
    
    /**
     * Checks the input of a form passed in a check data array, returns a HTML formatted error msgbox if errors found
     *
     * @param   array       2D-Array of form data to check. Contains arrays of the following structure: array(elem0, elem1, elem2, elem3[, elem4, ... ,elem9])
     *                      elem0: (string)  check method to use (check method names of this class without prefixing word 'check');
     *                                       currently available: 'None', 'Text', 'Digit', 'Tel', 'Zip', 'Email', 'Domain', 'Ftp', 'Login', 'Pwd', 'Date', 'Pulldown', 'Multiselect'
     *                      elem1: (string)  form field input to check (user submission), (array) for 'Multiselect' method
     *                      elem2: (string)  display title of the form field on the web page (used for possible error descriptions)
     *                      elem3: (boolean) flag for mandatory/required field (initiates an additional check for empty field)
     *                      elem4-elem9: (mixed) (optional) additional paremters for specific check methods (see specific check method's comment)
     * @param   string      (optional) additional message, is inserted at beginnign of messagebox text
     * @return  string      HTML of messagebox if errors are found, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2005-09-12
     */
    public function doFormCheck($checkArr, $initialMsg = '') {  
        
        $fieldParamsArr = array();
        $resultArr = array();
        $checkResult = '';
        $resultMsg = '';
        $msgBoxObj = NULL;
        
        // if initialMsg is available, use it as first part of resultMsg
        if ($initialMsg != '') {
            $resultMsg = $initialMsg . '<br />';
        }

        // process check array, call appropriate method for every field element
        foreach ($checkArr as $fieldParamsArr) {
            
            $checkMethod = 'check'.$fieldParamsArr[0];
            
            // break processing on call of an undefined method
            if (!method_exists($this, $checkMethod)) {
                $resultArr = array(sprintf(tx_pttools_div::getLLL('formchecker_wrong_method', $this->llArray), '<b>['.$checkMethod.']</b>'));
                break 1;
            }
            
            // catch return of check methods
            // TODO: check methods' call with variable number of parameters could be solved with a more sophisticated solution (e.g. using eval()), if somebody finds time for this
            if ($fieldParamsArr[0] == 'Multiselect') {
                if (sizeof($fieldParamsArr) > 3) {
                    $resultArr[] = $this->checkMultiselect($fieldParamsArr[1], $fieldParamsArr[2], $fieldParamsArr[3]);
                } else {
                    $resultArr[] = $this->checkMultiselect($fieldParamsArr[1], $fieldParamsArr[2]);
                }
            } else {
                switch (sizeof($fieldParamsArr)) {
                    case 5:
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3], $fieldParamsArr[4]);
                        break;
                    case 6: 
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3], $fieldParamsArr[4], 
                                                           $fieldParamsArr[5]);
                        break;
                    case 7:
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3], $fieldParamsArr[4], 
                                                           $fieldParamsArr[5], $fieldParamsArr[6]);
                        break;
                    case 8:
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3], $fieldParamsArr[4], 
                                                           $fieldParamsArr[5], $fieldParamsArr[6], $fieldParamsArr[7]);
                        break;
                    case 9:
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3], $fieldParamsArr[4], 
                                                           $fieldParamsArr[5], $fieldParamsArr[6], $fieldParamsArr[7], $fieldParamsArr[8]);
                        break;
                    default:
                        $resultArr[] = $this->$checkMethod(stripslashes($fieldParamsArr[1]), $fieldParamsArr[2], $fieldParamsArr[3]);
                        break;                     
                }
            }
            
        }
        
        // check results and generate message string
        foreach ($resultArr as $checkResult) {
            if ($checkResult != '') {
                $resultMsg .= $checkResult.'<br />';
            }
        }
        
        // create msgbox and return HTML MsgBox string if errors have been found, else return empty string
        if (empty($resultMsg)) {
            return '';    
        } 
        $msgBoxObj = new tx_pttools_msgBox('error', $resultMsg);
        return $msgBoxObj->__toString();
        
    }   
    
    
    
    /***************************************************************************
       HELPER METHODS
    ***************************************************************************/
    
    /**
     * Get match pattern for specified check, possibly modified for site charset
     *
     * @param   string     Name of Check function
     * @param   boolean    pattern is for ereg instead of preg_match
     * @return  string     Match pattern
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2007-04-07
     */
    protected function getPattern($functionname, $use_ereg = false) {

        $pattern = $this->configArr['fcRegex_'.$functionname];
        if (tx_pttools_div::getSiteCharsetEncoding() == 'utf-8') {
            // Add the utf-8 pattern flag for utf-8 sites
            if (! $use_ereg) {
                $pattern .= 'u';
            }
        }

        return $pattern;
    }

    
    /***************************************************************************
       FIELD CHECK METHODS
    ***************************************************************************/
    
    /**
     * 'None' check: check only for empty and (optional) to long string
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param   integer     maximum number of chars allowed for input (e.g. for database limitations, optional)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-08
     */
    public function checkNone($input, $fieldTitle, $required=0, $maxLength=NULL) {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // select string length checker depending on encoding
        if (tx_pttools_div::getSiteCharsetEncoding() == 'utf-8') {
            $strlenfunc = 'mb_strlen';
        } else {
            $strlenfunc = 'strlen';
        }
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check length of input
        } elseif (isset($maxLength) && ($strlenfunc($input) > $maxLength)) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_too_long', $this->llArray), '<b>'.$fieldTitle.'</b>', intval($maxLength), $strlenfunc($input));
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Text check (general): checks for special chars and string length
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param   integer     maximum number of chars allowed for input (e.g. for database limitations, optional)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkText($input, $fieldTitle, $required=0, $maxLength=NULL) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // select string length checker depending on encoding
        if (tx_pttools_div::getSiteCharsetEncoding() == 'utf-8') {
            $strlenfunc = 'mb_strlen';
        } else {
            $strlenfunc = 'strlen';
        }

        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } elseif (isset($maxLength) && ($strlenfunc($input) > $maxLength)) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_too_long', $this->llArray), '<b>'.$fieldTitle.'</b>', intval($maxLength), $strlenfunc($input));
        } elseif (preg_match_all($this->getPattern(__FUNCTION__), strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Digit check (general): checks for digits only (no decimal points, blanks etc. allowed)
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param   integer     minimum number of chars allowed for input (optional, default is 1)
     * @param   integer     maximum number of chars allowed for input (optional, default is 1024)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkDigit($input, $fieldTitle, $required=0, $minlength=1, $maxlength=1024) {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
            
        // check content of input
        } elseif (preg_match($this->getPattern(__FUNCTION__), $input)) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_numbers_only', $this->llArray), '<b>'.$fieldTitle.'</b>');
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Float check (general): checks for floating numbers (one decimal seperation character allowed, default is decimal point)
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default is 0)
     * @param   integer     maximal number of decimal places (optional, default is 2)
     * @param   string      character used als decimal seperator (optional, default is '.')
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Dorit Rottner <rottner@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-08
     */
    public function checkFloat($input, $fieldTitle, $required=0, $decimalPlaces=2, $decimalSeparator='.') {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input contents
        } else {
            list($integerPart, $decimalPlacesPart) = explode($decimalSeparator, $input, 2);
            // check length of decimal places
            if (strlen($decimalPlacesPart) > $decimalPlaces) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_too_many_decplaces', $this->llArray), 
                               '<b>'.$fieldTitle.'</b>', intval($decimalPlaces));
            // check content of input
            } elseif (preg_match($this->getPattern(__FUNCTION__), $integerPart) || preg_match($this->getPattern(__FUNCTION__), $decimalPlacesPart)) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_numbers_decplaces_only', $this->llArray), 
                                               '<b>'.$fieldTitle.'</b>', $decimalSeparator);
            }
        }
        
        // no error found
        return '';  
        
    }
    
    /**
     * Telecommunication number check: checks phone, fax, mobile phone numbers etc. (some special chars allowed)
     *
     * @param    string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param    string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param    boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @return   string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkTel($input, $fieldTitle, $required=0) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } elseif (preg_match_all($this->getPattern(__FUNCTION__), $input, $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
            
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Zip check: checks content and length of zip number codes
     *
     * @param    string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param    string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param    boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param    integer     minimum number of digits allowed for input (optional, default is for Germany)
     * @param    integer     maximum number of digits allowed for input (optional, default is for Germany)
     * @param    integer     minimum zip number (optional, default is for Germany)
     * @return   string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkZip($input, $fieldTitle, $required=0, $minlength=4, $maxlength=5, $minZip=1000) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } else {
            if (preg_match_all($this->getPattern(__FUNCTION__), $input, $matches)) {
                $wrongChars = '';
                foreach ($matches[0] as $char) {
                    $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
                }
                $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
            } 
            if (strlen($input) < $minlength || strlen($input) > $maxlength || (strlen($input)==$minlength && $input<$minZip)) { 
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_digits_constraint', $this->llArray), 
                               '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
            }
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Login (username) check: checks content and length of logins
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default=true)
     * @param   integer     minimum number of chars allowed for input (optional, default is 4)
     * @param   integer     maximum number of chars allowed for input (optional, default is 20)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkLogin($input, $fieldTitle, $required=1, $minlength=4, $maxlength=20) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
        
        // check content of input
        } elseif (preg_match_all($this->getPattern(__FUNCTION__), strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Password check: checks content and length of passwords
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default=true)
     * @param   integer     minimum number of chars allowed for input (optional, default is 4)
     * @param   integer     maximum number of chars allowed for input (optional, default is 20)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkPwd($input, $fieldTitle, $required=1, $minlength=4, $maxlength=20) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
        
        // check content of input
        } elseif (preg_match_all($this->getPattern(__FUNCTION__), strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Email address check: checks email addresses, optionally by regular expression check or MX check
     *
     * Notice: The MX-Check of this method uses PHP's checkdnsrr() which is not implemented in PHP on Windows platforms.
     *
     * @param    string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param    string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param    boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param    boolean     flag for MX check (optional, default ist regular expression check): Check DNS records corresponding to the given host name (WARNING: This function is not implemented on Windows platforms!)
     * @return   string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkEmail($input, $fieldTitle, $required=0, $mxCheck=0) {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } else {
            // MX check
            if ($mxCheck) {
                list($user, $host) = explode('@', $input);
                if (!((checkdnsrr($host, 'MX') || checkdnsrr($host, 'A')) && strlen($user) > 1)) {
                    return sprintf(tx_pttools_div::getLLL('formchecker_entry_invalid_email', $this->llArray), '<b>'.$fieldTitle.'</b>');
                }
            // RegExp check
            } elseif (!eregi($this->getPattern(__FUNCTION__, true), strtolower($input))) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_invalid_email', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
        }
        
        // no error found
        return '';
    }
    
    /**
     * Domain check: checks domain part of urls (without subdomain, TLD, points)
     *
     * @param    string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param    string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param    boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @return   string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkDomain($input, $fieldTitle, $required=0) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } elseif (preg_match_all($this->getPattern(__FUNCTION__), strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        } elseif (strpos($input, '-') === 0) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_domain_pos1', $this->llArray), '<b>'.$fieldTitle.'</b>');
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * FTP directory check: checks ftp directories for correct chars
     *
     * @param    string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param    string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param    boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @return   string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2003-05
     */
    public function checkFtp($input, $fieldTitle, $required=0) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } else { 
            $wrongChars = '';
            if (!(strpos($input, '..') === false)) {
                $wrongChars = '..';
            } elseif (!(strpos($input, './') === false)) {
                $wrongChars = './';
            }
            if ($wrongChars || preg_match_all($this->getPattern(__FUNCTION__), strtolower($input), $matches)) {
                if (!$wrongChars) {
                    foreach ($matches[0] as $char) {
                        $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
                    }
                    $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
                }
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
            }
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Date check (general): checks dates for format (yy)yy-mm-dd or dd.mm.(yy)yy
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param   boolean     changes required date format from (yy)yy-mm-dd to euro format dd.mm.(yy)yy (optional, default is true)
     * @param   boolean     requires a 2-digit year representation (optional, default is false)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Ursula Klinger <klinger@punkt.de>, Rainer Kuhn <kuhn@punkt.de>
     * @since   2004-05
     */
    public function checkDate($input, $fieldTitle, $required=0, $euroFormat=1, $year2Digits=0) {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check content of input
        } else {
            if ($euroFormat) {
                list($day, $month, $year) = split('\.', $input);
            } else {
                list($year, $month, $day) = split('-', $input);
            }
            if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year) || $day < 1 || $day > 31 
                || $month < 1 || $month > 12 || $year < 0 || $year > 2500 || ($year2Digits == 1 && strlen($year) != 2)) {
                
                $yearFormat = ($year2Digits ? 'yy' : 'yyyy');
                $reqDateFormat = ($euroFormat ? 'dd.mm.'.$yearFormat : $yearFormat.'-mm-dd');
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_date_format', $this->llArray), '<b>'.$fieldTitle.'</b>', '<b>'.$reqDateFormat.'</b>');
                
            }
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Required pulldown menu (selectorbox) check
     *
     * Checks if user didn't made a selection in a required pulldown menu with a blank or descriptive first entry
     *
     * @param   string      field input to check (value from pulldown menu)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @param   string      value of blank or descriptive first entry, submitted if no user selection was made (optional, default is '')
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2004-09-21
     */
    public function checkPulldown($input, $fieldTitle, $required=1, $noSelectionValue='') {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for no-selection-value
        if ($required && $input == $noSelectionValue) {
            return sprintf(tx_pttools_div::getLLL('formchecker_selection_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * Required multiselect input (pulldown or multi-checkbox)
     *
     * Checks if user has selected at least one value in a multi-select input if the field is required
     *
     * @param   array       field input to check (value from pulldown menu)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Wolfgang Zenker <zenker@punkt.de>
     * @since   2008-08
     */
    public function checkMultiselect($input, $fieldTitle, $required=1) {
        
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        
        // check for no-selection-value
        if ($required && count($input) < 1) {
            return sprintf(tx_pttools_div::getLLL('formchecker_selection_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * User defined check #1: checks content and length referring to user defined conditions. 
     * IMPORTANT: The regular expression for this check has to be configured in TYPO3's Constant Editor!
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default=true)
     * @param   integer     minimum number of chars allowed for input (optional, default is 1)
     * @param   integer     maximum number of chars allowed for input (optional, default is 255)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-07-26
     */
    public function checkUserdefined1($input, $fieldTitle, $required=1, $minlength=1, $maxlength=255) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        $userDefRegEx = $this->getPattern(__FUNCTION__);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
        
        // check content of input (if a regex is configured for this method)
        } elseif (!empty($userDefRegEx) && preg_match_all($userDefRegEx, strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * User defined check #2: checks content and length referring to user defined conditions. 
     * IMPORTANT: The regular expression for this check has to be configured in TYPO3's Constant Editor!
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default=true)
     * @param   integer     minimum number of chars allowed for input (optional, default is 1)
     * @param   integer     maximum number of chars allowed for input (optional, default is 255)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-07-26
     */
    public function checkUserdefined2($input, $fieldTitle, $required=1, $minlength=1, $maxlength=255) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        $userDefRegEx = $this->getPattern(__FUNCTION__);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
        
        // check content of input (if a regex is configured for this method)
        } elseif (!empty($userDefRegEx) && preg_match_all($userDefRegEx, strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    /**
     * User defined check #3: checks content and length referring to user defined conditions. 
     * IMPORTANT: The regular expression for this check has to be configured in TYPO3's Constant Editor!
     *
     * @param   string      field input to check (if used for HTML message return this param will be output-filtered within this method)
     * @param   string      display title of the form field on the web page (used for error descriptions; if used for HTML message return this param will be output-filtered within this method)
     * @param   boolean     flag for mandatory field (initiates additional check for empty field, optional, default=true)
     * @param   integer     minimum number of chars allowed for input (optional, default is 1)
     * @param   integer     maximum number of chars allowed for input (optional, default is 255)
     * @return  string      result of field check: HTML error message text on error, else empty string
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2006-07-26
     */
    public function checkUserdefined3($input, $fieldTitle, $required=1, $minlength=1, $maxlength=255) {
        
        $matches = array(); // will be filled with the results of preg_match_all() search
        $fieldTitle = tx_pttools_div::htmlOutput($fieldTitle);
        $userDefRegEx = $this->getPattern(__FUNCTION__);
        
        // check for empty input
        if (trim($input) == '') {
            if ($required) {
                return sprintf(tx_pttools_div::getLLL('formchecker_entry_required', $this->llArray), '<b>'.$fieldTitle.'</b>');
            }
            
        // check input length
        } elseif (strlen($input) < $minlength || strlen($input) > $maxlength) {
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_chars_constraint', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>', intval($minlength), intval($maxlength));
        
        // check content of input (if a regex is configured for this method)
        } elseif (!empty($userDefRegEx) && preg_match_all($userDefRegEx, strtolower($input), $matches)) {
            $wrongChars = '';
            foreach ($matches[0] as $char) {
                $wrongChars .= ($char==chr(92) ? '&#x005c;' : $char); // special treatment for display of backslash
            }
            $wrongChars = tx_pttools_div::htmlOutput($wrongChars);
            return sprintf(tx_pttools_div::getLLL('formchecker_entry_wrong_chars', $this->llArray), 
                           '<b>'.$fieldTitle.'</b>') .' [<b>'.$wrongChars.'</b>]';
        }
        
        // no error found
        return '';
        
    }
    
    
    
} // end class



/*******************************************************************************
    TYPO3 XCLASS INCLUSION (for class extension/overriding)
*******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formchecker.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_formchecker.php']);
}

?>
