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
$optionsWithArgs = array('regex');

require_once( 'commandLine.inc' );
require_once( 'languages/LanguageUtf8.php' );

/** */
class ParserTest {
	/**
	 * boolean $color whereas output should be colorized
	 * @access private
	 */
	var $color;

	/**
	 * boolean $lightcolor whereas output should use light colors
	 * @access private
	 */
	var $lightcolor;

	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 *
	 * @access public
	 */
	function ParserTest() {
		global $options;
		$this->lightcolor = false;
		if( isset( $_SERVER['argv'] ) && in_array( '--color', $_SERVER['argv'] ) ) {
			$this->color = true;
		} elseif( isset( $_SERVER['argv'] ) && in_array( '--color=yes', $_SERVER['argv'] ) ) {
			$this->color = true;
		} elseif( isset( $_SERVER['argv'] ) && in_array( '--color=light', $_SERVER['argv'] ) ) {
			$this->color = true;
			$this->lightcolor = true;
		} elseif( isset( $_SERVER['argv'] ) && in_array( '--color=no', $_SERVER['argv'] ) ) {
			$this->color = false;
		} elseif( wfIsWindows() ) {
			$this->color = false;
		} else {
			# Only colorize output if stdout is a terminal.
			$this->color = posix_isatty(1);
		}
		
		if( isset( $_SERVER['argv'] ) && in_array( '--quick', $_SERVER['argv'] ) ) {
			$this->showDiffs = false;
		} else {
			$this->showDiffs = true;
		}

		if (isset($options['regex'])) {
			$this->regex = $options['regex'];
		}
		else {
			# Matches anything
			$this->regex = '';
		}
	}

	/**
	 * Remove last character if it is a newline
	 * @access private
	 */
	function chomp($s) {
		if (substr($s, -1) === "\n") {
			return substr($s, 0, -1);
		}
		else {
			return $s;
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
				if( $section == 'endarticle') {
					if( !isset( $data['text'] ) ) {
						die( "'endarticle' without 'text' at line $n\n" );
					}
					if( !isset( $data['article'] ) ) {
						die( "'endarticle' without 'article' at line $n\n" );
					}
					$this->addArticle($this->chomp($data['article']), $this->chomp($data['text']), $n);
					$data = array();
					$section = null;
					continue;
				}
				if( $section == 'end' ) {
					if( !isset( $data['test'] ) ) {
						die( "'end' without 'test' at line $n\n" );
					}
					if( !isset( $data['input'] ) ) {
						die( "'end' without 'input' at line $n\n" );
					}
					if( !isset( $data['result'] ) ) {
						die( "'end' without 'result' at line $n\n" );
					}
					if( !isset( $data['options'] ) ) {
						$data['options'] = '';
					}
					else {
						$data['options'] = $this->chomp( $data['options'] );
					}
					if (preg_match('/\\bdisabled\\b/i', $data['options'])
						|| !preg_match("/{$this->regex}/i", $data['test'])) {
						# disabled test
						$data = array();
						$section = null;
						continue;
					}
					if( $this->runTest(
						$this->chomp( $data['test'] ),
						$this->chomp( $data['input'] ),
						$this->chomp( $data['result'] ),
						$this->chomp( $data['options'] ) ) ) {
						$success++;
					}
					$total++;
					$data = array();
					$section = null;
					continue;
				}
				if ( isset ($data[$section] ) ) {
					die ( "duplicate section '$section' at line $n\n" );
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
			print $this->termColor( 1 ) . "\nPassed $success of $total tests ($ratio%) ";
			if( $success == $total ) {
				print $this->termColor( 32 ) . "PASSED!";
			} else {
				print $this->termColor( 31 ) . "FAILED!";
			}
			print $this->termReset() . "\n";
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
	function runTest( $desc, $input, $result, $opts ) {
		print "Running test $desc... ";

		$this->setupGlobals($opts);

		$user =& new User();
		$options =& ParserOptions::newFromUser( $user );

		if (preg_match('/\\bmath\\b/i', $opts)) {
			# XXX this should probably be done by the ParserOptions
			require_once('Math.php');

			$options->setUseTex(true);
		}

		if (preg_match('/title=\[\[(.*)\]\]/', $opts, $m)) {
			$titleText = $m[1];
		}
		else {
			$titleText = 'Parser test';
		}

		$parser =& new Parser();
		$title =& Title::makeTitle( NS_MAIN, $titleText );

		if (preg_match('/\\bpst\\b/i', $opts)) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		}
		else if (preg_match('/\\bmsg\\b/i', $opts)) {
			$out = $parser->transformMsg( $input, $options );
		}
		else {
			$output =& $parser->parse( $input, $title, $options );
			$out = $output->getText();

			if (preg_match('/\\bill\\b/i', $opts)) {
				$out = $this->tidy( implode( ' ', $output->getLanguageLinks() ) );
			}	
			else if (preg_match('/\\bcat\\b/i', $opts)) {
				$out = $this->tidy ( implode( ' ', $output->getCategoryLinks() ) );
			}

			$result = $this->tidy($result);
		}
		
		$this->teardownGlobals();
		
		if( $result === $out ) {
			return $this->showSuccess( $desc );
		} else {
			return $this->showFailure( $desc, $result, $out );
		}
	}
	
	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 *
	 * @access private
	 */
	function setupGlobals($opts = '') {
		# Save the prefixed / quoted table names for later use when we make the temporaries.
		$db =& wfGetDB( DB_READ );
		$this->oldTableNames = array();
		foreach( $this->listTables() as $table ) {
			$this->oldTableNames[$table] = $db->tableName( $table );
		}
		
		$settings = array(
			'wgServer' => 'http://localhost',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgUploadPath' => '/images',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => 'en',
			'wgUseLatin1' => false,
			'wgDBprefix' => 'parsertest',
			
			'wgLoadBalancer' => LoadBalancer::newFromParams( $GLOBALS['wgDBservers'] ),
			'wgLang' => new LanguageUtf8(),
			'wgNamespacesWithSubpages' => array( 0 => preg_match('/\\bsubpage\\b/i', $opts)),
			'wgMaxTocLevel' => 999,
			);
		$this->savedGlobals = array();
		foreach( $settings as $var => $val ) {
			$this->savedGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $val;
		}
		$GLOBALS['wgLoadBalancer']->loadMasterPos();
		$this->setupDatabase();
	}
	
	# List of temporary tables to create, without prefix
	# Some of these probably aren't necessary
	function listTables() {
		return array('user', 'cur', 'old', 'links',
			'brokenlinks', 'imagelinks', 'categorylinks',
			'linkscc', 'site_stats', 'hitcounter',
			'ipblocks', 'image', 'oldimage',
			'recentchanges',
			'watchlist', 'math', 'searchindex',
			'interwiki', 'querycache',
			'objectcache'
		);
	}
	
	/**
	 * Set up a temporary set of wiki tables to work with for the tests.
	 * Currently this will only be done once per run, and any changes to
	 * the db will be visible to later tests in the run.
	 *
	 * @access private
	 */
	function setupDatabase() {
		static $setupDB = false;
		global $wgDBprefix;

		# Make sure we don't mess with the live DB
		if (!$setupDB && $wgDBprefix === 'parsertest') {
			$db =& wfGetDB( DB_MASTER );

			$tables = $this->listTables();

			if (!(strcmp($db->getServerVersion(), '4.1') < 0 and stristr($db->getSoftwareLink(), 'MySQL'))) {
				# Database that supports CREATE TABLE ... LIKE
				foreach ($tables as $tbl) {
					$newTableName = $db->tableName( $tbl );
					$tableName = $this->oldTableNames[$tbl];
					$db->query("CREATE TEMPORARY TABLE $newTableName (LIKE $tableName INCLUDING DEFAULTS)");
				}
			} else {
				# Hack for MySQL versions < 4.1, which don't support
				# "CREATE TABLE ... LIKE". Note that
				# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
				# would not create the indexes we need....
				foreach ($tables as $tbl) {
					$res = $db->query("SHOW CREATE TABLE $tbl");
					$row = $db->fetchRow($res);
					$create = $row[1];
					$create_tmp = preg_replace('/CREATE TABLE `(.*?)`/', 'CREATE TEMPORARY TABLE `'
						. $wgDBprefix . '\\1`', $create);
					if ($create === $create_tmp) {
						# Couldn't do replacement
						die("could not create temporary table $tbl");
					}
					$db->query($create_tmp);
				}

			}

			# Hack: insert a few Wikipedia in-project interwiki prefixes,
			# for testing inter-language links
			$db->insertArray( 'interwiki', array(
				array( 'iw_prefix' => 'Wikipedia',
				       'iw_url'    => 'http://en.wikipedia.org/wiki/$1',
				       'iw_local'  => 0 ),
				array( 'iw_prefix' => 'MeatBall',
				       'iw_url'    => 'http://www.usemod.com/cgi-bin/mb.pl?$1',
				       'iw_local'  => 0 ),
				array( 'iw_prefix' => 'zh',
				       'iw_url'    => 'http://zh.wikipedia.org/wiki/$1',
				       'iw_local'  => 1 ),
				array( 'iw_prefix' => 'es',
				       'iw_url'    => 'http://es.wikipedia.org/wiki/$1',
				       'iw_local'  => 1 ),
				array( 'iw_prefix' => 'fr',
				       'iw_url'    => 'http://fr.wikipedia.org/wiki/$1',
				       'iw_local'  => 1 ),
				array( 'iw_prefix' => 'ru',
				       'iw_url'    => 'http://ru.wikipedia.org/wiki/$1',
				       'iw_local'  => 1 ),
				) );


			$setupDB = true;
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
		
		$diff = `diff -au $infile $outfile`;
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
		fwrite( $file, $data . "\n" );
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
		if($this->lightcolor) {
			return $this->color ? "\x1b[1;{$color}m" : '';
		} else {
			return $this->color ? "\x1b[{$color}m" : '';
		}
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

	/**
	 * Insert a temporary test article
	 * @param string $name the title, including any prefix
	 * @param string $text the article text
	 * @param int $line the input line number, for reporting errors
	 * @static
	 * @access private
	 */
	function addArticle($name, $text, $line) {
		$this->setupGlobals();
		$title = Title::newFromText( $name );
		if ( is_null($title) ) {
			die( "invalid title at line $line\n" );
		}

		$aid = $title->getArticleID( GAID_FOR_UPDATE );
		if ($aid != 0) {
			die( "duplicate article at line $line\n" );
		}

		$art = new Article($title);
		$art->insertNewArticle($text, '', false, false );
		$this->teardownGlobals();
	}

	/*
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param string $text the text to tidy
	 * @return string
	 * @static
	 * @access private
	 */
	function tidy( $text ) {
		global $wgUseTidy;
		if ($wgUseTidy) {
			$text = Parser::tidy($text);
		}
		return $text;
	}
}

# There is a convention that the parser should never
# refer to $wgTitle directly, but instead use the title
# passed to it.
$wgTitle = Title::newFromText( 'Parser test script do not use' );
$tester =& new ParserTest();

# Note: the command line setup changes the current working directory
# to the parent, which is why we have to put the subdir here:
$ok = $tester->runTestsFromFile( 'maintenance/parserTests.txt' );

exit ($ok ? 0 : -1);

?>
