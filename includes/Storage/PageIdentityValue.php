<?php

namespace MediaWiki\Storage;

/**
 * Representation of a page title within MediaWiki.
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
 * @file
 * @author Daniel Kinzler
 */

use TitleValue;
use Wikimedia\Assert\Assert;

/**
 * Represents a page within a MediaWiki database.
 *
 * @note In contrast to Title, this is designed to be a plain value object. That is,
 * it is immutable, does not use global state, and causes no side effects.
 *
 * @see https://www.mediawiki.org/wiki/Requests_for_comment/TitleValue
 * @since 1.31
 */
class PageIdentityValue extends TitleValue implements PageIdentity {

	/**
	 * @var int|false
	 */
	private $pageID;

	/**
	 * @var string|false
	 */
	private $domainID;

	/**
	 * PageIdentityValue constructor.
	 *
	 * @param int $namespaceId
	 * @param string $dbKey
	 * @param false|int $pageID
	 * @param false|string $domainID
	 */
	public function __construct( $namespaceId, $dbKey, $pageID, $domainID = false ) {
		parent::__construct( $namespaceId, $dbKey );

		Assert::parameterType( 'bool|int', $pageID, '$pageID' );
		Assert::parameterType( 'bool|string', $domainID, '$domainID' );

		$this->pageID = $pageID;
		$this->domainID = $domainID;
	}

	/**
	 * Get the domain ID of the wiki database this PageIdentity belongs to.
	 *
	 * For interwiki links that are not bound to a database domain, this wil return UNKNOWN_DOMAIN.
	 * For the local database, it may return false, for historical reasons.
	 *
	 * @see IDatabase::getDomainID()
	 *
	 * @return string|false the wiki's domain ID, or false if it belongs to the local wiki's
	 * database.
	 */
	public function getDomainID() {
		return $this->domainID;
	}

	/**
	 * Get the page ID, if it exist.
	 *
	 * @return int|false the page's ID in the page table, or 0 if the page does not (but could)
	 *         exist in the database indicated by getDomainID(), or false if this page cannot be
	 *         created in the database indicated by getDomainID().
	 */
	public function getPageID() {
		return $this->pageID;
	}

	/**
	 * Can this page be created in the database indicated by getDomainId().
	 *
	 * This will always return false for SpecialPages and interwiki links that
	 * other titles that can exist as link targets, but cannot be written into a known database.
	 *
	 * @return bool
	 */
	public function canExist() {
		return is_int( $this->pageID );
	}

}
