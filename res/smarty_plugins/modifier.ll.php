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
 * Smarty modifier for language labels
 * Usage: {"label"|ll}
 * Usage: {"label"|ll:0} if you don't want to throw an exception if the key is not found
 *
 * @param 	string	language label key
 * @return 	string	language label
 * @throws	tx_pttools_exception if label not found
 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
 * @since	2008-06-16
 */
function smarty_modifier_ll($key, $throwExceptionOnTranslationUnavailable=true) {
	
	$registry = tx_pttools_registry::getInstance();
	
	// Get the language file and/or label information from the key
	$parts = t3lib_div::trimExplode(':', $key,1);
	$parts = t3lib_div::removeArrayEntryByValue($parts, 'LLL');
	$label = array_pop($parts);
	$language_file = implode(':',$parts);
	$language_file = !empty($language_file) ? $language_file : $registry['smarty_configuration']['t3_languageFile'];
	tx_pttools_assert::isNotEmptyString($language_file, array('message' => 'No language file set.'));

	// load lang object if not available
	if (!isset($registry['smarty_lang']) || !is_object($registry['smarty_lang'])) {
		$registry['smarty_lang'] = t3lib_div::makeInstance('language');
		tx_pttools_assert::isNotEmptyString($registry['smarty_configuration']['t3_languageKey'], array('message' => 'No "language key" found in registry (label: smarty_configuration)!'));
		$registry['smarty_lang']->init($registry['smarty_configuration']['t3_languageKey']);
		tx_pttools_assert::isNotEmptyString($registry['smarty_configuration']['t3_charSet'], array('message' => 'No "character set" found in registry (label: smarty_configuration)!'));
		$registry['smarty_lang']->charSet = $registry['smarty_configuration']['t3_charSet'];
	}

	// Call the sL method from lang object to translate the label
	$translation = $registry['smarty_lang']->sL('LLL:'.$language_file.':'.$label);

	// if additional arguments exists invoke sprintf on the label
	if (func_num_args() > 1) {
		$args = func_get_args();
		array_shift($args);
		$translation = vsprintf($translation, $args);
	}
	
	list($translation, $title) = t3lib_div::trimExplode('///', $translation);
	if (!empty($title)) {
		$translation = sprintf('<span title="%s">%s</span>', $title, $translation);
	}

	// Exit if no translation was found
	if (empty($translation)) {

		// check if file exists
		tx_pttools_assert::isFilePath($language_file, array('message' => '"'.$language_file.'" is no valid file!'));

		if ($throwExceptionOnTranslationUnavailable == true) {
			// and throw error if it exists and the key is not available in that file
			throw new tx_pttools_exception('Translation unavailable for key "'.$key.'" in language "'.$registry['smarty_lang']->lang.'" (language file:"'.$language_file.'")');
		} else {
		    $translation = $key;
		}
	}

	return $translation;
}

?>