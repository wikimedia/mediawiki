<?php

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Tests\AnsiTermColorer;
use Wikimedia\Diff\Diff;
use Wikimedia\Diff\UnifiedDiffFormatter;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestFileReader;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;
use Wikimedia\ScopedCallback;

require_once __DIR__ . '/../../maintenance/Maintenance.php';

define( 'MW_AUTOLOAD_TEST_CLASSES', true );

/**
 * Interactive parser test runner and test file editor
 */
class ParserEditTests extends Maintenance {
	/** @var int */
	private $termWidth;
	/** @var TestFileReader[] */
	private $testFiles;
	/** @var int */
	private $testCount;
	/** @var TestRecorder */
	private $recorder;
	/** @var ParserTestRunner */
	private $runner;
	/** @var int */
	private $numExecuted;
	/** @var int */
	private $numSkipped;
	/** @var int */
	private $numFailed;
	/** @var array */
	private $results;
	/** @var array */
	private $session;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'session-data', 'internal option, do not use', false, true );
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		// Some methods which are discouraged for normal code throw exceptions unless
		// we declare this is just a test.
		define( 'MW_PHPUNIT_TEST', true );

		parent::finalSetup( $settingsBuilder );
		TestSetup::applyInitialConfig();
	}

	public function execute() {
		$this->termWidth = $this->getTermSize()[0] - 1;

		$this->recorder = new TestRecorder();
		$this->setupFileData();

		if ( $this->hasOption( 'session-data' ) ) {
			$this->session = json_decode( $this->getOption( 'session-data' ), true );
		} else {
			$this->session = [ 'options' => [] ];
		}
		$this->runner = new ParserTestRunner( $this->recorder, $this->session['options'] );

		$this->runTests();

		if ( $this->numFailed === 0 ) {
			if ( $this->numSkipped === 0 ) {
				print "All tests passed!\n";
			} else {
				print "All tests passed (but skipped {$this->numSkipped})\n";
			}
			return;
		}
		print "{$this->numFailed} test(s) failed.\n";
		$this->showResults();
	}

	protected function setupFileData() {
		$this->testFiles = [];
		$this->testCount = 0;
		foreach ( ParserTestRunner::getParserTestFiles() as $file ) {
			$fileInfo = TestFileReader::read( $file );
			$this->testFiles[$file] = $fileInfo;
			$this->testCount += count( $fileInfo->testCases );
		}
	}

	protected function getTestDesc( ParserTest $test ): string {
		return $test->testName ?? ''; // could include mode here too
	}

	protected function getResultSection( ParserTest $test ): string {
		// This used to switch between html and html+tidy, but we
		// got rid of the "notidy" support some time ago.
		// This should probably eventually support html+standalone
		// and html+integrated in a similar way, but for now just
		// walk the legacy HTML fallback set
		$legacyHtmlKeys = [
			'html/php', 'html/*', 'html',
			# deprecated
			'result',
			'html/php+tidy',
			'html/*+tidy',
			'html+tidy',
		];
		foreach ( $legacyHtmlKeys as $key ) {
			if ( $test->sections[$key] ?? false ) {
				return $key;
			}
		}
		return 'html';
	}

	protected function runTests() {
		$teardownGuard = null;
		$teardownGuard = $this->runner->setupDatabase( $teardownGuard );
		$teardownGuard = $this->runner->staticSetup( $teardownGuard );
		$teardownGuard = $this->runner->setupUploads( $teardownGuard );

		print "Running tests...\n";
		$this->results = [];
		$this->numExecuted = 0;
		$this->numSkipped = 0;
		$this->numFailed = 0;
		$mode = new ParserTestMode( 'legacy' );
		foreach ( $this->testFiles as $fileName => $fileInfo ) {
			$this->runner->addArticles( $fileInfo->articles );
			foreach ( $fileInfo->testCases as $testInfo ) {
				$result = $this->runner->runTest( $testInfo, $mode );
				if ( $result === false ) {
					$this->numSkipped++;
				} elseif ( !$result->isSuccess() ) {
					$desc = $this->getTestDesc( $testInfo );
					$this->results[$fileName][$desc] = $result;
					$this->numFailed++;
				}
				$this->numExecuted++;
				$this->showProgress();
			}
		}
		print "\n";

		ScopedCallback::consume( $teardownGuard );
	}

	protected function showProgress() {
		$done = $this->numExecuted;
		$total = $this->testCount;
		$width = $this->termWidth - 9;
		$pos = (int)round( $width * $done / $total );
		printf( '│' . str_repeat( '█', $pos ) . str_repeat( '-', $width - $pos ) .
			"│ %5.1f%%\r", $done / $total * 100 );
	}

	protected function showResults() {
		if ( isset( $this->session['startFile'] ) ) {
			$startFile = $this->session['startFile'];
			$startTest = $this->session['startTest'];
			$foundStart = false;
		} else {
			$startFile = false;
			$startTest = false;
			$foundStart = true;
		}

		$testIndex = 0;
		foreach ( $this->testFiles as $fileName => $fileInfo ) {
			if ( !isset( $this->results[$fileName] ) ) {
				continue;
			}
			if ( !$foundStart && $startFile !== false && $fileName !== $startFile ) {
				$testIndex += count( $this->results[$fileName] );
				continue;
			}
			foreach ( $fileInfo->testCases as $testInfo ) {
				$desc = $this->getTestDesc( $testInfo );
				if ( !isset( $this->results[$fileName][$desc] ) ) {
					continue;
				}
				$result = $this->results[$fileName][$desc];
				$testIndex++;
				if ( !$foundStart && $startTest !== false ) {
					if ( $desc !== $startTest ) {
						continue;
					}
					$foundStart = true;
				}

				$this->handleFailure( $testIndex, $testInfo, $result );
			}
		}

		if ( !$foundStart ) {
			print "Could not find the test after a restart, did you rename it?";
			unset( $this->session['startFile'] );
			unset( $this->session['startTest'] );
			$this->showResults();
		}
		print "All done\n";
	}

	protected function heading( string $text ): string {
		$term = new AnsiTermColorer;
		$heading = "─── $text ";
		$heading .= str_repeat( '─', $this->termWidth - mb_strlen( $heading ) );
		$heading = $term->color( '34' ) . $heading . $term->reset() . "\n";
		return $heading;
	}

	protected function unifiedDiff( string $left, string $right ): string {
		$fromLines = explode( "\n", $left );
		$toLines = explode( "\n", $right );
		$formatter = new UnifiedDiffFormatter;
		return $formatter->format( new Diff( $fromLines, $toLines ) );
	}

	protected function handleFailure( int $index, ParserTest $testInfo, ParserTestResult $result ) {
		$term = new AnsiTermColorer;
		$div1 = $term->color( '34' ) . str_repeat( '━', $this->termWidth ) .
			$term->reset() . "\n";
		$div2 = $term->color( '34' ) . str_repeat( '─', $this->termWidth ) .
			$term->reset() . "\n";

		$desc = $this->getTestDesc( $testInfo );
		print $div1;
		print "Failure $index/{$this->numFailed}: {$testInfo->filename} line {$testInfo->lineNumStart}\n" .
			"{$desc}\n";

		print $this->heading( 'Input' );
		print "{$testInfo->wikitext}\n";

		print $this->heading( 'Alternating expected/actual output' );
		print $this->alternatingAligned( $result->expected, $result->actual );

		print $this->heading( 'Diff' );

		$dwdiff = $this->dwdiff( $result->expected, $result->actual );
		if ( $dwdiff !== false ) {
			$diff = $dwdiff;
		} else {
			$diff = $this->unifiedDiff( $result->expected, $result->actual );
		}
		print $diff;

		if ( $testInfo->options || $testInfo->config ) {
			print $this->heading( 'Options / Config' );
			if ( $testInfo->options ) {
				print json_encode( $testInfo->options ) . "\n";
			}
			if ( $testInfo->config ) {
				print json_encode( $testInfo->config ) . "\n";
			}
		}

		print $div2;
		print "What do you want to do?\n";
		$specs = [
			'[R]eload code and run again',
			'[U]pdate source file, copy actual to expected',
			'[I]gnore' ];

		# XXX originally isSubtest was a way to edit the +tidy vs +untidy
		# portions of the test separately (I believe)
		// @phan-suppress-next-line PhanUndeclaredProperty
		if ( !empty( $testInfo->isSubtest ) ) {
			# FIXME: this is orphan code, will never be true
			$specs[] = 'Delete [s]ubtest';
		}
		$specs[] = '[D]elete test';
		$specs[] = '[Q]uit';

		$options = [];
		foreach ( $specs as $spec ) {
			if ( !preg_match( '/^(.*\[)(.)(\].*)$/', $spec, $m ) ) {
				throw new LogicException( 'Invalid option spec: ' . $spec );
			}
			print '* ' . $m[1] . $term->color( '35' ) . $m[2] . $term->color( '0' ) . $m[3] . "\n";
			$options[strtoupper( $m[2] )] = true;
		}

		do {
			$response = self::readconsole();
			$cmdResult = false;
			if ( $response === false ) {
				exit( 0 );
			}

			$response = strtoupper( trim( $response ) );
			if ( !isset( $options[$response] ) ) {
				print "Invalid response, please enter a single letter from the list above\n";
				continue;
			}

			switch ( strtoupper( trim( $response ) ) ) {
				case 'R':
					$cmdResult = $this->reload( $testInfo );
					break;
				case 'U':
					$cmdResult = $this->update( $testInfo, $result );
					break;
				case 'I':
					return;
				case 'T':
					$cmdResult = $this->switchTidy( $testInfo );
					break;
				case 'S':
					$cmdResult = $this->deleteSubtest( $testInfo );
					break;
				case 'D':
					$cmdResult = $this->deleteTest( $testInfo );
					break;
				case 'Q':
					exit( 0 );
			}
		} while ( !$cmdResult );
	}

	protected function dwdiff( string $expected, string $actual ): string|false {
		if ( !is_executable( '/usr/bin/dwdiff' ) ) {
			return false;
		}

		$markers = [
			"\n" => '¶',
			' ' => '·',
			"\t" => '→'
		];
		$markedExpected = strtr( $expected, $markers );
		$markedActual = strtr( $actual, $markers );
		$diff = $this->unifiedDiff( $markedExpected, $markedActual );

		$tempFile = tmpfile();
		fwrite( $tempFile, $diff );
		fseek( $tempFile, 0 );
		$pipes = [];
		$proc = proc_open( '/usr/bin/dwdiff -Pc --diff-input',
			[ 0 => $tempFile, 1 => [ 'pipe', 'w' ], 2 => STDERR ],
			$pipes );

		if ( !$proc ) {
			return false;
		}

		$result = stream_get_contents( $pipes[1] );
		proc_close( $proc );
		fclose( $tempFile );
		return $result;
	}

	protected function alternatingAligned( string $expectedStr, string $actualStr ): string {
		$expectedLines = explode( "\n", $expectedStr );
		$actualLines = explode( "\n", $actualStr );
		$maxLines = max( count( $expectedLines ), count( $actualLines ) );
		$result = '';
		for ( $i = 0; $i < $maxLines; $i++ ) {
			if ( $i < count( $expectedLines ) ) {
				$expectedLine = $expectedLines[$i];
				$expectedChunks = str_split( $expectedLine, $this->termWidth - 3 );
			} else {
				$expectedChunks = [];
			}

			if ( $i < count( $actualLines ) ) {
				$actualLine = $actualLines[$i];
				$actualChunks = str_split( $actualLine, $this->termWidth - 3 );
			} else {
				$actualChunks = [];
			}

			$maxChunks = max( count( $expectedChunks ), count( $actualChunks ) );

			for ( $j = 0; $j < $maxChunks; $j++ ) {
				if ( isset( $expectedChunks[$j] ) ) {
					$result .= "E: " . $expectedChunks[$j];
					if ( $j === count( $expectedChunks ) - 1 ) {
						$result .= "¶";
					}
					$result .= "\n";
				} else {
					$result .= "E:\n";
				}
				$result .= "\33[4m" . // underline
					"A: ";
				if ( isset( $actualChunks[$j] ) ) {
					$result .= $actualChunks[$j];
					if ( $j === count( $actualChunks ) - 1 ) {
						$result .= "¶";
					}
				}
				$result .= "\33[0m\n"; // reset
			}
		}
		return $result;
	}

	protected function reload( ParserTest $testInfo ): bool {
		global $argv;
		pcntl_exec( PHP_BINARY, [
			$argv[0],
			'--session-data',
			json_encode( [
				'startFile' => $testInfo->filename,
				'startTest' => $this->getTestDesc( $testInfo ),
			] + $this->session ) ] );

		print "pcntl_exec() failed\n";
		return false;
	}

	/**
	 * @param resource $file
	 * @param ParserTest $testInfo
	 */
	protected function findTest( $file, ParserTest $testInfo ): array|false {
		$initialPart = '';
		for ( $i = 1; $i < $testInfo->lineNumStart; $i++ ) {
			$line = fgets( $file );
			if ( $line === false ) {
				print "Error reading from file\n";
				return false;
			}
			$initialPart .= $line;
		}

		$line = fgets( $file );
		if ( !preg_match( '/^!!\s*test/', $line ) ) {
			print "Test has moved, cannot edit\n";
			return false;
		}

		$testPart = $line;

		$desc = fgets( $file );
		if ( trim( $desc ) !== $this->getTestDesc( $testInfo ) ) {
			print "Description does not match, cannot edit\n";
			return false;
		}
		$testPart .= $desc;
		return [ $initialPart, $testPart ];
	}

	protected function getOutputFileName( string $inputFileName ): string {
		if ( is_writable( $inputFileName ) ) {
			$outputFileName = $inputFileName;
		} else {
			$outputFileName = wfTempDir() . '/' . basename( $inputFileName );
			print "Cannot write to input file, writing to $outputFileName instead\n";
		}
		return $outputFileName;
	}

	protected function editTest( string $fileName, array $deletions, array $changes ) {
		$text = file_get_contents( $fileName );
		if ( $text === false ) {
			print "Unable to open test file!";
			return;
		}
		$result = TestFileEditor::edit( $text, $deletions, $changes,
			static function ( $msg ) {
				print "$msg\n";
			}
		);
		if ( is_writable( $fileName ) ) {
			file_put_contents( $fileName, $result );
			print "Wrote updated file\n";
		} else {
			print "Cannot write updated file, here is a patch you can paste:\n\n";
			print "--- {$fileName}\n" .
				"+++ {$fileName}~\n" .
				$this->unifiedDiff( $text, $result ) .
				"\n";
		}
	}

	protected function update( ParserTest $testInfo, ParserTestResult $result ): bool {
		$resultSection = $this->getResultSection( $testInfo );
		$this->editTest( $testInfo->filename,
			[], // deletions
			[ // changes
				$testInfo->testName => [
					$resultSection => [
						'op' => 'update',
						'value' => $result->actual . "\n"
					]
				]
			]
		);
		return false;
	}

	protected function deleteTest( ParserTest $testInfo ): bool {
		$this->editTest( $testInfo->filename,
			[ $testInfo->testName ], // deletions
			[] // changes
		);
		return false;
	}

	protected function switchTidy( ParserTest $testInfo ): bool {
		$resultSection = $this->getResultSection( $testInfo );
		if ( in_array( $resultSection, [ 'html/php' ] ) ) {
			$newSection = 'html/php';
		} elseif ( in_array( $resultSection, [ 'html/*', 'html', 'result' ] ) ) {
			$newSection = 'html';
		} else {
			print "Unrecognised result section name \"$resultSection\"";
			return true;
		}

		$this->editTest( $testInfo->filename,
			[], // deletions
			[ // changes
				$testInfo->testName => [
					$resultSection => [
						'op' => 'rename',
						'value' => $newSection
					]
				]
			]
		);
		return false;
	}

	protected function deleteSubtest( ParserTest $testInfo ): bool {
		$resultSection = $this->getResultSection( $testInfo );
		$this->editTest( $testInfo->filename,
			[], // deletions
			[ // changes
				$testInfo->testName => [
					$resultSection => [
						'op' => 'delete'
					]
				]
			]
		);
		return false;
	}
}

$maintClass = ParserEditTests::class;
require_once RUN_MAINTENANCE_IF_MAIN;
