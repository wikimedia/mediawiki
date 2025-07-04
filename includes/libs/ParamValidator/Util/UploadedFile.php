<?php

namespace Wikimedia\ParamValidator\Util;

use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;
use Wikimedia\AtEase\AtEase;

/**
 * A simple implementation of UploadedFileInterface
 *
 * This exists so ParamValidator needn't depend on any specific PSR-7
 * implementation for a class implementing UploadedFileInterface. It shouldn't
 * be used directly by other code, other than perhaps when implementing
 * Callbacks::getUploadedFile() when another PSR-7 library is not already in use.
 *
 * @since 1.34
 * @unstable
 */
class UploadedFile implements UploadedFileInterface {

	/** @var array File data */
	private $data;

	/** @var bool */
	private $fromUpload;

	/** @var UploadedFileStream|null */
	private $stream = null;

	/** @var bool Whether moveTo() was called */
	private $moved = false;

	/**
	 * @param array $data Data from $_FILES
	 * @param bool $fromUpload Set false if using this task with data not from
	 *  $_FILES. Intended for unit testing.
	 */
	public function __construct( array $data, $fromUpload = true ) {
		$this->data = $data;
		$this->fromUpload = $fromUpload;
	}

	/**
	 * Throw if there was an error
	 * @throws RuntimeException
	 */
	private function checkError() {
		switch ( $this->data['error'] ) {
			case UPLOAD_ERR_OK:
				break;

			case UPLOAD_ERR_INI_SIZE:
				throw new RuntimeException( 'Upload exceeded maximum size' );

			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException( 'Upload exceeded form-specified maximum size' );

			case UPLOAD_ERR_PARTIAL:
				throw new RuntimeException( 'File was only partially uploaded' );

			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException( 'No file was uploaded' );

			case UPLOAD_ERR_NO_TMP_DIR:
				throw new RuntimeException( 'PHP has no temporary folder for storing uploaded files' );

			case UPLOAD_ERR_CANT_WRITE:
				throw new RuntimeException( 'PHP was unable to save the uploaded file' );

			case UPLOAD_ERR_EXTENSION:
				throw new RuntimeException( 'A PHP extension stopped the file upload' );

			default:
				throw new RuntimeException( 'Unknown upload error code ' . $this->data['error'] );
		}

		if ( $this->moved ) {
			throw new RuntimeException( 'File has already been moved' );
		}
		if ( !isset( $this->data['tmp_name'] ) || !file_exists( $this->data['tmp_name'] ) ) {
			throw new RuntimeException( 'Uploaded file is missing' );
		}
	}

	/** @inheritDoc */
	public function getStream() {
		if ( $this->stream ) {
			return $this->stream;
		}

		$this->checkError();
		$this->stream = new UploadedFileStream( $this->data['tmp_name'] );
		return $this->stream;
	}

	/** @inheritDoc */
	public function moveTo( $targetPath ) {
		$this->checkError();

		if ( $this->fromUpload && !is_uploaded_file( $this->data['tmp_name'] ) ) {
			throw new RuntimeException( 'Specified file is not an uploaded file' );
		}

		error_clear_last();
		$ret = AtEase::quietCall(
			$this->fromUpload ? 'move_uploaded_file' : 'rename',
			$this->data['tmp_name'],
			$targetPath
		);
		if ( $ret === false ) {
			$err = error_get_last();
			throw new RuntimeException( "Move failed: " . ( $err['message'] ?? 'Unknown error' ) );
		}

		$this->moved = true;
		if ( $this->stream ) {
			$this->stream->close();
			$this->stream = null;
		}
	}

	/** @inheritDoc */
	public function getSize() {
		return $this->data['size'] ?? null;
	}

	/** @inheritDoc */
	public function getError() {
		return $this->data['error'] ?? UPLOAD_ERR_NO_FILE;
	}

	/** @inheritDoc */
	public function getClientFilename() {
		$ret = $this->data['name'] ?? null;
		return $ret === '' ? null : $ret;
	}

	/** @inheritDoc */
	public function getClientMediaType() {
		$ret = $this->data['type'] ?? null;
		return $ret === '' ? null : $ret;
	}

}
