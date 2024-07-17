<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitXmlManager {

	private string $rootDir;

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

	public function __construct( string $rootDir ) {
		$this->rootDir = $rootDir;
	}

	private function getPhpUnitXmlTarget(): string {
		return $this->rootDir . DIRECTORY_SEPARATOR . PhpUnitXml::PHP_UNIT_XML_FILE;
	}

	private function getPhpUnitXmlDist(): string {
		return $this->rootDir . DIRECTORY_SEPARATOR . "phpunit.xml.dist";
	}

	private function getTestsList(): string {
		return $this->rootDir . DIRECTORY_SEPARATOR . "tests-list.xml";
	}

	public function isPhpUnitXmlPrepared(): bool {
		if ( !file_exists( $this->getPhpUnitXmlTarget() ) ) {
			return false;
		}
		$unitFile = $this->loadPhpUnitXml( $this->getPhpUnitXmlTarget() );
		return $unitFile->containsSplitGroups();
	}

	private function loadPhpUnitXmlDist(): PhpUnitXml {
		return $this->loadPhpUnitXml( $this->getPhpUnitXmlDist() );
	}

	private function loadPhpUnitXml( string $targetFile ): PhpUnitXml {
		return new PhpUnitXml( $targetFile );
	}

	private function loadTestClasses(): array {
		if ( !file_exists( $this->getTestsList() ) ) {
			throw new TestListMissingException( $this->getTestsList() );
		}
		return ( new PhpUnitTestListProcessor( $this->getTestsList() ) )->getTestClasses();
	}

	private function scanForTestFiles(): array {
		return ( new PhpUnitTestFileScanner( $this->rootDir ) )->scanForFiles();
	}

	private static function extractNamespaceFromFile( $filename ): array {
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
	 */
	private function resolveFileForTest( TestDescriptor $testDescriptor, array $phpFiles ): ?string {
		$filename = $testDescriptor->getClassName() . ".php";
		if ( !array_key_exists( $filename, $phpFiles ) ) {
			if ( !in_array( $testDescriptor->getFullClassname(), self::EXPECTED_MISSING_CLASSES ) ) {
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

	/**
	 * @return void
	 * @throws MissingNamespaceMatchForTestException
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws SuiteGenerationException
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

	public static function listTestsNotice() {
		print( PHP_EOL );
		print( 'Running `phpunit --list-tests-xml` to get a list of expected tests ... ' . PHP_EOL );
		print( PHP_EOL );
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 */
	public static function splitTestsList() {
		/**
		 * We split into 8 groups here, because experimentally that generates 100% CPU load
		 * on developer machines and results in groups that are similar in size to the
		 * Parser tests (which we have to run in a group on their own - see T345481)
		 */
		( new PhpUnitXmlManager( getcwd() ) )->createPhpUnitXml( 8 );
		print( PHP_EOL . 'Created modified `phpunit.xml` with test suite groups' . PHP_EOL );
	}

	/**
	 * @throws TestListMissingException
	 * @throws UnlocatedTestException
	 * @throws MissingNamespaceMatchForTestException
	 * @throws SuiteGenerationException
	 */
	public static function splitTestsCustom() {
		if ( $_SERVER["argc"] < 3 ) {
			print( 'Specify a filename to split' . PHP_EOL );
			exit( 1 );
		}
		$filename = $_SERVER["argv"][2];
		self::splitTestsList( $filename );
	}
}
