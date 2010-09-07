<?php

require_once( 'ParserHelpers.php' );

class MediaWikiParserTestSuite extends PHPUnit_Framework_TestSuite {
	private $count;
	public $backend;

	public static function suite() {
		return new self;
	}

	public function __construct() {
		$this->backend = new ParserTestSuiteBackend;
		parent::__construct();
		$this->setName( 'Parser tests' );
	}

	public function run( PHPUnit_Framework_TestResult $result = null, $filter = false, 
		array $groups = array(), array $excludeGroups = array(), $processIsolation = false
	) {
		global $IP, $wgContLang, $wgMemc;
		$wgContLang = Language::factory( 'en' );
		$wgMemc = new FakeMemCachedClient;
		$this->backend->setupDatabase();

		$iter = new TestFileIterator( "$IP/maintenance/parserTests.txt" );
		$iter->setParser( $this->backend );
		$this->count = 0;

		foreach ( $iter as $test ) {
			$this->addTest( new ParserUnitTest( $this, $test ) );
			$this->count++;
		}

		parent::run( $result, $filter, $groups, $excludeGroups, $processIsolation );

		$this->backend->teardownDatabase();
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

