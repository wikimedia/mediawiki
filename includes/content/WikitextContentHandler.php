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
		parent::__construct( $modelId, [ CONTENT_FORMAT_WIKITEXT ] );
	}

	protected function getContentClass() {
		return 'WikitextContent';
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

		$class = $this->getContentClass();
		return new $class( $redirectText );
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

	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields = [];

		$this->addSearchField( $fields, $engine, 'category', SearchIndexField::INDEX_TYPE_TEXT );
		$fields['category']->setFlag( SearchIndexField::FLAG_CASEFOLD );

		$this->addSearchField( $fields, $engine, 'external_link',
			SearchIndexField::INDEX_TYPE_KEYWORD );
		$this->addSearchField( $fields, $engine, 'heading', SearchIndexField::INDEX_TYPE_TEXT );
		$fields['heading']->setFlag( SearchIndexField::FLAG_SCORING );
		$this->addSearchField( $fields, $engine, 'auxiliary_text',
			SearchIndexField::INDEX_TYPE_TEXT );
		$this->addSearchField( $fields, $engine, 'opening_text',
			SearchIndexField::INDEX_TYPE_TEXT );
		$fields['opening_text']->setFlag( SearchIndexField::FLAG_SCORING );
		$this->addSearchField( $fields, $engine, 'outgoing_link',
			SearchIndexField::INDEX_TYPE_KEYWORD );
		$this->addSearchField( $fields, $engine, 'template', SearchIndexField::INDEX_TYPE_KEYWORD );
		$fields['template']->setFlag( SearchIndexField::FLAG_CASEFOLD );

		// FIXME: this really belongs in separate file handler but files
		// do not have separate handler. Sadness.
		$this->addSearchField( $fields, $engine, 'file_text', SearchIndexField::INDEX_TYPE_TEXT );

		return $fields;
	}

}
