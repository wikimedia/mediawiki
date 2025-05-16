<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\PasswordDef;
use Wikimedia\ParamValidator\TypeDef\StringDef;

require_once __DIR__ . '/StringDefTest.php';

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\PasswordDef
 */
class PasswordDefTest extends StringDefTest {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new PasswordDef( $callbacks, $options );
	}

	public static function provideNormalizeSettings() {
		return [
			[ [], [ ParamValidator::PARAM_SENSITIVE => true ] ],
			[ [ ParamValidator::PARAM_SENSITIVE => false ], [ ParamValidator::PARAM_SENSITIVE => true ] ],
		];
	}

	public static function provideCheckSettings() {
		$keys = [ 'Y', StringDef::PARAM_MAX_BYTES, StringDef::PARAM_MAX_CHARS ];

		yield from parent::provideCheckSettings();

		yield 'PARAM_SENSITIVE cannot be false' => [
			[
				ParamValidator::PARAM_SENSITIVE => false,
			],
			self::STDRET,
			[
				'issues' => [
					'X',
					ParamValidator::PARAM_SENSITIVE
						=> 'Cannot set PARAM_SENSITIVE to false for password-type parameters',
				],
				'allowedKeys' => $keys,
				'messages' => [],
			],
		];
		yield 'PARAM_SENSITIVE cannot be false, but another PARAM_SENSITIVE issue was already logged' => [
			[
				ParamValidator::PARAM_SENSITIVE => false,
			],
			[
				'issues' => [
					ParamValidator::PARAM_SENSITIVE => 'XXX',
				],
				'allowedKeys' => [ 'Y' ],
				'messages' => [],
			],
			[
				'issues' => [
					ParamValidator::PARAM_SENSITIVE => 'XXX',
				],
				'allowedKeys' => $keys,
				'messages' => [],
			],
		];
	}

}
