<?php
# Copyright (C) 2004, 2010 Brion Vibber <brion@pobox.com>
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
		$this->color = !wfIsWindows() && posix_isatty( 1 );

		if ( isset( $options['color'] ) ) {
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


		if ( isset( $options['regex'] ) ) {
			if ( isset( $options['record'] ) ) {
				echo "Warning: --record cannot be used with --regex, disabling --record\n";
				unset( $options['record'] );
			}
			$this->regex = $options['regex'];
		} else {
			# Matches anything
			$this->regex = '';
		}

		$this->setupRecorder();
		$this->keepUploads = isset( $options['keep-uploads'] );

		if ( isset( $options['seed'] ) ) {
			$this->fuzzSeed = intval( $options['seed'] ) - 1;
		}

		$this->runDisabled = isset( $options['run-disabled'] );

		$this->hooks = array();
		$this->functionHooks = array();
	}

	public function setupRecorder() {
		global $options;
		if ( isset( $options['record'] ) ) {
			$this->recorder = new DbTestRecorder( $this );
		} elseif ( isset( $options['compare'] ) ) {
			$this->recorder = new DbTestPreviewer( $this );
		} elseif ( isset( $options['upload'] ) ) {
			$this->recorder = new RemoteTestRecorder( $this );
		} else {
			$this->recorder = new TestRecorder( $this );
		}
	}

	/**
	 * Remove last character if it is a newline
	 */
	public function chomp( $s ) {
		if ( substr( $s, -1 ) === "\n" ) {
			return substr( $s, 0, -1 );
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
		foreach ( $filenames as $filename ) {
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
			$memStats['$' . $name] = strlen( serialize( $value ) );
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
	 * @param $filenames Array of strings
	 * @return Boolean: true if passed all tests, false if any tests failed.
	 */
	public function runTestsFromFiles( $filenames ) {
		$this->recorder->start();
		$this->setupDatabase();
		$ok = true;
		foreach ( $filenames as $filename ) {
			$tests = new TestFileIterator( $filename, $this );
			$ok = $this->runTests( $tests ) && $ok;
		}
		$this->teardownDatabase();
		$this->recorder->report();
		$this->recorder->end();
		return $ok;
	}

	function runTests( $tests ) {
		$ok = true;
		foreach ( $tests as $i => $t ) {
			$result =
				$this->runTest( $t['test'], $t['input'], $t['result'], $t['options'], $t['config'] );
			$ok = $ok && $result;
			$this->recorder->record( $t['test'], $result );
		}
		if ( $this->showProgress ) {
			print "\n";
		}
		return $ok;
	}

	/**
	 * Get a Parser object
	 */
	function getParser( $preprocessor = null ) {
		global $wgParserConf;
		$class = $wgParserConf['class'];
		$parser = new $class( array( 'preprocessorClass' => $preprocessor ) + $wgParserConf );
		foreach ( $this->hooks as $tag => $callback ) {
			$parser->setHook( $tag, $callback );
		}
		foreach ( $this->functionHooks as $tag => $bits ) {
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
	 * @param $desc String: test's description
	 * @param $input String: wikitext to try rendering
	 * @param $result String: result to output
	 * @param $opts Array: test's options
	 * @param $config String: overrides for global variables, one per line
	 * @return Boolean
	 */
	public function runTest( $desc, $input, $result, $opts, $config ) {
		if ( $this->showProgress ) {
			$this->showTesting( $desc );
		}

		$opts = $this->parseOptions( $opts );
		$this->setupGlobals( $opts, $config );

		$user = new User();
		$options = ParserOptions::newFromUser( $user );

		$m = array();
		if ( isset( $opts['title'] ) ) {
			$titleText = $opts['title'];
		}
		else {
			$titleText = 'Parser test';
		}

		$noxml = isset( $opts['noxml'] );
		$local = isset( $opts['local'] );
		$preprocessor = isset( $opts['preprocessor'] ) ? $opts['preprocessor'] : null;
		$parser = $this->getParser( $preprocessor );
		$title = Title::newFromText( $titleText );

		$matches = array();
		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $input, $options );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $input, $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $input, $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$linker = $user->getSkin();
			$out = $linker->formatComment( $input, $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getpreloadText( $input, $title, $options );
		} else {
			$output = $parser->parse( $input, $title, $options, true, true, 1337 );
			$out = $output->getText();

			if ( isset( $opts['showtitle'] ) ) {
				if ( $output->getTitleText() ) $title = $output->getTitleText();
				$out = "$title\n$out";
			}
			if ( isset( $opts['ill'] ) ) {
				$out = $this->tidy( implode( ' ', $output->getLanguageLinks() ) );
			} elseif ( isset( $opts['cat'] ) ) {
				global $wgOut;
				$wgOut->addCategoryLinks( $output->getCategories() );
				$cats = $wgOut->getCategoryLinks();
				if ( isset( $cats['normal'] ) ) {
					$out = $this->tidy( implode( ' ', $cats['normal'] ) );
				} else {
					$out = '';
				}
			}

			$result = $this->tidy( $result );
		}


		$this->teardownGlobals();

		if ( $result === $out && ( $noxml === true || $this->wellFormed( $out ) ) ) {
			return $this->showSuccess( $desc );
		} else {
			return $this->showFailure( $desc, $result, $out );
		}
	}


	/**
	 * Use a regex to find out the value of an option
	 * @param $key String: name of option val to retrieve
	 * @param $opts Options array to look in
	 * @param $default Mixed: default value returned if not found
	 */
	private static function getOptionValue( $key, $opts, $default ) {
		$key = strtolower( $key );
		if ( isset( $opts[$key] ) ) {
			return $opts[$key];
		} else {
			return $default;
		}
	}

	private function parseOptions( $instring ) {
		$opts = array();
		$lines = explode( "\n", $instring );
		// foo
		// foo=bar
		// foo="bar baz"
		// foo=[[bar baz]]
		// foo=bar,"baz quux"
		$regex = '/\b
			([\w-]+)						# Key
			\b
			(?:\s*
				=						# First sub-value
				\s*
				(
					"
						[^"]*			# Quoted val
					"
				|
					\[\[
						[^]]*			# Link target
					\]\]
				|
					[\w-]+				# Plain word
				)
				(?:\s*
					,					# Sub-vals 1..N
					\s*
					(
						"[^"]*"			# Quoted val
					|
						\[\[[^]]*\]\]	# Link target
					|
						[\w-]+			# Plain word
					)
				)*
			)?
			/x';

		if ( preg_match_all( $regex, $instring, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $bits ) {
				$match = array_shift( $bits );
				$key = strtolower( array_shift( $bits ) );
				if ( count( $bits ) == 0 ) {
					$opts[$key] = true;
				} elseif ( count( $bits ) == 1 ) {
					$opts[$key] = $this->cleanupOption( array_shift( $bits ) );
				} else {
					// Array!
					$opts[$key] = array_map( array( $this, 'cleanupOption' ), $bits );
				}
			}
		}
		return $opts;
	}

	private function cleanupOption( $opt ) {
		if ( substr( $opt, 0, 1 ) == '"' ) {
			return substr( $opt, 1, -1 );
		}
		if ( substr( $opt, 0, 2 ) == '[[' ) {
			return substr( $opt, 2, -2 );
		}
		return $opt;
	}

	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 */
	private function setupGlobals( $opts = '', $config = '' ) {
		global $wgDBtype;
		if ( !isset( $this->uploadDir ) ) {
			$this->uploadDir = $this->setupUploadDir();
		}

		# Find out values for some special options.
		$lang =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

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
			'wgServerName' => 'Britney-Spears',
			'wgLanguageCode' => $lang,
			'wgContLanguageCode' => $lang,
			'wgDBprefix' => $wgDBtype != 'oracle' ? 'parsertest_' : 'pt_',
			'wgRawHtml' => isset( $opts['rawhtml'] ),
			'wgLang' => null,
			'wgContLang' => null,
			'wgNamespacesWithSubpages' => array( 0 => isset( $opts['subpage'] ) ),
			'wgMaxTocLevel' => $maxtoclevel,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgThumbnailScriptPath' => false,
			'wgUseImageResize' => false,
			'wgUseTeX' => isset( $opts['math'] ),
			'wgMathDirectory' => $this->uploadDir . '/math',
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
			'wgExperimentalHtmlIds' => false,
			'wgExternalLinkTarget' => false,
			'wgAlwaysUseTidy' => false,
			'wgHtml5' => true,
			'wgWellFormedXml' => true,
			'wgAllowMicrodataAttributes' => true,
		);

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );

				$settings[$var] = eval( "return $value;" );
			}
		}

		$this->savedGlobals = array();
		foreach ( $settings as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}
			$GLOBALS[$var] = $val;
		}
		$langObj = Language::factory( $lang );
		$GLOBALS['wgLang'] = $langObj;
		$GLOBALS['wgContLang'] = $langObj;
		$GLOBALS['wgMemc'] = new FakeMemCachedClient;
		$GLOBALS['wgOut'] = new OutputPage;

		global $wgHooks;
		$wgHooks['ParserTestParser'][] = 'ParserTestParserHook::setup';
		$wgHooks['ParserTestParser'][] = 'ParserTestStaticParserHook::setup';
		$wgHooks['ParserGetVariableValueTs'][] = 'ParserTest::getFakeTimestamp';

		MagicWord::clearCache();

		global $wgUser;
		$wgUser = new User();
	}

	/**
	 * List of temporary tables to create, without prefix.
	 * Some of these probably aren't necessary.
	 */
	private function listTables() {
		global $wgDBtype;
		$tables = array( 'user', 'page', 'page_restrictions',
			'protected_titles', 'revision', 'text', 'pagelinks', 'imagelinks',
			'categorylinks', 'templatelinks', 'externallinks', 'langlinks', 'iwlinks',
			'site_stats', 'hitcounter',	'ipblocks', 'image', 'oldimage',
			'recentchanges', 'watchlist', 'math', 'interwiki',
			'querycache', 'objectcache', 'job', 'l10n_cache', 'redirect', 'querycachetwo',
			'archive', 'user_groups', 'page_props', 'category',
		);

		if ( $wgDBtype === 'mysql' )
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
	public function setupDatabase() {
		global $wgDBprefix, $wgDBtype;
		if ( $this->databaseSetupDone ) {
			return;
		}
		if ( $wgDBprefix === 'parsertest_' || ( $wgDBtype == 'oracle' && $wgDBprefix === 'pt_' ) ) {
			throw new MWException( 'setupDatabase should be called before setupGlobals' );
		}
		$this->databaseSetupDone = true;
		$this->oldTablePrefix = $wgDBprefix;

		# SqlBagOStuff broke when using temporary tables on r40209 (bug 15892).
		# It seems to have been fixed since (r55079?).
		# If it fails, $wgCaches[CACHE_DB] = new HashBagOStuff(); should work around it.

		# CREATE TEMPORARY TABLE breaks if there is more than one server
		if ( wfGetLB()->getServerCount() != 1 ) {
			$this->useTemporaryTables = false;
		}

		$temporary = $this->useTemporaryTables || $wgDBtype == 'postgres';

		$db = wfGetDB( DB_MASTER );
		$tables = $this->listTables();

		foreach ( $tables as $tbl ) {
			# Clean up from previous aborted run.  So that table escaping
			# works correctly across DB engines, we need to change the pre-
			# fix back and forth so tableName() works right.
			$this->changePrefix( $this->oldTablePrefix );
			$oldTableName = $db->tableName( $tbl );
			$this->changePrefix( $wgDBtype != 'oracle' ? 'parsertest_' : 'pt_' );
			$newTableName = $db->tableName( $tbl );

			if ( $db->tableExists( $tbl ) && $wgDBtype != 'postgres' && $wgDBtype != 'oracle' ) {
				$db->query( "DROP TABLE $newTableName" );
			}
			# Create new table
			$db->duplicateTableStructure( $oldTableName, $newTableName, $temporary );
		}
		if ( $wgDBtype == 'oracle' )
			$db->query( 'BEGIN FILL_WIKI_INFO; END;' );

		$this->changePrefix( $wgDBtype != 'oracle' ? 'parsertest_' : 'pt_' );

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


		if ( $wgDBtype == 'oracle' ) {
			# Insert 0 and 1 user_ids to prevent FK violations

			# Anonymous user
			$db->insert( 'user', array(
				'user_id'         => 0,
				'user_name'       => 'Anonymous' ) );

			# Hack-on-Hack: Insert a test user to be able to insert an image
			$db->insert( 'user', array(
				'user_id'         => 1,
				'user_name'       => 'Tester' ) );
		}

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

		# This image will be blacklisted in [[MediaWiki:Bad image list]]
		$db->insert( 'image', array(
			'img_name'        => 'Bad.jpg',
			'img_size'        => 12345,
			'img_description' => 'zomgnotcensored',
			'img_user'        => 1,
			'img_user_text'   => 'WikiSysop',
			'img_timestamp'   => $db->timestamp( '20010115123500' ),
			'img_width'       => 320,
			'img_height'      => 240,
			'img_bits'        => 24,
			'img_media_type'  => MEDIATYPE_BITMAP,
			'img_major_mime'  => "image",
			'img_minor_mime'  => "jpeg",
			'img_metadata'    => serialize( array() ),
			) );

		# Update certain things in site_stats
		$db->insert( 'site_stats', array( 'ss_row_id' => 1, 'ss_images' => 2, 'ss_good_articles' => 1 ) );

		# Reinitialise the LocalisationCache to match the database state
		Language::getLocalisationCache()->unloadAll();

		# Make a new message cache
		global $wgMessageCache, $wgMemc;
		$wgMessageCache = new MessageCache( $wgMemc, true, 3600, '' );
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

	public function teardownDatabase() {
		global $wgDBtype;
		if ( !$this->databaseSetupDone ) {
			return;
		}
		$this->changePrefix( $this->oldTablePrefix );
		$this->databaseSetupDone = false;
		if ( $this->useTemporaryTables ) {
			# Don't need to do anything
			return;
		}

		$tables = $this->listTables();
		$db = wfGetDB( DB_MASTER );
		foreach ( $tables as $table ) {
			$sql = $wgDBtype == 'oracle' ? "DROP TABLE pt_$table DROP CONSTRAINTS" : "DROP TABLE `parsertest_$table`";
			$db->query( $sql );
		}
		if ($wgDBtype == 'oracle')
			$db->query('BEGIN FILL_WIKI_INFO; END;');
	}

	/**
	 * Create a dummy uploads directory which will contain a couple
	 * of files in order to pass existence tests.
	 *
	 * @return String: the directory
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

		wfMkdirParents( $dir . '/0/09' );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/0/09/Bad.jpg" );
		return $dir;
	}

	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 */
	private function teardownGlobals() {
		RepoGroup::destroySingleton();
		LinkCache::singleton()->clear();
		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
		if ( isset( $this->uploadDir ) ) {
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

				"$dir/0/09/Bad.jpg",

				"$dir/math/f/a/5/fa50b8b616463173474302ca3e63586b.png",
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

				"$dir/0/09/",
				"$dir/0/",
				"$dir/thumb",
				"$dir/math/f/a/5",
				"$dir/math/f/a",
				"$dir/math/f",
				"$dir/math",
				"$dir",
			)
		);
	}

	/**
	 * Delete the specified files, if they exist.
	 * @param $files Array: full paths to files to delete.
	 */
	private static function deleteFiles( $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}
	}

	/**
	 * Delete the specified directories, if they exist. Must be empty.
	 * @param $dirs Array: full paths to directories to delete.
	 */
	private static function deleteDirs( $dirs ) {
		foreach ( $dirs as $dir ) {
			if ( is_dir( $dir ) ) {
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
	 * @param $desc String: the test name
	 * @return Boolean
	 */
	protected function showSuccess( $desc ) {
		if ( $this->showProgress ) {
			print $this->term->color( '1;32' ) . 'PASSED' . $this->term->reset() . "\n";
		}
		return true;
	}

	/**
	 * Print a failure message and provide some explanatory output
	 * about what went wrong if so configured.
	 *
	 * @param $desc String: the test name
	 * @param $result String: expected HTML output
	 * @param $html String: actual HTML output
	 * @return Boolean
	 */
	protected function showFailure( $desc, $result, $html ) {
		if ( $this->showFailure ) {
			if ( !$this->showProgress ) {
				# In quiet mode we didn't show the 'Testing' message before the
				# test, in case it succeeded. Show it now:
				$this->showTesting( $desc );
			}
			print $this->term->color( '31' ) . 'FAILED!' . $this->term->reset() . "\n";
			if ( $this->showOutput ) {
				print "--- Expected ---\n$result\n--- Actual ---\n$html\n";
			}
			if ( $this->showDiffs ) {
				print $this->quickDiff( $result, $html );
				if ( !$this->wellFormed( $html ) ) {
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
	 * @param $input String
	 * @param $output String
	 * @param $inFileTail String: tailing for the input file name
	 * @param $outFileTail String: tailing for the output file name
	 * @return String
	 */
	protected function quickDiff( $input, $output, $inFileTail = 'expected', $outFileTail = 'actual' ) {
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
	 * @param $data String
	 * @param $filename String
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
	 * @param $text String
	 * @return String
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
	 * @param $path String
	 */
	public function showRunFile( $path ) {
		print $this->term->color( 1 ) .
			"Reading tests from \"$path\"..." .
			$this->term->reset() .
			"\n";
	}

	/**
	 * Insert a temporary test article
	 * @param $name String: the title, including any prefix
	 * @param $text String: the article text
	 * @param $line Integer: the input line number, for reporting errors
	 */
	public function addArticle( $name, $text, $line ) {
		$this->setupGlobals();
		$title = Title::newFromText( $name );
		if ( is_null( $title ) ) {
			wfDie( "invalid title at line $line\n" );
		}

		$aid = $title->getArticleID( GAID_FOR_UPDATE );
		if ( $aid != 0 ) {
			wfDie( "duplicate article '$name' at line $line\n" );
		}

		$art = new Article( $title );
		$art->doEdit( $text, '', EDIT_NEW | EDIT_DEFER_UPDATES | EDIT_AUTOSUMMARY,
			false, null, false, false, '', true );

		$this->teardownGlobals();
	}

	/**
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * abort processing of this file.
	 *
	 * @param $name String
	 * @return Bool true if tag hook is present
	 */
	public function requireHook( $name ) {
		global $wgParser;
		$wgParser->firstCallInit( ); // make sure hooks are loaded.
		if ( isset( $wgParser->mTagHooks[$name] ) ) {
			$this->hooks[$name] = $wgParser->mTagHooks[$name];
		} else {
			echo "   This test suite requires the '$name' hook extension, skipping.\n";
			return false;
		}
		return true;
	}

	/**
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * abort processing of this file.
	 *
	 * @param $name String
	 * @return Bool true if function hook is present
	 */
	public function requireFunctionHook( $name ) {
		global $wgParser;
		$wgParser->firstCallInit( ); // make sure hooks are loaded.
		if ( isset( $wgParser->mFunctionHooks[$name] ) ) {
			$this->functionHooks[$name] = $wgParser->mFunctionHooks[$name];
		} else {
			echo "   This test suite requires the '$name' function hook extension, skipping.\n";
			return false;
		}
		return true;
	}

	/*
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param $text String: the text to tidy
	 * @return String
	 * @static
	 */
	private function tidy( $text ) {
		global $wgUseTidy;
		if ( $wgUseTidy ) {
			$text = Parser::tidy( $text );
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

		if ( !xml_parse( $parser, $html, true ) ) {
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

	static function getFakeTimestamp( &$parser, &$ts ) {
		$ts = 123;
		return true;
	}
}

class AnsiTermColorer {
	function __construct() {
	}

	/**
	 * Return ANSI terminal escape code for changing text attribs/color
	 *
	 * @param $color String: semicolon-separated list of attribute/color codes
	 * @return String
	 */
	public function color( $color ) {
		global $wgCommandLineDarkBg;
		$light = $wgCommandLineDarkBg ? "1;" : "0;";
		return "\x1b[{$light}{$color}m";
	}

	/**
	 * Return ANSI terminal escape code for restoring default text attributes
	 *
	 * @return String
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
		$this->success += ( $result ? 1 : 0 );
	}

	function end() {
		// dummy
	}

	function report() {
		if ( $this->total > 0 ) {
			$this->reportPercentage( $this->success, $this->total );
		} else {
			wfDie( "No tests found.\n" );
		}
	}

	function reportPercentage( $success, $total ) {
		$ratio = wfPercent( 100 * $success / $total );
		print $this->term->color( 1 ) . "Passed $success of $total tests ($ratio)... ";
		if ( $success == $total ) {
			print $this->term->color( 32 ) . "ALL TESTS PASSED!";
		} else {
			$failed = $total - $success ;
			print $this->term->color( 31 ) . "$failed tests failed!";
		}
		print $this->term->reset() . "\n";
		return ( $success == $total );
	}
}

class DbTestPreviewer extends TestRecorder  {
	protected $lb;      // /< Database load balancer
	protected $db;      // /< Database connection to the main DB
	protected $curRun;  // /< run ID number for the current run
	protected $prevRun; // /< run ID number for the previous run, if any
	protected $results; // /< Result array

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
		global $wgDBtype;
		parent::start();

		if ( ! $this->db->tableExists( 'testrun' )
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
		if ( $this->prevRun ) {
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
				if ( !empty( $breakdown[$code] ) ) {
					$count = count( $breakdown[$code] );
					printf( "\n%4d %s\n", $count, $label );
					foreach ( $breakdown[$code] as $differing_test_name => $statusInfo ) {
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
	private function getTestStatusInfo( $testname, $after ) {

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
			'ti_success' => ( $after == 'f' ? "1" : "0" ) );
		if ( $this->curRun ) {
			$conds[] = "ti_run != " . $this->db->addQuotes ( $this->curRun );
		}

		$changedRun = $this->db->selectField ( 'testitem', 'MAX(ti_run)', $conds, __METHOD__ );

		// If no record of ever having had a different result.
		if ( is_null ( $changedRun ) ) {
			if ( $after == "f" ) {
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
										array( "tr_id > " . $this->db->addQuotes ( $changedRun ) ),
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
		global $wgDBtype, $options;
		$this->db->begin();

		if ( ! $this->db->tableExists( 'testrun' )
			or ! $this->db->tableExists( 'testitem' ) )
		{
			print "WARNING> `testrun` table not found in database. Trying to create table.\n";
			if ( $wgDBtype === 'postgres' )
				$this->db->sourceFile( dirname( __FILE__ ) . '/testRunner.postgres.sql' );
			elseif ( $wgDBtype === 'oracle' )
				$this->db->sourceFile( dirname( __FILE__ ) . '/testRunner.ora.sql' );
			else
				$this->db->sourceFile( dirname( __FILE__ ) . '/testRunner.sql' );
			echo "OK, resuming.\n";
		}

		parent::start();

		$this->db->insert( 'testrun',
			array(
				'tr_date'        => $this->db->timestamp(),
				'tr_mw_version'  => isset( $options['setversion'] ) ?
					$options['setversion'] : SpecialVersion::getVersion(),
				'tr_php_version' => phpversion(),
				'tr_db_version'  => $this->db->getServerVersion(),
				'tr_uname'       => php_uname()
			),
			__METHOD__ );
			if ( $wgDBtype === 'postgres' )
				$this->curRun = $this->db->currentSequenceValue( 'testrun_id_seq' );
			else
				$this->curRun = $this->db->insertId();
	}

	/**
	 * Record an individual test item's success or failure to the db
	 *
	 * @param $test String
	 * @param $result Boolean
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

class RemoteTestRecorder extends TestRecorder {
	function start() {
		parent::start();
		$this->results = array();
		$this->ping( 'running' );
	}

	function record( $test, $result ) {
		parent::record( $test, $result );
		$this->results[$test] = (bool)$result;
	}

	function end() {
		$this->ping( 'complete', $this->results );
		parent::end();
	}

	/**
	 * Inform a CodeReview instance that we've started or completed a test run...
	 *
	 * @param $status string: "running" - tell it we've started
	 *                        "complete" - provide test results array
	 *                        "abort" - something went horribly awry
	 * @param $results array of test name => true/false
	 */
	function ping( $status, $results = false ) {
		global $wgParserTestRemote, $IP;

		$remote = $wgParserTestRemote;
		$revId = SpecialVersion::getSvnRevision( $IP );
		$jsonResults = json_encode( $results );

		if ( !$remote ) {
			print "Can't do remote upload without configuring \$wgParserTestRemote!\n";
			exit( 1 );
		}

		// Generate a hash MAC to validate our credentials
		$message = array(
			$remote['repo'],
			$remote['suite'],
			$revId,
			$status,
		);
		if ( $status == "complete" ) {
			$message[] = $jsonResults;
		}
		$hmac = hash_hmac( "sha1", implode( "|", $message ), $remote['secret'] );

		$postData = array(
			'action' => 'codetestupload',
			'format' => 'json',
			'repo'   => $remote['repo'],
			'suite'  => $remote['suite'],
			'rev'    => $revId,
			'status' => $status,
			'hmac'   => $hmac,
		);
		if ( $status == "complete" ) {
			$postData['results'] = $jsonResults;
		}
		$response = $this->post( $remote['api-url'], $postData );

		if ( $response === false ) {
			print "CodeReview info upload failed to reach server.\n";
			exit( 1 );
		}
		$responseData = FormatJson::decode( $response, true );
		if ( !is_array( $responseData ) ) {
			print "CodeReview API response not recognized...\n";
			wfDebug( "Unrecognized CodeReview API response: $response\n" );
			exit( 1 );
		}
		if ( isset( $responseData['error'] ) ) {
			$code = $responseData['error']['code'];
			$info = $responseData['error']['info'];
			print "CodeReview info upload failed: $code $info\n";
			exit( 1 );
		}
	}

	function post( $url, $data ) {
		return Http::post( $url, array( 'postData' => $data ) );
	}
}

class TestFileIterator implements Iterator {
    private $file;
    private $fh;
    private $parser;
    private $index = 0;
    private $test;
	private $lineNum;
	private $eof;

	function __construct( $file, $parser = null ) {
		global $IP;

		$this->file = $file;
    	$this->fh = fopen( $this->file, "rt" );
        if ( !$this->fh ) {
			wfDie( "Couldn't open file '$file'\n" );
		}

		$this->parser = $parser;

		if ( $this->parser ) $this->parser->showRunFile( wfRelativePath( $this->file, $IP ) );
		$this->lineNum = $this->index = 0;
	}

	function setParser( ParserTest $parser ) {
		$this->parser = $parser;
	}

	function rewind() {
		if ( fseek( $this->fh, 0 ) ) {
			wfDie( "Couldn't fseek to the start of '$this->file'\n" );
		}
		$this->index = -1;
		$this->lineNum = 0;
		$this->eof = false;
		$this->next();

		return true;
    }

    function current() {
		return $this->test;
    }

    function key() {
		return $this->index;
    }

    function next() {
        if ( $this->readNextTest() ) {
			$this->index++;
			return true;
		} else {
			$this->eof = true;
		}
    }

    function valid() {
		return $this->eof != true;
    }

	function readNextTest() {
		$data = array();
		$section = null;

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = array();
			if ( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$section = strtolower( $matches[1] );
				if ( $section == 'endarticle' ) {
					if ( !isset( $data['text'] ) ) {
						wfDie( "'endarticle' without 'text' at line {$this->lineNum} of $this->file\n" );
					}
					if ( !isset( $data['article'] ) ) {
						wfDie( "'endarticle' without 'article' at line {$this->lineNum} of $this->file\n" );
					}
					if ( $this->parser ) {
						$this->parser->addArticle( $this->parser->chomp( $data['article'] ), $this->parser->chomp( $data['text'] ),
							$this->lineNum );
					}
					$data = array();
					$section = null;
					continue;
				}
				if ( $section == 'endhooks' ) {
					if ( !isset( $data['hooks'] ) ) {
						wfDie( "'endhooks' without 'hooks' at line {$this->lineNum} of $this->file\n" );
					}
					foreach ( explode( "\n", $data['hooks'] ) as $line ) {
						$line = trim( $line );
						if ( $line ) {
							if ( $this->parser && !$this->parser->requireHook( $line ) ) {
								return false;
							}
						}
					}
					$data = array();
					$section = null;
					continue;
				}
				if ( $section == 'endfunctionhooks' ) {
					if ( !isset( $data['functionhooks'] ) ) {
						wfDie( "'endfunctionhooks' without 'functionhooks' at line {$this->lineNum} of $this->file\n" );
					}
					foreach ( explode( "\n", $data['functionhooks'] ) as $line ) {
						$line = trim( $line );
						if ( $line ) {
							if ( $this->parser && !$this->parser->requireFunctionHook( $line ) ) {
								return false;
							}
						}
					}
					$data = array();
					$section = null;
					continue;
				}
				if ( $section == 'end' ) {
					if ( !isset( $data['test'] ) ) {
						wfDie( "'end' without 'test' at line {$this->lineNum} of $this->file\n" );
					}
					if ( !isset( $data['input'] ) ) {
						wfDie( "'end' without 'input' at line {$this->lineNum} of $this->file\n" );
					}
					if ( !isset( $data['result'] ) ) {
						wfDie( "'end' without 'result' at line {$this->lineNum} of $this->file\n" );
					}
					if ( !isset( $data['options'] ) ) {
						$data['options'] = '';
					}
					if ( !isset( $data['config'] ) )
						$data['config'] = '';

					if ( $this->parser
						 && ( ( preg_match( '/\\bdisabled\\b/i', $data['options'] ) && !$this->parser->runDisabled )
							 || !preg_match( "/" . $this->parser->regex . "/i", $data['test'] ) )  ) {
						# disabled test
						$data = array();
						$section = null;
						continue;
					}
					if ( $this->parser &&
						 preg_match( '/\\bmath\\b/i', $data['options'] ) && !$this->parser->savedGlobals['wgUseTeX'] ) {
						# don't run math tests if $wgUseTeX is set to false in LocalSettings
						$data = array();
						$section = null;
						continue;
					}

					if ( $this->parser ) {
						$this->test = array(
							'test' => $this->parser->chomp( $data['test'] ),
							'input' => $this->parser->chomp( $data['input'] ),
							'result' => $this->parser->chomp( $data['result'] ),
							'options' => $this->parser->chomp( $data['options'] ),
							'config' => $this->parser->chomp( $data['config'] ) );
					} else {
						$this->test['test'] = $data['test'];
					}
					return true;
				}
				if ( isset ( $data[$section] ) ) {
					wfDie( "duplicate section '$section' at line {$this->lineNum} of $this->file\n" );
				}
				$data[$section] = '';
				continue;
			}
			if ( $section ) {
				$data[$section] .= $line;
			}
		}
		return false;
	}
}

