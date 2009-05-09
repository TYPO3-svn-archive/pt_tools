<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2009 Fabrizio Branca (mail@fabrizio-branca.de)
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
 * Smarty modifier "explodeAndPrint"
 * Usage {'1982-12-08'|explodeAndPrint:'-':'%3$s.%2$s.%1$s'}
 * Reults in 08.12.1982
 * 
 * Hint: Use single quotes for the template if you use position specifiers with the dollar sign
 * @see		http://www.php.net/manual/en/function.sprintf.php
 * for a detailed description on how to use ((v)s)printf
 *   
 * @param 	string	content
 * @param 	string	delimiter
 * @param 	string	template string
 * @return 	string	content
 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
 * @since	2009-03-03
 */
function smarty_modifier_explodeAndPrint($content, $delimiter, $templateString) {
    
    $contentParts = explode($delimiter, $content);
    
    return vsprintf($templateString, $contentParts);
	
}

?>