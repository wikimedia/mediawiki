<?php

namespace MediaWiki\Tests\Api\Validator;

use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiModuleManager;
use MediaWiki\Api\Validator\SubmoduleDef;
use MediaWiki\Tests\Api\MockApi;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Tests\ParamValidator\TypeDef\TypeDefTestCase;

/**
 * @covers \MediaWiki\Api\Validator\SubmoduleDef
 */
class SubmoduleDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new SubmoduleDef( $callbacks, $options );
	}

	private function mockApi() {
		$api = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'getModuleManager' ] )
			->getMock();
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mModuleName = 'testmod';
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$w->mMainModule->getModuleManager()->addModule( 'testmod', 'action', [
			'class' => MockApi::class,
			'factory' => static function () use ( $api ) {
				return $api;
			},
		] );

		$dep = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'isDeprecated' ] )
			->getMock();
		$dep->method( 'isDeprecated' )->willReturn( true );
		$int = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'isInternal' ] )
			->getMock();
		$int->method( 'isInternal' )->willReturn( true );
		$depint = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'isDeprecated', 'isInternal' ] )
			->getMock();
		$depint->method( 'isDeprecated' )->willReturn( true );
		$depint->method( 'isInternal' )->willReturn( true );

		$manager = new ApiModuleManager( $api );
		$api->method( 'getModuleManager' )->willReturn( $manager );
		$manager->addModule( 'mod1', 'test', MockApi::class );
		$manager->addModule( 'mod2', 'test', MockApi::class );
		$manager->addModule( 'dep', 'test', [
			'class' => MockApi::class,
			'factory' => static function () use ( $dep ) {
				return $dep;
			},
		] );
		$manager->addModule( 'depint', 'test', [
			'class' => MockApi::class,
			'factory' => static function () use ( $depint ) {
				return $depint;
			},
		] );
		$manager->addModule( 'int', 'test', [
			'class' => MockApi::class,
			'factory' => static function () use ( $int ) {
				return $int;
			},
		] );
		$manager->addModule( 'recurse', 'test', [
			'class' => MockApi::class,
			'factory' => static function () use ( $api ) {
				return $api;
			},
		] );
		$manager->addModule( 'mod3', 'xyz', MockApi::class );

		$this->assertSame( $api, $api->getModuleFromPath( 'testmod' ) );
		$this->assertSame( $dep, $api->getModuleFromPath( 'testmod+dep' ) );
		$this->assertSame( $int, $api->getModuleFromPath( 'testmod+int' ) );
		$this->assertSame( $depint, $api->getModuleFromPath( 'testmod+depint' ) );

		return $api;
	}

	/** @dataProvider provideValidate */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		$options['module'] = $this->mockApi();
		parent::testValidate( $value, $expect, $settings, $options, $expectConds );
	}

	public static function provideValidate() {
		$map = [
			SubmoduleDef::PARAM_SUBMODULE_MAP => [
				'mod2' => 'testmod+mod1',
				'mod3' => 'testmod+mod3',
			],
		];

		return [
			'Basic' => [ 'mod1', 'mod1', [] ],
			'Nonexistent submodule' => [
				'mod3',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue', [], 'badvalue', [] ), 'test', 'mod3', []
				),
				[],
			],
			'Mapped' => [ 'mod3', 'mod3', $map ],
			'Mapped, not in map' => [
				'mod1',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue', [], 'badvalue', [] ), 'test', 'mod1', $map
				),
				$map,
			],
		];
	}

	/** @dataProvider provideCheckSettings */
	public function testCheckSettings(
		array $settings,
		array $ret,
		array $expect,
		array $options = []
	): void {
		$options['module'] = $this->mockApi();
		parent::testCheckSettings( $settings, $ret, $expect, $options );
	}

	public static function provideCheckSettings() {
		$keys = [
			'Y', EnumDef::PARAM_DEPRECATED_VALUES,
			SubmoduleDef::PARAM_SUBMODULE_MAP, SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX
		];

		return [
			'Basic test' => [
				[],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with everything' => [
				[
					SubmoduleDef::PARAM_SUBMODULE_MAP => [
						'foo' => 'testmod+mod1', 'bar' => 'testmod+mod2'
					],
					SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX => 'g',
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Bad types' => [
				[
					SubmoduleDef::PARAM_SUBMODULE_MAP => false,
					SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX => true,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						SubmoduleDef::PARAM_SUBMODULE_MAP => 'PARAM_SUBMODULE_MAP must be an array, got boolean',
						SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX
							=> 'PARAM_SUBMODULE_PARAM_PREFIX must be a string, got boolean',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Bad values in map' => [
				[
					SubmoduleDef::PARAM_SUBMODULE_MAP => [
						'a' => 'testmod+mod1',
						'b' => false,
						'c' => null,
						'd' => 'testmod+mod7',
						'r' => 'testmod+recurse+recurse',
					],
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'Values for PARAM_SUBMODULE_MAP must be strings, but value for "b" is boolean',
						'Values for PARAM_SUBMODULE_MAP must be strings, but value for "c" is NULL',
						'PARAM_SUBMODULE_MAP contains "testmod+mod7", which is not a valid module path',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	/** @dataProvider provideGetEnumValues */
	public function testGetEnumValues( array $settings, $expect, array $options = [] ) {
		$options['module'] = $this->mockApi();
		parent::testGetEnumValues( $settings, $expect, $options );
	}

	public static function provideGetEnumValues() {
		return [
			'Basic test' => [
				[ ParamValidator::PARAM_TYPE => 'submodule' ],
				[ 'mod1', 'mod2', 'dep', 'depint', 'int', 'recurse' ],
			],
			'Mapped' => [
				[
					ParamValidator::PARAM_TYPE => 'submodule',
					SubmoduleDef::PARAM_SUBMODULE_MAP => [
						'mod2' => 'test+mod1',
						'mod3' => 'test+mod3',
					]
				],
				[ 'mod2', 'mod3' ],
			],
		];
	}

	/** @dataProvider provideGetInfo */
	public function testGetInfo(
		array $settings, array $expectParamInfo, array $expectHelpInfo, array $options = []
	) {
		$options['module'] = $this->mockApi();
		parent::testGetInfo( $settings, $expectParamInfo, $expectHelpInfo, $options );
	}

	public static function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[
					'type' => [ 'mod1', 'mod2', 'recurse', 'dep', 'int', 'depint' ],
					'submodules' => [
						'mod1' => 'testmod+mod1',
						'mod2' => 'testmod+mod2',
						'recurse' => 'testmod+recurse',
						'dep' => 'testmod+dep',
						'int' => 'testmod+int',
						'depint' => 'testmod+depint',
					],
					'deprecatedvalues' => [ 'dep', 'depint' ],
					'internalvalues' => [ 'depint', 'int' ],
				],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><list listType="comma"><text>[[Special:ApiHelp/testmod+mod1|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod1&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+mod2|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod2&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+recurse|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;recurse&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+dep|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value&quot;&gt;dep&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+int|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-internal-value&quot;&gt;int&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+depint|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value apihelp-internal-value&quot;&gt;depint&lt;/span&gt;]]</text></list><num>6</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
			'Mapped' => [
				[
					ParamValidator::PARAM_DEFAULT => 'mod3|mod4',
					ParamValidator::PARAM_ISMULTI => true,
					SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX => 'g',
					SubmoduleDef::PARAM_SUBMODULE_MAP => [
						'xyz' => 'testmod+dep',
						'mod3' => 'testmod+mod3',
						'mod4' => 'testmod+mod4', // doesn't exist
					],
				],
				[
					'type' => [ 'mod3', 'mod4', 'xyz' ],
					'submodules' => [
						'mod3' => 'testmod+mod3',
						'mod4' => 'testmod+mod4',
						'xyz' => 'testmod+dep',
					],
					'submoduleparamprefix' => 'g',
					'deprecatedvalues' => [ 'xyz' ],
				],
				[
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>2</text><list listType="comma"><text>[[Special:ApiHelp/testmod+mod3|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod3&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+mod4|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod4&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+dep|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value&quot;&gt;xyz&lt;/span&gt;]]</text></list><num>3</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
			],
		];
	}

}
