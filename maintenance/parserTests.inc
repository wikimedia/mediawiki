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
 * @file
 * @ingroup Maintenance
 */

/** */
$options = array( 'quick', 'color', 'quiet', 'help', 'show-output', 'record' );
$optionsWithArgs = array( 'regex', 'seed' );

require_once( 'commandLine.inc' );
require_once( "$IP/maintenance/parserTestsParserHook.php" );
require_once( "$IP/maintenance/parserTestsStaticParserHook.php" );
require_once( "$IP/maintenance/parserTestsParserTime.php" );

/**
 * @ingroup Maintenance
 */
class ParserTest {
	/**
	 * boolean $color whereas output should be colorized
	 */
	private $color;

	/**
	 * boolean $showOutput Show test output
	 */
	private $showOutput;

	/**
	 * boolean $useTemporaryTables Use temporary tables for the temporary database
	 */
	private $useTemporaryTables = true;

	/**
	 * boolean $databaseSetupDone True if the database has been set up
	 */
	private $databaseSetupDone = false;

	/**
	 * string $oldTablePrefix Original table prefix
	 */
	private $oldTablePrefix;

	private $maxFuzzTestLength = 300;
	private $fuzzSeed = 0;
	private $memoryLimit = 50;

	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 */
	public function ParserTest() {
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
			if ( isset( $options['record'] ) ) {
				echo "Warning: --record cannot be used with --regex, disabling --record\n";
				unset( $options['record'] );
			}
			$this->regex = $options['regex'];
		} else {
			# Matches anything
			$this->regex = '';
		}

		if( isset( $options['record'] ) ) {
			$this->recorder = new DbTestRecorder( $this );
		} elseif( isset( $options['compare'] ) ) {
			$this->recorder = new DbTestPreviewer( $this );
		} else {
			$this->recorder = new TestRecorder( $this );
		}
		$this->keepUploads = isset( $options['keep-uploads'] );

		if ( isset( $options['seed'] ) ) {
			$this->fuzzSeed = intval( $options['seed'] ) - 1;
		}

		$this->hooks = array();
		$this->functionHooks = array();
	}

	/**
	 * Remove last character if it is a newline
	 */
	private function chomp($s) {
		if (substr($s, -1) === "\n") {
			return substr($s, 0, -1);
		}
		else {
			return $s;
		}
	}

	/**
	 * Run a fuzz test series
	 * Draw input from a set of test files
	 */
	function fuzzTest( $filenames ) {
		$dict = $this->getFuzzInput( $filenames );
		$dictSize = strlen( $dict );
		$logMaxLength = log( $this->maxFuzzTestLength );
		$this->setupDatabase();
		ini_set( 'memory_limit', $this->memoryLimit * 1048576 );

		$numTotal = 0;
		$numSuccess = 0;
		$user = new User;
		$opts = ParserOptions::newFromUser( $user );
		$title = Title::makeTitle( NS_MAIN, 'Parser_test' );

		while ( true ) {
			// Generate test input
			mt_srand( ++$this->fuzzSeed );
			$totalLength = mt_rand( 1, $this->maxFuzzTestLength );
			$input = '';
			while ( strlen( $input ) < $totalLength ) {
				$logHairLength = mt_rand( 0, 1000000 ) / 1000000 * $logMaxLength;
				$hairLength = min( intval( exp( $logHairLength ) ), $dictSize );
				$offset = mt_rand( 0, $dictSize - $hairLength );
				$input .= substr( $dict, $offset, $hairLength );
			}

			$this->setupGlobals();
			$parser = $this->getParser();
			// Run the test
			try {
				$parser->parse( $input, $title, $opts );
				$fail = false;
			} catch ( Exception $exception ) {
				$fail = true;
			}

			if ( $fail ) {
				echo "Test failed with seed {$this->fuzzSeed}\n";
				echo "Input:\n";
				var_dump( $input );
				echo "\n\n";
				echo "$exception\n";
			} else {
				$numSuccess++;
			}
			$numTotal++;
			$this->teardownGlobals();
			$parser->__destruct();

			if ( $numTotal % 100 == 0 ) {
				$usage = intval( memory_get_usage( true ) / $this->memoryLimit / 1048576 * 100 );
				echo "{$this->fuzzSeed}: $numSuccess/$numTotal (mem: $usage%)\n";
				if ( $usage > 90 ) {
					echo "Out of memory:\n";
					$memStats = $this->getMemoryBreakdown();
					foreach ( $memStats as $name => $usage ) {
						echo "$name: $usage\n";
					}
					$this->abort();
				}
			}
		}
	}

	/**
	 * Get an input dictionary from a set of parser test files
	 */
	function getFuzzInput( $filenames ) {
		$dict = '';
		foreach( $filenames as $filename ) {
			$contents = file_get_contents( $filename );
			preg_match_all( '/!!\s*input\n(.*?)\n!!\s*result/s', $contents, $matches );
			foreach ( $matches[1] as $match ) {
				$dict .= $match . "\n";
			}
		}
		return $dict;
	}

	/**
	 * Get a memory usage breakdown
	 */
	function getMemoryBreakdown() {
		$memStats = array();
		foreach ( $GLOBALS as $name => $value ) {
			$memStats['$'.$name] = strlen( serialize( $value ) );
		}
		$classes = get_declared_classes();
		foreach ( $classes as $class ) {
			$rc = new ReflectionClass( $class );
			$props = $rc->getStaticProperties();
			$memStats[$class] = strlen( serialize( $props ) );
			$methods = $rc->getMethods();
			foreach ( $methods as $method ) {
				$memStats[$class] += strlen( serialize( $method->getStaticVariables() ) );
			}
		}
		$functions = get_defined_functions();
		foreach ( $functions['user'] as $function ) {
			$rf = new ReflectionFunction( $function );
			$memStats["$function()"] = strlen( serialize( $rf->getStaticVariables() ) );
		}
		asort( $memStats );
		return $memStats;
	}

	function abort() {
		$this->abort();
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
	 */
	public function runTestsFromFiles( $filenames ) {
		$this->recorder->start();
		$this->setupDatabase();
		$ok = true;
		foreach( $filenames as $filename ) {
			$ok = $this->runFile( $filename ) && $ok;
		}
		$this->teardownDatabase();
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
			$this->showRunFile( $relative );
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
					if (!isset( $data['config'] ) )
						$data['config'] = '';
					
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
						$this->chomp( $data['options'] ),
						$this->chomp( $data['config']   )
						);
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
		if ( $this->showProgress ) {
			print "\n";
		}
		return $ok;
	}

	/**
	 * Get a Parser object
	 */
	function getParser() {
		global $wgParserConf;
		$class = $wgParserConf['class'];
		$parser = new $class( $wgParserConf );
		foreach( $this->hooks as $tag => $callback ) {
			$parser->setHook( $tag, $callback );
		}
		foreach( $this->functionHooks as $tag => $bits ) {
			list( $callback, $flags ) = $bits;
			$parser->setFunctionHook( $tag, $callback, $flags );
		}
		wfRunHooks( 'ParserTestParser', array( &$parser ) );
		return $parser;
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
	private function runTest( $desc, $input, $result, $opts, $config ) {
		if( $this->showProgress ) {
			$this->showTesting( $desc );
		}

		$this->setupGlobals($opts, $config);

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
		$parser = $this->getParser();
		$title =& Title::makeTitle( NS_MAIN, $titleText );

		$matches = array();
		if (preg_match('/\\bpst\\b/i', $opts)) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif (preg_match('/\\bmsg\\b/i', $opts)) {
			$out = $parser->transformMsg( $input, $options );
		} elseif( preg_match( '/\\bsection=([\w-]+)\b/i', $opts, $matches ) ) {
			$section = $matches[1];
			$out = $parser->getSection( $input, $section );
		} elseif( preg_match( '/\\breplace=([\w-]+),"(.*?)"/i', $opts, $matches ) ) {
			$section = $matches[1];
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
				$cats = $wgOut->getCategoryLinks();
				if ( isset( $cats['normal'] ) ) {
					$out = $this->tidy( implode( ' ', $cats['normal'] ) );
				} else {
					$out = '';
				}
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
	 * Use a regex to find out the value of an option
	 * @param $regex A regex, the first group will be the value returned
	 * @param $opts Options line to look in
	 * @param $defaults Default value returned if the regex does not match
	 */
	private static function getOptionValue( $regex, $opts, $default ) {
		$m = array();
		if( preg_match( $regex, $opts, $m ) ) {
			return $m[1];
		} else {
			return $default;
		}
	}

	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 */
	private function setupGlobals($opts = '', $config = '') {
		if( !isset( $this->uploadDir ) ) {
			$this->uploadDir = $this->setupUploadDir();
		}

		# Find out values for some special options.
		$lang =
			self::getOptionValue( '/language=([a-z]+(?:_[a-z]+)?)/', $opts, 'en' );
		$variant =
			self::getOptionValue( '/variant=([a-z]+(?:-[a-z]+)?)/', $opts, false );
		$maxtoclevel =
			self::getOptionValue( '/wgMaxTocLevel=(\d+)/', $opts, 999 );
		$linkHolderBatchSize = 
			self::getOptionValue( '/wgLinkHolderBatchSize=(\d+)/', $opts, 1000 );

		$settings = array(
			'wgServer' => 'http://localhost',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => array(),
			'wgLocalFileRepo' => array(
				'class' => 'LocalRepo',
				'name' => 'local',
				'directory' => $this->uploadDir,
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
			),
			'wgEnableUploads' => true,
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
			'wgMaxTocLevel' => $maxtoclevel,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgThumbnailScriptPath' => false,
			'wgUseTeX' => false,
			'wgLocaltimezone' => 'UTC',
			'wgAllowExternalImages' => true,
			'wgUseTidy' => false,
			'wgDefaultLanguageVariant' => $variant,
			'wgVariantArticlePath' => false,
			'wgGroupPermissions' => array( '*' => array(
				'createaccount' => true,
				'read'          => true,
				'edit'          => true,
				'createpage'    => true,
				'createtalk'    => true,
			) ),
			'wgNamespaceProtection' => array( NS_MEDIAWIKI => 'editinterface' ),
			'wgDefaultExternalStore' => array(),
			'wgForeignFileRepos' => array(),
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
			'wgEnforceHtmlIds' => true,
			'wgExternalLinkTarget' => false,
			'wgAlwaysUseTidy' => false,
			);

		if ($config) {
			$configLines = explode( "\n", $config );
			
			foreach( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );
				
				$settings[$var] = eval("return $value;" );
			}
		}
		
		$this->savedGlobals = array();
		foreach( $settings as $var => $val ) {
			$this->savedGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $val;
		}
		$langObj = Language::factory( $lang );
		$GLOBALS['wgLang'] = $langObj;
		$GLOBALS['wgContLang'] = $langObj;
		$GLOBALS['wgMemc'] = new FakeMemCachedClient;

		//$GLOBALS['wgMessageCache'] = new MessageCache( new BagOStuff(), false, 0, $GLOBALS['wgDBname'] );

		global $wgUser;
		$wgUser = new User();
	}

	/**
	 * List of temporary tables to create, without prefix.
	 * Some of these probably aren't necessary.
	 */
	private function listTables() {
		global $wgDBtype;
		$tables = array('user', 'page', 'page_restrictions',
			'protected_titles', 'revision', 'text', 'pagelinks', 'imagelinks',
			'categorylinks', 'templatelinks', 'externallinks', 'langlinks',
			'site_stats', 'hitcounter',	'ipblocks', 'image', 'oldimage',
			'recentchanges', 'watchlist', 'math', 'interwiki',
			'querycache', 'objectcache', 'job', 'redirect', 'querycachetwo',
			'archive', 'user_groups', 'page_props', 'category'
		);

		if ($wgDBtype === 'mysql') 
			array_push( $tables, 'searchindex' );
		
		// Allow extensions to add to the list of tables to duplicate;
		// may be necessary if they hook into page save or other code
		// which will require them while running tests.
		wfRunHooks( 'ParserTestTables', array( &$tables ) );

		return $tables;
	}

	/**
	 * Set up a temporary set of wiki tables to work with for the tests.
	 * Currently this will only be done once per run, and any changes to
	 * the db will be visible to later tests in the run.
	 */
	private function setupDatabase() {
		global $wgDBprefix, $wgDBtype;
		if ( $this->databaseSetupDone ) {
			return;
		}
		if ( $wgDBprefix === 'parsertest_' ) {
			throw new MWException( 'setupDatabase should be called before setupGlobals' );
		}
		$this->databaseSetupDone = true;
		$this->oldTablePrefix = $wgDBprefix;

		# CREATE TEMPORARY TABLE breaks if there is more than one server
		# FIXME: r40209 makes temporary tables break even with just one server
		# FIXME: (bug 15892); disabling the feature entirely as a temporary fix
		if ( true || wfGetLB()->getServerCount() != 1 ) {
			$this->useTemporaryTables = false;
		}

		$temporary = $this->useTemporaryTables ? 'TEMPORARY' : '';

		$db = wfGetDB( DB_MASTER );
		$tables = $this->listTables();

		if ( !( $wgDBtype == 'mysql' && strcmp( $db->getServerVersion(), '4.1' ) < 0 ) ) {
			# Database that supports CREATE TABLE ... LIKE
			
			if( $wgDBtype == 'postgres' ) {
				$def = 'INCLUDING DEFAULTS';
				$temporary = 'TEMPORARY';
			} else {
				$def = '';
			}
			foreach ( $tables as $tbl ) {
				# Clean up from previous aborted run.  So that table escaping
				# works correctly across DB engines, we need to change the pre-
				# fix back and forth so tableName() works right.
				$this->changePrefix( $this->oldTablePrefix );
				$oldTableName = $db->tableName( $tbl );
				$this->changePrefix( 'parsertest_' );
				$newTableName = $db->tableName( $tbl );

				if ( $db->tableExists( $tbl ) && $wgDBtype != 'postgres' ) {
					$db->query( "DROP TABLE $newTableName" );
				}
				# Create new table
				$db->query( "CREATE $temporary TABLE $newTableName (LIKE $oldTableName $def)" );
			}
		} else {
			# Hack for MySQL versions < 4.1, which don't support
			# "CREATE TABLE ... LIKE". Note that
			# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
			# would not create the indexes we need....
			#
			# Note that we don't bother changing around the prefixes here be-
			# cause we know we're using MySQL anyway.
			foreach ($tables as $tbl) {
				$oldTableName = $db->tableName( $tbl );
				$res = $db->query("SHOW CREATE TABLE $oldTableName");
				$row = $db->fetchRow($res);
				$create = $row[1];
				$create_tmp = preg_replace('/CREATE TABLE `(.*?)`/', 
					"CREATE $temporary TABLE `parsertest_$tbl`", $create);
				if ($create === $create_tmp) {
					# Couldn't do replacement
					wfDie("could not create temporary table $tbl");
				}
				$db->query($create_tmp);
			}
		}

		$this->changePrefix( 'parsertest_' );

		# Hack: insert a few Wikipedia in-project interwiki prefixes,
		# for testing inter-language links
		$db->insert( 'interwiki', array(
			array( 'iw_prefix' => 'wikipedia',
				   'iw_url'    => 'http://en.wikipedia.org/wiki/$1',
				   'iw_local'  => 0 ),
			array( 'iw_prefix' => 'meatball',
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
	}

	/**
	 * Change the table prefix on all open DB connections/
	 */
	protected function changePrefix( $prefix ) {
		global $wgDBprefix;
		wfGetLBFactory()->forEachLB( array( $this, 'changeLBPrefix' ), array( $prefix ) );
		$wgDBprefix = $prefix;
	}

	public function changeLBPrefix( $lb, $prefix ) {
		$lb->forEachOpenConnection( array( $this, 'changeDBPrefix' ), array( $prefix ) );
	}

	public function changeDBPrefix( $db, $prefix ) {
		$db->tablePrefix( $prefix );
	}

	private function teardownDatabase() {
		global $wgDBprefix;
		if ( !$this->databaseSetupDone ) {
			return;
		}
		$this->changePrefix( $this->oldTablePrefix );
		$this->databaseSetupDone = false;
		if ( $this->useTemporaryTables ) {
			# Don't need to do anything
			return;
		}

		/*
		$tables = $this->listTables();
		$db = wfGetDB( DB_MASTER );
		foreach ( $tables as $table ) {
			$db->query( "DROP TABLE `parsertest_$table`" );
		}*/
	}
	
	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 * @return string The directory
	 */
	private function setupUploadDir() {
		global $IP;
		if ( $this->keepUploads ) {
			$dir = wfTempDir() . '/mwParser-images';
			if ( is_dir( $dir ) ) {
				return $dir;
			}
		} else {
			$dir = wfTempDir() . "/mwParser-" . mt_rand() . "-images";
		}

		wfDebug( "Creating upload directory $dir\n" );
		if ( file_exists( $dir ) ) {
			wfDebug( "Already exists!\n" );
			return $dir;
		}
		wfMkdirParents( $dir . '/3/3a' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/3/3a/Foobar.jpg" );
		return $dir;
	}

	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 */
	private function teardownGlobals() {
		RepoGroup::destroySingleton();
		FileCache::destroySingleton();
		LinkCache::singleton()->clear();
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
	 */
	private function teardownUploadDir( $dir ) {
		if ( $this->keepUploads ) {
			return;
		}

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
	 * Delete the specified files, if they exist.
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
	 * Delete the specified directories, if they exist. Must be empty.
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
	 */
	protected function showTesting( $desc ) {
		print "Running test $desc... ";
	}

	/**
	 * Print a happy success message.
	 *
	 * @param string $desc The test name
	 * @return bool
	 */
	protected function showSuccess( $desc ) {
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
	 */
	protected function showFailure( $desc, $result, $html ) {
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
	 */
	protected function quickDiff( $input, $output, $inFileTail='expected', $outFileTail='actual' ) {
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
	protected function colorDiff( $text ) {
		return preg_replace(
			array( '/^(-.*)$/m', '/^(\+.*)$/m' ),
			array( $this->term->color( 34 ) . '$1' . $this->term->reset(),
			       $this->term->color( 31 ) . '$1' . $this->term->reset() ),
			$text );
	}

	/**
	 * Show "Reading tests from ..."
	 *
	 * @param String $path
	 */
	protected function showRunFile( $path ){
		print $this->term->color( 1 ) .
			"Reading tests from \"$path\"..." .
			$this->term->reset() .
			"\n";
	}

	/**
	 * Insert a temporary test article
	 * @param string $name the title, including any prefix
	 * @param string $text the article text
	 * @param int $line the input line number, for reporting errors
	 */
	private function addArticle($name, $text, $line) {
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
	 */
	private function tidy( $text ) {
		global $wgUseTidy;
		if ($wgUseTidy) {
			$text = Parser::tidy($text);
		}
		return $text;
	}

	private function wellFormed( $text ) {
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

	private function extractFragment( $text, $position ) {
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
	 */
	public function color( $color ) {
		global $wgCommandLineDarkBg;
		$light = $wgCommandLineDarkBg ? "1;" : "0;";
		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return string
	 */
	public function reset() {
		return $this->color( 0 );
	}
}

/* A colour-less terminal */
class DummyTermColorer {
	public function color( $color ) {
		return '';
	}

	public function reset() {
		return '';
	}
}

class TestRecorder {
	var $parent;
	var $term;

	function __construct( $parent ) {
		$this->parent = $parent;
		$this->term = $parent->term;
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
			$failed = $total - $success ;
			print $this->term->color( 31 ) . "$failed tests failed!";
		}
		print $this->term->reset() . "\n";
		return ($success == $total);
	}
}

class DbTestPreviewer extends TestRecorder  {
	protected $lb;      ///< Database load balancer
	protected $db;      ///< Database connection to the main DB
	protected $curRun;  ///< run ID number for the current run
	protected $prevRun; ///< run ID number for the previous run, if any
	protected $results; ///< Result array

	/**
	 * This should be called before the table prefix is changed
	 */
	function __construct( $parent ) {
		parent::__construct( $parent );
		$this->lb = wfGetLBFactory()->newMainLB();
		// This connection will have the wiki's table prefix, not parsertest_
		$this->db = $this->lb->getConnection( DB_MASTER );
	}

	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		global $wgDBtype, $wgDBprefix;
		parent::start();

		if( ! $this->db->tableExists( 'testrun' ) 
			or ! $this->db->tableExists( 'testitem' ) ) 
		{
			print "WARNING> `testrun` table not found in database.\n";
			$this->prevRun = false;
		} else {
			// We'll make comparisons against the previous run later...
			$this->prevRun = $this->db->selectField( 'testrun', 'MAX(tr_id)' );
		}
		$this->results = array();
	}

	function record( $test, $result ) {
		parent::record( $test, $result );
		$this->results[$test] = $result;
	}

	function report() {
		if( $this->prevRun ) {
			// f = fail, p = pass, n = nonexistent
			// codes show before then after
			$table = array(
				'fp' => 'previously failing test(s) now PASSING! :)',
				'pn' => 'previously PASSING test(s) removed o_O',
				'np' => 'new PASSING test(s) :)',

				'pf' => 'previously passing test(s) now FAILING! :(',
				'fn' => 'previously FAILING test(s) removed O_o',
				'nf' => 'new FAILING test(s) :(',
				'ff' => 'still FAILING test(s) :(',
			);

			$prevResults = array();

			$res = $this->db->select( 'testitem', array( 'ti_name', 'ti_success' ),
				array( 'ti_run' => $this->prevRun ), __METHOD__ );
			foreach ( $res as $row ) {
				if ( !$this->parent->regex 
					|| preg_match( "/{$this->parent->regex}/i", $row->ti_name ) )
				{
					$prevResults[$row->ti_name] = $row->ti_success;
				}
			}

			$combined = array_keys( $this->results + $prevResults );

			# Determine breakdown by change type
			$breakdown = array();
			foreach ( $combined as $test ) {
				if ( !isset( $prevResults[$test] ) ) {
					$before = 'n';
				} elseif ( $prevResults[$test] == 1 ) {
					$before = 'p';
				} else /* if ( $prevResults[$test] == 0 )*/ {
					$before = 'f';
				}
				if ( !isset( $this->results[$test] ) ) {
					$after = 'n';
				} elseif ( $this->results[$test] == 1 ) {
					$after = 'p';
				} else /*if ( $this->results[$test] == 0 ) */ {
					$after = 'f';
				}
				$code = $before . $after;
				if ( isset( $table[$code] ) ) {
					$breakdown[$code][$test] = $this->getTestStatusInfo( $test, $after );
				}
			}

			# Write out results
			foreach ( $table as $code => $label ) {
				if( !empty( $breakdown[$code] ) ) {
					$count = count($breakdown[$code]);
					printf( "\n%4d %s\n", $count, $label );
					foreach ($breakdown[$code] as $differing_test_name => $statusInfo) {
						print "      * $differing_test_name  [$statusInfo]\n";
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
	 ** Returns a string giving information about when a test last had a status change.
	 ** Could help to track down when regressions were introduced, as distinct from tests
	 ** which have never passed (which are more change requests than regressions).
	 */
	private function getTestStatusInfo($testname, $after) {

		// If we're looking at a test that has just been removed, then say when it first appeared.
		if ( $after == 'n' ) {
			$changedRun = $this->db->selectField ( 'testitem',
												   'MIN(ti_run)',
												   array( 'ti_name' => $testname ),
												   __METHOD__ );
			$appear = $this->db->selectRow ( 'testrun',
											 array( 'tr_date', 'tr_mw_version' ),
											 array( 'tr_id' => $changedRun ),
											 __METHOD__ );
			return "First recorded appearance: "
			       . date( "d-M-Y H:i:s",  strtotime ( $appear->tr_date ) )
			       .  ", " . $appear->tr_mw_version;
		}

		// Otherwise, this test has previous recorded results.
		// See when this test last had a different result to what we're seeing now.
		$conds = array( 
			'ti_name'    => $testname,
			'ti_success' => ($after == 'f' ? "1" : "0") );
		if ( $this->curRun ) {
			$conds[] = "ti_run != " . $this->db->addQuotes ( $this->curRun );
		}

		$changedRun = $this->db->selectField ( 'testitem', 'MAX(ti_run)', $conds, __METHOD__ );

		// If no record of ever having had a different result.
		if ( is_null ( $changedRun ) ) {
			if ($after == "f") {
				return "Has never passed";
			} else {
				return "Has never failed";
			}
		}

		// Otherwise, we're looking at a test whose status has changed.
		// (i.e. it used to work, but now doesn't; or used to fail, but is now fixed.)
		// In this situation, give as much info as we can as to when it changed status.
		$pre  = $this->db->selectRow ( 'testrun',
										array( 'tr_date', 'tr_mw_version' ),
										array( 'tr_id' => $changedRun ),
										__METHOD__ );
		$post = $this->db->selectRow ( 'testrun',
										array( 'tr_date', 'tr_mw_version' ),
										array( "tr_id > " . $this->db->addQuotes ( $changedRun) ),
										__METHOD__,
										array( "LIMIT" => 1, "ORDER BY" => 'tr_id' )
									 );

		if ( $post ) {
			$postDate = date( "d-M-Y H:i:s",  strtotime ( $post->tr_date  ) ) . ", {$post->tr_mw_version}";
		} else {
			$postDate = 'now';
		}
		return ( $after == "f" ? "Introduced" : "Fixed" ) . " between "
				. date( "d-M-Y H:i:s",  strtotime ( $pre->tr_date ) ) .  ", " . $pre->tr_mw_version
				. " and $postDate";

	}

	/**
	 * Commit transaction and clean up for result recording
	 */
	function end() {
		$this->lb->commitMasterChanges();
		$this->lb->closeAll();
		parent::end();
	}

}

class DbTestRecorder extends DbTestPreviewer  {
	/**
	 * Set up result recording; insert a record for the run with the date
	 * and all that fun stuff
	 */
	function start() {
		global $wgDBtype, $wgDBprefix;
		$this->db->begin();

		if( ! $this->db->tableExists( 'testrun' ) 
			or ! $this->db->tableExists( 'testitem' ) ) 
		{
			print "WARNING> `testrun` table not found in database. Trying to create table.\n";
			if ($wgDBtype === 'postgres')
				$this->db->sourceFile( dirname(__FILE__) . '/testRunner.postgres.sql' );
			else
				$this->db->sourceFile( dirname(__FILE__) . '/testRunner.sql' );
			echo "OK, resuming.\n";
		}
		
		parent::start();

		$this->db->insert( 'testrun',
			array(
				'tr_date'        => $this->db->timestamp(),
				'tr_mw_version'  => SpecialVersion::getVersion(),
				'tr_php_version' => phpversion(),
				'tr_db_version'  => $this->db->getServerVersion(),
				'tr_uname'       => php_uname()
			),
			__METHOD__ );
			if ($wgDBtype === 'postgres')
				$this->curRun = $this->db->currentSequenceValue('testrun_id_seq');
			else
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
}
