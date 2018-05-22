<?php

namespace MediaWiki\Api;

use ApiBase;
use ApiMain;
use ApiUsageException;
use FauxRequest;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef
 */
class TypeDefTest extends MediaWikiLangTestCase {

	public function testMisc() {
		$typeDef = $this->getMockBuilder( TypeDef::class )->getMockForAbstractClass();
		$this->assertTrue( $typeDef->needsHelpParamMultiSeparate() );
		$this->assertSame( [ 'foobar' ], $typeDef->normalizeSettings( [ 'foobar' ] ) );
		$this->assertNull( $typeDef->getEnumValues( 'foobar', [], new MockApi ) );
	}

	/** @dataProvider provideGet */
	public function testGet( $value, $settings, $expect, $warn = [] ) {
		$api = new MockApi;
		$req = new FauxRequest( $value === null ? [] : [ 'ttfoobar' => $value ] );
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain( $req );
		$w->mModulePrefix = 'tt';

		$typeDef = $this->getMockBuilder( TypeDef::class )->getMockForAbstractClass();
		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->get( 'foobar', $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->get( 'foobar', $settings, $api ) );
			$this->assertSame( $warn, $api->warnings );
		}
	}

	public static function provideGet() {
		return [
			'Simple' => [ 'value', [], 'value' ],
			'Missing' => [ null, [ ApiBase::PARAM_DFLT => 'x' ], null ],
			'Not multi' => [
				"\x1F",
				[],
				ApiUsageException::newWithMessage( null, 'apierror-badvalue-notmultivalue' )
			],
			'Multi' => [
				"\x1Ffoo\x1Fbar",
				[ ApiBase::PARAM_ISMULTI => true ],
				"\x1Ffoo\x1Fbar"
			],
			'Not NFC' => [
				"a\xcc\x81",
				[],
				"\xc3\xa1",
				[ [ 'apiwarn-badutf8', 'ttfoobar' ] ],
			],
		];
	}

	public function testGetHelpInfo() {
		$typeDef = $this->getMockBuilder( TypeDef::class )->getMockForAbstractClass();
		$this->assertSame( [], $typeDef->getHelpInfo(
			RequestContext::getMain(), 'foobar', [ ApiBase::PARAM_TYPE => 'xxx' ], new MockApi
		) );
		$this->assertSame( [ 'Type: integer or <kbd>max</kbd>' ], $typeDef->getHelpInfo(
			RequestContext::getMain(), 'foobar', [ ApiBase::PARAM_TYPE => 'limit' ], new MockApi
		) );
	}

	public function testGetParamInfo() {
		$typeDef = $this->getMockBuilder( TypeDef::class )->getMockForAbstractClass();
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => 'xyz' ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => 'xyz' ], new MockApi
		) );
	}

}
