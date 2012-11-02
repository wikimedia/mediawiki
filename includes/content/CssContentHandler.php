<?php

/**
 * @since 1.21
 */
class CssContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_CSS ) );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new CssContent( $text );
	}

	public function makeEmptyContent() {
		return new CssContent( '' );
	}

	/**
	 * Returns the English language, because CSS is English, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}
}
