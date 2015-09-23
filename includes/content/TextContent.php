<?php
/**
 * Content object implementation for representing flat text.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * Content object implementation for representing flat text.
 *
 * TextContent instances are immutable
 *
 * @ingroup Content
 */
class TextContent extends AbstractContent {

	/**
	 * @param string $text
	 * @param string $model_id
	 * @throws MWException
	 */
	public function __construct( $text, $model_id = CONTENT_MODEL_TEXT ) {
		parent::__construct( $model_id );

		if ( $text === null || $text === false ) {
			wfWarn( "TextContent constructed with \$text = " . var_export( $text, true ) . "! "
				. "This may indicate an error in the caller's scope.", 2 );

			$text = '';
		}

		if ( !is_string( $text ) ) {
			throw new MWException( "TextContent expects a string in the constructor." );
		}

		$this->mText = $text;
	}

	/**
	 * @note Mutable subclasses MUST override this to return a copy!
	 *
	 * @return Content $this
	 */
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
	 * Returns the text's size in bytes.
	 *
	 * @return int
	 */
	public function getSize() {
		$text = $this->getNativeData();

		return strlen( $text );
	}

	/**
	 * Returns true if this content is not a redirect, and $wgArticleCountMethod
	 * is "any".
	 *
	 * @param bool $hasLinks If it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect() ) {
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
	 * @return string The raw text.
	 */
	public function getNativeData() {
		return $this->mText;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @return string The raw text.
	 */
	public function getTextForSearchIndex() {
		return $this->getNativeData();
	}

	/**
	 * Returns attempts to convert this content object to wikitext,
	 * and then returns the text string. The conversion may be lossy.
	 *
	 * @note this allows any text-based content to be transcluded as if it was wikitext.
	 *
	 * @return string|bool The raw text, or false if the conversion failed.
	 */
	public function getWikitextForTransclusion() {
		$wikitext = $this->convert( CONTENT_MODEL_WIKITEXT, 'lossy' );

		if ( $wikitext ) {
			return $wikitext->getNativeData();
		} else {
			return false;
		}
	}

	/**
	 * Returns a Content object with pre-save transformations applied.
	 * This implementation just trims trailing whitespace.
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 *
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$text = $this->getNativeData();
		$pst = rtrim( $text );

		return ( $text === $pst ) ? $this : new static( $pst, $this->getModel() );
	}

	/**
	 * Diff this content object with another content object.
	 *
	 * @since 1.21
	 *
	 * @param Content $that The other content object to compare this content object to.
	 * @param Language $lang The language object to use for text segmentation.
	 *    If not given, $wgContentLang is used.
	 *
	 * @return Diff A diff representing the changes that would have to be
	 *    made to this content object to make it equal to $that.
	 */
	public function diff( Content $that, Language $lang = null ) {
		global $wgContLang;

		$this->checkModelID( $that->getModel() );

		// @todo could implement this in DifferenceEngine and just delegate here?

		if ( !$lang ) {
			$lang = $wgContLang;
		}

		$otext = $this->getNativeData();
		$ntext = $that->getNativeData();

		# Note: Use native PHP diff, external engines don't give us abstract output
		$ota = explode( "\n", $lang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $lang->segmentForDiff( $ntext ) );

		$diff = new Diff( $ota, $nta );

		return $diff;
	}

	/**
	 * Fills the provided ParserOutput object with information derived from the content.
	 * Unless $generateHtml was false, this includes an HTML representation of the content
	 * provided by getHtml().
	 *
	 * For content models listed in $wgTextModelsToParse, this method will call the MediaWiki
	 * wikitext parser on the text to extract any (wikitext) links, magic words, etc.
	 *
	 * Subclasses may override this to provide custom content processing.
	 * For custom HTML generation alone, it is sufficient to override getHtml().
	 *
	 * @param Title $title Context title for parsing
	 * @param int $revId Revision ID (for {{REVISIONID}})
	 * @param ParserOptions $options Parser options
	 * @param bool $generateHtml Whether or not to generate HTML
	 * @param ParserOutput $output The output object to fill (reference).
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		global $wgParser, $wgTextModelsToParse;

		if ( in_array( $this->getModel(), $wgTextModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = $wgParser->parse( $this->getNativeData(), $title, $options, true, true, $revId );
		}

		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$output->setText( $html );
	}

	/**
	 * Generates an HTML version of the content, for display. Used by
	 * fillParserOutput() to provide HTML for the ParserOutput object.
	 *
	 * Subclasses may override this to provide a custom HTML rendering.
	 * If further information is to be derived from the content (such as
	 * categories), the fillParserOutput() method can be overridden instead.
	 *
	 * For backwards-compatibility, this default implementation just calls
	 * getHighlightHtml().
	 *
	 * @return string An HTML representation of the content
	 */
	protected function getHtml() {
		return $this->getHighlightHtml();
	}

	/**
	 * Generates an HTML version of the content, for display.
	 *
	 * This default implementation returns an HTML-escaped version
	 * of the raw text content.
	 *
	 * @note The functionality of this method should really be implemented
	 * in getHtml(), and subclasses should override getHtml() if needed.
	 * getHighlightHtml() is kept around for backward compatibility with
	 * extensions that already override it.
	 *
	 * @deprecated since 1.24. Use getHtml() instead. In particular, subclasses overriding
	 *     getHighlightHtml() should override getHtml() instead.
	 *
	 * @return string An HTML representation of the content
	 */
	protected function getHighlightHtml() {
		return htmlspecialchars( $this->getNativeData() );
	}

	/**
	 * This implementation provides lossless conversion between content models based
	 * on TextContent.
	 *
	 * @param string $toModel The desired content model, use the CONTENT_MODEL_XXX flags.
	 * @param string $lossy Flag, set to "lossy" to allow lossy conversion. If lossy conversion is not
	 *     allowed, full round-trip conversion is expected to work without losing information.
	 *
	 * @return Content|bool A content object with the content model $toModel, or false if that
	 *     conversion is not supported.
	 *
	 * @see Content::convert()
	 */
	public function convert( $toModel, $lossy = '' ) {
		$converted = parent::convert( $toModel, $lossy );

		if ( $converted !== false ) {
			return $converted;
		}

		$toHandler = ContentHandler::getForModelID( $toModel );

		if ( $toHandler instanceof TextContentHandler ) {
			// NOTE: ignore content serialization format - it's just text anyway.
			$text = $this->getNativeData();
			$converted = $toHandler->unserializeContent( $text );
		}

		return $converted;
	}

}
