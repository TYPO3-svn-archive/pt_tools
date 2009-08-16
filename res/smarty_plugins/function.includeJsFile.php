<?php

/***************************************************************
 *  Copyright notice
 *
 *	Created by Fabrizio Branca
 *	Copyright (c) 2009, Fabrizio Branca (mail@fabrizio-branca.de)
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
 * Smarty plugin "includeJsFile"
 * 
 * Usage:
 * {includeJsFile file="EXT:myext/application.js"}
 * {includeJsFile file="EXT:myext/application.js" namespace="tx_myext_controller_foo"}
 * 
 * TODO: add a "minify/compress" option.
 */
function smarty_function_includeJsFile(array $params, Smarty $smarty) {
	
	$path = $params['file'];
	
	tx_pttools_assert::isNotEmptyString($path);
	tx_pttools_assert::isFilePath($path);
	
	$absPath = t3lib_div::getFileAbsFileName($path);
	$siteRelPath = substr($absPath, strlen(PATH_site));
	
	$scriptTag = "\t".'<script src="' . $siteRelPath . '" type="text/javascript"></script>';
	if (!empty($params['namespace'])) {
		$GLOBALS['TSFE']->additionalHeaderData[$params['namespace']] = $scriptTag;
	} else {
		$GLOBALS['TSFE']->additionalHeaderData[] = $scriptTag;
	}
	
}

?>