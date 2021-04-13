<?php

namespace MediaWiki\Page;

use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;

/**
 * Service interface for looking up infermation about wiki pages
 *
 * @since 1.36
 * @unstable
 */
interface PageLookup extends IDBAccessObject {

	/**
	 * Returns the PageIdentity for the given LinkTarget. The page does not have to exist.
	 * Fragments are ignored.
	 *
	 * The LinkTarget must refer to a proper page - that is, it must not be a relative section link,
	 * an interwiki link, or refer to a special page.
	 *
	 * @param LinkTarget $link
	 * @param int $queryFlags
	 *
	 * @throws InvalidArgumentException if $link does not refer to a proper page.
	 * @return ProperPageIdentity
	 */
	public function getPageForLink(
		LinkTarget $link,
		int $queryFlags = self::READ_NORMAL
	): ProperPageIdentity;

	/**
	 * Returns the PageRecord of the given page.
	 *
	 * @param int $pageId
	 * @param int $queryFlags
	 *
	 * @throws InvalidArgumentException if $pageId is 0 or negative.
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 */
	public function getPageById(
		int $pageId,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord;

	/**
	 * Returns the PageRecord for the given name and namespace.
	 *
	 * @param int $namespace
	 * @param string $dbKey
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 * @throws InvalidArgumentException if $namespace is negative or $dbKey is empty.
	 */
	public function getPageByName(
		int $namespace,
		string $dbKey,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord;

	/**
	 * Returns the PageRecord of the given page.
	 * May return $page if that already is a PageRecord.
	 *
	 * @param PageIdentity $page
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 */
	public function getPageByIdentity(
		PageIdentity $page,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord;

}
