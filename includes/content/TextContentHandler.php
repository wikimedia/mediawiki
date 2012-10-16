<?php

/**
 * @since 1.21
 */
class TextContentHandler extends ContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_TEXT, $formats = array( CONTENT_FORMAT_TEXT ) ) {
		parent::__construct( $modelId, $formats );
	}

	/**
	 * Returns the content's text as-is.
	 *
	 * @param $content Content
	 * @param $format string|null
	 * @return mixed
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );
		return $content->getNativeData();
	}

	/**
	 * Attempts to merge differences between three versions. Returns a new
	 * Content object for a clean merge and false for failure or a conflict.
	 *
	 * All three Content objects passed as parameters must have the same
	 * content model.
	 *
	 * This text-based implementation uses wfMerge().
	 *
	 * @param $oldContent \Content|string  String
	 * @param $myContent \Content|string   String
	 * @param $yourContent \Content|string String
	 *
	 * @return Content|Bool
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

		$format = $this->getDefaultFormat();

		$old = $this->serializeContent( $oldContent, $format );
		$mine = $this->serializeContent( $myContent, $format );
		$yours = $this->serializeContent( $yourContent, $format );

		$ok = wfMerge( $old, $mine, $yours, $result );

		if ( !$ok ) {
			return false;
		}

		if ( !$result ) {
			return $this->makeEmptyContent();
		}

		$mergedContent = $this->unserializeContent( $result, $format );
		return $mergedContent;
	}

	/**
	 * Unserializes a Content object of the type supported by this ContentHandler.
	 *
	 * @since 1.21
	 *
	 * @param $text   string serialized form of the content
	 * @param $format null|String the format used for serialization
	 *
	 * @return Content the TextContent object wrapping $text
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new TextContent( $text );
	}

	/**
	 * Creates an empty TextContent object.
	 *
	 * @since 1.21
	 *
	 * @return Content
	 */
	public function makeEmptyContent() {
		return new TextContent( '' );
	}
}