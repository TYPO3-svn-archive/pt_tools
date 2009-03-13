<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2008 Rainer Kuhn (kuhn@punkt.de)
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

require_once(t3lib_extMgm::extPath('phpunit').'class.tx_phpunit_testcase.php');

/**
 * Inclusion of extension specific resources
 */
require_once t3lib_extMgm::extPath('pt_tools').'res/staticlib/class.tx_pttools_div.php';



/**
 * Testcase for class "tx_pttools_div"
 * 
 * $Id: class.tx_pttools_div_testcase.php,v 1.3 2008/10/22 07:25:17 ry44 Exp $
 * 
 * @author      Rainer Kuhn <kuhn@punkt.de>, Fabrizio Branca <branca@punkt.de>
 * @since       2008-10-09
 * @package     TYPO3
 * @subpackage  tx_pttools
 */
class tx_pttools_div_testcase extends tx_phpunit_testcase {
    
    
    /***************************************************************************
     *   SECTION: testing convertDate()
     **************************************************************************/
    
    /**   
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public function test_convertDateReturnsUsFormat() {
        $testDate = '24.12.2008';
        $expected = '2008-12-24';
        $this->assertEquals($expected, tx_pttools_div::convertDate($testDate));
    }
    
    /**   
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public function test_convertDateReturnsGermanFormat() {
        $testDate = '2008-12-24';
        $expected = '24.12.2008';
        $this->assertEquals($expected, tx_pttools_div::convertDate($testDate, 1));
    }
    
    /**   
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public static function provider_convertDateWithValidData() {
        return array(
            array('24.12.2008', '2008-12-24', 0), 
            array('2008-12-24', '24.12.2008', 1)
        );
    }
    
    /**
     * @dataProvider    provider_convertDateWithValidData
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public function test_convertDateWithValidData($input, $expected, $mode) {
        $this->assertEquals($expected, tx_pttools_div::convertDate($input, $mode));
    }
    
    /**
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public static function provider_convertDateWithInvalidData() {
        return array(
            array(24),
            // array('s'),
            array(NULL),
            array(array()),
            array(new stdClass())
        );
    }
    
    /**
     * @dataProvider    provider_convertDateWithInvalidData
     * @expectedException tx_pttools_exceptionAssertion
     * @author  Rainer Kuhn <kuhn@punkt.de>
     * @since   2008-10-09
     */
    public function test_convertDateWithInvalidData($input) {
        tx_pttools_div::convertDate($input);
    }
    
    
    /***************************************************************************
     *   SECTION: testing getTS()
     **************************************************************************/
    
    /**
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-10-15
     */
    public static function provider_exceptionOnInvalidTsPath() {
        return array(
            array(''),
            array(array()),
            array(new stdClass()),
            //array('plugin..test'),
            //array('.'),
            //array('-plugin'),
        );
    }
    
    /**
     * @dataProvider provider_exceptionOnInvalidTsPath
     * @expectedException tx_pttools_exceptionAssertion
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-10-15
     */
    public function test_exceptionOnInvalidTsPath($tsPath) {
        tx_pttools_div::getTS($tsPath, array('dummyToPreventUsageOfTSFE'));
    }
    
    /**
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-10-15
     */
    public function test_getTSReturnsAnArray() {
        // TODO: this should be a fixture
        $array = array(
        	'plugin.' => array(
                'my_ext.' => array(
                    'firstKey' => 'firstValue',
        			'secondKey' => 'secondValue',
                )
            )
        );
        $conf = tx_pttools_div::getTS('plugin.my_ext.', $array);
        $this->assertEquals($conf['firstKey'], 'firstValue');
        $this->assertEquals($conf['secondKey'], 'secondValue');
    }
    
    /**
     * @author	Fabrizio Branca <branca@punkt.de>
     * @since	2008-10-15
     */
    public function test_getTSResturnsAValue() {
        // TODO: this should be a fixture
        $array = array(
        	'plugin.' => array(
                'my_ext.' => array(
                    'firstKey' => 'firstValue',
        			'secondKey' => 'secondValue',
                )
            )
        );
        $conf = tx_pttools_div::getTS('plugin.my_ext.firstKey', $array);
        $this->assertEquals($conf, 'firstValue');
    }
    
}

