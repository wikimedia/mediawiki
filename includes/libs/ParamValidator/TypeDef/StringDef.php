<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for string types
 *
 * The result from validate() is a PHP string.
 *
 * ValidationException codes:
 *  - 'missingparam': The parameter is the empty string (and that's not allowed). No data.
 *
 * Additional codes may be generated when using certain PARAM constants. See
 * the constants' documentation for details.
 *
 * @since 1.34
 * @unstable
 */
class StringDef extends TypeDef {

	/**
	 * (integer) Maximum length of a string in bytes.
	 *
	 * ValidationException codes:
	 *  - 'maxbytes': The string is too long. Data:
	 *     - 'maxbytes': The maximum number of bytes allowed
	 *     - 'maxchars': The maximum number of characters allowed
	 */
	const PARAM_MAX_BYTES = 'param-max-bytes';

	/**
	 * (integer) Maximum length of a string in characters (Unicode codepoints).
	 *
	 * The string is assumed to be encoded as UTF-8.
	 *
	 * ValidationException codes:
	 *  - 'maxchars': The string is too long. Data:
	 *     - 'maxbytes': The maximum number of bytes allowed
	 *     - 'maxchars': The maximum number of characters allowed
	 */
	const PARAM_MAX_CHARS = 'param-max-chars';

	protected $allowEmptyWhenRequired = false;

	/**
	 * @param Callbacks $callbacks
	 * @param array $options Options:
	 *  - allowEmptyWhenRequired: (bool) Whether to reject the empty string when PARAM_REQUIRED.
	 *    Defaults to false.
	 */
	public function __construct( Callbacks $callbacks, array $options = [] ) {
		parent::__construct( $callbacks );

		$this->allowEmptyWhenRequired = !empty( $options['allowEmptyWhenRequired'] );
	}

	public function validate( $name, $value, array $settings, array $options ) {
		if ( !$this->allowEmptyWhenRequired && $value === '' &&
			!empty( $settings[ParamValidator::PARAM_REQUIRED] )
		) {
			throw new ValidationException( $name, $value, $settings, 'missingparam', [] );
		}

		if ( isset( $settings[self::PARAM_MAX_BYTES] )
			&& strlen( $value ) > $settings[self::PARAM_MAX_BYTES]
		) {
			throw new ValidationException( $name, $value, $settings, 'maxbytes', [
				'maxbytes' => $settings[self::PARAM_MAX_BYTES] ?? '',
				'maxchars' => $settings[self::PARAM_MAX_CHARS] ?? '',
			] );
		}
		if ( isset( $settings[self::PARAM_MAX_CHARS] )
			&& mb_strlen( $value, 'UTF-8' ) > $settings[self::PARAM_MAX_CHARS]
		) {
			throw new ValidationException( $name, $value, $settings, 'maxchars', [
				'maxbytes' => $settings[self::PARAM_MAX_BYTES] ?? '',
				'maxchars' => $settings[self::PARAM_MAX_CHARS] ?? '',
			] );
		}

		return $value;
	}

}
