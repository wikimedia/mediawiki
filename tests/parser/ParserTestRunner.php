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

use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Psr\Log\NullLogger;
use Wikimedia\Assert\Assert;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\SelserData;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\Ext\ExtensionModule;
use Wikimedia\Parsoid\ParserTests\Article as ParserTestArticle;
use Wikimedia\Parsoid\ParserTests\ParserHook as ParsoidParserHook;
use Wikimedia\Parsoid\ParserTests\RawHTML as ParsoidRawHTML;
use Wikimedia\Parsoid\ParserTests\StyleTag as ParsoidStyleTag;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestFileReader;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMDataUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @ingroup Testing
 */
class ParserTestRunner {

	/**
	 * MediaWiki core parser test files, paths
	 * will be prefixed with __DIR__ . '/'
	 *
	 * @var array
	 */
	private static $coreTestFiles = [
		'parserTests.txt',
		'pfeqParserTests.txt',
		'extraParserTests.txt',
		'legacyMediaParserTests.txt',
		'mediaParserTests.txt',
	];

	/**
	 * @var array The status of each setup function
	 */
	private $setupDone = [
		'staticSetup' => false,
		'perTestSetup' => false,
		'setupDatabase' => false,
		'setupUploads' => false,
	];

	/**
	 * @var array (CLI/Config) Options for the test runner
	 * See the constructor for documentation
	 */
	private $options;

	/**
	 * @var array set of requested test modes
	 */
	private $requestedTestModes;

	/**
	 * Our connection to the database
	 * @var IDatabase
	 */
	private $db;

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
	 * The name of the file backend to use, or false to use MockFileBackend.
	 * @var string|false
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
	 * Run disabled parser tests
	 * @var bool
	 */
	private $runDisabled;

	/**
	 * Disable parse on article insertion
	 * @var bool
	 */
	private $disableSaveParse;

	/**
	 * Reuse upload directory
	 * @var bool
	 */
	private $keepUploads;

	/** @var Title */
	private $defaultTitle;

	/**
	 * Did some Parsoid test pass where it was expected to fail?
	 * This can happen if the test failure is recorded in the -knownFailures.json file
	 * but the test result changed, or functionality changed that causes tests to pass.
	 * @var bool
	 */
	public $unexpectedTestPasses = false;

	/**
	 * Table name prefix.
	 */
	public const DB_PREFIX = 'parsertest_';

	/**
	 * Compute the set of valid test runner modes
	 *
	 * @return array
	 */
	public function getRequestedTestModes(): array {
		return $this->requestedTestModes;
	}

	/**
	 * @param TestRecorder $recorder
	 * @param array $options
	 *  - parsoid (bool) if true, run Parsoid tests
	 *  - testFile (string)
	 *      If set, the (Parsoid) test file to run tests from.
	 *      Currently, only used for CLI PHPUnit test runs
	 *      to avoid running every single test file out there.
	 *      Legacy parser test runs ignore this option.
	 *  - wt2html (bool) If true, run Parsoid wt2html tests
	 *  - wt2wt (bool) If true, run Parsoid wt2wt tests
	 *  - html2wt (bool) If true, run Parsoid html2wt tests
	 *  - html2html (bool) If true, run Parsoid html2html tests
	 *  - selser (bool/"noauto")
	 *      If true, run Parsoid auto-generated selser tests
	 *      If "noauto", run Parsoid manual edit selser tests
	 *  - numchanges (int) number of selser edit tests to generate
	 *  - changetree (array|null)
	 *      If not null, run a Parsoid selser edit test with this changetree
	 *  - updateKnownFailures (bool)
	 *      If true, *knownFailures.json files are updated
	 *  - norm (array)
	 *      An array of normalization functions to run on test output
	 *      to use in legacy parser test runs
	 *  - regex (string) Regex for filtering tests
	 *  - run-disabled (bool) If true, run disabled tests
	 *  - keep-uploads (bool) If true, reuse upload directory
	 *  - file-backend (string|bool)
	 *      If false, use MockFileBackend
	 *      Else name of the file backend to use
	 *  - disable-save-parse (bool) if true, disable parse on article insertion
	 *
	 * NOTE: At this time, Parsoid-specific test options are only handled
	 * in PHPUnit mode. A future patch will likely tweak some of this and
	 * support these flags no matter how this test runner is instantiated.
	 */
	public function __construct( TestRecorder $recorder, $options = [] ) {
		$this->recorder = $recorder;
		$this->options = $options + [
			'keep-uploads' => false,
			'file-backend' => false,
			'run-disabled' => false,
			'disable-save-parse' => false,
			'upload-dir' => null,
			'regex' => false,
			'norm' => [],
			// Parsoid-specific options
			'parsoid' => false,
			'knownFailures' => true,
			'updateKnownFailures' => false,
			'changetree' => null,
			// Options can also match those in ParserTestModes::TEST_MODES
			// but we don't need to initialize those here; they will be
			// accessed via $this->requestedTestModes instead.
		];
		// Requested test modes are used for Parsoid tests and ignored for
		// legacy parser tests.
		$this->requestedTestModes = ParserTestMode::requestedTestModes(
			$this->options
		);

		// @phan-suppress-next-line PhanEmptyForeach False positive
		foreach ( $this->options['norm'] as $func ) {
			if ( in_array( $func, [ 'removeTbody', 'trimWhitespace' ] ) ) {
				$this->normalizationFunctions[] = $func;
			} else {
				$this->recorder->warning(
					"Warning: unknown normalization option \"$func\"\n" );
			}
		}

		if ( $this->options['regex'] !== false ) {
			$this->regex = $this->options['regex'];
		} else {
			# Matches anything
			$this->regex = '//';
		}

		$this->keepUploads = (bool)$this->options['keep-uploads'];
		$this->fileBackendName = $this->options['file-backend'];
		$this->runDisabled = (bool)$this->options['run-disabled'];
		$this->disableSaveParse = (bool)$this->options['disable-save-parse'];
		$this->uploadDir = $this->options['upload-dir'];

		$this->defaultTitle = Title::newFromText( 'Parser test' );
	}

	/**
	 * @return array
	 */
	public function getOptions(): array {
		return $this->options;
	}

	/**
	 * Get list of filenames to extension and core parser tests
	 *
	 * @return array
	 */
	public static function getParserTestFiles() {
		global $wgParserTestFiles;

		// Add core test files
		$files = array_map( static function ( $item ) {
			return __DIR__ . "/$item";
		}, self::$coreTestFiles );

		// Plus legacy global files
		$files = array_merge( $files, $wgParserTestFiles );

		// Auto-discover extension parser tests
		$registry = ExtensionRegistry::getInstance();
		foreach ( $registry->getAllThings() as $info ) {
			$dir = dirname( $info['path'] ) . '/tests/parser';
			if ( !is_dir( $dir ) ) {
				continue;
			}
			$counter = 1;
			$dirIterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir )
			);
			foreach ( $dirIterator as $fileInfo ) {
				/** @var SplFileInfo $fileInfo */
				if ( str_ends_with( $fileInfo->getFilename(), '.txt' ) ) {
					$name = $info['name'] . '_' . $counter;
					while ( isset( $files[$name] ) ) {
						$counter++;
						$name = $info['name'] . '_' . $counter;
					}
					$files[$name] = $fileInfo->getPathname();
				}
			}
		}

		return array_unique( $files );
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
		$setup['wgMetaNamespace'] = "TestWiki";
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
		$setup['wgLocaltimezone'] = 'UTC';
		$setup['wgDisableLangConversion'] = false;
		$setup['wgDisableTitleConversion'] = false;
		$setup['wgUsePigLatinVariant'] = false;
		$reset = static function () {
			// Reset to follow changes to $wgDisable*Conversion
			MediaWikiServices::getInstance()->resetServiceForTesting( 'LanguageConverterFactory' );
		};

		// "extra language links"
		// see https://gerrit.wikimedia.org/r/111390
		$setup['wgExtraInterlanguageLinkPrefixes'] = [ 'mul' ];

		// Parsoid settings for testing
		$setup['wgParsoidSettings'] = [
			'nativeGalleryEnabled' => true,
		];

		// All FileRepo changes should be done here by injecting services,
		// there should be no need to change global variables.
		MediaWikiServices::getInstance()->disableService( 'RepoGroup' );
		MediaWikiServices::getInstance()->redefineService( 'RepoGroup',
			function () {
				return $this->createRepoGroup();
			}
		);
		$teardown[] = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'RepoGroup' );
		};

		// Set up null lock managers
		$setup['wgLockManagers'] = [ [
			'name' => 'fsLockManager',
			'class' => NullLockManager::class,
		], [
			'name' => 'nullLockManager',
			'class' => NullLockManager::class,
		] ];
		$reset = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'LockManagerGroupFactory' );
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// This allows article insertion into the prefixed DB
		$setup['wgDefaultExternalStore'] = false;

		// This might slightly reduce memory usage
		$setup['wgAdaptiveMessageCache'] = true;

		// This is essential and overrides disabling of database messages in TestSetup
		$setup['wgUseDatabaseMessages'] = true;
		$reset = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'MessageCache' );
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// It's not necessary to actually convert any files
		$setup['wgSVGConverter'] = 'null';
		$setup['wgSVGConverters'] = [ 'null' => 'echo "1">$output' ];

		// Fake constant timestamp
		MediaWikiServices::getInstance()->getHookContainer()->register(
			'ParserGetVariableValueTs',
			function ( $parser, &$ts ) {
				$ts = $this->getFakeTimestamp();
				return true;
			}
		);

		$teardown[] = static function () {
			MediaWikiServices::getInstance()->getHookContainer()->clear( 'ParserGetVariableValueTs' );
		};

		$this->appendNamespaceSetup( $setup, $teardown );

		// Set up interwikis and append teardown function
		$this->appendInterwikiSetup( $setup, $teardown );

		// Set up a mock MediaHandlerFactory
		MediaWikiServices::getInstance()->disableService( 'MediaHandlerFactory' );
		MediaWikiServices::getInstance()->redefineService(
			'MediaHandlerFactory',
			static function ( MediaWikiServices $services ) {
				$handlers = $services->getMainConfig()->get( MainConfigNames::ParserTestMediaHandlers );
				return new MediaHandlerFactory(
					new NullLogger(),
					$handlers
				);
			}
		);
		$teardown[] = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'MediaHandlerFactory' );
		};

		// SqlBagOStuff broke when using temporary tables on r40209 (T17892).
		// It seems to have been fixed since (r55079?), but regressed at some point before r85701.
		// This works around it for now...
		global $wgObjectCaches;
		$setup['wgObjectCaches'] = [ CACHE_DB => $wgObjectCaches['hash'] ] + $wgObjectCaches;
		if ( isset( ObjectCache::$instances[CACHE_DB] ) ) {
			$savedCache = ObjectCache::$instances[CACHE_DB];
			ObjectCache::$instances[CACHE_DB] = new HashBagOStuff;
			$teardown[] = static function () use ( $savedCache ) {
				ObjectCache::$instances[CACHE_DB] = $savedCache;
			};
		}

		$teardown[] = $this->executeSetupSnippets( $setup );

		// Schedule teardown snippets in reverse order
		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	private function appendNamespaceSetup( &$setup, &$teardown ) {
		// Add a namespace shadowing a interwiki link, to test
		// proper precedence when resolving links. (T53680)
		$setup['wgExtraNamespaces'] = [
			100 => 'MemoryAlpha',
			101 => 'MemoryAlpha_talk'
		];
		// Changing wgExtraNamespaces invalidates caches in NamespaceInfo and any live Language
		// object, both on setup and teardown
		$reset = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'MainConfig' );
			MediaWikiServices::getInstance()->resetServiceForTesting( 'NamespaceInfo' );
			MediaWikiServices::getInstance()->resetServiceForTesting( 'LanguageFactory' );
			MediaWikiServices::getInstance()->resetServiceForTesting( 'ContentLanguage' );
			MediaWikiServices::getInstance()->resetServiceForTesting( 'LinkCache' );
			MediaWikiServices::getInstance()->resetServiceForTesting( 'LanguageConverterFactory' );
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
				'wikiId' => WikiMap::getCurrentWikiId(),
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
			$class = $useConfig['class'];
			// @phan-suppress-next-line PhanInvalidFQSENInClasslike
			$backend = new $class( $useConfig );
		} else {
			# Replace with a mock. We do not care about generating real
			# files on the filesystem, just need to expose the file
			# informations.
			$backend = new MockFileBackend( [
				'name' => 'local-backend',
				'wikiId' => WikiMap::getCurrentWikiId()
			] );
		}

		$services = MediaWikiServices::getInstance();
		return new RepoGroup(
			[
				'class' => MockLocalRepo::class,
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => $backend
			],
			[],
			$services->getMainWANObjectCache(),
			$services->getMimeAnalyzer()
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
				$saved[$name] = $GLOBALS[$name] ?? null;
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
	 * @param ScopedCallback|null $nextTeardown A ScopedCallback to consume
	 * @return ScopedCallback
	 */
	protected function createTeardownObject(
		array $teardown, ?ScopedCallback $nextTeardown = null
	) {
		return new ScopedCallback( function () use ( $teardown, $nextTeardown ) {
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
	 * Ensure one of the given setup stages has been done, throw an exception otherwise.
	 * @param string $funcName
	 */
	protected function checkSetupDone( string $funcName ) {
		if ( !$this->setupDone[$funcName] ) {
			throw new MWException( "$funcName must be called before calling " . wfGetCaller() );
		}
	}

	/**
	 * Determine whether a particular setup function has been run
	 *
	 * @param string $funcName
	 * @return bool
	 */
	public function isSetupDone( $funcName ) {
		return $this->setupDone[$funcName] ?? false;
	}

	/**
	 * Insert hardcoded interwiki in the lookup table.
	 *
	 * This function insert a set of well known interwikis that are used in
	 * the parser tests. We use the $wgInterwikiCache mechanism to completely
	 * replace any other lookup.  (Note that the InterwikiLoadPrefix hook
	 * isn't used because it doesn't alter the result of
	 * Interwiki::getAllPrefixes() and so is incompatible with some users,
	 * including Parsoid.)
	 * @param array &$setup
	 * @param array &$teardown
	 */
	private function appendInterwikiSetup( &$setup, &$teardown ) {
		static $testInterwikis = [
			[
				'iw_prefix' => 'local',
				// This is a "local interwiki" (see wgLocalInterwikis elsewhere in this file)
				'iw_url' => 'http://example.org/wiki/$1',
				'iw_local' => 1,
			],
			// Local interwiki that matches a namespace name (T228616)
			[
				'iw_prefix' => 'project',
				// This is a "local interwiki" (see wgLocalInterwikis elsewhere in this file)
				'iw_url' => 'http://example.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'wikipedia',
				'iw_url' => 'http://en.wikipedia.org/wiki/$1',
				'iw_local' => 0,
			],
			[
				'iw_prefix' => 'meatball',
				// this has been updated in the live wikis, but the parser tests
				// expect the old value
				'iw_url' => 'http://www.usemod.com/cgi-bin/mb.pl?$1',
				'iw_local' => 0,
			],
			[
				'iw_prefix' => 'memoryalpha',
				'iw_url' => 'http://www.memory-alpha.org/en/index.php/$1',
				'iw_local' => 0,
			],
			[
				'iw_prefix' => 'zh',
				'iw_url' => 'http://zh.wikipedia.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'es',
				'iw_url' => 'http://es.wikipedia.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'fr',
				'iw_url' => 'http://fr.wikipedia.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'ru',
				'iw_url' => 'http://ru.wikipedia.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'mi',
				// This is a "local interwiki" (see wgLocalInterwikis elsewhere in this file)
				'iw_url' => 'http://example.org/wiki/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'mul',
				'iw_url' => 'http://wikisource.org/wiki/$1',
				'iw_local' => 1,
			],
			// Additions from Parsoid
			[
				'iw_prefix' => 'en',
				'iw_url' => '//en.wikipedia.org/wiki/$1',
				'iw_local' => 1
			],
			[
				'iw_prefix' => 'stats',
				'iw_url' => 'https://stats.wikimedia.org/$1',
				'iw_local' => 1,
			],
			[
				'iw_prefix' => 'gerrit',
				'iw_url' => 'https://gerrit.wikimedia.org/$1',
				'iw_local' => 1,
			],
			// Deliberately missing a $1 in the URL to exercise a common
			// misconfiguration.
			[
				'iw_prefix' => 'wikinvest',
				'iw_url' => 'https://meta.wikimedia.org/wiki/Interwiki_map/discontinued#Wikinvest',
				'iw_local' => 1,
			],
		];
		// When running from parserTests.php, database setup happens *after*
		// interwiki setup, and that changes the wiki id.  In order to avoid
		// breaking the interwiki cache, use 'global scope' for the interwiki
		// lookup.
		$GLOBAL_SCOPE = 2; // See docs for $wgInterwikiScopes
		$setup['wgInterwikiScopes'] = $GLOBAL_SCOPE;
		$setup['wgInterwikiCache'] =
			ClassicInterwikiLookup::buildCdbHash( $testInterwikis, $GLOBAL_SCOPE );
		$reset = static function () {
			// Reset the service in case any other tests already cached some prefixes.
			MediaWikiServices::getInstance()->resetServiceForTesting( 'InterwikiLookup' );
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		// This affects title normalization in links. It invalidates
		// MediaWikiTitleCodec objects.
		// These interwikis should have 'iw_url' that matches wgServer.
		$setup['wgLocalInterwikis'] = [ 'local', 'project', 'mi' ];
		$reset = function () {
			$this->resetTitleServices();
		};
		$setup[] = $reset;
		$teardown[] = $reset;
	}

	/**
	 * Reset the Title-related services that need resetting
	 * for each test
	 *
	 * @todo We need to reset all services on every test
	 */
	private function resetTitleServices() {
		$services = MediaWikiServices::getInstance();
		$services->resetServiceForTesting( 'TitleFormatter' );
		$services->resetServiceForTesting( 'TitleParser' );
		$services->resetServiceForTesting( '_MediaWikiTitleCodec' );
		$services->resetServiceForTesting( 'LinkRenderer' );
		$services->resetServiceForTesting( 'LinkRendererFactory' );
		$services->resetServiceForTesting( 'NamespaceInfo' );
		$services->resetServiceForTesting( 'SpecialPageFactory' );
	}

	/**
	 * Remove last character if it is a newline
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

		$teardownGuard = null;
		$teardownGuard = $this->setupDatabase( $teardownGuard );
		$teardownGuard = $this->staticSetup( $teardownGuard );
		$teardownGuard = $this->setupUploads( $teardownGuard );

		$this->recorder->start();
		try {
			$ok = true;

			foreach ( $filenames as $filename ) {
				$this->recorder->startSuite( $filename );
				if ( $this->options['parsoid'] ) {
					$ok = $this->runParsoidTests( $filename ) && $ok;
				} else {
					$ok = $this->runLegacyTests( $filename ) && $ok;
				}
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
	 * @param array $requirements
	 * @return bool
	 */
	public function meetsRequirements( $requirements ) {
		foreach ( $requirements as $requirement ) {
			$ok = true;
			switch ( $requirement['type'] ) {
				case 'hook':
					$ok = $this->requireHook( $requirement['name'] );
					break;
				case 'functionHook':
					$ok = $this->requireFunctionHook( $requirement['name'] );
					break;
			}
			if ( !$ok ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Run the legacy parser tests from a single file. staticSetup() and
	 * setupDatabase() must have been called already.
	 *
	 * @param string $filename Test file name
	 * @return bool True if passed all tests, false if any tests failed.
	 */
	public function runLegacyTests( string $filename ): bool {
		$mode = new ParserTestMode( 'legacy' );
		$testFileInfo = TestFileReader::read( $filename,
			static function ( $msg ) {
				wfDeprecatedMsg( $msg, '1.35', false, false );
			}
		);

		$this->checkSetupDone( 'staticSetup' );

		// If any requirements are not met, mark all tests from the file as skipped
		$skipMessage = $this->getFileSkipMessage( true, $testFileInfo->fileOptions, $filename );
		if ( $skipMessage !== null ) {
			foreach ( $testFileInfo->testCases as $test ) {
				$this->recorder->startTest( $test, $mode );
				$this->recorder->skipped( $test, $mode, $skipMessage );
			}
			return true;
		}

		// Add articles
		$teardown = $this->addArticles( $testFileInfo->articles );

		// Run tests
		$ok = true;
		$runner = $this;
		foreach ( $testFileInfo->testCases as $test ) {
			$result = $this->runTest( $test, $mode );
			$ok = $ok && $result->isSuccess();
		}

		// Clean up
		ScopedCallback::consume( $teardown );

		return $ok;
	}

	/**
	 * @param bool $isLegacy
	 * @param array $fileOptions
	 * @param string $filename
	 * @return string|null
	 */
	public function getFileSkipMessage( bool $isLegacy, array $fileOptions, string $filename ): ?string {
		$runnerOpts = $this->getOptions();
		// Verify minimum version #
		$testFormat = intval( $fileOptions['version'] ?? '1' );
		if ( $testFormat < 2 ) {
			throw new MWException(
				"$filename needs an update. Support for the parserTest v1 file format was removed in MediaWiki 1.36"
			);
		}

		// If any requirements are not met, mark all tests from the file as skipped
		if ( !(
			$isLegacy ||
			isset( $fileOptions['parsoid-compatible'] ) ||
			( $runnerOpts['parsoid'] ?? false )
		) ) {
			// Running files in Parsoid integrated mode is opt-in for now.
			return 'not compatible with Parsoid integrated mode';
		} elseif ( !$this->meetsRequirements( $fileOptions['requirements'] ?? [] ) ) {
			return 'required extension not enabled';
		} elseif ( ( $runnerOpts['testFile'] ?? $filename ) !== $filename ) {
			return 'Not the requested test file';
		} else {
			return null;
		}
	}

	public function getTestSkipMessage( ParserTest $test, ParserTestMode $mode ) {
			if ( $test->wikitext === null ) {
				// Note that /in theory/ we could have pure html2html tests
				// with no wikitext section, but /in practice/ all tests
				// include a wikitext section.
				$test->error( "Test lacks wikitext section", $test->testName );
			}
			// Skip disabled / filtered tests
			if ( isset( $test->options['disabled'] ) && !$this->runDisabled ) {
				return "Test disabled";
			}
			$testFilter = [ 'regex' => $this->regex ];
			if ( !$test->matchesFilter( $testFilter ) ) {
				return "Test doesn't match filter";
			}
			// Skip parsoid-only tests if running in a legacy test mode
			if ( $test->legacyHtml === null ) {
				// A Parsoid-only test should have one of the following sections
				if (
					isset( $test->sections['html/parsoid'] ) ||
					isset( $test->sections['html/parsoid+integrated'] ) ||
					isset( $test->sections['html/parsoid+standalone'] ) ||
					isset( $test->sections['wikitext/edited'] )
				) {
					if ( $mode->isLegacy() ) {
						// Not an error, just skip this test if we're in
						// legacy mode.
						return "Parsoid-only test";
					}
				} else {
					// This test lacks both a legacy html section and also
					// any parsoid-specific html or wikitext/edited section.
					$test->error( "Test lacks html section", $test->testName );
				}
			}
			return null;
	}

	/**
	 * Run the tests from a single file. staticSetup() and setupDatabase()
	 * must have been called already.
	 *
	 * @param string $filename Test file name
	 * @return bool True if passed all tests, false if any tests failed.
	 */
	public function runParsoidTests( string $filename ): bool {
		$testModes = $this->getRequestedTestModes();
		$skipMode = new ParserTestMode( $testModes[0] );
		$testFileInfo = TestFileReader::read( $filename,
			static function ( $msg ) {
				wfDeprecatedMsg( $msg, '1.35', false, false );
			}
		);

		$this->checkSetupDone( 'staticSetup' );

		// If any requirements are not met, mark all tests from the file as skipped
		$skipMessage = $this->getFileSkipMessage( false, $testFileInfo->fileOptions, $filename );
		if ( $skipMessage !== null ) {
			foreach ( $testFileInfo->testCases as $test ) {
				$this->recorder->startTest( $test, $skipMode );
				$this->recorder->skipped( $test, $skipMode, $skipMessage );
			}
			return true;
		}

		// Add articles
		$teardown = $this->addArticles( $testFileInfo->articles );

		// Run tests
		$ok = true;
		$runner = $this;
		$testFilter = [ 'regex' => $this->regex ];
		foreach ( $testFileInfo->testCases as $t ) {
			$t->testAllModes( $t->computeTestModes( $testModes ), $this->options,
				function ( ParserTest $test, string $modeStr, array $options ) use ( $runner, $t, &$ok ) {
					// $test could be a clone of $t
					// Ensure that updates to knownFailures in $test are reflected in $t
					$test->knownFailures = &$t->knownFailures;
					$mode = new ParserTestMode( $modeStr, $test->changetree );
					if ( $modeStr === 'selser' && $test->changetree === null ) {
						// This is an auto-edit test with either a CLI changetree
						// or a change tree that should be generated
						$mode = new ParserTestMode( 'selser-auto', json_decode( $runner->options['changetree'] ) );
						$result = $this->runTest( $test, $mode );

						// FIXME: Test.php in Parsoid doesn't know which tests are being
						// skipped for what reason. For now, prevent crashers on skipped tests
						// by matching expectations of Test.php::isDuplicateChangeTree(..)
						if ( $result->expected === 'SKIP' ) {
							// Make sure change tree is not null for skipped selser tests
							$test->changetree = [];
						}
					} else {
						$result = $this->runTest( $test, $mode );
					}
					$ok = $ok && $result->isSuccess();
				}
			);
		}

		if ( $this->options['updateKnownFailures'] ) {
			$this->updateKnownFailures( $testFileInfo );
		}

		// Clean up
		ScopedCallback::consume( $teardown );

		return $ok;
	}

	/**
	 * Update known failures JSON file for the parser tests file
	 * @param TestFileReader $testFileInfo
	 */
	public function updateKnownFailures( TestFileReader $testFileInfo ): void {
		$testKnownFailures = [];
		foreach ( $testFileInfo->testCases as $t ) {
			if ( $t->knownFailures && $t->testName ) {
				// @phan-suppress-next-line PhanTypeMismatchDimAssignment False positive
				$testKnownFailures[$t->testName] = $t->knownFailures;
				// FIXME: This reduces noise when updateKnownFailures is used
				// with a subset of test modes. But, this also mixes up the selser
				// test results with non-selser ones.
				// ksort( $testKnownFailures[$t->testName] );
			}
		}
		// Sort, otherwise, titles get added above based on the first
		// failing mode, which can make diffs harder to verify when
		// failing modes change.
		ksort( $testKnownFailures );
		$contents = FormatJson::encode( $testKnownFailures, "\t", FormatJson::ALL_OK ) . "\n";

		if ( file_exists( $testFileInfo->knownFailuresPath ) ) {
			$old = file_get_contents( $testFileInfo->knownFailuresPath );
		} else {
			$old = "";
		}

		if ( $testFileInfo->knownFailuresPath && $old !== $contents ) {
			$this->recorder->warning( "Updating known failures file: {$testFileInfo->knownFailuresPath}" );
			file_put_contents( $testFileInfo->knownFailuresPath, $contents );
		}
	}

	/**
	 * Shared code to initialize ParserOptions based on the $test object,
	 * used by both the legacy Parser and the Parsoid parser.
	 * @param ParserTest $test
	 * @param callable $parserOptionsCallback A callback to create the
	 *   initial ParserOptions object.  This allows for some minor
	 *   differences in how the legacy Parser and Parsoid create this.
	 * @return array An array of Title, ParserOptions, and integer revId.
	 */
	private function setupParserOptions( ParserTest $test, callable $parserOptionsCallback ) {
		$opts = $test->options;
		$context = RequestContext::getMain();
		$user = $context->getUser();
		$revId = 1337; // see Parser::getRevisionId()
		$title = isset( $opts['title'] )
			? Title::newFromText( $opts['title'] )
			: $this->defaultTitle;
		$wikitext = $test->wikitext;
		'@phan-var string $wikitext'; // assert that this is not null

		$options = $parserOptionsCallback(
			$context, $title, $revId, $wikitext
		);
		$options->setTimestamp( $this->getFakeTimestamp() );
		$options->setUserLang( $context->getLanguage() );

		if ( isset( $opts['lastsavedrevision'] ) ) {
			$content = new WikitextContent( $wikitext );
			$title = Title::newFromRow( (object)[
				'page_id' => 187,
				'page_len' => $content->getSize(),
				'page_latest' => 1337,
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
				'page_is_redirect' => 0
			] );

			$revRecord = new MutableRevisionRecord( $title );
			$revRecord->setContent( SlotRecord::MAIN, $content )
				->setUser( $user )
				->setTimestamp( strval( $this->getFakeTimestamp() ) )
				->setPageId( $title->getArticleID() )
				->setId( $title->getLatestRevID() );

			$oldCallback = $options->getCurrentRevisionRecordCallback();
			$options->setCurrentRevisionRecordCallback(
				static function ( Title $t, $parser = null ) use ( $title, $revRecord, $oldCallback ) {
					if ( $t->equals( $title ) ) {
						return $revRecord;
					} else {
						return $oldCallback( $t, $parser );
					}
				}
			);
		}

		if ( isset( $opts['maxincludesize'] ) ) {
			$options->setMaxIncludeSize( $opts['maxincludesize'] );
		}
		if ( isset( $opts['maxtemplatedepth'] ) ) {
			$options->setMaxTemplateDepth( $opts['maxtemplatedepth'] );
		}

		return [ $title, $options, $revId ];
	}

	/**
	 * Get a Parser object
	 *
	 * @return Parser
	 */
	public function getParser() {
		$parserFactory = MediaWikiServices::getInstance()->getParserFactory();
		$parser = $parserFactory->create(); // A fresh parser object.
		ParserTestParserHook::setup( $parser );
		return $parser;
	}

	/**
	 * Run a given wikitext input through either the legacy wiki parser
	 * or Parsoid, depending on the given test mode, and compare the
	 * output against the expected results.
	 *
	 * @param ParserTest $test The test parameters
	 * @param ParserTestMode $mode The test mode
	 * @return ParserTestResult The test results.
	 */
	public function runTest( ParserTest $test, ParserTestMode $mode ): ParserTestResult {
		if ( $this->getTestSkipMessage( $test, $mode ) ) {
			return new ParserTestResult( $test, $mode, 'SKIP', 'SKIP' );
		}
		$this->recorder->startTest( $test, $mode );
		$result = $mode->isLegacy() ?
				$this->runLegacyTest( $test, $mode ) :
				$this->runParsoidTest( $test, $mode );
		if ( $result === false ) {
			$this->recorder->skipped( $test, $mode, 'SKIP' );
			return new ParserTestResult( $test, $mode, 'SKIP', 'SKIP' );
		} else {
			$this->recorder->record( $result );
			return $result;
		}
	}

	/**
	 * Run a given wikitext input through a freshly-constructed instance
	 * of the legacy wiki parser, and compare the output against the expected
	 * results.
	 *
	 * Prints status and explanatory messages to stdout.
	 *
	 * staticSetup() and setupWikiData() must be called before this function
	 * is entered.
	 *
	 * @param ParserTest $test The test parameters
	 * @param ParserTestMode $mode The test mode
	 * @return ParserTestResult|false false if skipped
	 */
	public function runLegacyTest( ParserTest $test, ParserTestMode $mode ) {
		$desc = ( $test->comment ?? '' ) . $test->testName;
		wfDebug( __METHOD__ . ": running $desc" );
		$opts = $test->options;
		if ( isset( $opts['preprocessor'] ) && $opts['preprocessor'] !== 'Preprocessor_Hash' ) {
			wfDeprecated( 'preprocessor=Preprocessor_DOM', '1.36' );
			return false; // Skip test.
		}
		$teardownGuard = $this->perTestSetup( $test );
		[ $title, $options, $revId ] = $this->setupParserOptions(
			$test,
			static function ( $context, $title, $revId, $wikitext ) {
				return ParserOptions::newFromContext( $context );
			}
		);

		$local = isset( $opts['local'] );
		$parser = $this->getParser();

		if ( isset( $opts['styletag'] ) ) {
			// For testing the behavior of <style> (including those deduplicated
			// into <link> tags), add tag hooks to allow them to be generated.
			$parser->setHook( 'style', static function ( $content, $attributes, $parser ) {
				$marker = Parser::MARKER_PREFIX . '-style-' . md5( $content ) . Parser::MARKER_SUFFIX;
				// @phan-suppress-next-line SecurityCheck-XSS
				$parser->getStripState()->addNoWiki( $marker, $content );
				return Html::inlineStyle( $marker, 'all', $attributes );
			} );
			$parser->setHook( 'link', static function ( $content, $attributes, $parser ) {
				return Html::element( 'link', $attributes );
			} );
		}

		$wikitext = $test->wikitext;
		'@phan-var string $wikitext'; // assert that this is not null
		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $wikitext, $title, $options->getUserIdentity(), $options );
			$output = $parser->getOutput();
		} elseif ( isset( $opts['msg'] ) ) {
			$out = $parser->transformMsg( $wikitext, $options, $title );
		} elseif ( isset( $opts['section'] ) ) {
			$section = $opts['section'];
			$out = $parser->getSection( $wikitext, $section );
		} elseif ( isset( $opts['replace'] ) ) {
			$section = $opts['replace'][0];
			$replace = $opts['replace'][1];
			$out = $parser->replaceSection( $wikitext, $section, $replace );
		} elseif ( isset( $opts['comment'] ) ) {
			$out = Linker::formatComment( $wikitext, $title, $local );
		} elseif ( isset( $opts['preload'] ) ) {
			$out = $parser->getPreloadText( $wikitext, $title, $options );
		} else {
			$output = $parser->parse( $wikitext, $title, $options, true, true, $revId );
			$out = $output->getText( [
				'allowTOC' => !isset( $opts['notoc'] ),
				'unwrap' => !isset( $opts['wrap'] ),
			] );
			$out = preg_replace( '/\s+$/', '', $out );

			$this->addParserOutputInfo( $out, $output, $opts, $title );
		}

		if ( isset( $output ) && isset( $opts['showflags'] ) ) {
			$actualFlags = [];
			foreach ( ParserOutputFlags::cases() as $name ) {
				if ( $output->getOutputFlag( $name ) ) {
					$actualFlags[] = $name;
				}
			}
			sort( $actualFlags );
			$out .= "\nflags=" . implode( ', ', $actualFlags );
			# In 1.21 we deprecated the use of arbitrary keys for
			# ParserOutput::setFlag() by extensions; if we find anyone
			# still doing that complain about it.
			$oldFlags = array_diff_key(
				TestingAccessWrapper::newFromObject( $output )->mFlags,
				array_fill_keys( ParserOutputFlags::cases(), true )
			);
			if ( $oldFlags ) {
				wfDeprecated( 'Arbitrary flags in ParserOutput', '1.39' );
			}
		}

		ScopedCallback::consume( $teardownGuard );

		$expected = $test->legacyHtml;
		'@phan-var string $expected'; // assert that this is not null
		if ( count( $this->normalizationFunctions ) ) {
			$expected = ParserTestResultNormalizer::normalize(
				$expected, $this->normalizationFunctions );
			$out = ParserTestResultNormalizer::normalize( $out, $this->normalizationFunctions );
		}

		$testResult = new ParserTestResult( $test, $mode, $expected, $out );
		return $testResult;
	}

	/**
	 * Add information from the parser output to the result string
	 *
	 * @param string &$out
	 * @param ParserOutput $output
	 * @param array $opts
	 * @param Title $title
	 */
	private function addParserOutputInfo( &$out, ParserOutput $output, array $opts, Title $title ) {
		if ( isset( $opts['showtitle'] ) ) {
			if ( $output->getTitleText() ) {
				$titleText = $output->getTitleText();
			} else {
				$titleText = $title->getPrefixedText();
			}

			$out = "$titleText\n$out";
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

		if ( isset( $opts['extension'] ) ) {
			foreach ( explode( ',', $opts['extension'] ) as $ext ) {
				if ( $out !== '' ) {
					$out .= "\n";
				}
				$out .= "extension[$ext]=" .
					json_encode(
						$output->getExtensionData( $ext ),
						JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
					);
			}
		}

		if ( isset( $opts['property'] ) ) {
			foreach ( explode( ',', $opts['property'] ) as $prop ) {
				if ( $out !== '' ) {
					$out .= "\n";
				}
				$out .= "property[$prop]=" .
					( $output->getPageProperty( $prop ) ?? '' );
			}
		}
	}

	/**
	 * This processes test results and updates the known failures info for the test
	 *
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @param string|null|callable $rawExpected
	 *  If null, indicates that the normalizer should look for the expected
	 *  output in the $test object.
	 * @param string $rawActual
	 * @param callable $normalizer normalizer of expected & actual output strings
	 * @return ParserTestResult|false
	 */
	private function processResults(
		ParserTest $test, ParserTestMode $mode, $rawExpected, string $rawActual, callable $normalizer
	) {
		if ( $mode->isCachingMode() ) {
			return false;
		}

		if ( !$this->options['knownFailures'] ) {
			$expectedFailure = null;
		} else {
			$expectedFailure = $test->knownFailures["$mode"] ?? null;
		}

		$expectedToFail = $expectedFailure !== null;
		$knownFailureChanged = $expectedToFail && $expectedFailure !== $rawActual;

		if ( is_callable( $rawExpected ) ) {
			$rawExpected = $rawExpected();
		}
		list( $actual, $expected ) = $normalizer( $rawActual, $rawExpected, false /* standalone */ );
		$passed = $actual === $expected;

		$unexpectedPass = $expectedToFail && $passed;
		$unexpectedFail = !$expectedToFail && !$passed;

		if ( $unexpectedPass ) {
			$this->recorder->warning( "{$test->testName}: $mode: EXPECTED TO FAIL, BUT PASSED!" );
		}

		if ( $this->options['updateKnownFailures'] && (
			$knownFailureChanged || $unexpectedFail || $unexpectedPass
		) ) {
			if ( $unexpectedPass ) {
				unset( $test->knownFailures["$mode"] );
			} else {
				if ( $knownFailureChanged ) {
					$this->recorder->warning( "{$test->testName}: $mode: KNOWN FAILURE CHANGED!" );
				}
				$test->knownFailures["$mode"] = $rawActual;
			}
		}

		if ( $unexpectedPass ) {
			if ( !$this->options['updateKnownFailures'] ) {
				$this->unexpectedTestPasses = true;
			}
		} elseif ( $expectedToFail && !$knownFailureChanged ) {
			// Don't flag failures noisily when nothing really changed
			$expected = $expectedFailure;
			$actual = $rawActual;
		}

		return new ParserTestResult( $test, $mode, $expected, $actual );
	}

	private function fetchCachedDoc( $parsoid, $pageConfig, $test ) {
		// If cachedBODYstr is not already set, create it here.
		if ( $test->cachedBODYstr === null ) {
			$this->wt2html( $parsoid, $pageConfig, $test, new ParserTestMode( 'cache' ) );
		}
		$doc = DOMUtils::parseHTML( $test->cachedBODYstr, true );
		// XXX applyChanges calls functions in WTUtils which assume we
		// have a DataBag associated with the document.
		DOMDataUtils::prepareDoc( $doc );
		return $doc;
	}

	private function fetchCachedWt( $parsoid, $pageConfig, $test ) {
		// If cachedWTstr is not already set, set it here.
		if ( ( $test->cachedWTstr ?? null ) === null ) {
			$this->html2wt( $parsoid, $pageConfig, $test, new ParserTestMode( 'cache' ) );
		}
		return $test->cachedWTstr;
	}

	/**
	 * Run wt2html on the test
	 *
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function wt2html( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		Assert::invariant(
			$test->wikitext !== null,
			"All tests include a wikitext section"
		);

		if ( $html === null && !$mode->isCachingMode() ) {
			// Nothing to test, but if mode is 'cache' we're executing this
			// in order to set cachedBODYstr (say, for a wt2wt test)
			return false;
		}

		$origOut = $parsoid->wikitext2html( $pageConfig, [
			'body_only' => true,
			'wrapSections' => $test->options['parsoid']['wrapSections'] ?? false,
		] );
		$test->cachedBODYstr = $origOut;

		return $this->processResults(
			// Passing 'null' for expected output here asks normalizeHTML
			// to look it up for us, based on parsoid-only & standalone status
			$test, $mode, null, $origOut, [ $test, "normalizeHTML" ]
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function wt2wt( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		if ( $html === null ) {
			// We currently don't run this in standalone mode.
			// The expectation is to add html/parsoid sections
			// if we want to run these tests.
			return false;
		}

		Assert::invariant(
			$test->wikitext !== null,
			"All tests include a wikitext section"
		);

		// Handle a 'changes' option if present.
		$doc = $this->fetchCachedDoc( $parsoid, $pageConfig, $test );
		$testManualChanges = $test->options['parsoid']['changes'] ?? null;
		if ( $testManualChanges ) {
			$test->applyManualChanges( $doc );
		}

		$origWT = $parsoid->dom2wikitext( $pageConfig, $doc );
		if ( isset( $test->options['parsoid']['changes'] ) ) {
			$expectedWT = $test->sections['wikitext/edited'];
		} else {
			$expectedWT = $test->wikitext;
		}

		return $this->processResults(
			$test, $mode, $expectedWT, $origWT, [ $test, "normalizeWT" ]
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function html2wt( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		if ( $html === null ) {
			// Although ::getTestSkipMessage checks that parsoid-only tests
			// have html sections, this test would have (eg) only an
			// html/parsoid+standalone section, and we're executing in
			// integrated mode.
			return false; // Skip. Nothing to test.
		}
		if ( $test->wikitext === null && !$mode->isCachingMode() ) {
			// If mode is 'cache' we're executing this in order to
			// set cachedWTstr.
			throw new MWException( 'Error in the test setup' );
		}

		$test->cachedWTstr = $origWT = $parsoid->html2wikitext( $pageConfig, $html );

		return $this->processResults(
			$test, $mode, $test->wikitext ?? '', $origWT, [ $test, "normalizeWT" ]
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function html2html( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		if ( $html === null ) {
			// Although ::getTestSkipMessage checks that parsoid-only tests
			// have html sections, this test would have (eg) only an
			// html/parsoid+standalone section, and we're executing in
			// integrated mode.
			return false; // Skip. Nothing to test.
		}

		$wt = $this->fetchCachedWt( $parsoid, $pageConfig, $test );

		// Construct a fresh PageConfig object with $wt
		$oldWt = $test->wikitext;
		$test->wikitext = $wt;
		list( $pageConfig ) = $this->setupParsoidTransform( $test );
		$test->wikitext = $oldWt;

		$newHtml = $parsoid->wikitext2html( $pageConfig, [
			'body_only' => true,
			'wrapSections' => $test->options['parsoid']['wrapSections'] ?? false,
		] );

		return $this->processResults(
			$test, $mode, $test->cachedNormalizedHTML, $newHtml, [ $test, "normalizeHTML" ]
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function selser( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		if ( $html === null ) {
			// We currently don't run this in standalone mode.
			// The expectation is to add html/parsoid sections
			// if we want to run these tests.
			return false;
		}

		Assert::invariant(
			$test->wikitext !== null,
			"All tests include a wikitext section"
		);

		if ( $test->changetree === [ 'manual' ] && !isset( $test->options['parsoid']['changes'] ) ) {
			throw new MWException( 'Error in the test setup!' );
		}

		// Apply edits to the HTML.
		// Always serialize to string and reparse before passing to selser/wt2wt.
		$doc = $this->fetchCachedDoc( $parsoid, $pageConfig, $test );
		if ( $test->changetree === [ 'manual' ] ) {
			$test->applyManualChanges( $doc );
			$expectedWT = $test->sections['wikitext/edited'];
			$expectedFailure = $test->knownFailures["$mode"] ?? null;
		} else {
			// $test->changetree === [ 5 ]
			$changetree = $test->changetree;
			'@phan-var array $changetree'; // assert that this is not null
			$test->applyChanges( [], $doc, $changetree );
			$expectedWT = $test->wikitext;
			$expectedFailure = $test->knownFailures["$mode"] ?? null;
		}
		$editedHTML = ContentUtils::toXML( DOMCompat::getBody( $doc ) );

		// Run selser on edited doc
		$selserData = new SelserData( $test->wikitext, $test->cachedBODYstr );
		$origWT = $parsoid->html2wikitext( $pageConfig, $editedHTML, [], $selserData );

		if ( $test->changetree === [ 5 ] ) {
			$origWT = preg_replace( '/<!--' . ParserTest::STATIC_RANDOM_STRING . '-->/', '', $origWT );
		}

		return $this->processResults(
			$test, $mode, $expectedWT, $origWT, [ $test, "normalizeWT" ]
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @param Document $doc
	 * @return array
	 */
	private function runSelserEditTest(
		Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode, Document $doc
	): array {
		$test->applyChanges( [], $doc, $test->changetree );
		$editedHTML = ContentUtils::toXML( DOMCompat::getBody( $doc ) );

		// Run selser on edited doc
		$selserData = new SelserData( $test->wikitext, $test->cachedBODYstr );
		$origWT = $parsoid->html2wikitext( $pageConfig, $editedHTML, [], $selserData );

		$ptResult = $this->processResults(
			$test, $mode,
			static function () use ( $parsoid, $pageConfig, $editedHTML ): string {
				return $parsoid->html2wikitext( $pageConfig, $editedHTML );
			},
			$origWT, [ $test, "normalizeWT" ]
		);

		return [ $ptResult->actual, $ptResult->expected ];
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function selserAutoEdit( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		$html = $test->sections['html/parsoid+integrated'] ?? $test->parsoidHtml;
		if ( $html === null ) {
			// We currently don't run this in standalone mode.
			// The expectation is to add html/parsoid sections
			// if we want to run these tests.
			return false;
		}
		Assert::invariant(
			$test->wikitext !== null,
			"All tests include a wikitext section"
		);

		// Apply edits to the HTML.
		// Always serialize to string and reparse before passing to selser/wt2wt.
		$doc = $this->fetchCachedDoc( $parsoid, $pageConfig, $test );
		if ( !$test->changetree ) {
			$test->changetree = $test->generateChanges( $doc );
			if ( !$test->changetree ) {
				// No more changes to make
				return false;
			}
			$mode = new ParserTestMode( 'selser', $test->changetree );
		}
		list( $out, $expected ) = $this->runSelserEditTest( $parsoid, $pageConfig, $test, $mode, $doc );
		return new ParserTestResult(
			$test,
			$mode,
			$expected,
			$out
		);
	}

	/**
	 * @param Parsoid $parsoid
	 * @param PageConfig $pageConfig
	 * @param ParserTest $test
	 * @param ParserTestMode $mode
	 * @return ParserTestResult|false false if skipped
	 */
	private function selserAutoEditComposite( Parsoid $parsoid, PageConfig $pageConfig, ParserTest $test, ParserTestMode $mode ) {
		Assert::invariant(
			$test->wikitext !== null,
			"All tests include a wikitext section"
		);
		$runnerOpts = $this->getOptions();

		if ( $test->changetree ) {
			// Apply edits to the HTML.
			// Always serialize to string and reparse before passing to selser/wt2wt.
			$doc = $this->fetchCachedDoc( $parsoid, $pageConfig, $test );
			Assert::invariant(
				$mode->changetree === $test->changetree,
				"changetree should be consistent with mode"
			);
			list( $out, $expected ) = $this->runSelserEditTest( $parsoid, $pageConfig, $test, $mode, $doc );
			return new ParserTestResult( $test, $mode, $expected, $out );
		} else {
			// this mode is a composite of multiple selser tests
			$mode = new ParserTestMode( "selserAutoEdits" );
			$numChanges = $runnerOpts['numchanges'] ?? 20; // default in Parsoid
			$results = [];
			$bufOut = "";
			$bufExpected = "";
			for ( $i = 0; $i < $numChanges; $i++ ) {
				// Apply edits to the HTML.
				// Always serialize to string and reparse before passing to selser/wt2wt.
				$doc = $this->fetchCachedDoc( $parsoid, $pageConfig, $test );
				$test->seed = $i . '';
				$test->changetree = $test->generateChanges( $doc );
				if ( $test->changetree ) {
					// new mode with the generated changetree
					$nmode = new ParserTestMode( 'selser', $test->changetree );
					list( $out, $expected ) = $this->runSelserEditTest( $parsoid, $pageConfig, $test, $nmode, $doc );
					$testTitle = "TEST: {$test->testName} ($nmode)\n";
					$bufOut .= $testTitle;
					$bufExpected .= $testTitle;
					$bufOut .= "RESULT: $out\n";
					$bufExpected .= "RESULT: $expected\n";
				}
				// $test->changetree can be [] which is a NOP for testing
				// but not a NOP for duplicate change tree tests.
				if ( $test->isDuplicateChangeTree( $test->changetree ) ) {
					// Once we get a duplicate change tree, we can no longer
					// generate and run new tests. So, be done now!
					break;
				} else {
					$test->selserChangeTrees[$i] = $test->changetree;
				}
			}
			return new ParserTestResult( $test, $mode, $bufExpected, $bufOut );
		}
	}

	private function setupParsoidTransform( ParserTest $test ): array {
		$services = MediaWikiServices::getInstance();
		$pageConfigFactory = $services->get( 'ParsoidPageConfigFactory' );
		$pageConfig = null;
		$runner = $this;
		[ $title, $options, $revId ] = $this->setupParserOptions(
			$test,
			static function ( $context, $title, $revId, $wikitext ) use ( $runner, $pageConfigFactory, &$pageConfig ) {
				$user = $context->getUser();
				$content = new WikitextContent( $wikitext );
				$title = Title::newFromRow( (object)[
					'page_id' => 187,
					'page_len' => $content->getSize(),
					'page_latest' => 1337,
					'page_namespace' => $title->getNamespace(),
					'page_title' => $title->getDBkey(),
					'page_is_redirect' => 0
				] );
				$revRecord = new MutableRevisionRecord( $title );
				$revRecord->setContent( SlotRecord::MAIN, $content )
					->setUser( $user )
					->setTimestamp( strval( $runner->getFakeTimestamp() ) )
					->setPageId( $title->getArticleID() )
					->setId( $title->getLatestRevID() );
				$pageConfig = $pageConfigFactory->create(
					$title, $user, $revRecord, null, $context->getLanguage()->getCode()
				);
				return $pageConfig->getParserOptions();
			} );
		return [ $pageConfig, $title, $options, $revId ];
	}

	/**
	 * Helper function to register a Parsoid extension module in such a
	 * way that it can be cleaned up after the test is complete.
	 * @param SiteConfig $siteConfig
	 * @param class-string<ExtensionModule> $moduleName
	 * @return callable
	 */
	private static function registerExtensionModule( SiteConfig $siteConfig, string $moduleName ): callable {
		$id = $siteConfig->registerExtensionModule( $moduleName );
		return static function () use ( $siteConfig, $id ) {
			$siteConfig->unregisterExtensionModule( $id );
		};
	}

	/**
	 * Run a given wikitext input through a freshly-constructed Parsoid parser,
	 * running in 'integrated' mode, and compare the output against the
	 * expected results.
	 *
	 * Prints status and explanatory messages to stdout.
	 *
	 * staticSetup() and setupWikiData() must be called before this function
	 * is entered.
	 *
	 * @param ParserTest $test The test parameters:
	 * @param ParserTestMode $mode Parsoid test mode to run (including specific
	 *  changetree to run selser test in, if applicable)
	 *
	 * @return ParserTestResult|false false if skipped
	 */
	public function runParsoidTest( ParserTest $test, ParserTestMode $mode ) {
		wfDebug( __METHOD__ . ": running {$test->testName} [$mode]" );

		// Skip deprecated preprocessor tests
		// @phan-suppress-next-line PhanImpossibleCondition Other preprocessor are deprecated, see runTest
		if ( isset( $opts['preprocessor'] ) && $opts['preprocessor'] !== 'Preprocessor_Hash' ) {
			return false;
		}

		// Skip tests targetting features Parsoid doesn't (yet) support
		// @todo T270312
		if ( isset( $opts['styletag'] ) || isset( $opts['pst'] ) ||
			isset( $opts['msg'] ) || isset( $opts['section'] ) ||
			isset( $opts['replace'] ) || isset( $opts['comment'] ) ||
			isset( $opts['preload'] ) || isset( $opts['showtitle'] ) ||
			isset( $opts['showindicators'] ) || isset( $opts['ill'] ) ||
			isset( $opts['cat'] ) || isset( $opts['showflags'] )
		) {
			return false;
		}

		$teardownGuard = $this->perTestSetup( $test );
		$teardown = [];

		// Register any special extensions required by this test case
		$services = MediaWikiServices::getInstance();
		$siteConfig = $services->get( 'ParsoidSiteConfig' );
		$teardown[] = self::registerExtensionModule( $siteConfig, ParsoidParserHook::class );
		if ( ( $test->options['wgrawhtml'] ?? null ) === '1' ) {
			$teardown[] = self::registerExtensionModule( $siteConfig, ParsoidRawHTML::class );
		}
		if ( isset( $test->options['styletag'] ) ) {
			$teardown[] = self::registerExtensionModule( $siteConfig, ParsoidStyleTag::class );
		}
		// unregister these after this test
		$teardownGuard = $this->createTeardownObject( $teardown, $teardownGuard );

		// Create the Parsoid object. (This is cheap, since the SiteConfig
		// and DataAccess are cached by the ServiceContainer.)
		$dataAccess = $services->get( 'ParsoidDataAccess' );
		$parsoid = new Parsoid( $siteConfig, $dataAccess );

		list( $pageConfig ) = $this->setupParsoidTransform( $test );
		switch ( $mode->mode ) {
			case 'wt2html':
			case 'wt2html+integrated':
				$res = $this->wt2html( $parsoid, $pageConfig, $test, $mode );
				break;

			case 'wt2wt':
				$res = $this->wt2wt( $parsoid, $pageConfig, $test, $mode );
				break;

			case 'html2wt':
				$res = $this->html2wt( $parsoid, $pageConfig, $test, $mode );
				break;

			case 'html2html':
				$res = $this->html2html( $parsoid, $pageConfig, $test, $mode );
				break;

			case 'selser':
				$test->changetree = $mode->changetree;
				$res = $this->selser( $parsoid, $pageConfig, $test, $mode );
				$test->changetree = null; // Reset after each selser test
				break;

			case 'selser-auto-composite':
				$test->changetree = $mode->changetree;
				$res = $this->selserAutoEditComposite( $parsoid, $pageConfig, $test, $mode );
				$test->changetree = null; // Reset after each selser test
				break;

			case 'selser-auto':
				$test->changetree = $mode->changetree;
				$res = $this->selserAutoEdit( $parsoid, $pageConfig, $test, $mode );
				// Don't reset changetree here -- it is used to detect duplicate trees
				// and stop selser test generation in Test.php::testAllModes
				break;

			default:
				// Unsupported Mode
				$res = false;
				break;
		}

		ScopedCallback::consume( $teardownGuard );
		return $res;
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
		return $opts[$key] ?? $default;
	}

	/**
	 * Do any required setup which is dependent on test options.
	 *
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param ParserTest $test Test info supplied by TestFileReader
	 * @param callable|null $nextTeardown
	 * @return ScopedCallback
	 */
	public function perTestSetup( ParserTest $test, $nextTeardown = null ) {
		$teardown = [];

		$this->checkSetupDone( 'setupDatabase' );
		$teardown[] = $this->markSetupDone( 'perTestSetup' );

		$opts = $test->options;

		// Find out values for some special options.
		$langCode =
			self::getOptionValue( 'language', $opts, 'en' );
		$variant =
			self::getOptionValue( 'variant', $opts, false );
		$maxtoclevel =
			self::getOptionValue( 'wgMaxTocLevel', $opts, 999 );
		$linkHolderBatchSize =
			self::getOptionValue( 'wgLinkHolderBatchSize', $opts, 1000 );

		// Default to fallback skin, but allow it to be overridden
		$skin = self::getOptionValue( 'skin', $opts, 'fallback' );

		$setup = [
			'wgEnableUploads' => self::getOptionValue( 'wgEnableUploads', $opts, true ),
			'wgLanguageCode' => $langCode,
			'wgRawHtml' => self::getOptionValue( 'wgRawHtml', $opts, false ),
			'wgNamespacesWithSubpages' => array_fill_keys(
				MediaWikiServices::getInstance()->getNamespaceInfo()->getValidNamespaces(),
				isset( $opts['subpage'] )
			),
			'wgMaxTocLevel' => $maxtoclevel,
			'wgAllowExternalImages' => self::getOptionValue( 'wgAllowExternalImages', $opts, true ),
			'wgThumbLimits' => [ 0, 0, 0, 0, 0, self::getOptionValue( 'thumbsize', $opts, 180 ) ],
			'wgDefaultLanguageVariant' => $variant,
			'wgLinkHolderBatchSize' => $linkHolderBatchSize,
			// Set as a JSON object like:
			// wgEnableMagicLinks={"ISBN":false, "PMID":false, "RFC":false}
			'wgEnableMagicLinks' => self::getOptionValue( 'wgEnableMagicLinks', $opts, [] )
				+ [ 'ISBN' => true, 'PMID' => true, 'RFC' => true ],
			// Test with legacy encoding by default until HTML5 is very stable and default
			'wgFragmentMode' => [ 'legacy' ],
		];

		$nonIncludable = self::getOptionValue( 'wgNonincludableNamespaces', $opts, false );
		if ( $nonIncludable !== false ) {
			$setup['wgNonincludableNamespaces'] = [ $nonIncludable ];
		}

		if ( $test->config ) {
			foreach ( $test->config as $var => $value ) {
				$setup[$var] = $value;
			}
		}

		/** @since 1.20 */
		Hooks::runner()->onParserTestGlobals( $setup );

		// Set content language. This invalidates the magic word cache and title services
		// In addition the ParserFactory needs to be recreated as well.
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $langCode );
		$setup[] = static function () use ( $lang ) {
			MediaWikiServices::getInstance()->disableService( 'ContentLanguage' );
			MediaWikiServices::getInstance()->redefineService(
				'ContentLanguage',
				static function () use ( $lang ) {
					return $lang;
				}
			);
		};
		$teardown[] = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'ContentLanguage' );
		};
		$reset = function () {
			$mwServices = MediaWikiServices::getInstance();
			$mwServices->resetServiceForTesting( 'MagicWordFactory' );
			$this->resetTitleServices();
			$mwServices->resetServiceForTesting( 'ParserFactory' );
			// If !!config touches $wgUsePigLatinVariant or the local wiki
			// defaults to $wgUsePigLatinVariant=true, these need to be reset
			$mwServices->resetServiceForTesting( 'LanguageConverterFactory' );
			$mwServices->resetServiceForTesting( 'LanguageFactory' );
			$mwServices->resetServiceForTesting( 'LanguageNameUtils' );
			// The SiteConfig depends on the content language as well
			// as the config vars in SiteConfig::CONSTRUCTOR_OPTIONS,
			// so reset it as well.
			// T310283: be more selective about resetting SiteConfig if
			// performance is a concern.
			$mwServices->resetServiceForTesting( 'ParsoidSiteConfig' );
			// DataAccess depends on config vars, so reset it
			$mwServices->resetServiceForTesting( 'ParsoidDataAccess' );
		};
		$setup[] = $reset;
		$teardown[] = $reset;

		$userOptionsManager = MediaWikiServices::getInstance()->getUserOptionsManager();
		// Make a user object with the same language
		$user = new User;
		$userOptionsManager->setOption( $user, 'language', $langCode );
		$setup['wgLang'] = $lang;
		$setup['wgUser'] = $user;

		// And put both user and language into the context
		$context = RequestContext::getMain();
		$context->setUser( $user );
		$context->setLanguage( $lang );
		// And the skin!
		$oldSkin = $context->getSkin();
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$context->setSkin( $skinFactory->makeSkin( $skin ) );
		$context->setOutput( new OutputPage( $context ) );
		$setup['wgOut'] = $context->getOutput();
		$teardown[] = static function () use ( $context, $oldSkin ) {
			// Clear language conversion tables
			$wrapper = TestingAccessWrapper::newFromObject(
				MediaWikiServices::getInstance()->getLanguageConverterFactory()
					->getLanguageConverter( $context->getLanguage() )
			);
			@$wrapper->reloadTables();

			// Reset context to the restored globals
			$context->setUser( StubGlobalUser::getRealUser( $GLOBALS['wgUser'] ) );
			$context->setSkin( $oldSkin );
			$context->setOutput( $GLOBALS['wgOut'] );
		};

		$teardown[] = $this->executeSetupSnippets( $setup );

		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	/**
	 * Set up temporary DB tables.
	 *
	 * For best performance, call this once only for all tests. However, it can
	 * be called at the start of each test if more isolation is desired.
	 *
	 *
	 * Do not call this function from a MediaWikiIntegrationTestCase subclass,
	 * since MediaWikiIntegrationTestCase does its own DB setup.
	 *
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param ScopedCallback|null $nextTeardown The next teardown object
	 * @return ScopedCallback The teardown object
	 */
	public function setupDatabase( $nextTeardown = null ) {
		global $wgDBprefix;

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$this->db = $lb->getConnectionInternal( DB_PRIMARY );

		$suspiciousPrefixes = [ self::DB_PREFIX, MediaWikiIntegrationTestCase::DB_PREFIX ];
		if ( in_array( $wgDBprefix, $suspiciousPrefixes ) ) {
			throw new MWException( "\$wgDBprefix=$wgDBprefix suggests DB setup is already done" );
		}

		$teardown = [];
		$teardown[] = $this->markSetupDone( 'setupDatabase' );

		// Set up a test DB just for parser tests
		MediaWikiIntegrationTestCase::setupAllTestDBs(
			$this->db,
			self::DB_PREFIX,
			true // postgres requires that we use temporary tables
		);
		MediaWikiIntegrationTestCase::resetNonServiceCaches();
		$teardown[] = static function () {
			MediaWikiIntegrationTestCase::teardownTestDB();
		};

		MediaWikiIntegrationTestCase::installMockMwServices();
		$teardown[] = static function () {
			MediaWikiIntegrationTestCase::restoreMwServices();
		};

		// Wipe some DB query result caches on setup and teardown
		$reset = static function () {
			$services = MediaWikiServices::getInstance();
			$services->getLinkCache()->clear();

			// Clear the message cache
			$services->getMessageCache()->clear();
		};
		$reset();
		$teardown[] = $reset;
		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	/**
	 * Add data about uploads to the new test DB, and set up the upload
	 * directory. This should be called after setupDatabase().
	 *
	 * @param ScopedCallback|null $nextTeardown The next teardown object
	 * @return ScopedCallback The teardown object
	 */
	public function setupUploads( $nextTeardown = null ) {
		$teardown = [];

		$this->checkSetupDone( 'setupDatabase' );
		$teardown[] = $this->markSetupDone( 'setupUploads' );

		// Create the files in the upload directory (or pretend to create them
		// in a MockFileBackend). Append teardown callback.
		$teardown[] = $this->setupUploadBackend();

		// Create a user
		$user = User::createNew( 'WikiSysop' );

		// Register the uploads in the database
		$localRepo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();

		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Foobar.jpg' ) );
		# note that the size/width/height/bits/etc of the file
		# are actually set by inspecting the file itself; the arguments
		# to recordUpload3 have no effect.  That said, we try to make things
		# match up so it is less confusing to readers of the code & tests.
		$image->recordUpload3(
			'',
			'Upload of some lame file', 'Some lame file',
			$user,
			[
				'size' => 7881,
				'width' => 1941,
				'height' => 220,
				'bits' => 8,
				'media_type' => MEDIATYPE_BITMAP,
				'mime' => 'image/jpeg',
				'metadata' => [],
				'sha1' => Wikimedia\base_convert( '1', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Thumb.png' ) );
		# again, note that size/width/height below are ignored; see above.
		$image->recordUpload3(
			'',
			'Upload of some lame thumbnail',
			'Some lame thumbnail',
			$user,
			[
				'size' => 22589,
				'width' => 135,
				'height' => 135,
				'bits' => 8,
				'media_type' => MEDIATYPE_BITMAP,
				'mime' => 'image/png',
				'metadata' => [],
				'sha1' => Wikimedia\base_convert( '2', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20130225203040' )
		);

		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Foobar.svg' ) );
		$image->recordUpload3(
			'',
			'Upload of some lame SVG',
			'Some lame SVG',
			$user,
			[
				'size'        => 12345,
				'width'       => 240,
				'height'      => 180,
				'bits'        => 0,
				'media_type'  => MEDIATYPE_DRAWING,
				'mime'        => 'image/svg+xml',
				'metadata'    => [
					'version'        => SvgHandler::SVG_METADATA_VERSION,
					'width'          => 240,
					'height'         => 180,
					'originalWidth'  => '100%',
					'originalHeight' => '100%',
					'translations'   => [
						'en' => SVGReader::LANG_FULL_MATCH,
						'ru' => SVGReader::LANG_FULL_MATCH,
					],
				],
				'sha1'        => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists'  => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		# This image will be prohibited via the list in [[MediaWiki:Bad image list]]
		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Bad.jpg' ) );
		$image->recordUpload3(
			'',
			'zomgnotcensored',
			'Borderline image',
			$user,
			[
				'size' => 12345,
				'width' => 320,
				'height' => 240,
				'bits' => 24,
				'media_type' => MEDIATYPE_BITMAP,
				'mime' => 'image/jpeg',
				'metadata' => [],
				'sha1' => Wikimedia\base_convert( '3', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Video.ogv' ) );
		$image->recordUpload3(
			'',
			'A pretty movie',
			'Will it play',
			$user,
			[
				'size' => 12345,
				'width' => 320,
				'height' => 240,
				'bits' => 0,
				'media_type' => MEDIATYPE_VIDEO,
				'mime' => 'application/ogg',
				'metadata' => [],
				'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'Audio.oga' ) );
		$image->recordUpload3(
			'',
			'An awesome hitsong',
			'Will it play',
			$user,
			[
				'size' => 12345,
				'width' => 0,
				'height' => 0,
				'bits' => 0,
				'media_type' => MEDIATYPE_AUDIO,
				'mime' => 'application/ogg',
				'metadata' => [],
				'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		# A DjVu file
		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'LoremIpsum.djvu' ) );
		$djvuMetadata = [
			'data' => [
				'pages' => [
					[ 'height' => 3508, 'width' => 2480, 'dpi' => 300, 'gamma' => 2.2 ],
					[ 'height' => 3508, 'width' => 2480, 'dpi' => 300, 'gamma' => 2.2 ],
					[ 'height' => 3508, 'width' => 2480, 'dpi' => 300, 'gamma' => 2.2 ],
					[ 'height' => 3508, 'width' => 2480, 'dpi' => 300, 'gamma' => 2.2 ],
					[ 'height' => 3508, 'width' => 2480, 'dpi' => 300, 'gamma' => 2.2 ],
				],
			],
		];
		$image->recordUpload3(
			'',
			'Upload a DjVu',
			'A DjVu',
			$user,
			[
				'size' => 3249,
				'width' => 2480,
				'height' => 3508,
				'bits' => 0,
				'media_type' => MEDIATYPE_OFFICE,
				'mime' => 'image/vnd.djvu',
				'metadata' => $djvuMetadata,
				'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123600' )
		);

		return $this->createTeardownObject( $teardown, $nextTeardown );
	}

	/**
	 * Upload test files to the backend created by createRepoGroup().
	 *
	 * @return callable The teardown callback
	 */
	private function setupUploadBackend() {
		global $IP;

		$repo = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo();
		$base = $repo->getZonePath( 'public' );
		$backend = $repo->getBackend();
		$backend->prepare( [ 'dir' => "$base/3/3a" ] );
		$backend->quickStore( [
			'src' => "$IP/tests/phpunit/data/parser/headbg.jpg",
			'dst' => "$base/3/3a/Foobar.jpg"
		] );
		$backend->prepare( [ 'dir' => "$base/e/ea" ] );
		$backend->quickStore( [
			'src' => "$IP/tests/phpunit/data/parser/wiki.png",
			'dst' => "$base/e/ea/Thumb.png"
		] );
		$backend->prepare( [ 'dir' => "$base/0/09" ] );
		$backend->quickStore( [
			'src' => "$IP/tests/phpunit/data/parser/headbg.jpg",
			'dst' => "$base/0/09/Bad.jpg"
		] );
		$backend->prepare( [ 'dir' => "$base/5/5f" ] );
		$backend->quickStore( [
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

		$public = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->getZonePath( 'public' );

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
		$backend = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()->getBackend();
		foreach ( $files as $file ) {
			$backend->quickDelete( [ 'src' => $file ], [ 'force' => 1 ] );
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
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param ParserTestArticle[] $articles Article info array from TestFileReader
	 * @param ?ScopedCallback $nextTeardown The next teardown object
	 * @return ScopedCallback The teardown object
	 */
	public function addArticles(
		array $articles, ?ScopedCallback $nextTeardown = null
	): ScopedCallback {
		$this->checkSetupDone( 'setupDatabase' );
		$this->checkSetupDone( 'staticSetup' );

		$setup = [];
		$teardown = [];

		// Be sure ParserTestRunner::addArticle has correct language set,
		// so that system messages get into the right language cache
		$services = MediaWikiServices::getInstance();
		if ( $services->getContentLanguage()->getCode() !== 'en' ) {
			$setup['wgLanguageCode'] = 'en';
			$lang = $services->getLanguageFactory()->getLanguage( 'en' );
			$setup[] = static function () use ( $lang ) {
				$services = MediaWikiServices::getInstance();
				$services->disableService( 'ContentLanguage' );
				$services->redefineService( 'ContentLanguage', static function () use ( $lang ) {
					return $lang;
				} );
			};
			$teardown[] = static function () {
				MediaWikiServices::getInstance()->resetServiceForTesting( 'ContentLanguage' );
			};
			$reset = function () {
				$this->resetTitleServices();
			};
			$setup[] = $reset;
			$teardown[] = $reset;
		}

		$teardown[] = $this->executeSetupSnippets( $setup );

		foreach ( $articles as $info ) {
			$this->addArticle( $info->title, $info->text, $info->filename, $info->lineNumStart );
		}

		// Wipe WANObjectCache process cache, which is invalidated by article insertion
		// due to T144706
		MediaWikiServices::getInstance()->getMainWANObjectCache()->clearProcessCache();

		// Reset the service so that any "MediaWiki:bad image list" articles
		// added get fetched
		$teardown[] = static function () {
			MediaWikiServices::getInstance()->resetServiceForTesting( 'BadFileLookup' );
		};

		$this->executeSetupSnippets( $teardown );

		return $this->createTeardownObject( [ function () use ( $articles ) {
			$this->cleanupArticles( $articles );
		} ], $nextTeardown );
	}

	/**
	 * Remove articles from the test DB.  This prevents independent parser
	 * test files from having conflicts when they choose the same names
	 * for article or template test fixtures.
	 *
	 * @param ParserTestArticle[] $articles Article info array from TestFileReader
	 */
	public function cleanupArticles( $articles ) {
		$this->checkSetupDone( 'setupDatabase' );
		$this->checkSetupDone( 'staticSetup' );
		$user = MediaWikiIntegrationTestCase::getTestSysop()->getUser();
		$wikiPageFactory = MediaWikiServices::getInstance()->getWikiPageFactory();
		$delPageFactory = MediaWikiServices::getInstance()->getDeletePageFactory();
		foreach ( $articles as $info ) {
			$name = self::chomp( $info->title );
			$title = Title::newFromText( $name );
			$page = $wikiPageFactory->newFromTitle( $title );
			$delPageFactory->newDeletePage( $page, $user )->deleteUnsafe( 'cleaning up' );
		}
	}

	/**
	 * Insert a temporary test article
	 *
	 * @see MediaWikiIntegrationTestCase::addCoreDBData()
	 * @todo Refactor to share more code w/ ::addCoreDBData() or ::editPage
	 *
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

		if ( $title === null ) {
			throw new MWException( "invalid title '$name' at $file:$line\n" );
		}

		$user = MediaWikiIntegrationTestCase::getTestSysop()->getUser();

		$newContent = ContentHandler::makeContent( $text, $title );

		$page = WikiPage::factory( $title );
		$page->loadPageData( WikiPage::READ_LATEST );

		if ( $page->exists() ) {
			$content = $page->getContent( RevisionRecord::RAW );
			// Only reject the title, if the content/content model is different.
			// This makes it easier to create Template:(( or Template:)) in different extensions
			if ( $newContent->equals( $content ) ) {
				return;
			}
			throw new MWException(
				"duplicate article '$name' with different content at $file:$line\n"
			);
		}

		$services = MediaWikiServices::getInstance();

		// Optionally use mock parser, to make debugging of actual parser tests simpler.
		// But initialise the MessageCache clone first, don't let MessageCache
		// get a reference to the mock object.
		if ( $this->disableSaveParse ) {
			$services->getMessageCache()->getParser();
			$services->disableService( 'Parser' );
			$services->disableService( 'ParserFactory' );
			$services->redefineService(
				'Parser',
				static function () {
					return new ParserTestMockParser;
				}
			);
			$restore = static function () {
				MediaWikiServices::getInstance()->resetServiceForTesting( 'Parser' );
				MediaWikiServices::getInstance()->resetServiceForTesting( 'ParserFactory' );
			};
		} else {
			$restore = false;
		}
		$status = null;
		try {
			$status = $page->doUserEditContent(
				$newContent,
				$user,
				'',
				EDIT_NEW | EDIT_SUPPRESS_RC | EDIT_INTERNAL
			);
		} finally {
			if ( $restore ) {
				$restore();
			}
		}

		if ( !$status->isOK() ) {
			throw new MWException( $status->getWikiText( false, false, 'en' ) );
		}

		// an edit always attempt to purge backlink links such as history
		// pages. That is unnecessary.
		$jobQueueGroup = $services->getJobQueueGroup();
		$jobQueueGroup->get( 'htmlCacheUpdate' )->delete();
		// WikiPages::doEditUpdates randomly adds RC purges
		$jobQueueGroup->get( 'recentChangesUpdate' )->delete();

		// The RepoGroup cache is invalidated by the creation of file redirects
		if ( $title->inNamespace( NS_FILE ) ) {
			$services->getRepoGroup()->clearCache( $title );
		}
	}

	/**
	 * Check if a hook is installed
	 *
	 * @param string $name
	 * @return bool True if tag hook is present
	 */
	public function requireHook( $name ) {
		$parser = MediaWikiServices::getInstance()->getParser();

		if ( preg_match( '/^[Ee]xtension:(.*)$/', $name, $matches ) ) {
			$extName = $matches[1];
			if ( ExtensionRegistry::getInstance()->isLoaded( $extName ) ) {
				return true;
			} else {
				$this->recorder->warning( "   Skipping this test suite because it requires the '$extName' " .
					"extension, which isn't loaded." );
				return false;
			}
		}
		if ( in_array( $name, $parser->getTags(), true ) ) {
			return true;
		} else {
			$this->recorder->warning( "   Skipping this test suite because it requires the '$name' hook, " .
				"which isn't provided by any loaded extension." );
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
		$parser = MediaWikiServices::getInstance()->getParser();

		if ( in_array( $name, $parser->getFunctionHooks(), true ) ) {
			return true;
		} else {
			$this->recorder->warning( "   This test suite requires the '$name' function " .
				"hook extension, skipping." );
			return false;
		}
	}

	/**
	 * Fake constant timestamp to make sure time-related parser
	 * functions give a persistent value.
	 *
	 * - Parser::expandMagicVariable (via ParserGetVariableValueTs hook)
	 * - Parser::preSaveTransform (via ParserOptions)
	 * @return int Fake constant timestamp.
	 */
	private function getFakeTimestamp() {
		// parsed as '1970-01-01T00:02:03Z'
		return 123;
	}
}
