<?php

use PHPUnit\Framework\TestSuite;
use Wikimedia\Parsoid\ParserTests\Test as ParsoidTest;
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
		$runnerOpts = $this->ptRunner->getOptions();

		$this->ptFileName = $fileName;
		$this->ptFileInfo = Wikimedia\Parsoid\ParserTests\TestFileReader::read( $fileName, static function ( $msg ) {
			wfDeprecatedMsg( $msg, '1.35', false, false );
		} );

		$skipMessage = $this->ptRunner->getSkipMessage( $this->ptFileInfo->fileOptions, $runnerOpts, $fileName );
		// Don't bother doing anything else if a skip message is set.
		if ( $skipMessage !== null ) {
			return;
		}

		$validTestModes = $this->ptRunner->getRequestedTestModes();

		$suite = $this;
		foreach ( $this->ptFileInfo->testCases as $t ) {
			$testModes = $t->computeTestModes( $validTestModes );
			$test = $this->ptRunner->testToArray( $t );
			$t->testAllModes( $testModes, $runnerOpts,
				static function ( ParsoidTest $psdTest, string $mode, array $options ) use (
					$t, $suite, $runner, $runnerOpts, $fileName, $test, $skipMessage
				) {
					if ( $mode !== 'selser' || $runnerOpts['changetree'] === null ) {
						// $psdTest could be a clone of $t
						// Ensure that updates to knownFailures in $psdTest are reflected in $t
						$psdTest->knownFailures = &$t->knownFailures;
						// $options is being ignored but it is identical to $runnerOpts
						$newTest = $test;
						$newTest['parsoid'] = $psdTest;
						$newTest['parsoidMode'] = $mode;
						$newTest['parsoid-changetree'] = $psdTest->changetree;
						$pit = new ParserIntegrationTest( $runner, $fileName, $newTest, $skipMessage );
						$suite->addTest( $pit, [ 'Database', 'Parser', 'ParserTests' ] );
					}
				}
			);

			// Add a "selser-auto-composite" composite test
			if ( in_array( 'selser', $testModes ) &&
				$runnerOpts['selser'] !== 'noauto' &&
				( $t->options['parsoid']['selser'] ?? null ) !== 'noauto'
			) {
				$newTest = $test;
				$newTest['parsoid'] = $t;
				$newTest['parsoidMode'] = "selser-auto-composite";
				$newTest['parsoid-changetree'] = $runnerOpts['changetree'];
				$pit = new ParserIntegrationTest( $runner, $fileName, $newTest, $skipMessage );
				$suite->addTest( $pit, [ 'Database', 'Parser', 'ParserTests' ] );
			}
		}
	}

	protected function setUp(): void {
		$articles = [];
		foreach ( $this->ptFileInfo->articles as $a ) {
			$articles[] = [
				'name' => $a->title,
				'text' => $a->text,
				'line' => $a->lineNumStart,
				'file' => $a->filename,
			];
		}
		$this->ptTeardownScope = $this->ptRunner->addArticles( $articles );
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
