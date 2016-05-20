<?php
use MediaWiki\Site\MutableSite;

/**
 * Tests for the MutableSite class.
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
 * @file
 * @since 1.21
 *
 * @ingroup Site
 * @ingroup Test
 *
 * @group Site
 * @covers MediaWiki\MutableSite\MutableSite
 */
class MutableSiteTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers MutableSite::__construct
	 * @covers MutableSite::getType
	 */
	public function testConstructor() {
		$site = new MutableSite( 'foo' );
		$this->assertSame( 'foo', $site->getGlobalId(), 'getGlobalId' );
		$this->assertSame( Site::TYPE_UNKNOWN, $site->getType(), 'getType' );

		$site = new MutableSite( 'foo', 'XYZ' );
		$this->assertSame( 'XYZ', $site->getType(), 'getType' );

		$this->assertSame( Site::SOURCE_LOCAL, $site->getSource(), 'getSource' );
		$this->assertSame( null, $site->getGroup(), 'getGroup' );
		$this->assertSame( [], $site->getGroups( 'nope' ), 'getGroups' );
		$this->assertSame( [], $site->getIds( 'nope' ), 'getIds' );
		$this->assertSame( null, $site->getProperty( 'nope' ), 'getProperty' );
		$this->assertSame( [], $site->getAllPaths(), 'getAllPaths' );
		$this->assertSame( null, $site->getPath( 'nope' ), 'getPath' );
		$this->assertSame( null, $site->getLinkPath(), 'getLinkPath' );
		$this->assertSame( null, $site->getDomain(), 'getDomain' );
		$this->assertSame( '', $site->getProtocol(), 'getProtocol' );
		$this->assertSame( false, $site->shouldForward(), 'shouldForward' );
	}

	/**
	 * @covers MutableSite::setSource
	 * @covers MutableSite::getSource
	 */
	public function testGetSource() {
		$site = new MutableSite( 'foo' );

		$site->setSource( 'TEST' );
		$this->assertSame( 'TEST', $site->getSource() );
	}

	/**
	 * @covers MutableSite::setGlobalId
	 * @covers MutableSite::getGlobalId
	 */
	public function testGetGlobalId() {
		$site = new MutableSite( 'foo' );
		$this->assertSame( 'foo', $site->getGlobalId() );

		$site->setGlobalId( 'LOO' );
		$this->assertSame( 'LOO', $site->getGlobalId() );

		$site->setIds( Site::ID_GLOBAL, [ 'MOO', 'GOO' ] );
		$this->assertSame( 'MOO', $site->getGlobalId() );
	}

	/**
	 * @covers MutableSite::setGroup
	 * @covers MutableSite::getGroup
	 */
	public function testGetGroup() {
		$site = new MutableSite( 'foo' );

		$site->setGroup( 'LOO' );
		$this->assertSame( 'LOO', $site->getGroup() );

		$site->setGroups( Site::GROUP_FAMILY, [ 'MOO', 'GOO' ] );
		$this->assertSame( 'MOO', $site->getGroup() );
	}

	/**
	 * @covers MutableSite::setGroups
	 * @covers MutableSite::getGroups
	 */
	public function testGetGroups() {
		$site = new MutableSite( 'foo' );

		$this->assertSame( [], $site->getGroups( 'spam' ) );

		$site->setGroups( Site::GROUP_FAMILY, [ 'pedia', 'media' ] );
		$site->setGroups( Site::GROUP_LANGUAGE, [ 'de', '-ch' ] );

		$this->assertEquals( [ 'pedia', 'media' ], $site->getGroups( Site::GROUP_FAMILY ) );
		$this->assertEquals( [ 'de', '-ch' ], $site->getGroups( Site::GROUP_LANGUAGE ) );

		$site->setGroups( Site::GROUP_FAMILY, [ 'test' ] );
		$this->assertEquals( [ 'test' ], $site->getGroups( Site::GROUP_FAMILY ) );
	}

	/**
	 * @covers MutableSite::getGroupScopes
	 */
	public function testGetGroupScopes() {
		$site = new MutableSite( 'foo' );

		$site->setGroups( Site::GROUP_FAMILY, [ 'pedia', 'media' ] );
		$site->setGroups( Site::GROUP_LANGUAGE, [ 'de', '-ch' ] );
		$this->assertEquals( [ Site::GROUP_FAMILY, Site::GROUP_LANGUAGE ], $site->getGroupScopes() );
	}

	/**
	 * @covers MutableSite::setPath
	 * @covers MutableSite::getPath
	 */
	public function testGetPath() {
		$site = new MutableSite( 'test' );

		$this->assertSame( null, $site->getPath( 'spam' ) );

		$site->setPath( 'spam', '//foo.bar/' );
		$this->assertSame( '//foo.bar/', $site->getPath( 'spam' ) );

		$site->setPath( 'frob', '//bad.wolf/' );
		$this->assertSame( '//foo.bar/', $site->getPath( 'spam' ) );
		$this->assertSame( '//bad.wolf/', $site->getPath( 'frob' ) );

		$site->setPath( 'spam', '//schnuddelduddel.text' );
		$this->assertSame( '//schnuddelduddel.text', $site->getPath( 'spam' ) );
	}

	/**
	 * @covers MutableSite::getLinkPath
	 * @covers MutableSite::setLinkPath
	 */
	public function testGetLinkPath() {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( '//foo.bar/$1' );
		$this->assertSame( '//foo.bar/$1', $site->getLinkPath() );

		$site->setPath( Site::PATH_LINK, '//xyz' );
		$this->assertSame( '//xyz', $site->getLinkPath() );
	}

	/**
	 * @covers MutableSite::shouldForward
	 * @covers MutableSite::setForward
	 */
	public function testShouldForward() {
		$site = new MutableSite( 'test' );

		$site->setForward( true );
		$this->assertSame( true, $site->shouldForward() );

		$site->setProperty( 'forward', false );
		$this->assertSame( false, $site->shouldForward() );
	}

	/**
	 * @covers MutableSite::getLanguageCode
	 * @covers MutableSite::setLanguageCode
	 */
	public function testGetLanguageCode() {
		$site = new MutableSite( 'test' );

		$site->setLanguageCode( 'xy' );
		$this->assertSame( 'xy', $site->getLanguageCode() );

		$site->setProperty( 'language', 'ab' );
		$this->assertSame( 'ab', $site->getLanguageCode() );
	}

	/**
	 * @covers MutableSite::expandPath
	 */
	public function testExpandPath() {
		$site = new MutableSite( 'test' );

		$site->setPath( 'spam', '//foo.bar/$1/$2' );
		$this->assertSame( '//foo.bar/$1/$2', $site->expandPath( 'spam' ) );
		$this->assertSame( '//foo.bar/x%23y/$2', $site->expandPath( 'spam', 'x#y' ) );
		$this->assertSame( '//foo.bar/x%23y/a%2Bb', $site->expandPath( 'spam', 'x#y', 'a+b' ) );
		$this->assertSame( '//foo.bar/x%23y/a%2Bb', $site->expandPath( 'spam', 'x#y', 'a+b', 'more' ) );
	}

	/**
	 * @covers MutableSite::setAllPaths
	 * @covers MutableSite::getAllPaths
	 */
	public function testGetAllPaths() {
		$site = new MutableSite( 'test' );

		$site->setAllPaths( [ 'spam' => '//ONE', 'foobar' => '//TWO' ] );
		$site->setPath( 'spam', '//ONE!' );

		$this->assertEquals( [ 'spam' => '//ONE!', 'foobar' => '//TWO' ], $site->getAllPaths() );

		$site->setAllPaths( [ 'x' => 'y' ] );
		$this->assertEquals( [ 'x' => 'y' ], $site->getAllPaths() );
	}

	/**
	 * @covers MutableSite::setAllProperties
	 * @covers MutableSite::getAllProperties
	 */
	public function testGetAllProperties() {
		$site = new MutableSite( 'test' );

		$site->setAllProperties( [ 'spam' => 'one', 'foobar' => 'two' ] );
		$site->setProperty( 'spam', 1 );

		$this->assertEquals( [ 'spam' => 1, 'foobar' => 'two' ], $site->getAllProperties() );

		$site->setAllProperties( [ 'x' => 'y' ] );
		$this->assertEquals( [ 'x' => 'y' ], $site->getAllProperties() );
	}

	/**
	 * @covers MutableSite::setProperty
	 * @covers MutableSite::getProperty
	 */
	public function testGetProperty() {
		$site = new MutableSite( 'test' );

		$this->assertSame( null, $site->getProperty( 'spam' ) );
		$this->assertSame( 'KITTEN', $site->getProperty( 'spam', 'KITTEN' ) );

		$site->setProperty( 'spam', 'foo' );
		$this->assertSame( 'foo', $site->getProperty( 'spam', 'KITTEN' ) );

		$site->setProperty( 'frob', 23 );
		$this->assertSame( 'foo', $site->getProperty( 'spam' ) );
		$this->assertSame( 23, $site->getProperty( 'frob' ) );

		$site->setProperty( 'spam', 2.5 );
		$this->assertEquals( 2.5, $site->getProperty( 'spam' ) );
	}

	public function provideGetProtocol() {
		return [
			'empty' => [ '', '' ],
			'protocol-relative' => [ '//acme.test/', '' ],
			'http' => [ 'http://acme.test/', 'http' ],
			'invalid' => [ '-://:-', '' ],
		];
	}

	/**
	 * @dataProvider provideGetProtocol
	 * @covers MutableSite::getProtocol
	 */
	public function testGetProtocol( $path, $protocol ) {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( $path );
		$this->assertEquals( $protocol, $site->getProtocol() );
	}

	/**
	 * @covers MutableSite::getProtocol
	 */
	public function testGetProtocol_override() {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( 'http//acme.test/' );
		$site->setProperty( 'protocol', 'ftp' );
		$this->assertEquals( 'ftp', $site->getProtocol() );
	}

	public function provideGetDomain() {
		return [
			'empty' => [ '', '' ],
			'acme.test' => [ 'http://acme.test//foo.bar/', 'acme.test' ],
			'invalid' => [ '-://:-', '' ],
		];
	}

	/**
	 * @dataProvider provideGetDomain
	 * @covers MutableSite::getDomain
	 */
	public function testGetDomain( $path, $protocol ) {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( $path );
		$this->assertEquals( $protocol, $site->getDomain() );
	}

	/**
	 * @covers MutableSite::getDomain
	 */
	public function testGetDomain_override() {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( 'http//acme.test/' );
		$site->setProperty( 'domain', 'acme.foo' );
		$this->assertEquals( 'acme.foo', $site->getDomain() );
	}

	public static function provideGetPageUrl() {
		// The URL is built by replacing $1 with the urlencoded version of $page.

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
	 * @covers MutableSite::getPageUrl
	 */
	public function testGetPageUrl( $path, $page, $expected ) {
		$site = new MutableSite( 'test' );

		$site->setLinkPath( $path );
		$this->assertContains( $path, $site->getPageUrl() );

		$this->assertContains( $expected, $site->getPageUrl( $page ) );
	}

	/**
	 * @covers MutableSite::setIds
	 * @covers MutableSite::getIds
	 */
	public function testGetIds() {
		$site = new MutableSite( 'foo' );

		$this->assertSame( [], $site->getIds( 'spam' ) );

		$site->setIds( Site::ID_INTERWIKI, [ 'xy', 'ab' ] );
		$site->setIds( Site::ID_GLOBAL, [ 'xywiki', 'abwiki' ] );

		$this->assertEquals( [ 'xy', 'ab' ], $site->getIds( Site::ID_INTERWIKI ) );
		$this->assertEquals( [ 'xywiki', 'abwiki' ], $site->getIds( Site::ID_GLOBAL ) );

		$site->setIds( Site::ID_INTERWIKI, [ 'test' ] );
		$this->assertEquals( [ 'test' ], $site->getIds( Site::ID_INTERWIKI ) );
	}

	/**
	 * @covers MutableSite::getIdScopes
	 */
	public function testGetIdScopes() {
		$site = new MutableSite( 'foo' );

		$site->setIds( Site::ID_GLOBAL, [ 'dewiki' ] );
		$site->setIds( Site::ID_INTERWIKI, [ 'de', 'xy' ] );
		$this->assertEquals( [ Site::ID_GLOBAL, Site::ID_INTERWIKI ], $site->getIdScopes() );
	}

	/**
	 * @covers MutableSite::addLocalId
	 */
	public function testAddLocalId() {
		$site = new MutableSite( 'xxwiki' );

		$site->addLocalId( Site::ID_INTERWIKI, 'xy' );
		$site->addLocalId( Site::ID_GLOBAL, 'xywiki' );

		$this->assertEquals( [ 'xy' ], $site->getIds( Site::ID_INTERWIKI ) );
		$this->assertEquals( [ 'xxwiki', 'xywiki' ], $site->getIds( Site::ID_GLOBAL ) );

		$site->addLocalId( Site::ID_INTERWIKI, 'ab' );
		$this->assertEquals( [ 'xy', 'ab' ], $site->getIds( Site::ID_INTERWIKI ) );
	}

	/**
	 * @covers MutableSite::addNavigationId
	 * @covers MutableSite::getNavigationIds
	 */
	public function testAddNavigationId() {
		$site = new MutableSite( 'foo' );

		$site->addNavigationId( 'foo' );
		$site->addNavigationId( 'bar' );

		$this->assertEquals( [ 'foo', 'bar' ], $site->getNavigationIds() );
	}

	/**
	 * @covers MutableSite::addInterwikiId
	 * @covers MutableSite::getInterwikiIds
	 */
	public function testAddInterwikiId() {
		$site = new MutableSite( 'foo' );

		$site->addInterwikiId( 'foo' );
		$site->addInterwikiId( 'bar' );

		$this->assertEquals( [ 'foo', 'bar' ], $site->getInterwikiIds() );
	}

}
