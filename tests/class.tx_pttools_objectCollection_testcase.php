<?php

require_once(t3lib_extMgm::extPath('phpunit').'class.tx_phpunit_testcase.php');

require_once(t3lib_extMgm::extPath('pt_tools').'res/abstract/class.tx_pttools_objectCollection.php');



/**
 * Simple test object
 * TODO: replace by stdClass
 * 
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-06-09
 */
class tx_pttools_testObject {
}



/**
 * Test collection, because tx_pttools_objectCollection is an abstract class
 * TODO: find out how to test abstract classes without doing things like this
 * 
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-06-09
 */
class tx_pttools_testCollection extends tx_pttools_objectCollection {
    
    protected $restrictedClassName = 'tx_pttools_testObject';
    
    public function get_itemsArr() {
        return $this->itemsArr;
    }
}



/**
 * Testcase for class "tx_pttools_objectCollection"
 * 
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-06-09
 */
class tx_pttools_objectCollection_testcase extends tx_phpunit_testcase {

    /**
     * @var tx_pttools_testCollection
     */
	private $fixture;

	
	
	/**
	 * Setting up the fixture for the tests.
	 * This will be called before each single test
	 * 
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-06-09
	 */
	protected function setUp() {
		$this->fixture = new tx_pttools_testCollection();
	}

	
	/**
	 * Cleaning up after each single test
	 *
	 * @author	Fabrizio Branca <branca@punkt.de>
	 * @since	2008-06-09
	 */
	protected function tearDown() {
		unset($this->fixture);
	}
	
	
    /***************************************************************************
     * TEST METHODS
     **************************************************************************/
	
    public function test_addingAnObjectOfTheCorrectType() {
        $this->fixture->addItem(new tx_pttools_testObject, 1);
    	$this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
    	$this->assertType('tx_pttools_testObject', $this->fixture->getItemById(1), 'Object has not the right type!');
    }
    
    public function test_addingAnObjectOfTheWrongType() {
        $this->setExpectedException('tx_pttools_exception');
        $this->fixture->addItem(array('hello' => 'world'));
    }
    
    public function test_appendingAnObjectWithArrayAccess() {
        $this->fixture[1] = new tx_pttools_testObject();
    	$this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
    	$this->assertType('tx_pttools_testObject', $this->fixture->getItemById(1), 'Object has not the right type!');
    }
    
    public function test_setNonExistingIdAsSelected() {
        $this->setExpectedException('tx_pttools_exception');
        $this->fixture->set_selectedId(5);
    }
    
    public function test_setExistingIdAsSelected() {
        $this->fixture->addItem(new tx_pttools_testObject(), 5);
        $this->fixture->set_selectedId(5);
        $this->assertTrue($this->fixture->get_selectedId() === 5);
    }
    
    public function test_pushAndPopAnObject() {
        $this->fixture->push(new tx_pttools_testObject());
    	$this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
    	$this->assertType('tx_pttools_testObject', $this->fixture->pop(), 'Object has not the right type!');
    }
    
    public function test_unshiftAndShiftAnObject() {
        $this->fixture->unshift(new tx_pttools_testObject());
    	$this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
    	$this->assertType('tx_pttools_testObject', $this->fixture->shift(), 'Object has not the right type!');
    }
    
    public function test_integerishIdsChangeAfterShift() {
        $this->fixture->addItem(new tx_pttools_testObject(), 5);
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->shift(), 'Object has not the right type!');
        $this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(0));
    }
    
    public function test_integerishIdsDoNotChangeAfterPop() {
        $this->fixture->addItem(new tx_pttools_testObject(), 5);
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->pop(), 'Object has not the right type!');
        $this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(5));
    }
    
    public function test_integerishIdsChangeAfterUnshift() {
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->fixture->unshift(new tx_pttools_testObject());
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(0));
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(1));
    }
    
    public function test_integerishIdsDoNotChangeAfterPush() {
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->fixture->push(new tx_pttools_testObject());
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(6));
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(7));
    }
    
    public function test_seletectedIdIsClearedWhenSelectedItemIsPopped() {
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->fixture->set_selectedId(6);
        $this->fixture->pop();
        $this->assertNull($this->fixture->get_selectedId());
    }
    
    public function test_seletectedIdIsClearedWhenSelectedItemIsShifted() {
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->fixture->set_selectedId(6);
        $this->fixture->shift();
        $this->assertNull($this->fixture->get_selectedId());
    }
    
    public function test_integerishIdsDoNotChangeAfterShiftWithParameterTrue() {
        $this->fixture->addItem(new tx_pttools_testObject(), 5);
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->shift(true), 'Object has not the right type!');
        $this->assertTrue($this->fixture->count() === 1, 'Collection does not contain 1 item!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(6));
    }
    
    public function test_integerishIdsDoNotChangeAfterUnshiftWithSecondParameterTrue() {
        $this->fixture->addItem(new tx_pttools_testObject(), 6);
        $this->fixture->unshift(new tx_pttools_testObject(), true);
        $this->assertTrue($this->fixture->count() === 2, 'Collection does not contain 2 items!');
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(6));
        $this->assertType('tx_pttools_testObject', $this->fixture->getItemById(0));
    }
}


?>