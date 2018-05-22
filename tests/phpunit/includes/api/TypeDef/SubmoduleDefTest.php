<?php

namespace MediaWiki\Api\TypeDef;

use ApiBase;
use ApiMain;
use ApiModuleManager;
use ApiUsageException;
use MediaWikiLangTestCase;
use MockApi;
use RequestContext;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @covers MediaWiki\Api\TypeDef\SubmoduleDef
 */
class SubmoduleDefTest extends MediaWikiLangTestCase {

	private function mockApi() {
		$api = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'getModuleManager' ] )
			->getMock();
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mModuleName = 'test';
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$w->mMainModule->getModuleManager()->addModule(
			'test', 'action', MockApi::class, function () use ( $api ) {
				return $api;
			}
		);

		$dep = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'isDeprecated' ] )
			->getMock();
		$dep->method( 'isDeprecated' )->willReturn( true );

		$manager = new ApiModuleManager( $api );
		$api->method( 'getModuleManager' )->willReturn( $manager );
		$manager->addModule( 'mod1', 'foobar', MockApi::class );
		$manager->addModule( 'mod2', 'foobar', MockApi::class );
		$manager->addModule( 'dep', 'foobar', MockApi::class, function () use ( $dep ) {
			return $dep;
		} );
		$manager->addModule( 'recurse', 'foobar', MockApi::class, function () use ( $api ) {
			return $api;
		} );
		$manager->addModule( 'mod3', 'xyz', MockApi::class );

		$this->assertSame( $api, $api->getModuleFromPath( 'test' ), 'sanity check' );
		$this->assertSame( $dep, $api->getModuleFromPath( 'test+dep' ), 'sanity check' );

		return $api;
	}

	/** @dataProvider provideValidate */
	public function testValidate( $settings, $value, $expect, $warn = [] ) {
		$typeDef = new SubmoduleDef;
		$api = $this->mockApi();

		if ( $expect instanceof ApiUsageException ) {
			$this->setExpectedException( ApiUsageException::class, $expect->getMessage() );
			$typeDef->validate( 'foobar', $value, $settings, $api );
		} else {
			$this->assertSame( $expect, $typeDef->validate( 'foobar', $value, $settings, $api ) );
			$this->assertSame( $warn, $api->warnings );
		}
	}

	public static function provideValidate() {
		$map = [
			ApiBase::PARAM_SUBMODULE_MAP => [
				'mod2' => 'test+mod1',
				'mod3' => 'test+mod3',
			],
		];

		return [
			'Basic' => [ [], 'mod1', 'mod1' ],
			'Nonexistent submodule' => [ [], 'mod3',
				ApiUsageException::newWithMessage( null, [ 'apierror-unrecognizedvalue', 'ttfoobar', 'mod3' ] )
			],
			'Mapped' => [ $map, 'mod3', 'mod3' ],
			'Mapped, not in map' => [ $map, 'mod1',
				ApiUsageException::newWithMessage( null, [ 'apierror-unrecognizedvalue', 'ttfoobar', 'mod1' ] )
			],
		];
	}

	public function testGetEnumValues() {
		$typeDef = new SubmoduleDef;
		$api = $this->mockApi();

		$this->assertSame(
			[ 'mod1', 'mod2', 'dep', 'recurse' ],
			$typeDef->getEnumValues( 'foobar', [], $api )
		);
		$this->assertSame(
			[ 'mod2', 'mod3' ],
			$typeDef->getEnumValues(
				'foobar',
				[
					ApiBase::PARAM_SUBMODULE_MAP => [
						'mod2' => 'test+mod1',
						'mod3' => 'test+mod3',
					],
				],
				$api
			)
		);
	}

	/** @dataProvider provideGetHelpInfo */
	public function testGetHelpInfo( $settings, $info ) {
		$typeDef = new SubmoduleDef;
		$api = $this->mockApi();

		$settings += [
			ApiBase::PARAM_TYPE => 'submodule',
		];
		$this->assertSame(
			$info, $typeDef->getHelpInfo( RequestContext::getMain(), 'foobar', $settings, $api )
		);
	}

	public static function provideGetHelpInfo() {
		return [
			// phpcs:disable Generic.Files.LineLength
			'Basic' => [
				[],
				[
					'One of the following values: <a href="/wiki/Special:ApiHelp/test%2Bmod1" title="Special:ApiHelp/test+mod1"><span dir="ltr" lang="en">mod1</span></a>, <a href="/wiki/Special:ApiHelp/test%2Bmod2" title="Special:ApiHelp/test+mod2"><span dir="ltr" lang="en">mod2</span></a>, <a href="/wiki/Special:ApiHelp/test%2Brecurse" title="Special:ApiHelp/test+recurse"><span dir="ltr" lang="en">recurse</span></a>, <a href="/wiki/Special:ApiHelp/test%2Bdep" title="Special:ApiHelp/test+dep"><span dir="ltr" lang="en" class="apihelp-deprecated-value">dep</span></a>',
				],
			],
			'Mapped' => [
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_SUBMODULE_MAP => [
						'xyz' => 'test+dep',
						'mod3' => 'test+mod3',
						'mod4' => 'test+mod4', // doesn't exist
					],
				],
				[
					'Values (separate with <kbd>|</kbd> or <a href="/wiki/Special:ApiHelp/main#main.2Fdatatypes" title="Special:ApiHelp/main">alternative</a>): <a href="/wiki/Special:ApiHelp/test%2Bmod3" title="Special:ApiHelp/test+mod3">mod3</a>, <a href="/wiki/Special:ApiHelp/test%2Bmod4" title="Special:ApiHelp/test+mod4">mod4</a>, <a href="/wiki/Special:ApiHelp/test%2Bdep" title="Special:ApiHelp/test+dep"><span class="apihelp-deprecated-value">xyz</span></a>',
				],
			],
			// phpcs:enable
		];
	}

	public function testNeedsHelpParamMultiSeparate() {
		$typeDef = new SubmoduleDef;
		$this->assertFalse( $typeDef->needsHelpParamMultiSeparate() );
	}

	/** @dataProvider provideGetParamInfo */
	public function testGetParamInfo( $settings, $expect ) {
		$typeDef = new SubmoduleDef;
		$api = $this->mockApi();

		$this->assertEquals(
			$expect, $typeDef->getParamInfo( 'foobar', $settings, $api )
		);
	}

	public static function provideGetParamInfo() {
		return [
			'Basic' => [
				[],
				[
					'type' => [ 'mod1', 'mod2', 'recurse', 'dep' ],
					'submodules' => [
						'dep' => 'test+dep',
						'mod1' => 'test+mod1',
						'mod2' => 'test+mod2',
						'recurse' => 'test+recurse',
					],
					'deprecatedvalues' => [ 'dep' ],
				],
			],
			'Mapped' => [
				[
					ApiBase::PARAM_DFLT => 'mod3|mod4',
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_SUBMODULE_PARAM_PREFIX => 'g',
					ApiBase::PARAM_SUBMODULE_MAP => [
						'xyz' => 'test+dep',
						'mod3' => 'test+mod3',
						'mod4' => 'test+mod4', // doesn't exist
					],
				],
				[
					'default' => 'mod3|mod4',
					'type' => [ 'mod3', 'mod4', 'xyz' ],
					'submodules' => [
						'mod3' => 'test+mod3',
						'mod4' => 'test+mod4',
						'xyz' => 'test+dep',
					],
					'submoduleparamprefix' => 'g',
					'deprecatedvalues' => [ 'xyz' ],
				],
			],
		];
	}

}
