<?php

namespace MediaWiki\Page;

use IDBAccessObject;
use InvalidArgumentException;
use MediaWiki\Linker\LinkTarget;

/**
 * Service for looking up information about wiki pages.
 *
 * Default implementation is PageStore.
 *
 * @since 1.36
 * @ingroup Page
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
	 * Returns a PageIdentity for a given user provided page name text.
	 * Returns null if the title is not a valid name of a proper page,
	 * e.g if it is a special page, an interwiki link, a relative section line, or simply invalid.
	 *
	 * @since 1.37
	 *
	 * @param string $text
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 * @param int $queryFlags
	 *
	 * @return ProperPageIdentity|null
	 */
	public function getPageByText(
		string $text,
		int $defaultNamespace = NS_MAIN,
		int $queryFlags = self::READ_NORMAL
	): ?ProperPageIdentity;

	/**
	 * Returns an ExistingPageRecord for a given user provided page name text.
	 *
	 * Returns null if the page does not exist or if title is not a valid name of a proper page,
	 * e.g if it is a special page, an interwiki link, a relative section line, or simply invalid.
	 *
	 * @since 1.37
	 *
	 * @param string $text
	 * @param int $defaultNamespace Namespace to assume per default (usually NS_MAIN)
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null
	 */
	public function getExistingPageByText(
		string $text,
		int $defaultNamespace = NS_MAIN,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord;

	/**
	 * Returns the PageRecord of the given page.
	 * May return $page if that already is a PageRecord.
	 * If $page is a PageIdentity, implementations may call methods like exists() and getId() on it.
	 *
	 * The PageReference must refer to a proper page - that is, it must not refer to a special page.
	 *
	 * @param PageReference $page
	 * @param int $queryFlags
	 *
	 * @return ExistingPageRecord|null The page's PageRecord, or null if the page was not found.
	 * @throws InvalidArgumentException if $page does not refer to a proper page.
	 */
	public function getPageByReference(
		PageReference $page,
		int $queryFlags = self::READ_NORMAL
	): ?ExistingPageRecord;

}
