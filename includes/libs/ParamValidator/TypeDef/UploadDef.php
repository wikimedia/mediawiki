<?php

namespace Wikimedia\ParamValidator\TypeDef;

use InvalidArgumentException;
use Psr\Http\Message\UploadedFileInterface;
use UnexpectedValueException;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef;
use Wikimedia\ParamValidator\Util\UploadedFile;

/**
 * Type definition for upload types
 *
 * The result from validate() is an object implementing UploadedFileInterface.
 *
 * Failure codes:
 *  - 'badupload': The upload is not valid. Data:
 *     - 'code': A value indicating why the upload was not valid:
 *       - 'inisize': The upload exceeded the maximum in php.ini.
 *       - 'formsize': The upload exceeded the maximum in the form post.
 *       - 'partial': The file was only partially uploaded.
 *       - 'nofile': There was no file.
 *       - 'notmpdir': PHP has no temporary directory to store the upload.
 *       - 'cantwrite': PHP could not store the upload.
 *       - 'phpext': A PHP extension rejected the upload.
 *       - 'notupload': The field was present in the submission but was not encoded as an upload.
 *     - 'size': The configured size (in bytes), if 'code' is 'inisize'.
 *
 * @since 1.34
 * @unstable
 */
class UploadDef extends TypeDef {

	/** @inheritDoc */
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

	/** @inheritDoc */
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
			$type = get_debug_type( $value );
			throw new InvalidArgumentException( "\$value must be UploadedFileInterface, got $type" );
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
			$this->failure(
				$this->failureMessage( 'badupload', [
					'code' => 'inisize',
					'size' => $size,
				], 'inisize' )->sizeParams( $size ),
				$name, '', $settings, $options
			);
		} elseif ( isset( $codemap[$err] ) ) {
			$this->failure(
				$this->failureMessage( 'badupload', [ 'code' => $codemap[$err] ], $codemap[$err] ),
				$name, '', $settings, $options
			);
		} else {
			$constant = '';
			foreach ( get_defined_constants() as $c => $v ) {
				// @phan-suppress-next-line PhanTypeComparisonFromArray
				if ( $v === $err && str_starts_with( $c, 'UPLOAD_ERR_' ) ) {
					$constant = " ($c?)";
				}
			}
			throw new UnexpectedValueException( "Unrecognized PHP upload error value $err$constant" );
		}
	}

	/** @inheritDoc */
	public function checkSettings( string $name, $settings, array $options, array $ret ): array {
		$ret = parent::checkSettings( $name, $settings, $options, $ret );

		if ( isset( $settings[ParamValidator::PARAM_DEFAULT] ) ) {
			$ret['issues'][ParamValidator::PARAM_DEFAULT] =
				'Cannot specify a default for upload-type parameters';
		}

		if ( !empty( $settings[ParamValidator::PARAM_ISMULTI] ) &&
			!isset( $ret['issues'][ParamValidator::PARAM_ISMULTI] )
		) {
			$ret['issues'][ParamValidator::PARAM_ISMULTI] =
				'PARAM_ISMULTI cannot be used for upload-type parameters';
		}

		return $ret;
	}

	/** @inheritDoc */
	public function stringifyValue( $name, $value, array $settings, array $options ) {
		// Not going to happen.
		return null;
	}

	/** @inheritDoc */
	public function getHelpInfo( $name, array $settings, array $options ) {
		$info = parent::getHelpInfo( $name, $settings, $options );

		$info[ParamValidator::PARAM_TYPE] = MessageValue::new( 'paramvalidator-help-type-upload' );

		return $info;
	}

}
