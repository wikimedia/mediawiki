<?php

use MediaWiki\Interwiki\InterwikiLookup;

/**
 * @covers ExternalUsernameHandler
 */
class ExternalUsernameHandlerTest extends MediaWikiTestCase {

	public function provideGetExternalUsernameTitle() {
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
	 * @covers ExternalUsernameHandler::getExternalUsernameTitle
	 * @dataProvider provideGetExternalUsernameTitle
	 */
	public function testGetExternalUsernameTitle( $username, $expected ) {
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
			ExternalUsernameHandler::getExternalUsernameTitle( $username )
		);
	}

	public function providePrefixUsername() {
		return [
			[ 'User1', 'prefix', 'prefix>User1' ],
			[ 'User1', 'prefix:>', 'prefix>User1' ],
			[ 'User1', 'prefix:', 'prefix>User1' ],
		];
	}

	/**
	 * @covers ExternalUsernameHandler::prefixUsername
	 * @dataProvider providePrefixUsername
	 */
	public function testPrefixUsername( $username, $prefix, $expected ) {
		$externalUsernameHandler = new ExternalUsernameHandler();
		$externalUsernameHandler->setUsernamePrefix( $prefix, true );

		$this->assertSame(
			$expected,
			$externalUsernameHandler->prefixUsername( $username )
		);
	}

}
