<?php
/**
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require_once( 'commandLine.inc' );
include_once( 'InitialiseMessages.inc' );

$wgTitle = Title::newFromText( 'Parser test script' );

class ParserTest {
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
						$this->resultTransform(rtrim( $data['result'] ) ) ) ) {
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
		}
	}

	/**
	 * Substitute simple variables to allow for slightly more
	 * sophisticated tests.
	 * @access private
	 */
	function resultTransform($text) {
		$rep = array (
			'__SCRIPT__' => $GLOBALS['wgScript']
		);
		$text = str_replace(array_keys($rep), array_values($rep), $text);
		return $text;
	}

	/**
	 * @param string $input Wikitext to try rendering
	 * @param string $result Result to output
	 * @return bool
	 */
	function runTest( $desc, $input, $result ) {
		print "Running test $desc...";

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
		
		if( rtrim($result) === rtrim($html) ) {
			return $this->showSuccess( $desc );
		} else {
			return $this->showFailure( $desc, $result, $html );
		}
	}
	
	function showSuccess( $desc ) {
		print "ok\n";
		return true;
	}
	
	function showFailure( $desc, $result, $html ) {
		print "FAILED\n";
		print "!! Expected:\n$result\n";
		print "!! Received:\n$html\n!!\n";
		return false;
	}
}

$tester =& new ParserTest();
$tester->runTestsFromFile( 'maintenance/parserTests.txt' );

?>
