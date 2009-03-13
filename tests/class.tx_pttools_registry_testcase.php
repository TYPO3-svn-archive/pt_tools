<?php

require_once(t3lib_extMgm::extPath('phpunit').'class.tx_phpunit_testcase.php');

require_once t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_registry.php';


/**
 * tx_pttools_registry test case.
 */
class tx_pttools_registry_testcase extends tx_phpunit_testcase {
	
	/**
	 * @var tx_pttools_registry
	 */
	private $registry;



	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {

		$this->registry = tx_pttools_registry::getInstance();
	
	}



	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {

		// TODO Auto-generated tx_pttools_registry::tearDown()
		

		$this->registry = null;
		
		parent::tearDown();
	}
	
	
	public function testIsReference() {
		
	}

}

