<?php

/**
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
 * @file
 * @since 1.21
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 *
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class SiteTest extends MediaWikiTestCase {

	public function instanceProvider() {
		return $this->arrayWrap( TestSites::getSites() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getInterwikiIds
	 */
	public function testGetInterwikiIds( Site $site ) {
		$this->assertInternalType( 'array', $site->getInterwikiIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getNavigationIds
	 */
	public function testGetNavigationIds( Site $site ) {
		$this->assertInternalType( 'array', $site->getNavigationIds() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::addNavigationId
	 */
	public function testAddNavigationId( Site $site ) {
		$site->addNavigationId( 'foobar' );
		$this->assertTrue( in_array( 'foobar', $site->getNavigationIds(), true ) );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::addInterwikiId
	 */
	public function testAddInterwikiId( Site $site ) {
		$site->addInterwikiId( 'foobar' );
		$this->assertTrue( in_array( 'foobar', $site->getInterwikiIds(), true ) );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getLanguageCode
	 */
	public function testGetLanguageCode( Site $site ) {
		$this->assertTypeOrValue( 'string', $site->getLanguageCode(), null );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::setLanguageCode
	 */
	public function testSetLanguageCode( Site $site ) {
		$site->setLanguageCode( 'en' );
		$this->assertEquals( 'en', $site->getLanguageCode() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::normalizePageName
	 */
	public function testNormalizePageName( Site $site ) {
		$this->assertInternalType( 'string', $site->normalizePageName( 'Foobar' ) );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getGlobalId
	 */
	public function testGetGlobalId( Site $site ) {
		$this->assertTypeOrValue( 'string', $site->getGlobalId(), null );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::setGlobalId
	 */
	public function testSetGlobalId( Site $site ) {
		$site->setGlobalId( 'foobar' );
		$this->assertEquals( 'foobar', $site->getGlobalId() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getType
	 */
	public function testGetType( Site $site ) {
		$this->assertInternalType( 'string', $site->getType() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getPath
	 */
	public function testGetPath( Site $site ) {
		$this->assertTypeOrValue( 'string', $site->getPath( 'page_path' ), null );
		$this->assertTypeOrValue( 'string', $site->getPath( 'file_path' ), null );
		$this->assertTypeOrValue( 'string', $site->getPath( 'foobar' ), null );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::getAllPaths
	 */
	public function testGetAllPaths( Site $site ) {
		$this->assertInternalType( 'array', $site->getAllPaths() );
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::setPath
	 * @covers Site::removePath
	 */
	public function testSetAndRemovePath( Site $site ) {
		$count = count( $site->getAllPaths() );

		$site->setPath( 'spam', 'http://www.wikidata.org/$1' );
		$site->setPath( 'spam', 'http://www.wikidata.org/foo/$1' );
		$site->setPath( 'foobar', 'http://www.wikidata.org/bar/$1' );

		$this->assertEquals( $count + 2, count( $site->getAllPaths() ) );

		$this->assertInternalType( 'string', $site->getPath( 'foobar' ) );
		$this->assertEquals( 'http://www.wikidata.org/foo/$1', $site->getPath( 'spam' ) );

		$site->removePath( 'spam' );
		$site->removePath( 'foobar' );

		$this->assertEquals( $count, count( $site->getAllPaths() ) );

		$this->assertNull( $site->getPath( 'foobar' ) );
		$this->assertNull( $site->getPath( 'spam' ) );
	}

	/**
	 * @covers Site::setLinkPath
	 */
	public function testSetLinkPath() {
		$site = new Site();
		$path = "TestPath/$1";

		$site->setLinkPath( $path );
		$this->assertEquals( $path, $site->getLinkPath() );
	}

	/**
	 * @covers Site::getLinkPathType
	 */
	public function testGetLinkPathType() {
		$site = new Site();

		$path = 'TestPath/$1';
		$site->setLinkPath( $path );
		$this->assertEquals( $path, $site->getPath( $site->getLinkPathType() ) );

		$path = 'AnotherPath/$1';
		$site->setPath( $site->getLinkPathType(), $path );
		$this->assertEquals( $path, $site->getLinkPath() );
	}

	/**
	 * @covers Site::setPath
	 */
	public function testSetPath() {
		$site = new Site();

		$path = 'TestPath/$1';
		$site->setPath( 'foo', $path );

		$this->assertEquals( $path, $site->getPath( 'foo' ) );
	}

	/**
	 * @covers Site::setPath
	 * @covers Site::getProtocol
	 */
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
	 * @covers Site::getPageUrl
	 */
	public function testGetPageUrl( $path, $page, $expected ) {
		$site = new Site();

		// NOTE: the assumption that getPageUrl is based on getLinkPath
		//      is true for Site but not guaranteed for subclasses.
		//      Subclasses need to override this test case appropriately.
		$site->setLinkPath( $path );
		$this->assertContains( $path, $site->getPageUrl() );

		$this->assertContains( $expected, $site->getPageUrl( $page ) );
	}

	protected function assertTypeOrFalse( $type, $value ) {
		if ( $value === false ) {
			$this->assertTrue( true );
		} else {
			$this->assertInternalType( $type, $value );
		}
	}

	/**
	 * @dataProvider instanceProvider
	 * @param Site $site
	 * @covers Site::serialize
	 * @covers Site::unserialize
	 */
	public function testSerialization( Site $site ) {
		$this->assertInstanceOf( Serializable::class, $site );

		$serialization = serialize( $site );
		$newInstance = unserialize( $serialization );

		$this->assertInstanceOf( Site::class, $newInstance );

		$this->assertEquals( $serialization, serialize( $newInstance ) );
	}
}
