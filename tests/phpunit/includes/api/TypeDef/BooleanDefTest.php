<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use FauxRequest;
use MediaWikiLangTestCase;
use MockApi;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\BooleanDef
 */
class BooleanDefTest extends MediaWikiLangTestCase {

	public function testGet() {
		$api = new MockApi;
		$req = new FauxRequest( [ 'ttfoo' => '' ] );
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain( $req );
		$w->mModulePrefix = 'tt';
		TestingAccessWrapper::newFromObject( $api )->mMainModule = new ApiMain( $req );

		$typeDef = new BooleanDef;
		$this->assertTrue( $typeDef->get( 'foo', [], $api ) );
		$this->assertNull( $typeDef->get( 'bar', [], $api ) );
	}

	public function testValidate() {
		$typeDef = new BooleanDef;
		$this->assertTrue( $typeDef->validate( 'foo', true, [], new MockApi ) );
		$this->assertFalse( $typeDef->validate( 'bar', null, [], new MockApi ) );
	}

	public function testNormalizeSettings() {
		$typeDef = new BooleanDef;
		$this->assertSame( [ ApiBase::PARAM_DFLT => false ], $typeDef->normalizeSettings( [] ) );
	}

	public function testGetParamInfo() {
		$typeDef = new BooleanDef;
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => false ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => 0 ], new MockApi
		) );
	}

}
