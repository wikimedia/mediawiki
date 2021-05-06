<?php
namespace MediaWiki\Rest\Entity;

use MediaWiki\Page\PageIdentity;

/**
 * Lightweight interface representing a page identity
 *
 * @unstable
 * @note This interface is temorary solution. It will be replaced by the one from:
 * https://phabricator.wikimedia.org/T208776
 */
interface SearchResultPageIdentity {
	/**
	 * The numerical page ID.
	 * At the moment it is equivalent to PageIdentity::getId()
	 * @see PageIdentity::getId()
	 *
	 * @return int
	 */
	public function getId(): int;

	/**
	 * Returns the page's namespace number.
	 * At the moment it is equivalent to PageIdentity::getNamespace()
	 * @see PageIdentity::getNamespace()
	 *
	 * @return int
	 */
	public function getNamespace(): int;

	/**
	 * Get the page title in DB key form.
	 * At the moment it is equivalent to PageIdentity::getDBkey()
	 * @see PageIdentity::getDBkey()
	 *
	 * @return string
	 */
	public function getDBkey(): string;
}
