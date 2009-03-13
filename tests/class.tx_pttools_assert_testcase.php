<?php

require_once(t3lib_extMgm::extPath('phpunit').'class.tx_phpunit_testcase.php');

require_once(t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_assert.php');


/**
 * tx_pttools_assert test case.
 */
class tx_pttools_assert_testcase extends tx_phpunit_testcase {
	
	/**
	 * @var tx_pttools_assert
	 */
	private $fixture;



	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
        // As we want to test a static library, there is nothing to do here
	}



	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
        // As we want to test a static library, there is nothing to do here
	}





	/**
	 * Tests tx_pttools_assert::isArray()
	 */
	public function testIsArray() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsArray()
		$this->markTestIncomplete("isArray test not implemented");
		
		tx_pttools_assert::isArray(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isBoolean()
	 */
	public function testIsBoolean() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsBoolean()
		$this->markTestIncomplete("isBoolean test not implemented");
		
		tx_pttools_assert::isBoolean(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isEmpty()
	 */
	public function testIsEmpty() {
		tx_pttools_assert::isEmpty(0);
		tx_pttools_assert::isEmpty('');
		tx_pttools_assert::isEmpty('0');
		tx_pttools_assert::isEmpty(NULL);
		tx_pttools_assert::isEmpty(FALSE);
		tx_pttools_assert::isEmpty(array());	
	}
	
	public function testIsEmptyFailsWithNotZeroInt() {
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
		tx_pttools_assert::isEmpty(5);
	}
	
	public function testIsEmptyFailsWithNotEmptyString() {
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
		tx_pttools_assert::isEmpty('blub');
	}
	
	public function testIsEmptyFailsWithNotEmptyArray() {
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
		tx_pttools_assert::isEmpty(array('5'));
	}
	
	public function testIsEmptyFailsWithTrue() {
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
		tx_pttools_assert::isEmpty(true);
	}



	/**
	 * Tests tx_pttools_assert::isEqual()
	 */
	public function testIsEqual() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsEqual()
		$this->markTestIncomplete("isEqual test not implemented");
		
		tx_pttools_assert::isEqual(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isFalse()
	 */
	public function testIsFalse() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsFalse()
		$this->markTestIncomplete("isFalse test not implemented");
		
		tx_pttools_assert::isFalse(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isFilePath()
	 */
	public function testIsFilePath() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsFilePath()
		$this->markTestIncomplete("isFilePath test not implemented");
		
		tx_pttools_assert::isFilePath(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isIdentical()
	 */
	public function testIsIdentical() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsIdentical()
		$this->markTestIncomplete("isIdentical test not implemented");
		
		tx_pttools_assert::isIdentical(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isInArray()
	 */
	public function testIsInArray() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsInArray()
		$this->markTestIncomplete("isInArray test not implemented");
		
		tx_pttools_assert::isInArray(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isInList()
	 */
	public function testIsInList() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsInList()
		$this->markTestIncomplete("isInList test not implemented");
		
		tx_pttools_assert::isInList(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isInteger()
	 */
	public function testIsInteger() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsInteger()
		$this->markTestIncomplete("isInteger test not implemented");
		
		tx_pttools_assert::isInteger(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isIntegerish()
	 */
	public function testIsIntegerish() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsIntegerish()
		$this->markTestIncomplete("isIntegerish test not implemented");
		
		tx_pttools_assert::isIntegerish(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isMySQLRessource()
	 */
	public function testIsMySQLRessource() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsMySQLRessource()
		$this->markTestIncomplete("isMySQLRessource test not implemented");
		
		tx_pttools_assert::isMySQLRessource(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotArray()
	 */
	public function testIsNotArray() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotArray()
		$this->markTestIncomplete("isNotArray test not implemented");
		
		tx_pttools_assert::isNotArray(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotBoolean()
	 */
	public function testIsNotBoolean() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotBoolean()
		$this->markTestIncomplete("isNotBoolean test not implemented");
		
		tx_pttools_assert::isNotBoolean(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotEmpty()
	 */
	public function testIsNotEmpty() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotEmpty()
		$this->markTestIncomplete("isNotEmpty test not implemented");
		
		tx_pttools_assert::isNotEmpty(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotEmptyString()
	 */
	public function testIsNotEmptyString() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotEmptyString()
		$this->markTestIncomplete("isNotEmptyString test not implemented");
		
		tx_pttools_assert::isNotEmptyString(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotInteger()
	 */
	public function testIsNotInteger() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotInteger()
		$this->markTestIncomplete("isNotInteger test not implemented");
		
		tx_pttools_assert::isNotInteger(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotIntegerish()
	 */
	public function testIsNotIntegerish() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotIntegerish()
		$this->markTestIncomplete("isNotIntegerish test not implemented");
		
		tx_pttools_assert::isNotIntegerish(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotNumeric()
	 */
	public function testIsNotNumeric() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotNumeric()
		$this->markTestIncomplete("isNotNumeric test not implemented");
		
		tx_pttools_assert::isNotNumeric(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotObject()
	 */
	public function testIsNotObject() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotObject()
		$this->markTestIncomplete("isNotObject test not implemented");
		
		tx_pttools_assert::isNotObject(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNotString()
	 */
	public function testIsNotString() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNotString()
		$this->markTestIncomplete("isNotString test not implemented");
		
		tx_pttools_assert::isNotString(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isNumeric()
	 */
	public function testIsNumeric() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsNumeric()
		$this->markTestIncomplete("isNumeric test not implemented");
		
		tx_pttools_assert::isNumeric(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isObject()
	 */
	public function testIsObject() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsObject()
		$this->markTestIncomplete("isObject test not implemented");
		
		tx_pttools_assert::isObject(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isString()
	 */
	public function testIsString() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsString()
		$this->markTestIncomplete("isString test not implemented");
		
		tx_pttools_assert::isString(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isTrue()
	 */
	public function testIsTrue() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsTrue()
		$this->markTestIncomplete("isTrue test not implemented");
		
		tx_pttools_assert::isTrue(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::isType()
	 */
	public function testIsType() {

		// TODO Auto-generated tx_pttools_assert_testcase::testIsType()
		$this->markTestIncomplete("isType test not implemented");
		
		tx_pttools_assert::isType(/* parameters */);
	
	}


	public static function provideValidUids() {
	    return array(
	    	array('5', true),
	    	array('5', false),
	    	array(5, true),
	    	array(5, false),
	    	array(0, true),
	    );
	}

	/**
     * @dataProvider provideValidUids
	 */
	public function testIsValidUid($uid, $zeroAllowed) {
		tx_pttools_assert::isValidUid($uid, $zeroAllowed);
	}
	
	public static function provideInvalidUids() {
	    return array(
	    	array('-5', true),
	    	array('-5', false),
	    	array('0', false),
	    	array(0, false),
	    	array('N', true),
	    	array('N', false),
	    	array(0.5, true),
	    	array(0.5, false),
	    	array(-0.5, true),
	    	array(-0.5, false),
	    	array('-0.5', false),
	    	array('-0.5', true),
	    	/*
	    	array(array(), true),
	    	array(array(), false),
	    	array(new stdClass, false),
	    	array(new stdClass, false),
			*/
	    );
	}

	/**
     * @dataProvider provideInvalidUids
	 */
	public function testIsValidUidFails($uid, $zeroAllowed) {
        $this->setExpectedException('tx_pttools_exceptionAssertion');
        tx_pttools_assert::isValidUid($uid, $zeroAllowed);
	}

	public function testIsValidUidPassesWithZeroIfPermitted() {
        tx_pttools_assert::isValidUid('0', true);
        tx_pttools_assert::isValidUid(0, true);
	}
	
	public function testIsValidUidFailsWithNegativeNumbers() {
        $this->setExpectedException('tx_pttools_exceptionAssertion');
        tx_pttools_assert::isValidUid(-5);
	}
	
	public function testIsValidUidFailsWithNonIntegerValues() {
        $this->setExpectedException('tx_pttools_exceptionAssertion');
        tx_pttools_assert::isValidUid('N');
	}
	



	/**
	 * Tests tx_pttools_assert::matchesPattern()
	 */
	public function testMatchesPattern() {

		// TODO Auto-generated tx_pttools_assert_testcase::testMatchesPattern()
		$this->markTestIncomplete("matchesPattern test not implemented");
		
		tx_pttools_assert::matchesPattern(/* parameters */);
	
	}



	/**
	 * Tests tx_pttools_assert::test()
	 */
	public function testTest() {

		// TODO Auto-generated tx_pttools_assert_testcase::testTest()
		$this->markTestIncomplete("test test not implemented");
		
		tx_pttools_assert::test(/* parameters */);
	
	}
	
	
	
	public function testIsReference() {
	    
	    $object = new stdClass();	
		$objectReference = $object;
		
		$array = array("test");
		$arrayReference =& $array;
	    
	    tx_pttools_assert::isReference($object, $object);
	    tx_pttools_assert::isReference($objectReference, $object);
	    tx_pttools_assert::isReference($array, $array);
	    tx_pttools_assert::isReference($arrayReference, $array);
	}
	
	
	public function testIsReferenceFailsTwoObjects() {
	    
	    
	    $object = new stdClass();
		$otherObject = new stdClass();
	    
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
	    tx_pttools_assert::isReference($object, $otherObject);
	}
	
	
	public function testIsReferenceFailsObjectClone() {
	    
	    
	    $object = new stdClass();
	    $objectCopy = clone $object;
	    
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
	    tx_pttools_assert::isReference($object, $objectCopy);
	}
	
	
	public function testIsReferenceFailsTwoArrays() {
	    
		$array = array("test");
		$otherArray = array("test");
		
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
	    tx_pttools_assert::isReference($array, $otherArray);
	}
	
	
	
	public function testIsReferenceFailsArrayCopy() {
	    
		$array = array("test");
		$arrayCopy = $array;
	    
	    $this->setExpectedException('tx_pttools_exceptionAssertion');
	    tx_pttools_assert::isReference($array, $arrayCopy);
	}
	
	public function testIsDir() {
	    
	    if (!is_dir(sys_get_temp_dir())) {
	        throw new Exception('is_dir() failed!');
	    }
	    
	    $tmp_dir = sys_get_temp_dir();
	    tx_pttools_assert::isDir($tmp_dir);
	}
	

}

