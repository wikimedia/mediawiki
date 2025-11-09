<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Autoload
 * @covers \AutoLoader
 */
class AutoLoaderTest extends MediaWikiIntegrationTestCase {

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
}
