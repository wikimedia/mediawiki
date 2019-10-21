<?php

namespace Wikimedia\ParamValidator\TypeDef;

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
				new ValidationException( 'test', '', [], 'missingparam', [] ),
				$req,
			],
			'Empty, required, allowed' => [ '', '', $req, [ 'allowEmptyWhenRequired' => true ] ],
			'Max bytes, ok' => [ 'abcd', 'abcd', $maxBytes ],
			'Max bytes, exceeded' => [
				'abcde',
				new ValidationException( 'test', '', [], 'maxbytes', [ 'maxbytes' => 4, 'maxchars' => '' ] ),
				$maxBytes,
			],
			'Max bytes, ok (2)' => [ '😄', '😄', $maxBytes ],
			'Max bytes, exceeded (2)' => [
				'😭?',
				new ValidationException( 'test', '', [], 'maxbytes', [ 'maxbytes' => 4, 'maxchars' => '' ] ),
				$maxBytes,
			],
			'Max chars, ok' => [ 'ab', 'ab', $maxChars ],
			'Max chars, exceeded' => [
				'abc',
				new ValidationException( 'test', '', [], 'maxchars', [ 'maxbytes' => '', 'maxchars' => 2 ] ),
				$maxChars,
			],
			'Max chars, ok (2)' => [ '😄😄', '😄😄', $maxChars ],
			'Max chars, exceeded (2)' => [
				'😭??',
				new ValidationException( 'test', '', [], 'maxchars', [ 'maxbytes' => '', 'maxchars' => 2 ] ),
				$maxChars,
			],
		];
	}

}
