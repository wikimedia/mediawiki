<?php

use Wikimedia\TestingAccessWrapper;
use Wikimedia\ScopedCallback;

/**
 * @covers ParserOptions
 */
class ParserOptionsTest extends MediaWikiTestCase {

	private static function clearCache() {
		$wrap = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$wrap->defaults = null;
		$wrap->lazyOptions = [
			'dateformat' => [ ParserOptions::class, 'initDateFormat' ],
		];
		$wrap->inCacheKey = [
			'dateformat' => true,
			'numberheadings' => true,
			'thumbsize' => true,
			'stubthreshold' => true,
			'printable' => true,
			'userlang' => true,
		];
	}

	protected function setUp() {
		global $wgHooks;

		parent::setUp();
		self::clearCache();

		$this->setMwGlobals( [
			'wgRenderHashAppend' => '',
			'wgHooks' => [
				'PageRenderingHash' => [],
			] + $wgHooks,
		] );
	}

	protected function tearDown() {
		self::clearCache();
		parent::tearDown();
	}

	/**
	 * @dataProvider provideIsSafeToCache
	 * @param bool $expect Expected value
	 * @param array $options Options to set
	 */
	public function testIsSafeToCache( $expect, $options ) {
		$popt = ParserOptions::newCanonical();
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->isSafeToCache() );
	}

	public static function provideIsSafeToCache() {
		return [
			'No overrides' => [ true, [] ],
			'In-key options are ok' => [ true, [
				'thumbsize' => 1e100,
				'printable' => false,
			] ],
			'Non-in-key options are not ok' => [ false, [
				'removeComments' => false,
			] ],
			'Non-in-key options are not ok (2)' => [ false, [
				'wrapclass' => 'foobar',
			] ],
			'Canonical override, not default (1)' => [ true, [
				'tidy' => true,
			] ],
			'Canonical override, not default (2)' => [ false, [
				'tidy' => false,
			] ],
		];
	}

	/**
	 * @dataProvider provideOptionsHash
	 * @param array $usedOptions Used options
	 * @param string $expect Expected value
	 * @param array $options Options to set
	 * @param array $globals Globals to set
	 */
	public function testOptionsHash( $usedOptions, $expect, $options, $globals = [] ) {
		global $wgHooks;

		$globals += [
			'wgHooks' => [],
		];
		$globals['wgHooks'] += [
			'PageRenderingHash' => [],
		] + $wgHooks;
		$this->setMwGlobals( $globals );

		$popt = ParserOptions::newCanonical();
		foreach ( $options as $name => $value ) {
			$popt->setOption( $name, $value );
		}
		$this->assertSame( $expect, $popt->optionsHash( $usedOptions ) );
	}

	public static function provideOptionsHash() {
		$used = [ 'thumbsize', 'printable' ];

		$classWrapper = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$classWrapper->getDefaults();
		$allUsableOptions = array_diff(
			array_keys( $classWrapper->inCacheKey ),
			array_keys( $classWrapper->lazyOptions )
		);

		return [
			'Canonical options, nothing used' => [ [], 'canonical', [] ],
			'Canonical options, used some options' => [ $used, 'canonical', [] ],
			'Used some options, non-default values' => [
				$used,
				'printable=1!thumbsize=200',
				[
					'thumbsize' => 200,
					'printable' => true,
				]
			],
			'Canonical options, used all non-lazy options' => [ $allUsableOptions, 'canonical', [] ],
			'Canonical options, nothing used, but with hooks and $wgRenderHashAppend' => [
				[],
				'canonical!wgRenderHashAppend!onPageRenderingHash',
				[],
				[
					'wgRenderHashAppend' => '!wgRenderHashAppend',
					'wgHooks' => [ 'PageRenderingHash' => [ [ __CLASS__ . '::onPageRenderingHash' ] ] ],
				]
			],
		];
	}

	public static function onPageRenderingHash( &$confstr ) {
		$confstr .= '!onPageRenderingHash';
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Unknown parser option bogus
	 */
	public function testGetInvalidOption() {
		$popt = ParserOptions::newCanonical();
		$popt->getOption( 'bogus' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @expectedExceptionMessage Unknown parser option bogus
	 */
	public function testSetInvalidOption() {
		$popt = ParserOptions::newCanonical();
		$popt->setOption( 'bogus', true );
	}

	public function testMatches() {
		$classWrapper = TestingAccessWrapper::newFromClass( ParserOptions::class );
		$oldDefaults = $classWrapper->defaults;
		$oldLazy = $classWrapper->lazyOptions;
		$reset = new ScopedCallback( function () use ( $classWrapper, $oldDefaults, $oldLazy ) {
			$classWrapper->defaults = $oldDefaults;
			$classWrapper->lazyOptions = $oldLazy;
		} );

		$popt1 = ParserOptions::newCanonical();
		$popt2 = ParserOptions::newCanonical();
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt1->enableLimitReport( true );
		$popt2->enableLimitReport( false );
		$this->assertTrue( $popt1->matches( $popt2 ) );

		$popt2->setTidy( !$popt2->getTidy() );
		$this->assertFalse( $popt1->matches( $popt2 ) );

		$ctr = 0;
		$classWrapper->defaults += [ __METHOD__ => null ];
		$classWrapper->lazyOptions += [ __METHOD__ => function () use ( &$ctr ) {
			return ++$ctr;
		} ];
		$popt1 = ParserOptions::newCanonical();
		$popt2 = ParserOptions::newCanonical();
		$this->assertFalse( $popt1->matches( $popt2 ) );

		ScopedCallback::consume( $reset );
	}

	public function testAllCacheVaryingOptions() {
		global $wgHooks;

		// $wgHooks is already saved in self::setUp(), so we can modify it freely here
		$wgHooks['ParserOptionsRegister'] = [];
		$this->assertSame( [
			'dateformat', 'numberheadings', 'printable', 'stubthreshold',
			'thumbsize', 'userlang'
		], ParserOptions::allCacheVaryingOptions() );

		self::clearCache();

		$wgHooks['ParserOptionsRegister'][] = function ( &$defaults, &$inCacheKey ) {
			$defaults += [
				'foo' => 'foo',
				'bar' => 'bar',
				'baz' => 'baz',
			];
			$inCacheKey += [
				'foo' => true,
				'bar' => false,
			];
		};
		$this->assertSame( [
			'dateformat', 'foo', 'numberheadings', 'printable', 'stubthreshold',
			'thumbsize', 'userlang'
		], ParserOptions::allCacheVaryingOptions() );
	}

}
