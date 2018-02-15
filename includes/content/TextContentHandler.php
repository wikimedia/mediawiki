<?php
/**
 * Base content handler class for flat text contents.
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
 * Base content handler implementation for flat text contents.
 *
 * @ingroup Content
 */
class TextContentHandler extends ContentHandler {

	public function __construct( $modelId = CONTENT_MODEL_TEXT, $formats = [ CONTENT_FORMAT_TEXT ] ) {
		parent::__construct( $modelId, $formats );
	}

	/**
	 * Returns the content's text as-is.
	 *
	 * @param Content $content
	 * @param string $format The serialization format to check
	 *
	 * @return mixed
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		return $content->getNativeData();
	}

	/**
	 * Attempts to merge differences between three versions. Returns a new
	 * Content object for a clean merge and false for failure or a conflict.
	 *
	 * All three Content objects passed as parameters must have the same
	 * content model.
	 *
	 * This text-based implementation uses wfMerge().
	 *
	 * @param Content $oldContent The page's previous content.
	 * @param Content $myContent One of the page's conflicting contents.
	 * @param Content $yourContent One of the page's conflicting contents.
	 *
	 * @return Content|bool
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		$this->checkModelID( $oldContent->getModel() );
		$this->checkModelID( $myContent->getModel() );
		$this->checkModelID( $yourContent->getModel() );

		$format = $this->getDefaultFormat();

		$old = $this->serializeContent( $oldContent, $format );
		$mine = $this->serializeContent( $myContent, $format );
		$yours = $this->serializeContent( $yourContent, $format );

		$ok = wfMerge( $old, $mine, $yours, $result );

		if ( !$ok ) {
			return false;
		}

		if ( !$result ) {
			return $this->makeEmptyContent();
		}

		$mergedContent = $this->unserializeContent( $result, $format );

		return $mergedContent;
	}

	/**
	 * Returns the name of the associated Content class, to
	 * be used when creating new objects. Override expected
	 * by subclasses.
	 *
	 * @since 1.24
	 *
	 * @return string
	 */
	protected function getContentClass() {
		return TextContent::class;
	}

	/**
	 * Unserializes a Content object of the type supported by this ContentHandler.
	 *
	 * @since 1.21
	 *
	 * @param string $text Serialized form of the content
	 * @param string $format The format used for serialization
	 *
	 * @return Content The TextContent object wrapping $text
	 */
	public function unserializeContent( $text, $format = null ) {
		$this->checkFormat( $format );

		$class = $this->getContentClass();
		return new $class( $text );
	}

	/**
	 * Creates an empty TextContent object.
	 *
	 * @since 1.21
	 *
	 * @return Content A new TextContent object with empty text.
	 */
	public function makeEmptyContent() {
		$class = $this->getContentClass();
		return new $class( '' );
	}

	/**
	 * @see ContentHandler::supportsDirectEditing
	 *
	 * @return bool Default is true for TextContent and derivatives.
	 */
	public function supportsDirectEditing() {
		return true;
	}

	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields = parent::getFieldsForSearchIndex( $engine );
		$fields['language'] =
			$engine->makeSearchFieldMapping( 'language', SearchIndexField::INDEX_TYPE_KEYWORD );

		return $fields;
	}

	public function getDataForSearchIndex(
		WikiPage $page,
		ParserOutput $output,
		SearchEngine $engine
	) {
		$fields = parent::getDataForSearchIndex( $page, $output, $engine );
		$fields['language'] =
			$this->getPageLanguage( $page->getTitle(), $page->getContent() )->getCode();
		return $fields;
	}

}
