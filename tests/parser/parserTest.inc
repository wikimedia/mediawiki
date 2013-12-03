<?php
/**
 * Helper code for the MediaWiki parser test suite. Some code is duplicated
 * in PHPUnit's NewParserTests.php, so you'll probably want to update both
 * at the same time.
 *
 * Copyright © 2004, 2010 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 *
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
 * @todo Make this more independent of the configuration (and if possible the database)
 * @todo document
 * @file
 * @ingroup Testing
 */

/**
 * @ingroup Testing
 */
class ParserTest {
	/**
	 * @var bool $color whereas output should be colorized
	 */
	private $color;

	/**
	 * @var bool $showOutput Show test output
	 */
	private $showOutput;

	/**
	 * @var bool $useTemporaryTables Use temporary tables for the temporary database
	 */
	private $useTemporaryTables = true;

	/**
	 * @var bool $databaseSetupDone True if the database has been set up
	 */
	private $databaseSetupDone = false;

	/**
	 * Our connection to the database
	 * @var DatabaseBase
	 */
	private $db;

	/**
	 * Database clone helper
	 * @var CloneDatabase
	 */
	private $dbClone;

	/**
	 * @var string $oldTablePrefix Original table prefix
	 */
	private $oldTablePrefix;

	private $maxFuzzTestLength = 300;
	private $fuzzSeed = 0;
	private $memoryLimit = 50;
	private $uploadDir = null;

	public $regex = "";
	private $savedGlobals = array();

	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 */
	public function __construct( $options = array() ) {
		# Only colorize output if stdout is a terminal.
		$this->color = !wfIsWindows() && Maintenance::posix_isatty( 1 );

		if ( isset( $options['color'] ) ) {
			switch ( $options['color'] ) {
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

		if ( isset( $options['filter'] ) ) {
			$options['regex'] = $options['filter'];
		}

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

		$this->setupRecorder( $options );
		$this->keepUploads = isset( $options['keep-uploads'] );

		if ( isset( $options['seed'] ) ) {
			$this->fuzzSeed = intval( $options['seed'] ) - 1;
		}

		$this->runDisabled = isset( $options['run-disabled'] );
		$this->runParsoid = isset( $options['run-parsoid'] );

		$this->hooks = array();
		$this->functionHooks = array();
		self::setUp();
	}

	static function setUp() {
		global $wgParser, $wgParserConf, $IP, $messageMemc, $wgMemc,
			$wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory, $wgEnableParserCache,
			$wgExtraNamespaces, $wgNamespaceAliases, $wgNamespaceProtection, $wgLocalFileRepo,
			$parserMemc, $wgThumbnailScriptPath, $wgScriptPath,
			$wgArticlePath, $wgScript, $wgStylePath, $wgExtensionAssetsPath,
			$wgMainCacheType, $wgMessageCacheType, $wgParserCacheType, $wgLockManagers;

		$wgScript = '/index.php';
		$wgScriptPath = '/';
		$wgArticlePath = '/wiki/$1';
		$wgStylePath = '/skins';
		$wgExtensionAssetsPath = '/extensions';
		$wgThumbnailScriptPath = false;
		$wgLockManagers = array( array(
			'name' => 'fsLockManager',
			'class' => 'FSLockManager',
			'lockDirectory' => wfTempDir() . '/test-repo/lockdir',
		), array(
			'name' => 'nullLockManager',
			'class' => 'NullLockManager',
		) );
		$wgLocalFileRepo = array(
			'class' => 'LocalRepo',
			'name' => 'local',
			'url' => 'http://example.com/images',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( array(
				'name' => 'local-backend',
				'lockManager' => 'fsLockManager',
				'containerPaths' => array(
					'local-public' => wfTempDir() . '/test-repo/public',
					'local-thumb' => wfTempDir() . '/test-repo/thumb',
					'local-temp' => wfTempDir() . '/test-repo/temp',
					'local-deleted' => wfTempDir() . '/test-repo/deleted',
				)
			) )
		);
		$wgNamespaceProtection[NS_MEDIAWIKI] = 'editinterface';
		$wgNamespaceAliases['Image'] = NS_FILE;
		$wgNamespaceAliases['Image_talk'] = NS_FILE_TALK;
		# add a namespace shadowing a interwiki link, to test
		# proper precedence when resolving links. (bug 51680)
		$wgExtraNamespaces[100] = 'MemoryAlpha';

		// XXX: tests won't run without this (for CACHE_DB)
		if ( $wgMainCacheType === CACHE_DB ) {
			$wgMainCacheType = CACHE_NONE;
		}
		if ( $wgMessageCacheType === CACHE_DB ) {
			$wgMessageCacheType = CACHE_NONE;
		}
		if ( $wgParserCacheType === CACHE_DB ) {
			$wgParserCacheType = CACHE_NONE;
		}

		$wgEnableParserCache = false;
		DeferredUpdates::clearPendingUpdates();
		$wgMemc = wfGetMainCache(); // checks $wgMainCacheType
		$messageMemc = wfGetMessageCacheStorage();
		$parserMemc = wfGetParserCacheStorage();

		// $wgContLang = new StubContLang;
		$wgUser = new User;
		$context = new RequestContext;
		$wgLang = $context->getLanguage();
		$wgOut = $context->getOutput();
		$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], array( $wgParserConf ) );
		$wgRequest = $context->getRequest();

		if ( $wgStyleDirectory === false ) {
			$wgStyleDirectory = "$IP/skins";
		}

		self::setupInterwikis();
	}

	/**
	 * Insert hardcoded interwiki in the lookup table.
	 *
	 * This function insert a set of well known interwikis that are used in
	 * the parser tests. They can be considered has fixtures are injected in
	 * the interwiki cache by using the 'InterwikiLoadPrefix' hook.
	 * Since we are not interested in looking up interwikis in the database,
	 * the hook completely replace the existing mechanism (hook returns false).
	 */
	public static function setupInterwikis() {
		# Hack: insert a few Wikipedia in-project interwiki prefixes,
		# for testing inter-language links
		Hooks::register( 'InterwikiLoadPrefix', function ( $prefix, &$iwData ) {
			static $testInterwikis = array(
				'wikipedia' => array(
					'iw_url' => 'http://en.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ),
				'meatball' => array(
					'iw_url' => 'http://www.usemod.com/cgi-bin/mb.pl?$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ),
				'memoryalpha' => array(
					'iw_url' => 'http://www.memory-alpha.org/en/index.php/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ),
				'zh' => array(
					'iw_url' => 'http://zh.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ),
				'es' => array(
					'iw_url' => 'http://es.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ),
				'fr' => array(
					'iw_url' => 'http://fr.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ),
				'ru' => array(
					'iw_url' => 'http://ru.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ),
			);
			if ( array_key_exists( $prefix, $testInterwikis ) ) {
				$iwData = $testInterwikis[$prefix];
			}

			// We only want to rely on the above fixtures
			return false;
		} );// hooks::register
	}

	/**
	 * Remove the hardcoded interwiki lookup table.
	 */
	public static function tearDownInterwikis() {
		Hooks::clear( 'InterwikiLoadPrefix' );
	}

	public function setupRecorder( $options ) {
		if ( isset( $options['record'] ) ) {
			$this->recorder = new DbTestRecorder( $this );
			$this->recorder->version = isset( $options['setversion'] ) ?
				$options['setversion'] : SpecialVersion::getVersion();
		} elseif ( isset( $options['compare'] ) ) {
			$this->recorder = new DbTestPreviewer( $this );
		} else {
			$this->recorder = new TestRecorder( $this );
		}
	}

	/**
	 * Remove last character if it is a newline
	 * @group utility
	 */
	public static function chomp( $s ) {
		if ( substr( $s, -1 ) === "\n" ) {
			return substr( $s, 0, -1 );
		} else {
			return $s;
		}
	}

	/**
	 * Run a fuzz test series
	 * Draw input from a set of test files
	 */
	function fuzzTest( $filenames ) {
		$GLOBALS['wgContLang'] = Language::factory( 'en' );
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
				printf( "string(%d) \"%s\"\n\n", strlen( $input ), $input );
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
		$ok = false;

		// be sure, ParserTest::addArticle has correct language set,
		// so that system messages gets into the right language cache
		$GLOBALS['wgLanguageCode'] = 'en';
		$GLOBALS['wgContLang'] = Language::factory( 'en' );

		$this->recorder->start();
		try {
			$this->setupDatabase();
			$ok = true;

			foreach ( $filenames as $filename ) {
				$tests = new TestFileIterator( $filename, $this );
				$ok = $this->runTests( $tests ) && $ok;
			}

			$this->teardownDatabase();
			$this->recorder->report();
		} catch ( DBError $e ) {
			echo $e->getMessage();
		}
		$this->recorder->end();

		return $ok;
	}

	function runTests( $tests ) {
		$ok = true;

		foreach ( $tests as $t ) {
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
	 *
	 * @param string $preprocessor
	 * @return Parser
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
		$context = $this->setupGlobals( $opts, $config );

		$user = $context->getUser();
		$options = ParserOptions::newFromContext( $context );

		if ( isset( $opts['title'] ) ) {
			$titleText = $opts['title'];
		} else {
			$titleText = 'Parser test';
		}

		$local = isset( $opts['local'] );
		$preprocessor = isset( $opts['preprocessor'] ) ? $opts['preprocessor'] : null;
		$parser = $this->getParser( $preprocessor );
		$title = Title::newFromText( $titleText );

		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $input, $title, $user, $options );
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $input, $options, $title );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $input, $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $input, $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$out = Linker::formatComment( $input, $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getPreloadText( $input, $title, $options );
		} else {
			$output = $parser->parse( $input, $title, $options, true, true, 1337 );
			$output->setTOCEnabled( !isset( $opts['notoc'] ) );
			$out = $output->getText();

			if ( isset( $opts['showtitle'] ) ) {
				if ( $output->getTitleText() ) {
					$title = $output->getTitleText();
				}

				$out = "$title\n$out";
			}

			if ( isset( $opts['ill'] ) ) {
				$out = $this->tidy( implode( ' ', $output->getLanguageLinks() ) );
			} elseif ( isset( $opts['cat'] ) ) {
				$outputPage = $context->getOutput();
				$outputPage->addCategoryLinks( $output->getCategories() );
				$cats = $outputPage->getCategoryLinks();

				if ( isset( $cats['normal'] ) ) {
					$out = $this->tidy( implode( ' ', $cats['normal'] ) );
				} else {
					$out = '';
				}
			}

			$result = $this->tidy( $result );
		}

		$this->teardownGlobals();

		$testResult = new ParserTestResult( $desc );
		$testResult->expected = $result;
		$testResult->actual = $out;

		return $this->showTestResult( $testResult );
	}

	/**
	 * Refactored in 1.22 to use ParserTestResult
	 */
	function showTestResult( ParserTestResult $testResult ) {
		if ( $testResult->isSuccess() ) {
			$this->showSuccess( $testResult );
			return true;
		} else {
			$this->showFailure( $testResult );
			return false;
		}
	}

	/**
	 * Use a regex to find out the value of an option
	 * @param $key String: name of option val to retrieve
	 * @param $opts array: Options array to look in
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
				array_shift( $bits );
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
			'wgServer' => 'http://example.org',
			'wgScript' => '/index.php',
			'wgScriptPath' => '/',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => array(),
			'wgLockManagers' => array( array(
				'name' => 'fsLockManager',
				'class' => 'FSLockManager',
				'lockDirectory' => $this->uploadDir . '/lockdir',
			), array(
				'name' => 'nullLockManager',
				'class' => 'NullLockManager',
			) ),
			'wgLocalFileRepo' => array(
				'class' => 'LocalRepo',
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => new FSFileBackend( array(
					'name' => 'local-backend',
					'lockManager' => 'fsLockManager',
					'containerPaths' => array(
						'local-public' => $this->uploadDir,
						'local-thumb' => $this->uploadDir . '/thumb',
						'local-temp' => $this->uploadDir . '/temp',
						'local-deleted' => $this->uploadDir . '/delete',
					)
				) )
			),
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgStylePath' => '/skins',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => $lang,
			'wgDBprefix' => $this->db->getType() != 'oracle' ? 'parsertest_' : 'pt_',
			'wgRawHtml' => self::getOptionValue( 'wgRawHtml', $opts, false ),
			'wgLang' => null,
			'wgContLang' => null,
			'wgNamespacesWithSubpages' => array( 0 => isset( $opts['subpage'] ) ),
			'wgMaxTocLevel' => $maxtoclevel,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => array(),
			'wgThumbnailScriptPath' => false,
			'wgUseImageResize' => true,
			'wgSVGConverter' => 'null',
			'wgSVGConverters' => array( 'null' => 'echo "1">$output' ),
			'wgLocaltimezone' => 'UTC',
			'wgAllowExternalImages' => self::getOptionValue( 'wgAllowExternalImages', $opts, true ),
			'wgUseTidy' => false,
			'wgDefaultLanguageVariant' => $variant,
			'wgVariantArticlePath' => false,
			'wgGroupPermissions' => array( '*' => array(
				'createaccount' => true,
				'read' => true,
				'edit' => true,
				'createpage' => true,
				'createtalk' => true,
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
			'wgAdaptiveMessageCache' => true,
			'wgDisableLangConversion' => false,
			'wgDisableTitleConversion' => false,
		);

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );

				$settings[$var] = eval( "return $value;" );
			}
		}

		$this->savedGlobals = array();

		/** @since 1.20 */
		wfRunHooks( 'ParserTestGlobals', array( &$settings ) );

		foreach ( $settings as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}

			$GLOBALS[$var] = $val;
		}

		$GLOBALS['wgContLang'] = Language::factory( $lang );
		$GLOBALS['wgMemc'] = new EmptyBagOStuff;

		$context = new RequestContext();
		$GLOBALS['wgLang'] = $context->getLanguage();
		$GLOBALS['wgOut'] = $context->getOutput();

		$GLOBALS['wgUser'] = new User();

		global $wgHooks;

		$wgHooks['ParserTestParser'][] = 'ParserTestParserHook::setup';
		$wgHooks['ParserGetVariableValueTs'][] = 'ParserTest::getFakeTimestamp';

		MagicWord::clearCache();

		return $context;
	}

	/**
	 * List of temporary tables to create, without prefix.
	 * Some of these probably aren't necessary.
	 */
	private function listTables() {
		$tables = array( 'user', 'user_properties', 'user_former_groups', 'page', 'page_restrictions',
			'protected_titles', 'revision', 'text', 'pagelinks', 'imagelinks',
			'categorylinks', 'templatelinks', 'externallinks', 'langlinks', 'iwlinks',
			'site_stats', 'hitcounter', 'ipblocks', 'image', 'oldimage',
			'recentchanges', 'watchlist', 'interwiki', 'logging',
			'querycache', 'objectcache', 'job', 'l10n_cache', 'redirect', 'querycachetwo',
			'archive', 'user_groups', 'page_props', 'category', 'msg_resource', 'msg_resource_links'
		);

		if ( in_array( $this->db->getType(), array( 'mysql', 'sqlite', 'oracle' ) ) ) {
			array_push( $tables, 'searchindex' );
		}

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
		global $wgDBprefix;

		if ( $this->databaseSetupDone ) {
			return;
		}

		$this->db = wfGetDB( DB_MASTER );
		$dbType = $this->db->getType();

		if ( $wgDBprefix === 'parsertest_' || ( $dbType == 'oracle' && $wgDBprefix === 'pt_' ) ) {
			throw new MWException( 'setupDatabase should be called before setupGlobals' );
		}

		$this->databaseSetupDone = true;
		$this->oldTablePrefix = $wgDBprefix;

		# SqlBagOStuff broke when using temporary tables on r40209 (bug 15892).
		# It seems to have been fixed since (r55079?), but regressed at some point before r85701.
		# This works around it for now...
		ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;

		# CREATE TEMPORARY TABLE breaks if there is more than one server
		if ( wfGetLB()->getServerCount() != 1 ) {
			$this->useTemporaryTables = false;
		}

		$temporary = $this->useTemporaryTables || $dbType == 'postgres';
		$prefix = $dbType != 'oracle' ? 'parsertest_' : 'pt_';

		$this->dbClone = new CloneDatabase( $this->db, $this->listTables(), $prefix );
		$this->dbClone->useTemporaryTables( $temporary );
		$this->dbClone->cloneTableStructure();

		if ( $dbType == 'oracle' ) {
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );
			# Insert 0 user to prevent FK violations

			# Anonymous user
			$this->db->insert( 'user', array(
				'user_id' => 0,
				'user_name' => 'Anonymous' ) );
		}

		# Update certain things in site_stats
		$this->db->insert( 'site_stats',
			array( 'ss_row_id' => 1, 'ss_images' => 2, 'ss_good_articles' => 1 ) );

		# Reinitialise the LocalisationCache to match the database state
		Language::getLocalisationCache()->unloadAll();

		# Clear the message cache
		MessageCache::singleton()->clear();

		// Remember to update newParserTests.php after changing the below
		// (and it uses a slightly different syntax just for teh lulz)
		$this->uploadDir = $this->setupUploadDir();
		$user = User::createNew( 'WikiSysop' );
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.jpg' ) );
		# note that the size/width/height/bits/etc of the file
		# are actually set by inspecting the file itself; the arguments
		# to recordUpload2 have no effect.  That said, we try to make things
		# match up so it is less confusing to readers of the code & tests.
		$image->recordUpload2( '', 'Upload of some lame file', 'Some lame file', array(
			'size' => 7881,
			'width' => 1941,
			'height' => 220,
			'bits' => 8,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/jpeg',
			'metadata' => serialize( array() ),
			'sha1' => wfBaseConvert( '1', 16, 36, 31 ),
			'fileExists' => true
		), $this->db->timestamp( '20010115123500' ), $user );

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Thumb.png' ) );
		# again, note that size/width/height below are ignored; see above.
		$image->recordUpload2( '', 'Upload of some lame thumbnail', 'Some lame thumbnail', array(
			'size' => 22589,
			'width' => 135,
			'height' => 135,
			'bits' => 8,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/png',
			'metadata' => serialize( array() ),
			'sha1' => wfBaseConvert( '2', 16, 36, 31 ),
			'fileExists' => true
		), $this->db->timestamp( '20130225203040' ), $user );

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.svg' ) );
		$image->recordUpload2( '', 'Upload of some lame SVG', 'Some lame SVG', array(
				'size'        => 12345,
				'width'       => 240,
				'height'      => 180,
				'bits'        => 24,
				'media_type'  => MEDIATYPE_DRAWING,
				'mime'        => 'image/svg+xml',
				'metadata'    => serialize( array() ),
				'sha1'        => wfBaseConvert( '', 16, 36, 31 ),
				'fileExists'  => true
		), $this->db->timestamp( '20010115123500' ), $user );

		# This image will be blacklisted in [[MediaWiki:Bad image list]]
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Bad.jpg' ) );
		$image->recordUpload2( '', 'zomgnotcensored', 'Borderline image', array(
			'size' => 12345,
			'width' => 320,
			'height' => 240,
			'bits' => 24,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/jpeg',
			'metadata' => serialize( array() ),
			'sha1' => wfBaseConvert( '3', 16, 36, 31 ),
			'fileExists' => true
		), $this->db->timestamp( '20010115123500' ), $user );
	}

	public function teardownDatabase() {
		if ( !$this->databaseSetupDone ) {
			$this->teardownGlobals();
			return;
		}
		$this->teardownUploadDir( $this->uploadDir );

		$this->dbClone->destroy();
		$this->databaseSetupDone = false;

		if ( $this->useTemporaryTables ) {
			if ( $this->db->getType() == 'sqlite' ) {
				# Under SQLite the searchindex table is virtual and need
				# to be explicitly destroyed. See bug 29912
				# See also MediaWikiTestCase::destroyDB()
				wfDebug( __METHOD__ . " explicitly destroying sqlite virtual table parsertest_searchindex\n" );
				$this->db->query( "DROP TABLE `parsertest_searchindex`" );
			}
			# Don't need to do anything
			$this->teardownGlobals();
			return;
		}

		$tables = $this->listTables();

		foreach ( $tables as $table ) {
			if ( $this->db->getType() == 'oracle' ) {
				$this->db->query( "DROP TABLE pt_$table DROP CONSTRAINTS" );
			} else {
				$this->db->query( "DROP TABLE `parsertest_$table`" );
			}
		}

		if ( $this->db->getType() == 'oracle' ) {
			$this->db->query( 'BEGIN FILL_WIKI_INFO; END;' );
		}

		$this->teardownGlobals();
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

		// wfDebug( "Creating upload directory $dir\n" );
		if ( file_exists( $dir ) ) {
			wfDebug( "Already exists!\n" );
			return $dir;
		}

		wfMkdirParents( $dir . '/3/3a', null, __METHOD__ );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/3/3a/Foobar.jpg" );
		wfMkdirParents( $dir . '/e/ea', null, __METHOD__ );
		copy( "$IP/skins/monobook/wiki.png", "$dir/e/ea/Thumb.png" );
		wfMkdirParents( $dir . '/0/09', null, __METHOD__ );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/0/09/Bad.jpg" );
		wfMkdirParents( $dir . '/f/ff', null, __METHOD__ );
		copy( "$IP/skins/monobook/headbg.jpg", "$dir/f/ff/Foobar.svg" );
		file_put_contents( "$dir/f/ff/Foobar.svg",
			'<?xml version="1.0" encoding="utf-8"?>' .
			'<svg xmlns="http://www.w3.org/2000/svg" />' );
		return $dir;
	}

	/**
	 * Restore default values and perform any necessary clean-up
	 * after each test runs.
	 */
	private function teardownGlobals() {
		RepoGroup::destroySingleton();
		FileBackendGroup::destroySingleton();
		LockManagerGroup::destroySingletons();
		LinkCache::singleton()->clear();

		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
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
			array(
				"$dir/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/180px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/200px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/640px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/120px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/1280px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/20px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/270px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/300px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/30px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/360px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/400px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/40px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/70px-Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/960px-Foobar.jpg",

				"$dir/e/ea/Thumb.png",

				"$dir/0/09/Bad.jpg",

				"$dir/f/ff/Foobar.svg",
				"$dir/thumb/f/ff/Foobar.svg/180px-Foobar.svg.png",
				"$dir/thumb/f/ff/Foobar.svg/360px-Foobar.svg.png",
				"$dir/thumb/f/ff/Foobar.svg/langde-270px-Foobar.svg.png",
				"$dir/thumb/f/ff/Foobar.svg/270px-Foobar.svg.png",
				"$dir/thumb/f/ff/Foobar.svg/langde-180px-Foobar.svg.png",
				"$dir/thumb/f/ff/Foobar.svg/langde-360px-Foobar.svg.png",

				"$dir/math/f/a/5/fa50b8b616463173474302ca3e63586b.png",
			)
		);

		self::deleteDirs(
			array(
				"$dir/3/3a",
				"$dir/3",
				"$dir/thumb/6/65",
				"$dir/thumb/6",
				"$dir/thumb/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a",
				"$dir/thumb/3",
				"$dir/e/ea",
				"$dir/e",
				"$dir/f/ff/",
				"$dir/f/",
				"$dir/thumb/f/ff/Foobar.svg",
				"$dir/thumb/f/ff/",
				"$dir/thumb/f/",
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
	 * Refactored in 1.22 to use ParserTestResult
	 *
	 * @param $testResult ParserTestResult
	 * @return Boolean
	 */
	protected function showSuccess( ParserTestResult $testResult ) {
		if ( $this->showProgress ) {
			print $this->term->color( '1;32' ) . 'PASSED' . $this->term->reset() . "\n";
		}

		return true;
	}

	/**
	 * Print a failure message and provide some explanatory output
	 * about what went wrong if so configured.
	 *
	 * Refactored in 1.22 to use ParserTestResult
	 *
	 * @param $testResult ParserTestResult
	 * @return Boolean
	 */
	protected function showFailure( ParserTestResult $testResult ) {
		if ( $this->showFailure ) {
			if ( !$this->showProgress ) {
				# In quiet mode we didn't show the 'Testing' message before the
				# test, in case it succeeded. Show it now:
				$this->showTesting( $testResult->description );
			}

			print $this->term->color( '31' ) . 'FAILED!' . $this->term->reset() . "\n";

			if ( $this->showOutput ) {
				print "--- Expected ---\n{$testResult->expected}\n";
				print "--- Actual ---\n{$testResult->actual}\n";
			}

			if ( $this->showDiffs ) {
				print $this->quickDiff( $testResult->expected, $testResult->actual );
				if ( !$this->wellFormed( $testResult->actual ) ) {
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
	protected function quickDiff( $input, $output,
		$inFileTail = 'expected', $outFileTail = 'actual'
	) {
		# Windows, or at least the fc utility, is retarded
		$slash = wfIsWindows() ? '\\' : '/';
		$prefix = wfTempDir() . "{$slash}mwParser-" . mt_rand();

		$infile = "$prefix-$inFileTail";
		$this->dumpToFile( $input, $infile );

		$outfile = "$prefix-$outFileTail";
		$this->dumpToFile( $output, $outfile );

		$shellInfile = wfEscapeShellArg( $infile );
		$shellOutfile = wfEscapeShellArg( $outfile );

		global $wgDiff3;
		// we assume that people with diff3 also have usual diff
		$shellCommand = ( wfIsWindows() && !$wgDiff3 ) ? 'fc' : 'diff -au';

		$diff = wfShellExec( "$shellCommand $shellInfile $shellOutfile" );

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
	 * @param $ignoreDuplicate Boolean: whether to silently ignore duplicate pages
	 */
	public static function addArticle( $name, $text, $line = 'unknown', $ignoreDuplicate = '' ) {
		global $wgCapitalLinks;

		$oldCapitalLinks = $wgCapitalLinks;
		$wgCapitalLinks = true; // We only need this from SetupGlobals() See r70917#c8637

		$text = self::chomp( $text );
		$name = self::chomp( $name );

		$title = Title::newFromText( $name );

		if ( is_null( $title ) ) {
			throw new MWException( "invalid title '$name' at line $line\n" );
		}

		$page = WikiPage::factory( $title );
		$page->loadPageData( 'fromdbmaster' );

		if ( $page->exists() ) {
			if ( $ignoreDuplicate == 'ignoreduplicate' ) {
				return;
			} else {
				throw new MWException( "duplicate article '$name' at line $line\n" );
			}
		}

		$page->doEditContent( ContentHandler::makeContent( $text, $title ), '', EDIT_NEW );

		$wgCapitalLinks = $oldCapitalLinks;
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

		$wgParser->firstCallInit(); // make sure hooks are loaded.

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

		$wgParser->firstCallInit(); // make sure hooks are loaded.

		if ( isset( $wgParser->mFunctionHooks[$name] ) ) {
			$this->functionHooks[$name] = $wgParser->mFunctionHooks[$name];
		} else {
			echo "   This test suite requires the '$name' function hook extension, skipping.\n";
			return false;
		}

		return true;
	}

	/**
	 * Run the "tidy" command on text if the $wgUseTidy
	 * global is true
	 *
	 * @param $text String: the text to tidy
	 * @return String
	 */
	private function tidy( $text ) {
		global $wgUseTidy;

		if ( $wgUseTidy ) {
			$text = MWTidy::tidy( $text );
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
		$ts = 123; //parsed as '1970-01-01T00:02:03Z'
		return true;
	}
}
