<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\EnumDef
 */
class EnumDefTest extends MediaWikiLangTestCase {

	/** @dataProvider provideValidate */
	public function testValidate( $value, $expect, $warn = [], $valuesList = null ) {
		$typeDef = new EnumDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$settings = [
			ApiBase::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
			ApiBase::PARAM_DEPRECATED_VALUES => [
				'b' => [ 'not-to-be' ],
				'c' => true,
			],
		];
		if ( $valuesList !== null ) {
			$settings['values-list'] = $valuesList;
		}

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertSame( $warn, $api->warnings );
		}
	}

	public static function provideValidate() {
		return [
			'Basic' => [ 'a', 'a' ],
			'Deprecated' => [ 'c', 'c', [ [ 'apiwarn-deprecation-parameter', 'ttfoobar=c' ] ] ],
			'Deprecated with message' => [ 'b', 'b', [ [ 'not-to-be' ] ] ],
			'Bad value, non-multi' => [
				'x',
				ApiUsageException::newWithMessage( null, [ 'apierror-unrecognizedvalue', 'ttfoobar', 'x' ] )
			],
			'Bad value, non-multi but looks like it' => [
				'x|y',
				ApiUsageException::newWithMessage( null, [
					'apierror-multival-only-one-of',
					'ttfoobar',
					\Message::listParam( [ '<kbd>a</kbd>', '<kbd>b</kbd>', '<kbd>c</kbd>', '<kbd>d</kbd>' ] ),
					4
				] )
			],
			'Bad value, multi' => [
				'x|y',
				ApiUsageException::newWithMessage( null, [ 'apierror-unrecognizedvalue', 'ttfoobar', 'x|y' ] ),
				[],
				[ 'x|y' ]
			],
		];
	}

	public function testGetEnumValues() {
		$typeDef = new EnumDef;
		$this->assertSame(
			[ 'a', 'b', 'c', 'd' ],
			$typeDef->getEnumValues(
				'foobar', [ ApiBase::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ] ], new MockApi
			)
		);
	}

	public function testNormalizeSettings() {
		$typeDef = new EnumDef;
		$this->assertSame(
			[ ApiBase::PARAM_IGNORE_INVALID_VALUES => true ],
			$typeDef->normalizeSettings( [] )
		);
	}

	public function testGetHelpInfo() {
		$this->setMwGlobals( [
			'wgServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgActionPaths' => [],
		] );

		$typeDef = new EnumDef;

		$settings = [
			ApiBase::PARAM_TYPE => [ '', 'a', 'b', 'c', 'd' ],
			ApiBase::PARAM_DEPRECATED_VALUES => [
				'b' => [ 'not-to-be' ],
				'c' => true,
			],
		];
		$this->assertSame( [
			// phpcs:disable Generic.Files.LineLength
			'One of the following values: Can be empty, or <span dir="auto">a</span>, <span dir="auto" class="apihelp-deprecated-value">b</span>, <span dir="auto" class="apihelp-deprecated-value">c</span>, <span dir="auto">d</span>'
			// phpcs:enable
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
			ApiBase::PARAM_VALUE_LINKS => [ 'a' => 'UTPage', 'b' => 'UTPage' ],
			ApiBase::PARAM_DEPRECATED_VALUES => [
				'b' => [ 'not-to-be' ],
				'c' => true,
			],
			ApiBase::PARAM_ISMULTI => true,
		];
		$this->assertSame( [
			// phpcs:disable Generic.Files.LineLength
			'Values (separate with <kbd>|</kbd> or <a href="/wiki/Special:ApiHelp/main#main.2Fdatatypes" title="Special:ApiHelp/main">alternative</a>): <a href="/wiki/UTPage" title="UTPage"><span dir="auto">a</span></a>, <a href="/wiki/UTPage" title="UTPage"><span dir="auto" class="apihelp-deprecated-value">b</span></a>, <span dir="auto" class="apihelp-deprecated-value">c</span>, <span dir="auto">d</span>',
			// phpcs:enable
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );
	}

	public function testNeedsHelpParamMultiSeparate() {
		$typeDef = new EnumDef;
		$this->assertFalse( $typeDef->needsHelpParamMultiSeparate() );
	}

}
