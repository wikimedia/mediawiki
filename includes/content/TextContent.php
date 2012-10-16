<?php

/**
 * Content object implementation for representing flat text.
 *
 * TextContent instances are immutable
 *
 * @since 1.21
 */
class TextContent extends AbstractContent {

	public function __construct( $text, $model_id = CONTENT_MODEL_TEXT ) {
		parent::__construct( $model_id );

		if ( !is_string( $text ) ) {
			throw new MWException( "TextContent expects a string in the constructor." );
		}

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
	 * Fills the provided ParserOutput object with the HTML returned by getHtml().
	 *
	 * Subclasses may override this to provide custom rendering.
	 *
	 * @param $title Title Context title for parsing
	 * @param $revId int|null Revision ID (for {{REVISIONID}})
	 * @param $options ParserOptions|null Parser options
	 * @param $generateHtml bool Whether or not to generate HTML
	 * @param $output ParserOutput The output object to fill (reference).
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$output->setText( $html );
	}

	/**
	 * Generates an HTML version of the content, for display. Used by
	 * getParserOutput() to construct a ParserOutput object.
	 *
	 * This default implementation runs the text returned by $this->getNativeData()
	 * through htmlspecialchars and tried to convert line breaks and indentation to HTML..
	 *
	 * @return string An HTML representation of the content
	 */
	protected function getHtml() {
		$html = htmlspecialchars( $this->getNativeData() );
		$html = preg_replace( '/(\r\n|\r|\n)/', '<br/>', $html );
		$html = preg_replace( '/\t/', '&nbsp;&nbsp;&nbsp;&nbsp;', $html );
		$html = preg_replace( '/  /', '&nbsp; ', $html );

		return $html;
	}
}
