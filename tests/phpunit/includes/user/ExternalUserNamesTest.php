<?php

use MediaWiki\Interwiki\InterwikiLookup;

/**
 * @covers ExternalUserNames
 */
class ExternalUserNamesTest extends MediaWikiTestCase {

	public function provideGetUserLinkTitle() {
		return [
			[ 'valid:>User1', Title::makeTitle( NS_MAIN, ':User:User1', '', 'valid' ) ],
			[
				'valid:valid:>User1',
				Title::makeTitle( NS_MAIN, 'valid::User:User1', '', 'valid' )
			],
			[
				'127.0.0.1',
				Title::makeTitle( NS_SPECIAL, 'Contributions/127.0.0.1', '', '' )
			],
			[ 'invalid:>User1', null ]
		];
	}

	/**
	 * @covers ExternalUserNames::getUserLinkTitle
	 * @dataProvider provideGetUserLinkTitle
	 */
	public function testGetUserLinkTitle( $username, $expected ) {
		$interwikiLookupMock = $this->getMockBuilder( InterwikiLookup::class )
			->getMock();

		$interwikiValueMap = [
			[ 'valid', true ],
			[ 'invalid', false ]
		];
		$interwikiLookupMock->expects( $this->any() )
			->method( 'isValidInterwiki' )
			->will( $this->returnValueMap( $interwikiValueMap ) );

		$this->setService( 'InterwikiLookup', $interwikiLookupMock );

		$this->assertEquals(
			$expected,
			ExternalUserNames::getUserLinkTitle( $username )
		);
	}

	public function provideApplyPrefix() {
		return [
			[ 'User1', 'prefix', 'prefix>User1' ],
			[ 'User1', 'prefix:>', 'prefix>User1' ],
			[ 'User1', 'prefix:', 'prefix>User1' ],
		];
	}

	/**
	 * @covers ExternalUserNames::applyPrefix
	 * @dataProvider provideApplyPrefix
	 */
	public function testApplyPrefix( $username, $prefix, $expected ) {
		$externalUserNames = new ExternalUserNames( $prefix, true );

		$this->assertSame(
			$expected,
			$externalUserNames->applyPrefix( $username )
		);
	}

	public function provideAddPrefix() {
		return [
			[ 'User1', 'prefix', 'prefix>User1' ],
			[ 'User2', 'prefix2', 'prefix2>User2' ],
			[ 'User3', 'prefix3', 'prefix3>User3' ],
		];
	}

	/**
	 * @covers ExternalUserNames::addPrefix
	 * @dataProvider provideAddPrefix
	 */
	public function testAddPrefix( $username, $prefix, $expected ) {
		$externalUserNames = new ExternalUserNames( $prefix, true );

		$this->assertSame(
			$expected,
			$externalUserNames->addPrefix( $username )
		);
	}

	public function provideIsExternal() {
		return [
			[ 'User1', false ],
			[ '>User1', true ],
			[ 'prefix>User1', true ],
			[ 'prefix:>User1', true ],
		];
	}

	/**
	 * @covers ExternalUserNames::isExternal
	 * @dataProvider provideIsExternal
	 */
	public function testIsExternal( $username, $expected ) {
		$this->assertSame(
			$expected,
			ExternalUserNames::isExternal( $username )
		);
	}

	public function provideGetLocal() {
		return [
			[ 'User1', 'User1' ],
			[ '>User2', 'User2' ],
			[ 'prefix>User3', 'User3' ],
			[ 'prefix:>User4', 'User4' ],
		];
	}

	/**
	 * @covers ExternalUserNames::getLocal
	 * @dataProvider provideGetLocal
	 */
	public function testGetLocal( $username, $expected ) {
		$this->assertSame(
			$expected,
			ExternalUserNames::getLocal( $username )
		);
	}

}
