<?php

use LightnCandy\LightnCandy;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Templates
 * @coversDefaultClass TemplateParser
 */
class TemplateParserIntegrationTest extends MediaWikiIntegrationTestCase {

	private const TEMPLATE_NAME = 'foobar';

	private $secretKey;
	private $cache;
	private $templateDir;
	private $templateParser;

	private function makeKey() : string {
		return $this->cache->makeKey(
			'lightncandy-compiled',

			// See TemplateParser::$cacheVersion
			'2.2.0',

			// See TemplateParser::__construct and TemplateParser::$compileFlags
			LightnCandy::FLAG_ERROR_EXCEPTION | LightnCandy::FLAG_MUSTACHELOOKUP,

			$this->templateDir,
			self::TEMPLATE_NAME
		);
	}

	private function getTemplate() {
		//
		// NOTE: Deliberately destroy the per-instance cache every time we render a template in
		// order to test interactions with the server-local cache.
		$templateParser = TestingAccessWrapper::newFromObject(
			new TemplateParser( $this->templateDir )
		);

		return $templateParser->getTemplate( self::TEMPLATE_NAME );
	}

	// Tests
	// =====

	protected function setUp() : void {
		parent::setUp();

		// Data
		$this->secretKey = 'foo';
		$this->setMwGlobals( [
			'wgSecretKey' => $this->secretKey,
		] );
		$this->templateDir = __DIR__ . '/../../data/templates';

		// Stubs
		$this->cache = ObjectCache::getLocalServerInstance( CACHE_ANYTHING );
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateCachesCompilationResult() {
		$this->getTemplate();

		$value = $this->cache->get( $this->makeKey() );

		$this->assertIsArray( $value );
		$this->assertArrayHasKey( 'phpCode', $value );
		$this->assertArrayHasKey( 'files', $value );
		$this->assertArrayHasKey( 'filesHash', $value );

		// ---

		$this->assertEquals(
			hash_hmac( 'sha256', $value['phpCode'], $this->secretKey ),
			$value['integrityHash'],
			'::getTemplate adds an integrity hash to the compiled template before caching it.'
		);
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateInvalidatesCacheWhenFilesHashIsInvalid() {
		$key = $this->makeKey();
		$this->cache->set( $key, [
			'phpCode' => 'foo',
			'files' => [ 'bar' ],
			'filesHash' => 'baz',
		] );

		$this->getTemplate();

		$expectedFiles = [ $this->templateDir . '/' . self::TEMPLATE_NAME . '.mustache' ];
		$expectedFilesHash = FileContentsHasher::getFileContentsHash( $expectedFiles );

		$value = $this->cache->get( $this->makeKey() );

		$this->assertNotEquals( 'foo', $value['phpCode'] );
		$this->assertEquals( $expectedFiles, $value['files'] );
		$this->assertEquals(
			$expectedFilesHash,
			$value['filesHash'],
			'The cached compiled template was invalidated.'
		);
	}

	/**
	 * @covers ::getTemplate
	 */
	public function testGetTemplateInvalidatesCacheWhenIntegrityHashIsInvalid() {
		$this->getTemplate();

		$key = $this->makeKey();
		$value = $this->cache->get( $key );

		$value['integrityHash'] = 'foo';
		$this->cache->set( $key, $value );

		$this->getTemplate();

		$newValue = $this->cache->get( $key );

		$this->assertNotEquals(
			$newValue['integrityHash'],
			$value['integrityHash'],
			'The cached compiled template was invalidated.'
		);
	}
}
