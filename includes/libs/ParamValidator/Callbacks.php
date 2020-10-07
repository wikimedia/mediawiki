<?php

namespace Wikimedia\ParamValidator;

use Psr\Http\Message\UploadedFileInterface;
use Wikimedia\Message\DataMessageValue;

/**
 * Interface defining callbacks needed by ParamValidator
 *
 * The user of ParamValidator is expected to pass an object implementing this
 * interface to ParamValidator's constructor.
 *
 * All methods in this interface accept an "options array". This is the same `$options`
 * passed to ParamValidator::getValue(), ParamValidator::validateValue(), and the like
 * and is intended for communication of non-global state.
 *
 * @since 1.34
 * @unstable
 */
interface Callbacks {

	/**
	 * Test if a parameter exists in the request
	 * @param string $name Parameter name
	 * @param array $options Options array
	 * @return bool True if present, false if absent.
	 *  Return false for file upload parameters.
	 */
	public function hasParam( $name, array $options );

	/**
	 * Fetch a value from the request
	 *
	 * Return `$default` for file-upload parameters.
	 *
	 * @param string $name Parameter name to fetch
	 * @param mixed $default Default value to return if the parameter is unset.
	 * @param array $options Options array
	 * @return string|string[]|mixed A string or string[] if the parameter was found,
	 *  or $default if it was not.
	 */
	public function getValue( $name, $default, array $options );

	/**
	 * Test if a parameter exists as an upload in the request
	 * @param string $name Parameter name
	 * @param array $options Options array
	 * @return bool True if present, false if absent.
	 */
	public function hasUpload( $name, array $options );

	/**
	 * Fetch data for a file upload
	 * @param string $name Parameter name of the upload
	 * @param array $options Options array
	 * @return UploadedFileInterface|null Uploaded file, or null if there is no file for $name.
	 */
	public function getUploadedFile( $name, array $options );

	/**
	 * Record non-fatal conditions.
	 * @param DataMessageValue $message Failure message
	 * @param string $name Parameter name
	 * @param mixed $value Parameter value
	 * @param array $settings Parameter settings array
	 * @param array $options Options array
	 */
	public function recordCondition(
		DataMessageValue $message, $name, $value, array $settings, array $options
	);

	/**
	 * Indicate whether "high limits" should be used.
	 *
	 * Some settings have multiple limits, one for "normal" users and a higher
	 * one for "privileged" users. This is used to determine which class the
	 * current user is in when necessary.
	 *
	 * @param array $options Options array
	 * @return bool Whether the current user is privileged to use high limits
	 */
	public function useHighLimits( array $options );

}
