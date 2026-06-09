<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Autoload
 * @covers \AutoLoader
 */
class AutoLoaderTest extends MediaWikiIntegrationTestCase {

	/** Classes cannot have namespaces, mark them also in the class file with // NO_NAMESPACE */
	private const CLASS_NO_NAMESPACES = [
		\ConcatenatedGzipHistoryBlob::class,
		\DiffHistoryBlob::class,
		\HistoryBlob::class,
		\HistoryBlobCurStub::class,
		\HistoryBlobStub::class,
		\HistoryBlobUtils::class,
		\MediaWiki::class,
		\MemcachedClient::class,
		\PHPVersionCheck::class,

		// No alias, needs to extends instead - T428663
		\ConstantDependency::class,
		\DependencyWrapper::class,
		\FileDependency::class,
		\GlobalDependency::class,
		\MainConfigDependency::class,

		// Needs namespace - Hardcoded in phan-taint-check - T428662
		\StatusValue::class,
	];

	/** @var string[] */
	private $oldPsr4;
	/** @var string[] */
	private $oldClassFiles;

	protected function setUp(): void {
		parent::setUp();

		$this->mergeMwGlobalArrayValue( 'wgAutoloadLocalClasses', [
			'TestAutoloadedLocalClass' =>
				__DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php',
		] );
		$this->mergeMwGlobalArrayValue( 'wgAutoloadClasses', [
			'TestAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAutoloadedClass.php',
		] );

		$access = TestingAccessWrapper::newFromClass( AutoLoader::class );
		$this->oldPsr4 = $access->psr4Namespaces;
		$this->oldClassFiles = $access->classFiles;
		AutoLoader::registerNamespaces( [
			'Test\\MediaWiki\\AutoLoader\\' => __DIR__ . '/../data/autoloader/psr4/'
		] );
		AutoLoader::registerClasses( [
			'TestAnotherAutoloadedClass' => __DIR__ . '/../data/autoloader/TestAnotherAutoloadedClass.php',
		] );
	}

	protected function tearDown(): void {
		$access = TestingAccessWrapper::newFromClass( AutoLoader::class );
		$access->psr4Namespaces = $this->oldPsr4;
		$access->classFiles = $this->oldClassFiles;
		parent::tearDown();
	}

	public function testFind() {
		$path = __DIR__ . '/../data/autoloader/TestAutoloadedLocalClass.php';
		$this->assertSame( $path, AutoLoader::find( TestAutoloadedLocalClass::class ) );
	}

	public function testCoreClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedLocalClass' ) );
	}

	public function testExtensionClass() {
		$this->assertTrue( class_exists( 'TestAnotherAutoloadedClass' ) );
	}

	public function testLegacyExtensionClass() {
		$this->assertTrue( class_exists( 'TestAutoloadedClass' ) );
	}

	public function testPsr4() {
		$this->assertTrue( class_exists( 'Test\\MediaWiki\\AutoLoader\\TestFooBar' ) );
	}

	/**
	 * See T398513
	 * This does not test if the namespace matched, as not all classes follow PSR-4
	 * Remove this test when all classes are namespaced, it is covered by PSR-4 testing
	 */
	public function testCapitaliseFolder() {
		global $wgAutoloadLocalClasses, $IP;

		$error = [];
		$prefixLen = strlen( $IP ) + 1;
		foreach ( $wgAutoloadLocalClasses as $file ) {
			$slash = strrpos( $file, '/' );
			$filename = substr( $file, $slash + 1 );
			$subPath = substr( $file, $prefixLen, $slash - $prefixLen );
			if ( !str_starts_with( $subPath, 'includes/' ) ) {
				continue;
			}
			if ( preg_match( '#/(?!libs)[^A-Z]#', $subPath ) ) {
				$error[$filename] = $subPath;
			}
		}
		$this->assertSame( [], $error, 'All folder with php classes must start with upper case' );
	}

	public function testPsr4Mapping() {
		global $wgAutoloadLocalClasses, $IP;

		$error = [];
		$prefixLen = strlen( $IP ) + 1;
		$classNoNamespace = array_flip( self::CLASS_NO_NAMESPACES );
		foreach ( $wgAutoloadLocalClasses as $className => $file ) {
			$extDot = strrpos( $file, '.' );
			$subPath = substr( $file, $prefixLen, $extDot - $prefixLen );

			// Ignore maintenance scripts - T411218
			if ( !str_starts_with( $subPath, 'includes/' ) ) {
				continue;
			}

			// Ignore classes incompatibile with namespaces
			if ( isset( $classNoNamespace[$className] ) ) {
				continue;
			}

			// Ignore Message until re-namespaced - T411216
			if ( str_starts_with( $className, 'MediaWiki\\Message\\' )
				// Ignore Rdbms until folder structure reworked - T411217
				|| str_starts_with( $className, 'Wikimedia\\Rdbms\\' )
			) {
				continue;
			}

			$reflectionClass = new ReflectionClass( $className );
			// class-alias supported for backward compatibility on cross-namespace moves are not relevant
			if ( $reflectionClass->getName() !== $className ) {
				continue;
			}

			// Classes in the global namespace should not exists
			if ( !str_contains( $className, '\\' ) ) {
				$error[$className] = 'Missing namespace declaration';
				continue;
			}

			if ( str_starts_with( $subPath, 'includes/libs/' ) ) {
				$subPath = substr( $subPath, 14 );
				$highLevelNamespace = 'Wikimedia\\';
			} else {
				$subPath = substr( $subPath, 9 );
				$highLevelNamespace = 'MediaWiki\\';
			}

			$expectedClassName = $highLevelNamespace . strtr( $subPath, [ '/' => '\\' ] );
			if ( $expectedClassName !== $className ) {
				$error[$className] = $expectedClassName;
			}
		}
		$this->assertSame( [], $error, 'All namespaced classes should follow PSR-4' );
	}
}
