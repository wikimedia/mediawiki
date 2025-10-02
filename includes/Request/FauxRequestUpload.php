<?php
/**
 * Object to access a fake $_FILES array for testing purpose
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Request;

/**
 * Object to fake the $_FILES array
 *
 * @ingroup HTTP
 * @since 1.37
 */
class FauxRequestUpload extends WebRequestUpload {

	/**
	 * Constructor. Should only be called by MediaWiki\Request\FauxRequest
	 *
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake (whole) FILES values
	 * @param FauxRequest $request The associated faux request
	 * @param string $key name of upload param
	 */
	public function __construct( $data, $request, $key ) {
		$this->request = $request;
		$this->doesExist = isset( $data[$key] );
		if ( $this->doesExist ) {
			$this->fileInfo = $data[$key];
		}
	}

}
