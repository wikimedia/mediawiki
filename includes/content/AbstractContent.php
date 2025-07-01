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
 * @author Daniel Kinzler
 */

namespace MediaWiki\Content;

use LogicException;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Title\Title;
use Psr\Container\ContainerInterface;
use Wikimedia\JsonCodec\JsonClassCodec;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecInterface;

/**
 * Base class for all Content objects. Refer to Content for more information.
 *
 * @stable to extend
 * @since 1.21
 * @ingroup Content
 */
abstract class AbstractContent implements Content, JsonCodecable {
	/**
	 * @var string
	 * @since 1.21
	 */
	protected $model_id;

	/**
	 * @stable to call
	 * @since 1.21
	 * @param string|null $modelId One of the CONTENT_MODEL_XXX constants.
	 */
	public function __construct( $modelId = null ) {
		$this->model_id = $modelId;
	}

	/**
	 * @see Content::getModel
	 * @since 1.21
	 * @return string
	 */
	public function getModel() {
		return $this->model_id;
	}

	/**
	 * Helper for subclasses
	 *
	 * @since 1.21
	 * @param string $modelId The model to check
	 * @throws MWException If the provided model ID differs from this Content object
	 */
	protected function checkModelID( $modelId ) {
		if ( $modelId !== $this->model_id ) {
			throw new MWException(
				"Bad content model: expected {$this->model_id} but got $modelId."
			);
		}
	}

	/**
	 * @see Content::getContentHandler
	 * @since 1.21
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		return $this->getContentHandlerFactory()->getContentHandler( $this->getModel() );
	}

	protected function getContentHandlerFactory(): IContentHandlerFactory {
		return MediaWikiServices::getInstance()->getContentHandlerFactory();
	}

	/**
	 * @see Content::getDefaultFormat
	 * @since 1.21
	 * @return string
	 */
	public function getDefaultFormat() {
		return $this->getContentHandler()->getDefaultFormat();
	}

	/**
	 * @see Content::getSupportedFormats
	 * @since 1.21
	 * @return string[]
	 */
	public function getSupportedFormats() {
		return $this->getContentHandler()->getSupportedFormats();
	}

	/**
	 * @see Content::isSupportedFormat
	 * @since 1.21
	 * @param string $format
	 * @return bool
	 */
	public function isSupportedFormat( $format ) {
		if ( !$format ) {
			return true; // this means "use the default"
		}

		return $this->getContentHandler()->isSupportedFormat( $format );
	}

	/**
	 * Helper for subclasses.
	 *
	 * @since 1.21
	 * @param string $format The serialization format to check.
	 * @throws MWException If the format is not supported by this Content object
	 */
	protected function checkFormat( $format ) {
		if ( !$this->isSupportedFormat( $format ) ) {
			throw new MWException(
				"Format $format is not supported for content model " .
				$this->getModel()
			);
		}
	}

	/**
	 * @stable to override
	 * @see Content::serialize
	 * @since 1.21
	 * @param string|null $format
	 * @return string
	 */
	public function serialize( $format = null ) {
		return $this->getContentHandler()->serializeContent( $this, $format );
	}

	/**
	 * @see Content::getNativeData
	 * @stable to override
	 * @deprecated since 1.33 Use TextContent::getText() instead.
	 *  For other content models, use specialized getters.
	 * @since 1.21
	 * @return mixed
	 */
	public function getNativeData() {
		wfDeprecated( __METHOD__, '1.33' );
		throw new LogicException( __METHOD__ . ': not implemented' );
	}

	/**
	 * @see Content::isEmpty
	 * @stable to override
	 * @since 1.21
	 * @return bool
	 */
	public function isEmpty() {
		return $this->getSize() === 0;
	}

	/**
	 * @see Content::isValid
	 * @stable to override
	 * @since 1.21
	 * @return bool
	 */
	public function isValid() {
		return true;
	}

	/**
	 * @see Content::equals
	 * @stable to override
	 * @since 1.21
	 * @param Content|null $that
	 * @return bool
	 */
	public function equals( ?Content $that = null ) {
		if ( $that === null ) {
			return false;
		}

		if ( $that === $this ) {
			return true;
		}

		if ( $that->getModel() !== $this->getModel() ) {
			return false;
		}

		// For type safety. Needed for odd cases like non-TextContents using CONTENT_MODEL_WIKITEXT
		if ( get_class( $that ) !== get_class( $this ) ) {
			return false;
		}

		return $this->equalsInternal( $that );
	}

	/**
	 * Helper for AbstractContent::equals.
	 *
	 * @note Do not call this method directly, call Content::equals() instead.
	 *
	 * This method can be overwritten by subclasses that only need to implement custom
	 * equality checks, with the rest of the Content::equals contract taken care of by
	 * AbstractContent::equals.
	 *
	 * This default implementation compares Content::serialize of each object.
	 *
	 * If you override this method, you can safely assume that $that is an instance of the same
	 * class as the current Content object. This is ensured by AbstractContent::equals.
	 *
	 * @see Content::equals
	 * @stable to override
	 * @param Content $that
	 * @return bool
	 */
	protected function equalsInternal( Content $that ) {
		return $this->serialize() === $that->serialize();
	}

	/**
	 * Subclasses that implement redirects should override this.
	 *
	 * @see Content::getRedirectTarget
	 * @stable to override
	 * @since 1.21
	 * @return Title|null
	 */
	public function getRedirectTarget() {
		return null;
	}

	/**
	 * @see Content::isRedirect
	 * @since 1.21
	 * @return bool
	 */
	public function isRedirect() {
		return $this->getRedirectTarget() !== null;
	}

	/**
	 * This default implementation always returns $this.
	 * Subclasses that implement redirects should override this.
	 *
	 * @stable to override
	 * @see Content::updateRedirect
	 * @since 1.21
	 * @param Title $target
	 * @return Content $this
	 *
	 */
	public function updateRedirect( Title $target ) {
		return $this;
	}

	/**
	 * @stable to override
	 * @see Content::getSection
	 * @since 1.21
	 * @param string|int $sectionId
	 * @return null
	 */
	public function getSection( $sectionId ) {
		return null;
	}

	/**
	 * @stable to override
	 * @see Content::replaceSection
	 * @since 1.21
	 * @param string|int|null|false $sectionId
	 * @param Content $with
	 * @param string $sectionTitle
	 * @return null
	 *
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		return null;
	}

	/**
	 * @stable to override
	 * @see Content::addSectionHeader
	 * @since 1.21
	 * @param string $header
	 * @return Content $this
	 */
	public function addSectionHeader( $header ) {
		return $this;
	}

	/**
	 * This default implementation always returns false. Subclasses may override
	 * this to supply matching logic.
	 *
	 * @stable to override
	 * @see Content::matchMagicWord
	 * @since 1.21
	 * @param MagicWord $word
	 * @return bool
	 */
	public function matchMagicWord( MagicWord $word ) {
		return false;
	}

	/**
	 * This base implementation calls the hook ConvertContent to enable custom conversions.
	 * Subclasses may override this to implement conversion for "their" content model.
	 *
	 * @stable to override
	 * @see Content::convert()
	 * @param string $toModel
	 * @param string $lossy
	 * @return Content|false
	 */
	public function convert( $toModel, $lossy = '' ) {
		if ( $this->getModel() === $toModel ) {
			// nothing to do, shorten out.
			return $this;
		}

		$lossy = ( $lossy === 'lossy' ); // string flag, convert to boolean for convenience
		$result = false;

		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onConvertContent( $this, $toModel, $lossy, $result );

		return $result;
	}

	public static function jsonClassCodec(
		JsonCodecInterface $codec,
		ContainerInterface $serviceContainer
	): JsonClassCodec {
		return $serviceContainer->get( 'ContentJsonCodec' );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( AbstractContent::class, 'AbstractContent' );
