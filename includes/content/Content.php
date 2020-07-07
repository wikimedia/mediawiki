<?php
/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to wiki pages.
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
 * Base interface for content objects.
 *
 * @ingroup Content
 * @unstable for implementation, extensions should extend AbstractContent instead.
 */
interface Content {

	/**
	 * @since 1.21
	 *
	 * @return string A string representing the content in a way useful for
	 *   building a full text search index. If no useful representation exists,
	 *   this method returns an empty string.
	 *
	 * @todo Test that this actually works
	 * @todo Make sure this also works with LuceneSearch / WikiSearch
	 */
	public function getTextForSearchIndex();

	/**
	 * @since 1.21
	 *
	 * @return string|bool The wikitext to include when another page includes this
	 * content, or false if the content is not includable in a wikitext page.
	 *
	 * @todo Allow native handling, bypassing wikitext representation, like
	 *  for includable special pages.
	 * @todo Allow transclusion into other content models than Wikitext!
	 * @todo Used in WikiPage and MessageCache to get message text. Not so
	 *  nice. What should we use instead?!
	 */
	public function getWikitextForTransclusion();

	/**
	 * Returns a textual representation of the content suitable for use in edit
	 * summaries and log messages.
	 *
	 * @since 1.21
	 *
	 * @param int $maxLength Maximum length of the summary text.
	 *
	 * @return string The summary text.
	 */
	public function getTextForSummary( $maxLength = 250 );

	/**
	 * Returns native representation of the data. Interpretation depends on
	 * the data model used, as given by getDataModel().
	 *
	 * @since 1.21
	 *
	 * @deprecated since 1.33 use getText() for TextContent instances.
	 *             For other content models, use specialized getters.
	 *
	 * @return mixed The native representation of the content. Could be a
	 *    string, a nested array structure, an object, a binary blob...
	 *    anything, really.
	 *
	 * @note Caller must be aware of content model!
	 */
	public function getNativeData();

	/**
	 * Returns the content's nominal size in "bogo-bytes".
	 *
	 * @return int
	 */
	public function getSize();

	/**
	 * Returns the ID of the content model used by this Content object.
	 * Corresponds to the CONTENT_MODEL_XXX constants.
	 *
	 * @since 1.21
	 *
	 * @return string The model id
	 */
	public function getModel();

	/**
	 * Convenience method that returns the ContentHandler singleton for handling
	 * the content model that this Content object uses.
	 *
	 * Shorthand for ContentHandler::getForContent( $this )
	 *
	 * @since 1.21
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
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getDefaultFormat();

	/**
	 * Convenience method that returns the list of serialization formats
	 * supported for the content model that this Content object uses.
	 *
	 * Shorthand for $this->getContentHandler()->getSupportedFormats()
	 *
	 * @since 1.21
	 *
	 * @return string[] List of supported serialization formats
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
	 * @since 1.21
	 *
	 * @param string $format The serialization format to check.
	 *
	 * @return bool Whether the format is supported
	 */
	public function isSupportedFormat( $format );

	/**
	 * Convenience method for serializing this Content object.
	 *
	 * Shorthand for $this->getContentHandler()->serializeContent( $this, $format )
	 *
	 * @since 1.21
	 *
	 * @param string|null $format The desired serialization format, or null for the default format.
	 *
	 * @return string Serialized form of this Content object.
	 */
	public function serialize( $format = null );

	/**
	 * Returns true if this Content object represents empty content.
	 *
	 * @since 1.21
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
	 * @since 1.21
	 *
	 * @return bool
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
	 * - Will return false if $that->getModel() !== $this->getModel().
	 * - Will return false if get_class( $that ) !== get_class( $this )
	 * - Should return false if $that->getModel() == $this->getModel() and
	 *     $that is not semantically equivalent to $this, according to
	 *     the data model defined by $this->getModel().
	 *
	 * Implementations should be careful to make equals() transitive and reflexive:
	 *
	 * - $a->equals( $b ) <=> $b->equals( $a )
	 * - $a->equals( $b ) &&  $b->equals( $c ) ==> $a->equals( $c )
	 *
	 * @since 1.21
	 *
	 * @param Content|null $that The Content object to compare to.
	 *
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
	 * should return $this. That is, $copy === $original may be true, but only
	 * for immutable content objects.
	 *
	 * @since 1.21
	 *
	 * @return Content A copy of this object
	 */
	public function copy();

	/**
	 * Returns true if this content is countable as a "real" wiki page, provided
	 * that it's also in a countable location (e.g. a current revision in the
	 * main namespace).
	 *
	 * @see SlotRoleHandler::supportsArticleCount
	 *
	 * @since 1.21
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null );

	/**
	 * Parse the Content object and generate a ParserOutput from the result.
	 * $result->getText() can be used to obtain the generated HTML. If no HTML
	 * is needed, $generateHtml can be set to false; in that case,
	 * $result->getText() may return null.
	 *
	 * @note To control which options are used in the cache key for the
	 *       generated parser output, implementations of this method
	 *       may call ParserOutput::recordOption() on the output object.
	 *
	 * @param Title $title The page title to use as a context for rendering.
	 * @param int|null $revId ID of the revision being rendered.
	 *  See Parser::parse() for the ramifications. (default: null)
	 * @param ParserOptions|null $options Any parser options.
	 * @param bool $generateHtml Whether to generate HTML (default: true). If false,
	 *        the result of calling getText() on the ParserOutput object returned by
	 *        this method is undefined.
	 *
	 * @since 1.21
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( Title $title, $revId = null,
		ParserOptions $options = null, $generateHtml = true );

	// TODO: make RenderOutput and RenderOptions base classes

	/**
	 * Returns a list of DataUpdate objects for recording information about this
	 * Content in some secondary data store. If the optional second argument,
	 * $old, is given, the updates may model only the changes that need to be
	 * made to replace information about the old content with information about
	 * the new content.
	 *
	 * @deprecated since 1.32, call and override
	 *   ContentHandler::getSecondaryDataUpdates instead.
	 *
	 * @note Implementations should call the SecondaryDataUpdates hook, like
	 *   AbstractContent does.
	 *
	 * @param Title $title The context for determining the necessary updates
	 * @param Content|null $old An optional Content object representing the
	 *    previous content, i.e. the content being replaced by this Content
	 *    object.
	 * @param bool $recursive Whether to include recursive updates (default:
	 *    false).
	 * @param ParserOutput|null $parserOutput Optional ParserOutput object.
	 *    Provide if you have one handy, to avoid re-parsing of the content.
	 *
	 * @return DataUpdate[] A list of DataUpdate objects for putting information
	 *    about this content object somewhere.
	 *
	 * @since 1.21
	 */
	public function getSecondaryDataUpdates( Title $title, Content $old = null,
		$recursive = true, ParserOutput $parserOutput = null );

	/**
	 * Construct the redirect destination from this content and return an
	 * array of Titles, or null if this content doesn't represent a redirect.
	 * The last element in the array is the final destination after all redirects
	 * have been resolved (up to $wgMaxRedirects times).
	 *
	 * @since 1.21
	 *
	 * @return Title[]|null List of Titles, with the destination last.
	 */
	public function getRedirectChain();

	/**
	 * Construct the redirect destination from this content and return a Title,
	 * or null if this content doesn't represent a redirect.
	 * This will only return the immediate redirect target, useful for
	 * the redirect table and other checks that don't need full recursion.
	 *
	 * @since 1.21
	 *
	 * @return Title|null The corresponding Title.
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
	 * There is usually no need to override the default behavior, subclasses that
	 * want to implement redirects should override getRedirectTarget().
	 *
	 * @since 1.21
	 *
	 * @return Title|null
	 */
	public function getUltimateRedirectTarget();

	/**
	 * Returns whether this Content represents a redirect.
	 * Shorthand for getRedirectTarget() !== null.
	 *
	 * @see SlotRoleHandler::supportsRedirects
	 *
	 * @since 1.21
	 *
	 * @return bool
	 */
	public function isRedirect();

	/**
	 * If this Content object is a redirect, this method updates the redirect target.
	 * Otherwise, it does nothing.
	 *
	 * @since 1.21
	 *
	 * @param Title $target The new redirect target
	 *
	 * @return Content A new Content object with the updated redirect (or $this
	 *   if this Content object isn't a redirect)
	 */
	public function updateRedirect( Title $target );

	/**
	 * Returns the section with the given ID.
	 *
	 * @since 1.21
	 *
	 * @param string|int $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'). The ID "0" retrieves the section before the first heading, "1" the
	 * text between the first heading (included) and the second heading (excluded), etc.
	 *
	 * @return Content|bool|null The section, or false if no such section
	 *    exist, or null if sections are not supported.
	 */
	public function getSection( $sectionId );

	/**
	 * Replaces a section of the content and returns a Content object with the
	 * section replaced.
	 *
	 * @since 1.21
	 *
	 * @param string|int|null|bool $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $with New content of the section
	 * @param string $sectionTitle New section's subject, only if $section is 'new'
	 *
	 * @return Content|null New content of the entire page, or null if error
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' );

	/**
	 * Returns a Content object with pre-save transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @since 1.21
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $parserOptions
	 *
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $parserOptions );

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended, if supported. The default implementation just returns this
	 * Content object unmodified, ignoring the section header.
	 *
	 * @since 1.21
	 *
	 * @param string $header
	 *
	 * @return Content
	 */
	public function addSectionHeader( $header );

	/**
	 * Returns a Content object with preload transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @since 1.21
	 *
	 * @param Title $title
	 * @param ParserOptions $parserOptions
	 * @param array $params
	 *
	 * @return Content
	 */
	public function preloadTransform( Title $title, ParserOptions $parserOptions, $params = [] );

	/**
	 * Prepare Content for saving. Called before Content is saved by WikiPage::doEditContent() and in
	 * similar places.
	 *
	 * This may be used to check the content's consistency with global state. This function should
	 * NOT write any information to the database.
	 *
	 * Note that this method will usually be called inside the same transaction
	 * bracket that will be used to save the new revision.
	 *
	 * Note that this method is called before any update to the page table is
	 * performed. This means that $page may not yet know a page ID.
	 *
	 * @since 1.21
	 *
	 * @param WikiPage $page The page to be saved.
	 * @param int $flags Bitfield for use with EDIT_XXX constants, see WikiPage::doEditContent()
	 * @param int $parentRevId The ID of the current revision
	 * @param User $user
	 *
	 * @return Status A status object indicating whether the content was
	 *   successfully prepared for saving. If the returned status indicates
	 *   an error, a rollback will be performed and the transaction aborted.
	 *
	 * @see WikiPage::doEditContent()
	 */
	public function prepareSave( WikiPage $page, $flags, $parentRevId, User $user );

	/**
	 * Returns a list of updates to perform when this content is deleted.
	 * The necessary updates may be taken from the Content object, or depend on
	 * the current state of the database.
	 *
	 * @since 1.21
	 * @deprecated since 1.32, call and override
	 *   ContentHandler::getDeletionUpdates instead.
	 *
	 * @param WikiPage $page The page the content was deleted from.
	 * @param ParserOutput|null $parserOutput Optional parser output object
	 *    for efficient access to meta-information about the content object.
	 *    Provide if you have one handy.
	 *
	 * @return DeferrableUpdate[] A list of DeferrableUpdate instances that will clean up the
	 *    database after deletion.
	 */
	public function getDeletionUpdates( WikiPage $page,
		ParserOutput $parserOutput = null );

	/**
	 * Returns true if this Content object matches the given magic word.
	 *
	 * @since 1.21
	 *
	 * @param MagicWord $word The magic word to match
	 *
	 * @return bool Whether this Content object matches the given magic word.
	 */
	public function matchMagicWord( MagicWord $word );

	/**
	 * Converts this content object into another content object with the given content model,
	 * if that is possible.
	 *
	 * @param string $toModel The desired content model, use the CONTENT_MODEL_XXX flags.
	 * @param string $lossy Optional flag, set to "lossy" to allow lossy conversion. If lossy
	 * conversion is not allowed, full round-trip conversion is expected to work without losing
	 * information.
	 *
	 * @return Content|bool A content object with the content model $toModel, or false if
	 * that conversion is not supported.
	 */
	public function convert( $toModel, $lossy = '' );

	// @todo ImagePage and CategoryPage interfere with per-content action handlers
	// @todo nice&sane integration of GeSHi syntax highlighting
	//   [11:59] <vvv> Hooks are ugly; make CodeHighlighter interface and a
	//   config to set the class which handles syntax highlighting
	//   [12:00] <vvv> And default it to a DummyHighlighter

}
