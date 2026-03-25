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
 * Note that in order to provide forward compatibility with "new" flag
 * values, any additions to ParserOutputFlags should be backported to
 * all currently-active release branches before those flags are written
 * into the parser cache.
 *
 * @package MediaWiki\Parser
 * @since 1.38
 */
class ParserOutputFlags {

	// See note above about backporting any new flags added to this
	// enumeration.

	/**
	 * @var string Disable magic gallery on category page (__NOGALLERY__).
	 *
	 * This is used to selectively disable the auto-magic thumbnail
	 * gallery on thumbnail pages.
	 *
	 * @see MainConfigSchema::CategoryMagicGallery
	 * @since 1.38
	 */
	public const NO_GALLERY = 'mw-NoGallery';

	/**
	 * @var string Whether OOUI should be enabled.  If set, OOUI is enabled in
	 * any OutputPage instance the ParserOutput containing this flag
	 * is added to.
	 * @since 1.38
	 */
	public const ENABLE_OOUI = 'mw-EnableOOUI';

	/**
	 * @var string Force index policy to be 'index'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 * @since 1.38
	 */
	public const INDEX_POLICY = 'mw-IndexPolicy';

	/**
	 * @var string Force index policy to be 'noindex'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 * @since 1.38
	 */
	public const NO_INDEX_POLICY = 'mw-NoIndexPolicy';

	/**
	 * @var string Show a new section link?
	 * @since 1.38
	 */
	public const NEW_SECTION = 'mw-NewSection';

	/**
	 * @var string Hide the new section link?
	 * @since 1.38
	 */
	public const HIDE_NEW_SECTION = 'mw-HideNewSection';

	/**
	 * @var string The prevent-clickjacking flag.
	 *
	 * If true, we emit an `X-Frame-Options` header appropriate for
	 * edit pages.  The exact header value is controlled by
	 * `$wgEditPageFrameOptions`.  This controls if anti-clickjacking
	 * / frame-breaking headers will be sent.  This flag is set by
	 * default for special pages.  For other pages where a
	 * CSRF-protect form is displayed (for example where edit actions
	 * are possible), you need to set this flag.
	 *
	 * @since 1.38
	 */
	public const PREVENT_CLICKJACKING = 'mw-PreventClickjacking';

	/**
	 * @var string Show the table of contents in the skin?
	 *
	 * This is a /suggestion/ based on whether the TOC is "large
	 * enough" and other factors, and is intended mostly for skins
	 * which want to match the behavior of the traditional inline ToC.
	 * @since 1.39
	 */
	public const SHOW_TOC = 'show-toc';

	/**
	 * @var string Suppress the table of contents in the skin?
	 *
	 * This reflects the use of the `__NOTOC__` magic word in the
	 * article (possibly modified by `__TOC__` or `__FORCETOC__`), and
	 * represents an explicit request from the author to hide the TOC.
	 * @since 1.41
	 */
	public const NO_TOC = 'no-toc';

	/**
	 * @var string Suppress the section edit links?
	 *
	 * This reflects the `ParserOptions::getSuppressSectionEditLinks()`
	 * flag and affects the default value of `enableSectionEditLinks`
	 * in `ParserOutput::getText()`.
	 * @since 1.42
	 */
	public const NO_SECTION_EDIT_LINKS = 'no-section-edit-links';

	/**
	 * @var string Wrap section contents to allow collapsing them?
	 *
	 * This reflects the ParserOptions::getCollapsibleSections()
	 * flag.
	 * @since 1.43
	 */
	public const COLLAPSIBLE_SECTIONS = 'collapsible-sections';

	// See RenderedRevision::outputVariesOnRevisionMetadata for the
	// VARY_* flags.

	/**
	 * @var string Informs the edit saving system that the canonical output for
	 * this page may change after revision insertion; for instance
	 * if it uses some properties of the page itself (like categories)
	 * which will not be updated until the RefreshLinksJob is run after
	 * the revision is saved.
	 * @since 1.38
	 */
	public const VARY_REVISION = 'vary-revision';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't
	 * guess the ID correctly. Informs the edit saving system that
	 * getting the canonical output after revision insertion requires
	 * a parse that used that exact revision ID, for instance if the
	 * page used {{REVISIONID}} to fetch its own revision ID.
	 * @since 1.38
	 */
	public const VARY_REVISION_ID = 'vary-revision-id';

	/**
	 * @var string Similar to VARY_REVISION_ID, but used if we didn't
	 * guess the timestamp correctly. Informs the edit saving system
	 * that getting the canonical output after revision insertion
	 * requires a parse that used an actual revision timestamp.
	 * @since 1.38
	 */
	public const VARY_REVISION_TIMESTAMP = 'vary-revision-timestamp';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't guess the
	 * content correctly.  For example, a self-transclusion will
	 * set this flag, since the lookup of the transcluded content
	 * (itself) will be stale until the new revision of this page
	 * is actually stored in the DB.
	 * @since 1.38
	 */
	public const VARY_REVISION_SHA1 = 'vary-revision-sha1';

	/**
	 * @var string Similar to VARY_REVISION, but used if the output will change
	 * once this page exists in the database.
	 * @since 1.38
	 */
	public const VARY_REVISION_EXISTS = 'vary-revision-exists';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't guess the
	 * page id correctly.  Informs the edit saving system that getting the
	 * canonical output after page insertion requires a parse that used that
	 * exact page id.
	 * @since 1.38
	 */
	public const VARY_PAGE_ID = 'vary-page-id';

	/**
	 * @var string Similar to VARY_REVISION. Informs the edit saving
	 * system that getting the canonical output after revision
	 * insertion requires a parse that used the actual user ID.
	 * @since 1.38
	 */
	public const VARY_USER = 'vary-user';

	/**
	 * @var string Used to avoid extremely stale user signature timestamps
	 * (T84843). Set if the signature wikitext contains another '~~~~' or
	 * similar (T230652).
	 * @since 1.38
	 */
	public const USER_SIGNATURE = 'user-signature';

	/**
	 * @var string Set when the parse is done in "preview mode".
	 *
	 * When the parse is done in "previous mode" various shortcuts are
	 * taken to work around the fact that the parsed text does not yet
	 * have an actual revision ID, revision time, etc.
	 * @see ParserOptions::getIsPreview()
	 * @since 1.42
	 */
	public const IS_PREVIEW = 'is-preview';

	/**
	 * @var string Set when the parse was done with parsoid; clear if the
	 * parse was done with the legacy parser (or unknown).
	 *
	 * @see ParserOptions::getUseParsoid()
	 * @since 1.45
	 */
	public const USE_PARSOID = 'use-parsoid';

	/**
	 * @var string Set if this page contains content which could be
	 * asynchronous, even if the content was "ready" at the time of
	 * the parse.
	 *
	 * This ensures that when the page expires from the
	 * cache and the page is reparsed, RefreshLinksJob will also be
	 * re-run since the content could be different from the last
	 * parse. (T373256)
	 * @since 1.44
	 */
	public const HAS_ASYNC_CONTENT = 'has-async-content';

	/**
	 * @var string Set if this page contains asynchronous content which
	 * was not ready by the time the output was generated.
	 *
	 * At present this reduces the cache TTL. (T373256)
	 * @since 1.44
	 */
	public const ASYNC_NOT_READY = 'async-not-ready';

	/**
	 * @var string Set if this page is unsafe for selective update.
	 *
	 * Generally this means a resource limit was reached, and so this
	 * page does not contain a full representation of the input wikitext.
	 * An update might resolve the excess resource use, but it wouldn't
	 * be able to replace the content that was lost when the limit
	 * was reached. (Duplicate or conflicting definitions constitute
	 * another form of "resource limit", since uniqueness limits us to
	 * only one of a given item.)
	 *
	 * Triggering other error conditions or the use of other unsafe features
	 * may also set this flag.
	 * @since 1.46
	 */
	public const PREVENT_SELECTIVE_UPDATE = 'prevent-selective-update';

	/**
	 * @var string Set if this page contains header placeholders (T200915).
	 * @since 1.46
	 */
	public const HAS_SLOT_HEADERS = 'has-slot-headers';

	public static function cases(): array {
		return [
			self::NO_GALLERY,
			self::ENABLE_OOUI,
			self::INDEX_POLICY,
			self::NO_INDEX_POLICY,
			self::NEW_SECTION,
			self::HIDE_NEW_SECTION,
			self::SHOW_TOC,
			self::NO_TOC,
			self::NO_SECTION_EDIT_LINKS,
			self::COLLAPSIBLE_SECTIONS,
			self::PREVENT_CLICKJACKING,
			self::VARY_REVISION,
			self::VARY_REVISION_ID,
			self::VARY_REVISION_TIMESTAMP,
			self::VARY_REVISION_SHA1,
			self::VARY_REVISION_EXISTS,
			self::VARY_PAGE_ID,
			self::VARY_USER,
			self::USER_SIGNATURE,
			self::IS_PREVIEW,
			self::USE_PARSOID,
			self::HAS_ASYNC_CONTENT,
			self::ASYNC_NOT_READY,
			self::PREVENT_SELECTIVE_UPDATE,
			self::HAS_SLOT_HEADERS,
		];
	}
}
