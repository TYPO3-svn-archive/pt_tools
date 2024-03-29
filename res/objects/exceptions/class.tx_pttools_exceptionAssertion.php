<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Rainer Kuhn (mail@fabrizio-branca.de)
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
 *  "Assertion failed" exception class (part of the library extension 'pt_tools')
 *
 * $Id$
 *
 * @author  Fabrizio Branca <mail@fabrizio-branca.de>
 * @since   2008-06-11
 */ 



/**
 * Inclusion of parent general exception class
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php';



/** 
 * "Assertion failed" exception class
 *
 * @author      Fabrizio Branca <mail@fabrizio-branca.de>
 * @since       2008-06-11
 * @package     TYPO3
 * @subpackage  tx_pttools
 */ 
class tx_pttools_exceptionAssertion extends tx_pttools_exception {
    
    /***************************************************************************
     *   CONSTRUCTOR
     **************************************************************************/
     
    /**
     * Class constructor
     * 
     * @param   string  (optional) error message (used for frontend/enduser display, too)    
     * @param   string  (optional) detailed debug message (not used for frontend display)  
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-06-11
     */
    public function __construct($errMsg='', $debugMsg='') {
        
        parent::__construct($errMsg, 0, $debugMsg);
        
    }

    /**
     * Set file
     *  
     * @param string $file
     * @return void
     * @author Fabrizio Branca <typo3@fabrizio-branca.de>
     */
    public function setFile($file) {
    	$this->file = $file;
    }
    
    /**
     * Set line
     *  
     * @param string line
     * @return void
     * @author Fabrizio Branca <typo3@fabrizio-branca.de>
     */
    public function setLine($line) {
    	$this->line = $line;
    }
    
    
    
} // end class



/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/exceptions/class.tx_pttools_exceptionAssertion.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/exceptions/class.tx_pttools_exceptionAssertion.php']);
}

?>