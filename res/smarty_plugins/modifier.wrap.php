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
	
	
/**
 * Smarty modifier "wrap"
 * Usage {"text"|wrap:"<span>|</span>"}
 *
 * @param 	string	content
 * @param 	string	wrap
 * @return 	string	wrapped content or empty string
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-06-16
 */
function smarty_modifier_wrap($content, $wrap) {
	
	if (!empty($content)) {
		$content = str_replace('|', $content, $wrap);
	} else {
		$content = '';
	}
	return $content;
}

?>