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

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Content\ValidationParams;

/**
 * Content handler for JSON text.
 *
 * Useful for maintaining JSON that can be viewed and edited directly by users.
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 *
 * @since 1.24
 * @stable to extend
 * @ingroup Content
 */
class JsonContentHandler extends CodeContentHandler {

	/**
	 * @param string $modelId
	 * @stable to call
	 */
	public function __construct( $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_JSON ] );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return JsonContent::class;
	}

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
		$shouldCallDeprecatedMethod = $this->shouldCallDeprecatedContentTransformMethod(
			$content,
			$pstParams
		);

		if ( $shouldCallDeprecatedMethod ) {
			return $this->callDeprecatedContentPST(
				$content,
				$pstParams
			);
		}

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
		if ( $cpoParams->getGenerateHtml() && $content->isValid() ) {
			$parserOutput->setText( $content->rootValueTable( $content->getData()->getValue() ) );
			$parserOutput->addModuleStyles( [ 'mediawiki.content.json' ] );
		} else {
			$parserOutput->setText( null );
		}
	}
}
