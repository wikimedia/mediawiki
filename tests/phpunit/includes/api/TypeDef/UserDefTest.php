<?php

namespace MediaWiki\Api\TypeDef;

use ApiMain;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\UserDef
 */
class UserDefTest extends MediaWikiLangTestCase {

	/** @dataProvider provideValidate */
	public function testValidate( $value, $expect ) {
		$typeDef = new UserDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, [], $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, [], $api ) );
			$this->assertEquals( [], $api->warnings );
		}
	}

	public static function provideValidate() {
		return [
			'Basic' => [ 'Some user', 'Some user' ],
			'Normalized' => [ 'some_user', 'Some user' ],
			'External' => [ 'm>some_user', 'm>some_user' ],
			'IPv4' => [ '192.168.0.1', '192.168.0.1' ],
			'IPv4, normalized' => [ '192.168.000.001', '192.168.0.1' ],
			'IPv6' => [ '2001:DB8:0:0:0:0:0:0', '2001:DB8:0:0:0:0:0:0' ],
			'IPv6, normalized' => [ '2001:0db8::', '2001:DB8:0:0:0:0:0:0' ],
			'IPv4 range' => [ '192.168.000.000/16', '192.168.0.0/16' ],
			'IPv6 range' => [ '2001:0DB8::/64', '2001:DB8:0:0:0:0:0:0/64' ],
			'Usemod IP' => [ '192.168.0.xxx', '192.168.0.xxx' ],
			'Bogus IP' => [ '192.168.0.256',
				ApiUsageException::newWithMessage( null, [ 'apierror-baduser', 'ttfoobar', '192.168.0.256' ] )
			],
			'No namespaces' => [ 'Talk:Foo',
				ApiUsageException::newWithMessage( null, [ 'apierror-baduser', 'ttfoobar', 'Talk:Foo' ] )
			],
			'No namespaces (except User is ok)' => [ 'User:some_user', 'Some user' ],
		];
	}

}
