<?php
/**
 * Object to access the $_FILES array
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * Object to access the $_FILES array
 *
 * @ingroup HTTP
 */
class WebRequestUpload {
	protected $request;
	protected $doesExist;
	protected $fileInfo;

	/**
	 * Constructor. Should only be called by WebRequest
	 *
	 * @param WebRequest $request The associated request
	 * @param string $key Key in $_FILES array (name of form field)
	 */
	public function __construct( $request, $key ) {
		$this->request = $request;
		$this->doesExist = isset( $_FILES[$key] );
		if ( $this->doesExist ) {
			$this->fileInfo = $_FILES[$key];
		}
	}

	/**
	 * Return whether a file with this name was uploaded.
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->doesExist;
	}

	/**
	 * Return the original filename of the uploaded file
	 *
	 * @return string|null Filename or null if non-existent
	 */
	public function getName() {
		if ( !$this->exists() ) {
			return null;
		}

		global $wgContLang;
		$name = $this->fileInfo['name'];

		# Safari sends filenames in HTML-encoded Unicode form D...
		# Horrid and evil! Let's try to make some kind of sense of it.
		$name = Sanitizer::decodeCharReferences( $name );
		$name = $wgContLang->normalize( $name );
		wfDebug( __METHOD__ . ": {$this->fileInfo['name']} normalized to '$name'\n" );
		return $name;
	}

	/**
	 * Return the file size of the uploaded file
	 *
	 * @return int File size or zero if non-existent
	 */
	public function getSize() {
		if ( !$this->exists() ) {
			return 0;
		}

		return $this->fileInfo['size'];
	}

	/**
	 * Return the path to the temporary file
	 *
	 * @return string|null Path or null if non-existent
	 */
	public function getTempName() {
		if ( !$this->exists() ) {
			return null;
		}

		return $this->fileInfo['tmp_name'];
	}

	/**
	 * Return the upload error. See link for explanation
	 * http://www.php.net/manual/en/features.file-upload.errors.php
	 *
	 * @return int One of the UPLOAD_ constants, 0 if non-existent
	 */
	public function getError() {
		if ( !$this->exists() ) {
			return 0; # UPLOAD_ERR_OK
		}

		return $this->fileInfo['error'];
	}

	/**
	 * Returns whether this upload failed because of overflow of a maximum set
	 * in php.ini
	 *
	 * @return bool
	 */
	public function isIniSizeOverflow() {
		if ( $this->getError() == UPLOAD_ERR_INI_SIZE ) {
			# PHP indicated that upload_max_filesize is exceeded
			return true;
		}

		$contentLength = $this->request->getHeader( 'Content-Length' );
		$maxPostSize = wfShorthandToInteger(
			ini_get( 'post_max_size' ) ?: ini_get( 'hhvm.server.max_post_size' ),
			0
		);

		if ( $maxPostSize && $contentLength > $maxPostSize ) {
			# post_max_size is exceeded
			return true;
		}

		return false;
	}
}
