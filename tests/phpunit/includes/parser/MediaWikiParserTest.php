<?php

require_once( dirname( __FILE__ ) . '/ParserHelpers.php' );
require_once( dirname(dirname(dirname( __FILE__ ))) . '/bootstrap.php' );

class MediaWikiParserTest extends MediaWikiTestCase {
	public $count;		// Number of tests in the suite.
	public $backend;	// ParserTestSuiteBackend instance
	public $articles = array();	// Array of test articles defined by the tests

	public function __construct() {
		parent::__construct();
		$this->backend = new ParserTestSuiteBackend;
		$this->setName( 'Parser tests' );
	}

	public static function suite() {
		global $IP;

		$tester = new self;
		$tester->suite = new PHPUnit_Framework_TestSuite('Parser Tests');
		
		//Fixme: Use all the wgParserTestFiles (or whatever that global was...)
		$iter = new TestFileIterator( "$IP/tests/parser/parserTests.txt", $tester );
		$tester->count = 0;

		foreach ( $iter as $test ) {
			$tester->suite->addTest( new ParserUnitTest( $tester, $test ), array( 'Parser', 'Destructive', 'Database', 'Broken' ) );
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

	public function publishTestArticles() {
		if ( empty( $this->articles ) ) {
			return;
		}

		foreach ( $this->articles as $name => $text ) {
			$title = Title::newFromText( $name );

			if ( $title->getArticleID( Title::GAID_FOR_UPDATE ) == 0 ) {
				ParserTest::addArticle( $name, $text );
			}
		}
		$this->articles = array();
	}

	public function addArticle( $name, $text, $line ) {
		$this->articles[$name] = $text;
	}

	public function showRunFile( $path ) {
		/* Nothing shown when run from phpunit */
	}
}

