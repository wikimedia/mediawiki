<?php

namespace Wikimedia\ParamValidator;

use Exception;
use Throwable;
use UnexpectedValueException;

/**
 * Error reporting for ParamValidator
 *
 * @since 1.34
 * @unstable
 */
class ValidationException extends UnexpectedValueException {

	/** @var string */
	protected $paramName;

	/** @var mixed */
	protected $paramValue;

	/** @var array */
	protected $settings;

	/** @var string */
	protected $failureCode;

	/** @var (string|int|string[])[] */
	protected $failureData;

	/**
	 * @param string $name Parameter name being validated
	 * @param mixed $value Value of the parameter
	 * @param array $settings Settings array being used for validation
	 * @param string $code Failure code. See getFailureCode() for requirements.
	 * @param (string|int|string[])[] $data Data for the failure code.
	 *  See getFailureData() for requirements.
	 * @param Throwable|Exception|null $previous Previous exception causing this failure
	 */
	public function __construct( $name, $value, $settings, $code, $data, $previous = null ) {
		parent::__construct( self::formatMessage( $name, $code, $data ), 0, $previous );

		$this->paramName = $name;
		$this->paramValue = $value;
		$this->settings = $settings;
		$this->failureCode = $code;
		$this->failureData = $data;
	}

	/**
	 * Make a simple English message for the exception
	 * @param string $name
	 * @param string $code
	 * @param array $data
	 * @return string
	 */
	private static function formatMessage( $name, $code, $data ) {
		$ret = "Validation of `$name` failed: $code";
		foreach ( $data as $k => $v ) {
			if ( is_array( $v ) ) {
				$v = implode( ', ', $v );
			}
			$ret .= "; $k => $v";
		}
		return $ret;
	}

	/**
	 * Fetch the parameter name that failed validation
	 * @return string
	 */
	public function getParamName() {
		return $this->paramName;
	}

	/**
	 * Fetch the parameter value that failed validation
	 * @return mixed
	 */
	public function getParamValue() {
		return $this->paramValue;
	}

	/**
	 * Fetch the settings array that failed validation
	 * @return array
	 */
	public function getSettings() {
		return $this->settings;
	}

	/**
	 * Fetch the validation failure code
	 *
	 * A validation failure code is a reasonably short string matching the regex
	 * `/^[a-z][a-z0-9-]*$/`.
	 *
	 * Users are encouraged to use this with a suitable i18n mechanism rather
	 * than relying on the limited English text returned by getMessage().
	 *
	 * @return string
	 */
	public function getFailureCode() {
		return $this->failureCode;
	}

	/**
	 * Fetch the validation failure data
	 *
	 * This returns additional data relevant to the particular failure code.
	 *
	 * Keys in the array are short ASCII strings. Values are strings or
	 * integers, or arrays of strings intended to be displayed as a
	 * comma-separated list. For any particular code the same keys are always
	 * returned in the same order, making it safe to use array_values() and
	 * access them positionally if that is desired.
	 *
	 * For example, the data for a hypothetical "integer-out-of-range" code
	 * might have data `[ 'min' => 0, 'max' => 100 ]` indicating the range of
	 * allowed values.
	 *
	 * @return (string|int|string[])[]
	 */
	public function getFailureData() {
		return $this->failureData;
	}

}
