<?php
/**
 * JSON Schema Content Handler
 *
 * @file
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */

class JSONContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_JSON ) );
	}

	/**
	 * Unserializes a JSONContent object.
	 *
	 * @param string $text Serialized form of the content
	 * @param null|string $format The format used for serialization
	 *
	 * @return JSONContent
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );
		return new JSONContent( $text );
	}

	/**
	 * Creates an empty JSONContent object.
	 *
	 * @return JSONContent
	 */
	public function makeEmptyContent() {
		return new JSONContent( '' );
	}

	/** JSON is English **/
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/** JSON is English **/
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}
