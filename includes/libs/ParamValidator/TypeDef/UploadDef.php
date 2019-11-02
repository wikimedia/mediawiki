<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Psr\Http\Message\UploadedFileInterface;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\Util\UploadedFile;
use Wikimedia\ParamValidator\ValidationException;

/**
 * Type definition for upload types
 *
 * The result from validate() is an object implementing UploadedFileInterface.
 *
 * ValidationException codes:
 *  - 'badupload': The upload is not valid. No data.
 *  - 'badupload-inisize': The upload exceeded the maximum in php.ini. Data:
 *     - 'size': The configured size (in bytes).
 *  - 'badupload-formsize': The upload exceeded the maximum in the form post. No data.
 *  - 'badupload-partial': The file was only partially uploaded. No data.
 *  - 'badupload-nofile': There was no file. No data.
 *  - 'badupload-notmpdir': PHP has no temporary directory to store the upload. No data.
 *  - 'badupload-cantwrite': PHP could not store the upload. No data.
 *  - 'badupload-phpext': A PHP extension rejected the upload. No data.
 *  - 'badupload-notupload': The field was present in the submission but was not encoded as
 *    an upload. No data.
 *  - 'badupload-unknown': Some unknown PHP upload error code. Data:
 *     - 'code': The code.
 *
 * @since 1.34
 * @unstable
 */
class UploadDef extends TypeDef {

	public function getValue( $name, array $settings, array $options ) {
		$ret = $this->callbacks->getUploadedFile( $name, $options );

		if ( $ret && $ret->getError() === UPLOAD_ERR_NO_FILE &&
			!$this->callbacks->hasParam( $name, $options )
		) {
			// This seems to be that the client explicitly specified "no file" for the field
			// instead of just omitting the field completely. DWTM.
			$ret = null;
		} elseif ( !$ret && $this->callbacks->hasParam( $name, $options ) ) {
			// The client didn't format their upload properly so it came in as an ordinary
			// field. Convert it to an error.
			$ret = new UploadedFile( [
				'name' => '',
				'type' => '',
				'tmp_name' => '',
				'error' => -42, // PHP's UPLOAD_ERR_* are all positive numbers.
				'size' => 0,
			] );
		}

		return $ret;
	}

	/**
	 * Fetch the value of PHP's upload_max_filesize ini setting
	 *
	 * This method exists so it can be mocked by unit tests that can't
	 * affect ini_get() directly.
	 *
	 * @codeCoverageIgnore
	 * @return string|false
	 */
	protected function getIniSize() {
		return ini_get( 'upload_max_filesize' );
	}

	public function validate( $name, $value, array $settings, array $options ) {
		static $codemap = [
			-42 => 'notupload', // Local from getValue()
			UPLOAD_ERR_FORM_SIZE => 'formsize',
			UPLOAD_ERR_PARTIAL => 'partial',
			UPLOAD_ERR_NO_FILE => 'nofile',
			UPLOAD_ERR_NO_TMP_DIR => 'notmpdir',
			UPLOAD_ERR_CANT_WRITE => 'cantwrite',
			UPLOAD_ERR_EXTENSION => 'phpext',
		];

		if ( !$value instanceof UploadedFileInterface ) {
			// Err?
			throw new ValidationException( $name, $value, $settings, 'badupload', [] );
		}

		$err = $value->getError();
		if ( $err === UPLOAD_ERR_OK ) {
			return $value;
		} elseif ( $err === UPLOAD_ERR_INI_SIZE ) {
			static $prefixes = [
				'g' => 1024 ** 3,
				'm' => 1024 ** 2,
				'k' => 1024 ** 1,
			];
			$size = $this->getIniSize();
			$last = strtolower( substr( $size, -1 ) );
			$size = intval( $size, 10 ) * ( $prefixes[$last] ?? 1 );
			throw new ValidationException( $name, $value, $settings, 'badupload-inisize', [
				'size' => $size,
			] );
		} elseif ( isset( $codemap[$err] ) ) {
			throw new ValidationException( $name, $value, $settings, 'badupload-' . $codemap[$err], [] );
		} else {
			throw new ValidationException( $name, $value, $settings, 'badupload-unknown', [
				'code' => $err,
			] );
		}
	}

	public function stringifyValue( $name, $value, array $settings, array $options ) {
		// Not going to happen.
		return null;
	}

}
