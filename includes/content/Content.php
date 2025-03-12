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

use MediaWiki\Parser\MagicWord;
use MediaWiki\Title\Title;

/**
 * Content objects represent page content, e.g. the text shown on a page.
 *
 * Content objects must have no knowledge of what page they belong to,
 * or how they relate to pages in general. This information belongs in page
 * and revision records instead, flowing down to the content, instead of up
 * in the other direction.
 *
 * NOTE: This interface must NOT be implemented directly by extensions as it may
 * grow in backwards incompatible ways. Extend the AbstractContent class instead.
 *
 * @stable to type
 * @since 1.21
 * @ingroup Content
 * @author Daniel Kinzler
 */
interface Content {

	/**
	 * @since 1.21
	 * @see ContentHandler::getDataForSearchIndex
	 * @return string A string representing the content in a way useful for
	 *   building a full text search index. If no useful representation exists,
	 *   this method returns an empty string.
	 */
	public function getTextForSearchIndex();

	/**
	 * @todo Allow native handling, bypassing wikitext representation, like
	 *  for includable special pages.
	 * @todo Allow transclusion into other content models than Wikitext!
	 * @todo Used in WikiPage and MessageCache to get message text. Not so
	 *  nice. What should we use instead?!
	 *
	 * @since 1.21
	 * @return string|false The wikitext to include when another page includes this
	 * content, or false if the content is not includable in a wikitext page.
	 */
	public function getWikitextForTransclusion();

	/**
	 * Get a textual representation of the content, suitable for use in edit
	 * summaries and log messages.
	 *
	 * @since 1.21
	 * @param int $maxLength Maximum length of the summary text, in bytes.
	 * Usually implemented using {@link Language::truncateForDatabase()}.
	 * @return string The summary text.
	 */
	public function getTextForSummary( $maxLength = 250 );

	/**
	 * Get native representation of the data.
	 *
	 * @note Caller must be aware of content model. Interpretation must consider
	 * the model from getModel() or the handler from getContentHandler().
	 *
	 * @deprecated since 1.33 Use getText() for TextContent instances.
	 *  For other content models, use specialized getters.
	 * @since 1.21
	 * @return mixed The native representation of the content. Could be a
	 *  string, nested array structure, object, binary blob, anything really.
	 */
	public function getNativeData();

	/**
	 * Get the content's nominal size in "bogo-bytes".
	 *
	 * @since 1.21
	 * @return int
	 */
	public function getSize();

	/**
	 * Get the content model ID.
	 *
	 * @since 1.21
	 * @return string One of the CONTENT_MODEL_XXX constants.
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
	 * Get the default serialization format.
	 *
	 * Convenience method as shorthand for `$this->getContentHandler()->getDefaultFormat()`.
	 *
	 * @see ContentHandler::getDefaultFormat
	 * @since 1.21
	 * @return string
	 */
	public function getDefaultFormat();

	/**
	 * Get the list of supported serialization formats.
	 *
	 * Shorthand for `$this->getContentHandler()->getSupportedFormats()`.
	 *
	 * @see ContentHandler::getSupportedFormats
	 * @since 1.21
	 * @return string[] List of supported serialization formats
	 */
	public function getSupportedFormats();

	/**
	 * Whether a given format is a supported serialization format for this Content.
	 *
	 * Note that this should always return true if $format is null, because null
	 * stands for the default serialization.
	 *
	 * Shorthand for `$this->getContentHandler()->isSupportedFormat( $format )`.
	 *
	 * @since 1.21
	 * @see ContentHandler::isSupportedFormat
	 * @param string $format The serialization format to check.
	 * @return bool Whether the format is supported
	 */
	public function isSupportedFormat( $format );

	/**
	 * Serialize this Content object.
	 *
	 * Shorthand for $this->getContentHandler()->serializeContent( $this, $format )
	 *
	 * @since 1.21
	 * @see ContentHandler::serializeContent
	 * @param string|null $format The desired serialization format, or null for the default format.
	 * @return string
	 */
	public function serialize( $format = null );

	/**
	 * Whether this Content object is considered empty.
	 *
	 * @since 1.21
	 * @return bool
	 */
	public function isEmpty();

	/**
	 * Whether the content is valid.
	 *
	 * This is intended for local validity checks, not considering global consistency.
	 * Content needs to be valid before it can be saved.
	 *
	 * The default AbstractContent implementation always returns true.
	 *
	 * @since 1.21
	 * @return bool
	 */
	public function isValid();

	/**
	 * Whether this Content object is conceptually equivalent to another one.
	 *
	 * Contract:
	 *
	 * - MUST return false if $that is null.
	 * - MUST return true if `$that === $this`.
	 * - MUST return false if $that does not share the same content model, i.e.
	 *   `$that->getModel() !== $this->getModel()`.
	 * - MUST return false if `get_class( $that ) !== get_class( $this )`.
	 * - MUST return true if `$that->getModel() == $this->getModel()` and the contents
	 *   are considered semantically equivalent according to the data model defined by
	 *   `$this->getModel()`.
	 *
	 * Two Content objects that are equal SHOULD have the same serialization.
	 *
	 * Implementations should be careful to make equals() transitive and reflexive:
	 *
	 * - $a->equals( $b ) <=> $b->equals( $a )
	 * - $a->equals( $b ) &&  $b->equals( $c ) ==> $a->equals( $c )
	 *
	 * This default AbstractContent::equals implementation fulfills the above and relies on
	 * Contenet::serialize() (via AbstractContent::equalsInternal) to determine whether Content
	 * objects are logically equivalent. Subclasses that only need to implement a custom equality
	 * check should consider overriding AbstractContent::equalsInternal().
	 *
	 * @since 1.21
	 * @param Content|null $that The Content object to compare to.
	 * @return bool True if this Content object is equal to $that, false otherwise.
	 */
	public function equals( ?Content $that = null );

	/**
	 * Create a copy of this Content object.
	 *
	 * The following must be true for the returned object, given `$copy = $original->copy()`:
	 * - `get_class( $original ) === get_class( $copy )`
	 * - `$original->getModel() === $copy->getModel()`
	 * - `$original->equals( $copy )`
	 *
	 * If and only if the Content object is immutable, the copy() method can and
	 * should return $this. That is, $copy === $original may be true, but only
	 * for fully immutable value objects.
	 *
	 * @since 1.21
	 * @return Content
	 */
	public function copy();

	/**
	 * Whether this content may count towards a "real" wiki page.
	 *
	 * The only factor not taken into account here is the content location
	 * (i.e. whether the content belongs to a current revision, of an existant page,
	 * in a "content" namespace).
	 *
	 * @since 1.21
	 * @see WikiPage::isCountable
	 * @param bool|null $hasLinks If it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out.
	 * @return bool
	 */
	public function isCountable( $hasLinks = null );

	/**
	 * Get the redirect destination or null if this content doesn't represent a redirect.
	 *
	 * @since 1.21
	 * @return Title|null
	 */
	public function getRedirectTarget();

	/**
	 * Whether this Content represents a redirect.
	 *
	 * Shorthand for getRedirectTarget() !== null.
	 *
	 * @since 1.21
	 * @see SlotRoleHandler::supportsRedirects
	 * @return bool
	 */
	public function isRedirect();

	/**
	 * Create a derived Content with a replaced redirect destination.
	 *
	 * If the content is not already a redirect, this method returns the same
	 * object unchanged. If the content can contain other information besides a
	 * redirect (e.g. WikitextContent::updateRedirect) then that information is
	 * preserved. Otherwise it is effectively the same as creating a new
	 * content object via ContentHandler::makeRedirectContent.
	 *
	 * @since 1.21
	 * @param Title $target The new redirect target
	 * @return Content A new Content object with the updated redirect, or $this
	 *   if it wasn't a redirect already
	 */
	public function updateRedirect( Title $target );

	/**
	 * Create a derived Content for the portion of text in the specified section.
	 *
	 * @since 1.21
	 * @param string|int $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'). The ID "0" retrieves the section before the first heading, "1" the
	 * text between the first heading (included) and the second heading (excluded), etc.
	 * @return Content|false|null The requested section's content, or false if no such section
	 *    exist, or null if sections are not supported for this content model.
	 */
	public function getSection( $sectionId );

	/**
	 * Create a derived Content with the specified section added or replaced.
	 *
	 * @since 1.21
	 * @param string|int|null|false $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1'), null/false or an empty string for the whole page
	 * or 'new' for a new section.
	 * @param Content $with New content of the section
	 * @param string $sectionTitle New section's subject, only if $section is 'new'
	 * @return Content|null New content of the entire page, or null if error
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' );

	/**
	 * Create a derived WikitextContent with a prepended section heading, or return $this.
	 *
	 * If the content model does not support section headings, the same Content object
	 * is returned unmodified. The default AbstractContent implementation returns $this
	 * unmodified, ignoring the section header.
	 *
	 * @since 1.21
	 * @param string $header
	 * @return Content
	 */
	public function addSectionHeader( $header );

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
	 * @return Content|false A content object with the content model $toModel, or false if
	 * that conversion is not supported.
	 */
	public function convert( $toModel, $lossy = '' );

	// @todo ImagePage and CategoryPage interfere with per-content action handlers
	// @todo nice integration of GeSHi syntax highlighting
	//   [11:59] <vvv> Hooks are ugly; make CodeHighlighter interface and a
	//   config to set the class which handles syntax highlighting
	//   [12:00] <vvv> And default it to a DummyHighlighter

}

/** @deprecated class alias since 1.43 */
class_alias( Content::class, 'Content' );
