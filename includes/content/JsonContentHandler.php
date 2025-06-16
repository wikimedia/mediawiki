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
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Title\TitleFactory;
use StatusValue;

/**
 * Content handler for JSON text.
 *
 * Useful for maintaining JSON that can be viewed and edited directly by users.
 *
 * @stable to extend
 * @since 1.24
 * @ingroup Content
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */
class JsonContentHandler extends CodeContentHandler {

	private ParsoidParserFactory $parsoidParserFactory;
	private TitleFactory $titleFactory;

	/**
	 * @stable to call
	 */
	public function __construct(
		string $modelId = CONTENT_MODEL_JSON,
		?ParsoidParserFactory $parsoidParserFactory = null,
		?TitleFactory $titleFactory = null
	) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_JSON ] );
		$this->parsoidParserFactory = $parsoidParserFactory ??
			MediaWikiServices::getInstance()->getParsoidParserFactory();
		$this->titleFactory = $titleFactory ??
			MediaWikiServices::getInstance()->getTitleFactory();
	}

	/**
	 * @return class-string<JsonContent>
	 */
	protected function getContentClass() {
		return JsonContent::class;
	}

	/** @inheritDoc */
	public function makeEmptyContent() {
		$class = $this->getContentClass();
		return new $class( '{}' );
	}

	/**
	 * Enables EditPage's preload feature on .json pages as well as for extensions like MassMessage
	 * that subclass {@see JsonContentHandler}.
	 *
	 * @return true
	 */
	public function supportsPreloadContent(): bool {
		return true;
	}

	/**
	 * @param Content $content
	 * @param ValidationParams $validationParams
	 * @return StatusValue
	 */
	public function validateSave( Content $content, ValidationParams $validationParams ) {
		$status = parent::validateSave( $content, $validationParams );
		'@phan-var JsonContent $content';
		if ( !$status->isOK() ) {
			if ( !$content->getData()->isGood() ) {
				return StatusValue::newFatal( $content->getData()->getMessage( 'invalid-json-data' ) );
			} else {
				return $status;
			}
		}
		$this->getHookRunner()->onJsonValidateSave( $content, $validationParams->getPageIdentity(), $status );
		return $status;
	}

	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		'@phan-var JsonContent $content';

		// FIXME: WikiPage::doUserEditContent invokes PST before validation. As such, native
		// data may be invalid (though PST result is discarded later in that case).
		if ( !$content->isValid() ) {
			return $content;
		}

		$contentClass = $this->getContentClass();
		return new $contentClass( JsonContent::normalizeLineEndings( $content->beautifyJSON() ) );
	}

	/**
	 * Set the HTML and add the appropriate styles.
	 *
	 * @since 1.38
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$parserOutput The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$parserOutput
	) {
		'@phan-var JsonContent $content';
		// FIXME: WikiPage::doUserEditContent generates parser output before validation.
		// As such, native data may be invalid (though output is discarded later in that case).
		if ( $cpoParams->getGenerateHtml() ) {
			if ( $content->isValid() ) {
				$parserOptions = $cpoParams->getParserOptions();
				if ( $cpoParams->getParserOptions()->getUseParsoid() ) {
					$title = $this->titleFactory->newFromPageReference( $cpoParams->getPage() );
					$parser = $this->parsoidParserFactory->create();
					$parserOutput = $parser->parse(
						// It is necessary to pass a Content rather than a
						// string in order for Parsoid to handle the
						// contentmodel correctly.
						$content, $title, $parserOptions,
						true, true, $cpoParams->getRevId()
					);
					// Register the use of the 'parsoid' option again, since
					// we have a new $parserOutput now.
					$parserOptions->getUseParsoid();
				} else {
					$parserOutput->setRawText( $content->rootValueTable( $content->getData()->getValue() ) );
				}
			} else {
				$error = wfMessage( 'invalid-json-data' )->parse();
				$parserOutput->setRawText( $error );
			}

			$parserOutput->addModuleStyles( [ 'mediawiki.content.json' ] );
		} else {
			$parserOutput->setRawText( null );
		}
	}
}
/** @deprecated class alias since 1.43 */
class_alias( JsonContentHandler::class, 'JsonContentHandler' );
