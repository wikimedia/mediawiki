<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\PresenceBooleanDef
 */
class PresenceBooleanDefTest extends TypeDefTestCase {

	protected static $testClass = PresenceBooleanDef::class;

	public function provideValidate() {
		return [
			[ null, false ],
			[ '', true ],
			[ '0', true ],
			[ '1', true ],
			[ 'anything really', true ],
		];
	}

	public function provideNormalizeSettings() {
		return [
			[
				[],
				[ ParamValidator::PARAM_ISMULTI => false, ParamValidator::PARAM_DEFAULT => false ],
			],
			[
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ ParamValidator::PARAM_ISMULTI => false, ParamValidator::PARAM_DEFAULT => false ],
			],
			[
				[ ParamValidator::PARAM_DEFAULT => null ],
				[ ParamValidator::PARAM_DEFAULT => null, ParamValidator::PARAM_ISMULTI => false ],
			],
		];
	}

	public function provideCheckSettings() {
		return [
			'Basic test' => [
				[],
				self::STDRET,
				self::STDRET,
			],
			'PARAM_ISMULTI not allowed' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_ISMULTI
							=> 'PARAM_ISMULTI cannot be used for presence-boolean-type parameters',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
			'PARAM_ISMULTI not allowed, but another ISMULTI issue was already logged' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
			'PARAM_DEFAULT can be false' => [
				[ ParamValidator::PARAM_DEFAULT => false ],
				self::STDRET,
				self::STDRET,
			],
			'PARAM_DEFAULT can be null' => [
				[ ParamValidator::PARAM_DEFAULT => null ],
				self::STDRET,
				self::STDRET,
			],
			'PARAM_DEFAULT cannot be true' => [
				[
					ParamValidator::PARAM_DEFAULT => true,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_DEFAULT
							=> 'Default for presence-boolean-type parameters must be false or null',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
			'PARAM_DEFAULT invalid, but another DEFAULT issue was already logged' => [
				[
					ParamValidator::PARAM_DEFAULT => true,
				],
				[
					'issues' => [
						ParamValidator::PARAM_DEFAULT => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
				[
					'issues' => [
						ParamValidator::PARAM_DEFAULT => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
			],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[ 'default' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-presenceboolean"><text>1</text></message>',
					ParamValidator::PARAM_DEFAULT => null,
				],
			],
		];
	}

}
