<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\Callbacks;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;

/**
 * Type definition for string types
 *
 * The result from validate() is a PHP string.
 *
 * Failure codes:
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
	 * When this option is set, the empty string is considered a proper value.
	 */
	public const OPT_ALLOW_EMPTY = 'allowEmptyWhenRequired';

	/**
	 * (integer) Maximum length of a string in bytes.
	 *
	 * Failure codes:
	 *  - 'maxbytes': The string is too long. Data:
	 *     - 'maxbytes': The maximum number of bytes allowed, or null if no limit
	 *     - 'maxchars': The maximum number of characters allowed, or null if no limit
	 */
	public const PARAM_MAX_BYTES = 'param-max-bytes';

	/**
	 * (integer) Maximum length of a string in characters (Unicode codepoints).
	 *
	 * The string is assumed to be encoded as UTF-8.
	 *
	 * Failure codes:
	 *  - 'maxchars': The string is too long. Data:
	 *     - 'maxbytes': The maximum number of bytes allowed, or null if no limit
	 *     - 'maxchars': The maximum number of characters allowed, or null if no limit
	 */
	public const PARAM_MAX_CHARS = 'param-max-chars';

	/** @var bool */
	protected $allowEmptyWhenRequired = false;

	/**
	 * @param Callbacks $callbacks
	 * @param array $options Options:
	 *  - allowEmptyWhenRequired: (bool) Whether to reject the empty string when PARAM_REQUIRED.
	 *    Defaults to false.
	 */
	public function __construct( Callbacks $callbacks, array $options = [] ) {
		parent::__construct( $callbacks );

		$this->allowEmptyWhenRequired = !empty( $options[ self::OPT_ALLOW_EMPTY ] );
	}

	/** @inheritDoc */
	public function validate( $name, $value, array $settings, array $options ) {
		$allowEmptyWhenRequired = $options[ self::OPT_ALLOW_EMPTY ]
			?? $this->allowEmptyWhenRequired;

		if ( !$allowEmptyWhenRequired && $value === '' &&
			!empty( $settings[ParamValidator::PARAM_REQUIRED] )
		) {
			$this->failure( 'missingparam', $name, $value, $settings, $options );
		}

		$this->failIfNotString( $name, $value, $settings, $options );

		$len = strlen( $value );
		if ( isset( $settings[self::PARAM_MAX_BYTES] ) && $len > $settings[self::PARAM_MAX_BYTES] ) {
			$this->failure(
				$this->failureMessage( 'maxbytes', [
					'maxbytes' => $settings[self::PARAM_MAX_BYTES] ?? null,
					'maxchars' => $settings[self::PARAM_MAX_CHARS] ?? null,
				] )->numParams( $settings[self::PARAM_MAX_BYTES], $len ),
				$name, $value, $settings, $options
			);
		}
		$len = mb_strlen( $value, 'UTF-8' );
		if ( isset( $settings[self::PARAM_MAX_CHARS] ) && $len > $settings[self::PARAM_MAX_CHARS] ) {
			$this->failure(
				$this->failureMessage( 'maxchars', [
					'maxbytes' => $settings[self::PARAM_MAX_BYTES] ?? null,
					'maxchars' => $settings[self::PARAM_MAX_CHARS] ?? null,
				] )->numParams( $settings[self::PARAM_MAX_CHARS], $len ),
				$name, $value, $settings, $options
			);
		}

		return $value;
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		$ret['allowedKeys'][] = self::PARAM_MAX_BYTES;
		$ret['allowedKeys'][] = self::PARAM_MAX_CHARS;

		$maxb = $settings[self::PARAM_MAX_BYTES] ?? PHP_INT_MAX;
		if ( !is_int( $maxb ) ) {
			$ret['issues'][self::PARAM_MAX_BYTES] = 'PARAM_MAX_BYTES must be an integer, got '
				. gettype( $maxb );
		} elseif ( $maxb < 0 ) {
			$ret['issues'][self::PARAM_MAX_BYTES] = 'PARAM_MAX_BYTES must be greater than or equal to 0';
		}

		$maxc = $settings[self::PARAM_MAX_CHARS] ?? PHP_INT_MAX;
		if ( !is_int( $maxc ) ) {
			$ret['issues'][self::PARAM_MAX_CHARS] = 'PARAM_MAX_CHARS must be an integer, got '
				. gettype( $maxc );
		} elseif ( $maxc < 0 ) {
			$ret['issues'][self::PARAM_MAX_CHARS] = 'PARAM_MAX_CHARS must be greater than or equal to 0';
		}

		if ( !$this->allowEmptyWhenRequired && !empty( $settings[ParamValidator::PARAM_REQUIRED] ) ) {
			if ( $maxb === 0 ) {
				$ret['issues'][] = 'PARAM_REQUIRED is set, allowEmptyWhenRequired is not set, and '
					. 'PARAM_MAX_BYTES is 0. That\'s impossible to satisfy.';
			}
			if ( $maxc === 0 ) {
				$ret['issues'][] = 'PARAM_REQUIRED is set, allowEmptyWhenRequired is not set, and '
					. 'PARAM_MAX_CHARS is 0. That\'s impossible to satisfy.';
			}
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getParamInfo( $name, array $settings, array $options ) {
		$info = parent::getParamInfo( $name, $settings, $options );

		$info['maxbytes'] = $settings[self::PARAM_MAX_BYTES] ?? null;
		$info['maxchars'] = $settings[self::PARAM_MAX_CHARS] ?? null;

		return $info;
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		if ( isset( $settings[self::PARAM_MAX_BYTES] ) ) {
			$info[self::PARAM_MAX_BYTES] = MessageValue::new( 'paramvalidator-help-type-string-maxbytes' )
				->numParams( $settings[self::PARAM_MAX_BYTES] );
		}
		if ( isset( $settings[self::PARAM_MAX_CHARS] ) ) {
			$info[self::PARAM_MAX_CHARS] = MessageValue::new( 'paramvalidator-help-type-string-maxchars' )
				->numParams( $settings[self::PARAM_MAX_CHARS] );
		}

		return $info;
	}

}
