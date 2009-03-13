<?php

require_once(t3lib_extMgm::extPath('phpunit').'class.tx_phpunit_testcase.php');

require_once(t3lib_extMgm::extPath('pt_tools').'res/objects/class.tx_pttools_smartyAdapter.php');


/**
 * tx_pttools_smartyAdapter test case.
 * 
 * @version $Id: class.tx_pttools_smartyAdapter_testcase.php,v 1.4 2008/10/23 13:01:38 ry44 Exp $
 * @author	Fabrizio Branca <branca@punkt.de>
 * @since	2008-10-15
 */
class tx_pttools_smartyAdapter_testcase extends tx_phpunit_testcase {
	
	/**
	 * @var tx_pttools_smartyAdapter
	 */
	private $fixture;
	
	private $tmp_dir;
	

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
	    
	    // prepare directories
	    $tmp_dir = sys_get_temp_dir();
	    $time = time();
	    
	    
	    $this->tmp_dir = $tmp_dir . 'tx_pttools_smartyAdapter_testcase' . $time . '/';
	    $compile_dir = $this->tmp_dir . 'compile/';
	    $cache_dir = $this->tmp_dir . 'cache/'; 
	    
        $error = t3lib_div::mkdir_deep('/', $this->tmp_dir);
	    if ($error) throw new Exception($error);
	    
	    $error = t3lib_div::mkdir_deep('/', $compile_dir);
	    if ($error) throw new Exception($error);
	    
	    $error = t3lib_div::mkdir_deep('/', $cache_dir);
	    if ($error) throw new Exception($error);
	    
	    // create smarty adapter;
        $this->fixture = new tx_pttools_smartyAdapter(
            null, 
            array(
				'compile_dir'  => $compile_dir,
                'cache_dir'    => $cache_dir,
            )
        );
	}



	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {   
	    
	    unset($this->fixture);
	    
	    // remove directories
	    $ok = self::rmdir($this->tmp_dir, true);
	    if ($ok == false) {
	        throw new Exception('Removing directory "'.$this->tmp_dir.'" failed.');
	    }
	}	


	/**
	 * Wrapper function for rmdir, allowing recursive deletion of folders and files
	 * 
	 * This method was added in 4.2. As pt_tools can be used with 4.1 here is a copy of the new method 
	 *
	 * @param	string		Absolute path to folder, see PHP rmdir() function. Removes trailing slash internally.
	 * @param	boolean		Allow deletion of non-empty directories
	 * @return	boolean		true if @rmdir went well!
	 */
	public static function rmdir($path,$removeNonEmpty=false)	{
		$OK = false;
		$path = preg_replace('|/$|','',$path);	// Remove trailing slash

		if (file_exists($path))	{
			$OK = true;

			if (is_dir($path))	{
				if ($removeNonEmpty==true && $handle = opendir($path))	{
					while ($OK && false !== ($file = readdir($handle)))	{
						if ($file=='.' || $file=='..') continue;
						$OK = self::rmdir($path.'/'.$file,$removeNonEmpty);
					}
					closedir($handle);
				}
				if ($OK)	{ $OK = rmdir($path); }

			} else {	// If $dirname is a file, simply remove it
				$OK = unlink($path);
			}

			clearstatcache();
		}

		return $OK;
	}
	
	public function testUsingAStringResourceAsTemplate() {

        $template = 'This is my test template with one variable a: {$a}';
        
        $this->fixture->assign('a', 'content');
        $return = $this->fixture->fetch('string:'.$template);
        
        $this->assertEquals('This is my test template with one variable a: content', $return);	
	}
	
}

