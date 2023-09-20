<?php

namespace MediaWiki\Tests\ResourceLoader;

use EmptyResourceLoader;
use MediaWiki\Config\HashConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\User\User;
use MediaWikiCoversValidator;
use Message;

/**
 * See also:
 * - ImageModuleTest::testContext
 *
 * @group ResourceLoader
 * @covers \MediaWiki\ResourceLoader\Context
 */
class ContextTest extends \PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	protected static function getResourceLoader() {
		return new EmptyResourceLoader( new HashConfig( [
			MainConfigNames::ResourceLoaderDebug => false,
			MainConfigNames::LoadScript => '/w/load.php',
		] ) );
	}

	public function testEmpty() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );

		// Request parameters
		$this->assertEquals( [], $ctx->getModules() );
		$this->assertEquals( 'qqx', $ctx->getLanguage() );
		$this->assertSame( 0, $ctx->getDebug() );
		$this->assertNull( $ctx->getOnly() );
		$this->assertEquals( 'fallback', $ctx->getSkin() );
		$this->assertNull( $ctx->getUser() );
		$this->assertNull( $ctx->getContentOverrideCallback() );

		// Misc
		$this->assertEquals( 'ltr', $ctx->getDirection() );
		$this->assertEquals( 'qqx|fallback|0|||||||', $ctx->getHash() );
		$this->assertSame( [], $ctx->getReqBase() );
		$this->assertInstanceOf( User::class, $ctx->getUserObj() );
		$this->assertNull( $ctx->getUserIdentity() );
	}

	public function testDummy() {
		$this->assertInstanceOf(
			Context::class,
			Context::newDummyContext()
		);
	}

	public function testAccessors() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );
		$this->assertInstanceOf( ResourceLoader::class, $ctx->getResourceLoader() );
		$this->assertInstanceOf( WebRequest::class, $ctx->getRequest() );
		$this->assertInstanceOf( \Psr\Log\LoggerInterface::class, $ctx->getLogger() );
	}

	public function testTypicalRequest() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [
			'debug' => 'false',
			'lang' => 'zh',
			'modules' => 'foo|foo.quux,baz,bar|baz.quux',
			'only' => 'styles',
			'skin' => 'fallback',
		] ) );

		// Request parameters
		$this->assertEquals(
			[ 'foo', 'foo.quux', 'foo.baz', 'foo.bar', 'baz.quux' ],
			$ctx->getModules()
		);
		$this->assertSame( 0, $ctx->getDebug() );
		$this->assertEquals( 'zh', $ctx->getLanguage() );
		$this->assertEquals( 'styles', $ctx->getOnly() );
		$this->assertEquals( 'fallback', $ctx->getSkin() );
		$this->assertNull( $ctx->getUser() );

		// Misc
		$this->assertEquals( 'ltr', $ctx->getDirection() );
		$this->assertEquals( 'zh|fallback|0||styles|||||', $ctx->getHash() );
		$this->assertSame( [ 'lang' => 'zh' ], $ctx->getReqBase() );
	}

	public static function provideDirection() {
		yield 'LTR language' => [
			[ 'lang' => 'en' ],
			'ltr',
		];
		yield 'RTL language' => [
			[ 'lang' => 'he' ],
			'rtl',
		];
		yield 'explicit LTR' => [
			[ 'lang' => 'he', 'dir' => 'ltr' ],
			'ltr',
		];
		yield 'explicit RTL' => [
			[ 'lang' => 'en', 'dir' => 'rtl' ],
			'rtl',
		];
		// Not supported, but tested to cover the case and detect change
		yield 'invalid dir' => [
			[ 'lang' => 'he', 'dir' => 'xyz' ],
			'rtl',
		];
	}

	/**
	 * @dataProvider provideDirection
	 */
	public function testDirection( array $params, $expected ) {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( $params ) );
		$this->assertEquals( $expected, $ctx->getDirection() );
	}

	public function testShouldInclude() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );
		$this->assertTrue( $ctx->shouldIncludeScripts(), 'Scripts in combined' );
		$this->assertTrue( $ctx->shouldIncludeStyles(), 'Styles in combined' );
		$this->assertTrue( $ctx->shouldIncludeMessages(), 'Messages in combined' );

		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [
			'only' => 'styles'
		] ) );
		$this->assertFalse( $ctx->shouldIncludeScripts(), 'Scripts not in styles-only' );
		$this->assertTrue( $ctx->shouldIncludeStyles(), 'Styles in styles-only' );
		$this->assertFalse( $ctx->shouldIncludeMessages(), 'Messages not in styles-only' );

		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [
			'only' => 'scripts'
		] ) );
		$this->assertTrue( $ctx->shouldIncludeScripts(), 'Scripts in scripts-only' );
		$this->assertFalse( $ctx->shouldIncludeStyles(), 'Styles not in scripts-only' );
		$this->assertFalse( $ctx->shouldIncludeMessages(), 'Messages not in scripts-only' );
	}

	public function testGetUser() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );
		$this->assertSame( null, $ctx->getUser() );
		$this->assertFalse( $ctx->getUserObj()->isRegistered() );
		$this->assertNull( $ctx->getUserIdentity() );

		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [
			'user' => 'Example'
		] ) );
		$this->assertSame( 'Example', $ctx->getUser() );
		$this->assertEquals( 'Example', $ctx->getUserObj()->getName() );
		$this->assertEquals( 'Example', $ctx->getUserIdentity()->getName() );
	}

	public function testMsg() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [
			'lang' => 'en'
		] ) );
		$msg = $ctx->msg( 'mainpage' );
		$this->assertInstanceOf( Message::class, $msg );
		$this->assertSame( 'Main Page', $msg->useDatabase( false )->plain() );
	}

	public function testEncodeJson() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );

		$json = $ctx->encodeJson( [ 'x' => 'A' ] );
		$this->assertSame( '{"x":"A"}', $json );

		// Regression: https://phabricator.wikimedia.org/T329330
		$json = @$ctx->encodeJson( [
			'x' => 'A',
			'y' => "Foo\x80\xf0Bar",
			'z' => 'C',
		] );
		$this->assertSame( '{"x":"A","y":null,"z":"C"}', $json, 'Ignore invalid UTF-8' );
	}

	public function testEncodeJsonWarning() {
		$ctx = new Context( $this->getResourceLoader(), new FauxRequest( [] ) );

		$this->expectWarning();
		$this->expectWarningMessage( 'encodeJson partially failed: Malformed UTF-8' );
		$ctx->encodeJson( [
			'x' => 'A',
			'y' => "Foo\x80\xf0Bar",
			'z' => 'C',
		] );
	}
}
