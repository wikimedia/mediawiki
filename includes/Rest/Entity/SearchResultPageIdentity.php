<?php
namespace MediaWiki\Rest\Entity;

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
	 * At the moment it is equivalent to Title::getArticleID()
	 * @see Title::getArticleID()
	 *
	 * @return int
	 */
	public function getId(): int;

	/**
	 * Returns the page's namespace number.
	 * At the moment it is equivalent to Title::getNamespace()
	 * @see Title::getNamespace()
	 *
	 * @return int
	 */
	public function getNamespace(): int;

	/**
	 * Get the page title in DB key form.
	 * At the moment it is equivalent to Title::getDBkey()
	 * @see Title::getDBkey()
	 *
	 * @return string
	 */
	public function getDBkey(): string;
}
