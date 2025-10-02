<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Page;

use Wikimedia\Assert\PreconditionException;

/**
 * Interface for a page that is (or could be, or used to be) an editable wiki page.
 *
 * @note In contrast to PageIdentity, a ProperPageIdentity is guaranteed to
 *       represent an actual editable (or creatable) page. Eventually,
 *       PageIdentity is intended to adopt the same contract. At that point,
 *       ProperPageIdentity may become an alias for PageIdentity.
 *
 * @note For compatibility with the WikiPage class, ProperPageIdentity instances
 *       may be mutable, and return different values from methods such as getId() or exist()
 *       at different times. In the future, the contract of this interface is intended
 *       to be changed to disallow this.
 *
 * @see https://www.mediawiki.org/wiki/Manual:Modeling_pages
 *
 * @stable to type
 *
 * @since 1.36
 */
interface ProperPageIdentity extends PageIdentity {

	/**
	 * Get the ID of the wiki this page belongs to.
	 *
	 * @see RevisionRecord::getWikiId()
	 *
	 * @return string|false The wiki's logical name, of false to indicate the local wiki.
	 */
	public function getWikiId();

	/**
	 * Returns the page ID.
	 * Will return 0 if the page does not currently exist.
	 *
	 * @param string|false $wikiId Must be provided when accessing the ID of a non-local
	 *        PageIdentity, to prevent data corruption when using a PageIdentity belonging
	 *        to one wiki in the context of another. Should be omitted if expecting the local wiki.
	 * @throws PreconditionException if this PageIdentity does not belong to the wiki
	 *         identified by $wikiId.
	 *
	 * @return int
	 */
	public function getId( $wikiId = self::LOCAL ): int;

	/**
	 * Get the page title in DB key form.
	 *
	 * This should always return a valid DB key.
	 *
	 * @return string
	 */
	public function getDBkey(): string;

	/**
	 * Always true.
	 * Implementations must ensure that no "improper" instances can be created.
	 *
	 * @return true
	 */
	public function canExist(): bool;

}
