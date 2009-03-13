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



require_once SMARTY_DIR.'Smarty_Compiler.class.php';
require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_exception.php';



/**
 * Compiler class for smarty
 *
 * @author      Fabrizio Branca <branca@punkt.de>
 * @since       2008-06-20
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_smartyCompiler extends Smarty_Compiler {


    /**
     * Override "trigger Smarty error" method to throw exceptions instead of triggering errors
     *
     * @param 	string 	error_msg
     * @param 	integer (optional) error_type, default is E_USER_WARNING
     * @throws	tx_pttools_exception
     * @return 	void
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-06-20
     */
    public function trigger_error($error_msg, $error_type = E_USER_WARNING) {
        throw new tx_pttools_exception('Smarty error: "'.$error_msg.'" (Type: "'.$error_type.'")');
    }

}




/*******************************************************************************
 *   TYPO3 XCLASS INCLUSION (for class extension/overriding)
 ******************************************************************************/
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_smartyCompiler.php']) {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/objects/class.tx_pttools_smartyCompiler.php']);
}

?>