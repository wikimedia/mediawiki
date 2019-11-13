<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\StringDef
 */
class StringDefTest extends TypeDefTestCase {

	protected static $testClass = StringDef::class;

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		if ( static::$testClass === null ) {
			throw new \LogicException( 'Either assign static::$testClass or override ' . __METHOD__ );
		}

		return new static::$testClass( $callbacks, $options );
	}

	public function provideValidate() {
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
			'Empty, required, allowed' => [ '', '', $req, [ 'allowEmptyWhenRequired' => true ] ],
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

	public function provideGetInfo() {
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
					// phpcs:ignore Generic.Files.LineLength.TooLong
					StringDef::PARAM_MAX_BYTES => '<message key="paramvalidator-help-type-string-maxbytes"><num>4</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					StringDef::PARAM_MAX_CHARS => '<message key="paramvalidator-help-type-string-maxchars"><num>2</num></message>',
				],
			],
		];
	}

}
