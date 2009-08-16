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
 * Smarty plugin "includeCssFile"
 * 
 * Usage: 
 * {includeCssFile file="EXT:myext/styles.css"}
 * {includeCssFile file="EXT:myext/styles.css" namespace="tx_myext_css"}
 * 
 * Using the "media" attribute. If nothing is set media defaults to "screen".
 * If you want another media you can define it in the tag.
 * {includeCssFile file="EXT:myext/styles.css" media="print"}
 * If you don't want any media define an empty tag:
 * {includeCssFile file="EXT:myext/styles.css" media=""}
 */
function smarty_function_includeCssFile(array $params, Smarty $smarty) {
	
	$path = $params['file'];
	
	tx_pttools_assert::isNotEmptyString($path);
	tx_pttools_assert::isFilePath($path);
	
	if (!isset($params['media'])) {
		$params['media'] = 'screen';
	}
	$media = ($mediaType != '') ? ' media="' . $params['media'] . '"' : '';
	
	$absPath = t3lib_div::getFileAbsFileName($path);
	$siteRelPath = substr($absPath, strlen(PATH_site));
	
	$linkTag = "\t".'<link rel="stylesheet" type="text/css" href="' . $siteRelPath . '"' . $media . ' />';
	if (!empty($params['namespace'])) {
		$GLOBALS['TSFE']->additionalHeaderData[$params['namespace']] = $linkTag;
	} else {
		$GLOBALS['TSFE']->additionalHeaderData[] = $linkTag;
	}
	
}

?>