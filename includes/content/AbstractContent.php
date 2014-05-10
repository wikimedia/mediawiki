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
	 * @param string $modelId
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
	 */
	public function getContentHandler() {
		return ContentHandler::getForContent( $this );
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getDefaultFormat
	 */
	public function getDefaultFormat() {
		return $this->getContentHandler()->getDefaultFormat();
	}

	/**
	 * @since 1.21
	 *
	 * @see Content::getSupportedFormats
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
	 * @since 1.21
	 *
	 * @param string $format
	 *
	 * @return string
	 *
	 * @see Content::serialize
	 */
	public function serialize( $format = null ) {
		return $this->getContentHandler()->serializeContent( $this, $format );
	}

	/**
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
	 * @since 1.21
	 *
	 * @param Content $that
	 *
	 * @return bool
	 *
	 * @see Content::equals
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
	 * @since 1.21
	 *
	 * @param Title $title
	 * @param Content $old
	 * @param bool $recursive
	 * @param ParserOutput $parserOutput
	 *
	 * @return DataUpdate[]
	 *
	 * @see Content::getSecondaryDataUpdates()
	 */
	public function getSecondaryDataUpdates( Title $title, Content $old = null,
		$recursive = true, ParserOutput $parserOutput = null ) {
		if ( $parserOutput === null ) {
			$parserOutput = $this->getParserOutput( $title, null, null, false );
		}

		return $parserOutput->getSecondaryDataUpdates( $title, $recursive );
	}

	/**
	 * @since 1.21
	 *
	 * @return Title[]|null
	 *
	 * @see Content::getRedirectChain
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
			if ( $newtitle instanceof Title && $newtitle->isValidRedirectTarget() ) {
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
	 * Subclasses that implement redirects should override this.
	 *
	 * @since 1.21
	 *
	 * @return null
	 *
	 * @see Content::getRedirectTarget
	 */
	public function getRedirectTarget() {
		return null;
	}

	/**
	 * @note Migrated here from Title::newFromRedirectRecurse.
	 *
	 * @since 1.21
	 *
	 * @return Title|null
	 *
	 * @see Content::getUltimateRedirectTarget
	 */
	public function getUltimateRedirectTarget() {
		$titles = $this->getRedirectChain();

		return $titles ? array_pop( $titles ) : null;
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
	 * @since 1.21
	 *
	 * @return null
	 *
	 * @see Content::getSection
	 */
	public function getSection( $sectionId ) {
		return null;
	}

	/**
	 * @since 1.21
	 *
	 * @return null
	 *
	 * @see Content::replaceSection
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
		return null;
	}

	/**
	 * @since 1.21
	 *
	 * @return Content $this
	 *
	 * @see Content::preSaveTransform
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return $this;
	}

	/**
	 * @since 1.21
	 *
	 * @return Content $this
	 *
	 * @see Content::addSectionHeader
	 */
	public function addSectionHeader( $header ) {
		return $this;
	}

	/**
	 * @since 1.21
	 *
	 * @return Content $this
	 *
	 * @see Content::preloadTransform
	 */
	public function preloadTransform( Title $title, ParserOptions $popts, $params = array() ) {
		return $this;
	}

	/**
	 * @since 1.21
	 *
	 * @return Status
	 *
	 * @see Content::prepareSave
	 */
	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user ) {
		if ( $this->isValid() ) {
			return Status::newGood();
		} else {
			return Status::newFatal( "invalid-content-data" );
		}
	}

	/**
	 * @since 1.21
	 *
	 * @param WikiPage $page
	 * @param ParserOutput $parserOutput
	 *
	 * @return LinksDeletionUpdate[]
	 *
	 * @see Content::getDeletionUpdates
	 */
	public function getDeletionUpdates( WikiPage $page, ParserOutput $parserOutput = null ) {
		return array(
			new LinksDeletionUpdate( $page ),
		);
	}

	/**
	 * This default implementation always returns false. Subclasses may override
	 * this to supply matching logic.
	 *
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
	 * @see Content::convert()
	 *
	 * This base implementation handles conversion to CONTENT_MODEL_HTML by calling getHtml().
	 * Subclasses may override this to implement additional conversions.
	 *
	 * @note This function also the ConvertContent hook to allow third parties to control
	 * the conversion.
	 *
	 * @param string $toModel
	 * @param string $lossy
	 *
	 * @return Content|bool
	 */
	public function convert( $toModel, $lossy = '' ) {
		if ( $this->getModel() === $toModel ) {
			// Nothing to do, shorten out.
			// Return a clone (if this content is mutable), for consistency.
			return $this->copy();
		}

		$lossy = ( $lossy === 'lossy' ); // string flag, convert to boolean for convenience
		$result = false;

		if ( wfRunHooks( 'ConvertContent', array( $this, $toModel, $lossy, &$result ) ) ) {

			// Default to using getHtml() when converting to HTML.
			if ( $result === false && $toModel === CONTENT_MODEL_HTML ) {
				try {
					$html = $this->getHtml();
					$result = new TextContent( $html, CONTENT_MODEL_HTML );
				} catch ( MWException $ex ) {
					// Handles the case that getHtml() is not implemented.
					// That would be the case for content models that rely on meta data
					// in the ParserOutput, e.g. for loading CSS or JS modules. Such content
					// can not readily be transcluded as raw HTML.
					// XXX: Use a more specific exception?
				}
			}

			// NOTE: Do not call getTextForTransclusion() and friends here,
			// since these themselves rely on convert().
		}

		return $result;
	}

	/**
	 * @see Content::getWikitextForTransclusion
	 * @see AbstractContent::getTextForTransclusion
	 *
	 * This is implemented to return $this->getTextForTransclusion( CONTENT_MODEL_WIKITEXT ).
	 *
	 * @since 1.24
	 *
	 * @return string|bool false
	 */
	public function getWikitextForTransclusion() {
		return $this->getTextForTransclusion( CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @see Content::getTextForTransclusion
	 *
	 * This default implementation calls getContentForTransclusion() and returns
	 * the resulting Content object's native data, if that data is a string. If
	 * the conversion is not possible or the result is not string based content,
	 * this returns false.
	 *
	 * @see getWikitextViaHtml
	 * @see ParserOptions::getHtmlTransclusionMode
	 *
	 * @since 1.24
	 *
	 * @param string $modelId
	 * @param object|null $context
	 *
	 * @return string|bool The text to use for transclusion, or false if such a
	 * transclusion is not supported.
	 */
	public function getTextForTransclusion( $modelId, $context = null ) {
		$content = $this->getContentForTransclusion( $modelId, $context );

		if ( $content === false ) {
			// conversion not supported
			return false;
		}

		$data = $content->getNativeData();

		if ( !is_string( $data ) ) {
			// The conversion succeeded, but did not result in string-based content.
			return false;
		}

		return $data;
	}

	/**
	 * @see Content::getContentForTransclusion
	 * @see Content::convert()
	 *
	 * This calls $this->convert( $modelId, 'lossy' ). If that conversion fails,
	 * $this->convertContentViaHtml() is called to try and provide a transcludable
	 * Content object wrapping the HTML rendering of the content.
	 *
	 * @since 1.24
	 *
	 * @param string $modelId
	 * @param object|null $context
	 *
	 * @return Content|false
	 */
	public function getContentForTransclusion( $modelId, $context = null ) {
		$content = $this->convert( $modelId, 'lossy' );

		if ( $content === false ) {
			// Conversion not supported.
			// Try conversion via HTML, if that wasn't the original target model
			// (in which case we already know the conversion will fail,
			// triggering an infinite regress).
			if ( $modelId !== CONTENT_MODEL_HTML ) {
				$content = $this->convertContentViaHtml( $modelId );
			}
		}

		return $content;
	}

	/**
	 * If possible, converts the content to raw HTML, and then wraps
	 * that HTML in a Content object using the given target model.
	 *
	 * This allows arbitrary content to be used in transclusions, by
	 * using their HTML rendering.
	 *
	 * @param string $modelId The desired content Model. Must not be CONTENT_MODEL_HTML!
	 * @param object|null $context
	 *
	 * @throws InvalidArgumentException If called with $modelId === CONTENT_MODEL_HTML.
	 * @return bool|Content|null
	 */
	protected function convertContentViaHtml( $modelId, $context = null ) {
		if ( $modelId === CONTENT_MODEL_HTML ) {
			throw new InvalidArgumentException( 'Can\'t convert to HTML via HTML!' );
		}

		$content = false;
		$targetHandler = ContentHandler::getForModelID( $modelId );

		if ( $targetHandler->supportsHtmlTransclusion() ) {

			// Try converting to raw HTML, and then wrap that HTML appropriately.
			$html = $this->getTextForTransclusion( CONTENT_MODEL_HTML );

			if ( $html !== false ) {
				$content = $targetHandler->makeContentFromHtml( $html, $context );

				// makeContentFromHtml() may return null.
				if ( !$content ) {
					$content = false;
				}
			}
		}

		return $content;
	}

	/**
	 * Returns a ParserOutput object containing information derived from this content.
	 * Most importantly, unless $generateHtml was false, the return value contains an
	 * HTML representation of the content.
	 *
	 * Subclasses that want to control the parser output may override this, but it is
	 * preferred to override fillParserOutput() instead.
	 *
	 * Subclasses that override getParserOutput() itself should take care to call the
	 * ContentGetParserOutput hook.
	 *
	 * @since 1.24
	 *
	 * @param Title $title Context title for parsing
	 * @param int|null $revId Revision ID (for {{REVISIONID}})
	 * @param ParserOptions|null $options Parser options
	 * @param bool $generateHtml Whether or not to generate HTML
	 *
	 * @return ParserOutput Containing information derived from this content.
	 */
	public function getParserOutput( Title $title, $revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		if ( $options === null ) {
			$options = $this->getContentHandler()->makeParserOptions( 'canonical' );
		}

		$po = new ParserOutput();

		if ( wfRunHooks( 'ContentGetParserOutput',
			array( $this, $title, $revId, $options, $generateHtml, &$po ) ) ) {

			$this->fillParserOutput( $title, $revId, $options, $generateHtml, $po );
		}

		return $po;
	}

	/**
	 * Fills the provided ParserOutput with information derived from the content.
	 * Unless $generateHtml was false, this includes an HTML representation of the content.
	 *
	 * This is called by getParserOutput() after consulting the ContentGetParserOutput hook.
	 * Subclasses are expected to override this method or getHtml() (or getParserOutput(),
	 * if need be).
	 *
	 * If no meta-information is required, subclasses may override getHtml() instead.
	 *
	 * This placeholder implementation always throws an exception.
	 *
	 * @since 1.24
	 *
	 * @param Title $title Context title for parsing
	 * @param int|null $revId Revision ID (for {{REVISIONID}})
	 * @param ParserOptions|null $options Parser options
	 * @param bool $generateHtml Whether or not to generate HTML
	 * @param ParserOutput &$output The output object to fill (reference).
	 *
	 * @throws MWException
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		if ( $generateHtml ) {
			// XXX: would be nice to pass $parserOptions on to getHtml(),
			// but we can't easily modify the signature of getHtml() any more.
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$output->setText( $html );
	}

	/**
	 * Generates an HTML version of the content, for display. Used by
	 * fillParserOutput() to provide HTML for the ParserOutput object,
	 * as well as by convert() for conversion to CONTENT_MODEL_HTML.
	 *
	 * Subclasses must implement this to provide a HTML rendering.
	 * If further information is to be derived from the content (such as
	 * categories), the fillParserOutput() method should be overridden.
	 *
	 * @note: The default implementation of convert() relies on getHtml()
	 * for naive conversion to HTML. Subclasses that override fillParserOutput()
	 * should consider overwriting getHtml() anyway (possibly based on
	 * getParserOutput), to allow conversion to HTML and thus automatic
	 * HTML based transclusion into wikitext.
	 *
	 * @throws MWException Always.
	 * @return string An HTML representation of the content
	 */
	protected function getHtml() {
		// Don't make abstract, so subclasses that override fillParserOutput()
		// resp. getParserOutput() directly don't fail.
		throw new MWException( 'Subclasses of AbstractContent must override getHtml(), '
			. 'fillParserOutput() or getParserOutput()!' );
	}
}
