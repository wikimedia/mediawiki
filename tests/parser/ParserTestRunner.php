<?php
/**
 * Generic backend for the MediaWiki parser test suite, used by both the
 * standalone parserTests.php and the PHPUnit "parsertests" suite.
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
 * @file
 * @ingroup Testing
 */
use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

/**
 * @ingroup Testing
 */
class ParserTestRunner {
	/**
	 * @var bool $useTemporaryTables Use temporary tables for the temporary database
	 */
	private $useTemporaryTables = true;

	/**
	 * @var array $setupDone The status of each setup function
	 */
	private $setupDone = [
		'staticSetup' => false,
		'perTestSetup' => false,
		'setupDatabase' => false,
		'setDatabase' => false,
		'setupUploads' => false,
	];

	/**
	 * Our connection to the database
	 * @var Database
	 */
	private $db;

	/**
	 * Database clone helper
	 * @var CloneDatabase
	 */
	private $dbClone;

	/**
	 * @var TidySupport
	 */
	private $tidySupport;

	/**
	 * @var TidyDriverBase
	 */
	private $tidyDriver = null;

	/**
	 * @var TestRecorder
	 */
	private $recorder;

	/**
	 * The upload directory, or null to not set up an upload directory
	 *
	 * @var string|null
	 */
	private $uploadDir = null;

	/**
	 * The name of the file backend to use, or null to use MockFileBackend.
	 * @var string|null
	 */
	private $fileBackendName;

	/**
	 * A complete regex for filtering tests.
	 * @var string
	 */
	private $regex;

	/**
	 * A list of normalization functions to apply to the expected and actual
	 * output.
	 * @var array
	 */
	private $normalizationFunctions = [];

	/**
	 * @param TestRecorder $recorder
	 * @param array $options
	 */
	public function __construct( TestRecorder $recorder, $options = [] ) {
		$this->recorder = $recorder;

		if ( isset( $options['norm'] ) ) {
			foreach ( $options['norm'] as $func ) {
				if ( in_array( $func, [ 'removeTbody', 'trimWhitespace' ] ) ) {
					$this->normalizationFunctions[] = $func;
				} else {
					$this->recorder->warning(
						"Warning: unknown normalization option \"$func\"\n" );
				}
			}
		}

		if ( isset( $options['regex'] ) && $options['regex'] !== false ) {
			$this->regex = $options['regex'];
		} else {
			# Matches anything
			$this->regex = '//';
		}

		$this->keepUploads = !empty( $options['keep-uploads'] );

		$this->fileBackendName = isset( $options['file-backend'] ) ?
			$options['file-backend'] : false;

		$this->runDisabled = !empty( $options['run-disabled'] );
		$this->runParsoid = !empty( $options['run-parsoid'] );

		$this->tidySupport = new TidySupport( !empty( $options['use-tidy-config'] ) );
		if ( !$this->tidySupport->isEnabled() ) {
			$this->recorder->warning(
				"Warning: tidy is not installed, skipping some tests\n" );
		}

		if ( isset( $options['upload-dir'] ) ) {
			$this->uploadDir = $options['upload-dir'];
		}
	}

	public function getRecorder() {
		return $this->recorder;
	}

	/**
	 * Do any setup which can be done once for all tests, independent of test
	 * options, except for database setup.
	 *
	 * Public setup functions in this class return a ScopedCallback object. When
	 * this object is destroyed by going out of scope, teardown of the
	 * corresponding test setup is performed.
	 *
	 * Teardown objects may be chained by passing a ScopedCallback from a
	 * previous setup stage as the $nextTeardown parameter. This enforces the
	 * convention that teardown actions are taken in reverse order to the
	 * corresponding setup actions. When $nextTeardown is specified, a
	 * ScopedCallback will be returned which first tears down the current
	 * setup stage, and then tears down the previous setup stage which was
	 * specified by $nextTeardown.
	 *
	 * @param ScopedCallback|null $nextTeardown
	 * @return ScopedCallback
	 */
	public function staticSetup( $nextTeardown = null ) {
		// A note on coding style:

		// The general idea here is to keep setup code together with
		// corresponding teardown code, in a fine-grained manner. We have two
		// arrays: $setup and $teardown. The code snippets in the $setup array
		// are executed at the end of the method, before it returns, and the
		// code snippets in the $teardown array are executed in reverse order
		// when the Wikimedia\ScopedCallback object is consumed.

		// Because it is a common operation to save, set and restore global
		// variables, we have an additional convention: when the array key of
		// $setup is a string, the string is taken to be the name of the global
		// variable, and the element value is taken to be the desired new value.

		// It's acceptable to just do the setup immediately, instead of adding
		// a closure to $setup, except when the setup action depends on global
		// variable initialisation being done first. In this case, you have to
		// append a closure to $setup after the global variable is appended.

		// When you add to setup functions in this class, please keep associated
		// setup and teardown actions together in the source code, and please
		// add comments explaining why the setup action is necessary.

		$setup = [];
		$teardown = [];

		$teardown[] = $this->markSetupDone( 'staticSetup' );

		// Some settings which influence HTML output
		$setup['wgSitename'] = 'MediaWiki';
		$setup['wgServer'] = 'http://example.org';
		$setup['wgServerName'] = 'example.org';
		$setup['wgScriptPath'] = '';
		$setup['wgScript'] = '/index.php';
		$setup['wgResourceBasePath'] = '';
		$setup['wgStylePath'] = '/skins';
		$setup['wgExtensionAssetsPath'] = '/extensions';
		$setup['wgArticlePath'] = '/wiki/$1';
		$setup['wgActionPaths'] = [];
		$setup['wgVariantArticlePath'] = false;
		$setup['wgUploadNavigationUrl'] = false;
		$setup['wgCapitalLinks'] = true;
		$setup['wgNoFollowLinks'] = true;
		$setup['wgNoFollowDomainExceptions'] = [ 'no-nofollow.org' ];
		$setup['wgExternalLinkTarget'] = false;
		$setup['wgExperimentalHtmlIds'] = false;
		$setup['wgLocaltimezone'] = 'UTC';
		$setup['wgHtml5'] = true;
		$setup['wgDisableLangConversion'] = false;
		$setup['wgDisableTitleConversion'] = false;

		// "extra language links"
		// see https://gerrit.wikimedia.org/r/111390
		$setup['wgExtraInterlanguageLinkPrefixes'] = [ 'mul' ];

		// All FileRepo changes should be done here by injecting services,
		// there should be no need to change global variables.
		RepoGroup::setSingleton( $this->createRepoGroup() );
		$teardown[] = function () {
			RepoGroup::destroySingleton();
		};

		// Set up null lock managers
		$setup['wgLockManagers'] = [ [
			'name' => 'fsLockManager',
			'class' => 'NullLockManager',
		], [
			'name' => 'nullLockManager',
			'class' => 'NullLockManager',
		] ];
		$reset = function() {
			LockManagerGroup::destroySingletons();
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// This allows article insertion into the prefixed DB
		$setup['wgDefaultExternalStore'] = false;

		// This might slightly reduce memory usage
		$setup['wgAdaptiveMessageCache'] = true;

		// This is essential and overrides disabling of database messages in TestSetup
		$setup['wgUseDatabaseMessages'] = true;
		$reset = function () {
			MessageCache::destroyInstance();
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// It's not necessary to actually convert any files
		$setup['wgSVGConverter'] = 'null';
		$setup['wgSVGConverters'] = [ 'null' => 'echo "1">$output' ];

		// Fake constant timestamp
		Hooks::register( 'ParserGetVariableValueTs', 'ParserTestRunner::getFakeTimestamp' );
		$teardown[] = function () {
			Hooks::clear( 'ParserGetVariableValueTs' );
		};

		$this->appendNamespaceSetup( $setup, $teardown );

		// Set up interwikis and append teardown function
		$teardown[] = $this->setupInterwikis();

		// This affects title normalization in links. It invalidates
		// MediaWikiTitleCodec objects.
		$setup['wgLocalInterwikis'] = [ 'local', 'mi' ];
		$reset = function () {
			$this->resetTitleServices();
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// Set up a mock MediaHandlerFactory
		MediaWikiServices::getInstance()->disableService( 'MediaHandlerFactory' );
		MediaWikiServices::getInstance()->redefineService(
			'MediaHandlerFactory',
			function() {
				return new MockMediaHandlerFactory();
			}
		);
		$teardown[] = function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'MediaHandlerFactory' );
		};

		// SqlBagOStuff broke when using temporary tables on r40209 (bug 15892).
		// It seems to have been fixed since (r55079?), but regressed at some point before r85701.
		// This works around it for now...
		global $wgObjectCaches;
		$setup['wgObjectCaches'] = [ CACHE_DB => $wgObjectCaches['hash'] ] + $wgObjectCaches;
		if ( isset( ObjectCache::$instances[CACHE_DB] ) ) {
			$savedCache = ObjectCache::$instances[CACHE_DB];
			ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;
			$teardown[] = function () use ( $savedCache ) {
				ObjectCache::$instances[CACHE_DB] = $savedCache;
			};
		}

		$teardown[] = $this->executeSetupSnippets( $setup );

		// Schedule teardown snippets in reverse order
		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	private function appendNamespaceSetup( &$setup, &$teardown ) {
		// Add a namespace shadowing a interwiki link, to test
		// proper precedence when resolving links. (bug 51680)
		$setup['wgExtraNamespaces'] = [
			100 => 'MemoryAlpha',
			101 => 'MemoryAlpha_talk'
		];
		// Changing wgExtraNamespaces invalidates caches in MWNamespace and
		// any live Language object, both on setup and teardown
		$reset = function () {
			MWNamespace::getCanonicalNamespaces( true );
			$GLOBALS['wgContLang']->resetNamespaces();
		};
		$setup[] = $reset;
		$teardown[] = $reset;
	}

	/**
	 * Create a RepoGroup object appropriate for the current configuration
	 * @return RepoGroup
	 */
	protected function createRepoGroup() {
		if ( $this->uploadDir ) {
			if ( $this->fileBackendName ) {
				throw new MWException( 'You cannot specify both use-filebackend and upload-dir' );
			}
			$backend = new FSFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID(),
				'basePath' => $this->uploadDir,
				'tmpDirectory' => wfTempDir()
			] );
		} elseif ( $this->fileBackendName ) {
			global $wgFileBackends;
			$name = $this->fileBackendName;
			$useConfig = false;
			foreach ( $wgFileBackends as $conf ) {
				if ( $conf['name'] === $name ) {
					$useConfig = $conf;
				}
			}
			if ( $useConfig === false ) {
				throw new MWException( "Unable to find file backend \"$name\"" );
			}
			$useConfig['name'] = 'local-backend'; // swap name
			unset( $useConfig['lockManager'] );
			unset( $useConfig['fileJournal'] );
			$class = $useConfig['class'];
			$backend = new $class( $useConfig );
		} else {
			# Replace with a mock. We do not care about generating real
			# files on the filesystem, just need to expose the file
			# informations.
			$backend = new MockFileBackend( [
				'name' => 'local-backend',
				'wikiId' => wfWikiID()
			] );
		}

		return new RepoGroup(
			[
				'class' => 'MockLocalRepo',
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => $backend
			],
			[]
		);
	}

	/**
	 * Execute an array in which elements with integer keys are taken to be
	 * callable objects, and other elements are taken to be global variable
	 * set operations, with the key giving the variable name and the value
	 * giving the new global variable value. A closure is returned which, when
	 * executed, sets the global variables back to the values they had before
	 * this function was called.
	 *
	 * @see staticSetup
	 *
	 * @param array $setup
	 * @return closure
	 */
	protected function executeSetupSnippets( $setup ) {
		$saved = [];
		foreach ( $setup as $name => $value ) {
			if ( is_int( $name ) ) {
				$value();
			} else {
				$saved[$name] = isset( $GLOBALS[$name] ) ? $GLOBALS[$name] : null;
				$GLOBALS[$name] = $value;
			}
		}
		return function () use ( $saved ) {
			$this->executeSetupSnippets( $saved );
		};
	}

	/**
	 * Take a setup array in the same format as the one given to
	 * executeSetupSnippets(), and return a ScopedCallback which, when consumed,
	 * executes the snippets in the setup array in reverse order. This is used
	 * to create "teardown objects" for the public API.
	 *
	 * @see staticSetup
	 *
	 * @param array $teardown The snippet array
	 * @param ScopedCallback|null A ScopedCallback to consume
	 * @return ScopedCallback
	 */
	protected function createTeardownObject( $teardown, $nextTeardown ) {
		return new ScopedCallback( function() use ( $teardown, $nextTeardown ) {
			// Schedule teardown snippets in reverse order
			$teardown = array_reverse( $teardown );

			$this->executeSetupSnippets( $teardown );
			if ( $nextTeardown ) {
				ScopedCallback::consume( $nextTeardown );
			}
		} );
	}

	/**
	 * Set a setupDone flag to indicate that setup has been done, and return
	 * the teardown closure. If the flag was already set, throw an exception.
	 *
	 * @param string $funcName The setup function name
	 * @return closure
	 */
	protected function markSetupDone( $funcName ) {
		if ( $this->setupDone[$funcName] ) {
			throw new MWException( "$funcName is already done" );
		}
		$this->setupDone[$funcName] = true;
		return function () use ( $funcName ) {
			$this->setupDone[$funcName] = false;
		};
	}

	/**
	 * Ensure a given setup stage has been done, throw an exception if it has
	 * not.
	 */
	protected function checkSetupDone( $funcName, $funcName2 = null ) {
		if ( !$this->setupDone[$funcName]
			&& ( $funcName === null || !$this->setupDone[$funcName2] )
		) {
			throw new MWException( "$funcName must be called before calling " .
				wfGetCaller() );
		}
	}

	/**
	 * Determine whether a particular setup function has been run
	 *
	 * @param string $funcName
	 * @return boolean
	 */
	public function isSetupDone( $funcName ) {
		return isset( $this->setupDone[$funcName] ) ? $this->setupDone[$funcName] : false;
	}

	/**
	 * Insert hardcoded interwiki in the lookup table.
	 *
	 * This function insert a set of well known interwikis that are used in
	 * the parser tests. They can be considered has fixtures are injected in
	 * the interwiki cache by using the 'InterwikiLoadPrefix' hook.
	 * Since we are not interested in looking up interwikis in the database,
	 * the hook completely replace the existing mechanism (hook returns false).
	 *
	 * @return closure for teardown
	 */
	private function setupInterwikis() {
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

		return function () {
			// Tear down
			Hooks::clear( 'InterwikiLoadPrefix' );
		};
	}

	/**
	 * Reset the Title-related services that need resetting
	 * for each test
	 */
	private function resetTitleServices() {
		$services = MediaWikiServices::getInstance();
		$services->resetServiceForTesting( 'TitleFormatter' );
		$services->resetServiceForTesting( 'TitleParser' );
		$services->resetServiceForTesting( '_MediaWikiTitleCodec' );
		$services->resetServiceForTesting( 'LinkRenderer' );
		$services->resetServiceForTesting( 'LinkRendererFactory' );
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
	 * Run a series of tests listed in the given text files.
	 * Each test consists of a brief description, wikitext input,
	 * and the expected HTML output.
	 *
	 * Prints status updates on stdout and counts up the total
	 * number and percentage of passed tests.
	 *
	 * Handles all setup and teardown.
	 *
	 * @param array $filenames Array of strings
	 * @return bool True if passed all tests, false if any tests failed.
	 */
	public function runTestsFromFiles( $filenames ) {
		$ok = false;

		$teardownGuard = $this->staticSetup();
		$teardownGuard = $this->setupDatabase( $teardownGuard );
		$teardownGuard = $this->setupUploads( $teardownGuard );

		$this->recorder->start();
		try {
			$ok = true;

			foreach ( $filenames as $filename ) {
				$testFileInfo = TestFileReader::read( $filename, [
					'runDisabled' => $this->runDisabled,
					'runParsoid' => $this->runParsoid,
					'regex' => $this->regex ] );

				// Don't start the suite if there are no enabled tests in the file
				if ( !$testFileInfo['tests'] ) {
					continue;
				}

				$this->recorder->startSuite( $filename );
				$ok = $this->runTests( $testFileInfo ) && $ok;
				$this->recorder->endSuite( $filename );
			}

			$this->recorder->report();
		} catch ( DBError $e ) {
			$this->recorder->warning( $e->getMessage() );
		}
		$this->recorder->end();

		ScopedCallback::consume( $teardownGuard );

		return $ok;
	}

	/**
	 * Determine whether the current parser has the hooks registered in it
	 * that are required by a file read by TestFileReader.
	 */
	public function meetsRequirements( $requirements ) {
		foreach ( $requirements as $requirement ) {
			switch ( $requirement['type'] ) {
			case 'hook':
				$ok = $this->requireHook( $requirement['name'] );
				break;
			case 'functionHook':
				$ok = $this->requireFunctionHook( $requirement['name'] );
				break;
			case 'transparentHook':
				$ok = $this->requireTransparentHook( $requirement['name'] );
				break;
			}
			if ( !$ok ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Run the tests from a single file. staticSetup() and setupDatabase()
	 * must have been called already.
	 *
	 * @param array $testFileInfo Parsed file info returned by TestFileReader
	 * @return bool True if passed all tests, false if any tests failed.
	 */
	public function runTests( $testFileInfo ) {
		$ok = true;

		$this->checkSetupDone( 'staticSetup' );

		// Don't add articles from the file if there are no enabled tests from the file
		if ( !$testFileInfo['tests'] ) {
			return true;
		}

		// If any requirements are not met, mark all tests from the file as skipped
		if ( !$this->meetsRequirements( $testFileInfo['requirements'] ) ) {
			foreach ( $testFileInfo['tests'] as $test ) {
				$this->recorder->startTest( $test );
				$this->recorder->skipped( $test, 'required extension not enabled' );
			}
			return true;
		}

		// Add articles
		$this->addArticles( $testFileInfo['articles'] );

		// Run tests
		foreach ( $testFileInfo['tests'] as $test ) {
			$this->recorder->startTest( $test );
			$result =
				$this->runTest( $test );
			if ( $result !== false ) {
				$ok = $ok && $result->isSuccess();
				$this->recorder->record( $test, $result );
			}
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
		ParserTestParserHook::setup( $parser );

		return $parser;
	}

	/**
	 * Run a given wikitext input through a freshly-constructed wiki parser,
	 * and compare the output against the expected results.
	 * Prints status and explanatory messages to stdout.
	 *
	 * staticSetup() and setupWikiData() must be called before this function
	 * is entered.
	 *
	 * @param array $test The test parameters:
	 *  - test: The test name
	 *  - desc: The subtest description
	 *  - input: Wikitext to try rendering
	 *  - options: Array of test options
	 *  - config: Overrides for global variables, one per line
	 *
	 * @return ParserTestResult or false if skipped
	 */
	public function runTest( $test ) {
		wfDebug( __METHOD__.": running {$test['desc']}" );
		$opts = $this->parseOptions( $test['options'] );
		$teardownGuard = $this->perTestSetup( $test );

		$context = RequestContext::getMain();
		$user = $context->getUser();
		$options = ParserOptions::newFromContext( $context );

		if ( isset( $opts['tidy'] ) ) {
			if ( !$this->tidySupport->isEnabled() ) {
				$this->recorder->skipped( $test, 'tidy extension is not installed' );
				return false;
			} else {
				$options->setTidy( true );
			}
		}

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
			$out = $parser->preSaveTransform( $test['input'], $title, $user, $options );
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $test['input'], $options, $title );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $test['input'], $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $test['input'], $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$out = Linker::formatComment( $test['input'], $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getPreloadText( $test['input'], $title, $options );
		} else {
			$output = $parser->parse( $test['input'], $title, $options, true, true, 1337 );
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
				$out = '';
				foreach ( $output->getCategories() as $name => $sortkey ) {
					if ( $out !== '' ) {
						$out .= "\n";
					}
					$out .= "cat=$name sort=$sortkey";
				}
			}
		}

		ScopedCallback::consume( $teardownGuard );

		$expected = $test['result'];
		if ( count( $this->normalizationFunctions ) ) {
			$expected = ParserTestResultNormalizer::normalize(
				$test['expected'], $this->normalizationFunctions );
			$out = ParserTestResultNormalizer::normalize( $out, $this->normalizationFunctions );
		}

		$testResult = new ParserTestResult( $test, $expected, $out );
		return $testResult;
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

	/**
	 * Given the options string, return an associative array of options.
	 * @todo Move this to TestFileReader
	 *
	 * @param string $instring
	 * @return array
	 */
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
	 * Do any required setup which is dependent on test options.
	 *
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param array $test Test info supplied by TestFileReader
	 * @param callable|null $nextTeardown
	 * @return ScopedCallback
	 */
	public function perTestSetup( $test, $nextTeardown = null ) {
		$teardown = [];

		$this->checkSetupDone( 'setupDatabase', 'setDatabase' );
		$teardown[] = $this->markSetupDone( 'perTestSetup' );

		$opts = $this->parseOptions( $test['options'] );
		$config = $test['config'];

		// Find out values for some special options.
		$langCode =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

		$setup = [
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgLanguageCode' => $langCode,
			'wgRawHtml' => self::getOptionValue( 'wgRawHtml', $opts, false ),
			'wgNamespacesWithSubpages' => [ 0 => isset( $opts['subpage'] ) ],
			'wgMaxTocLevel' => $maxtoclevel,
			'wgAllowExternalImages' => self::getOptionValue( 'wgAllowExternalImages', $opts, true ),
			'wgThumbLimits' => [ self::getOptionValue( 'thumbsize', $opts, 180 ) ],
			'wgDefaultLanguageVariant' => $variant,
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
			// Set as a JSON object like:
			// wgEnableMagicLinks={"ISBN":false, "PMID":false, "RFC":false}
			'wgEnableMagicLinks' => self::getOptionValue( 'wgEnableMagicLinks', $opts, [] )
				+ [ 'ISBN' => true, 'PMID' => true, 'RFC' => true ],
		];

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value )  = explode( '=', $line, 2 );
				$setup[$var] = eval( "return $value;" );
			}
		}

		/** @since 1.20 */
		Hooks::run( 'ParserTestGlobals', [ &$setup ] );

		// Create tidy driver
		if ( isset( $opts['tidy'] ) ) {
			// Cache a driver instance
			if ( $this->tidyDriver === null ) {
				$this->tidyDriver = MWTidy::factory( $this->tidySupport->getConfig() );
			}
			$tidy = $this->tidyDriver;
		} else {
			$tidy = false;
		}
		MWTidy::setInstance( $tidy );
		$teardown[] = function () {
			MWTidy::destroySingleton();
		};

		// Set content language. This invalidates the magic word cache and title services
		$lang = Language::factory( $langCode );
		$setup['wgContLang'] = $lang;
		$reset = function () {
			MagicWord::clearCache();
			$this->resetTitleServices();
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// Make a user object with the same language
		$user = new User;
		$user->setOption( 'language', $langCode );
		$setup['wgLang'] = $lang;

		// We (re)set $wgThumbLimits to a single-element array above.
		$user->setOption( 'thumbsize', 0 );

		$setup['wgUser'] = $user;

		// And put both user and language into the context
		$context = RequestContext::getMain();
		$context->setUser( $user );
		$context->setLanguage( $lang );
		$teardown[] = function () use ( $context ) {
			// Reset context to the restored globals
			$context->setUser( $GLOBALS['wgUser'] );
			$context->setLanguage( $GLOBALS['wgContLang'] );
		};

		$teardown[] = $this->executeSetupSnippets( $setup );

		return $this->createTeardownObject( $teardown, $nextTeardown );
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

	public function setDatabase( IDatabase $db ) {
		$this->db = $db;
		$this->setupDone['setDatabase'] = true;
	}

	/**
	 * Set up temporary DB tables.
	 *
	 * For best performance, call this once only for all tests. However, it can
	 * be called at the start of each test if more isolation is desired.
	 *
	 * @todo: This is basically an unrefactored copy of
	 * MediaWikiTestCase::setupAllTestDBs. They should be factored out somehow.
	 *
	 * Do not call this function from a MediaWikiTestCase subclass, since
	 * MediaWikiTestCase does its own DB setup. Instead use setDatabase().
	 *
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param ScopedCallback|null $nextTeardown The next teardown object
	 * @return ScopedCallback The teardown object
	 */
	public function setupDatabase( $nextTeardown = null ) {
		global $wgDBprefix;

		$this->db = wfGetDB( DB_MASTER );
		$dbType = $this->db->getType();

		if ( $dbType == 'oracle' ) {
			$suspiciousPrefixes = [ 'pt_', MediaWikiTestCase::ORA_DB_PREFIX ];
		} else {
			$suspiciousPrefixes = [ 'parsertest_', MediaWikiTestCase::DB_PREFIX ];
		}
		if ( in_array( $wgDBprefix, $suspiciousPrefixes ) ) {
			throw new MWException( "\$wgDBprefix=$wgDBprefix suggests DB setup is already done" );
		}

		$teardown = [];

		$teardown[] = $this->markSetupDone( 'setupDatabase' );

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

		$teardown[] = function () {
			$this->teardownDatabase();
		};

		// Wipe some DB query result caches on setup and teardown
		$reset = function () {
			LinkCache::singleton()->clear();

			// Clear the message cache
			MessageCache::singleton()->clear();
		};
		$reset();
		$teardown[] = $reset;
		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	/**
	 * Add data about uploads to the new test DB, and set up the upload
	 * directory. This should be called after either setDatabase() or
	 * setupDatabase().
	 *
	 * @param ScopedCallback|null $nextTeardown The next teardown object
	 * @return ScopedCallback The teardown object
	 */
	public function setupUploads( $nextTeardown = null ) {
		$teardown = [];

		$this->checkSetupDone( 'setupDatabase', 'setDatabase' );
		$teardown[] = $this->markSetupDone( 'setupUploads' );

		// Create the files in the upload directory (or pretend to create them
		// in a MockFileBackend). Append teardown callback.
		$teardown[] = $this->setupUploadBackend();

		// Create a user
		$user = User::createNew( 'WikiSysop' );

		// Register the uploads in the database

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

		$image = wfLocalFile( Title::makeTitle( NS_FILE, 'Audio.oga' ) );
		$image->recordUpload2( '', 'An awesome hitsong', 'Will it play', [
			'size' => 12345,
			'width' => 0,
			'height' => 0,
			'bits' => 0,
			'media_type' => MEDIATYPE_AUDIO,
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

		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	/**
	 * Helper for database teardown, called from the teardown closure. Destroy
	 * the database clone and fix up some things that CloneDatabase doesn't fix.
	 *
	 * @todo Move most things here to CloneDatabase
	 */
	private function teardownDatabase() {
		$this->checkSetupDone( 'setupDatabase' );

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
	}

	/**
	 * Upload test files to the backend created by createRepoGroup().
	 *
	 * @return callable The teardown callback
	 */
	private function setupUploadBackend() {
		global $IP;

		$repo = RepoGroup::singleton()->getLocalRepo();
		$base = $repo->getZonePath( 'public' );
		$backend = $repo->getBackend();
		$backend->prepare( [ 'dir' => "$base/3/3a" ] );
		$backend->store( [
			'src' => "$IP/tests/phpunit/data/parser/headbg.jpg",
			'dst' => "$base/3/3a/Foobar.jpg"
		] );
		$backend->prepare( [ 'dir' => "$base/e/ea" ] );
		$backend->store( [
			'src' => "$IP/tests/phpunit/data/parser/wiki.png",
			'dst' => "$base/e/ea/Thumb.png"
		] );
		$backend->prepare( [ 'dir' => "$base/0/09" ] );
		$backend->store( [
			'src' => "$IP/tests/phpunit/data/parser/headbg.jpg",
			'dst' => "$base/0/09/Bad.jpg"
		] );
		$backend->prepare( [ 'dir' => "$base/5/5f" ] );
		$backend->store( [
			'src' => "$IP/tests/phpunit/data/parser/LoremIpsum.djvu",
			'dst' => "$base/5/5f/LoremIpsum.djvu"
		] );

		// No helpful SVG file to copy, so make one ourselves
		$data = '<?xml version="1.0" encoding="utf-8"?>' .
			'<svg xmlns="http://www.w3.org/2000/svg"' .
			' version="1.1" width="240" height="180"/>';

		$backend->prepare( [ 'dir' => "$base/f/ff" ] );
		$backend->quickCreate( [
			'content' => $data, 'dst' => "$base/f/ff/Foobar.svg"
		] );

		return function () use ( $backend ) {
			if ( $backend instanceof MockFileBackend ) {
				// In memory backend, so dont bother cleaning them up.
				return;
			}
			$this->teardownUploadBackend();
		};
	}

	/**
	 * Remove the dummy uploads directory
	 */
	private function teardownUploadBackend() {
		if ( $this->keepUploads ) {
			return;
		}

		$repo = RepoGroup::singleton()->getLocalRepo();
		$public = $repo->getZonePath( 'public' );

		$this->deleteFiles(
			[
				"$public/3/3a/Foobar.jpg",
				"$public/e/ea/Thumb.png",
				"$public/0/09/Bad.jpg",
				"$public/5/5f/LoremIpsum.djvu",
				"$public/f/ff/Foobar.svg",
				"$public/0/00/Video.ogv",
				"$public/4/41/Audio.oga",
			]
		);
	}

	/**
	 * Delete the specified files and their parent directories
	 * @param array $files File backend URIs mwstore://...
	 */
	private function deleteFiles( $files ) {
		// Delete the files
		$backend = RepoGroup::singleton()->getLocalRepo()->getBackend();
		foreach ( $files as $file ) {
			$backend->delete( [ 'src' => $file ], [ 'force' => 1 ] );
		}

		// Delete the parent directories
		foreach ( $files as $file ) {
			$tmp = FileBackend::parentStoragePath( $file );
			while ( $tmp ) {
				if ( !$backend->clean( [ 'dir' => $tmp ] )->isOK() ) {
					break;
				}
				$tmp = FileBackend::parentStoragePath( $tmp );
			}
		}
	}

	/**
	 * Add articles to the test DB.
	 *
	 * @param $articles Article info array from TestFileReader
	 */
	public function addArticles( $articles ) {
		global $wgContLang;
		$setup = [];
		$teardown = [];

		// Be sure ParserTestRunner::addArticle has correct language set,
		// so that system messages get into the right language cache
		if ( $wgContLang->getCode() !== 'en' ) {
			$setup['wgLanguageCode'] = 'en';
			$setup['wgContLang'] = Language::factory( 'en' );
		}

		// Add special namespaces, in case that hasn't been done by staticSetup() yet
		$this->appendNamespaceSetup( $setup, $teardown );

		// wgCapitalLinks obviously needs initialisation
		$setup['wgCapitalLinks'] = true;

		$teardown[] = $this->executeSetupSnippets( $setup );

		foreach ( $articles as $info ) {
			$this->addArticle( $info['name'], $info['text'], $info['file'], $info['line'] );
		}

		// Wipe WANObjectCache process cache, which is invalidated by article insertion
		// due to T144706
		ObjectCache::getMainWANInstance()->clearProcessCache();

		$this->executeSetupSnippets( $teardown );
	}

	/**
	 * Insert a temporary test article
	 * @param string $name The title, including any prefix
	 * @param string $text The article text
	 * @param string $file The input file name
	 * @param int|string $line The input line number, for reporting errors
	 * @throws Exception
	 * @throws MWException
	 */
	private function addArticle( $name, $text, $file, $line ) {
		$text = self::chomp( $text );
		$name = self::chomp( $name );

		$title = Title::newFromText( $name );
		wfDebug( __METHOD__ . ": adding $name" );

		if ( is_null( $title ) ) {
			throw new MWException( "invalid title '$name' at $file:$line\n" );
		}

		$page = WikiPage::factory( $title );
		$page->loadPageData( 'fromdbmaster' );

		if ( $page->exists() ) {
			throw new MWException( "duplicate article '$name' at $file:$line\n" );
		}

		$status = $page->doEditContent( ContentHandler::makeContent( $text, $title ), '', EDIT_NEW );
		if ( !$status->isOK() ) {
			throw new MWException( $status->getWikiText( false, false, 'en' ) );
		}

		// The RepoGroup cache is invalidated by the creation of file redirects
		if ( $title->inNamespace( NS_FILE ) ) {
			RepoGroup::singleton()->clearCache( $title );
		}
	}

	/**
	 * Check if a hook is installed
	 *
	 * @param string $name
	 * @return bool True if tag hook is present
	 */
	public function requireHook( $name ) {
		global $wgParser;

		$wgParser->firstCallInit(); // make sure hooks are loaded.
		if ( isset( $wgParser->mTagHooks[$name] ) ) {
			return true;
		} else {
			$this->recorder->warning( "   This test suite requires the '$name' hook " .
				"extension, skipping." );
			return false;
		}
	}

	/**
	 * Check if a function hook is installed
	 *
	 * @param string $name
	 * @return bool True if function hook is present
	 */
	public function requireFunctionHook( $name ) {
		global $wgParser;

		$wgParser->firstCallInit(); // make sure hooks are loaded.

		if ( isset( $wgParser->mFunctionHooks[$name] ) ) {
			return true;
		} else {
			$this->recorder->warning( "   This test suite requires the '$name' function " .
				"hook extension, skipping." );
			return false;
		}
	}

	/**
	 * Check if a transparent tag hook is installed
	 *
	 * @param string $name
	 * @return bool True if function hook is present
	 */
	public function requireTransparentHook( $name ) {
		global $wgParser;

		$wgParser->firstCallInit(); // make sure hooks are loaded.

		if ( isset( $wgParser->mTransparentTagHooks[$name] ) ) {
			return true;
		} else {
			$this->recorder->warning( "   This test suite requires the '$name' transparent " .
				"hook extension, skipping.\n" );
			return false;
		}
	}

	/**
	 * The ParserGetVariableValueTs hook, used to make sure time-related parser
	 * functions give a persistent value.
	 */
	static function getFakeTimestamp( &$parser, &$ts ) {
		$ts = 123; // parsed as '1970-01-01T00:02:03Z'
		return true;
	}
}
