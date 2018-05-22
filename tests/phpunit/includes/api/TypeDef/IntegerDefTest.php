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
 * @covers MediaWiki\Api\TypeDef\IntegerDef
 */
class IntegerDefTest extends MediaWikiLangTestCase {

	/** @dataProvider provideValidate */
	public function testValidate( $settings, $value, $expect, $options = [] ) {
		$typeDef = new IntegerDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$options += [
			'warnings' => [],
			'highlimits' => false,
			'internal' => false,
		];
		$wmain = TestingAccessWrapper::newFromObject( $w->mMainModule );
		$wmain->mCanApiHighLimits = $options['highlimits'];
		$wmain->mInternalMode = $options['internal'];
		$this->assertSame( $options['highlimits'], $w->mMainModule->canApiHighLimits(), 'sanity check' );
		$this->assertSame( $options['internal'], $w->mMainModule->isInternalMode(), 'sanity check' );

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertEquals( $options['warnings'], $api->warnings );
		}
	}

	public static function provideValidate() {
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
			'Negative' => [ [], '-123', -123 ],
			'Bad int: 1.5' => [ [], '1.5',
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', '1.5' ] ) ],
			'Bad int: space' => [ [], ' 1',
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', ' 1' ] ) ],
			'Bad int: newline' => [ [], "\n1",
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', "\n1" ] ) ],
			'Bad int: 1e1' => [ [], '1e1',
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', '1e1' ] ) ],
			'Bad int: 1foo' => [ [], '1foo',
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', '1foo' ] ) ],
			'Bad int: foo' => [ [], 'foo',
				ApiUsageException::newWithMessage( null, [ 'apierror-badinteger', 'ttfoobar', 'foo' ] ) ],
			'Ok with range' => [ $minmax, 1, 1 ],
			'Below minimum' => [ $minmax, -1, 0, [
				'warnings' => [ ApiMessage::create(
					[ 'apierror-integeroutofrange-belowminimum', 'ttfoobar', 0, -1 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 2, 'botMax' => 2 ]
				) ],
			] ],
			'Above maximum' => [ $minmax, 3, 2, [
				'warnings' => [ ApiMessage::create(
					[ 'apierror-integeroutofrange-abovemax', 'ttfoobar', 2, 3 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 2, 'botMax' => 2 ]
				) ],
			] ],
			'Not above bot maximum' => [ $minmax2, 3, 3, [ 'highlimits' => true ] ],
			'Above bot maximum' => [ $minmax2, 5, 4, [
				'warnings' => [ ApiMessage::create(
					[ 'apierror-integeroutofrange-abovebotmax', 'ttfoobar', 4, 5 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 2, 'botMax' => 4 ]
				) ],
				'highlimits' => true,
			] ],
			'Below minimum, enforced' => [ $minmax + $enforce, -1, ApiUsageException::newWithMessage(
				null, [ 'apierror-integeroutofrange-belowminimum', 'ttfoobar', 0, -1 ]
			) ],
			'Above maximum, enforced' => [ $minmax + $enforce, 3, ApiUsageException::newWithMessage(
				null, [ 'apierror-integeroutofrange-abovemax', 'ttfoobar', 2, 3 ]
			) ],
			'Above bot maximum, enforced' => [ $minmax2 + $enforce, 5,
				ApiUsageException::newWithMessage(
					null, [ 'apierror-integeroutofrange-abovebotmax', 'ttfoobar', 4, 5 ]
				),
				[ 'highlimits' => true ],
			],
			'Max not checked in internal mode' => [
				$minmax + $enforce, 123, 123, [ 'internal' => true ]
			],
			'Min is checked in internal mode' => [ $minmax, -1, 0, [
				'warnings' => [ ApiMessage::create(
					[ 'apierror-integeroutofrange-belowminimum', 'ttfoobar', 0, -1 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 2, 'botMax' => 2 ]
				) ],
				'internal' => true,
			] ],
		];
	}

	public function testNormalizeSettings() {
		$typeDef = new IntegerDef;
		$this->assertSame(
			[ ApiBase::PARAM_IGNORE_INVALID_VALUES => true ],
			$typeDef->normalizeSettings( [] )
		);
		$this->assertSame(
			[ ApiBase::PARAM_RANGE_ENFORCE => true, ApiBase::PARAM_IGNORE_INVALID_VALUES => false ],
			$typeDef->normalizeSettings( [ ApiBase::PARAM_RANGE_ENFORCE => true ] )
		);
		$this->assertSame(
			[ ApiBase::PARAM_IGNORE_INVALID_VALUES => false ],
			$typeDef->normalizeSettings( [
				ApiBase::PARAM_IGNORE_INVALID_VALUES => false ,
			] )
		);
		$this->assertSame(
			[ ApiBase::PARAM_RANGE_ENFORCE => true, ApiBase::PARAM_IGNORE_INVALID_VALUES => true ],
			$typeDef->normalizeSettings( [
				ApiBase::PARAM_RANGE_ENFORCE => true,
				ApiBase::PARAM_IGNORE_INVALID_VALUES => true,
			] )
		);
	}

	public function testGetHelpInfo() {
		$typeDef = new IntegerDef;

		$settings = [
			ApiBase::PARAM_TYPE => 'integer',
		];
		$this->assertSame( [
			'Type: integer'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_TYPE => 'integer',
			ApiBase::PARAM_MIN => 1,
		];
		$this->assertSame( [
			'The value must be no less than 1.',
			'Type: integer'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_TYPE => 'integer',
			ApiBase::PARAM_MAX => 10,
		];
		$this->assertSame( [
			'The value must be no greater than 10.',
			'Type: integer'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_TYPE => 'integer',
			ApiBase::PARAM_MIN => 1,
			ApiBase::PARAM_MAX => 10,
		];
		$this->assertSame( [
			'The value must be between 1 and 10.',
			'Type: integer'
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );
	}

	public function testGetParamInfo() {
		$typeDef = new IntegerDef;
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => 123 ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => "123" ], new MockApi
		) );
	}

}
