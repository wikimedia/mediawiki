<?php

/**
 * This is the suite class for running tests within a single .txt source file.
 * It is not invoked directly. Use --filter to select files, or
 * use parserTests.php.
 */
class ParserTestFileSuite extends PHPUnit_Framework_TestSuite {
	private $ptRunner;
	private $ptFileName;
	private $ptFileInfo;

	function __construct( $runner, $name, $fileName ) {
		parent::__construct( $name );
		$this->ptRunner = $runner;
		$this->ptFileName = $fileName;
		$this->ptFileInfo = TestFileReader::read( $this->ptFileName );

		foreach ( $this->ptFileInfo['tests'] as $test ) {
			$this->addTest( new ParserIntegrationTest( $runner, $fileName, $test ),
				[ 'Database', 'Parser', 'ParserTests' ] );
		}
	}

	function setUp() {
		if ( !$this->ptRunner->meetsRequirements( $this->ptFileInfo['requirements'] ) ) {
			$this->markTestSuiteSkipped( 'required extension not enabled' );
		} else {
			$this->ptRunner->addArticles( $this->ptFileInfo[ 'articles'] );
		}
	}
}
