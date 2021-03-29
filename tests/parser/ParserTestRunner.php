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
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Wikimedia\Parsoid\ParserTests\ParserHook as ParsoidParserHook;
use Wikimedia\Parsoid\ParserTests\RawHTML as ParsoidRawHTML;
use Wikimedia\Parsoid\ParserTests\StyleTag as ParsoidStyleTag;
use Wikimedia\Parsoid\ParserTests\Test as ParsoidTest;
use Wikimedia\Parsoid\ParserTests\TestUtils as ParsoidTestUtils;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\ScopedCallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @ingroup Testing
 */
class ParserTestRunner {

	use MediaWikiTestCaseTrait;

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
	 * Table name prefix.
	 */
	public const DB_PREFIX = 'parsertest_';

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

		$this->fileBackendName = $options['file-backend'] ?? false;

		$this->runDisabled = !empty( $options['run-disabled'] );

		$this->disableSaveParse = !empty( $options['disable-save-parse'] );

		if ( isset( $options['upload-dir'] ) ) {
			$this->uploadDir = $options['upload-dir'];
		}

		$this->defaultTitle = Title::newFromText( 'Parser test' );
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
			if ( !file_exists( $dir ) ) {
				continue;
			}
			$counter = 1;
			$dirIterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir )
			);
			foreach ( $dirIterator as $fileInfo ) {
				/** @var SplFileInfo $fileInfo */
				if ( substr( $fileInfo->getFilename(), -4 ) === '.txt' ) {
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
			// Parsoid deliberately ignores the per-user thumbsize preference
			'thumbsize' => 220,
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
		Hooks::register( 'ParserGetVariableValueTs', function ( $parser, &$ts ) {
			$ts = $this->getFakeTimestamp();
			return true;
		} );

		$this->hideDeprecated( 'Hooks::clear' );
		$teardown[] = static function () {
			Hooks::clear( 'ParserGetVariableValueTs' );
		};

		$this->appendNamespaceSetup( $setup, $teardown );

		// Set up interwikis and append teardown function
		$this->appendInterwikiSetup( $setup, $teardown );

		// Set up a mock MediaHandlerFactory
		MediaWikiServices::getInstance()->disableService( 'MediaHandlerFactory' );
		MediaWikiServices::getInstance()->redefineService(
			'MediaHandlerFactory',
			static function ( MediaWikiServices $services ) {
				$handlers = $services->getMainConfig()->get( 'ParserTestMediaHandlers' );
				return new MediaHandlerFactory( $handlers );
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
			MediaWikiServices::getInstance()->resetServiceForTesting( 'NamespaceInfo' );
			MediaWikiServices::getInstance()->getContentLanguage()->resetNamespaces();
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
				'class' => MockLocalRepo::class,
				'name' => 'local',
				'url' => 'http://example.com/images',
				'hashLevels' => 2,
				'transformVia404' => false,
				'backend' => $backend
			],
			[],
			MediaWikiServices::getInstance()->getMainWANObjectCache()
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
				'iw_url' => 'http://doesnt.matter.org/$1',
				'iw_local' => 0,
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
				'iw_url' => 'http://mi.wikipedia.org/wiki/$1',
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
				'iw_url' => 'http://en.wikipedia.org/wiki/$1',
				'iw_local' => 1,
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
		$setup['wgLocalInterwikis'] = [ 'local', 'mi' ];
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
				$testFileInfo = TestFileReader::read( $filename, [
					'runDisabled' => $this->runDisabled,
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
	 * @param array $requirements
	 * @return bool
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
		if ( !$this->meetsRequirements( $testFileInfo['fileOptions']['requirements'] ?? [] ) ) {
			foreach ( $testFileInfo['tests'] as $test ) {
				$this->recorder->startTest( $test );
				$this->recorder->skipped( $test, 'required extension not enabled' );
			}
			return true;
		}

		// Add articles
		$teardown = $this->addArticles( $testFileInfo['articles'] );

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

		// Clean up
		ScopedCallback::consume( $teardown );

		return $ok;
	}

	/**
	 * Shared code to initialize ParserOptions based on the $test object,
	 * used by both the legacy Parser and the Parsoid parser.
	 * @param stdClass $test
	 * @param callable $parserOptionsCallback A callback to create the
	 *   initial ParserOptions object.  This allows for some minor
	 *   differences in how the legacy Parser and Parsoid create this.
	 * @return array An array of Title, ParserOptions, and integer revId.
	 */
	private function setupParserOptions( $test, callable $parserOptionsCallback ) {
		$opts = $test->options;
		$context = RequestContext::getMain();
		$user = $context->getUser();
		$revId = 1337; // see Parser::getRevisionId()
		$title = isset( $opts['title'] )
			? Title::newFromText( $opts['title'] )
			: $this->defaultTitle;
		$wikitext = $test->wikitext ?? $test->input;

		$options = $parserOptionsCallback(
			$context, $title, $revId, $wikitext
		);
		$options->setTimestamp( $this->getFakeTimestamp() );
		$options->setUserLang( $context->getLanguage() );

		if ( isset( $opts['lastsavedrevision'] ) ) {
			$content = new WikitextContent( $test->wikitext ?? $test->input );
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
	 * Run a given wikitext input through a freshly-constructed instance
	 * of the legacy wiki parser, and compare the output against the expected
	 * results.
	 *
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
	 * @return ParserTestResult|false false if skipped
	 */
	public function runTest( $test ) {
		wfDebug( __METHOD__ . ": running {$test['desc']}" );
		$opts = $test['options'];
		if ( isset( $opts['preprocessor'] ) && $opts['preprocessor'] !== 'Preprocessor_Hash' ) {
			wfDeprecated( 'preprocessor=Preprocessor_DOM', '1.36' );
			return false; // Skip test.
		}
		$teardownGuard = $this->perTestSetup( $test );
		[ $title, $options, $revId ] = $this->setupParserOptions(
			(object)$test,
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
				$parser->mStripState->addNoWiki( $marker, $content );
				return Html::inlineStyle( $marker, 'all', $attributes );
			} );
			$parser->setHook( 'link', static function ( $content, $attributes, $parser ) {
				return Html::element( 'link', $attributes );
			} );
		}

		if ( isset( $opts['pst'] ) ) {
			$out = $parser->preSaveTransform( $test['input'], $title, $options->getUser(), $options );
			$output = $parser->getOutput();
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
			$output = $parser->parse( $test['input'], $title, $options, true, true, $revId );
			$out = $output->getText( [
				'allowTOC' => !isset( $opts['notoc'] ),
				'unwrap' => !isset( $opts['wrap'] ),
			] );
			$out = preg_replace( '/\s+$/', '', $out );

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

		if ( isset( $output ) && isset( $opts['showflags'] ) ) {
			$actualFlags = array_keys( TestingAccessWrapper::newFromObject( $output )->mFlags );
			sort( $actualFlags );
			$out .= "\nflags=" . implode( ', ', $actualFlags );
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
	 * Run a given wikitext input through a freshly-constructed Parsoid parser,
	 * running in 'integrated' mode, and compare the output against the
	 * expected results.
	 *
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
	 * @return ParserTestResult|false false if skipped
	 */
	public function runParsoidTest( ParsoidTest $test ) {
		wfDebug( __METHOD__ . ": running {$test->testName} (parsoid)" );
		$opts = $test->options;
		$parsoidOnly = isset( $test->sections['html/parsoid'] ) ||
			isset( $test->sections['html/parsoid+integrated'] ) ||
			( isset( $test->sections['html/parsoid+langconv'] ) ) ||
			( isset( $opts['parsoid'] ) && !isset( $opts['parsoid']['normalizePhp'] ) );
		$modes = $opts['parsoid']['modes'] ?? null;
		if (
			$modes &&
			array_search( 'wt2html', $modes, true ) === false &&
			array_search( 'wt2html+integrated', $modes, true ) === false
		) {
			return false; // Skip test, it doesn't have a wt2html mode
		}
		$normOpts = [
			'parsoidOnly' => $parsoidOnly,
			'preserveIEW' => isset( $opts['parsoid']['preserveIEW'] ),
			'scrubWikitext' => isset( $opts['parsoid']['scrubWikitext'] ),
		];

		if ( isset( $opts['preprocessor'] ) && $opts['preprocessor'] !== 'Preprocessor_Hash' ) {
			return false; // Skip test.
		}
		// Skip tests targetting features Parsoid doesn't (yet) support
		// @todo T270312
		if ( isset( $opts['styletag'] ) || isset( $opts['pst'] ) ||
			 isset( $opts['msg'] ) || isset( $opts['section'] ) ||
			 isset( $opts['replace'] ) || isset( $opts['comment'] ) ||
			 isset( $opts['preload'] ) || isset( $opts['showtitle'] ) ||
			 isset( $opts['showindicators'] ) || isset( $opts['ill'] ) ||
			 isset( $opts['cat'] ) || isset( $opts['showflags'] ) ) {
			return false; // skip test
		}
		$parsoidHtml = $test->sections['html/parsoid+integrated'] ??
			$test->parsoidHtml;
		// @todo T270311 eventually we should support the full set of
		// test modes: wt2html, wt2wt, html2wt, html2html, and selser
		if ( $test->wikitext === null || $parsoidHtml === null ) {
			return false; // Legacy-only test or non-wt2html
		}
		if ( ( $test->knownFailures['wt2html'] ?? null ) !== null ) {
			// @todo: Parsoid's built-in test runner checks the output
			// even on the known failure list.
			return false; // on the known failures list
		}

		$services = MediaWikiServices::getInstance();
		$siteConfig = $services->get( 'ParsoidSiteConfig' );
		$dataAccess = $services->get( 'ParsoidDataAccess' );
		$pageConfigFactory = $services->get( 'ParsoidPageConfigFactory' );
		$pageConfig = null;

		$teardownGuard = $this->perTestSetup( $test );
		[ $title, $options, $revId ] = $this->setupParserOptions(
			$test,
			static function ( $context, $title, $revId, $wikitext ) use ( $pageConfigFactory, &$pageConfig ) {
				$pageConfig = $pageConfigFactory->create(
					$title,
					$context->getUser(),
					// @todo T270310: Parsoid doesn't have a mechanism
					// to override revid with a fake revision, like the
					// legacy parser does, so {{REVISIONID}} will be
					// 'wrong' in parser tests.  Probably need to
					// override
					// ParserOptions::getCurrentRevisionRecordCallback()
					// (like we do for the 'lastsavedrevision' option
					// below) in order to fix this.
					null/*$revId*/,
					// @todo T270310: Parsoid should really accept a
					// RevisionRecord here, instead of raw wikitext.
					$wikitext,
					$context->getLanguage()->getCode()
				);
				return $pageConfig->getParserOptions();
			} );

		// Create Parsoid object.
		// @todo T270307: unregister these after this test
		$siteConfig->registerExtensionModule( ParsoidParserHook::class );
		if ( ( $opts['wgrawhtml'] ?? null ) === '1' ) {
			$siteConfig->registerExtensionModule( ParsoidRawHTML::class );
		}
		if ( isset( $opts['styletag'] ) ) {
			$siteConfig->registerExtensionModule( ParsoidStyleTag::class );
		}

		$parsoid = new Parsoid( $siteConfig, $dataAccess );

		$out = $parsoid->wikitext2html( $pageConfig, [
			'body_only' => true,
			'wrapSections' => $opts['parsoid']['wrapSections'] ?? false,
			'scrubWikitext' => $normOpts['scrubWikitext'],
		] );
		$expected = $parsoidHtml;

		$out = ParsoidTestUtils::normalizeOut( $out, $normOpts );
		if ( $normOpts['parsoidOnly'] ) {
			$expected = ParsoidTestUtils::normalizeOut( $expected, $normOpts );
		} else {
			$expected = ParsoidTestUtils::normalizeHTML( $expected );
		}

		$testResult = new ParserTestResult( [
			'test' => $test->testName,
			'desc' => ( $test->comment ?? '' ) . $test->testName,
			'input' => $test->wikitext,
			'result' => $test->legacyHtml,
			'options' => $test->options,
			'config' => $test->config,
			'line' => $test->lineNumStart,
			'file' => $test->filename,
		], $expected, $out );
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
		return $opts[$key] ?? $default;
	}

	/**
	 * Do any required setup which is dependent on test options.
	 *
	 * @see staticSetup() for more information about setup/teardown
	 *
	 * @param array|ParserTest $test Test info supplied by TestFileReader
	 * @param callable|null $nextTeardown
	 * @return ScopedCallback
	 */
	public function perTestSetup( $test, $nextTeardown = null ) {
		$teardown = [];

		$this->checkSetupDone( 'setupDatabase' );
		$teardown[] = $this->markSetupDone( 'perTestSetup' );

		$opts = is_array( $test ) ? $test['options'] : $test->options;
		$config = is_array( $test ) ? $test['config'] : $test->config;

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
			'wgThumbLimits' => [ self::getOptionValue( 'thumbsize', $opts, 180 ) ],
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

		if ( $config ) {
			$configLines = explode( "\n", $config );

			foreach ( $configLines as $line ) {
				list( $var, $value )  = explode( '=', $line, 2 );
				$setup[$var] = eval( "return $value;" );
			}
		}

		/** @since 1.20 */
		Hooks::runner()->onParserTestGlobals( $setup );

		// Set content language. This invalidates the magic word cache and title services
		// In addition the ParserFactory needs to be recreated as well.
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( $langCode );
		$lang->resetNamespaces();
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
			Wikimedia\suppressWarnings();
			$wrapper->reloadTables();
			Wikimedia\restoreWarnings();

			// Reset context to the restored globals
			$context->setUser( $GLOBALS['wgUser'] );
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
		$this->db = $lb->getConnection( DB_MASTER );

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
				'metadata' => serialize( [] ),
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
				'metadata' => serialize( [] ),
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
				'metadata'    => serialize( [
					'version'        => SvgHandler::SVG_METADATA_VERSION,
					'width'          => 240,
					'height'         => 180,
					'originalWidth'  => '100%',
					'originalHeight' => '100%',
					'translations'   => [
						'en' => SVGReader::LANG_FULL_MATCH,
						'ru' => SVGReader::LANG_FULL_MATCH,
					],
				] ),
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
				'metadata' => serialize( [] ),
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
				'metadata' => serialize( [] ),
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
				'metadata' => serialize( [] ),
				'sha1' => Wikimedia\base_convert( '', 16, 36, 31 ),
				'fileExists' => true
			],
			$this->db->timestamp( '20010115123500' )
		);

		# A DjVu file
		$image = $localRepo->newFile( Title::makeTitle( NS_FILE, 'LoremIpsum.djvu' ) );
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
	 * @param array $articles Article info array from TestFileReader
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
			$this->addArticle( $info['name'], $info['text'], $info['file'], $info['line'] );
		}

		// Wipe WANObjectCache process cache, which is invalidated by article insertion
		// due to T144706
		MediaWikiServices::getInstance()->getMainWANObjectCache()->clearProcessCache();

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
	 * @param array $articles Article info array from TestFileReader
	 */
	public function cleanupArticles( $articles ) {
		$this->checkSetupDone( 'setupDatabase' );
		$this->checkSetupDone( 'staticSetup' );
		$user = MediaWikiIntegrationTestCase::getTestSysop()->getUser();
		foreach ( $articles as $info ) {
			$name = self::chomp( $info['name'] );
			$title = Title::newFromText( $name );
			$page = WikiPage::factory( $title );
			$page->doDeleteArticleReal( 'cleaning up', $user );
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
		$page->loadPageData( 'fromdbmaster' );

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

		// Optionally use mock parser, to make debugging of actual parser tests simpler.
		// But initialise the MessageCache clone first, don't let MessageCache
		// get a reference to the mock object.
		if ( $this->disableSaveParse ) {
			MediaWikiServices::getInstance()->getMessageCache()->getParser();
			$restore = $this->executeSetupSnippets( [ 'wgParser' => new ParserTestMockParser ] );
		} else {
			$restore = false;
		}
		try {
			$status = $page->doEditContent(
				$newContent,
				'',
				EDIT_NEW | EDIT_SUPPRESS_RC | EDIT_INTERNAL,
				false,
				$user
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
		JobQueueGroup::singleton()->get( 'htmlCacheUpdate' )->delete();
		// WikiPages::doEditUpdates randomly adds RC purges
		JobQueueGroup::singleton()->get( 'recentChangesUpdate' )->delete();

		// The RepoGroup cache is invalidated by the creation of file redirects
		if ( $title->inNamespace( NS_FILE ) ) {
			MediaWikiServices::getInstance()->getRepoGroup()->clearCache( $title );
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
