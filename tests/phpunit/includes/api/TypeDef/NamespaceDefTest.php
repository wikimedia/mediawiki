<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiResult;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use MWNamespace;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\NamespaceDef
 */
class NamespaceDefTest extends MediaWikiLangTestCase {

	private static function getNamespaces( $extra = [] ) {
		$namespaces = array_merge( MWNamespace::getValidNamespaces(), $extra );
		sort( $namespaces );
		return $namespaces;
	}

	/** @dataProvider provideValidate */
	public function testValidate( $settings, $value, $expect, $warn = [] ) {
		$typeDef = new NamespaceDef;
		$api = new MockApi;
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertSame( $warn, $api->warnings );
		}
	}

	public static function provideValidate() {
		$extra = [
			ApiBase::PARAM_EXTRA_NAMESPACES => [ -5 ],
		];

		return [
			'Basic' => [ [], '0', 0 ],
			'Bad namespace' => [ [], 'x',
				ApiUsageException::newWithMessage( null, [ 'apierror-badnamespace', 'ttfoobar', 'x' ] )
			],
			'Unknown namespace' => [ [], '-1',
				ApiUsageException::newWithMessage( null, [ 'apierror-unrecognizedvalue', 'ttfoobar', '-1' ] )
			],
			'Extra namespaces' => [ $extra, '-5', -5 ],
		];
	}

	public function testGetEnumValues() {
		$typeDef = new NamespaceDef;
		$this->assertSame( self::getNamespaces(), $typeDef->getEnumValues( 'foobar', [], new MockApi ) );
		$this->assertSame(
			self::getNamespaces( [ NS_SPECIAL, NS_MEDIA ] ),
			$typeDef->getEnumValues(
				'foobar', [ ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_SPECIAL, NS_MEDIA ] ], new MockApi
			)
		);
	}

	public function testNormalizeSettings() {
		$typeDef = new NamespaceDef;
		$this->assertSame(
			[ ApiBase::PARAM_IGNORE_INVALID_VALUES => true ],
			$typeDef->normalizeSettings( [] )
		);
		$this->assertSame(
			[
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALL => true,
				ApiBase::PARAM_IGNORE_INVALID_VALUES => true
			],
			$typeDef->normalizeSettings( [ ApiBase::PARAM_ISMULTI => true ] )
		);
		$this->assertSame(
			[
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_ALL => true,
				ApiBase::PARAM_IGNORE_INVALID_VALUES => true
			],
			$typeDef->normalizeSettings( [ ApiBase::PARAM_ISMULTI => true, ApiBase::PARAM_ALL => 'X' ] )
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

		$typeDef = new NamespaceDef;

		$settings = [
		];
		$this->assertSame( [
			'One of the following values: ' . implode( ', ', MWNamespace::getValidNamespaces() ),
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );

		$settings = [
			ApiBase::PARAM_ISMULTI => true,
			ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_SPECIAL, NS_MEDIA ],
		];
		$this->assertSame( [
			// phpcs:disable Generic.Files.LineLength
			'Values (separate with <kbd>|</kbd> or <a href="/wiki/Special:ApiHelp/main#main.2Fdatatypes" title="Special:ApiHelp/main">alternative</a>): -2, -1, '
				. implode( ', ', MWNamespace::getValidNamespaces() ),
			// phpcs:enable
		], $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, new MockApi ) );
	}

	public function testNeedsHelpParamMultiSeparate() {
		$typeDef = new NamespaceDef;
		$this->assertFalse( $typeDef->needsHelpParamMultiSeparate() );
	}

	public function testGetParamInfo() {
		$typeDef = new NamespaceDef;

		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], new MockApi ) );

		$settings = [
			ApiBase::PARAM_DFLT => 0,
			ApiBase::PARAM_EXTRA_NAMESPACES => [ NS_SPECIAL, NS_MEDIA ],
		];
		$this->assertSame(
			[
				'default' => 0,
				'extranamespaces' => [
					NS_SPECIAL,
					NS_MEDIA,
					ApiResult::META_TYPE => 'array',
					ApiResult::META_INDEXED_TAG_NAME => 'ns',
				],
			],
			$typeDef->getParamInfo( 'foobar', $settings, new MockApi )
		);
	}

}
