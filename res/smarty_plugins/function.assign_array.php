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
 * Callback function for Smarty's register_function to assign complete arrays to Smarty variables
 * Smarty usage examples: {assign_array var="myArray" values="2,4,5"}, {assign_array var="myArray" values="2;4;5" delimiter=";"}
 *
 * @param 	array 	  parameters for the assign array function from the Smarty engine, mandatory keys are 'var' and 'values', key 'delimiter' is optional (e.g. array('var'=>'myArray', 'values'=>'2,4,5'))
 * @param	Smarty 	  object of type Smarty (reference to the Smarty object)
 * @return 	void
 * @author	Fabrizio Branca <mail@fabrizio-branca.de>, based on function.assign_array.php by Jens Lehmann <jenslehmann@goldmail.de>
 * @see 	http://smarty.php.net/contribs/plugins/view.php/function.assign_array.php
 */
function smarty_function_assign_array($params, Smarty &$smarty) {

	if (empty($params['var'])) {
		$smarty->trigger_error('assign_array: missing "var" parameter');
		return;
	}

	if (empty($params['values'])) {
		$smarty->trigger_error('assign_array: missing "values" parameter');
		return;
	}

	if (empty($params['delimiter'])) {
		$params['delimiter'] = ',';
	}

	$smarty->assign($params['var'], explode($params['delimiter'], $params['values']) );

}

?>