<?php

/**
 * Content object implementation for representing flat text.
 *
 * TextContent instances are immutable
 *
 * @since 1.21
 */
abstract class TextContent extends AbstractContent {

	public function __construct( $text, $model_id = null ) {
		parent::__construct( $model_id );

		$this->mText = $text;
	}

	public function copy() {
		return $this; # NOTE: this is ok since TextContent are immutable.
	}

	public function getTextForSummary( $maxlength = 250 ) {
		global $wgContLang;

		$text = $this->getNativeData();

		$truncatedtext = $wgContLang->truncate(
			preg_replace( "/[\n\r]/", ' ', $text ),
			max( 0, $maxlength ) );

		return $truncatedtext;
	}

	/**
	 * returns the text's size in bytes.
	 *
	 * @return int The size
	 */
	public function getSize( ) {
		$text = $this->getNativeData( );
		return strlen( $text );
	}

	/**
	 * Returns true if this content is not a redirect, and $wgArticleCountMethod
	 * is "any".
	 *
	 * @param $hasLinks Bool: if it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool True if the content is countable
	 */
	public function isCountable( $hasLinks = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect( ) ) {
			return false;
		}

		if ( $wgArticleCountMethod === 'any' ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getNativeData( ) {
		$text = $this->mText;
		return $text;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getTextForSearchIndex( ) {
		return $this->getNativeData();
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getWikitextForTransclusion( ) {
		return $this->getNativeData();
	}

	/**
	 * Diff this content object with another content object..
	 *
	 * @since 1.21diff
	 *
	 * @param $that Content the other content object to compare this content object to
	 * @param $lang Language the language object to use for text segmentation.
	 *    If not given, $wgContentLang is used.
	 *
	 * @return DiffResult a diff representing the changes that would have to be
	 *    made to this content object to make it equal to $that.
	 */
	public function diff( Content $that, Language $lang = null ) {
		global $wgContLang;

		$this->checkModelID( $that->getModel() );

		# @todo: could implement this in DifferenceEngine and just delegate here?

		if ( !$lang ) $lang = $wgContLang;

		$otext = $this->getNativeData();
		$ntext = $this->getNativeData();

		# Note: Use native PHP diff, external engines don't give us abstract output
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );

		$diff = new Diff( $ota, $nta );
		return $diff;
	}


	/**
	 * Returns a generic ParserOutput object, wrapping the HTML returned by
	 * getHtml().
	 *
	 * @param $title Title Context title for parsing
	 * @param $revId int|null Revision ID (for {{REVISIONID}})
	 * @param $options ParserOptions|null Parser options
	 * @param $generateHtml bool Whether or not to generate HTML
	 *
	 * @return ParserOutput representing the HTML form of the text
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		# Generic implementation, relying on $this->getHtml()

		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$po = new ParserOutput( $html );
		return $po;
	}

	/**
	 * Generates an HTML version of the content, for display. Used by
	 * getParserOutput() to construct a ParserOutput object.
	 *
	 * This default implementation just calls getHighlightHtml(). Content
	 * models that have another mapping to HTML (as is the case for markup
	 * languages like wikitext) should override this method to generate the
	 * appropriate HTML.
	 *
	 * @return string An HTML representation of the content
	 */
	protected function getHtml() {
		return $this->getHighlightHtml();
	}

	/**
	 * Generates a syntax-highlighted version of the content, as HTML.
	 * Used by the default implementation of getHtml().
	 *
	 * @return string an HTML representation of the content's markup
	 */
	protected function getHighlightHtml( ) {
		# TODO: make Highlighter interface, use highlighter here, if available
		return htmlspecialchars( $this->getNativeData() );
	}
}