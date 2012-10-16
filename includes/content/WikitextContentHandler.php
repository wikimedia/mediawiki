<?php

/**
 * @since 1.21
 */
class WikitextContentHandler extends TextContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_WIKITEXT ) {
		parent::__construct( $modelId, array( CONTENT_FORMAT_WIKITEXT ) );
	}

	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		return new WikitextContent( $text );
	}

	/**
	 * @see ContentHandler::makeEmptyContent
	 *
	 * @return Content
	 */
	public function makeEmptyContent() {
		return new WikitextContent( '' );
	}


	/**
	 * Returns a WikitextContent object representing a redirect to the given destination page.
	 *
	 * @see ContentHandler::makeRedirectContent
	 *
	 * @param Title $destination the page to redirect to.
	 *
	 * @return Content
	 */
	public function makeRedirectContent( Title $destination ) {
		$mwRedir = MagicWord::get( 'redirect' );
		$redirectText = $mwRedir->getSynonym( 0 ) . ' [[' . $destination->getPrefixedText() . "]]\n";

		return new WikitextContent( $redirectText );
	}

	/**
	 * Returns true because wikitext supports sections.
	 *
	 * @return boolean whether sections are supported.
	 */
	public function supportsSections() {
		return true;
	}

	/**
	 * Returns true, because wikitext supports caching using the
	 * ParserCache mechanism.
	 *
	 * @since 1.21
	 * @return bool
	 */
	public function isParserCacheSupported() {
		return true;
	}
}