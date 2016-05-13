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

	/**
	 * Get fields definition for search index
	 * @return SearchIndexFieldDefinition[] List of fields this content handler can provide.
	 */
	public function getFieldsForSearchIndex() {
		$fields = [];

		$this->addSearchField( $fields, 'category', SearchIndexFieldDefinition::INDEX_TYPE_TEXT );
		$fields['category']->setFlag( SearchIndexFieldDefinition::FLAG_CASEFOLD );

		$this->addSearchField( $fields, 'external_link',
			SearchIndexFieldDefinition::INDEX_TYPE_KEYWORD );
		$this->addSearchField( $fields, 'heading', SearchIndexFieldDefinition::INDEX_TYPE_TEXT );
		// FIXME: 'heading' => MappingConfigBuilder::SPEED_UP_HIGHLIGHTING, // -> no norms
		$this->addSearchField( $fields, 'incoming_links',
			SearchIndexFieldDefinition::INDEX_TYPE_INTEGER );
		$this->addSearchField( $fields, 'auxiliary_text',
			SearchIndexFieldDefinition::INDEX_TYPE_TEXT );
		$this->addSearchField( $fields, 'opening_text',
			SearchIndexFieldDefinition::INDEX_TYPE_TEXT );
		$fields['opening_text']->setFlag( SearchIndexFieldDefinition::FLAG_SECONDARY );
		$this->addSearchField( $fields, 'outgoing_link',
			SearchIndexFieldDefinition::INDEX_TYPE_KEYWORD );
		$this->addSearchField( $fields, 'template',
			SearchIndexFieldDefinition::INDEX_TYPE_KEYWORD );
		$fields['template']->setFlag( SearchIndexFieldDefinition::FLAG_CASEFOLD );
		$this->addSearchField( $fields, 'wikibase_item',
			SearchIndexFieldDefinition::INDEX_TYPE_KEYWORD );

		// FIXME: this really belongs in separate file handler but files
		// do not have separate handler. Sadness.
		$this->addSearchField( $fields, 'file_text', SearchIndexFieldDefinition::INDEX_TYPE_TEXT );
		$this->addSearchField( $fields, 'local_sites_with_dupe',
			SearchIndexFieldDefinition::INDEX_TYPE_KEYWORD );
		$fields['local_sites_with_dupe']->setFlag( SearchIndexFieldDefinition::FLAG_CASEFOLD );

		return $fields;
	}

}
