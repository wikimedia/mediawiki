<?php
/**
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

namespace MediaWiki\User\CentralId;

use InvalidArgumentException;
use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityLookup;
use Throwable;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Find central user IDs associated with local user IDs, e.g. across a wiki farm.
 *
 * Default implementation is MediaWiki\User\CentralId\LocalIdLookup.
 *
 * @since 1.27
 * @stable to extend
 * @ingroup User
 */
abstract class CentralIdLookup {
	// Audience options for accessors
	public const AUDIENCE_PUBLIC = 1;
	public const AUDIENCE_RAW = 2;

	public const FILTER_NONE = 'none';
	public const FILTER_ATTACHED = 'attached';
	public const FILTER_OWNED = 'owned';

	/** @var string */
	private $providerId;

	private UserIdentityLookup $userIdentityLookup;
	private UserFactory $userFactory;

	/**
	 * Fetch a CentralIdLookup
	 * @deprecated since 1.37 Use MediaWikiServices to obtain an instance.
	 * @param string|null $providerId Provider ID from $wgCentralIdLookupProviders
	 * @return CentralIdLookup|null
	 */
	public static function factory( $providerId = null ) {
		wfDeprecated( __METHOD__, '1.37' );
		try {
			return MediaWikiServices::getInstance()
				->getCentralIdLookupFactory()
				->getLookup( $providerId );
		} catch ( Throwable ) {
			return null;
		}
	}

	/**
	 * Initialize the provider.
	 *
	 * @internal
	 */
	public function init(
		string $providerId,
		UserIdentityLookup $userIdentityLookup,
		UserFactory $userFactory
	) {
		if ( $this->providerId !== null ) {
			throw new LogicException( "CentralIdProvider $providerId already initialized" );
		}
		$this->providerId = $providerId;
		$this->userIdentityLookup = $userIdentityLookup;
		$this->userFactory = $userFactory;
	}

	public function getProviderId(): string {
		return $this->providerId;
	}

	/**
	 * Check that the "audience" parameter is valid
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @return Authority|null authority to check against, or null if no checks are needed
	 * @throws InvalidArgumentException
	 */
	protected function checkAudience( $audience ): ?Authority {
		if ( $audience instanceof Authority ) {
			return $audience;
		}
		if ( $audience === self::AUDIENCE_PUBLIC ) {
			// TODO: when available, inject AuthorityFactory
			// via init and use it to create anon authority
			return $this->userFactory->newAnonymous();
		}
		if ( $audience === self::AUDIENCE_RAW ) {
			return null;
		}
		throw new InvalidArgumentException( 'Invalid audience' );
	}

	/**
	 * Check that a user is attached on the specified wiki.
	 *
	 * If unattached local accounts don't exist in your extension, this comes
	 * down to a check whether the central account exists at all and that
	 * $wikiId is using the same central database.
	 *
	 * @param UserIdentity $user
	 * @param string|false $wikiId Wiki to check attachment status. If false, check the current wiki.
	 * @return bool
	 */
	abstract public function isAttached( UserIdentity $user, $wikiId = UserIdentity::LOCAL ): bool;

	/**
	 * Check that a username is owned by the central user on the specified wiki.
	 *
	 * This should return true if the local account exists and is attached (see isAttached()),
	 * or if it does not exist but is reserved for the central user (it's guaranteed that
	 * if it's ever created, then it will be attached to the central user).
	 *
	 * @since 1.43
	 * @stable to override
	 * @param UserIdentity $user
	 * @param string|false $wikiId Wiki to check attachment status. If false, check the current wiki.
	 * @return bool
	 */
	public function isOwned( UserIdentity $user, $wikiId = UserIdentity::LOCAL ): bool {
		return $this->isAttached( $user, $wikiId );
	}

	/**
	 * Given central user IDs, return the (local) user names
	 * @note There's no requirement that the user names actually exist locally,
	 *  or if they do that they're actually attached to the central account.
	 * @param array $idToName Array with keys being central user IDs
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return string[] Copy of $idToName with values set to user names (or
	 *  empty-string if the user exists but $audience lacks the rights needed
	 *  to see it). IDs not corresponding to a user are unchanged.
	 */
	abstract public function lookupCentralIds(
		array $idToName, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): array;

	/**
	 * Given (local) user names, return the central IDs
	 * @note There's no requirement that the user names actually exist locally,
	 *  or if they do that they're actually attached to the central account.
	 * @param array $nameToId Array with keys being canonicalized user names
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return int[] Copy of $nameToId with values set to central IDs.
	 *  Names not corresponding to a user (or $audience lacks the rights needed
	 *  to see it) are unchanged.
	 */
	public function lookupUserNames(
		array $nameToId, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): array {
		return $this->lookupUserNamesWithFilter( $nameToId, self::FILTER_NONE,
			$audience, $flags );
	}

	/**
	 * Given user names on the wiki specified by $wikiId, return the central
	 * IDs, but only include IDs for local users owned by the central user,
	 * i.e. isOwned() would be true.
	 *
	 * @since 1.44
	 *
	 * @param array $nameToId Array with keys being canonicalized user names
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @param string|false $wikiId Wiki to check attachment status. If false, check the current
	 *   wiki.
	 * @return int[] Copy of $nameToId with values set to central IDs.
	 *   Names not owned by the central user are unchanged.
	 */
	public function lookupOwnedUserNames(
		array $nameToId,
		$audience = self::AUDIENCE_PUBLIC,
		$flags = IDBAccessObject::READ_NORMAL,
		$wikiId = UserIdentity::LOCAL
	) {
		return $this->lookupUserNamesWithFilter( $nameToId, self::FILTER_OWNED,
			$audience, $flags, $wikiId );
	}

	/**
	 * Given user names on the wiki specified by $wikiId, return the central
	 * IDs, but only include IDs for local users attached to the central user,
	 * i.e. isAttached() would be true.
	 *
	 * @since 1.44
	 *
	 * @param array $nameToId Array with keys being canonicalized user names
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @param string|false $wikiId Wiki to check attachment status. If false, check the current
	 *   wiki.
	 * @return int[] Copy of $nameToId with values set to central IDs.
	 *   Names not attached to the central user are unchanged.
	 */
	public function lookupAttachedUserNames(
		array $nameToId,
		$audience = self::AUDIENCE_PUBLIC,
		$flags = IDBAccessObject::READ_NORMAL,
		$wikiId = UserIdentity::LOCAL
	) {
		return $this->lookupUserNamesWithFilter( $nameToId, self::FILTER_ATTACHED,
			$audience, $flags, $wikiId );
	}

	/**
	 * Given user names on the wiki specified by $wikiId, return the central
	 * IDs. If $filter is not FILTER_NONE, filter the users by owned or
	 * attached status.
	 *
	 * @since 1.44
	 *
	 * @param array $nameToId Array with keys being canonicalized user names
	 * @param string $filter One of:
	 *   - self::FILTER_NONE: Get all users with the specified names
	 *   - self::FILTER_ATTACHED: Only get IDs for attached users
	 *   - self::FILTER_OWNED: Only get IDs for owned users
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @param string|false $wikiId Wiki to check attachment status. If false, check the current
	 *   wiki.
	 * @return int[] Copy of $nameToId with values set to central IDs.
	 *   Names not owned by the central user are unchanged.
	 */
	abstract protected function lookupUserNamesWithFilter(
		array $nameToId,
		$filter,
		$audience = self::AUDIENCE_PUBLIC,
		$flags = IDBAccessObject::READ_NORMAL,
		$wikiId = UserIdentity::LOCAL
	): array;

	/**
	 * Given a central user ID, return the (local) user name
	 * @note There's no requirement that the user name actually exists locally,
	 *  or if it does that it's actually attached to the central account.
	 * @param int $id Central user ID
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return string|null user name, or empty string if $audience lacks the
	 *  rights needed to see it, or null if $id doesn't correspond to a user
	 */
	public function nameFromCentralId(
		$id, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): ?string {
		$idToName = $this->lookupCentralIds( [ $id => null ], $audience, $flags );
		return $idToName[$id];
	}

	/**
	 * Given a an array of central user IDs, return the (local) user names.
	 * @param int[] $ids Central user IDs
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return string[] user names
	 * @since 1.30
	 */
	public function namesFromCentralIds(
		array $ids, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): array {
		$idToName = array_fill_keys( $ids, false );
		$names = $this->lookupCentralIds( $idToName, $audience, $flags );
		$names = array_unique( $names );
		$names = array_filter( $names, static function ( $name ) {
			return $name !== false && $name !== '';
		} );

		return array_values( $names );
	}

	/**
	 * Given a (local) user name, return the central ID
	 * @note There's no requirement that the user name actually exists locally,
	 *  or if it does that it's actually attached to the central account.
	 * @param string $name Canonicalized user name
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return int user ID; 0 if the name does not correspond to a user or
	 *  $audience lacks the rights needed to see it.
	 */
	public function centralIdFromName(
		$name, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): int {
		$nameToId = $this->lookupUserNames( [ $name => 0 ], $audience, $flags );
		return $nameToId[$name];
	}

	/**
	 * Given an array of (local) user names, return the central IDs.
	 * @param string[] $names Canonicalized user names
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return int[] user IDs
	 * @since 1.30
	 */
	public function centralIdsFromNames(
		array $names, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): array {
		$nameToId = array_fill_keys( $names, false );
		$ids = $this->lookupUserNames( $nameToId, $audience, $flags );
		$ids = array_unique( $ids );
		$ids = array_filter( $ids, static function ( $id ) {
			return $id !== false;
		} );

		return array_values( $ids );
	}

	/**
	 * Given a central user ID, return a local user object
	 * @note Unlike nameFromCentralId(), this does guarantee that the local
	 *  user exists and is attached to the central account.
	 * @stable to override
	 * @param int $id Central user ID
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return UserIdentity|null Local user, or null if: $id doesn't correspond to a
	 *  user, $audience lacks the rights needed to see the user, the user
	 *  doesn't exist locally, or the user isn't locally attached.
	 */
	public function localUserFromCentralId(
		$id, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): ?UserIdentity {
		$name = $this->nameFromCentralId( $id, $audience, $flags );
		if ( !$name ) {
			return null;
		}
		$user = $this->userIdentityLookup->getUserIdentityByName( $name );
		if ( $user && $user->isRegistered() && $this->isAttached( $user ) ) {
			return $user;
		}
		return null;
	}

	/**
	 * Given a local UserIdentity object, return the central ID
	 * @stable to override
	 * @note Unlike centralIdFromName(), this does guarantee that the local
	 *  user is attached to the central account.
	 * @param UserIdentity $user Local user
	 * @param int|Authority $audience One of the audience constants, or a specific authority
	 * @param int $flags IDBAccessObject read flags
	 * @return int user ID; 0 if the local user does not correspond to a
	 *  central user, $audience lacks the rights needed to see it, or the
	 *  central user isn't locally attached.
	 */
	public function centralIdFromLocalUser(
		UserIdentity $user, $audience = self::AUDIENCE_PUBLIC, $flags = IDBAccessObject::READ_NORMAL
	): int {
		$name = $user->getName();
		$nameToId = $this->lookupAttachedUserNames( [ $name => 0 ], $audience, $flags );
		return $nameToId[$name];
	}

}

/** @deprecated class alias since 1.41 */
class_alias( CentralIdLookup::class, 'CentralIdLookup' );
