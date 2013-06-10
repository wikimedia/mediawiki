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

	public function __construct( $text, $model_id = CONTENT_MODEL_TEXT ) {
		parent::__construct( $model_id );

		if ( $text === null || $text === false ) {
			wfWarn( "TextContent constructed with \$text = " . var_export( $text, true ) . "! "
					. "This may indicate an error in the caller's scope." );

			$text = '';
		}

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
	public function getSize() {
		$text = $this->getNativeData();
		return strlen( $text );
	}

	/**
	 * Returns true if this content is not a redirect, and $wgArticleCountMethod
	 * is "any".
	 *
	 * @param bool $hasLinks if it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool True if the content is countable
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
	 * @return string: the raw text
	 */
	public function getNativeData() {
		$text = $this->mText;
		return $text;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @return string: the raw text
	 */
	public function getTextForSearchIndex() {
		return $this->getNativeData();
	}

	/**
	 * Returns attempts to convert this content object to wikitext,
	 * and then returns the text string. The conversion may be lossy.
	 *
	 * @note: this allows any text-based content to be transcluded as if it was wikitext.
	 *
	 * @return string|false: the raw text, or null if the conversion failed
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
	 * @param $title Title
	 * @param $user User
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$text = $this->getNativeData();
		$pst = rtrim( $text );

		return ( $text === $pst ) ? $this : new WikitextContent( $pst );
	}

	/**
	 * Diff this content object with another content object.
	 *
	 * @since 1.21diff
	 *
	 * @param $that Content: The other content object to compare this content
	 * object to.
	 * @param $lang Language: The language object to use for text segmentation.
	 *    If not given, $wgContentLang is used.
	 *
	 * @return DiffResult: A diff representing the changes that would have to be
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
		$ntext = $this->getNativeData();

		# Note: Use native PHP diff, external engines don't give us abstract output
		$ota = explode( "\n", $lang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $lang->segmentForDiff( $ntext ) );

		$diff = new Diff( $ota, $nta );
		return $diff;
	}

	/**
	 * Returns a generic ParserOutput object, wrapping the HTML returned by
	 * getHtml().
	 *
	 * @param $title Title Context title for parsing
	 * @param int|null $revId Revision ID (for {{REVISIONID}})
	 * @param $options ParserOptions|null Parser options
	 * @param bool $generateHtml Whether or not to generate HTML
	 *
	 * @return ParserOutput representing the HTML form of the text
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		global $wgParser, $wgTextModelsToParse;

		if ( !$options ) {
			//NOTE: use canonical options per default to produce cacheable output
			$options = $this->getContentHandler()->makeParserOptions( 'canonical' );
		}

		if ( in_array( $this->getModel(), $wgTextModelsToParse ) ) {
			// parse just to get links etc into the database
			$po = $wgParser->parse( $this->getNativeData(), $title, $options, true, true, $revId );
		} else {
			$po = new ParserOutput();
		}

		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$po->setText( $html );
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
	protected function getHighlightHtml() {
		# TODO: make Highlighter interface, use highlighter here, if available
		return htmlspecialchars( $this->getNativeData() );
	}

	/**
	 * @see Content::convert()
	 *
	 * This implementation provides lossless conversion between content models based
	 * on TextContent.
	 *
	 * @param string  $toModel the desired content model, use the CONTENT_MODEL_XXX flags.
	 * @param string  $lossy flag, set to "lossy" to allow lossy conversion. If lossy conversion is
	 * not allowed, full round-trip conversion is expected to work without losing information.
	 *
	 * @return Content|bool A content object with the content model $toModel, or false if
	 * that conversion is not supported.
	 */
	public function convert( $toModel, $lossy = '' ) {
		$converted = parent::convert( $toModel, $lossy );

		if ( $converted !== false ) {
			return $converted;
		}

		$toHandler = ContentHandler::getForModelID( $toModel );

		if ( $toHandler instanceof TextContentHandler ) {
			//NOTE: ignore content serialization format - it's just text anyway.
			$text = $this->getNativeData();
			$converted = $toHandler->unserializeContent( $text );
		}

		return $converted;
	}
}
