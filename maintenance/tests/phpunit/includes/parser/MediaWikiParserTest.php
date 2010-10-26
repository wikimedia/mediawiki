<?php

require_once( dirname( __FILE__ ) . '/ParserHelpers.php' );
require_once( dirname(dirname(dirname( __FILE__ ))) . '/bootstrap.php' );

class MediaWikiParserTest extends MediaWikiTestSetup {
	public $count;
	public $backend;

	public function __construct() {
		$suite = new PHPUnit_Framework_TestSuite('Parser Tests');
		parent::__construct($suite);
		$this->backend = new ParserTestSuiteBackend;
		$this->setName( 'Parser tests' );
	}

	public static function suite() {
		global $IP;

		$tester = new self;

		$iter = new TestFileIterator( "$IP/maintenance/tests/parser/parserTests.txt" );
		$iter->setParser( $tester );
		$tester->count = 0;

		foreach ( $iter as $test ) {
			$tester->suite->addTest( new ParserUnitTest( $tester, $test ), array( 'Parser', 'Broken' ) );
			$tester->count++;
		}

		return $tester->suite;
	}

	public function count() {
		return $this->count;
	}

	public function toString() {
		return "MediaWiki Parser Tests";
	}

	public function getBackend() {
		return $this->backend;
	}

	public function getIterator() {
		return $this->iterator;
	}
}

