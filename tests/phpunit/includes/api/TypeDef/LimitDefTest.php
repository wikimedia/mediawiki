<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiMessage;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\LimitDef
 */
class LimitDefTest extends MediaWikiLangTestCase {

	/** @dataProvider provideValidate */
	public function testValidate( $settings, $value, $expect, $options = [] ) {
		$settings += [
			'parse-limit' => true,
			'set-parsed-limit' => true,
			ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
			ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
		];

		$typeDef = new LimitDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mModuleName = 'test';
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$options += [
			'warnings' => [],
			'highlimits' => false,
			'parsed-limit' => null,
		];
		$wmain = TestingAccessWrapper::newFromObject( $w->mMainModule );
		$wmain->mCanApiHighLimits = $options['highlimits'];
		$this->assertSame( $options['highlimits'], $w->mMainModule->canApiHighLimits(), 'sanity check' );

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertEquals( $options['warnings'], $api->warnings );
			$this->assertSame(
				$options['parsed-limit'], $api->getResult()->getResultData( [ 'limits', 'test' ] )
			);
		}
	}

	public static function provideValidate() {
		$noparse = [ 'parse-limit' => false ];
		$noset = [ 'set-parsed-limit' => false ];
		$minmax = [
			ApiBase::PARAM_MIN => 0,
			ApiBase::PARAM_MAX => 2,
		];
		$minmax2 = [
			ApiBase::PARAM_MIN => 0,
			ApiBase::PARAM_MAX => 2,
			ApiBase::PARAM_MAX2 => 4,
		];
		$enforce = [
			ApiBase::PARAM_RANGE_ENFORCE => true,
		];

		return [
			'Basic' => [ [], '123', 123 ],
			'Negative' => [ [], '-123', 0, [
				'warnings' => [ ApiMessage::create(
					[ 'apierror-integeroutofrange-belowminimum', 'ttfoobar', 0, -123 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => ApiBase::LIMIT_BIG1, 'botMax' => ApiBase::LIMIT_BIG2 ]
				) ]
			] ],
			'Max' => [ [], 'max', ApiBase::LIMIT_BIG1, [ 'parsed-limit' => ApiBase::LIMIT_BIG1 ] ],
			'Max, with highlimits' => [ [], 'max', ApiBase::LIMIT_BIG2, [
				'highlimits' => true,
				'parsed-limit' => ApiBase::LIMIT_BIG2,
			] ],
			'Max, no set' => [ $noset, 'max', ApiBase::LIMIT_BIG1 ],
			'Max, no parse' => [ $noparse, 'max', 'max' ],
		];
	}

	public function testGetHelpInfo() {
		$typeDef = new LimitDef;

		$settings = [
			ApiBase::PARAM_TYPE => 'limit',
			ApiBase::PARAM_MAX => 10,
			ApiBase::PARAM_MAX2 => 100,
		];
		$this->assertSame( [
			'No more than 10 (100 for bots) allowed.',
			'Type: integer or <kbd>max</kbd>'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_TYPE => 'limit',
			ApiBase::PARAM_MIN => 1,
			ApiBase::PARAM_MAX => 10,
			ApiBase::PARAM_MAX2 => 10,
		];
		$this->assertSame( [
			'No more than 10 allowed.',
			'Type: integer or <kbd>max</kbd>'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );
	}

	public function testGetParamInfo() {
		$typeDef = new LimitDef;
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => 123 ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => '123' ], new MockApi
		) );
		$this->assertSame( [ 'default' => 'max' ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => 'max' ], new MockApi
		) );
	}

}
