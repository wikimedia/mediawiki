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
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * @todo Make this more independent of the configuration (and if possible the database)
 * @todo document
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
$options = array( 'quick', 'color', 'quiet', 'help', 'show-output', 'record' );
$optionsWithArgs = array( 'regex' );

require_once( 'commandLine.inc' );
require_once( "$IP/maintenance/parserTestsParserHook.php" );
require_once( "$IP/maintenance/parserTestsStaticParserHook.php" );
require_once( "$IP/maintenance/parserTestsParserTime.php" );

/**
 * @package MediaWiki
 * @subpackage Maintenance
 */
class ParserTest {
	/**
	 * boolean $color whereas output should be colorized
	 * @private
	 */
	var $color;

	/**
	 * boolean $showOutput Show test output
	 */
	var $showOutput;

	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 *
	 * @public
	 */
	function ParserTest() {
		global $options;

		# Only colorize output if stdout is a terminal.
		$this->color = !wfIsWindows() && posix_isatty(1);

		if( isset( $options['color'] ) ) {
			switch( $options['color'] ) {
			case 'no':
				$this->color = false;
				break;
			case 'yes':
			default:
				$this->color = true;
				break;
			}
		}
		$this->term = $this->color
			? new AnsiTermColorer()
			: new DummyTermColorer();

		$this->showDiffs = !isset( $options['quick'] );
		$this->showProgress = !isset( $options['quiet'] );
		$this->showFailure = !(
			isset( $options['quiet'] )
			&& ( isset( $options['record'] )
				|| isset( $options['compare'] ) ) ); // redundant output
		
		$this->showOutput = isset( $options['show-output'] );


		if (isset($options['regex'])) {
			$this->regex = $options['regex'];
		} else {
			# Matches anything
			$this->regex = '';
		}

		if( isset( $options['record'] ) ) {
			$this->recorder = new DbTestRecorder( $this->term );
		} elseif( isset( $options['compare'] ) ) {
			$this->recorder = new DbTestPreviewer( $this->term );
		} else {
			$this->recorder = new TestRecorder( $this->term );
		}

		$this->hooks = array();
		$this->functionHooks = array();
	}

	/**
	 * Remove last character if it is a newline
	 * @private
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
	 * Run a series of tests listed in the given text files.
	 * Each test consists of a brief description, wikitext input,
	 * and the expected HTML output.
	 *
	 * Prints status updates on stdout and counts up the total
	 * number and percentage of passed tests.
	 *
	 * @param array of strings $filenames
	 * @return bool True if passed all tests, false if any tests failed.
	 * @public
	 */
	function runTestsFromFiles( $filenames ) {
		$this->recorder->start();
		$ok = true;
		foreach( $filenames as $filename ) {
			$ok = $this->runFile( $filename ) && $ok;
		}
		$this->recorder->report();
		$this->recorder->end();
		return $ok;
	}

	private function runFile( $filename ) {
		$infile = fopen( $filename, 'rt' );
		if( !$infile ) {
			wfDie( "Couldn't open $filename\n" );
		} else {
			global $IP;
			$relative = wfRelativePath( $filename, $IP );
			print $this->term->color( 1 ) .
				"Reading tests from \"$relative\"..." .
				$this->term->reset() .
				"\n";
		}

		$data = array();
		$section = null;
		$n = 0;
		$ok = true;
		while( false !== ($line = fgets( $infile ) ) ) {
			$n++;
			$matches = array();
			if( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$section = strtolower( $matches[1] );
				if( $section == 'endarticle') {
					if( !isset( $data['text'] ) ) {
						wfDie( "'endarticle' without 'text' at line $n of $filename\n" );
					}
					if( !isset( $data['article'] ) ) {
						wfDie( "'endarticle' without 'article' at line $n of $filename\n" );
					}
					$this->addArticle($this->chomp($data['article']), $this->chomp($data['text']), $n);
					$data = array();
					$section = null;
					continue;
				}
				if( $section == 'endhooks' ) {
					if( !isset( $data['hooks'] ) ) {
						wfDie( "'endhooks' without 'hooks' at line $n of $filename\n" );
					}
					foreach( explode( "\n", $data['hooks'] ) as $line ) {
						$line = trim( $line );
						if( $line ) {
							$this->requireHook( $line );
						}
					}
					$data = array();
					$section = null;
					continue;
				}
				if( $section == 'endfunctionhooks' ) {
					if( !isset( $data['functionhooks'] ) ) {
						wfDie( "'endfunctionhooks' without 'functionhooks' at line $n of $filename\n" );
					}
					foreach( explode( "\n", $data['functionhooks'] ) as $line ) {
						$line = trim( $line );
						if( $line ) {
							$this->requireFunctionHook( $line );
						}
					}
					$data = array();
					$section = null;
					continue;
				}
				if( $section == 'end' ) {
					if( !isset( $data['test'] ) ) {
						wfDie( "'end' without 'test' at line $n of $filename\n" );
					}
					if( !isset( $data['input'] ) ) {
						wfDie( "'end' without 'input' at line $n of $filename\n" );
					}
					if( !isset( $data['result'] ) ) {
						wfDie( "'end' without 'result' at line $n of $filename\n" );
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
					$result = $this->runTest(
						$this->chomp( $data['test'] ),
						$this->chomp( $data['input'] ),
						$this->chomp( $data['result'] ),
						$this->chomp( $data['options'] ) );
					$ok = $ok && $result;
					$this->recorder->record( $this->chomp( $data['test'] ), $result );
					$data = array();
					$section = null;
					continue;
				}
				if ( isset ($data[$section] ) ) {
					wfDie( "duplicate section '$section' at line $n of $filename\n" );
				}
				$data[$section] = '';
				continue;
			}
			if( $section ) {
				$data[$section] .= $line;
			}
		}
		print "\n";
		return $ok;
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
		if( $this->showProgress ) {
			$this->showTesting( $desc );
		}

		$this->setupGlobals($opts);

		$user = new User();
		$options = ParserOptions::newFromUser( $user );

		if (preg_match('/\\bmath\\b/i', $opts)) {
			# XXX this should probably be done by the ParserOptions
			$options->setUseTex(true);
		}

		$m = array();
		if (preg_match('/title=\[\[(.*)\]\]/', $opts, $m)) {
			$titleText = $m[1];
		}
		else {
			$titleText = 'Parser test';
		}

		$noxml = (bool)preg_match( '~\\b noxml \\b~x', $opts );

		$parser = new Parser();
		foreach( $this->hooks as $tag => $callback ) {
			$parser->setHook( $tag, $callback );
		}
		foreach( $this->functionHooks as $tag => $callback ) {
			$parser->setFunctionHook( $tag, $callback );
		}
		wfRunHooks( 'ParserTestParser', array( &$parser ) );

		$title =& Title::makeTitle( NS_MAIN, $titleText );

		$matches = array();
		if (preg_match('/\\bpst\\b/i', $opts)) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif (preg_match('/\\bmsg\\b/i', $opts)) {
			$out = $parser->transformMsg( $input, $options );
		} elseif( preg_match( '/\\bsection=(\d+)\b/i', $opts, $matches ) ) {
			$section = intval( $matches[1] );
			$out = $parser->getSection( $input, $section );
		} elseif( preg_match( '/\\breplace=(\d+),"(.*?)"/i', $opts, $matches ) ) {
			$section = intval( $matches[1] );
			$replace = $matches[2];
			$out = $parser->replaceSection( $input, $section, $replace );
		} else {
			$output = $parser->parse( $input, $title, $options, true, true, 1337 );
			$out = $output->getText();

			if (preg_match('/\\bill\\b/i', $opts)) {
				$out = $this->tidy( implode( ' ', $output->getLanguageLinks() ) );
			} else if (preg_match('/\\bcat\\b/i', $opts)) {
				global $wgOut;
				$wgOut->addCategoryLinks($output->getCategories());
				$out = $this->tidy ( implode( ' ', $wgOut->getCategoryLinks() ) );
			}

			$result = $this->tidy($result);
		}

		$this->teardownGlobals();

		if( $result === $out && ( $noxml === true || $this->wellFormed( $out ) ) ) {
			return $this->showSuccess( $desc );
		} else {
			return $this->showFailure( $desc, $result, $out );
		}
	}

	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 *
	 * @private
	 */
	function setupGlobals($opts = '') {
		# Save the prefixed / quoted table names for later use when we make the temporaries.
		$db =& wfGetDB( DB_READ );
		$this->oldTableNames = array();
		foreach( $this->listTables() as $table ) {
			$this->oldTableNames[$table] = $db->tableName( $table );
		}
		if( !isset( $this->uploadDir ) ) {
			$this->uploadDir = $this->setupUploadDir();
		}

		$m = array();
		if( preg_match( '/language=([a-z]+(?:_[a-z]+)?)/', $opts, $m ) ) {
			$lang = $m[1];
		} else {
			$lang = 'en';
		}

		if( preg_match( '/variant=([a-z]+(?:-[a-z]+)?)/', $opts, $m ) ) {
			$variant = $m[1];
		} else {
			$variant = false;
		}


		$settings = array(
			'wgServer' => 'http://localhost',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => array(),
			'wgUploadPath' => 'http://example.com/images',
			'wgUploadDirectory' => $this->uploadDir,
			'wgStyleSheetPath' => '/skins',
			'wgSitename' => 'MediaWiki',
			'wgServerName' => 'Britney Spears',
			'wgLanguageCode' => $lang,
			'wgContLanguageCode' => $lang,
			'wgDBprefix' => 'parsertest_',
			'wgRawHtml' => preg_match('/\\brawhtml\\b/i', $opts),
			'wgLang' => null,
			'wgContLang' => null,
			'wgNamespacesWithSubpages' => array( 0 => preg_match('/\\bsubpage\\b/i', $opts)),
			'wgMaxTocLevel' => 999,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgThumbnailScriptPath' => false,
			'wgUseTeX' => false,
			'wgLocaltimezone' => 'UTC',
			'wgAllowExternalImages' => true,
			'wgUseTidy' => false,
			'wgDefaultLanguageVariant' => $variant,
			'wgVariantArticlePath' => false,
			);
		$this->savedGlobals = array();
		foreach( $settings as $var => $val ) {
			$this->savedGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $val;
		}
		$langObj = Language::factory( $lang );
		$GLOBALS['wgLang'] = $langObj;
		$GLOBALS['wgContLang'] = $langObj;

		$GLOBALS['wgLoadBalancer']->loadMasterPos();
		//$GLOBALS['wgMessageCache'] = new MessageCache( new BagOStuff(), false, 0, $GLOBALS['wgDBname'] );
		$this->setupDatabase();

		global $wgUser;
		$wgUser = new User();
	}

	# List of temporary tables to create, without prefix
	# Some of these probably aren't necessary
	function listTables() {
		$tables = array('user', 'page', 'page_restrictions', 'revision', 'text',
			'pagelinks', 'imagelinks', 'categorylinks',
			'templatelinks', 'externallinks', 'langlinks',
			'site_stats', 'hitcounter',
			'ipblocks', 'image', 'oldimage',
			'recentchanges',
			'watchlist', 'math', 'searchindex',
			'interwiki', 'querycache',
			'objectcache', 'job', 'redirect',
			'querycachetwo'
		);

		// FIXME manually adding additional table for the tasks extension
		// we probably need a better software wide system to register new
		// tables.
		global $wgExtensionFunctions;
		if( in_array('wfTasksExtension' , $wgExtensionFunctions ) ) {
			$tables[] = 'tasks';
		}

		return $tables;
	}

	/**
	 * Set up a temporary set of wiki tables to work with for the tests.
	 * Currently this will only be done once per run, and any changes to
	 * the db will be visible to later tests in the run.
	 *
	 * @private
	 */
	function setupDatabase() {
		static $setupDB = false;
		global $wgDBprefix;

		# Make sure we don't mess with the live DB
		if (!$setupDB && $wgDBprefix === 'parsertest_') {
			# oh teh horror
			$GLOBALS['wgLoadBalancer'] = LoadBalancer::newFromParams( $GLOBALS['wgDBservers'] );
			$db =& wfGetDB( DB_MASTER );

			$tables = $this->listTables();

			if (!(strcmp($db->getServerVersion(), '4.1') < 0 and stristr($db->getSoftwareLink(), 'MySQL'))) {
				# Database that supports CREATE TABLE ... LIKE
				global $wgDBtype;
				if( $wgDBtype == 'postgres' ) {
					$def = 'INCLUDING DEFAULTS';
				} else {
					$def = '';
				}
				foreach ($tables as $tbl) {
					$newTableName = $db->tableName( $tbl );
					$tableName = $this->oldTableNames[$tbl];
					$db->query("CREATE TEMPORARY TABLE $newTableName (LIKE $tableName $def)");
				}
			} else {
				# Hack for MySQL versions < 4.1, which don't support
				# "CREATE TABLE ... LIKE". Note that
				# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
				# would not create the indexes we need....
				foreach ($tables as $tbl) {
					$res = $db->query("SHOW CREATE TABLE {$this->oldTableNames[$tbl]}");
					$row = $db->fetchRow($res);
					$create = $row[1];
					$create_tmp = preg_replace('/CREATE TABLE `(.*?)`/', 'CREATE TEMPORARY TABLE `'
						. $wgDBprefix . $tbl .'`', $create);
					if ($create === $create_tmp) {
						# Couldn't do replacement
						wfDie("could not create temporary table $tbl");
					}
					$db->query($create_tmp);
				}

			}

			# Hack: insert a few Wikipedia in-project interwiki prefixes,
			# for testing inter-language links
			$db->insert( 'interwiki', array(
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

			# Hack: Insert an image to work with
			$db->insert( 'image', array(
				'img_name'        => 'Foobar.jpg',
				'img_size'        => 12345,
				'img_description' => 'Some lame file',
				'img_user'        => 1,
				'img_user_text'   => 'WikiSysop',
				'img_timestamp'   => $db->timestamp( '20010115123500' ),
				'img_width'       => 1941,
				'img_height'      => 220,
				'img_bits'        => 24,
				'img_media_type'  => MEDIATYPE_BITMAP,
				'img_major_mime'  => "image",
				'img_minor_mime'  => "jpeg",
				'img_metadata'    => serialize( array() ),
				) );

			# Update certain things in site_stats
			$db->insert( 'site_stats', array( 'ss_row_id' => 1, 'ss_images' => 1, 'ss_good_articles' => 1 ) );

			$setupDB = true;
		}
	}

	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 * @return string The directory
	 * @private
	 */
	function setupUploadDir() {
		global $IP;

		$dir = wfTempDir() . "/mwParser-" . mt_rand() . "-images";
		mkdir( $dir );
		mkdir( $dir . '/3' );
		mkdir( $dir . '/3/3a' );

		$img = "$IP/skins/monobook/headbg.jpg";
		$h = fopen($img, 'r');
		$c = fread($h, filesize($img));
		fclose($h);

		$f = fopen( $dir . '/3/3a/Foobar.jpg', 'wb' );
		fwrite( $f, $c );
		fclose( $f );
		return $dir;
	}

	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 *
	 * @private
	 */
	function teardownGlobals() {
		foreach( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		if( isset( $this->uploadDir ) ) {
			$this->teardownUploadDir( $this->uploadDir );
			unset( $this->uploadDir );
		}
	}

	/**
	 * Remove the dummy uploads directory
	 * @private
	 */
	function teardownUploadDir( $dir ) {
		// delete the files first, then the dirs.
		self::deleteFiles(
			array (
				"$dir/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/180px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/200px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/640px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/120px-Foobar.jpg",
			)
		);

		self::deleteDirs(
			array (
				"$dir/3/3a",
				"$dir/3",
				"$dir/thumb/6/65",
				"$dir/thumb/6",
				"$dir/thumb/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a",
				"$dir/thumb/3",
				"$dir/thumb",
				"$dir",
			)
		);
	}

	/**
	 * @desc delete the specified files, if they exist.
	 * @param array $files full paths to files to delete.
	 */
	private static function deleteFiles( $files ) {
		foreach( $files as $file ) {
			if( file_exists( $file ) ) {
				unlink( $file );
			}
		}
	}

	/**
	 * @desc delete the specified directories, if they exist. Must be empty.
	 * @param array $dirs full paths to directories to delete.
	 */
	private static function deleteDirs( $dirs ) {
		foreach( $dirs as $dir ) {
			if( is_dir( $dir ) ) {
				rmdir( $dir );
			}
		}
	}

	/**
	 * "Running test $desc..."
	 * @private
	 */
	function showTesting( $desc ) {
		print "Running test $desc... ";
	}

	/**
	 * Print a happy success message.
	 *
	 * @param string $desc The test name
	 * @return bool
	 * @private
	 */
	function showSuccess( $desc ) {
		if( $this->showProgress ) {
			print $this->term->color( '1;32' ) . 'PASSED' . $this->term->reset() . "\n";
		}
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
	 * @private
	 */
	function showFailure( $desc, $result, $html ) {
		if( $this->showFailure ) {
			if( !$this->showProgress ) {
				# In quiet mode we didn't show the 'Testing' message before the
				# test, in case it succeeded. Show it now:
				$this->showTesting( $desc );
			}
			print $this->term->color( '31' ) . 'FAILED!' . $this->term->reset() . "\n";
			if ( $this->showOutput ) {
				print "--- Expected ---\n$result\n--- Actual ---\n$html\n";
			}
			if( $this->showDiffs ) {
				print $this->quickDiff( $result, $html );
				if( !$this->wellFormed( $html ) ) {
					print "XML error: $this->mXmlError\n";
				}
			}
		}
		return false;
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
	 * @private
	 */
	function quickDiff( $input, $output, $inFileTail='expected', $outFileTail='actual' ) {
		$prefix = wfTempDir() . "/mwParser-" . mt_rand();

		$infile = "$prefix-$inFileTail";
		$this->dumpToFile( $input, $infile );

		$outfile = "$prefix-$outFileTail";
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
	 * @private
	 */
	function dumpToFile( $data, $filename ) {
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
	 * @private
	 */
	function colorDiff( $text ) {
		return preg_replace(
			array( '/^(-.*)$/m', '/^(\+.*)$/m' ),
			array( $this->term->color( 34 ) . '$1' . $this->term->reset(),
			       $this->term->color( 31 ) . '$1' . $this->term->reset() ),
			$text );
	}

	/**
	 * Insert a temporary test article
	 * @param string $name the title, including any prefix
	 * @param string $text the article text
	 * @param int $line the input line number, for reporting errors
	 * @private
	 */
	function addArticle($name, $text, $line) {
		$this->setupGlobals();
		$title = Title::newFromText( $name );
		if ( is_null($title) ) {
			wfDie( "invalid title at line $line\n" );
		}

		$aid = $title->getArticleID( GAID_FOR_UPDATE );
		if ($aid != 0) {
			wfDie( "duplicate article at line $line\n" );
		}

		$art = new Article($title);
		$art->insertNewArticle($text, '', false, false );
		$this->teardownGlobals();
	}

	/**
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * die a painful dead to warn the others.
	 * @param string $name
	 */
	private function requireHook( $name ) {
		global $wgParser;
		if( isset( $wgParser->mTagHooks[$name] ) ) {
			$this->hooks[$name] = $wgParser->mTagHooks[$name];
		} else {
			wfDie( "This test suite requires the '$name' hook extension.\n" );
		}
	}

	/**
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * die a painful dead to warn the others.
	 * @param string $name
	 */
	private function requireFunctionHook( $name ) {
		global $wgParser;
		if( isset( $wgParser->mFunctionHooks[$name] ) ) {
			$this->functionHooks[$name] = $wgParser->mFunctionHooks[$name];
		} else {
			wfDie( "This test suite requires the '$name' function hook extension.\n" );
		}
	}

	/*
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param string $text the text to tidy
	 * @return string
	 * @static
	 * @private
	 */
	function tidy( $text ) {
		global $wgUseTidy;
		if ($wgUseTidy) {
			$text = Parser::tidy($text);
		}
		return $text;
	}

	function wellFormed( $text ) {
		$html =
			Sanitizer::hackDocType() .
			'<html>' .
			$text .
			'</html>';

		$parser = xml_parser_create( "UTF-8" );

		# case folding violates XML standard, turn it off
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		if( !xml_parse( $parser, $html, true ) ) {
			$err = xml_error_string( xml_get_error_code( $parser ) );
			$position = xml_get_current_byte_index( $parser );
			$fragment = $this->extractFragment( $html, $position );
			$this->mXmlError = "$err at byte $position:\n$fragment";
			xml_parser_free( $parser );
			return false;
		}
		xml_parser_free( $parser );
		return true;
	}

	function extractFragment( $text, $position ) {
		$start = max( 0, $position - 10 );
		$before = $position - $start;
		$fragment = '...' .
			$this->term->color( 34 ) .
			substr( $text, $start, $before ) .
			$this->term->color( 0 ) .
			$this->term->color( 31 ) .
			$this->term->color( 1 ) .
			substr( $text, $position, 1 ) .
			$this->term->color( 0 ) .
			$this->term->color( 34 ) .
			substr( $text, $position + 1, 9 ) .
			$this->term->color( 0 ) .
			'...';
		$display = str_replace( "\n", ' ', $fragment );
		$caret = '   ' .
			str_repeat( ' ', $before ) .
			$this->term->color( 31 ) .
			'^' .
			$this->term->color( 0 );
		return "$display\n$caret";
	}
}

class AnsiTermColorer {
	function __construct() {
	}

	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param string $color Semicolon-separated list of attribute/color codes
	 * @return string
	 * @private
	 */
	function color( $color ) {
		global $wgCommandLineDarkBg;
		$light = $wgCommandLineDarkBg ? "1;" : "0;";
		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return string
	 * @private
	 */
	function reset() {
		return $this->color( 0 );
	}
}

/* A colour-less terminal */
class DummyTermColorer {
	function color( $color ) {
		return '';
	}

	function reset() {
		return '';
	}
}

class TestRecorder {
	function __construct( $term ) {
		$this->term = $term;
	}

	function start() {
		$this->total = 0;
		$this->success = 0;
	}

	function record( $test, $result ) {
		$this->total++;
		$this->success += ($result ? 1 : 0);
	}

	function end() {
		// dummy
	}

	function report() {
		if( $this->total > 0 ) {
			$this->reportPercentage( $this->success, $this->total );
		} else {
			wfDie( "No tests found.\n" );
		}
	}

	function reportPercentage( $success, $total ) {
		$ratio = wfPercent( 100 * $success / $total );
		print $this->term->color( 1 ) . "Passed $success of $total tests ($ratio)... ";
		if( $success == $total ) {
			print $this->term->color( 32 ) . "ALL TESTS PASSED!";
		} else {
			print $this->term->color( 31 ) . "some tests failed!";
		}
		print $this->term->reset() . "\n";
		return ($success == $total);
	}
}

class DbTestRecorder extends TestRecorder  {
	protected $db;      ///< Database connection to the main DB
	protected $curRun;  ///< run ID number for the current run
	protected $prevRun; ///< run ID number for the previous run, if any

	function __construct( $term ) {
		parent::__construct( $term );
		$this->db = wfGetDB( DB_MASTER );
	}

	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		parent::start();

		$this->db->begin();

		if( ! $this->db->tableExists( 'testrun' ) or ! $this->db->tableExists( 'testitem') ) {
			print "WARNING> `testrun` table not found in database. Trying to create table.\n";
			dbsource( 'testRunner.sql',  $this->db );
			echo "OK, resuming.\n";
		}

		// We'll make comparisons against the previous run later...
		$this->prevRun = $this->db->selectField( 'testrun', 'MAX(tr_id)' );

		$this->db->insert( 'testrun',
			array(
				'tr_date'        => $this->db->timestamp(),
				'tr_mw_version'  => SpecialVersion::getVersion(),
				'tr_php_version' => phpversion(),
				'tr_db_version'  => $this->db->getServerVersion(),
				'tr_uname'       => php_uname()
			),
			__METHOD__ );
		$this->curRun = $this->db->insertId();
	}

	/**
	 * Record an individual test item's success or failure to the db
	 * @param string $test
	 * @param bool $result
	 */
	function record( $test, $result ) {
		parent::record( $test, $result );
		$this->db->insert( 'testitem',
			array(
				'ti_run'     => $this->curRun,
				'ti_name'    => $test,
				'ti_success' => $result ? 1 : 0,
			),
			__METHOD__ );
	}

	/**
	 * Commit transaction and clean up for result recording
	 */
	function end() {
		$this->db->commit();
		parent::end();
	}

	function report() {
		if( $this->prevRun ) {
			$table = array(
				array( 'previously failing test(s) now PASSING! :)', 0, 1 ),
				array( 'previously PASSING test(s) removed o_O', 1, null ),
				array( 'new PASSING test(s) :)', null, 1 ),

				array( 'previously passing test(s) now FAILING! :(', 1, 0 ),
				array( 'previously FAILING test(s) removed O_o', 0, null ),
				array( 'new FAILING test(s) :(', null, 0 ),
				array( 'still FAILING test(s) :(', 0, 0 ),
			);
			foreach( $table as $criteria ) {
				list( $label, $before, $after ) = $criteria;
				$differences = $this->compareResult( $before, $after );
				if( $differences ) {
					$count = count($differences);
					printf( "%4d %s\n", $count, $label );
					foreach ($differences as $differing_test_name) {
						print "      * $differing_test_name\n";
					}
				}
			}
		} else {
			print "No previous test runs to compare against.\n";
		}
		print "\n";
		parent::report();
	}

	/**
	 ** @desc: Returns an array of the test names with changed results, based on the specified
	 **        before/after criteria.
	 */
	private function compareResult( $before, $after ) {
		$testitem = $this->db->tableName( 'testitem' );
		$prevRun = intval( $this->prevRun );
		$curRun = intval( $this->curRun );
		$prevStatus = $this->condition( $before );
		$curStatus = $this->condition( $after );

		// note: requires mysql >= ver 4.1 for subselects
		if( is_null( $after ) ) {
			$sql = "
				select prev.ti_name as t from $testitem as prev
					where prev.ti_run=$prevRun and
						prev.ti_success $prevStatus and
						(select current.ti_success from $testitem as current
							where current.ti_run=$curRun
								and prev.ti_name=current.ti_name) $curStatus";
		} else {
			$sql = "
				select current.ti_name as t from $testitem as current 
					where current.ti_run=$curRun and
						current.ti_success $curStatus and
						(select prev.ti_success from $testitem as prev
							where prev.ti_run=$prevRun
								and prev.ti_name=current.ti_name) $prevStatus";
		}
		$result = $this->db->query( $sql, __METHOD__ );
		$retval = array();
		while ($row = $this->db->fetchObject( $result )) {
			$retval[] = $row->t;
		}
		$this->db->freeResult( $result );
		return $retval;
	}

	/**
	 ** @desc: Helper function for compareResult() database querying.
	 */
	private function condition( $value ) {
		if( is_null( $value ) ) {
			return 'IS NULL';
		} else {
			return '=' . intval( $value );
		}
	}

}

class DbTestPreviewer extends DbTestRecorder  {
	/**
	 * Commit transaction and clean up for result recording
	 */
	function end() {
		$this->db->rollback();
		TestRecorder::end();
	}
}

?>
