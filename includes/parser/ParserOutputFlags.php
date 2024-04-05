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
class ParserOutputFlags {

	// These flags are currently stored as ParserOutput properties

	/**
	 * @var string No gallery on category page? (__NOGALLERY__).
	 * @see ParserOutput::getNoGallery()
	 * @see ParserOutput::setNoGallery()
	 */
	public const NO_GALLERY = 'mw-NoGallery';

	/**
	 * @var string Whether OOUI should be enabled.
	 * @see ParserOutput::getEnableOOUI()
	 * @see ParserOutput::setEnableOOUI()
	 */
	public const ENABLE_OOUI = 'mw-EnableOOUI';

	/**
	 * @var string Force index policy to be 'index'
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 */
	public const INDEX_POLICY = 'mw-IndexPolicy';

	/**
	 * @var string Force index policy to be 'noindex'
	 * @see ParserOutput::getIndexPolicy()
	 * @see ParserOutput::setIndexPolicy()
	 */
	public const NO_INDEX_POLICY = 'mw-NoIndexPolicy';

	/**
	 * @var string Show a new section link?
	 * @see ParserOutput::getNewSection()
	 * @see ParserOutput::setNewSection()
	 */
	public const NEW_SECTION = 'mw-NewSection';

	/**
	 * @var string Hide the new section link?
	 * @see ParserOutput::getHideNewSection()
	 * @see ParserOutput::setHideNewSection()
	 */
	public const HIDE_NEW_SECTION = 'mw-HideNewSection';

	/**
	 * @var string The prevent-clickjacking flag
	 * @see ParserOutput::getPreventClickjacking()
	 * @see ParserOutput::setPreventClickjacking()
	 */
	public const PREVENT_CLICKJACKING = 'mw-PreventClickjacking';

	// These flags are stored in the ParserOutput::$mFlags array

	/**
	 * @var string Show the table of contents in the skin?  This is
	 *  a /suggestion/ based on whether the TOC is "large enough"
	 *  and other factors, and is intended mostly for skins which
	 *  want to match the behavior of the traditional inline ToC.
	 */
	public const SHOW_TOC = 'show-toc';

	/**
	 * @var string Suppress the table of contents in the skin?
	 *  This reflects the use of the __NOTOC__ magic word in the
	 *  article (possibly modified by __TOC__ or __FORCETOC__),
	 *  and represents an explicit request from the author to
	 *  hide the TOC.
	 */
	public const NO_TOC = 'no-toc';

	/**
	 * @var string Suppress the section edit links?
	 *  This reflects the ParserOptions::getSuppressSectionEditLinks()
	 *  flag and affects the default value of `enableSectionEditLinks`
	 *  in ParserOutput::getText().
	 */
	public const NO_SECTION_EDIT_LINKS = 'no-section-edit-links';

	/**
	 * @var string Wrap section contents to allow collapsing them?
	 * This reflects the ParserOptions::getCollapsibleSections()
	 * flag.
	 */
	public const COLLAPSIBLE_SECTIONS = 'collapsible-sections';

	/**
	 * @var string
	 */
	public const VARY_REVISION = 'vary-revision';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't
	 * guess the ID correctly. Informs the edit saving system that
	 * getting the canonical output after revision insertion requires
	 * a parse that used that exact revision ID.
	 */
	public const VARY_REVISION_ID = 'vary-revision-id';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't
	 * guess the timestamp correctly. Informs the edit saving system
	 * that getting the canonical output after revision insertion
	 * requires a parse that used an actual revision timestamp.
	 */
	public const VARY_REVISION_TIMESTAMP = 'vary-revision-timestamp';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't guess the
	 * content correctly.
	 */
	public const VARY_REVISION_SHA1 = 'vary-revision-sha1';

	/**
	 * @var string Similar to VARY_REVISION
	 */
	public const VARY_REVISION_EXISTS = 'vary-revision-exists';

	/**
	 * @var string Similar to VARY_REVISION, but used if we didn't guess the
	 * page id correctly.  Informs the edit saving system that getting the
	 * canonical output after page insertion requires a parse that used that
	 * exact page id.
	 */
	public const VARY_PAGE_ID = 'vary-page-id';

	/**
	 * @var string Similar to VARY_REVISION. Informs the edit saving
	 * system that getting the canonical output after revision
	 * insertion requires a parse that used the actual user ID.
	 */
	public const VARY_USER = 'vary-user';

	/**
	 * @var string Used to avoid extremely stale user signature timestamps
	 * (T84843). Set if the signature wikitext contains another '~~~~' or
	 * similar (T230652).
	 */
	public const USER_SIGNATURE = 'user-signature';

	/**
	 * @var string Set when the parse is done in "preview mode", in which
	 * case various shortcuts are taken to work around the fact that the
	 * parsed text does not yet have an actual revision ID, revision time,
	 * etc.
	 * @see ParserOptions::getIsPreview()
	 */
	public const IS_PREVIEW = 'is-preview';

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
		];
	}
}
