<?php

require_once( dirname( __FILE__ ) . '/ParserHelpers.php' );
require_once( dirname(dirname(dirname( __FILE__ ))) . '/bootstrap.php' );

/**
 * @group Parser
 * @group Destructive
 * @group Database
 */
class MediaWikiParserTest extends MediaWikiTestCase {
	public $count;		// Number of tests in the suite.
	public $articles = array();	// Array of test articles defined by the tests
	protected $pt;
	
	function setUp() {
		global $wgContLang;
		$wgContLang = Language::factory( 'en' );
		
		$this->pt = new PHPUnitParserTest;
		$this->pt->setupDatabase();
		
	}
	
	function tearDown() {
		if( is_object( $this->pt ) && $this->pt instanceof PHPUnitParserTest ) {
			$this->pt->teardownDatabase();
			$this->pt = null;
		}
	}

	
	public function testParserTests() {
		//global $IP;
		//$wgParserTestFiles = array( "$IP/tests/parser/testparserTests.txt" );
		
		global $wgParserTestFiles;
		
		foreach( $wgParserTestFiles as $file ) {
			
			$iter = new TestFileIterator( $file, $this->pt );
			
			try {
				foreach( $iter as $test ) {
					$r = $this->pt->runTest( $test['test'], $test['input'],
						$test['result'], $test['options'], $test['config']
					);
					
					$this->assertTrue( $r, 'Parser test ' . $test['test'] );
					
				}
			} 
			catch( DBQueryError $e ) {
				$this->assertTrue( false, 'Parser test ' . $test['test'] . ' (error: "' . $e->getMessage() . '")' );
				//This is annoying... it always stops on error and doesn't go to the next one.
				continue;
			}
			
		}
		
	}

}

