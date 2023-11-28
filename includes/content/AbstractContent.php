<?php
/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to Wiki pages.
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

use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\MagicWord;
use MediaWiki\Title\Title;

/**
 * Base implementation for content objects.
 *
 * @stable to extend
 *
 * @ingroup Content
 */
abstract class AbstractContent implements Content {
	/**
	 * Name of the content model this Content object represents.
	 * Use with CONTENT_MODEL_XXX constants
	 *
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $model_id;

	/**
	 * @stable to call
	 *
	 * @param string|null $modelId
	 *
	 * @since 1.21
	 */
	public function __construct( $modelId = null ) {
		$this->model_id = $modelId;
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getModel
	 * @return string
	 */
	public function getModel() {
		return $this->model_id;
	}

	/**
	 * @since 1.21
	 *
	 * @param string $modelId The model to check
	 *
	 * @throws MWException If the provided ID is not the ID of the content model supported by this
	 * Content object.
	 */
	protected function checkModelID( $modelId ) {
		if ( $modelId !== $this->model_id ) {
			throw new MWException(
				"Bad content model: " .
				"expected {$this->model_id} " .
				"but got $modelId."
			);
		}
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getContentHandler
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		return $this->getContentHandlerFactory()->getContentHandler( $this->getModel() );
	}

	/**
	 * @return IContentHandlerFactory
	 */
	protected function getContentHandlerFactory(): IContentHandlerFactory {
		return MediaWikiServices::getInstance()->getContentHandlerFactory();
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getDefaultFormat
	 * @return string
	 */
	public function getDefaultFormat() {
		return $this->getContentHandler()->getDefaultFormat();
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getSupportedFormats
	 * @return string[]
	 */
	public function getSupportedFormats() {
		return $this->getContentHandler()->getSupportedFormats();
	}

	/**
	 * @since 1.21
	 *
	 * @param string $format
	 *
	 * @return bool
	 *
	 * @see Content::isSupportedFormat
	 */
	public function isSupportedFormat( $format ) {
		if ( !$format ) {
			return true; // this means "use the default"
		}

		return $this->getContentHandler()->isSupportedFormat( $format );
	}

	/**
	 * @since 1.21
	 *
	 * @param string $format The serialization format to check.
	 *
	 * @throws MWException If the format is not supported by this content handler.
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
	 * @since 1.21
	 *
	 * @param string|null $format
	 *
	 * @return string
	 *
	 * @see Content::serialize
	 */
	public function serialize( $format = null ) {
		return $this->getContentHandler()->serializeContent( $this, $format );
	}

	/**
	 * Returns native representation of the data. Interpretation depends on
	 * the data model used, as given by getDataModel().
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @deprecated since 1.33. Use getText() for TextContent instances.
	 *             For other content models, use specialized getters.
	 *             Emitting deprecation warnings since 1.41.
	 *
	 * @return mixed The native representation of the content. Could be a
	 *    string, a nested array structure, an object, a binary blob...
	 *    anything, really.
	 * @throws LogicException
	 *
	 * @note Caller must be aware of content model!
	 */
	public function getNativeData() {
		wfDeprecated( __METHOD__, '1.33' );
		throw new LogicException( __METHOD__ . ': not implemented' );
	}

	/**
	 * @stable to override
	 * @since 1.21
	 *
	 * @return bool
	 *
	 * @see Content::isEmpty
	 */
	public function isEmpty() {
		return $this->getSize() === 0;
	}

	/**
	 * Subclasses may override this to implement (light weight) validation.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @return bool Always true.
	 *
	 * @see Content::isValid
	 */
	public function isValid() {
		return true;
	}

	/**
	 * Decides whether two Content objects are equal.
	 * Two Content objects MUST not be considered equal if they do not share the same content model.
	 * Two Content objects that are equal SHOULD have the same serialization.
	 *
	 * This default implementation relies on equalsInternal() to determine whether the
	 * Content objects are logically equivalent. Subclasses that need to implement a custom
	 * equality check should consider overriding equalsInternal(). Subclasses that override
	 * equals() itself MUST make sure that the implementation returns false for $that === null,
	 * and true for $that === this. It MUST also return false if $that does not have the same
	 * content model.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Content|null $that
	 *
	 * @return bool
	 *
	 * @see Content::equals
	 */
	public function equals( Content $that = null ) {
		if ( $that === null ) {
			return false;
		}

		if ( $that === $this ) {
			return true;
		}

		if ( $that->getModel() !== $this->getModel() ) {
			return false;
		}

		// For type safety. Needed for odd cases like MessageContent using CONTENT_MODEL_WIKITEXT
		if ( get_class( $that ) !== get_class( $this ) ) {
			return false;
		}

		return $this->equalsInternal( $that );
	}

	/**
	 * Checks whether $that is logically equal to this Content object.
	 *
	 * This method can be overwritten by subclasses that need to implement custom
	 * equality checks.
	 *
	 * This default implementation checks whether the serializations
	 * of $this and $that are the same: $this->serialize() === $that->serialize()
	 *
	 * Implementors can assume that $that is an instance of the same class
	 * as the present Content object, as long as equalsInternal() is only called
	 * by the standard implementation of equals().
	 *
	 * @note Do not call this method directly, call equals() instead.
	 *
	 * @stable to override
	 *
	 * @param Content $that
	 * @return bool
	 */
	protected function equalsInternal( Content $that ) {
		return $this->serialize() === $that->serialize();
	}

	/**
	 * Subclasses that implement redirects should override this.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @return Title|null
	 *
	 * @see Content::getRedirectTarget
	 */
	public function getRedirectTarget() {
		return null;
	}

	/**
	 * @since 1.21
	 *
	 * @return bool
	 *
	 * @see Content::isRedirect
	 */
	public function isRedirect() {
		return $this->getRedirectTarget() !== null;
	}

	/**
	 * This default implementation always returns $this.
	 * Subclasses that implement redirects should override this.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param Title $target
	 *
	 * @return Content $this
	 *
	 * @see Content::updateRedirect
	 */
	public function updateRedirect( Title $target ) {
		return $this;
	}

	/**
	 * @stable to override
	 * @since 1.21
	 *
	 * @param string|int $sectionId
	 * @return null
	 *
	 * @see Content::getSection
	 */
	public function getSection( $sectionId ) {
		return null;
	}

	/**
	 * @stable to override
	 * @since 1.21
	 *
	 * @param string|int|null|false $sectionId
	 * @param Content $with
	 * @param string $sectionTitle
	 * @return null
	 *
	 * @see Content::replaceSection
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		return null;
	}

	/**
	 * @stable to override
	 * @since 1.21
	 *
	 * @param string $header
	 * @return Content $this
	 *
	 * @see Content::addSectionHeader
	 */
	public function addSectionHeader( $header ) {
		return $this;
	}

	/**
	 * This default implementation always returns false. Subclasses may override
	 * this to supply matching logic.
	 *
	 * @stable to override
	 * @since 1.21
	 *
	 * @param MagicWord $word
	 *
	 * @return bool Always false.
	 *
	 * @see Content::matchMagicWord
	 */
	public function matchMagicWord( MagicWord $word ) {
		return false;
	}

	/**
	 * This base implementation calls the hook ConvertContent to enable custom conversions.
	 * Subclasses may override this to implement conversion for "their" content model.
	 *
	 * @stable to override
	 *
	 * @param string $toModel
	 * @param string $lossy
	 *
	 * @return Content|false
	 *
	 * @see Content::convert()
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

}
