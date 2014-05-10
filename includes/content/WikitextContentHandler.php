<?php
/**
 * Content handler for wiki text pages.
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
 */

/**
 * Content handler for wiki text pages.
 *
 * @ingroup Content
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
	 * @return Content A new WikitextContent object with empty text.
	 *
	 * @see ContentHandler::makeEmptyContent
	 */
	public function makeEmptyContent() {
		return new WikitextContent( '' );
	}

	/**
	 * @see ContentHandler::supportsHtmlTransclusion()
	 *
	 * @return bool true
	 */
	public function supportsHtmlTransclusion() {
		return true;
	}

	/**
	 * @see ContentHandler::makeContentFromHtml
	 *
	 * Wraps the given HTML in a way that allows inclusion in wikitext.
	 * Depending on the ParserOptions provided via $context, this may make
	 * use of $parser->insertStripItem() to protect the HTML from wikitext processing.
	 *
	 * The intent of this method is to allow the inclusion of any kind of content
	 * as a template in wikitext, by using the content's HTML rendering.
	 *
	 * $parser->getOptions()->getHtmlTransclusionMode() is used
	 * to determine if and how raw HTML should be included in the wikitext.
	 *
	 * @see ParserOptions::getHtmlTransclusionMode
	 * @see ParserOptions::HTML_TRANSCLUSION_DISABLED
	 * @see ParserOptions::HTML_TRANSCLUSION_WRAP
	 * @see ParserOptions::HTML_TRANSCLUSION_PASS_THROUGH
	 *
	 * @param string HTML
	 * @param object|null $context The Parser to use as context for the conversion.
	 * If not given (or not a Parser object), this method will return null.
	 *
	 * @return Content|null A WikitextContent object wrapping the given HTML,
	 * or null.
	 */
	public function makeContentFromHtml( $html, $context = null ) {
		if ( $context === null || !( $context instanceof Parser ) ) {
			return null;
		}

		$mode = $context->getOptions()->getHtmlTransclusionMode();

		if ( $mode === ParserOptions::HTML_TRANSCLUSION_DISABLED ) {
			return null;
		}

		if ( $html === false ) {
			return null;
		}

		if ( $mode === ParserOptions::HTML_TRANSCLUSION_WRAP ) {
			// NOTE: this is intended for preprocessor passes without actual wikitext parsing,
			// as done e.g. by ApiExpandTemplates. The text generated below will be mangled
			// if parsed as wikitext, unless $wgRawHtml is enabled.
			$wikitext = '<html>' . $html . '</html>';
		} else {
			// Replace the actual HTML with a strip mark, to be substituted later,
			// to protected it from wikitext processing.
			$wikitext = $context->insertStripItem( $html );
		}

		return $this->unserializeContent( $wikitext );
	}

	/**
	 * Returns a WikitextContent object representing a redirect to the given destination page.
	 *
	 * @param Title $destination The page to redirect to.
	 * @param string $text Text to include in the redirect, if possible.
	 *
	 * @return Content
	 *
	 * @see ContentHandler::makeRedirectContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		$optionalColon = '';

		if ( $destination->getNamespace() == NS_CATEGORY ) {
			$optionalColon = ':';
		} else {
			$iw = $destination->getInterwiki();
			if ( $iw && Language::fetchLanguageName( $iw, null, 'mw' ) ) {
				$optionalColon = ':';
			}
		}

		$mwRedir = MagicWord::get( 'redirect' );
		$redirectText = $mwRedir->getSynonym( 0 ) .
			' [[' . $optionalColon . $destination->getFullText() . ']]';

		if ( $text != '' ) {
			$redirectText .= "\n" . $text;
		}

		return new WikitextContent( $redirectText );
	}

	/**
	 * Returns true because wikitext supports redirects.
	 *
	 * @return bool Always true.
	 *
	 * @see ContentHandler::supportsRedirects
	 */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * Returns true because wikitext supports sections.
	 *
	 * @return bool Always true.
	 *
	 * @see ContentHandler::supportsSections
	 */
	public function supportsSections() {
		return true;
	}

	/**
	 * Returns true, because wikitext supports caching using the
	 * ParserCache mechanism.
	 *
	 * @since 1.21
	 *
	 * @return bool Always true.
	 *
	 * @see ContentHandler::isParserCacheSupported
	 */
	public function isParserCacheSupported() {
		return true;
	}

}
