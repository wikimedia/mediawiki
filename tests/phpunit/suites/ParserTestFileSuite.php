<?php

use PHPUnit\Framework\TestSuite;
use Wikimedia\Parsoid\ParserTests\TestFileReader;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;
use Wikimedia\ScopedCallback;

/**
 * This is the suite class for running tests with the legacy parser
 * within a single .txt source file.
 * It is not invoked directly. Use --filter to select files, or
 * use parserTests.php.
 */
class ParserTestFileSuite extends TestSuite {
	use SuiteEventsTrait;

	private $ptRunner;
	private $ptFileName;
	private $ptFileInfo;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	public function __construct( $runner, $name, $fileName ) {
		parent::__construct( $name );
		$this->ptRunner = $runner;
		$this->ptFileName = $fileName;
		try {
			$this->ptFileInfo = TestFileReader::read( $this->ptFileName, static function ( $msg ) {
				wfDeprecatedMsg( $msg, '1.35', false, false );
			} );
		} catch ( \Exception $e ) {
			// Friendlier wrapping for any syntax errors that might occur.
			throw new MWException(
				$fileName . ': ' . $e->getMessage()
			);
		}

		$skipMessage = $this->ptRunner->getFileSkipMessage(
			true, /* legacy parser */
			$this->ptFileInfo->fileOptions,
			$fileName
		);
		// Don't bother doing anything else if a skip message is set.
		if ( $skipMessage !== null ) {
			return;
		}

		$mode = new ParserTestMode( 'legacy' );
		foreach ( $this->ptFileInfo->testCases as $test ) {
			$this->addTest( new ParserIntegrationTest(
				$this->ptRunner, $fileName, $test, $mode, $skipMessage ),
				[ 'Database', 'Parser', 'ParserTests' ] );
		}
	}

	protected function setUp(): void {
		$this->ptTeardownScope = $this->ptRunner->addArticles(
			$this->ptFileInfo->articles
		);
	}

	protected function tearDown(): void {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
