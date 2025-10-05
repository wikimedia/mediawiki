<?php

/**
 * Registry of flags used with ParserOutput::setOutputFlag() within
 * MediaWiki core.
 *
 * @license GPL-2.0-or-later
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
 * @since 1.38
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
	 * @since 1.38
	 */
	case NO_GALLERY = 'mw-NoGallery';

	/**
	 * Whether OOUI should be enabled.
	 * @see ParserOutput::getEnableOOUI()
	 * @see ParserOutput::setEnableOOUI()
	 * @since 1.38
	 */
	case ENABLE_OOUI = 'mw-EnableOOUI';

	/**
	 * Force index policy to be 'index'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 * @since 1.38
	 */
	case INDEX_POLICY = 'mw-IndexPolicy';

	/**
	 * Force index policy to be 'noindex'.
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 * @since 1.38
	 */
	case NO_INDEX_POLICY = 'mw-NoIndexPolicy';

	/**
	 * Show a new section link?
	 * @see ParserOutput::getNewSection()
	 * @see ParserOutput::setNewSection()
	 * @since 1.38
	 */
	case NEW_SECTION = 'mw-NewSection';

	/**
	 * Hide the new section link?
	 * @see ParserOutput::getHideNewSection()
	 * @see ParserOutput::setHideNewSection()
	 * @since 1.38
	 */
	case HIDE_NEW_SECTION = 'mw-HideNewSection';

	/**
	 * The prevent-clickjacking flag.
	 * If true, we emit X-Frame-Options: DENY.
	 * This controls if anti-clickjacking / frame-breaking headers will
	 * be sent. This should be done for pages where edit actions are possible.
	 * @see ParserOutput::getPreventClickjacking()
	 * @see ParserOutput::setPreventClickjacking()
	 * @since 1.38
	 */
	case PREVENT_CLICKJACKING = 'mw-PreventClickjacking';

	// These flags are stored in the ParserOutput::$mFlags array

	/**
	 * Show the table of contents in the skin?
	 *
	 * This is a /suggestion/ based on whether the TOC is "large
	 * enough" and other factors, and is intended mostly for skins
	 * which want to match the behavior of the traditional inline ToC.
	 * @since 1.39
	 */
	case SHOW_TOC = 'show-toc';

	/**
	 * Suppress the table of contents in the skin?
	 *
	 * This reflects the use of the `__NOTOC__` magic word in the
	 * article (possibly modified by `__TOC__` or `__FORCETOC__`), and
	 * represents an explicit request from the author to hide the TOC.
	 * @since 1.41
	 */
	case NO_TOC = 'no-toc';

	/**
	 * Suppress the section edit links?
	 *
	 * This reflects the `ParserOptions::getSuppressSectionEditLinks()`
	 * flag and affects the default value of `enableSectionEditLinks`
	 * in `ParserOutput::getText()`.
	 * @since 1.42
	 */
	case NO_SECTION_EDIT_LINKS = 'no-section-edit-links';

	/**
	 * Wrap section contents to allow collapsing them?
	 *
	 * This reflects the ParserOptions::getCollapsibleSections()
	 * flag.
	 * @since 1.43
	 */
	case COLLAPSIBLE_SECTIONS = 'collapsible-sections';

	// See RenderedRevision::outputVariesOnRevisionMetadata for the
	// following flags.

	/**
	 * Informs the edit saving system that the canonical output for
	 * this page may change after revision insertion; for instance
	 * if it uses some properties of the page itself (like categories)
	 * which will not be updated until the RefreshLinksJob is run after
	 * the revision is saved.
	 * @since 1.38
	 */
	case VARY_REVISION = 'vary-revision';

	/**
	 * Similar to VARY_REVISION, but used if we didn't
	 * guess the ID correctly. Informs the edit saving system that
	 * getting the canonical output after revision insertion requires
	 * a parse that used that exact revision ID, for instance if the
	 * page used {{REVISIONID}} to fetch its own revision ID.
	 * @since 1.38
	 */
	case VARY_REVISION_ID = 'vary-revision-id';

	/**
	 * Similar to VARY_REVISION_ID, but used if we didn't
	 * guess the timestamp correctly. Informs the edit saving system
	 * that getting the canonical output after revision insertion
	 * requires a parse that used an actual revision timestamp.
	 * @since 1.38
	 */
	case VARY_REVISION_TIMESTAMP = 'vary-revision-timestamp';

	/**
	 * Similar to VARY_REVISION, but used if we didn't guess the
	 * content correctly.  For example, a self-transclusion will
	 * set this flag, since the lookup of the transcluded content
	 * (itself) will be stale until the new revision of this page
	 * is actually stored in the DB.
	 * @since 1.38
	 */
	case VARY_REVISION_SHA1 = 'vary-revision-sha1';

	/**
	 * Similar to VARY_REVISION, but used if the output will change
	 * once this page exists in the database.
	 * @since 1.38
	 */
	case VARY_REVISION_EXISTS = 'vary-revision-exists';

	/**
	 * Similar to VARY_REVISION, but used if we didn't guess the
	 * page id correctly.  Informs the edit saving system that getting the
	 * canonical output after page insertion requires a parse that used that
	 * exact page id.
	 * @since 1.38
	 */
	case VARY_PAGE_ID = 'vary-page-id';

	/**
	 * Similar to VARY_REVISION. Informs the edit saving
	 * system that getting the canonical output after revision
	 * insertion requires a parse that used the actual user ID.
	 * @since 1.38
	 */
	case VARY_USER = 'vary-user';

	/**
	 * Used to avoid extremely stale user signature timestamps
	 * (T84843). Set if the signature wikitext contains another '~~~~' or
	 * similar (T230652).
	 * @since 1.38
	 */
	case USER_SIGNATURE = 'user-signature';

	/**
	 * Set when the parse is done in "preview mode".
	 *
	 * When the parse is done in "previous mode" various shortcuts are
	 * taken to work around the fact that the parsed text does not yet
	 * have an actual revision ID, revision time, etc.
	 * @see ParserOptions::getIsPreview()
	 * @since 1.42
	 */
	case IS_PREVIEW = 'is-preview';

	/**
	 * Set when the parse was done with parsoid; clear if the
	 * parse was done with the legacy parser (or unknown).
	 *
	 * @see ParserOptions::getUseParsoid()
	 * @since 1.45
	 */
	case USE_PARSOID = 'use-parsoid';

	/**
	 * Set if this page contains content which could be
	 * asynchronous, even if the content was "ready" at the time of
	 * the parse.
	 *
	 * This ensures that when the page expires from the
	 * cache and the page is reparsed, RefreshLinksJob will also be
	 * re-run since the content could be different from the last
	 * parse. (T373256)
	 * @since 1.44
	 */
	case HAS_ASYNC_CONTENT = 'has-async-content';

	/**
	 * Set if this page contains asynchronous content which
	 * was not ready by the time the output was generated.
	 *
	 * At present this reduces the cache TTL. (T373256)
	 * @since 1.44
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
