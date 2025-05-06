<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MediaWiki\Composer\ComposerLaunchParallel;
use MediaWiki\Composer\ComposerSystemInterface;
use PHPUnit\Framework\ErrorTestCase;
use Shellbox\Shellbox;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitXmlManager {

	public const RESULTS_CACHE_FILE = 'phpunit-results-cache.json';

	private string $rootDir;

	private string $testsListFile;
	private string $phpunitConfigFile;
	private string $testGroup;
	private ?string $resultsCacheFile;

	/**
	 * The `SkippedTestCase` is generated dynamically by PHPUnit for tests
	 * that are marked as skipped. We don't need to find a matching filesystem
	 * file for these.
	 *
	 * The `ParserIntegrationTest` is a special case - it's a single test class
	 * that generates very many tests. To balance out the test suites, we exclude
	 * the class from the scan, and add it back in PhpUnitXml::addSpecialCaseTests
	 */
	private const EXPECTED_MISSING_CLASSES = [
		"PHPUnit\\Framework\\SkippedTestCase",
		"MediaWiki\\Extension\\Scribunto\\Tests\\Engines\\LuaCommon\\LuaEngineTestSkip",
		"\\ParserIntegrationTest",
	];

	public function __construct(
		string $rootDir,
		string $testsListFile,
		string $phpunitConfigFile,
		string $testGroup,
		?string $resultsCacheFile = null
	) {
		$this->rootDir = $rootDir;
		$this->testsListFile = $testsListFile;
		$this->phpunitConfigFile = $phpunitConfigFile;
		$this->testGroup = $testGroup;
		$this->resultsCacheFile = $resultsCacheFile;
	}

	private function getPhpUnitXmlTarget(): string {
		return $this->rootDir . DIRECTORY_SEPARATOR . $this->phpunitConfigFile;
	}

	public static function getPhpUnitXmlDist( string $rootDir ): string {
		return $rootDir . DIRECTORY_SEPARATOR . "phpunit.xml.dist";
	}

	private function getTestsList(): string {
		return $this->rootDir . DIRECTORY_SEPARATOR . $this->testsListFile;
	}

	private function loadPhpUnitXmlDist(): PhpUnitXml {
		return $this->loadPhpUnitXml( self::getPhpUnitXmlDist( $this->rootDir ) );
	}

	private function loadPhpUnitXml( string $targetFile ): PhpUnitXml {
		return new PhpUnitXml( $targetFile );
	}

	/**
	 * @throws TestListMissingException
	 * @throws PhpUnitResultsCachingException
	 */
	private function loadTestClasses(): array {
		if ( !file_exists( $this->getTestsList() ) ) {
			throw new TestListMissingException( $this->getTestsList() );
		}
		return ( new PhpUnitTestListProcessor(
			$this->getTestsList(),
			$this->resultsCacheFile,
			$this->testGroup
		) )->getTestClasses();
	}

	private function scanForTestFiles(): array {
		return ( new PhpUnitTestFileScanner( $this->rootDir ) )->scanForFiles();
	}

	private static function extractNamespaceFromFile( string $filename ): array {
		$contents = file_get_contents( $filename );
		$matches = [];
		if ( preg_match( '/^namespace\s+([^\s;]+)/m', $contents, $matches ) ) {
			return explode( '\\', $matches[1] );
		}
		return [];
	}

	/**
	 * @param TestDescriptor $testDescriptor
	 * @param array $phpFiles
	 * @return ?string
	 * @throws MissingNamespaceMatchForTestException
	 * @throws UnlocatedTestException
	 * @throws PhpUnitErrorTestCaseFoundException
	 */
	private function resolveFileForTest( TestDescriptor $testDescriptor, array $phpFiles ): ?string {
		$filename = $testDescriptor->getClassName() . ".php";
		if ( !array_key_exists( $filename, $phpFiles ) ) {
			if ( !in_array( $testDescriptor->getFullClassname(), self::EXPECTED_MISSING_CLASSES ) ) {
				if ( $testDescriptor->getFullClassname() === ErrorTestCase::class ) {
					throw new PhpUnitErrorTestCaseFoundException();
				}
				throw new UnlocatedTestException( $testDescriptor );
			} else {
				return null;
			}
		}
		if ( count( $phpFiles[$filename] ) === 1 ) {
			return $phpFiles[$filename][0];
		}
		$possibleNamespaces = [];
		foreach ( $phpFiles[$filename] as $file ) {
			$namespace = self::extractNamespaceFromFile( $file );
			if ( $namespace === $testDescriptor->getNamespace() ) {
				return $file;
			}
			$possibleNamespaces[] = $namespace;
		}
		throw new MissingNamespaceMatchForTestException( $testDescriptor );
	}

	private function buildSuites( array $testClasses, int $groups ): array {
		return ( new TestSuiteBuilder() )->buildSuites( $testClasses, $groups );
	}

	public function isPhpUnitXmlPrepared(): bool {
		return PhpUnitXml::isPhpUnitXmlPrepared(
			$this->rootDir . DIRECTORY_SEPARATOR . 'phpunit-' . $this->testGroup . '.xml'
		);
	}

	/**
	 * @return void
	 * @throws MissingNamespaceMatchForTestException
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws SuiteGenerationException
	 * @throws PhpUnitErrorTestCaseFoundException
	 * @throws PhpUnitResultsCachingException
	 */
	public function createPhpUnitXml( int $groups ) {
		$unitFile = $this->loadPhpUnitXmlDist();
		$testFiles = $this->scanForTestFiles();
		$testClasses = $this->loadTestClasses();
		$seenFiles = [];
		foreach ( $testClasses as $testDescriptor ) {
			$file = $this->resolveFileForTest( $testDescriptor, $testFiles );
			if ( is_string( $file ) && !array_key_exists( $file, $seenFiles ) ) {
				$testDescriptor->setFilename( $file );
				$seenFiles[$file] = 1;
			}
		}
		$suites = $this->buildSuites( $testClasses, $groups - 1 );
		$unitFile->addSplitGroups( $suites );
		$unitFile->addSpecialCaseTests( $groups );
		$unitFile->saveToDisk( $this->getPhpUnitXmlTarget() );
	}

	/**
	 * The URL for the results cache file depends on the name of the current job
	 * since the results cache server implements a per-job cache (T384925).
	 * Since the name of the job is not available in the environment of Quibble jobs,
	 * we infer it here from the LOG_PATH environment variable if set.
	 * @param string $serverUrlBase
	 * @param string|bool|array $logPath
	 * @return ?string The result cache file URL
	 */
	private static function generateResultsCacheUrl( string $serverUrlBase, $logPath ): ?string {
		if ( !is_string( $logPath ) ) {
			return null;
		}
		$matches = [];
		if ( !preg_match( '/([^\/]+-)?quibble-[^\/]+/', $logPath, $matches ) ) {
			return null;
		}
		return $serverUrlBase . '/' . $matches[0];
	}

	/**
	 * We don't have access to Mediawiki's HTTPRequestFactory here since we are in a minimal
	 * bootstrap and Mediawiki Services are not loaded. Use Guzzle to make a simple
	 * unauthenticated GET for the results cache file. If anything goes wrong, we just continue
	 * to split / run the tests without cached timing data.
	 *
	 * @throws GuzzleException
	 */
	private static function downloadResultsCacheFile( string $resultsCacheUrl ): ?string {
		$client = new Client( [
			'timeout' => 5,
			'headers' => [ 'User-Agent' => 'MediaWiki Composer' ]
		] );
		$content = $client->get( $resultsCacheUrl )->getBody()->getContents();
		if ( !$content ) {
			return null;
		}
		return $content;
	}

	public static function fetchResultsCache( Event $event ) {
		$resultsCacheServerBaseUrl = getenv( 'MW_RESULTS_CACHE_SERVER_BASE_URL' );
		if ( !is_string( $resultsCacheServerBaseUrl ) ) {
			$event->getIO()->write(
				'No results cache server defined - set MW_RESULTS_CACHE_SERVER_BASE_URL ' .
				'to use cached phpunit test timing information to optimise the distribution of tests ' .
				'into split_groups.'
			);
			return;
		}
		// We want to put the cache file in a tmp directory, but we don't have MainConfigSchema::TmpDirectory available
		// to us, so use the system temp folder.
		$cacheFile = self::resultsCacheFileLocation();
		$event->getIO()->write( '' );
		if ( file_exists( $cacheFile ) ) {
			$event->getIO()->write( 'Using existing PHPUnit results cache ' . self::RESULTS_CACHE_FILE );
		} else {
			$resultsCacheUrl = self::generateResultsCacheUrl( $resultsCacheServerBaseUrl, getenv( 'LOG_PATH' ) );
			if ( $resultsCacheUrl === null ) {
				$event->getIO()->warning(
					'Unable to generate results cache URL - is LOG_PATH set?'
				);
				$event->getIO()->write( '' );
				return;
			}
			try {
				$latestResultsCache = self::downloadResultsCacheFile( $resultsCacheUrl );
				if ( $latestResultsCache ) {
					$event->getIO()->write( 'Downloaded latest PHPUnit results cache from ' . $resultsCacheUrl );
					file_put_contents( $cacheFile, $latestResultsCache );
				} else {
					$event->getIO()->warning(
						'Unable to download latest PHPUnit results cache from ' . $resultsCacheUrl
					);
				}
			} catch ( GuzzleException $ge ) {
				$event->getIO()->warning(
					'GuzzleException when fetching results cache file: ' . $ge->getMessage()
				);
			}
		}
		$event->getIO()->write( '' );
	}

	public static function listTestsNotice( Event $event ) {
		$event->getIO()->write( '' );
		$event->getIO()->write( 'Running `phpunit --list-tests-xml` to get a list of expected tests ... ' );
		$event->getIO()->write( '' );
	}

	public static function newSplitGroupExecutor( IOInterface $io, ComposerSystemInterface $system ) {
		return new SplitGroupExecutor(
			self::getPhpUnitXmlDist( $system->getcwd() ),
			Shellbox::createUnboxedExecutor(),
			$io
		);
	}

	private static function resultsCacheFileLocation(): string {
		return sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::RESULTS_CACHE_FILE;
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 * @throws PhpUnitResultsCachingException
	 */
	public static function splitTestsList(
		string $testGroup,
		string $testListFile,
		?string $testSuite,
		string $phpunitConfigOutputFile,
		ComposerSystemInterface $system,
		SplitGroupExecutor $executor,
		?IOInterface $io
	) {
		/**
		 * We split into 8 groups here, because experimentally that generates 100% CPU load
		 * on developer machines and results in groups that are similar in size to the
		 * Parser tests (which we have to run in a group on their own - see T345481)
		 */
		try {
			$cacheFileLocation = self::resultsCacheFileLocation();
			$resultsCacheFile = file_exists( $cacheFileLocation ) ? $cacheFileLocation : null;
			if ( $io && !$resultsCacheFile ) {
				$io->warning( 'No results cache file found at ' . $cacheFileLocation );
			}
			( new PhpUnitXmlManager(
				$system->getcwd(),
				$testListFile,
				$phpunitConfigOutputFile,
				$testGroup,
				$resultsCacheFile
			) )->createPhpUnitXml( 8 );
			if ( $io ) {
				$io->write( '' );
				$io->info( 'Created modified `%s` with test suite groups', [ $phpunitConfigOutputFile ] );
			}
			// Quibble still expects a `phpunit.xml` file to be generated by parallel runs.
			// Create this file for now to make the Quibble migration easier. Delete this when
			// Quibble has been updated to expect the correct files (T378797)
			copy( $phpunitConfigOutputFile, $system->getcwd() . DIRECTORY_SEPARATOR . 'phpunit.xml' );
		} catch ( PhpUnitErrorTestCaseFoundException $tce ) {
			if ( $io ) {
				$io->error( $tce->getMessage() );
			}
			copy(
				self::getPhpUnitXmlDist( $system->getcwd() ),
				$system->getcwd() . DIRECTORY_SEPARATOR . 'phpunit.xml'
			);
			if ( $testSuite !== null ) {
				/* Parallel test suite run failed. Run the tests in linear order to work out
				 * which test actually has an error (see T379764 for some discussion of why this
				 * is necessary)
				 *
				 * For a "custom" run, no test suite is specified.
				 * This only happens when users manually run the 'split' function from composer on the command
				 * line and supply their own `tests-list.xml` file and does not happen as part of a CI run, so a
				 * fallback run of the test suite is not desired / expected.
				 */
				$executor->runLinearFallback( $testSuite );
				if ( $io ) {
					$io->error( "Test suite splitting failed" );
				}
			}
			$system->exit( ComposerLaunchParallel::EXIT_STATUS_PHPUNIT_LIST_TESTS_ERROR );
		}
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 */
	public static function splitTestsListExtensions( Event $event ) {
		$systemInterface = new ComposerSystemInterface();
		self::splitTestsList(
			'database',
			'tests-list-extensions.xml',
			'extensions',
			'phpunit-database.xml',
			$systemInterface,
			self::newSplitGroupExecutor( $event->getIO(), $systemInterface ),
			$event->getIO()
		);
		self::splitTestsList(
			'databaseless',
			'tests-list-extensions.xml',
			'extensions',
			'phpunit-databaseless.xml',
			$systemInterface,
			self::newSplitGroupExecutor( $event->getIO(), $systemInterface ),
			$event->getIO()
		);
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 */
	public static function splitTestsListDefault( Event $event ) {
		$systemInterface = new ComposerSystemInterface();
		self::splitTestsList(
			'database',
			'tests-list-default.xml',
			'default',
			'phpunit-database.xml',
			$systemInterface,
			self::newSplitGroupExecutor( $event->getIO(), $systemInterface ),
			$event->getIO()
		);
		self::splitTestsList(
			'databaseless',
			'tests-list-default.xml',
			'default',
			'phpunit-databaseless.xml',
			$systemInterface,
			self::newSplitGroupExecutor( $event->getIO(), $systemInterface ),
			$event->getIO()
		);
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 */
	public static function splitTestsCustom( Event $event ) {
		if ( $_SERVER["argc"] < 3 ) {
			$event->getIO()->error( 'Specify a filename to split' );
			exit( 1 );
		}
		$filename = $_SERVER["argv"][2];
		$systemInterface = new ComposerSystemInterface();
		self::splitTestsList(
			'custom',
			$filename,
			null,
			'phpunit-custom.xml',
			$systemInterface,
			self::newSplitGroupExecutor( $event->getIO(), $systemInterface ),
			$event->getIO()
		);
	}
}
