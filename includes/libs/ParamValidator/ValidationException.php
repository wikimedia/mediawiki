<?php

namespace Wikimedia\ParamValidator;

use Throwable;
use UnexpectedValueException;
use Wikimedia\Message\DataMessageValue;

/**
 * Error reporting for ParamValidator
 *
 * @newable
 * @since 1.34
 * @unstable
 */
class ValidationException extends UnexpectedValueException {

	/** @var DataMessageValue */
	protected $failureMessage;

	/** @var string */
	protected $paramName;

	/** @var mixed */
	protected $paramValue;

	/** @var array */
	protected $settings;

	/**
	 * @stable to call
	 * @param DataMessageValue $failureMessage Failure message.
	 * @param string $name Parameter name being validated
	 * @param mixed $value Value of the parameter
	 * @param array $settings Settings array being used for validation
	 * @param Throwable|null $previous Previous throwable causing this failure
	 */
	public function __construct(
		DataMessageValue $failureMessage, $name, $value, $settings, Throwable $previous = null
	) {
		$this->failureMessage = $failureMessage;
		$this->paramName = $name;
		$this->paramValue = $value;
		$this->settings = $settings;

		// Parent class needs some static English message.
		$msg = "Validation of `$name` failed: " . $failureMessage->getCode();
		$data = $failureMessage->getData();
		if ( $data ) {
			$msg .= ' ' . json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
		}
		parent::__construct( $msg, 0, $previous );
	}

	/**
	 * Fetch the validation failure message
	 *
	 * Users are encouraged to use this with an appropriate message formatter rather
	 * than relying on the limited English text returned by getMessage().
	 *
	 * @return DataMessageValue
	 */
	public function getFailureMessage() {
		return $this->failureMessage;
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

}
