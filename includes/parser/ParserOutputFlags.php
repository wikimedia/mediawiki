<?php

/**
 * Registry of flags used with ParserOutput::setOutputFlag() within
 * MediaWiki core.
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
 * @since 1.38
 *
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * Registry of flags used with ParserOutput::{get,set}OutputFlag() within
 * MediaWiki core.
 *
 * All flags used should be defined in this class.
 *
 * It is recommended that new flag names in core should begin with 'mw-'
 * in order to prevent namespace conflicts with legacy flags.
 *
 * @package MediaWiki\Parser
 */
enum ParserOutputFlags: string {

	// These flags are currently stored as ParserOutput properties

	/**
	 * Disable magic gallery on category page (__NOGALLERY__).
	 *
	 * This is used to selectively disable the auto-magic thumbnail
	 * gallery on thumbnail pages.
	 *
	 * @see MainConfigSchema::CategoryMagicGallery
	 * @see ParserOutput::getNoGallery()
	 * @see ParserOutput::setNoGallery()
	 */
	case NO_GALLERY = 'mw-NoGallery';

	/**
	 * Whether OOUI should be enabled.
	 * @see ParserOutput::getEnableOOUI()
	 * @see ParserOutput::setEnableOOUI()
	 */
	case ENABLE_OOUI = 'mw-EnableOOUI';

	/**
	 * Force index policy to be 'index'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 */
	case INDEX_POLICY = 'mw-IndexPolicy';

	/**
	 * Force index policy to be 'noindex'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 */
	case NO_INDEX_POLICY = 'mw-NoIndexPolicy';

	/**
	 * Show a new section link?
	 * @see ParserOutput::getNewSection()
	 * @see ParserOutput::setNewSection()
	 */
	case NEW_SECTION = 'mw-NewSection';

	/**
	 * Hide the new section link?
	 * @see ParserOutput::getHideNewSection()
	 * @see ParserOutput::setHideNewSection()
	 */
	case HIDE_NEW_SECTION = 'mw-HideNewSection';

	/**
	 * The prevent-clickjacking flag
	 * @see ParserOutput::getPreventClickjacking()
	 * @see ParserOutput::setPreventClickjacking()
	 */
	case PREVENT_CLICKJACKING = 'mw-PreventClickjacking';

	// These flags are stored in the ParserOutput::$mFlags array

	/**
	 * Show the table of contents in the skin?
	 *
	 * This is a /suggestion/ based on whether the TOC is "large
	 * enough" and other factors, and is intended mostly for skins
	 * which want to match the behavior of the traditional inline ToC.
	 */
	case SHOW_TOC = 'show-toc';

	/**
	 * Suppress the table of contents in the skin?
	 *
	 * This reflects the use of the `__NOTOC__` magic word in the
	 * article (possibly modified by `__TOC__` or `__FORCETOC__`), and
	 * represents an explicit request from the author to hide the TOC.
	 */
	case NO_TOC = 'no-toc';

	/**
	 * Suppress the section edit links?
	 *
	 * This reflects the `ParserOptions::getSuppressSectionEditLinks()`
	 * flag and affects the default value of `enableSectionEditLinks`
	 * in `ParserOutput::getText()`.
	 */
	case NO_SECTION_EDIT_LINKS = 'no-section-edit-links';

	/**
	 * Wrap section contents to allow collapsing them?
	 *
	 * This reflects the ParserOptions::getCollapsibleSections()
	 * flag.
	 */
	case COLLAPSIBLE_SECTIONS = 'collapsible-sections';

	/**
	 * Informs the edit saving system that...
	 */
	case VARY_REVISION = 'vary-revision';

	/**
	 * Similar to VARY_REVISION, but used if we didn't
	 * guess the ID correctly. Informs the edit saving system that
	 * getting the canonical output after revision insertion requires
	 * a parse that used that exact revision ID.
	 */
	case VARY_REVISION_ID = 'vary-revision-id';

	/**
	 * Similar to VARY_REVISION, but used if we didn't
	 * guess the timestamp correctly. Informs the edit saving system
	 * that getting the canonical output after revision insertion
	 * requires a parse that used an actual revision timestamp.
	 */
	case VARY_REVISION_TIMESTAMP = 'vary-revision-timestamp';

	/**
	 * Similar to VARY_REVISION, but used if we didn't guess the
	 * content correctly.
	 */
	case VARY_REVISION_SHA1 = 'vary-revision-sha1';

	/**
	 * Similar to VARY_REVISION
	 */
	case VARY_REVISION_EXISTS = 'vary-revision-exists';

	/**
	 * Similar to VARY_REVISION, but used if we didn't guess the
	 * page id correctly.  Informs the edit saving system that getting the
	 * canonical output after page insertion requires a parse that used that
	 * exact page id.
	 */
	case VARY_PAGE_ID = 'vary-page-id';

	/**
	 * Similar to VARY_REVISION. Informs the edit saving
	 * system that getting the canonical output after revision
	 * insertion requires a parse that used the actual user ID.
	 */
	case VARY_USER = 'vary-user';

	/**
	 * Used to avoid extremely stale user signature timestamps
	 * (T84843). Set if the signature wikitext contains another '~~~~' or
	 * similar (T230652).
	 */
	case USER_SIGNATURE = 'user-signature';

	/**
	 * Set when the parse is done in "preview mode".
	 *
	 * When the parse is done in "previous mode" various shortcuts are
	 * taken to work around the fact that the parsed text does not yet
	 * have an actual revision ID, revision time, etc.
	 * @see ParserOptions::getIsPreview()
	 */
	case IS_PREVIEW = 'is-preview';

	/**
	 * Set if this page contains content which could be
	 * asynchronous, even if the content was "ready" at the time of
	 * the parse.
	 *
	 * This ensures that when the page expires from the
	 * cache and the page is reparsed, RefreshLinksJob will also be
	 * re-run since the content could be different from the last
	 * parse. (T373256)
	 */
	case HAS_ASYNC_CONTENT = 'has-async-content';

	/**
	 * Set if this page contains asynchronous content which
	 * was not ready by the time the output was generated.
	 *
	 * At present this reduces the cache TTL. (T373256)
	 */
	case ASYNC_NOT_READY = 'async-not-ready';

	/**
	 * Return the ParserOutputFlags, as an array of string flag values.
	 * @return list<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}
}
