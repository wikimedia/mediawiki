<?php

use PHPUnit\Framework\TestSuite;

/**
 * This is the suite class for running tests within a single .txt source file.
 * It is not invoked directly. Use --filter to select files, or
 * use parserTests.php.
 */
class ParserTestFileSuite extends TestSuite {
	use SuiteEventsTrait;

	private $ptRunner;
	private $ptFileName;
	private $ptFileInfo;

	public function __construct( $runner, $name, $fileName ) {
		parent::__construct( $name );
		$this->ptRunner = $runner;
		$this->ptFileName = $fileName;
		try {
			$this->ptFileInfo = TestFileReader::read( $this->ptFileName );
		} catch ( \Exception $e ) {
			// Friendlier wrapping for any syntax errors that might occur.
			throw new MWException(
				$fileName . ': ' . $e->getMessage()
			);
		}
		if ( !$this->ptRunner->meetsRequirements( $this->ptFileInfo['requirements'] ) ) {
			$skipMessage = 'required extension not enabled';
		} else {
			$skipMessage = null;
		}

		foreach ( $this->ptFileInfo['tests'] as $test ) {
			$this->addTest( new ParserIntegrationTest( $runner, $fileName, $test, $skipMessage ),
				[ 'Database', 'Parser', 'ParserTests' ] );
		}
	}

	protected function setUp() : void {
		$this->ptRunner->addArticles( $this->ptFileInfo[ 'articles'] );
	}

	protected function tearDown() : void {
		$this->ptRunner->cleanupArticles( $this->ptFileInfo[ 'articles'] );
	}
}
