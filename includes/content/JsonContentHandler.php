<?php
/**
 * JSON Schema Content Handler
 *
 * @file
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */

/**
 * @since 1.24
 */
class JsonContentHandler extends CodeContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JSON ) );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return 'JsonContent';
	}
}
