<?php
/**
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
 * @file
 */

namespace MediaWiki\Content;

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use ReflectionMethod;
use SearchEngine;
use SearchIndexField;

/**
 * Base content handler implementation for flat text contents.
 *
 * @since 1.21
 * @ingroup Content
 */
class TextContentHandler extends ContentHandler {

	/** @inheritDoc */
	public function __construct( $modelId = CONTENT_MODEL_TEXT, $formats = [ CONTENT_FORMAT_TEXT ] ) {
		parent::__construct( $modelId, $formats );
	}

	/**
	 * Returns the content's text as-is.
	 *
	 * @param Content $content
	 * @param string|null $format The serialization format to check
	 *
	 * @return mixed
	 */
	public function serializeContent( Content $content, $format = null ) {
		$this->checkFormat( $format );

		// @phan-suppress-next-line PhanUndeclaredMethod
		return $content->getText();
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
	 * @return Content|false
	 */
	public function merge3( Content $oldContent, Content $myContent, Content $yourContent ) {
		// Nothing to do when the unsaved edit is already identical to the latest revision
		if ( $myContent->equals( $yourContent ) ) {
			return $yourContent;
		}
		// Impossible to have a conflict when the user just edited the latest revision. This can
		// happen e.g. when $wgDiff3 is badly configured.
		if ( $oldContent->equals( $yourContent ) ) {
			return $myContent;
		}

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
	 * @return class-string<TextContent>
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
	 * @param string|null $format The format used for serialization
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
	 * @return bool Should return true for TextContent and derivatives.
	 */
	public function supportsDirectEditing() {
		return true;
	}

	/** @inheritDoc */
	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields = parent::getFieldsForSearchIndex( $engine );
		$fields['language'] =
			$engine->makeSearchFieldMapping( 'language', SearchIndexField::INDEX_TYPE_KEYWORD );

		return $fields;
	}

	/** @inheritDoc */
	public function getDataForSearchIndex(
		WikiPage $page,
		ParserOutput $output,
		SearchEngine $engine,
		?RevisionRecord $revision = null
	) {
		$fields = parent::getDataForSearchIndex( $page, $output, $engine, $revision );
		$fields['language'] =
			$this->getPageLanguage( $page->getTitle(), $page->getContent() )->getCode();
		return $fields;
	}

	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		'@phan-var TextContent $content';

		$text = $content->getText();

		$pst = TextContent::normalizeLineEndings( $text );

		$contentClass = $this->getContentClass();
		return ( $text === $pst ) ? $content : new $contentClass( $pst, $content->getModel() );
	}

	/**
	 * Fills the provided ParserOutput object with information derived from the content.
	 * Unless $generateHtml was false, this includes an HTML representation of the content
	 * provided by getHtml().
	 *
	 * For content models listed in $wgTextModelsToParse, this method will call the MediaWiki
	 * wikitext parser on the text to extract any (wikitext) links, magic words, etc.,
	 * but note that the Table of Contents will *not* be generated
	 * (feature added by T307691, but should be refactored: T313455).
	 *
	 * Subclasses may override this to provide custom content processing.
	 * For custom HTML generation alone, it is sufficient to override getHtml().
	 *
	 * @stable to override
	 *
	 * @since 1.38
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		$textModelsToParse = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::TextModelsToParse );
		'@phan-var TextContent $content';
		if ( in_array( $content->getModel(), $textModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = MediaWikiServices::getInstance()->getParserFactory()->getInstance()
				->parse(
					$content->getText(),
					$cpoParams->getPage(),
					$cpoParams->getParserOptions(),
					true,
					true,
					$cpoParams->getRevId()
				);
		}

		if ( $cpoParams->getGenerateHtml() ) {
			// Temporary changes as getHtml() is deprecated, we are working on removing usage of it.
			if ( method_exists( $content, 'getHtml' ) ) {
				$method = new ReflectionMethod( $content, 'getHtml' );
				$method->setAccessible( true );
				$html = $method->invoke( $content );
				$html = "<pre>$html</pre>";
			} else {
				// Return an HTML representation of the content
				$html = htmlspecialchars( $content->getText(), ENT_COMPAT );
				$html = "<pre>$html</pre>";
			}
		} else {
			$html = null;
		}

		$output->clearWrapperDivClass();
		$output->setRawText( $html );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( TextContentHandler::class, 'TextContentHandler' );
