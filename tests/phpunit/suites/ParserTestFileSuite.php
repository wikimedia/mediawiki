<?php

use PHPUnit\Framework\TestSuite;
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
			$this->ptFileInfo = TestFileReader::read( $this->ptFileName );
		} catch ( \Exception $e ) {
			// Friendlier wrapping for any syntax errors that might occur.
			throw new MWException(
				$fileName . ': ' . $e->getMessage()
			);
		}
		if ( !$this->ptRunner->meetsRequirements( $this->ptFileInfo['fileOptions']['requirements'] ?? [] ) ) {
			$skipMessage = 'required extension not enabled';
		} else {
			$skipMessage = null;
		}

		foreach ( $this->ptFileInfo['tests'] as $test ) {
			$test['parsoid'] = false;
			$this->addTest( new ParserIntegrationTest( $runner, $fileName, $test, $skipMessage ),
				[ 'Database', 'Parser', 'ParserTests' ] );
		}
	}

	protected function setUp(): void {
		$this->ptTeardownScope = $this->ptRunner->addArticles(
			$this->ptFileInfo[ 'articles']
		);
	}

	protected function tearDown(): void {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
