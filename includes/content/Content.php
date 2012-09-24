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
	 * Prepare Content for saving. Called before Content is saved by WikiPage::doEditContent() and in
	 * similar places.
	 *
	 * This may be used to check the content's consistency with global state. This function should
	 * NOT write any information to the database.
	 *
	 * Note that this method will usually be called inside the same transaction bracket that will be used
	 * to save the new revision.
	 *
	 * Note that this method is called before any update to the page table is performed. This means that
	 * $page may not yet know a page ID.
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
	 * @param $page \WikiPage the deleted page
	 * @param $parserOutput null|\ParserOutput optional parser output object
	 *    for efficient access to meta-information about the content object.
	 *    Provide if you have one handy.
	 *
	 * @return array A list of DataUpdate instances that will clean up the
	 *    database after deletion.
	 */
	public function getDeletionUpdates( WikiPage $page,
		ParserOutput $parserOutput = null );

	/**
	 * Returns true if this Content object matches the given magic word.
	 *
	 * @param MagicWord $word the magic word to match
	 *
	 * @return bool whether this Content object matches the given magic word.
	 */
	public function matchMagicWord( MagicWord $word );

	# TODO: ImagePage and CategoryPage interfere with per-content action handlers
	# TODO: make sure WikiSearch extension still works
	# TODO: make sure ReplaceTemplates extension still works
	# TODO: nice&sane integration of GeSHi syntax highlighting
	#   [11:59] <vvv> Hooks are ugly; make CodeHighlighter interface and a 
	#   config to set the class which handles syntax highlighting
	#   [12:00] <vvv> And default it to a DummyHighlighter
}