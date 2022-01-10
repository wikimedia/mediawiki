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

use MediaWiki\MediaWikiServices;

/**
 * Content object implementation for representing flat text.
 *
 * TextContent instances are immutable
 *
 * @newable
 * @stable to extend
 * @ingroup Content
 */
class TextContent extends AbstractContent {

	/**
	 * @var string
	 */
	protected $mText;

	/**
	 * @stable to call
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

	/**
	 * @stable to override
	 *
	 * @param int $maxlength
	 *
	 * @return string
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		$text = $this->getText();

		$truncatedtext = MediaWikiServices::getInstance()->getContentLanguage()->
			truncateForDatabase( preg_replace( "/[\n\r]/", ' ', $text ), max( 0, $maxlength ) );

		return $truncatedtext;
	}

	/**
	 * Returns the text's size in bytes.
	 *
	 * @stable to override
	 *
	 * @return int
	 */
	public function getSize() {
		$text = $this->getText();

		return strlen( $text );
	}

	/**
	 * Returns true if this content is not a redirect, and $wgArticleCountMethod
	 * is "any".
	 *
	 * @stable to override
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		$articleCountMethod = MediaWikiServices::getInstance()->getMainConfig()->get( 'ArticleCountMethod' );

		if ( $this->isRedirect() ) {
			return false;
		}

		if ( $articleCountMethod === 'any' ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @deprecated since 1.33 use getText() instead.
	 *
	 * @return string The raw text. Subclasses may guarantee a specific syntax here.
	 */
	public function getNativeData() {
		return $this->getText();
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @since 1.33
	 * @note This method should not be overwritten by subclasses. If a subclass find itself in
	 * need to override this method, it should probably not be based on TextContent, but
	 * should rather extend AbstractContent instead.
	 *
	 * @return string The raw text.
	 */
	public function getText() {
		return $this->mText;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @stable to override
	 *
	 * @return string The raw text.
	 */
	public function getTextForSearchIndex() {
		return $this->getText();
	}

	/**
	 * Returns attempts to convert this content object to wikitext,
	 * and then returns the text string. The conversion may be lossy.
	 *
	 * @stable to override
	 *
	 * @note this allows any text-based content to be transcluded as if it was wikitext.
	 *
	 * @return string|bool The raw text, or false if the conversion failed.
	 */
	public function getWikitextForTransclusion() {
		/** @var WikitextContent $wikitext */
		$wikitext = $this->convert( CONTENT_MODEL_WIKITEXT, 'lossy' );
		'@phan-var WikitextContent $wikitext';

		if ( $wikitext ) {
			return $wikitext->getText();
		} else {
			return false;
		}
	}

	/**
	 * Do a "\r\n" -> "\n" and "\r" -> "\n" transformation
	 * as well as trim trailing whitespace
	 *
	 * This was formerly part of Parser::preSaveTransform, but
	 * for non-wikitext content models they probably still want
	 * to normalize line endings without all of the other PST
	 * changes.
	 *
	 * @since 1.28
	 * @param string $text
	 * @return string
	 */
	public static function normalizeLineEndings( $text ) {
		return str_replace( [ "\r\n", "\r" ], "\n", rtrim( $text ) );
	}

	/**
	 * Diff this content object with another content object.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Content $that The other content object to compare this content object to.
	 * @param Language|null $lang The language object to use for text segmentation.
	 *    If not given, the content language is used.
	 *
	 * @return Diff A diff representing the changes that would have to be
	 *    made to this content object to make it equal to $that.
	 */
	public function diff( Content $that, Language $lang = null ) {
		$this->checkModelID( $that->getModel() );
		/** @var self $that */
		'@phan-var self $that';
		// @todo could implement this in DifferenceEngine and just delegate here?

		if ( !$lang ) {
			$lang = MediaWikiServices::getInstance()->getContentLanguage();
		}

		$otext = $this->getText();
		$ntext = $that->getText();

		# Note: Use native PHP diff, external engines don't give us abstract output
		$ota = explode( "\n", $lang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $lang->segmentForDiff( $ntext ) );

		$diff = new Diff( $ota, $nta );

		return $diff;
	}

	/**
	 * This implementation provides lossless conversion between content models based
	 * on TextContent.
	 *
	 * @stable to override
	 *
	 * @param string $toModel The desired content model, use the CONTENT_MODEL_XXX flags.
	 * @param string $lossy Flag, set to "lossy" to allow lossy conversion. If lossy conversion is not
	 *     allowed, full round-trip conversion is expected to work without losing information.
	 *
	 * @return Content|bool A content object with the content model $toModel, or false if that
	 *     conversion is not supported.
	 * @throws MWUnknownContentModelException
	 *
	 * @see Content::convert()
	 */
	public function convert( $toModel, $lossy = '' ) {
		$converted = parent::convert( $toModel, $lossy );

		if ( $converted !== false ) {
			return $converted;
		}

		$toHandler = $this->getContentHandlerFactory()->getContentHandler( $toModel );

		if ( $toHandler instanceof TextContentHandler ) {
			// NOTE: ignore content serialization format - it's just text anyway.
			$text = $this->getText();
			$converted = $toHandler->unserializeContent( $text );
		}

		return $converted;
	}

}
