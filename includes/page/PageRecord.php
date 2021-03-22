<?php
namespace MediaWiki\Page;

/**
 * Data record representing a page that is (or used to be, or could be)
 * an editable page on a wiki.
 *
 * @note For compatibility with the WikiPage class, PageIdentity instances may
 *   represent non-existing pages. In the future, the contract of this interface is intended
 *   to be changed to disallow this.
 *
 * @note For compatibility with the WikiPage class, PageIdentity instances may
 *   be mutable, and return different values from methods such as getLatest() or isRedirect()
 *   at different times. In the future, the contract of this interface is intended
 *   to be changed to disallow this.
 *
 * @note Only WikiPage should implement PageRecord directly, other implementations should use
 *   ExistingPageRecord instead. Once WikiPage is removed or guaranteed to be immutable and
 *   existing, ExistingPageRecord will become an alias of PageRecord.
 *
 * @todo In the future, PageRecord should extend ProperPageIdentity. This will only
 *   become possible when WikiPage can no longer represent non-proper pages.
 *
 * @stable to type
 *
 * @since 1.36
 */
interface PageRecord extends PageIdentity {

	/**
	 * False if the page has had more than one edit.
	 *
	 * @warning this is not guaranteed to always return true for old pages that have only one edit.
	 *
	 * @return bool
	 */
	public function isNew();

	/**
	 * True if the page is a redirect.
	 *
	 * @return bool
	 */
	public function isRedirect();

	/**
	 * The ID of the page's latest revision.
	 *
	 * @param string|false $wikiId Must be provided when accessing the ID of the latest revision of
	 *        a non-local PageRecord, to prevent data corruption when using a PageRecord belonging
	 *        to one wiki in the context of another. Should be omitted if expecting the local wiki.
	 *
	 * @return int The revision ID of the page's latest revision, or 0 if the page does not exist.
	 */
	public function getLatest( $wikiId = self::LOCAL );

	/**
	 * Timestamp at which the page was last flagged for rerendering.
	 *
	 * @return string
	 */
	public function getTouched();

	/**
	 * The page's language.
	 *
	 * @return string
	 */
	public function getLanguage();
}
