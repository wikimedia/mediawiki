<?php

namespace MediaWiki\Api\Validator;

use ApiMain;
use ApiModuleManager;
use MockApi;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\TypeDefTestCase;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Api\Validator\SubmoduleDef
 */
class SubmoduleDefTest extends TypeDefTestCase {

	protected static $testClass = SubmoduleDef::class;

	private function mockApi() {
		$api = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'getModuleManager' ] )
			->getMock();
		$w = TestingAccessWrapper::newFromObject( $api );
		$w->mModuleName = 'testmod';
		$w->mMainModule = new ApiMain;
		$w->mModulePrefix = 'tt';

		$w->mMainModule->getModuleManager()->addModule( 'testmod', 'action', [
			'class' => MockApi::class,
			'factory' => function () use ( $api ) {
				return $api;
			},
		] );

		$dep = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'isDeprecated' ] )
			->getMock();
		$dep->method( 'isDeprecated' )->willReturn( true );
		$int = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'isInternal' ] )
			->getMock();
		$int->method( 'isInternal' )->willReturn( true );
		$depint = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'isDeprecated', 'isInternal' ] )
			->getMock();
		$depint->method( 'isDeprecated' )->willReturn( true );
		$depint->method( 'isInternal' )->willReturn( true );

		$manager = new ApiModuleManager( $api );
		$api->method( 'getModuleManager' )->willReturn( $manager );
		$manager->addModule( 'mod1', 'test', MockApi::class );
		$manager->addModule( 'mod2', 'test', MockApi::class );
		$manager->addModule( 'dep', 'test', [
			'class' => MockApi::class,
			'factory' => function () use ( $dep ) {
				return $dep;
			},
		] );
		$manager->addModule( 'depint', 'test', [
			'class' => MockApi::class,
			'factory' => function () use ( $depint ) {
				return $depint;
			},
		] );
		$manager->addModule( 'int', 'test', [
			'class' => MockApi::class,
			'factory' => function () use ( $int ) {
				return $int;
			},
		] );
		$manager->addModule( 'recurse', 'test', [
			'class' => MockApi::class,
			'factory' => function () use ( $api ) {
				return $api;
			},
		] );
		$manager->addModule( 'mod3', 'xyz', MockApi::class );

		$this->assertSame( $api, $api->getModuleFromPath( 'testmod' ), 'sanity check' );
		$this->assertSame( $dep, $api->getModuleFromPath( 'testmod+dep' ), 'sanity check' );
		$this->assertSame( $int, $api->getModuleFromPath( 'testmod+int' ), 'sanity check' );
		$this->assertSame( $depint, $api->getModuleFromPath( 'testmod+depint' ), 'sanity check' );

		return $api;
	}

	public function provideValidate() {
		$opts = [
			'module' => $this->mockApi(),
		];
		$map = [
			SubmoduleDef::PARAM_SUBMODULE_MAP => [
				'mod2' => 'testmod+mod1',
				'mod3' => 'testmod+mod3',
			],
		];

		return [
			'Basic' => [ 'mod1', 'mod1', [], $opts ],
			'Nonexistent submodule' => [
				'mod3',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue', [], 'badvalue', [] ), 'test', 'mod3', []
				),
				[],
				$opts,
			],
			'Mapped' => [ 'mod3', 'mod3', $map, $opts ],
			'Mapped, not in map' => [
				'mod1',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badvalue', [], 'badvalue', [] ), 'test', 'mod1', $map
				),
				$map,
				$opts,
			],
		];
	}

	public function provideCheckSettings() {
		$opts = [
			'module' => $this->mockApi(),
		];
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
				$opts
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
				$opts
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
				$opts
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
				$opts
			],
		];
	}

	public function provideGetEnumValues() {
		$opts = [
			'module' => $this->mockApi(),
		];

		return [
			'Basic test' => [
				[ ParamValidator::PARAM_TYPE => 'submodule' ],
				[ 'mod1', 'mod2', 'dep', 'depint', 'int', 'recurse' ],
				$opts,
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
				$opts,
			],
		];
	}

	public function provideGetInfo() {
		$opts = [
			'module' => $this->mockApi(),
		];

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
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>1</text><list listType="comma"><text>[[Special:ApiHelp/testmod+mod1|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod1&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+mod2|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod2&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+recurse|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;recurse&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+dep|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value&quot;&gt;dep&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+int|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-internal-value&quot;&gt;int&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+depint|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value apihelp-internal-value&quot;&gt;depint&lt;/span&gt;]]</text></list><num>6</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
				$opts,
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
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-enum"><text>2</text><list listType="comma"><text>[[Special:ApiHelp/testmod+mod3|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod3&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+mod4|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot;&gt;mod4&lt;/span&gt;]]</text><text>[[Special:ApiHelp/testmod+dep|&lt;span dir=&quot;ltr&quot; lang=&quot;en&quot; class=&quot;apihelp-deprecated-value&quot;&gt;xyz&lt;/span&gt;]]</text></list><num>3</num></message>',
					ParamValidator::PARAM_ISMULTI => null,
				],
				$opts,
			],
		];
	}

}
