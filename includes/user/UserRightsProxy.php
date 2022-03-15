<?php
/**
 * Representation of an user on a other locally-hosted wiki.
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
 */

use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;

/**
 * Cut-down copy of User interface for local-interwiki-database
 * user rights manipulation.
 * @deprecated since 1.38, pass the correct domain to UserGroupManagerFactory instead.
 */
class UserRightsProxy implements UserIdentity {
	use WikiAwareEntityTrait;

	/** @var IDatabase */
	private $db;
	/** @var string */
	private $dbDomain;
	/** @var string */
	private $name;
	/** @var int */
	private $id;
	/** @var array */
	private $newOptions;
	/** @var UserGroupManager */
	private $userGroupManager;

	/**
	 * @see newFromId()
	 * @see newFromName()
	 * @param IDatabase $db Db connection
	 * @param string $dbDomain Database name
	 * @param string $name User name
	 * @param int $id User ID
	 */
	private function __construct( $db, $dbDomain, $name, $id ) {
		$this->db = $db;
		$this->dbDomain = $dbDomain;
		$this->name = $name;
		$this->id = intval( $id );
		$this->newOptions = [];
		$this->userGroupManager = MediaWikiServices::getInstance()
			->getUserGroupManagerFactory()
			->getUserGroupManager( $dbDomain );
	}

	/**
	 * Confirm the selected database name is a valid local interwiki database name.
	 *
	 * @param string $dbDomain Database name
	 * @return bool
	 */
	public static function validDatabase( $dbDomain ) {
		$localDatabases = MediaWikiServices::getInstance()->getMainConfig()->get( 'LocalDatabases' );
		return in_array( $dbDomain, $localDatabases );
	}

	/**
	 * Same as User::whoIs()
	 *
	 * @param string $dbDomain Database name
	 * @param int $id User ID
	 * @param bool $ignoreInvalidDB If true, don't check if $dbDomain is in $wgLocalDatabases
	 * @return string|false User name or false if the user doesn't exist
	 */
	public static function whoIs( $dbDomain, $id, $ignoreInvalidDB = false ) {
		$user = self::newFromId( $dbDomain, $id, $ignoreInvalidDB );
		if ( $user ) {
			return $user->name;
		} else {
			return false;
		}
	}

	/**
	 * Factory function; get a remote user entry by ID number.
	 *
	 * @param string $dbDomain Database name
	 * @param int $id User ID
	 * @param bool $ignoreInvalidDB If true, don't check if $dbDomain is in $wgLocalDatabases
	 * @return UserRightsProxy|null If doesn't exist
	 */
	public static function newFromId( $dbDomain, $id, $ignoreInvalidDB = false ) {
		return self::newFromLookup( $dbDomain, 'user_id', intval( $id ), $ignoreInvalidDB );
	}

	/**
	 * Factory function; get a remote user entry by name.
	 *
	 * @param string $dbDomain Database name
	 * @param string $name User name
	 * @param bool $ignoreInvalidDB If true, don't check if $dbDomain is in $wgLocalDatabases
	 * @return UserRightsProxy|null If doesn't exist
	 */
	public static function newFromName( $dbDomain, $name, $ignoreInvalidDB = false ) {
		return self::newFromLookup( $dbDomain, 'user_name', $name, $ignoreInvalidDB );
	}

	/**
	 * @param string $dbDomain
	 * @param string $field
	 * @param string|int $value
	 * @param bool $ignoreInvalidDB
	 * @return null|UserRightsProxy
	 */
	private static function newFromLookup( $dbDomain, $field, $value, $ignoreInvalidDB = false ) {
		$sharedDB = MediaWikiServices::getInstance()->getMainConfig()->get( 'SharedDB' );
		$sharedTables = MediaWikiServices::getInstance()->getMainConfig()->get( 'SharedTables' );
		// If the user table is shared, perform the user query on it,
		// but don't pass it to the UserRightsProxy,
		// as user rights are normally not shared.
		if ( $sharedDB && in_array( 'user', $sharedTables ) ) {
			$userdb = self::getDB( $sharedDB, $ignoreInvalidDB );
		} else {
			$userdb = self::getDB( $dbDomain, $ignoreInvalidDB );
		}

		$db = self::getDB( $dbDomain, $ignoreInvalidDB );

		if ( $db && $userdb ) {
			$row = $userdb->selectRow( 'user',
				[ 'user_id', 'user_name' ],
				[ $field => $value ],
				__METHOD__ );

			if ( $row !== false ) {
				return new UserRightsProxy(
					$db, $dbDomain, $row->user_name, intval( $row->user_id ) );
			}
		}
		return null;
	}

	/**
	 * Open a database connection to work on for the requested user.
	 * This may be a new connection to another database for remote users.
	 *
	 * @param string $dbDomain
	 * @param bool $ignoreInvalidDB If true, don't check if $dbDomain is in $wgLocalDatabases
	 * @return IDatabase|null If invalid selection
	 */
	public static function getDB( $dbDomain, $ignoreInvalidDB = false ) {
		if ( $ignoreInvalidDB || self::validDatabase( $dbDomain ) ) {
			if ( WikiMap::isCurrentWikiId( $dbDomain ) ) {
				// Hmm... this shouldn't happen though. :)
				return wfGetDB( DB_PRIMARY );
			} else {
				return wfGetDB( DB_PRIMARY, [], $dbDomain );
			}
		}
		return null;
	}

	/**
	 * @param string|false $wikiId
	 * @return int
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function isAnon(): bool {
		return !$this->isRegistered();
	}

	/**
	 * Same as User::getName()
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name . '@' . $this->dbDomain;
	}

	/**
	 * Same as User::getUserPage()
	 *
	 * @return Title
	 */
	public function getUserPage() {
		return Title::makeTitle( NS_USER, $this->getName() );
	}

	/**
	 * Replaces User::getUserGroups()
	 * @return string[]
	 */
	public function getGroups() {
		return array_keys( self::getGroupMemberships() );
	}

	/**
	 * Replaces User::getGroupMemberships()
	 *
	 * @return UserGroupMembership[]
	 * @since 1.29
	 */
	public function getGroupMemberships() {
		return $this->userGroupManager->getUserGroupMemberships( $this, IDBAccessObject::READ_LATEST );
	}

	/**
	 * Replaces User::addGroup()
	 *
	 * @param string $group
	 * @param string|null $expiry
	 * @return bool
	 */
	public function addGroup( $group, $expiry = null ) {
		return $this->userGroupManager->addUserToGroup(
			$this,
			$group,
			$expiry,
			true
		);
	}

	/**
	 * Replaces User::removeGroup()
	 *
	 * @param string $group
	 * @return bool
	 */
	public function removeGroup( $group ) {
		return $this->userGroupManager->removeUserFromGroup(
			$this,
			$group
		);
	}

	/**
	 * Replaces User::setOption()
	 * @param string $option
	 * @param mixed $value
	 */
	public function setOption( $option, $value ) {
		$this->newOptions[$option] = $value;
	}

	public function saveSettings() {
		$rows = [];
		foreach ( $this->newOptions as $option => $value ) {
			$rows[] = [
				'up_user' => $this->id,
				'up_property' => $option,
				'up_value' => $value,
			];
		}
		$this->db->replace(
			'user_properties',
			[ [ 'up_user', 'up_property' ] ],
			$rows,
			__METHOD__
		);
		$this->invalidateCache();
	}

	/**
	 * Replaces User::touchUser()
	 */
	public function invalidateCache() {
		$this->db->update(
			'user',
			[ 'user_touched' => $this->db->timestamp() ],
			[ 'user_id' => $this->id ],
			__METHOD__
		);

		$domainId = $this->db->getDomainID();
		$userId = $this->id;
		$this->db->onTransactionPreCommitOrIdle(
			static function () use ( $domainId, $userId ) {
				User::purge( $domainId, $userId );
			},
			__METHOD__
		);
	}

	/**
	 * @inheritDoc
	 */
	public function equals( ?UserIdentity $user ): bool {
		if ( !$user ) {
			return false;
		}
		return $this->getName() === $user->getName();
	}

	/**
	 * @inheritDoc
	 */
	public function isRegistered(): bool {
		return $this->getId( $this->getWikiId() ) != 0;
	}

	/**
	 * Returns the db Domain of the wiki the UserRightsProxy is associated with.
	 *
	 * @since 1.37
	 * @return string
	 */
	public function getWikiId() {
		return $this->dbDomain;
	}
}
