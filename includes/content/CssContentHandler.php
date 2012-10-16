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
	 * Returns the english language, because CSS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageLanguage()
	 */
	public function getPageLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * Returns the english language, because CSS is english, and should be handled as such.
	 *
	 * @return Language wfGetLangObj( 'en' )
	 *
	 * @see ContentHandler::getPageViewLanguage()
	 */
	public function getPageViewLanguage( Title $title, Content $content = null ) {
		return wfGetLangObj( 'en' );
	}

	/**
	 * @see ContentHandler::isParserCacheSupported
	 *
	 * Currently returns false, so the ShowRawCssJs hook has a chance to intercept rendering.
	 * Can be set to true once extensions use ContentGetParserOutput instead of ShowRawCssJs.
	 *
	 * @since 1.21
	 * @return bool
	 */
	public function isParserCacheSupported() {
		return false;
	}
}