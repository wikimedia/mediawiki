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
class JSONContentHandler extends TextContentHandler {

	/**
	 * The class name of objects that should be created
	 *
	 * @var string
	 */
	protected $contentClass = 'JSONContent';

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
		return new $this->contentClass( $text );
	}

	/**
	 * Creates an empty JSONContent object.
	 *
	 * @return JSONContent
	 */
	public function makeEmptyContent() {
		return new $this->contentClass( '' );
	}

	/**
	 * Returns the english language, because JSON is english, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content|null $content
	 *
	 * @return Language Return of wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * Returns the english language, because JSON is english, and should be handled as such.
	 *
	 * @param Title $title
	 * @param Content|null $content
	 *
	 * @return Language Return of wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}
