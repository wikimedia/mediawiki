<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * @todo Make this more independent of the configuration (and if possible the database)
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( 'commandLine.inc' );
require_once( 'languages/LanguageUtf8.php' );

class ParserTest {
	
	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 *
	 * @access public
	 */
	function ParserTest() {
		if( isset( $_SERVER['argv'] ) && in_array( '--color', $_SERVER['argv'] ) ) {
			$this->color = true;
		} elseif( isset( $_SERVER['argv'] ) && in_array( '--color=yes', $_SERVER['argv'] ) ) {
			$this->color = true;
		} elseif( isset( $_SERVER['argv'] ) && in_array( '--color=no', $_SERVER['argv'] ) ) {
			$this->color = false;
		} elseif( wfIsWindows() ) {
			$this->color = false;
		} else {
			$this->color = true;
		}
		
		if( isset( $_SERVER['argv'] ) && in_array( '--quick', $_SERVER['argv'] ) ) {
			$this->showDiffs = false;
		} else {
			$this->showDiffs = true;
		}
	}
	
	
	/**
	 * Run a series of tests listed in the given text file.
	 * Each test consists of a brief description, wikitext input,
	 * and the expected HTML output.
	 *
	 * Prints status updates on stdout and counts up the total
	 * number and percentage of passed tests.
	 *
	 * @param string $filename
	 * @return bool True if passed all tests, false if any tests failed.
	 * @access public
	 */
	function runTestsFromFile( $filename ) {
		$infile = fopen( $filename, 'rt' );
		if( !$infile ) {
			die( "Couldn't open parserTests.txt\n" );
		}
		
		$data = array();
		$section = null;
		$success = 0;
		$total = 0;
		$n = 0;
		while( false !== ($line = fgets( $infile ) ) ) {
			$n++;
			if( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$section = strtolower( $matches[1] );
				if( $section == 'end' ) {
					if  (isset ($data['disabled'])) {
						# disabled test
						$data = array();
						$section = null;
						continue;
					}
					if( !isset( $data['test'] ) ) {
						die( "'end' without 'test' at line $n\n" );
					}
					if( !isset( $data['input'] ) ) {
						die( "'end' without 'input' at line $n\n" );
					}
					if( !isset( $data['result'] ) ) {
						die( "'end' without 'result' at line $n\n" );
					}
					if( $this->runTest(
						rtrim( $data['test'] ),
						rtrim( $data['input'] ),
						rtrim( $data['result'] ) ) ) {
						$success++;
					}
					$total++;
					$data = array();
					$section = null;
					continue;
				}
				$data[$section] = '';
				continue;
			}
			if( $section ) {
				$data[$section] .= $line;
			}
		}
		if( $total > 0 ) {
			$ratio = IntVal( 100.0 * $success / $total );
			print "\nPassed $success of $total tests ($ratio%)\n";
			return ($success == $total);
		} else {
			die( "No tests found.\n" );
		}
	}

	/**
	 * Run a given wikitext input through a freshly-constructed wiki parser,
	 * and compare the output against the expected results.
	 * Prints status and explanatory messages to stdout.
	 *
	 * @param string $input Wikitext to try rendering
	 * @param string $result Result to output
	 * @return bool
	 */
	function runTest( $desc, $input, $result ) {
		print "Running test $desc... ";

		$this->setupGlobals();
		
		$user =& new User();
		$options =& ParserOptions::newFromUser( $user );
		$parser =& new Parser();
		$title =& Title::makeTitle( NS_MAIN, 'Parser_test' );

		$output =& $parser->parse( $input, $title, $options );
		
		$html = $output->getText();
		# $languageLinks = $output->getLanguageLinks();
		# $categoryLinks = $output->getCategoryLinks();

		$op = new OutputPage();
		$op->replaceLinkHolders($html);

		global $wgUseTidy;
		if ($wgUseTidy) {
			# Using Parser here is probably theoretically
			# wrong, because we shouldn't use Parser to
			# validate itself, but this should be safe
			# in practice.
			$result = Parser::tidy($result);
		}
		
		$this->teardownGlobals();
		
		if( rtrim($result) === rtrim($html) ) {
			return $this->showSuccess( $desc );
		} else {
			return $this->showFailure( $desc, $result, $html );
		}
	}
	
	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 *
	 * @access private
	 */
	function setupGlobals() {
		$settings = array(
			'wgServer' => 'http://localhost',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => 'en',
			'wgUseLatin1' => false,
			'wgLang' => new LanguageUtf8(),
			);
		$this->savedGlobals = array();
		foreach( $settings as $var => $val ) {
			$this->savedGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $val;
		}
	}
	
	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 *
	 * @access private
	 */
	function teardownGlobals() {
		foreach( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
	}
	
	/**
	 * Print a happy success message.
	 *
	 * @param string $desc The test name
	 * @return bool
	 * @access private
	 */
	function showSuccess( $desc ) {
		print $this->termColor( '1;32' ) . 'PASSED' . $this->termReset() . "\n";
		return true;
	}
	
	/**
	 * Print a failure message and provide some explanatory output
	 * about what went wrong if so configured.
	 *
	 * @param string $desc The test name
	 * @param string $result Expected HTML output
	 * @param string $html Actual HTML output
	 * @return bool
	 * @access private
	 */
	function showFailure( $desc, $result, $html ) {
		print $this->termColor( '1;31' ) . 'FAILED!' . $this->termReset() . "\n";
		if( $this->showDiffs ) {
			print $this->quickDiff( $result, $html );
		}
		return false;
	}
	
	/**
	 * Run given strings through a diff and return the (colorized) output.
	 * Requires writable /tmp directory and a 'diff' command in the PATH.
	 *
	 * @param string $input
	 * @param string $output
	 * @return string
	 * @access private
	 */
	function quickDiff( $input, $output ) {
		$prefix = "/tmp/mwParser-" . mt_rand();
		
		$infile = "$prefix-expected";
		$this->dumpToFile( $input, $infile );
		
		$outfile = "$prefix-actual";
		$this->dumpToFile( $output, $outfile );
		
		$diff = `diff -u $infile $outfile`;
		unlink( $infile );
		unlink( $outfile );
		
		return $this->colorDiff( $diff );
	}
	
	/**
	 * Write the given string to a file, adding a final newline.
	 *
	 * @param string $data
	 * @param string $filename
	 * @access private
	 */
	function dumpToFile( $data, $filename ) {
		$file = fopen( $filename, "wt" );
		fwrite( $file, rtrim( $data ) . "\n" );
		fclose( $file );
	}
	
	/**
	 * Return ANSI terminal escape code for changing text attribs/color,
	 * or empty string if color output is disabled.
	 *
	 * @param string $color Semicolon-separated list of attribute/color codes
	 * @return string
	 * @access private
	 */
	function termColor( $color ) {
		return $this->color ? "\x1b[{$color}m" : '';
	}
	
	/**
	 * Return ANSI terminal escape code for restoring default text attributes,
	 * or empty string if color output is disabled.
	 *
	 * @return string
	 * @access private
	 */
	function termReset() {
		return $this->color ? "\x1b[0m" : '';
	}
	
	/**
	 * Colorize unified diff output if set for ANSI color output.
	 * Subtractions are colored blue, additions red.
	 *
	 * @param string $text
	 * @return string
	 * @access private
	 */
	function colorDiff( $text ) {
		return preg_replace(
			array( '/^(-.*)$/m', '/^(\+.*)$/m' ),
			array( $this->termColor( 34 ) . '$1' . $this->termReset(),
			       $this->termColor( 31 ) . '$1' . $this->termReset() ),
			$text );
	}
}

$wgTitle = Title::newFromText( 'Parser test script' );
$tester =& new ParserTest();

# Note: the command line setup changes the current working directory
# to the parent, which is why we have to put the subdir here:
$ok = $tester->runTestsFromFile( 'maintenance/parserTests.txt' );

exit ($ok ? 0 : -1);

?>
