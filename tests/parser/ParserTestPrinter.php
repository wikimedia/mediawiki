<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Testing
 */

use MediaWiki\Parser\Sanitizer;
use MediaWiki\Shell\Shell;
use MediaWiki\Tests\AnsiTermColorer;
use MediaWiki\Tests\DummyTermColorer;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;

/**
 * This is a TestRecorder responsible for printing information about progress,
 * success and failure to the console. It is specific to the parserTests.php
 * frontend.
 */
class ParserTestPrinter extends TestRecorder {
	/** @var int */
	private $total;
	/** @var int */
	private $success;
	/** @var int */
	private $skipped;
	/** @var AnsiTermColorer|DummyTermColorer */
	private $term;
	/** @var bool */
	private $showDiffs;
	/** @var bool */
	private $showProgress;
	/** @var bool */
	private $showFailure;
	/** @var bool */
	private $showOutput;
	/** @var bool */
	private $useDwdiff;
	/** @var bool */
	private $markWhitespace;
	/** @var string */
	private $xmlError;

	public function __construct( AnsiTermColorer|DummyTermColorer $term, array $options ) {
		$this->term = $term;
		$options += [
			'showDiffs' => true,
			'showProgress' => true,
			'showFailure' => true,
			'showOutput' => false,
			'useDwdiff' => false,
			'markWhitespace' => false,
		];
		$this->showDiffs = $options['showDiffs'];
		$this->showProgress = $options['showProgress'];
		$this->showFailure = $options['showFailure'];
		$this->showOutput = $options['showOutput'];
		$this->useDwdiff = $options['useDwdiff'];
		$this->markWhitespace = $options['markWhitespace'];
	}

	public function start() {
		$this->total = 0;
		$this->success = 0;
		$this->skipped = 0;
	}

	public function startTest( ParserTest $test, ParserTestMode $mode ) {
		if ( $this->showProgress ) {
			$fake = new ParserTestResult( $test, $mode, '', '' );
			$this->showTesting( $fake->getDescription() );
		}
	}

	/**
	 * @param string $desc Test description
	 */
	private function showTesting( string $desc ) {
		self::print( "Running test $desc... " );
	}

	/**
	 * Show "Reading tests from ..."
	 *
	 * @param string $path
	 * @param-taint $path none
	 */
	public function startSuite( string $path ) {
		print $this->term->color( 1 ) .
			"Running parser tests from \"$path\"..." .
			$this->term->reset() .
			"\n";
	}

	public function endSuite( string $path ) {
		print "\n";
	}

	public function record( ParserTestResult $result ): void {
		$this->total++;
		$this->success += ( $result->isSuccess() ? 1 : 0 );

		if ( $result->isSuccess() ) {
			$this->showSuccess();
		} else {
			$this->showFailure( $result );
		}
	}

	/**
	 * Print a happy success message.
	 */
	private function showSuccess(): void {
		if ( $this->showProgress ) {
			print $this->term->color( '1;32' ) . 'PASSED' . $this->term->reset() . "\n";
		}
	}

	/**
	 * Helper function to ensure the phan SecurityCheckPlugin does not register
	 * false "XSS" positives on parser test output, as this is a CLI tool.
	 *
	 * @param string $str Output string
	 * @param-taint $str none
	 */
	private static function print( string $str ) {
		print $str;
	}

	/**
	 * Print a failure message and provide some explanatory output
	 * about what went wrong if so configured.
	 */
	private function showFailure( ParserTestResult $testResult ): void {
		if ( $this->showFailure ) {
			if ( !$this->showProgress ) {
				# In quiet mode we didn't show the 'Testing' message before the
				# test, in case it succeeded. Show it now:
				$this->showTesting( $testResult->getDescription() );
			}

			print $this->term->color( '31' ) . 'FAILED!' . $this->term->reset() . "\n";

			print "{$testResult->test->filename}:{$testResult->test->lineNumStart}\n";

			if ( $this->showOutput ) {
				self::print( "--- Expected ---\n{$testResult->expected}\n" );
				self::print( "--- Actual ---\n{$testResult->actual}\n" );
			}

			if ( $this->showDiffs ) {
				self::print( $this->quickDiff( $testResult->expected, $testResult->actual ) );
				if ( !$this->wellFormed( $testResult->actual ) ) {
					self::print( "XML error: $this->xmlError\n" );
				}
			}
		}
	}

	/**
	 * Run given strings through a diff and return the (colorized) output.
	 * Requires writable /tmp directory and a 'diff' command in the PATH.
	 *
	 * @param string $input
	 * @param string $output
	 * @param string $inFileTail Tailing for the input file name
	 * @param string $outFileTail Tailing for the output file name
	 * @return string
	 */
	private function quickDiff( $input, $output,
		$inFileTail = 'expected', $outFileTail = 'actual'
	) {
		if ( $this->markWhitespace ) {
			$pairs = [
				"\n" => '¶',
				' ' => '·',
				"\t" => '→'
			];
			$input = strtr( $input, $pairs );
			$output = strtr( $output, $pairs );
		}

		$infile = tempnam( wfTempDir(), "mwParser-$inFileTail" );
		$this->dumpToFile( $input, $infile );

		$outfile = tempnam( wfTempDir(), "mwParser-$outFileTail" );
		$this->dumpToFile( $output, $outfile );

		global $wgDiff3;
		// we assume that people with diff3 also have usual diff
		if ( $this->useDwdiff ) {
			$shellCommand = 'dwdiff -Pc';
		} else {
			$shellCommand = ( wfIsWindows() && !$wgDiff3 ) ? 'fc' : 'diff -au';
		}

		$result = Shell::command()
			->unsafeParams( $shellCommand )
			->params( $infile, $outfile )
			->execute();
		$diff = $result->getStdout();

		unlink( $infile );
		unlink( $outfile );

		if ( $this->useDwdiff ) {
			return $diff;
		} else {
			return $this->colorDiff( $diff );
		}
	}

	/**
	 * Write the given string to a file, adding a final newline.
	 *
	 * @param string $data
	 * @param string $filename
	 */
	private function dumpToFile( $data, $filename ) {
		$file = fopen( $filename, "wt" );
		fwrite( $file, $data . "\n" );
		fclose( $file );
	}

	/**
	 * Colorize unified diff output if set for ANSI color output.
	 * Subtractions are colored blue, additions red.
	 *
	 * @param string $text
	 * @return string
	 */
	private function colorDiff( $text ) {
		return preg_replace(
			[ '/^(-.*)$/m', '/^(\+.*)$/m' ],
			[ $this->term->color( '34' ) . '$1' . $this->term->reset(),
				$this->term->color( '31' ) . '$1' . $this->term->reset() ],
			$text );
	}

	private function wellFormed( string $text ): bool {
		$html =
			Sanitizer::hackDocType() .
				'<html>' .
				$text .
				'</html>';

		$parser = xml_parser_create( "UTF-8" );

		# case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 );

		if ( !xml_parse( $parser, $html, true ) ) {
			$err = xml_error_string( xml_get_error_code( $parser ) );
			$position = xml_get_current_byte_index( $parser );
			$fragment = $this->extractFragment( $html, $position );
			$this->xmlError = "$err at byte $position:\n$fragment";
			xml_parser_free( $parser );

			return false;
		}

		xml_parser_free( $parser );

		return true;
	}

	private function extractFragment( string $text, int $position ): string {
		$start = max( 0, $position - 10 );
		$before = $position - $start;
		$fragment = '...' .
			$this->term->color( '34' ) .
			substr( $text, $start, $before ) .
			$this->term->color( '0' ) .
			$this->term->color( '31' ) .
			$this->term->color( '1' ) .
			substr( $text, $position, 1 ) .
			$this->term->color( '0' ) .
			$this->term->color( '34' ) .
			substr( $text, $position + 1, 9 ) .
			$this->term->color( '0' ) .
			'...';
		$display = str_replace( "\n", ' ', $fragment );
		$caret = '   ' .
			str_repeat( ' ', $before ) .
			$this->term->color( '31' ) .
			'^' .
			$this->term->color( '0' );

		return "$display\n$caret";
	}

	/**
	 * Show a warning to the user
	 */
	public function warning( string $message ) {
		echo "$message\n";
	}

	/**
	 * Mark a test skipped
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @param string $subtest
	 */
	public function skipped( ParserTest $test, ParserTestMode $mode, string $subtest ) {
		if ( $this->showProgress ) {
			print $this->term->color( '1;33' ) . 'SKIPPED' . $this->term->reset() . "\n";
		}
		$this->skipped++;
	}

	public function report() {
		if ( $this->total > 0 ) {
			$this->reportPercentage( $this->success, $this->total );
		} else {
			print $this->term->color( '31' ) . "No tests found." . $this->term->reset() . "\n";
		}
	}

	/**
	 * @param int $success Number of passed tests
	 * @param int $total Number of total tests
	 * @return bool True if and only if all tests passed
	 */
	private function reportPercentage( $success, $total ) {
		$ratio = wfPercent( 100 * $success / $total );
		self::print( $this->term->color( '1' ) . "Passed $success of $total tests ($ratio)" );
		if ( $this->skipped ) {
			self::print( ", skipped {$this->skipped}" );
		}
		print "... ";

		if ( $success == $total ) {
			print $this->term->color( '32' ) . "ALL TESTS PASSED!";
		} else {
			$failed = $total - $success;
			self::print( $this->term->color( '31' ) . "$failed tests failed!" );
		}

		print $this->term->reset() . "\n";

		return ( $success == $total );
	}
}
