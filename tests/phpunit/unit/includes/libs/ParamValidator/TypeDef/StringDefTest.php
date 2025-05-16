<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\StringDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\StringDef
 */
class StringDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new StringDef( $callbacks, $options );
	}

	public static function provideValidate() {
		$req = [
			ParamValidator::PARAM_REQUIRED => true,
		];
		$maxBytes = [
			StringDef::PARAM_MAX_BYTES => 4,
		];
		$maxChars = [
			StringDef::PARAM_MAX_CHARS => 2,
		];

		return [
			'Basic' => [ '123', '123' ],
			'Empty' => [ '', '' ],
			'Empty, required' => [
				'',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-missingparam', [], 'missingparam' ),
					'test', '', []
				),
				$req,
			],
			'Not a string' => [
				[ 1, 2, 3 ],
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-needstring', [], 'needstring' ),
					'test', '', []
				),
				$req,
			],
			'Empty, required, allowed' => [ '', '', $req, [ StringDef::OPT_ALLOW_EMPTY => true ] ],
			'Max bytes, ok' => [ 'abcd', 'abcd', $maxBytes ],
			'Max bytes, exceeded' => [
				'abcde',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-maxbytes', [], 'maxbytes', [
						'maxbytes' => 4, 'maxchars' => null,
					] ),
					'test', '', []
				),
				$maxBytes,
			],
			'Max bytes, ok (2)' => [ '😄', '😄', $maxBytes ],
			'Max bytes, exceeded (2)' => [
				'😭?',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-maxbytes', [], 'maxbytes', [
						'maxbytes' => 4, 'maxchars' => null,
					] ),
					'test', '', []
				),
				$maxBytes,
			],
			'Max chars, ok' => [ 'ab', 'ab', $maxChars ],
			'Max chars, exceeded' => [
				'abc',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-maxchars', [], 'maxchars', [
						'maxbytes' => null, 'maxchars' => 2,
					] ),
					'test', '', []
				),
				$maxChars,
			],
			'Max chars, ok (2)' => [ '😄😄', '😄😄', $maxChars ],
			'Max chars, exceeded (2)' => [
				'😭??',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-maxchars', [], 'maxchars', [
						'maxbytes' => null, 'maxchars' => 2,
					] ),
					'test', '', []
				),
				$maxChars,
			],
		];
	}

	public static function provideCheckSettings() {
		$keys = [ 'Y', StringDef::PARAM_MAX_BYTES, StringDef::PARAM_MAX_CHARS ];

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
					StringDef::PARAM_MAX_BYTES => 255,
					StringDef::PARAM_MAX_CHARS => 100,
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
					StringDef::PARAM_MAX_BYTES => '255',
					StringDef::PARAM_MAX_CHARS => 100.0,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						StringDef::PARAM_MAX_BYTES => 'PARAM_MAX_BYTES must be an integer, got string',
						StringDef::PARAM_MAX_CHARS => 'PARAM_MAX_CHARS must be an integer, got double',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Out of range' => [
				[
					StringDef::PARAM_MAX_BYTES => -1,
					StringDef::PARAM_MAX_CHARS => -1,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						StringDef::PARAM_MAX_BYTES => 'PARAM_MAX_BYTES must be greater than or equal to 0',
						StringDef::PARAM_MAX_CHARS => 'PARAM_MAX_CHARS must be greater than or equal to 0',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Zero not allowed when required and !allowEmptyWhenRequired' => [
				[
					ParamValidator::PARAM_REQUIRED => true,
					StringDef::PARAM_MAX_BYTES => 0,
					StringDef::PARAM_MAX_CHARS => 0,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_REQUIRED is set, allowEmptyWhenRequired is not set, and PARAM_MAX_BYTES is 0. That\'s impossible to satisfy.',
						'PARAM_REQUIRED is set, allowEmptyWhenRequired is not set, and PARAM_MAX_CHARS is 0. That\'s impossible to satisfy.',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
				[ 'allowEmptyWhenRequired' => false ],
			],
			'Zero allowed when not required' => [
				[
					StringDef::PARAM_MAX_BYTES => 0,
					StringDef::PARAM_MAX_CHARS => 0,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
				[ 'allowEmptyWhenRequired' => false ],
			],
			'Zero allowed when allowEmptyWhenRequired' => [
				[
					ParamValidator::PARAM_REQUIRED => true,
					StringDef::PARAM_MAX_BYTES => 0,
					StringDef::PARAM_MAX_CHARS => 0,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
				[ 'allowEmptyWhenRequired' => true ],
			],
		];
	}

	public static function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[ 'maxbytes' => null, 'maxchars' => null ],
				[],
			],
			'With settings' => [
				[
					StringDef::PARAM_MAX_BYTES => 4,
					StringDef::PARAM_MAX_CHARS => 2,
					ParamValidator::PARAM_ISMULTI => true,
				],
				[ 'maxbytes' => 4, 'maxchars' => 2 ],
				[
					StringDef::PARAM_MAX_BYTES => '<message key="paramvalidator-help-type-string-maxbytes"><num>4</num></message>',
					StringDef::PARAM_MAX_CHARS => '<message key="paramvalidator-help-type-string-maxchars"><num>2</num></message>',
				],
			],
		];
	}

}
