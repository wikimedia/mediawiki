<?php
/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to Wiki pages.
 *
 * @since 1.21
 */
abstract class AbstractContent implements Content {

	/**
	 * Name of the content model this Content object represents.
	 * Use with CONTENT_MODEL_XXX constants
	 *
	 * @var string $model_id
	 */
	protected $model_id;

	/**
	 * @param String $model_id
	 */
	public function __construct( $model_id = null ) {
		$this->model_id = $model_id;
	}

	/**
	 * @see Content::getModel()
	 */
	public function getModel() {
		return $this->model_id;
	}

	/**
	 * Throws an MWException if $model_id is not the id of the content model
	 * supported by this Content object.
	 *
	 * @param $model_id int the model to check
	 *
	 * @throws MWException
	 */
	protected function checkModelID( $model_id ) {
		if ( $model_id !== $this->model_id ) {
			throw new MWException( "Bad content model: " .
				"expected {$this->model_id}  " .
				"but got $model_id." );
		}
	}

	/**
	 * @see Content::getContentHandler()
	 */
	public function getContentHandler() {
		return ContentHandler::getForContent( $this );
	}

	/**
	 * @see Content::getDefaultFormat()
	 */
	public function getDefaultFormat() {
		return $this->getContentHandler()->getDefaultFormat();
	}

	/**
	 * @see Content::getSupportedFormats()
	 */
	public function getSupportedFormats() {
		return $this->getContentHandler()->getSupportedFormats();
	}

	/**
	 * @see Content::isSupportedFormat()
	 */
	public function isSupportedFormat( $format ) {
		if ( !$format ) {
			return true; // this means "use the default"
		}

		return $this->getContentHandler()->isSupportedFormat( $format );
	}

	/**
	 * Throws an MWException if $this->isSupportedFormat( $format ) doesn't
	 * return true.
	 *
	 * @param $format
	 * @throws MWException
	 */
	protected function checkFormat( $format ) {
		if ( !$this->isSupportedFormat( $format ) ) {
			throw new MWException( "Format $format is not supported for content model " . 
				$this->getModel() );
		}
	}

	/**
	 * @see Content::serialize
	 */
	public function serialize( $format = null ) {
		return $this->getContentHandler()->serializeContent( $this, $format );
	}

	/**
	 * @see Content::isEmpty()
	 */
	public function isEmpty() {
		return $this->getSize() == 0;
	}

	/**
	 * @see Content::isValid()
	 */
	public function isValid() {
		return true;
	}

	/**
	 * @see Content::equals()
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
	 * efficiently, preferrably without the need to generate a parser output object.
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
		if ( !$parserOutput ) {
			$parserOutput = $this->getParserOutput( $title, null, null, false );
		}

		return $parserOutput->getSecondaryDataUpdates( $title, $recursive );
	}


	/**
	 * @see Content::getRedirectChain()
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
	 * @see Content::getRedirectTarget()
	 */
	public function getRedirectTarget() {
		return null;
	}

	/**
	 * @see Content::getUltimateRedirectTarget()
	 * @note: migrated here from Title::newFromRedirectRecurse
	 */
	public function getUltimateRedirectTarget() {
		$titles = $this->getRedirectChain();
		return $titles ? array_pop( $titles ) : null;
	}

	/**
	 * @see Content::isRedirect()
	 *
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function isRedirect() {
		return $this->getRedirectTarget() !== null;
	}

	/**
	 * @see Content::updateRedirect()
	 *
	 * This default implementation always returns $this.
	 *
	 * @since 1.21
	 *
	 * @return Content $this
	 */
	public function updateRedirect( Title $target ) {
		return $this;
	}

	/**
	 * @see Content::getSection()
	 */
	public function getSection( $sectionId ) {
		return null;
	}

	/**
	 * @see Content::replaceSection()
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = ''  ) {
		return null;
	}

	/**
	 * @see Content::preSaveTransform()
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return $this;
	}

	/**
	 * @see Content::addSectionHeader()
	 */
	public function addSectionHeader( $header ) {
		return $this;
	}

	/**
	 * @see Content::preloadTransform()
	 */
	public function preloadTransform( Title $title, ParserOptions $popts ) {
		return $this;
	}

	/**
	 * @see  Content::prepareSave()
	 */
	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user ) {
		if ( $this->isValid() ) {
			return Status::newGood();
		} else {
			return Status::newFatal( "invalid-content-data" );
		}
	}

	/**
	 * @see  Content::getDeletionUpdates()
	 *
	 * @since 1.21
	 *
	 * @param $page \WikiPage the deleted page
	 * @param $parserOutput null|\ParserOutput optional parser output object
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
	 * @see  Content::matchMagicWord()
	 *
	 * This default implementation always returns false. Subclasses may override this to supply matching logic.
	 *
	 * @param MagicWord $word
	 *
	 * @return bool
	 */
	public function matchMagicWord( MagicWord $word ) {
		return false;
	}
}