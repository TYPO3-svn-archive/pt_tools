<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Rainer Kuhn <kuhn@punkt.de>
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
	
	
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php';

/**
 * Smarty modifier for date format conversion: converts a given date string from Euro format to US format or vice versa and returns the converted string
 * Usage: {"datestring"|convertDate}   if you want to convert from Euro format (TT.MM.YYYY) to US format (YYYY-MM-DD)
 * Usage: {"datestring"|convertDate:1} if you want to convert from US format (YYYY-MM-DD) to Euro format (TT.MM.YYYY)
 *
 * @param   string      original date string to convert
 * @param   boolean     (optional) flag for conversion direction: 0 (default) = converts DD.MM.YYYY/DD-MM-YYYY to YYYY-MM-DD, 1 = converts YYYY-MM-DD to DD.MM.YYYY
 * @return  string      converted date (YYYY-MM-DD by default, if 2. param is set to true DD.MM.YYYY)
 * @throws  tx_pttools_exceptionAssertion   if non-empty date param is not a string
 * @author	Rainer Kuhn <kuhn@punkt.de>
 * @since	2009-03-16
 */
function smarty_modifier_convertDate($dateOrig, $reverse=0) {
	
    if (empty($dateOrig)) {
        return $dateOrig;
    }
    tx_pttools_assert::isString($dateOrig, array('message'=>'No string given for data conversion.'));
    
	return tx_pttools_div::convertDate($dateOrig, $reverse);
	
}

?>