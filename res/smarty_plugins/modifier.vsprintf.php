<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Fabrizio Branca (mail@fabrizio-branca.de)
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
 * Smarty modifier "vsprintf"
 * Usage {'The quick brown %s jumps over the lazy %s'|vsprintf:'fox':'dog'}
 * 
 * Hint: Use single quotes for the template if you use position specifiers with the dollar sign
 * @see		http://www.php.net/manual/en/function.sprintf.php
 * for a detailed description on how to use ((v)s)printf
 *
 * @param 	string	content
 * @param 	string	(optional) first parameter to replace...
 * @param 	string	(optional) second parameter to replace...
 * @param 	string	(optional) ...
 * @return 	string	content
 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
 * @since	2008-07-08
 */
function smarty_modifier_vsprintf($content) {
	
	// if additional arguments exists invoke vsprintf on the text
	if (func_num_args() > 1) {
		$args = func_get_args();
		array_shift($args);
		$content = vsprintf($content, $args);
	}

	return $content;
	
}

?>