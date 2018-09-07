<?php

require __DIR__ . '/../../maintenance/Maintenance.php';

define( 'MW_PARSER_TEST', true );

/**
 * Interactive parser test runner and test file editor
 */
class ParserEditTests extends Maintenance {
	private $termWidth;
	private $testFiles;
	private $testCount;
	private $recorder;
	private $runner;
	private $numExecuted;
	private $numSkipped;
	private $numFailed;

	function __construct() {
		parent::__construct();
		$this->addOption( 'session-data', 'internal option, do not use', false, true );
		$this->addOption( 'use-tidy-config',
			'Use the wiki\'s Tidy configuration instead of known-good' .
			'defaults.' );
	}

	public function finalSetup() {
		parent::finalSetup();
		self::requireTestsAutoloader();
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
		if ( $this->hasOption( 'use-tidy-config' ) ) {
			$this->session['options']['use-tidy-config'] = true;
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
			$this->testCount += count( $fileInfo['tests'] );
		}
	}

	protected function runTests() {
		$teardown = $this->runner->staticSetup();
		$teardown = $this->runner->setupDatabase( $teardown );
		$teardown = $this->runner->setupUploads( $teardown );

		print "Running tests...\n";
		$this->results = [];
		$this->numExecuted = 0;
		$this->numSkipped = 0;
		$this->numFailed = 0;
		foreach ( $this->testFiles as $fileName => $fileInfo ) {
			$this->runner->addArticles( $fileInfo['articles'] );
			foreach ( $fileInfo['tests'] as $testInfo ) {
				$result = $this->runner->runTest( $testInfo );
				if ( $result === false ) {
					$this->numSkipped++;
				} elseif ( !$result->isSuccess() ) {
					$this->results[$fileName][$testInfo['desc']] = $result;
					$this->numFailed++;
				}
				$this->numExecuted++;
				$this->showProgress();
			}
		}
		print "\n";
	}

	protected function showProgress() {
		$done = $this->numExecuted;
		$total = $this->testCount;
		$width = $this->termWidth - 9;
		$pos = round( $width * $done / $total );
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
			foreach ( $fileInfo['tests'] as $testInfo ) {
				if ( !isset( $this->results[$fileName][$testInfo['desc']] ) ) {
					continue;
				}
				$result = $this->results[$fileName][$testInfo['desc']];
				$testIndex++;
				if ( !$foundStart && $startTest !== false ) {
					if ( $testInfo['desc'] !== $startTest ) {
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

	protected function heading( $text ) {
		$term = new AnsiTermColorer;
		$heading = "─── $text ";
		$heading .= str_repeat( '─', $this->termWidth - mb_strlen( $heading ) );
		$heading = $term->color( 34 ) . $heading . $term->reset() . "\n";
		return $heading;
	}

	protected function unifiedDiff( $left, $right ) {
		$fromLines = explode( "\n", $left );
		$toLines = explode( "\n", $right );
		$formatter = new UnifiedDiffFormatter;
		return $formatter->format( new Diff( $fromLines, $toLines ) );
	}

	protected function handleFailure( $index, $testInfo, $result ) {
		$term = new AnsiTermColorer;
		$div1 = $term->color( 34 ) . str_repeat( '━', $this->termWidth ) .
			$term->reset() . "\n";
		$div2 = $term->color( 34 ) . str_repeat( '─', $this->termWidth ) .
			$term->reset() . "\n";

		print $div1;
		print "Failure $index/{$this->numFailed}: {$testInfo['file']} line {$testInfo['line']}\n" .
			"{$testInfo['desc']}\n";

		print $this->heading( 'Input' );
		print "{$testInfo['input']}\n";

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

		if ( $testInfo['options'] || $testInfo['config'] ) {
			print $this->heading( 'Options / Config' );
			if ( $testInfo['options'] ) {
				print $testInfo['options'] . "\n";
			}
			if ( $testInfo['config'] ) {
				print $testInfo['config'] . "\n";
			}
		}

		print $div2;
		print "What do you want to do?\n";
		$specs = [
			'[R]eload code and run again',
			'[U]pdate source file, copy actual to expected',
			'[I]gnore' ];

		if ( strpos( $testInfo['options'], ' tidy' ) === false ) {
			if ( empty( $testInfo['isSubtest'] ) ) {
				$specs[] = "Enable [T]idy";
			}
		} else {
			$specs[] = 'Disable [T]idy';
		}

		if ( !empty( $testInfo['isSubtest'] ) ) {
			$specs[] = 'Delete [s]ubtest';
		}
		$specs[] = '[D]elete test';
		$specs[] = '[Q]uit';

		$options = [];
		foreach ( $specs as $spec ) {
			if ( !preg_match( '/^(.*\[)(.)(\].*)$/', $spec, $m ) ) {
				throw new MWException( 'Invalid option spec: ' . $spec );
			}
			print '* ' . $m[1] . $term->color( 35 ) . $m[2] . $term->color( 0 ) . $m[3] . "\n";
			$options[strtoupper( $m[2] )] = true;
		}

		do {
			$response = $this->readconsole();
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

	protected function dwdiff( $expected, $actual ) {
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

	protected function alternatingAligned( $expectedStr, $actualStr ) {
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

	protected function reload( $testInfo ) {
		global $argv;
		pcntl_exec( PHP_BINARY, [
			$argv[0],
			'--session-data',
			json_encode( [
				'startFile' => $testInfo['file'],
				'startTest' => $testInfo['desc']
			] + $this->session ) ] );

		print "pcntl_exec() failed\n";
		return false;
	}

	protected function findTest( $file, $testInfo ) {
		$initialPart = '';
		for ( $i = 1; $i < $testInfo['line']; $i++ ) {
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
		if ( trim( $desc ) !== $testInfo['desc'] ) {
			print "Description does not match, cannot edit\n";
			return false;
		}
		$testPart .= $desc;
		return [ $initialPart, $testPart ];
	}

	protected function getOutputFileName( $inputFileName ) {
		if ( is_writable( $inputFileName ) ) {
			$outputFileName = $inputFileName;
		} else {
			$outputFileName = wfTempDir() . '/' . basename( $inputFileName );
			print "Cannot write to input file, writing to $outputFileName instead\n";
		}
		return $outputFileName;
	}

	protected function editTest( $fileName, $deletions, $changes ) {
		$text = file_get_contents( $fileName );
		if ( $text === false ) {
			print "Unable to open test file!";
			return false;
		}
		$result = TestFileEditor::edit( $text, $deletions, $changes,
			function ( $msg ) {
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

	protected function update( $testInfo, $result ) {
		$this->editTest( $testInfo['file'],
			[], // deletions
			[ // changes
				$testInfo['test'] => [
					$testInfo['resultSection'] => [
						'op' => 'update',
						'value' => $result->actual . "\n"
					]
				]
			]
		);
	}

	protected function deleteTest( $testInfo ) {
		$this->editTest( $testInfo['file'],
			[ $testInfo['test'] ], // deletions
			[] // changes
		);
	}

	protected function switchTidy( $testInfo ) {
		$resultSection = $testInfo['resultSection'];
		if ( in_array( $resultSection, [ 'html/php', 'html/*', 'html', 'result' ] ) ) {
			$newSection = 'html+tidy';
		} elseif ( in_array( $resultSection, [ 'html/php+tidy', 'html+tidy' ] ) ) {
			$newSection = 'html';
		} else {
			print "Unrecognised result section name \"$resultSection\"";
			return;
		}

		$this->editTest( $testInfo['file'],
			[], // deletions
			[ // changes
				$testInfo['test'] => [
					$resultSection => [
						'op' => 'rename',
						'value' => $newSection
					]
				]
			]
		);
	}

	protected function deleteSubtest( $testInfo ) {
		$this->editTest( $testInfo['file'],
			[], // deletions
			[ // changes
				$testInfo['test'] => [
					$testInfo['resultSection'] => [
						'op' => 'delete'
					]
				]
			]
		);
	}
}

$maintClass = 'ParserEditTests';
require RUN_MAINTENANCE_IF_MAIN;
