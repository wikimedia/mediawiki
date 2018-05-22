<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiUsageException;
use DerivativeContext;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\StringDef
 */
class StringDefTest extends MediaWikiLangTestCase {

	/** @dataProvider provideValidate */
	public function testValidate( $settings, $value, $expect, $options = [] ) {
		if ( isset( $options['options'] ) ) {
			$typeDef = new StringDef( $options['options'] );
		} else {
			$typeDef = new StringDef;
		}
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$options += [
			'warnings' => [],
		];

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertEquals( $options['warnings'], $api->warnings );
		}
	}

	public static function provideValidate() {
		$req = [
			ApiBase::PARAM_REQUIRED => true,
		];
		$maxBytes = [
			ApiBase::PARAM_MAX_BYTES => 4,
		];
		$maxChars = [
			ApiBase::PARAM_MAX_CHARS => 2,
		];

		return [
			'Basic' => [ [], '123', '123' ],
			'Empty' => [ [], '', '' ],
			'Empty, required' => [ $req, '',
				ApiUsageException::newWithMessage( null, [ 'apierror-missingparam', 'ttfoobar' ] ) ],
			'Empty, required, allowed' => [ $req, '', '', [
				'options' => [ 'allowEmptyWhenRequired' => true ],
			] ],
			'Max bytes, ok' => [ $maxBytes, 'abcd', 'abcd' ],
			'Max bytes, exceeded' => [ $maxBytes, 'abcde',
				ApiUsageException::newWithMessage( null, [ 'apierror-maxbytes', 'ttfoobar', 4 ] ) ],
			'Max bytes, ok (2)' => [ $maxBytes, '😄', '😄' ],
			'Max bytes, exceeded (2)' => [ $maxBytes, '😭?',
				ApiUsageException::newWithMessage( null, [ 'apierror-maxbytes', 'ttfoobar', 4 ] ) ],
			'Max chars, ok' => [ $maxChars, 'ab', 'ab' ],
			'Max chars, exceeded' => [ $maxChars, 'abc',
				ApiUsageException::newWithMessage( null, [ 'apierror-maxchars', 'ttfoobar', 2 ] ) ],
			'Max chars, ok (2)' => [ $maxChars, '😄😄', '😄😄' ],
			'Max chars, exceeded (2)' => [ $maxChars, '😭??',
				ApiUsageException::newWithMessage( null, [ 'apierror-maxchars', 'ttfoobar', 2 ] ) ],
		];
	}

	public function testGetHelpInfo() {
		$typeDef = new StringDef;

		$settings = [
			ApiBase::PARAM_TYPE => 'string',
		];
		$this->assertSame( [
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setLanguage( 'qqx' );
		$this->assertSame( [
		], $typeDef->getHelpInfo( $context, 'foobar', $settings, new MockApi ) );
	}

	public function testGetParamInfo() {
		$typeDef = new StringDef;
		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );
		$this->assertSame( [ 'default' => '123' ], $typeDef->getParamInfo(
			'foobar', [ ApiBase::PARAM_DFLT => 123 ], new MockApi
		) );
	}

}
