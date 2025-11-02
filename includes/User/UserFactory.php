<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\TempUser\TempUserConfig;
use RuntimeException;
use stdClass;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILBFactory;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Create User objects.
 *
 * This creates User objects and involves all the same global state,
 * but wraps it in a service class to avoid static coupling, which
 * eases mocking in unit tests.
 *
 * @since 1.35
 * @ingroup User
 */
class UserFactory implements UserRigorOptions {

	/**
	 * RIGOR_* constants are inherited from UserRigorOptions
	 */

	/** @internal */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::SharedDB,
		MainConfigNames::SharedTables,
	];

	private ILoadBalancer $loadBalancer;

	private ?User $lastUserFromIdentity = null;

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly ILBFactory $loadBalancerFactory,
		private readonly UserNameUtils $userNameUtils,
		private readonly TempUserConfig $tempUserConfig
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->loadBalancer = $loadBalancerFactory->getMainLB();
	}

	/**
	 * Factory method for creating users by name, replacing static User::newFromName
	 *
	 * This is slightly less efficient than newFromId(), so use newFromId() if
	 * you have both an ID and a name handy.
	 *
	 * @note unlike User::newFromName, this returns null instead of false for invalid usernames
	 *
	 * @since 1.35
	 * @since 1.36 returns null instead of false for invalid user names
	 *
	 * @param string $name Username, validated by Title::newFromText
	 * @param string $validate Validation strategy, one of the RIGOR_* constants. For no
	 *    validation, use RIGOR_NONE. If you just want to create valid user who can be either a named
	 *    user or an IP, consider using newFromNameOrIp() instead of calling this with RIGOR_NONE.
	 * @return ?User User object, or null if the username is invalid (e.g. if it contains
	 *  illegal characters or is an IP address). If the username is not present in the database,
	 *  the result will be a user object with a name, a user id of 0, and default settings.
	 */
	public function newFromName(
		string $name,
		string $validate = self::RIGOR_VALID
	): ?User {
		// RIGOR_* constants are the same here and in the UserNameUtils class
		$canonicalName = $this->userNameUtils->getCanonical( $name, $validate );
		if ( $canonicalName === false ) {
			return null;
		}

		$user = new User();
		$user->mName = $canonicalName;
		$user->mFrom = 'name';
		$user->setItemLoaded( 'name' );
		return $user;
	}

	/**
	 * Returns a new anonymous User based on ip.
	 *
	 * @since 1.35
	 *
	 * @param string|null $ip IP address
	 * @return User
	 */
	public function newAnonymous( ?string $ip = null ): User {
		if ( $ip ) {
			if ( !$this->userNameUtils->isIP( $ip ) ) {
				throw new InvalidArgumentException( 'Invalid IP address' );
			}
			$user = new User();
			$user->setName( $ip );
		} else {
			$user = new User();
		}
		return $user;
	}

	/**
	 * Returns either an anonymous or a named User based on the supplied argument
	 *
	 * If IP is supplied, an anonymous user will be created, otherwise a valid named user.
	 * If you don't want to have the named user validated, use self::newFromName().
	 * If you want to create a simple anonymous user without providing the IP, use self::newAnonymous()
	 *
	 * @since 1.44
	 *
	 * @param string $name IP address or username
	 * @return User|null
	 */
	public function newFromNameOrIp( string $name ): ?User {
		if ( $this->userNameUtils->isIP( $name ) ) {
			return $this->newAnonymous( $name );
		}

		return $this->newFromName( $name );
	}

	/**
	 * Factory method for creation from a given user ID, replacing User::newFromId
	 *
	 * @since 1.35
	 *
	 * @param int $id Valid user ID
	 * @return User
	 */
	public function newFromId( int $id ): User {
		$user = new User();
		$user->mId = $id;
		$user->mFrom = 'id';
		$user->setItemLoaded( 'id' );
		return $user;
	}

	/**
	 * Factory method for creation from a given actor ID, replacing User::newFromActorId
	 *
	 * @since 1.35
	 */
	public function newFromActorId( int $actorId ): User {
		$user = new User();
		$user->mActorId = $actorId;
		$user->mFrom = 'actor';
		$user->setItemLoaded( 'actor' );
		return $user;
	}

	/**
	 * Factory method for creation from a given UserIdentity, replacing User::newFromIdentity
	 *
	 * @since 1.35
	 */
	public function newFromUserIdentity( UserIdentity $userIdentity ): User {
		if ( $userIdentity instanceof User ) {
			return $userIdentity;
		}

		$id = $userIdentity->getId();
		$name = $userIdentity->getName();
		// Cache the $userIdentity we converted last. This avoids redundant conversion
		// in cases where we would be converting the same UserIdentity over and over,
		// for instance because we need to access data preferences when formatting
		// timestamps in a listing.
		if (
			$this->lastUserFromIdentity
			&& $this->lastUserFromIdentity->getId() === $id
			&& $this->lastUserFromIdentity->getName() === $name
			&& $this->lastUserFromIdentity->getWikiId() === $userIdentity->getWikiId()
		) {
			return $this->lastUserFromIdentity;
		}

		$this->lastUserFromIdentity = $this->newFromAnyId(
			$id === 0 ? null : $id,
			$name === '' ? null : $name,
			null,
			$userIdentity->getWikiId()
		);

		return $this->lastUserFromIdentity;
	}

	/**
	 * Factory method for creation from an ID, name, and/or actor ID, replacing User::newFromAnyId
	 *
	 * @note This does not check that the ID, name, and actor ID all correspond to
	 * the same user.
	 *
	 * @since 1.35
	 *
	 * @param ?int $userId
	 * @param ?string $userName
	 * @param ?int $actorId
	 * @param string|false $dbDomain
	 * @return User
	 * @throws InvalidArgumentException if none of userId, userName, and actorId are specified
	 */
	public function newFromAnyId(
		?int $userId,
		?string $userName,
		?int $actorId = null,
		$dbDomain = false
	): User {
		// Stop-gap solution for the problem described in T222212.
		// Force the User ID and Actor ID to zero for users loaded from the database
		// of another wiki, to prevent subtle data corruption and confusing failure modes.
		// FIXME this assumes the same username belongs to the same user on all wikis
		if ( $dbDomain !== false ) {
			LoggerFactory::getInstance( 'user' )->warning(
				'UserFactory::newFromAnyId called with cross-wiki user data',
				[ 'userId' => $userId, 'userName' => $userName, 'actorId' => $actorId,
				  'dbDomain' => $dbDomain, 'exception' => new RuntimeException() ]
			);
			$userId = 0;
			$actorId = 0;
		}

		$user = new User;
		$user->mFrom = 'defaults';

		if ( $actorId !== null ) {
			$user->mActorId = $actorId;
			if ( $actorId !== 0 ) {
				$user->mFrom = 'actor';
			}
			$user->setItemLoaded( 'actor' );
		}

		if ( $userName !== null && $userName !== '' ) {
			$user->mName = $userName;
			$user->mFrom = 'name';
			$user->setItemLoaded( 'name' );
		}

		if ( $userId !== null ) {
			$user->mId = $userId;
			if ( $userId !== 0 ) {
				$user->mFrom = 'id';
			}
			$user->setItemLoaded( 'id' );
		}

		if ( $user->mFrom === 'defaults' ) {
			throw new InvalidArgumentException(
				'Cannot create a user with no name, no ID, and no actor ID'
			);
		}

		return $user;
	}

	/**
	 * Factory method to fetch the user for a given email confirmation code, replacing User::newFromConfirmationCode
	 *
	 * This code is generated when an account is created or its e-mail address has changed.
	 * If the code is invalid or has expired, returns null.
	 *
	 * @since 1.35
	 */
	public function newFromConfirmationCode(
		string $confirmationCode,
		int $flags = IDBAccessObject::READ_NORMAL
	): ?User {
		if ( ( $flags & IDBAccessObject::READ_LATEST ) === IDBAccessObject::READ_LATEST ) {
			$db = $this->loadBalancer->getConnection( DB_PRIMARY );
		} else {
			$db = $this->loadBalancer->getConnection( DB_REPLICA );
		}

		$id = $db->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user' )
			->where( [ 'user_email_token' => md5( $confirmationCode ) ] )
			->andWhere( $db->expr( 'user_email_token_expires', '>', $db->timestamp() ) )
			->recency( $flags )
			->caller( __METHOD__ )->fetchField();

		if ( !$id ) {
			return null;
		}

		return $this->newFromId( (int)$id );
	}

	/**
	 * @see User::newFromRow
	 *
	 * @since 1.36
	 *
	 * @param stdClass $row A row from the user table
	 * @param array|null $data Further data to load into the object
	 * @return User
	 */
	public function newFromRow( $row, $data = null ): User {
		return User::newFromRow( $row, $data );
	}

	/**
	 * @internal for transition from User to Authority as performer concept.
	 */
	public function newFromAuthority( Authority $authority ): User {
		if ( $authority instanceof User ) {
			return $authority;
		}
		return $this->newFromUserIdentity( $authority->getUser() );
	}

	/**
	 * Create a placeholder user for an anonymous user who will be upgraded to
	 * a temporary user. This will throw an exception if temp user autocreation
	 * is disabled.
	 *
	 * @since 1.39
	 */
	public function newTempPlaceholder(): User {
		$user = new User();
		$user->setName( $this->tempUserConfig->getPlaceholderName() );
		return $user;
	}

	/**
	 * Create an unsaved temporary user with a previously acquired name or a placeholder name.
	 *
	 * @since 1.39
	 * @param ?string $name If null, a placeholder name is used
	 * @return User
	 */
	public function newUnsavedTempUser( ?string $name ): User {
		$user = new User();
		$user->setName( $name ?? $this->tempUserConfig->getPlaceholderName() );
		return $user;
	}

	/**
	 * Purge user-related caches, "touch" the user table to invalidate further caches
	 * @since 1.41
	 */
	public function invalidateCache( UserIdentity $userIdentity ): void {
		if ( !$userIdentity->isRegistered() ) {
			return;
		}

		$wikiId = $userIdentity->getWikiId();
		if ( $wikiId === UserIdentity::LOCAL ) {
			$legacyUser = $this->newFromUserIdentity( $userIdentity );
			// Update user_touched within User class to manage the state of User::mTouched for CAS check
			$legacyUser->invalidateCache();
		} else {
			// cross-wiki invalidation
			$userId = $userIdentity->getId( $wikiId );

			$dbw = $this->getUserTableConnection( ILoadBalancer::DB_PRIMARY, $wikiId );
			$dbw->newUpdateQueryBuilder()
				->update( 'user' )
				->set( [ 'user_touched' => $dbw->timestamp() ] )
				->where( [ 'user_id' => $userId ] )
				->caller( __METHOD__ )->execute();

			$dbw->onTransactionPreCommitOrIdle(
				static function () use ( $wikiId, $userId ) {
					User::purge( $wikiId, $userId );
				},
				__METHOD__
			);
		}
	}

	/**
	 * @param int $mode
	 * @param string|false $wikiId
	 * @return IDatabase
	 */
	private function getUserTableConnection( $mode, $wikiId ): IDatabase {
		if ( is_string( $wikiId ) && $this->loadBalancerFactory->getLocalDomainID() === $wikiId ) {
			$wikiId = UserIdentity::LOCAL;
		}

		if ( $this->options->get( MainConfigNames::SharedDB ) &&
			in_array( 'user', $this->options->get( MainConfigNames::SharedTables ) )
		) {
			// The main LB is aliased for the shared database in Setup.php
			$lb = $this->loadBalancer;
		} else {
			$lb = $this->loadBalancerFactory->getMainLB( $wikiId );
		}

		return $lb->getConnection( $mode, [], $wikiId );
	}

	/**
	 * Returns if the user table is shared with other wikis.
	 */
	public function isUserTableShared(): bool {
		return $this->options->get( MainConfigNames::SharedDB ) &&
			in_array( 'user', $this->options->get( MainConfigNames::SharedTables ) );
	}
}
