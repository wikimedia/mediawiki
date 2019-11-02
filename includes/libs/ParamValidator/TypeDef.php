<?php

namespace Wikimedia\ParamValidator;

/**
 * Base definition for ParamValidator types.
 *
 * All methods in this class accept an "options array". This is just the `$options`
 * passed to ParamValidator::getValue(), ParamValidator::validateValue(), and the like
 * and is intended for communication of non-global state.
 *
 * @since 1.34
 * @unstable
 */
abstract class TypeDef {

	/** @var Callbacks */
	protected $callbacks;

	public function __construct( Callbacks $callbacks ) {
		$this->callbacks = $callbacks;
	}

	/**
	 * Get the value from the request
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
	 *   - values-list: (string[]) If defined, values of a multi-valued parameter are being processed
	 *     (and this array holds the full set of values).
	 * @return mixed Validated value
	 * @throws ValidationException if the value is invalid
	 */
	abstract public function validate( $name, $value, array $settings, array $options );

	/**
	 * Normalize a settings array
	 * @param array $settings
	 * @return array
	 */
	public function normalizeSettings( array $settings ) {
		return $settings;
	}

	/**
	 * Get the values for enum-like parameters
	 *
	 * This is primarily intended for documentation and implementation of
	 * PARAM_ALL; it is the responsibility of the TypeDef to ensure that validate()
	 * accepts the values returned here.
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
	 * "Describe" a settings array
	 *
	 * This is intended to format data about a settings array using this type
	 * in a way that would be useful for automatically generated documentation
	 * or a machine-readable interface specification.
	 *
	 * Keys in the description array should follow the same guidelines as the
	 * code described for ValidationException.
	 *
	 * By default, each value in the description array is a single string,
	 * integer, or array. When `$options['compact']` is supplied, each value is
	 * instead an array of such and related values may be combined. For example,
	 * a non-compact description for an integer type might include
	 * `[ 'default' => 0, 'min' => 0, 'max' => 5 ]`, while in compact mode it might
	 * instead report `[ 'default' => [ 'value' => 0 ], 'minmax' => [ 'min' => 0, 'max' => 5 ] ]`
	 * to facilitate auto-generated documentation turning that 'minmax' into
	 * "Value must be between 0 and 5" rather than disconnected statements
	 * "Value must be >= 0" and "Value must be <= 5".
	 *
	 * @param string $name Parameter name being described.
	 * @param array $settings Parameter settings array.
	 * @param array $options Options array. Defined options for this base class are:
	 *  - 'compact': (bool) Enable compact mode, as described above.
	 * @return array
	 */
	public function describeSettings( $name, array $settings, array $options ) {
		$compact = !empty( $options['compact'] );

		$ret = [];

		if ( isset( $settings[ParamValidator::PARAM_DEFAULT] ) ) {
			$value = $this->stringifyValue(
				$name, $settings[ParamValidator::PARAM_DEFAULT], $settings, $options
			);
			$ret['default'] = $compact ? [ 'value' => $value ] : $value;
		}

		return $ret;
	}

}
