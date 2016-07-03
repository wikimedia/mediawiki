<?php
/**
 * Helper code for the MediaWiki parser test suite. Some code is duplicated
 * in PHPUnit's NewParserTests.php, so you'll probably want to update both
 * at the same time.
 *
 * Copyright © 2004, 2010 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
	 * @var DjVuSupport
	 */
	private $djVuSupport;

	/**
	 * @var TidySupport
	 */
	private $tidySupport;

	private $maxFuzzTestLength = 300;
	private $fuzzSeed = 0;
	private $memoryLimit = 50;
	private $uploadDir = null;

	public $regex = "";
	private $savedGlobals = [];

	/**
	 * Sets terminal colorization and diff/quick modes depending on OS and
	 * command-line options (--color and --quick).
	 * @param array $options
	 */
	public function __construct( $options = [] ) {
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

		if ( $this->keepUploads ) {
			$this->uploadDir = wfTempDir() . '/mwParser-images';
		} else {
			$this->uploadDir = wfTempDir() . "/mwParser-" . mt_rand() . "-images";
		}

		if ( isset( $options['seed'] ) ) {
			$this->fuzzSeed = intval( $options['seed'] ) - 1;
		}

		$this->runDisabled = isset( $options['run-disabled'] );
		$this->runParsoid = isset( $options['run-parsoid'] );

		$this->djVuSupport = new DjVuSupport();
		$this->tidySupport = new TidySupport();
		if ( !$this->tidySupport->isEnabled() ) {
			echo "Warning: tidy is not installed, skipping some tests\n";
		}

		if ( !extension_loaded( 'gd' ) ) {
			echo "Warning: GD extension is not present, thumbnailing tests will probably fail\n";
		}

		$this->hooks = [];
		$this->functionHooks = [];
		$this->transparentHooks = [];
		$this->setUp();
	}

	function setUp() {
		global $wgParser, $wgParserConf, $IP, $messageMemc, $wgMemc,
			$wgUser, $wgLang, $wgOut, $wgRequest, $wgStyleDirectory,
			$wgExtraNamespaces, $wgNamespaceAliases, $wgNamespaceProtection, $wgLocalFileRepo,
			$wgExtraInterlanguageLinkPrefixes, $wgLocalInterwikis,
			$parserMemc, $wgThumbnailScriptPath, $wgScriptPath, $wgResourceBasePath,
			$wgArticlePath, $wgScript, $wgStylePath, $wgExtensionAssetsPath,
			$wgMainCacheType, $wgMessageCacheType, $wgParserCacheType, $wgLockManagers;

		$wgScriptPath = '';
		$wgScript = '/index.php';
		$wgStylePath = '/skins';
		$wgResourceBasePath = '';
		$wgExtensionAssetsPath = '/extensions';
		$wgArticlePath = '/wiki/$1';
		$wgThumbnailScriptPath = false;
		$wgLockManagers = [ [
			'name' => 'fsLockManager',
			'class' => 'FSLockManager',
			'lockDirectory' => $this->uploadDir . '/lockdir',
		], [
			'name' => 'nullLockManager',
			'class' => 'NullLockManager',
		] ];
		$wgLocalFileRepo = [
			'class' => 'LocalRepo',
			'name' => 'local',
			'url' => 'http://example.com/images',
			'hashLevels' => 2,
			'transformVia404' => false,
			'backend' => new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID(),
				'containerPaths' => [
					'local-public' => $this->uploadDir . '/public',
					'local-thumb' => $this->uploadDir . '/thumb',
					'local-temp' => $this->uploadDir . '/temp',
					'local-deleted' => $this->uploadDir . '/deleted',
				]
			] )
		];
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

		DeferredUpdates::clearPendingUpdates();
		$wgMemc = wfGetMainCache(); // checks $wgMainCacheType
		$messageMemc = wfGetMessageCacheStorage();
		$parserMemc = wfGetParserCacheStorage();

		RequestContext::resetMain();
		$context = new RequestContext;
		$wgUser = new User;
		$wgLang = $context->getLanguage();
		$wgOut = $context->getOutput();
		$wgRequest = $context->getRequest();
		$wgParser = new StubObject( 'wgParser', $wgParserConf['class'], [ $wgParserConf ] );

		if ( $wgStyleDirectory === false ) {
			$wgStyleDirectory = "$IP/skins";
		}

		self::setupInterwikis();
		$wgLocalInterwikis = [ 'local', 'mi' ];
		// "extra language links"
		// see https://gerrit.wikimedia.org/r/111390
		array_push( $wgExtraInterlanguageLinkPrefixes, 'mul' );

		// Reset namespace cache
		MWNamespace::getCanonicalNamespaces( true );
		Language::factory( 'en' )->resetNamespaces();
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
			static $testInterwikis = [
				'local' => [
					'iw_url' => 'http://doesnt.matter.org/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ],
				'wikipedia' => [
					'iw_url' => 'http://en.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ],
				'meatball' => [
					'iw_url' => 'http://www.usemod.com/cgi-bin/mb.pl?$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ],
				'memoryalpha' => [
					'iw_url' => 'http://www.memory-alpha.org/en/index.php/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0 ],
				'zh' => [
					'iw_url' => 'http://zh.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
				'es' => [
					'iw_url' => 'http://es.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
				'fr' => [
					'iw_url' => 'http://fr.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
				'ru' => [
					'iw_url' => 'http://ru.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
				'mi' => [
					'iw_url' => 'http://mi.wikipedia.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
				'mul' => [
					'iw_url' => 'http://wikisource.org/wiki/$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 1 ],
			];
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
	 * @param string $s
	 * @return string
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
	 * @param array $filenames
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
	 * @param array $filenames
	 * @return string
	 */
	function getFuzzInput( $filenames ) {
		$dict = '';

		foreach ( $filenames as $filename ) {
			$contents = file_get_contents( $filename );
			preg_match_all(
				'/!!\s*(input|wikitext)\n(.*?)\n!!\s*(result|html|html\/\*|html\/php)/s',
				$contents,
				$matches
			);

			foreach ( $matches[1] as $match ) {
				$dict .= $match . "\n";
			}
		}

		return $dict;
	}

	/**
	 * Get a memory usage breakdown
	 * @return array
	 */
	function getMemoryBreakdown() {
		$memStats = [];

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
	 * @param array $filenames Array of strings
	 * @return bool True if passed all tests, false if any tests failed.
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
				echo "Running parser tests from: $filename\n";
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
			$this->recorder->record( $t['test'], $t['subtest'], $result );
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
		$parser = new $class( [ 'preprocessorClass' => $preprocessor ] + $wgParserConf );

		foreach ( $this->hooks as $tag => $callback ) {
			$parser->setHook( $tag, $callback );
		}

		foreach ( $this->functionHooks as $tag => $bits ) {
			list( $callback, $flags ) = $bits;
			$parser->setFunctionHook( $tag, $callback, $flags );
		}

		foreach ( $this->transparentHooks as $tag => $callback ) {
			$parser->setTransparentTagHook( $tag, $callback );
		}

		Hooks::run( 'ParserTestParser', [ &$parser ] );

		return $parser;
	}

	/**
	 * Run a given wikitext input through a freshly-constructed wiki parser,
	 * and compare the output against the expected results.
	 * Prints status and explanatory messages to stdout.
	 *
	 * @param string $desc Test's description
	 * @param string $input Wikitext to try rendering
	 * @param string $result Result to output
	 * @param array $opts Test's options
	 * @param string $config Overrides for global variables, one per line
	 * @return bool
	 */
	public function runTest( $desc, $input, $result, $opts, $config ) {
		if ( $this->showProgress ) {
			$this->showTesting( $desc );
		}

		$opts = $this->parseOptions( $opts );
		$context = $this->setupGlobals( $opts, $config );

		$user = $context->getUser();
		$options = ParserOptions::newFromContext( $context );

		if ( isset( $opts['djvu'] ) ) {
			if ( !$this->djVuSupport->isEnabled() ) {
				return $this->showSkipped();
			}
		}

		if ( isset( $opts['tidy'] ) ) {
			if ( !$this->tidySupport->isEnabled() ) {
				return $this->showSkipped();
			} else {
				$options->setTidy( true );
			}
		}

		if ( isset( $opts['title'] ) ) {
			$titleText = $opts['title'];
		} else {
			$titleText = 'Parser test';
		}

		ObjectCache::getMainWANInstance()->clearProcessCache();
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
			if ( isset( $opts['tidy'] ) ) {
				$out = preg_replace( '/\s+$/', '', $out );
			}

			if ( isset( $opts['showtitle'] ) ) {
				if ( $output->getTitleText() ) {
					$title = $output->getTitleText();
				}

				$out = "$title\n$out";
			}

			if ( isset( $opts['showindicators'] ) ) {
				$indicators = '';
				foreach ( $output->getIndicators() as $id => $content ) {
					$indicators .= "$id=$content\n";
				}
				$out = $indicators . $out;
			}

			if ( isset( $opts['ill'] ) ) {
				$out = implode( ' ', $output->getLanguageLinks() );
			} elseif ( isset( $opts['cat'] ) ) {
				$outputPage = $context->getOutput();
				$outputPage->addCategoryLinks( $output->getCategories() );
				$cats = $outputPage->getCategoryLinks();

				if ( isset( $cats['normal'] ) ) {
					$out = implode( ' ', $cats['normal'] );
				} else {
					$out = '';
				}
			}
		}

		$this->teardownGlobals();

		$testResult = new ParserTestResult( $desc );
		$testResult->expected = $result;
		$testResult->actual = $out;

		return $this->showTestResult( $testResult );
	}

	/**
	 * Refactored in 1.22 to use ParserTestResult
	 * @param ParserTestResult $testResult
	 * @return bool
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
	 * @param string $key Name of option val to retrieve
	 * @param array $opts Options array to look in
	 * @param mixed $default Default value returned if not found
	 * @return mixed
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
		$opts = [];
		// foo
		// foo=bar
		// foo="bar baz"
		// foo=[[bar baz]]
		// foo=bar,"baz quux"
		// foo={...json...}
		$defs = '(?(DEFINE)
			(?<qstr>					# Quoted string
				"
				(?:[^\\\\"] | \\\\.)*
				"
			)
			(?<json>
				\{		# Open bracket
				(?:
					[^"{}] |				# Not a quoted string or object, or
					(?&qstr) |				# A quoted string, or
					(?&json)				# A json object (recursively)
				)*
				\}		# Close bracket
			)
			(?<value>
				(?:
					(?&qstr)			# Quoted val
				|
					\[\[
						[^]]*			# Link target
					\]\]
				|
					[\w-]+				# Plain word
				|
					(?&json)			# JSON object
				)
			)
		)';
		$regex = '/' . $defs . '\b
			(?<k>[\w-]+)				# Key
			\b
			(?:\s*
				=						# First sub-value
				\s*
				(?<v>
					(?&value)
					(?:\s*
						,				# Sub-vals 1..N
						\s*
						(?&value)
					)*
				)
			)?
			/x';
		$valueregex = '/' . $defs . '(?&value)/x';

		if ( preg_match_all( $regex, $instring, $matches, PREG_SET_ORDER ) ) {
			foreach ( $matches as $bits ) {
				$key = strtolower( $bits['k'] );
				if ( !isset( $bits['v'] ) ) {
					$opts[$key] = true;
				} else {
					preg_match_all( $valueregex, $bits['v'], $vmatches );
					$opts[$key] = array_map( [ $this, 'cleanupOption' ], $vmatches[0] );
					if ( count( $opts[$key] ) == 1 ) {
						$opts[$key] = $opts[$key][0];
					}
				}
			}
		}
		return $opts;
	}

	private function cleanupOption( $opt ) {
		if ( substr( $opt, 0, 1 ) == '"' ) {
			return stripcslashes( substr( $opt, 1, -1 ) );
		}

		if ( substr( $opt, 0, 2 ) == '[[' ) {
			return substr( $opt, 2, -2 );
		}

		if ( substr( $opt, 0, 1 ) == '{' ) {
			return FormatJson::decode( $opt, true );
		}
		return $opt;
	}

	/**
	 * Set up the global variables for a consistent environment for each test.
	 * Ideally this should replace the global configuration entirely.
	 * @param string $opts
	 * @param string $config
	 * @return RequestContext
	 */
	private function setupGlobals( $opts = '', $config = '' ) {
		global $IP;

		# Find out values for some special options.
		$lang =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

		$settings = [
			'wgServer' => 'http://example.org',
			'wgServerName' => 'example.org',
			'wgScript' => '/index.php',
			'wgScriptPath' => '',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => [],
			'wgLockManagers' => [ [
				'name' => 'fsLockManager',
				'class' => 'FSLockManager',
				'lockDirectory' => $this->uploadDir . '/lockdir',
			], [
				'name' => 'nullLockManager',
				'class' => 'NullLockManager',
			] ],
			'wgLocalFileRepo' => [
				'class' => 'LocalRepo',
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => new FSFileBackend( [
					'name' => 'local-backend',
					'wikiId' => wfWikiID(),
					'containerPaths' => [
						'local-public' => $this->uploadDir,
						'local-thumb' => $this->uploadDir . '/thumb',
						'local-temp' => $this->uploadDir . '/temp',
						'local-deleted' => $this->uploadDir . '/delete',
					]
				] )
			],
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgUploadNavigationUrl' => false,
			'wgStylePath' => '/skins',
			'wgSitename' => 'MediaWiki',
			'wgLanguageCode' => $lang,
			'wgDBprefix' => $this->db->getType() != 'oracle' ? 'parsertest_' : 'pt_',
			'wgRawHtml' => self::getOptionValue( 'wgRawHtml', $opts, false ),
			'wgLang' => null,
			'wgContLang' => null,
			'wgNamespacesWithSubpages' => [ 0 => isset( $opts['subpage'] ) ],
			'wgMaxTocLevel' => $maxtoclevel,
			'wgCapitalLinks' => true,
			'wgNoFollowLinks' => true,
			'wgNoFollowDomainExceptions' => [],
			'wgThumbnailScriptPath' => false,
			'wgUseImageResize' => true,
			'wgSVGConverter' => 'null',
			'wgSVGConverters' => [ 'null' => 'echo "1">$output' ],
			'wgLocaltimezone' => 'UTC',
			'wgAllowExternalImages' => self::getOptionValue( 'wgAllowExternalImages', $opts, true ),
			'wgThumbLimits' => [ self::getOptionValue( 'thumbsize', $opts, 180 ) ],
			'wgDefaultLanguageVariant' => $variant,
			'wgVariantArticlePath' => false,
			'wgGroupPermissions' => [ '*' => [
				'createaccount' => true,
				'read' => true,
				'edit' => true,
				'createpage' => true,
				'createtalk' => true,
			] ],
			'wgNamespaceProtection' => [ NS_MEDIAWIKI => 'editinterface' ],
			'wgDefaultExternalStore' => [],
			'wgForeignFileRepos' => [],
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
			'wgExperimentalHtmlIds' => false,
			'wgExternalLinkTarget' => false,
			'wgHtml5' => true,
			'wgAdaptiveMessageCache' => true,
			'wgDisableLangConversion' => false,
			'wgDisableTitleConversion' => false,
			// Tidy options.
			'wgUseTidy' => isset( $opts['tidy'] ),
			'wgTidyConfig' => null,
			'wgDebugTidy' => false,
			'wgTidyConf' => $IP . '/includes/tidy/tidy.conf',
			'wgTidyOpts' => '',
			'wgTidyInternal' => $this->tidySupport->isInternal(),
		];

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value ) = explode( '=', $line, 2 );

				$settings[$var] = eval( "return $value;" );
			}
		}

		$this->savedGlobals = [];

		/** @since 1.20 */
		Hooks::run( 'ParserTestGlobals', [ &$settings ] );

		foreach ( $settings as $var => $val ) {
			if ( array_key_exists( $var, $GLOBALS ) ) {
				$this->savedGlobals[$var] = $GLOBALS[$var];
			}

			$GLOBALS[$var] = $val;
		}

		// Must be set before $context as user language defaults to $wgContLang
		$GLOBALS['wgContLang'] = Language::factory( $lang );
		$GLOBALS['wgMemc'] = new EmptyBagOStuff;

		RequestContext::resetMain();
		$context = RequestContext::getMain();
		$GLOBALS['wgLang'] = $context->getLanguage();
		$GLOBALS['wgOut'] = $context->getOutput();
		$GLOBALS['wgUser'] = $context->getUser();

		// We (re)set $wgThumbLimits to a single-element array above.
		$context->getUser()->setOption( 'thumbsize', 0 );

		global $wgHooks;

		$wgHooks['ParserTestParser'][] = 'ParserTestParserHook::setup';
		$wgHooks['ParserGetVariableValueTs'][] = 'ParserTest::getFakeTimestamp';

		MagicWord::clearCache();
		MWTidy::destroySingleton();
		RepoGroup::destroySingleton();

		return $context;
	}

	/**
	 * List of temporary tables to create, without prefix.
	 * Some of these probably aren't necessary.
	 * @return array
	 */
	private function listTables() {
		$tables = [ 'user', 'user_properties', 'user_former_groups', 'page', 'page_restrictions',
			'protected_titles', 'revision', 'text', 'pagelinks', 'imagelinks',
			'categorylinks', 'templatelinks', 'externallinks', 'langlinks', 'iwlinks',
			'site_stats', 'ipblocks', 'image', 'oldimage',
			'recentchanges', 'watchlist', 'interwiki', 'logging', 'log_search',
			'querycache', 'objectcache', 'job', 'l10n_cache', 'redirect', 'querycachetwo',
			'archive', 'user_groups', 'page_props', 'category'
		];

		if ( in_array( $this->db->getType(), [ 'mysql', 'sqlite', 'oracle' ] ) ) {
			array_push( $tables, 'searchindex' );
		}

		// Allow extensions to add to the list of tables to duplicate;
		// may be necessary if they hook into page save or other code
		// which will require them while running tests.
		Hooks::run( 'ParserTestTables', [ &$tables ] );

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
			$this->db->insert( 'user', [
				'user_id' => 0,
				'user_name' => 'Anonymous' ] );
		}

		# Update certain things in site_stats
		$this->db->insert( 'site_stats',
			[ 'ss_row_id' => 1, 'ss_images' => 2, 'ss_good_articles' => 1 ] );

		# Reinitialise the LocalisationCache to match the database state
		Language::getLocalisationCache()->unloadAll();

		# Clear the message cache
		MessageCache::singleton()->clear();

		// Remember to update newParserTests.php after changing the below
		// (and it uses a slightly different syntax just for teh lulz)
		$this->setupUploadDir();
		$user = User::createNew( 'WikiSysop' );
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.jpg' ) );
		# note that the size/width/height/bits/etc of the file
		# are actually set by inspecting the file itself; the arguments
		# to recordUpload2 have no effect.  That said, we try to make things
		# match up so it is less confusing to readers of the code & tests.
		$image->recordUpload2( '', 'Upload of some lame file', 'Some lame file', [
			'size' => 7881,
			'width' => 1941,
			'height' => 220,
			'bits' => 8,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/jpeg',
			'metadata' => serialize( [] ),
			'sha1' => Wikimedia\base_convert( '1', 16, 36, 31 ),
			'fileExists' => true
		], $this->db->timestamp( '20010115123500' ), $user );

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Thumb.png' ) );
		# again, note that size/width/height below are ignored; see above.
		$image->recordUpload2( '', 'Upload of some lame thumbnail', 'Some lame thumbnail', [
			'size' => 22589,
			'width' => 135,
			'height' => 135,
			'bits' => 8,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/png',
			'metadata' => serialize( [] ),
			'sha1' => Wikimedia\base_convert( '2', 16, 36, 31 ),
			'fileExists' => true
		], $this->db->timestamp( '20130225203040' ), $user );

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Foobar.svg' ) );
		$image->recordUpload2( '', 'Upload of some lame SVG', 'Some lame SVG', [
				'size'        => 12345,
				'width'       => 240,
				'height'      => 180,
				'bits'        => 0,
				'media_type'  => MEDIATYPE_DRAWING,
				'mime'        => 'image/svg+xml',
				'metadata'    => serialize( [] ),
				'sha1'        => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists'  => true
		], $this->db->timestamp( '20010115123500' ), $user );

		# This image will be blacklisted in [[MediaWiki:Bad image list]]
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Bad.jpg' ) );
		$image->recordUpload2( '', 'zomgnotcensored', 'Borderline image', [
			'size' => 12345,
			'width' => 320,
			'height' => 240,
			'bits' => 24,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/jpeg',
			'metadata' => serialize( [] ),
			'sha1' => Wikimedia\base_convert( '3', 16, 36, 31 ),
			'fileExists' => true
		], $this->db->timestamp( '20010115123500' ), $user );

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Video.ogv' ) );
		$image->recordUpload2( '', 'A pretty movie', 'Will it play', [
			'size' => 12345,
			'width' => 320,
			'height' => 240,
			'bits' => 0,
			'media_type' => MEDIATYPE_VIDEO,
			'mime' => 'application/ogg',
			'metadata' => serialize( [] ),
			'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
			'fileExists' => true
		], $this->db->timestamp( '20010115123500' ), $user );

		# A DjVu file
		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'LoremIpsum.djvu' ) );
		$image->recordUpload2( '', 'Upload a DjVu', 'A DjVu', [
			'size' => 3249,
			'width' => 2480,
			'height' => 3508,
			'bits' => 0,
			'media_type' => MEDIATYPE_BITMAP,
			'mime' => 'image/vnd.djvu',
			'metadata' => '<?xml version="1.0" ?>
<!DOCTYPE DjVuXML PUBLIC "-//W3C//DTD DjVuXML 1.1//EN" "pubtext/DjVuXML-s.dtd">
<DjVuXML>
<HEAD></HEAD>
<BODY><OBJECT height="3508" width="2480">
<PARAM name="DPI" value="300" />
<PARAM name="GAMMA" value="2.2" />
</OBJECT>
<OBJECT height="3508" width="2480">
<PARAM name="DPI" value="300" />
<PARAM name="GAMMA" value="2.2" />
</OBJECT>
<OBJECT height="3508" width="2480">
<PARAM name="DPI" value="300" />
<PARAM name="GAMMA" value="2.2" />
</OBJECT>
<OBJECT height="3508" width="2480">
<PARAM name="DPI" value="300" />
<PARAM name="GAMMA" value="2.2" />
</OBJECT>
<OBJECT height="3508" width="2480">
<PARAM name="DPI" value="300" />
<PARAM name="GAMMA" value="2.2" />
</OBJECT>
</BODY>
</DjVuXML>',
			'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
			'fileExists' => true
		], $this->db->timestamp( '20010115123600' ), $user );
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
	 * @return string The directory
	 */
	private function setupUploadDir() {
		global $IP;

		$dir = $this->uploadDir;
		if ( $this->keepUploads && is_dir( $dir ) ) {
			return;
		}

		// wfDebug( "Creating upload directory $dir\n" );
		if ( file_exists( $dir ) ) {
			wfDebug( "Already exists!\n" );
			return;
		}

		wfMkdirParents( $dir . '/3/3a', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/parser/headbg.jpg", "$dir/3/3a/Foobar.jpg" );
		wfMkdirParents( $dir . '/e/ea', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/parser/wiki.png", "$dir/e/ea/Thumb.png" );
		wfMkdirParents( $dir . '/0/09', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/parser/headbg.jpg", "$dir/0/09/Bad.jpg" );
		wfMkdirParents( $dir . '/f/ff', null, __METHOD__ );
		file_put_contents( "$dir/f/ff/Foobar.svg",
			'<?xml version="1.0" encoding="utf-8"?>' .
			'<svg xmlns="http://www.w3.org/2000/svg"' .
			' version="1.1" width="240" height="180"/>' );
		wfMkdirParents( $dir . '/5/5f', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/parser/LoremIpsum.djvu", "$dir/5/5f/LoremIpsum.djvu" );
		wfMkdirParents( $dir . '/0/00', null, __METHOD__ );
		copy( "$IP/tests/phpunit/data/parser/320x240.ogv", "$dir/0/00/Video.ogv" );

		return;
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
		MWTidy::destroySingleton();

		foreach ( $this->savedGlobals as $var => $val ) {
			$GLOBALS[$var] = $val;
		}
	}

	/**
	 * Remove the dummy uploads directory
	 * @param string $dir
	 */
	private function teardownUploadDir( $dir ) {
		if ( $this->keepUploads ) {
			return;
		}

		// delete the files first, then the dirs.
		self::deleteFiles(
			[
				"$dir/3/3a/Foobar.jpg",
				"$dir/thumb/3/3a/Foobar.jpg/*.jpg",
				"$dir/e/ea/Thumb.png",
				"$dir/0/09/Bad.jpg",
				"$dir/5/5f/LoremIpsum.djvu",
				"$dir/thumb/5/5f/LoremIpsum.djvu/*-LoremIpsum.djvu.jpg",
				"$dir/f/ff/Foobar.svg",
				"$dir/thumb/f/ff/Foobar.svg/*-Foobar.svg.png",
				"$dir/math/f/a/5/fa50b8b616463173474302ca3e63586b.png",
				"$dir/0/00/Video.ogv",
				"$dir/thumb/0/00/Video.ogv/120px--Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/180px--Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/240px--Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/320px--Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/270px--Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/320px-seek=2-Video.ogv.jpg",
				"$dir/thumb/0/00/Video.ogv/320px-seek=3.3666666666667-Video.ogv.jpg",
			]
		);

		self::deleteDirs(
			[
				"$dir/3/3a",
				"$dir/3",
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
				"$dir/0/00/",
				"$dir/0/09/",
				"$dir/0/",
				"$dir/5/5f",
				"$dir/5",
				"$dir/thumb/0/00/Video.ogv",
				"$dir/thumb/0/00",
				"$dir/thumb/0",
				"$dir/thumb/5/5f/LoremIpsum.djvu",
				"$dir/thumb/5/5f",
				"$dir/thumb/5",
				"$dir/thumb",
				"$dir/math/f/a/5",
				"$dir/math/f/a",
				"$dir/math/f",
				"$dir/math",
				"$dir/lockdir",
				"$dir",
			]
		);
	}

	/**
	 * Delete the specified files, if they exist.
	 * @param array $files Full paths to files to delete.
	 */
	private static function deleteFiles( $files ) {
		foreach ( $files as $pattern ) {
			foreach ( glob( $pattern ) as $file ) {
				if ( file_exists( $file ) ) {
					unlink( $file );
				}
			}
		}
	}

	/**
	 * Delete the specified directories, if they exist. Must be empty.
	 * @param array $dirs Full paths to directories to delete.
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
	 * @param string $desc
	 */
	protected function showTesting( $desc ) {
		print "Running test $desc... ";
	}

	/**
	 * Print a happy success message.
	 *
	 * Refactored in 1.22 to use ParserTestResult
	 *
	 * @param ParserTestResult $testResult
	 * @return bool
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
	 * @param ParserTestResult $testResult
	 * @return bool
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
	 * Print a skipped message.
	 *
	 * @return bool
	 */
	protected function showSkipped() {
		if ( $this->showProgress ) {
			print $this->term->color( '1;33' ) . 'SKIPPED' . $this->term->reset() . "\n";
		}

		return true;
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
			[ '/^(-.*)$/m', '/^(\+.*)$/m' ],
			[ $this->term->color( 34 ) . '$1' . $this->term->reset(),
				$this->term->color( 31 ) . '$1' . $this->term->reset() ],
			$text );
	}

	/**
	 * Show "Reading tests from ..."
	 *
	 * @param string $path
	 */
	public function showRunFile( $path ) {
		print $this->term->color( 1 ) .
			"Reading tests from \"$path\"..." .
			$this->term->reset() .
			"\n";
	}

	/**
	 * Insert a temporary test article
	 * @param string $name The title, including any prefix
	 * @param string $text The article text
	 * @param int|string $line The input line number, for reporting errors
	 * @param bool|string $ignoreDuplicate Whether to silently ignore duplicate pages
	 * @throws Exception
	 * @throws MWException
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
	 * @param string $name
	 * @return bool True if tag hook is present
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
	 * @param string $name
	 * @return bool True if function hook is present
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
	 * Steal a callback function from the primary parser, save it for
	 * application to our scary parser. If the hook is not installed,
	 * abort processing of this file.
	 *
	 * @param string $name
	 * @return bool True if function hook is present
	 */
	public function requireTransparentHook( $name ) {
		global $wgParser;

		$wgParser->firstCallInit(); // make sure hooks are loaded.

		if ( isset( $wgParser->mTransparentTagHooks[$name] ) ) {
			$this->transparentHooks[$name] = $wgParser->mTransparentTagHooks[$name];
		} else {
			echo "   This test suite requires the '$name' transparent hook extension, skipping.\n";
			return false;
		}

		return true;
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
		$ts = 123; // parsed as '1970-01-01T00:02:03Z'
		return true;
	}
}
