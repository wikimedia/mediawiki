<?php
/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to wiki pages.
 *
 * @since 1.WD
 */
interface Content {

	/**
	 * @since WD.1
	 *
	 * @return string A string representing the content in a way useful for
	 *   building a full text search index. If no useful representation exists,
	 *   this method returns an empty string.
	 *
	 * @todo: test that this actually works
	 * @todo: make sure this also works with LuceneSearch / WikiSearch
	 */
	public function getTextForSearchIndex( );

	/**
	 * @since WD.1
	 *
	 * @return string The wikitext to include when another page includes this
	 * content, or false if the content is not includable in a wikitext page.
	 *
	 * @TODO: allow native handling, bypassing wikitext representation, like
	 *    for includable special pages.
	 * @TODO: allow transclusion into other content models than Wikitext!
	 * @TODO: used in WikiPage and MessageCache to get message text. Not so
	 *    nice. What should we use instead?!
	 */
	public function getWikitextForTransclusion( );

	/**
	 * Returns a textual representation of the content suitable for use in edit
	 * summaries and log messages.
	 *
	 * @since WD.1
	 *
	 * @param $maxlength int Maximum length of the summary text
	 * @return   The summary text
	 */
	public function getTextForSummary( $maxlength = 250 );

	/**
	 * Returns native representation of the data. Interpretation depends on
	 * the data model used, as given by getDataModel().
	 *
	 * @since WD.1
	 *
	 * @return mixed The native representation of the content. Could be a
	 *    string, a nested array structure, an object, a binary blob...
	 *    anything, really.
	 *
	 * @NOTE: review all calls carefully, caller must be aware of content model!
	 */
	public function getNativeData( );

	/**
	 * Returns the content's nominal size in bogo-bytes.
	 *
	 * @return int
	 */
	public function getSize( );

	/**
	 * Returns the ID of the content model used by this Content object.
	 * Corresponds to the CONTENT_MODEL_XXX constants.
	 *
	 * @since WD.1
	 *
	 * @return String The model id
	 */
	public function getModel();

	/**
	 * Convenience method that returns the ContentHandler singleton for handling
	 * the content model that this Content object uses.
	 *
	 * Shorthand for ContentHandler::getForContent( $this )
	 *
	 * @since WD.1
	 *
	 * @return ContentHandler
	 */
	public function getContentHandler();

	/**
	 * Convenience method that returns the default serialization format for the
	 * content model that this Content object uses.
	 *
	 * Shorthand for $this->getContentHandler()->getDefaultFormat()
	 *
	 * @since WD.1
	 *
	 * @return String
	 */
	public function getDefaultFormat();

	/**
	 * Convenience method that returns the list of serialization formats
	 * supported for the content model that this Content object uses.
	 *
	 * Shorthand for $this->getContentHandler()->getSupportedFormats()
	 *
	 * @since WD.1
	 *
	 * @return Array of supported serialization formats
	 */
	public function getSupportedFormats();

	/**
	 * Returns true if $format is a supported serialization format for this
	 * Content object, false if it isn't.
	 *
	 * Note that this should always return true if $format is null, because null
	 * stands for the default serialization.
	 *
	 * Shorthand for $this->getContentHandler()->isSupportedFormat( $format )
	 *
	 * @since WD.1
	 *
	 * @param $format string The format to check
	 * @return bool Whether the format is supported
	 */
	public function isSupportedFormat( $format );

	/**
	 * Convenience method for serializing this Content object.
	 *
	 * Shorthand for $this->getContentHandler()->serializeContent( $this, $format )
	 *
	 * @since WD.1
	 *
	 * @param $format null|string The desired serialization format (or null for
	 *    the default format).
	 * @return string Serialized form of this Content object
	 */
	public function serialize( $format = null );

	/**
	 * Returns true if this Content object represents empty content.
	 *
	 * @since WD.1
	 *
	 * @return bool Whether this Content object is empty
	 */
	public function isEmpty();

	/**
	 * Returns whether the content is valid. This is intended for local validity
	 * checks, not considering global consistency.
	 *
	 * Content needs to be valid before it can be saved.
	 *
	 * This default implementation always returns true.
	 *
	 * @since WD.1
	 *
	 * @return boolean
	 */
	public function isValid();

	/**
	 * Returns true if this Content objects is conceptually equivalent to the
	 * given Content object.
	 *
	 * Contract:
	 *
	 * - Will return false if $that is null.
	 * - Will return true if $that === $this.
	 * - Will return false if $that->getModelName() != $this->getModel().
	 * - Will return false if $that->getNativeData() is not equal to $this->getNativeData(),
	 *   where the meaning of "equal" depends on the actual data model.
	 *
	 * Implementations should be careful to make equals() transitive and reflexive:
	 *
	 * - $a->equals( $b ) <=> $b->equals( $a )
	 * - $a->equals( $b ) &&  $b->equals( $c ) ==> $a->equals( $c )
	 *
	 * @since WD.1
	 *
	 * @param $that Content The Content object to compare to
	 * @return bool True if this Content object is equal to $that, false otherwise.
	 */
	public function equals( Content $that = null );

	/**
	 * Return a copy of this Content object. The following must be true for the
	 * object returned:
	 *
	 * if $copy = $original->copy()
	 *
	 * - get_class($original) === get_class($copy)
	 * - $original->getModel() === $copy->getModel()
	 * - $original->equals( $copy )
	 *
	 * If and only if the Content object is immutable, the copy() method can and
	 * should return $this. That is,  $copy === $original may be true, but only
	 * for immutable content objects.
	 *
	 * @since WD.1
	 *
	 * @return Content. A copy of this object
	 */
	public function copy( );

	/**
	 * Returns true if this content is countable as a "real" wiki page, provided
	 * that it's also in a countable location (e.g. a current revision in the
	 * main namespace).
	 *
	 * @since WD.1
	 *
	 * @param $hasLinks Bool: If it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out.
	 * @return boolean
	 */
	public function isCountable( $hasLinks = null ) ;


	/**
	 * Parse the Content object and generate a ParserOutput from the result.
	 * $result->getText() can be used to obtain the generated HTML. If no HTML
	 * is needed, $generateHtml can be set to false; in that case,
	 * $result->getText() may return null.
	 *
	 * @param $title Title The page title to use as a context for rendering
	 * @param $revId null|int The revision being rendered (optional)
	 * @param $options null|ParserOptions Any parser options
	 * @param $generateHtml Boolean Whether to generate HTML (default: true). If false,
	 *        the result of calling getText() on the ParserOutput object returned by
	 *        this method is undefined.
	 *
	 * @since WD.1
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true );
	# TODO: make RenderOutput and RenderOptions base classes

	/**
	 * Returns a list of DataUpdate objects for recording information about this
	 * Content in some secondary data store. If the optional second argument,
	 * $old, is given, the updates may model only the changes that need to be
	 * made to replace information about the old content with information about
	 * the new content.
	 *
	 * This default implementation calls
	 * $this->getParserOutput( $content, $title, null, null, false ),
	 * and then calls getSecondaryDataUpdates( $title, $recursive ) on the
	 * resulting ParserOutput object.
	 *
	 * Subclasses may implement this to determine the necessary updates more
	 * efficiently, or make use of information about the old content.
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
	 * @since WD.1
	 */
	public function getSecondaryDataUpdates( Title $title,
		Content $old = null,
		$recursive = true, ParserOutput $parserOutput = null
	);

	/**
	 * Construct the redirect destination from this content and return an
	 * array of Titles, or null if this content doesn't represent a redirect.
	 * The last element in the array is the final destination after all redirects
	 * have been resolved (up to $wgMaxRedirects times).
	 *
	 * @since WD.1
	 *
	 * @return Array of Titles, with the destination last
	 */
	public function getRedirectChain();

	/**
	 * Construct the redirect destination from this content and return a Title,
	 * or null if this content doesn't represent a redirect.
	 * This will only return the immediate redirect target, useful for
	 * the redirect table and other checks that don't need full recursion.
	 *
	 * @since WD.1
	 *
	 * @return Title: The corresponding Title
	 */
	public function getRedirectTarget();

	/**
	 * Construct the redirect destination from this content and return the
	 * Title, or null if this content doesn't represent a redirect.
	 *
	 * This will recurse down $wgMaxRedirects times or until a non-redirect
	 * target is hit in order to provide (hopefully) the Title of the final
	 * destination instead of another redirect.
	 *
	 * There is usually no need to override the default behaviour, subclasses that
	 * want to implement redirects should override getRedirectTarget().
	 * 
	 * @since WD.1
	 *
	 * @return Title
	 */
	public function getUltimateRedirectTarget();

	/**
	 * Returns whether this Content represents a redirect.
	 * Shorthand for getRedirectTarget() !== null.
	 *
	 * @since WD.1
	 *
	 * @return bool
	 */
	public function isRedirect();

	/**
	 * If this Content object is a redirect, this method updates the redirect target.
	 * Otherwise, it does nothing.
	 *
	 * @since WD.1
	 *
	 * @param Title $target the new redirect target
	 *
	 * @return Content a new Content object with the updated redirect (or $this if this Content object isn't a redirect)
	 */
	public function updateRedirect( Title $target );

	/**
	 * Returns the section with the given ID.
	 *
	 * @since WD.1
	 *
	 * @param $sectionId string The section's ID, given as a numeric string.
	 *    The ID "0" retrieves the section before the first heading, "1" the
	 *    text between the first heading (included) and the second heading
	 *    (excluded), etc.
	 * @return Content|Boolean|null The section, or false if no such section
	 *    exist, or null if sections are not supported.
	 */
	public function getSection( $sectionId );

	/**
	 * Replaces a section of the content and returns a Content object with the
	 * section replaced.
	 *
	 * @since WD.1
	 *
	 * @param $section Empty/null/false or a section number (0, 1, 2, T1, T2...), or "new"
	 * @param $with Content: new content of the section
	 * @param $sectionTitle String: new section's subject, only if $section is 'new'
	 * @return string Complete article text, or null if error
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = ''  );

	/**
	 * Returns a Content object with pre-save transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @since WD.1
	 *
	 * @param $title Title
	 * @param $user User
	 * @param $popts null|ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts );

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended, if supported. The default implementation just returns this
	 * Content object unmodified, ignoring the section header.
	 *
	 * @since WD.1
	 *
	 * @param $header string
	 * @return Content
	 */
	public function addSectionHeader( $header );

	/**
	 * Returns a Content object with preload transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @since WD.1
	 *
	 * @param $title Title
	 * @param $popts null|ParserOptions
	 * @return Content
	 */
	public function preloadTransform( Title $title, ParserOptions $popts );

	/**
	 * Prepare Content for saving. Called before Content is saved by WikiPage::doEditContent().
	 * This may be used to store additional information in the database, or check the content's
	 * consistency with global state.
	 *
	 * Note that this method will be called inside the same transaction bracket that will be used
	 * to save the new revision.
	 *
	 * @param WikiPage $page The page to be saved.
	 * @param int      $flags bitfield for use with EDIT_XXX constants, see WikiPage::doEditContent()
	 * @param int      $baseRevId the ID of the current revision
	 * @param User     $user
	 *
	 * @return Status A status object indicating whether the content was successfully prepared for saving.
	 *                If the returned status indicates an error, a rollback will be performed and the
	 *                transaction aborted.
	 *
	 * @see see WikiPage::doEditContent()
	 */
	public function prepareSave( WikiPage $page, $flags, $baseRevId, User $user );

	/**
	 * Returns a list of updates to perform when this content is deleted.
	 * The necessary updates may be taken from the Content object, or depend on
	 * the current state of the database.
	 *
	 * @since WD.1
	 *
	 * @param $title \Title the title of the deleted page
	 * @param $parserOutput null|\ParserOutput optional parser output object
	 *    for efficient access to meta-information about the content object.
	 *    Provide if you have one handy.
	 *
	 * @return array A list of DataUpdate instances that will clean up the
	 *    database after deletion.
	 */
	public function getDeletionUpdates( Title $title,
		ParserOutput $parserOutput = null );

	# TODO: handle ImagePage and CategoryPage
	# TODO: make sure we cover lucene search / wikisearch.
	# TODO: make sure ReplaceTemplates still works
	# FUTURE: nice&sane integration of GeSHi syntax highlighting
	#   [11:59] <vvv> Hooks are ugly; make CodeHighlighter interface and a 
	#   config to set the class which handles syntax highlighting
	#   [12:00] <vvv> And default it to a DummyHighlighter

	# TODO: make sure we cover the external editor interface (does anyone actually use that?!)

	# TODO: tie into API to provide contentModel for Revisions
	# TODO: tie into API to provide serialized version and contentFormat for Revisions
	# TODO: tie into API edit interface
	# FUTURE: make EditForm plugin for EditPage

	# FUTURE: special type for redirects?!
	# FUTURE: MultipartMultipart < WikipageContent (Main + Links + X)
	# FUTURE: LinksContent < LanguageLinksContent, CategoriesContent
}


/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to Wiki pages.
 *
 * @since 1.WD
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
	 * @since WD.1
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
	 * @since WD.1
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
	 * @since WD.1
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
	 * Returns a list of updates to perform when this content is deleted.
	 * The necessary updates may be taken from the Content object, or depend on
	 * the current state of the database.
	 *
	 * @since WD.1
	 *
	 * @param $title \Title the title of the deleted page
	 * @param $parserOutput null|\ParserOutput optional parser output object
	 *    for efficient access to meta-information about the content object.
	 *    Provide if you have one handy.
	 *
	 * @return array A list of DataUpdate instances that will clean up the
	 *    database after deletion.
	 */
	public function getDeletionUpdates( Title $title,
		ParserOutput $parserOutput = null )
	{
		return array(
			new LinksDeletionUpdate( $title ),
		);
	}
}

/**
 * Content object implementation for representing flat text.
 *
 * TextContent instances are immutable
 *
 * @since WD.1
 */
abstract class TextContent extends AbstractContent {

	public function __construct( $text, $model_id = null ) {
		parent::__construct( $model_id );

		$this->mText = $text;
	}

	public function copy() {
		return $this; # NOTE: this is ok since TextContent are immutable.
	}

	public function getTextForSummary( $maxlength = 250 ) {
		global $wgContLang;

		$text = $this->getNativeData();

		$truncatedtext = $wgContLang->truncate(
			preg_replace( "/[\n\r]/", ' ', $text ),
			max( 0, $maxlength ) );

		return $truncatedtext;
	}

	/**
	 * returns the text's size in bytes.
	 *
	 * @return int The size
	 */
	public function getSize( ) {
		$text = $this->getNativeData( );
		return strlen( $text );
	}

	/**
	 * Returns true if this content is not a redirect, and $wgArticleCountMethod
	 * is "any".
	 *
	 * @param $hasLinks Bool: if it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool True if the content is countable
	 */
	public function isCountable( $hasLinks = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect( ) ) {
			return false;
		}

		if (  $wgArticleCountMethod === 'any' ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getNativeData( ) {
		$text = $this->mText;
		return $text;
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getTextForSearchIndex( ) {
		return $this->getNativeData();
	}

	/**
	 * Returns the text represented by this Content object, as a string.
	 *
	 * @param   the raw text
	 */
	public function getWikitextForTransclusion( ) {
		return $this->getNativeData();
	}

	/**
	 * Diff this content object with another content object..
	 *
	 * @since WD.diff
	 *
	 * @param $that Content the other content object to compare this content object to
	 * @param $lang Language the language object to use for text segmentation.
	 *    If not given, $wgContentLang is used.
	 *
	 * @return DiffResult a diff representing the changes that would have to be
	 *    made to this content object to make it equal to $that.
	 */
	public function diff( Content $that, Language $lang = null ) {
		global $wgContLang;

		$this->checkModelID( $that->getModel() );

		# @todo: could implement this in DifferenceEngine and just delegate here?

		if ( !$lang ) $lang = $wgContLang;

		$otext = $this->getNativeData();
		$ntext = $this->getNativeData();

		# Note: Use native PHP diff, external engines don't give us abstract output
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );

		$diff = new Diff( $ota, $nta );
		return $diff;
	}


	/**
	 * Returns a generic ParserOutput object, wrapping the HTML returned by
	 * getHtml().
	 *
	 * @param $title Title Context title for parsing
	 * @param $revId int|null Revision ID (for {{REVISIONID}})
	 * @param $options ParserOptions|null Parser options
	 * @param $generateHtml bool Whether or not to generate HTML
	 *
	 * @return ParserOutput representing the HTML form of the text
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		# Generic implementation, relying on $this->getHtml()

		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$po = new ParserOutput( $html );
		return $po;
	}

	/**
	 * Generates an HTML version of the content, for display. Used by
	 * getParserOutput() to construct a ParserOutput object.
	 *
	 * This default implementation just calls getHighlightHtml(). Content
	 * models that have another mapping to HTML (as is the case for markup
	 * languages like wikitext) should override this method to generate the
	 * appropriate HTML.
	 *
	 * @return string An HTML representation of the content
	 */
	protected function getHtml() {
		return $this->getHighlightHtml();
	}

	/**
	 * Generates a syntax-highlighted version of the content, as HTML.
	 * Used by the default implementation of getHtml().
	 *
	 * @return string an HTML representation of the content's markup
	 */
	protected function getHighlightHtml( ) {
		# TODO: make Highlighter interface, use highlighter here, if available
		return htmlspecialchars( $this->getNativeData() );
	}

}

/**
 * @since WD.1
 */
class WikitextContent extends TextContent {

	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @see Content::getSection()
	 */
	public function getSection( $section ) {
		global $wgParser;

		$text = $this->getNativeData();
		$sect = $wgParser->getSection( $text, $section, false );

		return  new WikitextContent( $sect );
	}

	/**
	 * @see Content::replaceSection()
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
		wfProfileIn( __METHOD__ );

		$myModelId = $this->getModel();
		$sectionModelId = $with->getModel();

		if ( $sectionModelId != $myModelId  ) {
			throw new MWException( "Incompatible content model for section: " .
				"document uses $myModelId but " .
				"section uses $sectionModelId." );
		}

		$oldtext = $this->getNativeData();
		$text = $with->getNativeData();

		if ( $section === '' ) {
			return $with; # XXX: copy first?
		} if ( $section == 'new' ) {
			# Inserting a new section
			if ( $sectionTitle ) {
				$subject = wfMsgForContent( 'newsectionheaderdefaultlevel', $sectionTitle ) . "\n\n";
			} else {
				$subject = '';
			}
			if ( wfRunHooks( 'PlaceNewSection', array( $this, $oldtext, $subject, &$text ) ) ) {
				$text = strlen( trim( $oldtext ) ) > 0
					? "{$oldtext}\n\n{$subject}{$text}"
					: "{$subject}{$text}";
			}
		} else {
			# Replacing an existing section; roll out the big guns
			global $wgParser;

			$text = $wgParser->replaceSection( $oldtext, $section, $text );
		}

		$newContent = new WikitextContent( $text );

		wfProfileOut( __METHOD__ );
		return $newContent;
	}

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended.
	 *
	 * @param $header string
	 * @return Content
	 */
	public function addSectionHeader( $header ) {
		$text = wfMsgForContent( 'newsectionheaderdefaultlevel', $header ) . "\n\n" . 
			$this->getNativeData();

		return new WikitextContent( $text );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param $title Title
	 * @param $user User
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new WikitextContent( $pst );
	}

	/**
	 * Returns a Content object with preload transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @param $title Title
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preloadTransform( Title $title, ParserOptions $popts ) {
		global $wgParser;

		$text = $this->getNativeData();
		$plt = $wgParser->getPreloadText( $text, $title, $popts );

		return new WikitextContent( $plt );
	}

	/**
	 * Implement redirect extraction for wikitext.
	 *
	 * @return null|Title
	 *
	 * @note: migrated here from Title::newFromRedirectInternal()
	 *
	 * @see Content::getRedirectTarget
	 * @see AbstractContent::getRedirectTarget
	 */
	public function getRedirectTarget() {
		global $wgMaxRedirects;
		if ( $wgMaxRedirects < 1 ) {
			// redirects are disabled, so quit early
			return null;
		}
		$redir = MagicWord::get( 'redirect' );
		$text = trim( $this->getNativeData() );
		if ( $redir->matchStartAndRemove( $text ) ) {
			// Extract the first link and see if it's usable
			// Ensure that it really does come directly after #REDIRECT
			// Some older redirects included a colon, so don't freak about that!
			$m = array();
			if ( preg_match( '!^\s*:?\s*\[{2}(.*?)(?:\|.*?)?\]{2}!', $text, $m ) ) {
				// Strip preceding colon used to "escape" categories, etc.
				// and URL-decode links
				if ( strpos( $m[1], '%' ) !== false ) {
					// Match behavior of inline link parsing here;
					$m[1] = rawurldecode( ltrim( $m[1], ':' ) );
				}
				$title = Title::newFromText( $m[1] );
				// If the title is a redirect to bad special pages or is invalid, return null
				if ( !$title instanceof Title || !$title->isValidRedirectTarget() ) {
					return null;
				}
				return $title;
			}
		}
		return null;
	}

	/**
	 * @see   Content::updateRedirect()
	 *
	 * This implementation replaces the first link on the page with the given new target
	 * if this Content object is a redirect. Otherwise, this method returns $this.
	 *
	 * @since WD.1
	 *
	 * @param Title $target
	 *
	 * @return Content a new Content object with the updated redirect (or $this if this Content object isn't a redirect)
	 */
	public function updateRedirect( Title $target ) {
		if ( !$this->isRedirect() ) {
			return $this;
		}

		# Fix the text
		# Remember that redirect pages can have categories, templates, etc.,
		# so the regex has to be fairly general
		$newText = preg_replace( '/ \[ \[  [^\]]*  \] \] /x',
			'[[' . $target->getFullText() . ']]',
			$this->getNativeData(), 1 );

		return new WikitextContent( $newText );
	}

	/**
	 * Returns true if this content is not a redirect, and this content's text
	 * is countable according to the criteria defined by $wgArticleCountMethod.
	 *
	 * @param $hasLinks Bool  if it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out.
	 * @param $title null|\Title
	 *
	 * @internal param \IContextSource $context context for parsing if necessary
	 *
	 * @return bool True if the content is countable
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect( ) ) {
			return false;
		}

		$text = $this->getNativeData();

		switch ( $wgArticleCountMethod ) {
			case 'any':
				return true;
			case 'comma':
				return strpos( $text,  ',' ) !== false;
			case 'link':
				if ( $hasLinks === null ) { # not known, find out
					if ( !$title ) {
						$context = RequestContext::getMain();
						$title = $context->getTitle();
					}

					$po = $this->getParserOutput( $title, null, null, false );
					$links = $po->getLinks();
					$hasLinks = !empty( $links );
				}

				return $hasLinks;
		}

		return false;
	}

	public function getTextForSummary( $maxlength = 250 ) {
		$truncatedtext = parent::getTextForSummary( $maxlength );

		# clean up unfinished links
		# XXX: make this optional? wasn't there in autosummary, but required for
		# deletion summary.
		$truncatedtext = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $truncatedtext );

		return $truncatedtext;
	}


	/**
	 * Returns a ParserOutput object resulting from parsing the content's text
	 * using $wgParser.
	 *
	 * @since    WD.1
	 *
	 * @param $content Content the content to render
	 * @param $title \Title
	 * @param $revId null
	 * @param $options null|ParserOptions
	 * @param $generateHtml bool
	 *
	 * @internal param \IContextSource|null $context
	 * @return ParserOutput representing the HTML form of the text
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		global $wgParser;

		if ( !$options ) {
			$options = new ParserOptions();
		}

		$po = $wgParser->parse( $this->getNativeData(), $title, $options, true, true, $revId );
		return $po;
	}

	protected function getHtml() {
		throw new MWException(
			"getHtml() not implemented for wikitext. "
				. "Use getParserOutput()->getText()."
		);
	}


}

/**
 * @since WD.1
 */
class MessageContent extends TextContent {
	public function __construct( $msg_key, $params = null, $options = null ) {
		# XXX: messages may be wikitext, html or plain text! and maybe even
		# something else entirely.
		parent::__construct( null, CONTENT_MODEL_WIKITEXT );

		$this->mMessageKey = $msg_key;

		$this->mParameters = $params;

		if ( is_null( $options ) ) {
			$options = array();
		}
		elseif ( is_string( $options ) ) {
			$options = array( $options );
		}

		$this->mOptions = $options;
	}

	/**
	 * Returns the message as rendered HTML, using the options supplied to the
	 * constructor plus "parse".
	 * @param   the message text, parsed
	 */
	public function getHtml(  ) {
		$opt = array_merge( $this->mOptions, array( 'parse' ) );

		return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
	}


	/**
	 * Returns the message as raw text, using the options supplied to the
	 * constructor minus "parse" and "parseinline".
	 *
	 * @param   the message text, unparsed.
	 */
	public function getNativeData( ) {
		$opt = array_diff( $this->mOptions, array( 'parse', 'parseinline' ) );

		return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
	}

}

/**
 * @since WD.1
 */
class JavaScriptContent extends TextContent {
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_JAVASCRIPT );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo: make pre-save transformation optional for script pages
		// See bug #32858

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new JavaScriptContent( $pst );
	}


	protected function getHtml( ) {
		$html = "";
		$html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
		$html .= $this->getHighlightHtml( );
		$html .= "\n</pre>\n";

		return $html;
	}
}

/**
 * @since WD.1
 */
class CssContent extends TextContent {
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_CSS );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param $title Title
	 * @param $user User
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo: make pre-save transformation optional for script pages

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new CssContent( $pst );
	}


	protected function getHtml( ) {
		$html = "";
		$html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
		$html .= $this->getHighlightHtml( );
		$html .= "\n</pre>\n";

		return $html;
	}
}
