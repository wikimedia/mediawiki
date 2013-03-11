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

/**
 * Base implementation for content objects.
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
	 * @var string $model_id
	 */
	protected $model_id;

	/**
	 * @param string|null $modelId
	 *
	 * @since 1.21
	 */
	public function __construct( $modelId = null ) {
		$this->model_id = $modelId;
	}

	/**
	 * @see Content::getModel
	 *
	 * @since 1.21
	 */
	public function getModel() {
		return $this->model_id;
	}

	/**
	 * Throws an MWException if $model_id is not the id of the content model
	 * supported by this Content object.
	 *
	 * @since 1.21
	 *
	 * @param string $modelId The model to check
	 *
	 * @throws MWException
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
	 * @see Content::getContentHandler
	 *
	 * @since 1.21
	 */
	public function getContentHandler() {
		return ContentHandler::getForContent( $this );
	}

	/**
	 * @see Content::getDefaultFormat
	 *
	 * @since 1.21
	 */
	public function getDefaultFormat() {
		return $this->getContentHandler()->getDefaultFormat();
	}

	/**
	 * @see Content::getSupportedFormats
	 *
	 * @since 1.21
	 */
	public function getSupportedFormats() {
		return $this->getContentHandler()->getSupportedFormats();
	}

	/**
	 * @see Content::isSupportedFormat
	 *
	 * @param string $format
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function isSupportedFormat( $format ) {
		if ( !$format ) {
			return true; // this means "use the default"
		}

		return $this->getContentHandler()->isSupportedFormat( $format );
	}

	/**
	 * Throws an MWException if $this->isSupportedFormat( $format ) does not
	 * return true.
	 *
	 * @since 1.21
	 *
	 * @param string $format
	 * @throws MWException
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
	 * @see Content::serialize
	 *
	 * @param string|null $format
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function serialize( $format = null ) {
		return $this->getContentHandler()->serializeContent( $this, $format );
	}

	/**
	 * @see Content::isEmpty
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->getSize() === 0;
	}

	/**
	 * @see Content::isValid
	 *
	 * @since 1.21
	 *
	 * @return boolean
	 */
	public function isValid() {
		return true;
	}

	/**
	 * @see Content::equals
	 *
	 * @since 1.21
	 *
	 * @param Content|null $that
	 *
	 * @return boolean
	 */
	public function equals( Content $that = null ) {
		if ( is_null( $that ) ) {
			return false;
		}

		if ( $that === $this ) {
			return true;
		}

		if ( $that->getModel() !== $this->getModel() ) {
			return false;
		}

		return $this->getNativeData() === $that->getNativeData();
	}

	/**
	 * Returns a list of DataUpdate objects for recording information about this
	 * Content in some secondary data store.
	 *
	 * This default implementation calls
	 * $this->getParserOutput( $content, $title, null, null, false ),
	 * and then calls getSecondaryDataUpdates( $title, $recursive ) on the
	 * resulting ParserOutput object.
	 *
	 * Subclasses may override this to determine the secondary data updates more
	 * efficiently, preferably without the need to generate a parser output object.
	 *
	 * @see Content::getSecondaryDataUpdates()
	 *
	 * @param $title Title The context for determining the necessary updates
	 * @param $old Content|null An optional Content object representing the
	 *    previous content, i.e. the content being replaced by this Content
	 *    object.
	 * @param $recursive boolean Whether to include recursive updates (default:
	 *    false).
	 * @param $parserOutput ParserOutput|null Optional ParserOutput object.
	 *    Provide if you have one handy, to avoid re-parsing of the content.
	 *
	 * @return Array. A list of DataUpdate objects for putting information
	 *    about this content object somewhere.
	 *
	 * @since 1.21
	 */
	public function getSecondaryDataUpdates( Title $title,
		Content $old = null,
		$recursive = true, ParserOutput $parserOutput = null
	) {
		if ( $parserOutput === null ) {
			$parserOutput = $this->getParserOutput( $title, null, null, false );
		}

		return $parserOutput->getSecondaryDataUpdates( $title, $recursive );
	}

	/**
	 * @see Content::getRedirectChain
	 *
	 * @since 1.21
	 */
	public function getRedirectChain() {
		global $wgMaxRedirects;
		$title = $this->getRedirectTarget();
		if ( is_null( $title ) ) {
			return null;
		}
		// recursive check to follow double redirects
		$recurse = $wgMaxRedirects;
		$titles = array( $title );
		while ( --$recurse > 0 ) {
			if ( $title->isRedirect() ) {
				$page = WikiPage::factory( $title );
				$newtitle = $page->getRedirectTarget();
			} else {
				break;
			}
			// Redirects to some special pages are not permitted
			if ( $newtitle instanceOf Title && $newtitle->isValidRedirectTarget() ) {
				// The new title passes the checks, so make that our current
				// title so that further recursion can be checked
				$title = $newtitle;
				$titles[] = $newtitle;
			} else {
				break;
			}
		}
		return $titles;
	}

	/**
	 * @see Content::getRedirectTarget
	 *
	 * @since 1.21
	 */
	public function getRedirectTarget() {
		return null;
	}

	/**
	 * @see Content::getUltimateRedirectTarget
	 * @note: migrated here from Title::newFromRedirectRecurse
	 *
	 * @since 1.21
	 */
	public function getUltimateRedirectTarget() {
		$titles = $this->getRedirectChain();
		return $titles ? array_pop( $titles ) : null;
	}

	/**
	 * @see Content::isRedirect
	 *
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function isRedirect() {
		return $this->getRedirectTarget() !== null;
	}

	/**
	 * @see Content::updateRedirect
	 *
	 * This default implementation always returns $this.
	 *
	 * @param Title $target
	 *
	 * @since 1.21
	 *
	 * @return Content $this
	 */
	public function updateRedirect( Title $target ) {
		return $this;
	}

	/**
	 * @see Content::getSection
	 *
	 * @since 1.21
	 */
	public function getSection( $sectionId ) {
		return null;
	}

	/**
	 * @see Content::replaceSection
	 *
	 * @since 1.21
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
		return null;
	}

	/**
	 * @see Content::preSaveTransform
	 *
	 * @since 1.21
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return $this;
	}

	/**
	 * @see Content::addSectionHeader
	 *
	 * @since 1.21
	 */
	public function addSectionHeader( $header ) {
		return $this;
	}

	/**
	 * @see Content::preloadTransform
	 *
	 * @since 1.21
	 */
	public function preloadTransform( Title $title, ParserOptions $popts ) {
		return $this;
	}

	/**
	 * @see Content::prepareSave
	 *
	 * @since 1.21
	 */
	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user ) {
		if ( $this->isValid() ) {
			return Status::newGood();
		} else {
			return Status::newFatal( "invalid-content-data" );
		}
	}

	/**
	 * @see Content::getDeletionUpdates
	 *
	 * @since 1.21
	 *
	 * @param $page WikiPage the deleted page
	 * @param $parserOutput null|ParserOutput optional parser output object
	 *    for efficient access to meta-information about the content object.
	 *    Provide if you have one handy.
	 *
	 * @return array A list of DataUpdate instances that will clean up the
	 *    database after deletion.
	 */
	public function getDeletionUpdates( WikiPage $page,
		ParserOutput $parserOutput = null )
	{
		return array(
			new LinksDeletionUpdate( $page ),
		);
	}

	/**
	 * This default implementation always returns false. Subclasses may override this to supply matching logic.
	 *
	 * @see Content::matchMagicWord
	 *
	 * @since 1.21
	 *
	 * @param MagicWord $word
	 *
	 * @return bool
	 */
	public function matchMagicWord( MagicWord $word ) {
		return false;
	}

	/**
	 * @see Content::convert()
	 *
	 * This base implementation calls the hook ConvertContent to enable custom conversions.
	 * Subclasses may override this to implement conversion for "their" content model.
	 *
	 * @param string  $toModel the desired content model, use the CONTENT_MODEL_XXX flags.
	 * @param string  $lossy flag, set to "lossy" to allow lossy conversion. If lossy conversion is
	 * not allowed, full round-trip conversion is expected to work without losing information.
	 *
	 * @return Content|bool A content object with the content model $toModel, or false if
	 * that conversion is not supported.
	 */
	public function convert( $toModel, $lossy = '' ) {
		if ( $this->getModel() === $toModel ) {
			//nothing to do, shorten out.
			return $this;
		}

		$lossy = ( $lossy === 'lossy' ); // string flag, convert to boolean for convenience
		$result = false;

		wfRunHooks( 'ConvertContent', array( $this, $toModel, $lossy, &$result ) );
		return $result;
	}
}
