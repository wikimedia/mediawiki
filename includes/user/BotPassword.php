<?php
/**
 * Utility class for bot passwords
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
 */

use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\Throttler;
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\BotPasswordSessionProvider;
use MediaWiki\Session\SessionManager;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;

/**
 * Utility class for bot passwords
 * @since 1.27
 */
class BotPassword implements IDBAccessObject {

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

	/** @var bool */
	private $isSaved;

	/** @var int */
	private $centralId;

	/** @var string */
	private $appId;

	/** @var string */
	private $token;

	/** @var MWRestrictions */
	private $restrictions;

	/** @var string[] */
	private $grants;

	/** @var int Defaults to {@see READ_NORMAL} */
	private $flags;

	/**
	 * @internal only public for construction in BotPasswordStore
	 *
	 * @param stdClass $row bot_passwords database row
	 * @param bool $isSaved Whether the bot password was read from the database
	 * @param int $flags IDBAccessObject read flags
	 */
	public function __construct( $row, $isSaved, $flags = self::READ_NORMAL ) {
		$this->isSaved = $isSaved;
		$this->flags = $flags;

		$this->centralId = (int)$row->bp_user;
		$this->appId = $row->bp_app_id;
		$this->token = $row->bp_token;
		$this->restrictions = MWRestrictions::newFromJson( $row->bp_restrictions );
		$this->grants = FormatJson::decode( $row->bp_grants );
	}

	/**
	 * Get a database connection for the bot passwords database
	 * @param int $db Index of the connection to get, e.g. DB_PRIMARY or DB_REPLICA.
	 * @return IDatabase
	 */
	public static function getDB( $db ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->getDatabase( $db );
	}

	/**
	 * Load a BotPassword from the database
	 * @param UserIdentity $userIdentity
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromUser( UserIdentity $userIdentity, $appId, $flags = self::READ_NORMAL ) {
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
	public static function newFromCentralId( $centralId, $appId, $flags = self::READ_NORMAL ) {
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
	public static function newUnsaved( array $data, $flags = self::READ_NORMAL ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->newUnsavedBotPassword( $data, (int)$flags );
	}

	/**
	 * Indicate whether this is known to be saved
	 * @return bool
	 */
	public function isSaved() {
		return $this->isSaved;
	}

	/**
	 * Get the central user ID
	 * @return int
	 */
	public function getUserCentralId() {
		return $this->centralId;
	}

	/**
	 * @return string
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @return MWRestrictions
	 */
	public function getRestrictions() {
		return $this->restrictions;
	}

	/**
	 * @return string[]
	 */
	public function getGrants() {
		return $this->grants;
	}

	/**
	 * Get the separator for combined user name + app ID
	 * @return string
	 */
	public static function getSeparator() {
		$userrightsInterwikiDelimiter = MediaWikiServices::getInstance()
			->getMainConfig()->get( 'UserrightsInterwikiDelimiter' );
		return $userrightsInterwikiDelimiter;
	}

	/**
	 * @return Password
	 */
	private function getPassword() {
		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $this->flags );
		$db = self::getDB( $index );
		$password = $db->selectField(
			'bot_passwords',
			'bp_password',
			[ 'bp_user' => $this->centralId, 'bp_app_id' => $this->appId ],
			__METHOD__,
			$options
		);
		if ( $password === false ) {
			return PasswordFactory::newInvalidPassword();
		}

		$passwordFactory = MediaWikiServices::getInstance()->getPasswordFactory();
		try {
			return $passwordFactory->newFromCiphertext( $password );
		} catch ( PasswordError $ex ) {
			return PasswordFactory::newInvalidPassword();
		}
	}

	/**
	 * Whether the password is currently invalid
	 * @since 1.32
	 * @return bool
	 */
	public function isInvalid() {
		return $this->getPassword() instanceof InvalidPassword;
	}

	/**
	 * Save the BotPassword to the database
	 * @param string $operation 'update' or 'insert'
	 * @param Password|null $password Password to set.
	 * @return Status
	 * @throws UnexpectedValueException
	 */
	public function save( $operation, Password $password = null ) {
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
	public static function invalidateAllPasswordsForUser( $username ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->invalidateUserPasswords( (string)$username );
	}

	/**
	 * Invalidate all passwords for a user, by central ID
	 *
	 * @deprecated since 1.37
	 *
	 * @param int $centralId
	 * @return bool Whether any passwords were invalidated
	 */
	public static function invalidateAllPasswordsForCentralId( $centralId ) {
		wfDeprecated( __METHOD__, '1.37' );

		$enableBotPasswords = MediaWikiServices::getInstance()->getMainConfig()->get( 'EnableBotPasswords' );

		if ( !$enableBotPasswords ) {
			return false;
		}

		$dbw = self::getDB( DB_PRIMARY );
		$dbw->update(
			'bot_passwords',
			[ 'bp_password' => PasswordFactory::newInvalidPassword()->toString() ],
			[ 'bp_user' => $centralId ],
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Remove all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were removed
	 */
	public static function removeAllPasswordsForUser( $username ) {
		return MediaWikiServices::getInstance()
			->getBotPasswordStore()
			->removeUserPasswords( (string)$username );
	}

	/**
	 * Remove all passwords for a user, by central ID
	 *
	 * @deprecated since 1.37
	 *
	 * @param int $centralId
	 * @return bool Whether any passwords were removed
	 */
	public static function removeAllPasswordsForCentralId( $centralId ) {
		wfDeprecated( __METHOD__, '1.37' );

		$enableBotPasswords = MediaWikiServices::getInstance()->getMainConfig()->get( 'EnableBotPasswords' );

		if ( !$enableBotPasswords ) {
			return false;
		}

		$dbw = self::getDB( DB_PRIMARY );
		$dbw->delete(
			'bot_passwords',
			[ 'bp_user' => $centralId ],
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Returns a (raw, unhashed) random password string.
	 * @param Config $config
	 * @return string
	 */
	public static function generatePassword( $config ) {
		return PasswordFactory::generateRandomPasswordString(
			max( self::PASSWORD_MINLENGTH, $config->get( 'MinimalPasswordLength' ) ) );
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
	public static function canonicalizeLoginData( $username, $password ) {
		$sep = self::getSeparator();
		// the strlen check helps minimize the password information obtainable from timing
		if ( strlen( $password ) >= self::PASSWORD_MINLENGTH && strpos( $username, $sep ) !== false ) {
			// the separator is not valid in new usernames but might appear in legacy ones
			if ( preg_match( '/^[0-9a-w]{' . self::PASSWORD_MINLENGTH . ',}$/', $password ) ) {
				return [ $username, $password ];
			}
		} elseif ( strlen( $password ) > self::PASSWORD_MINLENGTH && strpos( $password, $sep ) !== false ) {
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
	 * @param string $username Combined user name and app ID
	 * @param string $password Supplied password
	 * @param WebRequest $request
	 * @return Status On success, the good status's value is the new Session object
	 */
	public static function login( $username, $password, WebRequest $request ) {
		$enableBotPasswords = MediaWikiServices::getInstance()->getMainConfig()->get( 'EnableBotPasswords' );
		$passwordAttemptThrottle = MediaWikiServices::getInstance()->getMainConfig()->get( 'PasswordAttemptThrottle' );
		if ( !$enableBotPasswords ) {
			return Status::newFatal( 'botpasswords-disabled' );
		}

		$provider = SessionManager::singleton()->getProvider( BotPasswordSessionProvider::class );
		if ( !$provider ) {
			return Status::newFatal( 'botpasswords-no-provider' );
		}

		// Split name into name+appId
		$sep = self::getSeparator();
		if ( strpos( $username, $sep ) === false ) {
			return self::loginHook( $username, null, Status::newFatal( 'botpasswords-invalid-name', $sep ) );
		}
		list( $name, $appId ) = explode( $sep, $username, 2 );

		// Find the named user
		$user = User::newFromName( $name );
		if ( !$user || $user->isAnon() ) {
			return self::loginHook( $user ?: $name, null, Status::newFatal( 'nosuchuser', $name ) );
		}

		if ( $user->isLocked() ) {
			return Status::newFatal( 'botpasswords-locked' );
		}

		$throttle = null;
		if ( !empty( $passwordAttemptThrottle ) ) {
			$throttle = new Throttler( $passwordAttemptThrottle, [
				'type' => 'botpassword',
				'cache' => ObjectCache::getLocalClusterInstance(),
			] );
			$result = $throttle->increase( $user->getName(), $request->getIP(), __METHOD__ );
			if ( $result ) {
				$msg = wfMessage( 'login-throttled' )->durationParams( $result['wait'] );
				return self::loginHook( $user, null, Status::newFatal( $msg ) );
			}
		}

		// Get the bot password
		$bp = self::newFromUser( $user, $appId );
		if ( !$bp ) {
			return self::loginHook( $user, $bp,
				Status::newFatal( 'botpasswords-not-exist', $name, $appId ) );
		}

		// Check restrictions
		$status = $bp->getRestrictions()->check( $request );
		if ( !$status->isOK() ) {
			return self::loginHook( $user, $bp, Status::newFatal( 'botpasswords-restriction-failed' ) );
		}

		// Check the password
		$passwordObj = $bp->getPassword();
		if ( $passwordObj instanceof InvalidPassword ) {
			return self::loginHook( $user, $bp,
				Status::newFatal( 'botpasswords-needs-reset', $name, $appId ) );
		}
		if ( !$passwordObj->verify( $password ) ) {
			return self::loginHook( $user, $bp, Status::newFatal( 'wrongpassword' ) );
		}

		// Ok! Create the session.
		if ( $throttle ) {
			$throttle->clear( $user->getName(), $request->getIP() );
		}
		return self::loginHook( $user, $bp,
			// @phan-suppress-next-line PhanUndeclaredMethod
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
	 * @param Status $status Login status
	 * @return Status The passed-in status
	 */
	private static function loginHook( $user, $bp, Status $status ) {
		$extraData = [];
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
		Hooks::runner()->onAuthManagerLoginAuthenticateAudit( $response, $user, $name, $extraData );

		return $status;
	}
}
