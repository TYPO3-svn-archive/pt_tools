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


require_once t3lib_extMgm::extPath('pt_tools') . 'res/objects/class.tx_pttools_exception.php';

/**
 * Assertion class
 *
 * @see 	http://www.debuggable.com/posts/assert-the-yummyness-of-your-cake:480f4dd6-7fe0-4113-9776-458acbdd56cb
 * @author	Fabrizio Branca <mail@fabrizio-branca.de>
 * @since	2008-05-30
 */
class tx_pttools_assert {



    /**
     * Basic test method
     *
     * @param 	mixed	first parameter
     * @param	mixed	second parameter
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @param 	bool	(optional) if true (default), parameters are tested by identy and not only equality
     * @param	int		(optional) tx_pttools_exception error code, default is 0
     * @return 	void
     * @throws	tx_pttools_exceptionAssertion	if assertion fails
     */
    public static function test($val, $expected, array $info = array(), $strict = true) {

        // check values
        $success = ($strict) ? $val === $expected : $val == $expected;

        if ($success) {
            return;
        }

        // values do not match, preparing exception...

        $calls = debug_backtrace();
        foreach ($calls as $call) {
            if ($call['file'] !== __FILE__) {
                $assertCall = $call;
                break;
            }
        }
        $triggerCall = current($calls);


        $info = array_merge(
            array(
                'file'         => $assertCall['file'],
                'line'         => $assertCall['line'],
                'function'     => $triggerCall['class'] . '::' . $triggerCall['function'],
                'assertType'   => $assertCall['function'],
                'val'          => $val,
                'expected'     => $expected,
            ),
            $info
        );

        $debugMessage = '';
        foreach ($info as $key => $value) {
			$debugMessage .= sprintf('<span class="label">%1$s</span><span class="value %1$s">%2$s</span>', $key, $value);
        }
        $debugMessage = trim($debugMessage, ' ,');

        $exception = new tx_pttools_exceptionAssertion('Assertion "'.$assertCall['function'].'" failed! '.$info['message'], $debugMessage);
        $exception->setFile($assertCall['file']);
        $exception->setLine($assertCall['line']);
        if ($info['permanent']) {
        	$exception->setPermanent();
        }
        throw $exception;
    }



    /**
     * Test if value is true
     *
     * @param	mixed	value
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isTrue($val, array $info = array()) {

        return self::test($val, true, $info);
    }



    /**
     * Test if value is true
     *
     * @param	mixed	value
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotFalse($val, array $info = array()) {

        return self::test($val, true, $info, false);
    }



    /**
     * Test if value if false
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isFalse($val, array $info = array()) {

        return self::test($val, false, $info);
    }



    /**
     * Test if two values are equal
     *
     * @param 	mixed	$a
     * @param 	mixed	$b
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isEqual($a, $b, array $info = array()) {
		if ($info['message']) {
			$info['message'] = sprintf($info['message'], $a, $b);
		}
        return self::test($a, $b, $info, false);
    }



    /**
     * Test if two values are not equal
     *
     * @param 	mixed	$a
     * @param 	mixed	$b
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotEqual($a, $b, array $info = array()) {

        return self::test($a == $b, false, $info, true);
    }



    /**
     * Test if two values are identical
     *
     * @param 	mixed	$a
     * @param 	mixed	$b
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isIdentical($a, $b, array $info = array()) {

        return self::test($a, $b, $info, true);
    }



    /**
     * Test if two values are not identical
     *
     * @param 	mixed	$a
     * @param 	mixed	$b
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotIdentical($a, $b, array $info = array()) {

        return self::test($a === $b, false, $info, true);
    }



    /**
     * Test if a value matches a reqular expression
     *
     * @param 	string	pattern
     * @param 	string	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function matchesPattern($pattern, $val, array $info = array()) {
        self::isString($pattern);
        self::isString($val);
        return self::test(preg_match($pattern, $val), true, $info, false);
    }



    /**
     * Test if variable consists only of letters and digits
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isAlphaNum($val, array $info = array()) {
    	return self::matchesPattern('/^[\w\d]+$/', $val, $info);
    }


 	/**
     * Test if a this is a valid email
     *
     * @param 	string	email
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isValidEmail($email, array $info = array()) {
        self::isString($email);
        return self::test(t3lib_div::validEmail($email), true, $info);
    }

    /**
     * Test if variable is empty
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isEmpty($val, array $info = array()) {

        return self::test(empty($val), true, $info);
    }



    /**
     * test if variable is not empty
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotEmpty($val, array $info = array()) {

        return self::test(empty($val), false, $info);
    }



    /**
     * Test if value is numeric
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNumeric($val, array $info = array()) {

        return self::test(is_numeric($val), true, $info);
    }



    /**
     * Test if value is not numeric
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotNumeric($val, array $info = array()) {

        return self::test(is_numeric($val), false, $info);
    }



    /**
     * Test if value is an integer
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isInteger($val, array $info = array()) {

        return self::test(is_int($val), true, $info);
    }

    /**
     * Test if a value is a positive integer (allowing zero or not, depending on 2nd param)
     *
     * @param   mixed   value
     * @param   bool    (optional) allow "0", default is false
     * @param   array   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return  void
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-12-12
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isPositiveInteger($val, $allowZero = false, array $info = array()) {

        $info['tested_value'] = $val;
        $info['value_type'] = gettype($val);
        $info['zero_allowed'] = $allowZero ? 'true' : 'false';

        return self::test((is_int($val) && (intval($val) >= ($allowZero ? 0 : 1))), true, $info);

    }

    /**
     * Test if value is not an integer
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotInteger($val, array $info = array()) {

        return self::test(is_int($val), false, $info);
    }



    /**
     * Test if value is integerish
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isIntegerish($val, array $info = array()) {

        return self::test(is_int($val) || ctype_digit($val), true, $info);
    }



    /**
     * Test if value is not integerish
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotIntegerish($val, array $info = array()) {

        return self::test(is_int($val) || ctype_digit($val), false, $info);
    }



    /**
     * Test if value is an object
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isObject($val, array $info = array()) {

        return self::test(is_object($val), true, $info);
    }



    /**
     * Test if value is not an object
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotObject($val, array $info = array()) {

        return self::test(is_object($val), false, $info);
    }



    /**
     * Test if value is boolean
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isBoolean($val, array $info = array()) {

        return self::test(is_bool($val), true, $info);
    }



    /**
     * Test if value is not boolean
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotBoolean($val, array $info = array()) {

        return self::test(is_bool($val), false, $info);
    }



    /**
     * Test if value is a string
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isString($val, array $info = array()) {

        return self::test(is_string($val), true, $info);
    }



    /**
     * Test if value is not a string
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotString($val, array $info = array()) {

        return self::test(is_string($val), false, $info);
    }



    /**
     * Test if value is an array
     *
     * @param	mixed	value
     * @param 	array	(optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return 	void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isArray($val, array $info = array()) {

        return self::test(is_array($val), true, $info);
    }



    /**
     * Test if value is an associative array
     *
     * @param 	mixed	$val	Value to be tested
     * @param 	array	$info	Array of information
     * @return  void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isAssociativeArray($val, array $info = array()) {

        return self::test(tx_pttools_div::isAssociativeArray($val), true, $info);

    }



    /**
     * Test if value is not an array
     *
     * @param    mixed    value
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotArray($val, array $info = array()) {

        return self::test(is_array($val), false, $info);
    }



    /**
     * Test if a value is a non-empty array
     *
     * @param   mixed   value
     * @param   array   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-16
     */
    public static function isNotEmptyArray($val, array $info = array()) {

        return self::test((is_array($val) && count($val)) > 0, true, $info);

    }



    /**
     * Test if a value is in an array
     *
     * @param     mixed    value
     * @param     array     array
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-05-17
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isInArray($val, array $array, array $info = array()) {

        return self::test(in_array($val, $array), true, $info);
    }



    /**
     * Test if a value is in an array key
     *
     * @param     mixed    value
     * @param     array     array
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-05-17
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isArrayKey($val, array $array, array $info = array()) {

        return self::test(array_key_exists($val, $array), true, $info);
    }



    /**
     * Test if a value is in a comma separated list
     *
     * @param     string    value
     * @param     string     list
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-05-17
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isInList($val, $list, array $info = array()) {

        return self::test(t3lib_div::inList($list, $val), true, $info);
    }



    /**
     * Test if a value is in a range
     *
     * @param     mixed    value
     * @param     mixed     lower boundary
     * @param     mixed     higher boundary
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-07-01
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isInRange($val, $low, $high, array $info = array()) {
        $info['tested_value'] = $val;
        $info['range_low'] = $low;
        $info['range_high'] = $high;
        return self::test(($val >= $low && $val <= $high), true, $info);
    }



    /**
     * Test if a value is a valid uid for TYPO3 records. (positive integer)
     *
     * @param     mixed    value
     * @param     bool    (optional) allow "0", default is false
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-02
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isValidUid($val, $allowZero = false, array $info = array()) {

        // TODO: test if is_string or is_int

        // TODO: replace by regex? Test what is faster...
        $str = strval($val);
        return self::test(ctype_digit($str) && (strlen($str) == 1 || $str[0] != '0') && (intval($val) >= ($allowZero ? 0 : 1)), true, $info);
    }



    /**
     * Test if a value is an array with valid uids for TYPO3 records. (positive integer)
     *
     * @param     mixed    value
     * @param     bool    (optional) allow "0", default is false
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2009-12-23
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isValidUidArray($val, $allowZero = false, array $info = array()) {
    	self::isArray($val, $info);

    	foreach ($val as $uid) {
    		self::isValidUid($uid, $allowZero, $info);
    	}
    }



    /**
     * Test if value is a valid mysql ressource
     *
     * @param     mixed        value
     * @param     t3lib_DB    (optional) t3lib_DB used, default is NULL, then $GLOBALS['TYPO3_DB'] will be used
     * @param     array        (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-08
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isMySQLRessource($res, t3lib_DB $dbObj = NULL, array $info = array()) {
        if (is_null($dbObj)) {
            $dbObj = $GLOBALS['TYPO3_DB'];
        }
        self::isInstanceOf($dbObj, 't3lib_DB', $info);

        // append sql_error to info array
        $info['sql_error'] = $dbObj->sql_error();

        if (empty($info['message'])) {
            $info['message'] = $info['sql_error'];
        }

        // append debug_lastBuiltQuery to info array
        if (!empty($dbObj->debug_lastBuiltQuery)) {
            $info['debug_lastBuiltQuery'] = $dbObj->debug_lastBuiltQuery;
        }

        return self::test($dbObj->debug_check_recordset($res), true, $info);
    }



    /**
     * Test if an object is instance of a class or interface
     *
     * @deprecated     use self::isInstanceOf instead!
     * @param     mixed    value
     * @param     string    type
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-10
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isType($val, $type, array $info = array()) {
        self::isNotEmptyString($type, $info);

        return self::test($val instanceof $type, true, $info, true);
    }



    /**
     * Test if the value is a string that is not empty
     *
     * @param     mixed    value
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-10
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isNotEmptyString($val, array $info = array()) {

        return self::test(is_string($val) && (strlen($val)>0), true, $info);
    }



    /**
     * Test if a value is a valid and existing file
     *
     * @param     string    value
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-10
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isFilePath($val, array $info = array()) {
        self::isNotEmptyString($val, $info);

    	if ($info['message']) {
			$info['message'] = sprintf($info['message'], $val);
		}

        $filePath = t3lib_div::getFileAbsFileName($val);
        return self::test(t3lib_div::validPathStr($filePath) && is_file($filePath), true, $info);
    }



    /**
     * Test if a value is a valid and existing directory
     *
     * @param     string    value
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return     void
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-10
     * @throws  tx_pttools_exceptionAssertion   if assertion fails
     */
    public static function isDir($val, array $info = array()) {
        self::isNotEmptyString($val, $info);

        $filePath = t3lib_div::getFileAbsFileName($val, false);
        return self::test(t3lib_div::validPathStr($filePath) && is_dir($filePath), true, $info);
    }



    /**
     * Test for two variables being references to each other
     *
     * @param     mixed     first variable
     * @param     mixed     second variable
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-21
     */
    public static function isReference(&$a, &$b, array $info = array()) {

        if (is_object($a)) {
            $is_ref = ($a === $b);
        } else {
            $temp = $a;
            $a = uniqid('test');
            $is_ref = ($a === $b);
            $a = $temp;
        }
        return self::test($is_ref, true, $info);
    }



    /**
     * Test if an object is instance of a given class/interface
     *
     * @param     mixed    object
     * @param     mixed    class name
     * @param     array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @author    Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-06-21
     */
    public static function isInstanceOf($object, $class, array $info = array()) {
        self::isObject($object, $info);
        self::isNotEmptyString($class, $info);

        $info['class'] = $class;
        if (empty($info['message'])) {
            $info['message'] = sprintf('Object is not an instance of class "%s"!', $class);
        }
        return self::test($object instanceof $class, true, $info);
    }



    /**
     * Test if an object is a non-empty object collection of type tx_pttools_objectCollection
     *
     * @param   mixed   object
     * @param   array   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-01
     */
    public static function isNotEmptyObjectCollection($object, array $info = array()) {

        self::isInstanceOf($object, 'tx_pttools_objectCollection');

        return self::test(count($object) > 0, true, $info);

    }



    /**
     * Test if a variable is not null
     *
     * @param    mixed    value
     * @param    array    (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return   void
     * @author   Fabrizio Branca <mail@fabrizio-branca.de>
     * @since    2008-11-10
     */
    public static function isNotNull($val, array $info = array()) {
        return self::test(is_null($val), false, $info);
    }



    /**
     * Test if a variable is null
     *
     * @param   mixed   value
     * @param   array   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-11-10
     */
    public static function isNull($val, array $info = array()) {
        return self::test(is_null($val), true, $info);
    }



    /**
     * Test if a fe_user is logged in
     *
     * @param   array   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2008-11-11
     */
    public static function loggedIn(array $info = array()) {
        return self::test($GLOBALS['TSFE']->loginUser, true, $info, false);
    }



    /**
     * Test if a variable is the name of a table defined in TCA
     *
     * @param $val
     * @param $info   (optional) additional info, will be displayed as debug message, if a key "message" exists this will be appended to the error message
     * @return  void
     * @author  Fabrizio Branca <mail@fabrizio-branca.de>
     * @since   2010-07-07 (<- Worldcup semi final germany vs. spain)
     */
    public static function isTcaTable($val, array $info = array()) {
    	self::isNotEmptyString($val, $info);
    	return self::isArrayKey($val, $GLOBALS['TCA'], $info);
    }



}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_assert.php'])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/pt_tools/res/staticlib/class.tx_pttools_assert.php']);
}
?>