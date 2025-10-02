<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Tests\Site;

use MediaWiki\Site\Site;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Site\Site
 * @group Site
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteTest extends MediaWikiIntegrationTestCase {

	public static function instanceProvider() {
		return self::arrayWrap( TestSites::getSites() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetInterwikiIds( Site $site ) {
		$this->assertIsArray( $site->getInterwikiIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetNavigationIds( Site $site ) {
		$this->assertIsArray( $site->getNavigationIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testAddNavigationId( Site $site ) {
		$site->addNavigationId( 'foobar' );
		$this->assertContains( 'foobar', $site->getNavigationIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testAddInterwikiId( Site $site ) {
		$site->addInterwikiId( 'foobar' );
		$this->assertContains( 'foobar', $site->getInterwikiIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetLanguageCode( Site $site ) {
		$this->assertThat(
			$site->getLanguageCode(),
			$this->logicalOr( $this->isNull(), $this->isType( 'string' ) )
		);
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSetLanguageCode( Site $site ) {
		$site->setLanguageCode( 'en' );
		$this->assertEquals( 'en', $site->getLanguageCode() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testNormalizePageName( Site $site ) {
		$this->assertIsString( $site->normalizePageName( 'Foobar' ) );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetGlobalId( Site $site ) {
		$this->assertThat(
			$site->getGlobalId(),
			$this->logicalOr( $this->isNull(), $this->isType( 'string' ) )
		);
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSetGlobalId( Site $site ) {
		$site->setGlobalId( 'foobar' );
		$this->assertEquals( 'foobar', $site->getGlobalId() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetType( Site $site ) {
		$this->assertIsString( $site->getType() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetPath( Site $site ) {
		$this->assertThat(
			$site->getPath( 'page_path' ),
			$this->logicalOr( $this->isNull(), $this->isType( 'string' ) )
		);
		$this->assertThat(
			$site->getPath( 'file_path' ),
			$this->logicalOr( $this->isNull(), $this->isType( 'string' ) )
		);
		$this->assertThat(
			$site->getPath( 'foobar' ),
			$this->logicalOr( $this->isNull(), $this->isType( 'string' ) )
		);
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testGetAllPaths( Site $site ) {
		$this->assertIsArray( $site->getAllPaths() );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSetAndRemovePath( Site $site ) {
		$count = count( $site->getAllPaths() );

		$site->setPath( 'spam', 'http://www.wikidata.org/$1' );
		$site->setPath( 'spam', 'http://www.wikidata.org/foo/$1' );
		$site->setPath( 'foobar', 'http://www.wikidata.org/bar/$1' );

		$this->assertCount( $count + 2, $site->getAllPaths() );

		$this->assertIsString( $site->getPath( 'foobar' ) );
		$this->assertEquals( 'http://www.wikidata.org/foo/$1', $site->getPath( 'spam' ) );

		$site->removePath( 'spam' );
		$site->removePath( 'foobar' );

		$this->assertCount( $count, $site->getAllPaths() );

		$this->assertNull( $site->getPath( 'foobar' ) );
		$this->assertNull( $site->getPath( 'spam' ) );
	}

	public function testSetLinkPath() {
		$site = new Site();
		$path = "TestPath/$1";

		$site->setLinkPath( $path );
		$this->assertEquals( $path, $site->getLinkPath() );
	}

	public function testGetLinkPathType() {
		$site = new Site();

		$path = 'TestPath/$1';
		$site->setLinkPath( $path );
		$this->assertEquals( $path, $site->getPath( $site->getLinkPathType() ) );

		$path = 'AnotherPath/$1';
		$site->setPath( $site->getLinkPathType(), $path );
		$this->assertEquals( $path, $site->getLinkPath() );
	}

	public function testSetPath() {
		$site = new Site();

		$path = 'TestPath/$1';
		$site->setPath( 'foo', $path );

		$this->assertEquals( $path, $site->getPath( 'foo' ) );
	}

	public function testProtocolRelativePath() {
		$site = new Site();

		$type = $site->getLinkPathType();
		$path = '//acme.com/'; // protocol-relative URL
		$site->setPath( $type, $path );

		$this->assertSame( '', $site->getProtocol() );
	}

	public static function provideGetPageUrl() {
		// NOTE: the assumption that the URL is built by replacing $1
		//      with the urlencoded version of $page
		//      is true for Site but not guaranteed for subclasses.
		//      Subclasses need to override this provider appropriately.

		return [
			[ # 0
				'http://acme.test/TestPath/$1',
				'Foo',
				'/TestPath/Foo',
			],
			[ # 1
				'http://acme.test/TestScript?x=$1&y=bla',
				'Foo',
				'TestScript?x=Foo&y=bla',
			],
			[ # 2
				'http://acme.test/TestPath/$1',
				'foo & bar/xyzzy (quux-shmoox?)',
				'/TestPath/foo%20%26%20bar%2Fxyzzy%20%28quux-shmoox%3F%29',
			],
		];
	}

	/**
	 * @dataProvider provideGetPageUrl
	 */
	public function testGetPageUrl( $path, $page, $expected ) {
		$site = new Site();

		// NOTE: the assumption that getPageUrl is based on getLinkPath
		//      is true for Site but not guaranteed for subclasses.
		//      Subclasses need to override this test case appropriately.
		$site->setLinkPath( $path );
		$this->assertStringContainsString( $path, $site->getPageUrl() );

		$this->assertStringContainsString( $expected, $site->getPageUrl( $page ) );
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testSerialization( Site $site ) {
		$serialization = serialize( $site );
		$newInstance = unserialize( $serialization );

		$this->assertInstanceOf( Site::class, $newInstance );

		$this->assertEquals( $serialization, serialize( $newInstance ) );
	}
}
