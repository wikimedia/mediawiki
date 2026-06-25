<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\Throttler;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Password\InvalidPassword;
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordError;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Request\WebRequest;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Status\Status;
use MediaWiki\Utils\MWRestrictions;
use stdClass;
use UnexpectedValueException;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * Utility class for bot passwords
 * @since 1.27
 */
class BotPassword {

	public const APPID_MAXLENGTH = 32;

	/**
	 * Minimum length for a bot password
	 */
	public const PASSWORD_MINLENGTH = 32;

	/**
	 * Maximum length of the json representation of restrictions
	 * @since 1.36
	 */
	public const RESTRICTIONS_MAXLENGTH = 65535;

	/**
	 * Maximum length of the json representation of grants
	 * @since 1.36
	 */
	public const GRANTS_MAXLENGTH = 65535;

	private int $centralId;
	private readonly string $appId;
	private string $token;
	private readonly MWRestrictions $restrictions;

	/** @var string[] */
	private readonly array $grants;

	/**
	 * @internal only public for construction in BotPasswordStore
	 *
	 * @param stdClass $row bot_passwords database row
	 * @param bool $isSaved Whether the bot password was read from the database
	 * @param int $flags IDBAccessObject read flags
	 */
	public function __construct(
		stdClass $row,
		private bool $isSaved,
		private readonly int $flags = IDBAccessObject::READ_NORMAL,
	) {
		$this->centralId = (int)$row->bp_user;
		$this->appId = $row->bp_app_id;
		$this->token = $row->bp_token;
		$this->restrictions = MWRestrictions::newFromJson( $row->bp_restrictions );
		$this->grants = FormatJson::decode( $row->bp_grants );
	}

	public static function getReplicaDatabase(): IReadableDatabase {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->getReplicaDatabase();
	}

	public static function getPrimaryDatabase(): IDatabase {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->getPrimaryDatabase();
	}

	/**
	 * Load a BotPassword from the database
	 * @param UserIdentity $userIdentity
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromUser( UserIdentity $userIdentity, $appId, $flags = IDBAccessObject::READ_NORMAL ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->getByUser( $userIdentity, (string)$appId, (int)$flags );
	}

	/**
	 * Load a BotPassword from the database
	 * @param int $centralId from CentralIdLookup
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromCentralId( $centralId, $appId, $flags = IDBAccessObject::READ_NORMAL ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->getByCentralId( (int)$centralId, (string)$appId, (int)$flags );
	}

	/**
	 * Create an unsaved BotPassword
	 * @param array $data Data to use to create the bot password. Keys are:
	 *  - user: (UserIdentity) UserIdentity to create the password for. Overrides username and centralId.
	 *  - username: (string) Username to create the password for. Overrides centralId.
	 *  - centralId: (int) User central ID to create the password for.
	 *  - appId: (string, required) App ID for the password.
	 *  - restrictions: (MWRestrictions, optional) Restrictions.
	 *  - grants: (string[], optional) Grants.
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newUnsaved( array $data, $flags = IDBAccessObject::READ_NORMAL ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->newUnsavedBotPassword( $data, (int)$flags );
	}

	/**
	 * Indicate whether this is known to be saved
	 */
	public function isSaved(): bool {
		return $this->isSaved;
	}

	/**
	 * Get the central user ID
	 */
	public function getUserCentralId(): int {
		return $this->centralId;
	}

	public function getAppId(): string {
		return $this->appId;
	}

	public function getToken(): string {
		return $this->token;
	}

	public function getRestrictions(): MWRestrictions {
		return $this->restrictions;
	}

	/**
	 * @return string[]
	 */
	public function getGrants() {
		return $this->grants;
	}

	/**
	 * Get the separator for combined username + app ID
	 */
	public static function getSeparator(): string {
		return MediaWikiServices::getInstance()
			->getMainConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter );
	}

	private function getPassword(): Password {
		if ( ( $this->flags & IDBAccessObject::READ_LATEST ) === IDBAccessObject::READ_LATEST ) {
			$db = self::getPrimaryDatabase();
		} else {
			$db = self::getReplicaDatabase();
		}

		$password = $db->newSelectQueryBuilder()
			->select( 'bp_password' )
			->from( 'bot_passwords' )
			->where( [ 'bp_user' => $this->centralId, 'bp_app_id' => $this->appId ] )
			->recency( $this->flags )
			->caller( __METHOD__ )->fetchField();
		if ( $password === false ) {
			return PasswordFactory::newInvalidPassword();
		}

		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();
		try {
			return $passwordFactory->newFromCiphertext( $password );
		} catch ( PasswordError ) {
			return PasswordFactory::newInvalidPassword();
		}
	}

	/**
	 * Whether the password is currently invalid
	 * @since 1.32
	 */
	public function isInvalid(): bool {
		return $this->getPassword() instanceof InvalidPassword;
	}

	/**
	 * Save the BotPassword to the database
	 * @param string $operation 'update' or 'insert'
	 * @param Password|null $password Password to set.
	 * @return Status
	 * @throws UnexpectedValueException
	 */
	public function save( $operation, ?Password $password = null ) {
		// Ensure operation is valid
		if ( $operation !== 'insert' && $operation !== 'update' ) {
			throw new UnexpectedValueException(
				"Expected 'insert' or 'update'; got '{$operation}'."
			);
		}

		$store = MediaWikiServices::getInstance()->getBotPasswordStore();
		if ( $operation === 'insert' ) {
			$statusValue = $store->insertBotPassword( $this, $password );
		} else {
			// Must be update, already checked above
			$statusValue = $store->updateBotPassword( $this, $password );
		}

		if ( $statusValue->isGood() ) {
			$this->token = $statusValue->getValue();
			$this->isSaved = true;
			return Status::newGood();
		}

		// Action failed, status will have code botpasswords-insert-failed or
		// botpasswords-update-failed depending on which action we tried
		return Status::wrap( $statusValue );
	}

	/**
	 * Delete the BotPassword from the database
	 * @return bool Success
	 */
	public function delete() {
		$ok = MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->deleteBotPassword( $this );
		if ( $ok ) {
			$this->token = '**unsaved**';
			$this->isSaved = false;
		}
		return $ok;
	}

	/**
	 * Invalidate all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were invalidated
	 */
	public static function invalidateAllPasswordsForUser( string $username ): bool {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->invalidateUserPasswords( $username );
	}

	/**
	 * Remove all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were removed
	 */
	public static function removeAllPasswordsForUser( string $username ): bool {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->removeUserPasswords( $username );
	}

	/**
	 * Returns a (raw, unhashed) random password string.
	 */
	public static function generatePassword(): string {
		return PasswordFactory::generateRandomPasswordString( self::PASSWORD_MINLENGTH );
	}

	/**
	 * There are two ways to login with a bot password: "username@appId", "password" and
	 * "username", "appId@password". Transform it so it is always in the first form.
	 * Returns [bot username, bot password].
	 * If this cannot be a bot password login just return false.
	 * @param string $username
	 * @param string $password
	 * @return string[]|false
	 */
	public static function canonicalizeLoginData( string $username, string $password ) {
		$sep = self::getSeparator();
		// the strlen check helps minimize the password information obtainable from timing
		if ( strlen( $password ) >= self::PASSWORD_MINLENGTH && str_contains( $username, $sep ) ) {
			// the separator is not valid in new usernames but might appear in legacy ones
			if ( preg_match( '/^[0-9a-w]{' . self::PASSWORD_MINLENGTH . ',}$/', $password ) ) {
				return [ $username, $password ];
			}
		} elseif ( strlen( $password ) > self::PASSWORD_MINLENGTH && str_contains( $password, $sep ) ) {
			$segments = explode( $sep, $password );
			$password = array_pop( $segments );
			$appId = implode( $sep, $segments );
			if ( preg_match( '/^[0-9a-w]{' . self::PASSWORD_MINLENGTH . ',}$/', $password ) ) {
				return [ $username . $sep . $appId, $password ];
			}
		}
		return false;
	}

	/**
	 * Try to log the user in
	 * @param string $username Combined username and app ID
	 * @param string $password Supplied password
	 * @param WebRequest $request
	 * @return Status On success, the good status's value is the new Session object
	 */
	public static function login( string $username, string $password, WebRequest $request ) {
		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		$enableBotPasswords = $config->get( MainConfigNames::EnableBotPasswords );
		if ( !$enableBotPasswords ) {
			return Status::newFatal( 'botpasswords-disabled' );
		}

		$sessionManager = $services->getSessionManager();

		// @phan-suppress-next-line PhanUndeclaredMethod
		$provider = $sessionManager->getProvider( BotPasswordSessionProvider::class );

		if ( !$provider ) {
			return Status::newFatal( 'botpasswords-no-provider' );
		}

		$performer = $request->getSession()->getUser();
		// Split name into name+appId
		$sep = self::getSeparator();
		if ( !str_contains( $username, $sep ) ) {
			return self::loginHook(
				$username, null, $performer, Status::newFatal( 'botpasswords-invalid-name', $sep )
			);
		}
		[ $name, $appId ] = explode( $sep, $username, 2 );

		// Find the named user
		$user = User::newFromName( $name );
		if ( !$user ) {
			return self::loginHook( $name, null, $performer, Status::newFatal( 'noname' ) );
		}

		$centralIdLookup = $services->getCentralIdLookup();
		// Since bot passwords may be shared within a wiki farm, only allow them for globally owned accounts
		[ $user->getName() => $centralId ] = $centralIdLookup->lookupOwnedUserNames(
			[ $user->getName() => 0 ], $centralIdLookup::AUDIENCE_RAW );
		if ( !$centralId ) {
			return self::loginHook( $user, null, $performer, Status::newFatal( 'nosuchuser', $name ) );
		}
		if ( $user->isLocked() ) {
			return Status::newFatal( 'botpasswords-locked' );
		}

		$throttle = null;
		$passwordAttemptThrottle = $config->get( MainConfigNames::PasswordAttemptThrottle );
		if ( $passwordAttemptThrottle ) {
			$throttle = new Throttler( $passwordAttemptThrottle, [
				'type' => 'botpassword',
				'cache' => $services->getObjectCacheFactory()->getLocalClusterInstance(),
			] );
			$result = $throttle->increase( $user->getName(), $request->getIP(), __METHOD__ );
			if ( $result ) {
				$msg = wfMessage( 'login-throttled' )->durationParams( $result['wait'] );
				return self::loginHook( $user, null, $performer, Status::newFatal( $msg ) );
			}
		}

		// Get the bot password
		$bp = self::newFromCentralId( $centralId, $appId );
		if ( !$bp ) {
			return self::loginHook( $user, $bp, $performer,
				Status::newFatal( 'botpasswords-not-exist', $name, $appId ) );
		}

		// Check restrictions
		$status = $bp->getRestrictions()->check( $request );
		if ( !$status->isOK() ) {
			return self::loginHook( $user, $bp, $performer,
				Status::newFatal( 'botpasswords-restriction-failed' ) );
		}

		// Check the password
		$passwordObj = $bp->getPassword();
		if ( $passwordObj instanceof InvalidPassword ) {
			return self::loginHook( $user, $bp, $performer,
				Status::newFatal( 'botpasswords-needs-reset', $name, $appId ) );
		}
		if ( !$passwordObj->verify( $password ) ) {
			return self::loginHook( $user, $bp, $performer, Status::newFatal( 'wrongpassword' ) );
		}

		// Ok! Create the session.
		$throttle?->clear( $user->getName(), $request->getIP() );

		if ( !$user->isRegistered() ) {
			// Clone $user before passing it to ::autoCreateUser, as that will
			// modify the provided User object to an IP for some failures (but
			// we want the login hook to get the username used and not an IP)
			$userForFailure = clone $user;
			$status = $services->getAuthManager()->autoCreateUser(
				$user,
				AuthManager::AUTOCREATE_SOURCE_SESSION,
				false
			);
			if ( !$status->isOK() ) {
				return self::loginHook( $userForFailure, $bp, $performer, $status );
			}
		}

		return self::loginHook( $user, $bp, $performer,
			Status::newGood( $provider->newSessionForRequest( $user, $bp, $request ) ) );
	}

	/**
	 * Call AuthManagerLoginAuthenticateAudit
	 *
	 * To facilitate logging all authentications, even ones not via
	 * AuthManager, call the AuthManagerLoginAuthenticateAudit hook.
	 *
	 * @param User|string $user User being logged in
	 * @param BotPassword|null $bp Bot sub-account, if it can be identified
	 * @param User $performer User performing the request
	 * @param Status $status Login status
	 * @return Status The passed-in status
	 */
	private static function loginHook( $user, $bp, User $performer, Status $status ) {
		$extraData = [
			'performer' => $performer
		];
		if ( $user instanceof User ) {
			$name = $user->getName();
			if ( $bp ) {
				$extraData['appId'] = $name . self::getSeparator() . $bp->getAppId();
			}
		} else {
			$name = $user;
			$user = null;
		}

		if ( $status->isGood() ) {
			$response = AuthenticationResponse::newPass( $name );
		} else {
			$response = AuthenticationResponse::newFail( $status->getMessage() );
		}
		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onAuthManagerLoginAuthenticateAudit( $response, $user, $name, $extraData );

		return $status;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( BotPassword::class, 'BotPassword' );
