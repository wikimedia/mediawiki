<?php

use PHPUnit\Framework\TestSuite;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestFileReader;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;
use Wikimedia\ScopedCallback;

/**
 * This is the suite class for running tests with Parsoid in integrated
 * mode within a single .txt source file.
 * It is not invoked directly. Use --filter to select files, or
 * use parserTests.php.
 */
class ParsoidTestFileSuite extends TestSuite {
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
			$this->ptFileInfo = TestFileReader::read( $fileName, static function ( $msg ) {
				wfDeprecatedMsg( $msg, '1.35', false, false );
			} );
		} catch ( \Exception $e ) {
			// Friendlier wrapping for any syntax errors that might occur.
			throw new MWException(
				$fileName . ': ' . $e->getMessage()
			);
		}

		$skipMessage = $this->ptRunner->getFileSkipMessage(
			false, /* parsoid tests */
			$this->ptFileInfo->fileOptions,
			$fileName
		);
		// Don't bother doing anything else if a skip message is set.
		if ( $skipMessage !== null ) {
			return;
		}
		$validTestModes = $this->ptRunner->getRequestedTestModes();
		// Dummy mode, for the purpose of satisfying the signature of getTestSkipMessage
		// Only used for an isLegacy check, which should always be false for this file
		$skipMode = new ParserTestMode( 'not-legacy' );

		// This is expected to be set at this point. $skipMessage above will have
		// skipped the file if not.
		$modeRestriction = $this->ptFileInfo->fileOptions['parsoid-compatible'];
		// Treat 'parsoid-compatible' as enabling all modes.
		if ( $modeRestriction !== '' ) {
			if ( is_string( $modeRestriction ) ) {
				// shorthand
				$modeRestriction = [ $modeRestriction ];
			}
			$validTestModes = array_intersect( $validTestModes, $modeRestriction );
		}

		$suite = $this;
		foreach ( $this->ptFileInfo->testCases as $t ) {
			$skipMessage = $this->ptRunner->getTestSkipMessage( $t, $skipMode );
			if ( $skipMessage ) {
				continue;
			}
			$testModes = $t->computeTestModes( $validTestModes );
			$t->testAllModes( $testModes, $runner->getOptions(),
				// $options is being ignored but it is identical to $runnerOpts
				function ( ParserTest $test, string $modeStr, array $options ) use (
					$t, $suite, $fileName, $skipMessage
				) {
					if ( $modeStr !== 'selser' || $test->changetree !== null ) {
						// $test could be a clone of $t
						// Ensure that updates to knownFailures in $test are reflected in $t
						$test->knownFailures = &$t->knownFailures;
						$runner = $this->ptRunner;
						$runnerOpts = $runner->getOptions();
						$mode = new ParserTestMode( $modeStr, $test->changetree );
						$pit = new ParserIntegrationTest( $runner, $fileName, $test, $mode, $skipMessage );
						$suite->addTest( $pit, [ 'Database', 'Parser', 'ParserTests' ] );
					}
				}
			);

			// Add a "selser-auto-composite" composite test
			if ( in_array( 'selser', $testModes ) &&
				( $runnerOpts['selser'] ?? null ) !== 'noauto' &&
				( $t->options['parsoid']['selser'] ?? null ) !== 'noauto'
			) {
				$mode = new ParserTestMode( 'selser-auto-composite', $runnerOpts['changetree'] ?? null );
				$pit = new ParserIntegrationTest( $runner, $fileName, $t, $mode, $skipMessage );
				$suite->addTest( $pit, [ 'Database', 'Parser', 'ParserTests' ] );
			}
		}
	}

	protected function setUp(): void {
		$this->ptTeardownScope = $this->ptRunner->addArticles(
			$this->ptFileInfo->articles
		);
	}

	protected function tearDown(): void {
		if ( $this->ptRunner->getOptions()['updateKnownFailures'] ) {
			$this->ptRunner->updateKnownFailures( $this->ptFileInfo );
		}

		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
	}
}
