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
include_once( 'InitialiseMessages.inc' );

$wgTitle = Title::newFromText( 'Parser test script' );

class ParserTest {
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
	
	function setupGlobals() {
		$settings = array(
			'wgServer' => 'http://localhost',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => 'en',
			'wgLang' => new LanguageUtf8(),
			);
		$this->savedGlobals = array();
		foreach( $settings as $var => $val ) {
			$this->savedGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $val;
		}
	}
	
	function teardownGlobals() {
		foreach( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
	}
	
	function showSuccess( $desc ) {
		print $this->termColor( '1;32' ) . 'PASSED' . $this->termReset() . "\n";
		return true;
	}
	
	function showFailure( $desc, $result, $html ) {
		print $this->termColor( '1;31' ) . 'FAILED!' . $this->termReset() . "\n";
		if( $this->showDiffs ) {
			print $this->quickDiff( $result, $html );
		}
		return false;
	}
	
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
	
	function dumpToFile( $data, $filename ) {
		$file = fopen( $filename, "wt" );
		fwrite( $file, rtrim( $data ) . "\n" );
		fclose( $file );
	}
	
	function termColor( $color ) {
		return $this->color ? "\x1b[{$color}m" : '';
	}
	
	function termReset() {
		return $this->color ? "\x1b[0m" : '';
	}
	
	function colorDiff( $text ) {
		return preg_replace(
			array( '/^(-.*)$/m', '/^(\+.*)$/m' ),
			array( $this->termColor( 34 ) . '$1' . $this->termReset(),
			       $this->termColor( 31 ) . '$1' . $this->termReset() ),
			$text );
	}
}

$tester =& new ParserTest();

# Note: the command line setup changes the current working directory
# to the parent, which is why we have to put the subdir here:
$ok = $tester->runTestsFromFile( 'maintenance/parserTests.txt' );

exit ($ok ? 0 : -1);

?>
