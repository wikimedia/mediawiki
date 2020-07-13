<?php

namespace Wikimedia\ParamValidator;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;

/**
 * Base definition for ParamValidator types.
 *
 * Most methods in this class accept an "options array". This is just the `$options`
 * passed to ParamValidator::getValue(), ParamValidator::validateValue(), and the like
 * and is intended for communication of non-global state to the Callbacks.
 *
 * @stable to extend
 * @since 1.34
 * @unstable
 */
abstract class TypeDef {

	/** @var Callbacks */
	protected $callbacks;

	/**
	 * @stable to call
	 *
	 * @param Callbacks $callbacks
	 */
	public function __construct( Callbacks $callbacks ) {
		$this->callbacks = $callbacks;
	}

	/**
	 * Record a failure message
	 *
	 * Depending on `$fatal`, this will either throw a ValidationException or
	 * call $this->callbacks->recordCondition().
	 *
	 * Note that parameters for `$name` and `$value` are always added as `$1`
	 * and `$2`.
	 *
	 * @param DataMessageValue|string $failure Failure code or message.
	 * @param string $name Parameter name being validated.
	 * @param mixed $value Value being validated.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @param bool $fatal Whether the failure is fatal
	 */
	protected function failure(
		$failure, $name, $value, array $settings, array $options, $fatal = true
	) {
		if ( !is_string( $value ) ) {
			$value = (string)$this->stringifyValue( $name, $value, $settings, $options );
		}

		if ( is_string( $failure ) ) {
			$mv = $this->failureMessage( $failure )
				->plaintextParams( $name, $value );
		} else {
			$mv = DataMessageValue::new( $failure->getKey(), [], $failure->getCode(), $failure->getData() )
				->plaintextParams( $name, $value )
				->params( ...$failure->getParams() );
		}

		if ( $fatal ) {
			throw new ValidationException( $mv, $name, $value, $settings );
		}
		$this->callbacks->recordCondition( $mv, $name, $value, $settings, $options );
	}

	/**
	 * Create a DataMessageValue representing a failure
	 *
	 * The message key will be "paramvalidator-$code" or "paramvalidator-$code-$suffix".
	 *
	 * Use DataMessageValue's param mutators to add additional MessageParams.
	 * Note that `failure()` will prepend parameters for `$name` and `$value`.
	 *
	 * @param string $code Failure code.
	 * @param array|null $data Failure data.
	 * @param string|null $suffix Suffix to append when producing the message key
	 * @return DataMessageValue
	 */
	protected function failureMessage( $code, array $data = null, $suffix = null ) : DataMessageValue {
		return DataMessageValue::new(
			"paramvalidator-$code" . ( $suffix !== null ? "-$suffix" : '' ),
			[], $code, $data
		);
	}

	/**
	 * Get the value from the request
	 * @stable to override
	 *
	 * @note Only override this if you need to use something other than
	 *  $this->callbacks->getValue() to fetch the value. Reformatting from a
	 *  string should typically be done by self::validate().
	 * @note Handling of ParamValidator::PARAM_DEFAULT should be left to ParamValidator,
	 *  as should PARAM_REQUIRED and the like.
	 *
	 * @param string $name Parameter name being fetched.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return null|mixed Return null if the value wasn't present, otherwise a
	 *  value to be passed to self::validate().
	 */
	public function getValue( $name, array $settings, array $options ) {
		return $this->callbacks->getValue( $name, null, $options );
	}

	/**
	 * Validate the value
	 *
	 * When ParamValidator is processing a multi-valued parameter, this will be
	 * called once for each of the supplied values. Which may mean zero calls.
	 *
	 * When getValue() returned null, this will not be called.
	 *
	 * @param string $name Parameter name being validated.
	 * @param mixed $value Value to validate, from getValue().
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array. Note the following values that may be set
	 *  by ParamValidator:
	 *   - is-default: (bool) If present and true, the value was taken from PARAM_DEFAULT rather
	 *     that being supplied by the client.
	 *   - values-list: (string[]) If defined, values of a multi-valued parameter are being processed
	 *     (and this array holds the full set of values).
	 * @return mixed Validated value
	 * @throws ValidationException if the value is invalid
	 */
	abstract public function validate( $name, $value, array $settings, array $options );

	/**
	 * Normalize a settings array
	 * @stable to override
	 * @param array $settings
	 * @return array
	 */
	public function normalizeSettings( array $settings ) {
		return $settings;
	}

	/**
	 * Validate a parameter settings array
	 *
	 * This is intended for validation of parameter settings during unit or
	 * integration testing, and should implement strict checks.
	 *
	 * The rest of the code should generally be more permissive.
	 *
	 * @see ParamValidator::checkSettings()
	 * @stable to override
	 *
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param array $options Options array, passed through to the TypeDef and Callbacks.
	 * @param array $ret
	 *  - 'issues': (string[]) Errors detected in $settings, as English text. If the settings
	 *    are valid, this will be the empty array. Keys on input are ParamValidator constants,
	 *    allowing the typedef to easily override core validation; this need not be preserved
	 *    when returned.
	 *  - 'allowedKeys': (string[]) ParamValidator keys that are allowed in `$settings`.
	 *  - 'messages': (MessageValue[]) Messages to be checked for existence.
	 * @return array $ret, with any relevant changes.
	 */
	public function checkSettings( string $name, $settings, array $options, array $ret ) : array {
		return $ret;
	}

	/**
	 * Get the values for enum-like parameters
	 *
	 * This is primarily intended for documentation and implementation of
	 * PARAM_ALL; it is the responsibility of the TypeDef to ensure that validate()
	 * accepts the values returned here.
	 * @stable to override
	 *
	 * @param string $name Parameter name being validated.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return array|null All possible enumerated values, or null if this is
	 *  not an enumeration.
	 */
	public function getEnumValues( $name, array $settings, array $options ) {
		return null;
	}

	/**
	 * Convert a value to a string representation.
	 *
	 * This is intended as the inverse of getValue() and validate(): this
	 * should accept anything returned by those methods or expected to be used
	 * as PARAM_DEFAULT, and if the string from this method is passed in as client
	 * input or PARAM_DEFAULT it should give equivalent output from validate().
	 *
	 * @param string $name Parameter name being converted.
	 * @param mixed $value Parameter value being converted. Do not pass null.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return string|null Return null if there is no representation of $value
	 *  reasonably satisfying the description given.
	 */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		return (string)$value;
	}

	/**
	 * Describe parameter settings in a machine-readable format.
	 *
	 * Keys should be short strings using lowercase ASCII letters. Values
	 * should generally be values that could be encoded in JSON or the like.
	 *
	 * This is intended to handle PARAM constants specific to this class. It
	 * generally shouldn't handle constants defined on ParamValidator itself.
	 * @stable to override
	 *
	 * @param string $name Parameter name.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return array
	 */
	public function getParamInfo( $name, array $settings, array $options ) {
		return [];
	}

	/**
	 * Describe parameter settings in human-readable format
	 *
	 * Keys in the returned array should generally correspond to PARAM
	 * constants.
	 *
	 * If relevant, a MessageValue describing the type itself should be
	 * returned with key ParamValidator::PARAM_TYPE.
	 *
	 * The default messages for other ParamValidator-defined PARAM constants
	 * may be suppressed by returning null as the value for those constants, or
	 * replaced by returning a replacement MessageValue. Normally, however,
	 * the default messages should not be changed.
	 *
	 * MessageValues describing any other constraints applied via PARAM
	 * constants specific to this class should also be returned.
	 * @stable to override
	 *
	 * @param string $name Parameter name being described.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array.
	 * @return (MessageValue|null)[]
	 */
	public function getHelpInfo( $name, array $settings, array $options ) {
		return [];
	}

}
