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
use MediaWiki\MediaWikiServices;
use MediaWiki\Session\BotPasswordSessionProvider;
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
	 * @since 1.35.1
	 */
	public const RESTRICTIONS_MAXLENGTH = 65535;

	/**
	 * Maximum length of the json representation of grants
	 * @since 1.35.1
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

	/** @var int */
	private $flags = self::READ_NORMAL;

	/**
	 * @param object $row bot_passwords database row
	 * @param bool $isSaved Whether the bot password was read from the database
	 * @param int $flags IDBAccessObject read flags
	 */
	protected function __construct( $row, $isSaved, $flags = self::READ_NORMAL ) {
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
	 * @param int $db Index of the connection to get, e.g. DB_MASTER or DB_REPLICA.
	 * @return IDatabase
	 */
	public static function getDB( $db ) {
		global $wgBotPasswordsCluster, $wgBotPasswordsDatabase;

		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lb = $wgBotPasswordsCluster
			? $lbFactory->getExternalLB( $wgBotPasswordsCluster )
			: $lbFactory->getMainLB( $wgBotPasswordsDatabase );
		return $lb->getConnectionRef( $db, [], $wgBotPasswordsDatabase );
	}

	/**
	 * Load a BotPassword from the database
	 * @param User $user
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromUser( User $user, $appId, $flags = self::READ_NORMAL ) {
		$centralId = CentralIdLookup::factory()->centralIdFromLocalUser(
			$user, CentralIdLookup::AUDIENCE_RAW, $flags
		);
		return $centralId ? self::newFromCentralId( $centralId, $appId, $flags ) : null;
	}

	/**
	 * Load a BotPassword from the database
	 * @param int $centralId from CentralIdLookup
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromCentralId( $centralId, $appId, $flags = self::READ_NORMAL ) {
		global $wgEnableBotPasswords;

		if ( !$wgEnableBotPasswords ) {
			return null;
		}

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = self::getDB( $index );
		$row = $db->selectRow(
			'bot_passwords',
			[ 'bp_user', 'bp_app_id', 'bp_token', 'bp_restrictions', 'bp_grants' ],
			[ 'bp_user' => $centralId, 'bp_app_id' => $appId ],
			__METHOD__,
			$options
		);
		return $row ? new self( $row, true, $flags ) : null;
	}

	/**
	 * Create an unsaved BotPassword
	 * @param array $data Data to use to create the bot password. Keys are:
	 *  - user: (User) User object to create the password for. Overrides username and centralId.
	 *  - username: (string) Username to create the password for. Overrides centralId.
	 *  - centralId: (int) User central ID to create the password for.
	 *  - appId: (string) App ID for the password.
	 *  - restrictions: (MWRestrictions, optional) Restrictions.
	 *  - grants: (string[], optional) Grants.
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newUnsaved( array $data, $flags = self::READ_NORMAL ) {
		$row = (object)[
			'bp_user' => 0,
			'bp_app_id' => isset( $data['appId'] ) ? trim( $data['appId'] ) : '',
			'bp_token' => '**unsaved**',
			'bp_restrictions' => $data['restrictions'] ?? MWRestrictions::newDefault(),
			'bp_grants' => $data['grants'] ?? [],
		];

		if (
			$row->bp_app_id === '' || strlen( $row->bp_app_id ) > self::APPID_MAXLENGTH ||
			!$row->bp_restrictions instanceof MWRestrictions ||
			!is_array( $row->bp_grants )
		) {
			return null;
		}

		$row->bp_restrictions = $row->bp_restrictions->toJson();
		$row->bp_grants = FormatJson::encode( $row->bp_grants );

		if ( isset( $data['user'] ) ) {
			if ( !$data['user'] instanceof User ) {
				return null;
			}
			$row->bp_user = CentralIdLookup::factory()->centralIdFromLocalUser(
				$data['user'], CentralIdLookup::AUDIENCE_RAW, $flags
			);
		} elseif ( isset( $data['username'] ) ) {
			$row->bp_user = CentralIdLookup::factory()->centralIdFromName(
				$data['username'], CentralIdLookup::AUDIENCE_RAW, $flags
			);
		} elseif ( isset( $data['centralId'] ) ) {
			$row->bp_user = $data['centralId'];
		}
		if ( !$row->bp_user ) {
			return null;
		}

		return new self( $row, false, $flags );
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
	 * Get the app ID
	 * @return string
	 */
	public function getAppId() {
		return $this->appId;
	}

	/**
	 * Get the token
	 * @return string
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * Get the restrictions
	 * @return MWRestrictions
	 */
	public function getRestrictions() {
		return $this->restrictions;
	}

	/**
	 * Get the grants
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
		global $wgUserrightsInterwikiDelimiter;
		return $wgUserrightsInterwikiDelimiter;
	}

	/**
	 * Get the password
	 * @return Password
	 */
	protected function getPassword() {
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

		$conds = [
			'bp_user' => $this->centralId,
			'bp_app_id' => $this->appId,
		];

		$res = Status::newGood();

		$restrictions = $this->restrictions->toJson();

		if ( strlen( $restrictions ) > self::RESTRICTIONS_MAXLENGTH ) {
			$res->fatal( 'botpasswords-toolong-restrictions' );
		}

		$grants = FormatJson::encode( $this->grants );

		if ( strlen( $grants ) > self::GRANTS_MAXLENGTH ) {
			$res->fatal( 'botpasswords-toolong-grants' );
		}

		if ( !$res->isGood() ) {
			return $res;
		}

		$fields = [
			'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
			'bp_restrictions' => $restrictions,
			'bp_grants' => $grants,
		];

		if ( $password !== null ) {
			$fields['bp_password'] = $password->toString();
		} elseif ( $operation === 'insert' ) {
			$fields['bp_password'] = PasswordFactory::newInvalidPassword()->toString();
		}

		$dbw = self::getDB( DB_MASTER );

		if ( $operation === 'insert' ) {
			$dbw->insert( 'bot_passwords', $fields + $conds, __METHOD__, [ 'IGNORE' ] );
		} else {
			// Must be update, already checked above
			$dbw->update( 'bot_passwords', $fields, $conds, __METHOD__ );
		}
		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$this->token = $dbw->selectField( 'bot_passwords', 'bp_token', $conds, __METHOD__ );
			$this->isSaved = true;

			return $res;
		}

		// Messages: botpasswords-insert-failed, botpasswords-update-failed
		return Status::newFatal( "botpasswords-{$operation}-failed", $this->appId );
	}

	/**
	 * Delete the BotPassword from the database
	 * @return bool Success
	 */
	public function delete() {
		$conds = [
			'bp_user' => $this->centralId,
			'bp_app_id' => $this->appId,
		];
		$dbw = self::getDB( DB_MASTER );
		$dbw->delete( 'bot_passwords', $conds, __METHOD__ );
		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$this->token = '**unsaved**';
			$this->isSaved = false;
		}
		return $ok;
	}

	/**
	 * Invalidate all passwords for a user, by name
	 * @param string $username User name
	 * @return bool Whether any passwords were invalidated
	 */
	public static function invalidateAllPasswordsForUser( $username ) {
		$centralId = CentralIdLookup::factory()->centralIdFromName(
			$username, CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST
		);
		return $centralId && self::invalidateAllPasswordsForCentralId( $centralId );
	}

	/**
	 * Invalidate all passwords for a user, by central ID
	 * @param int $centralId
	 * @return bool Whether any passwords were invalidated
	 */
	public static function invalidateAllPasswordsForCentralId( $centralId ) {
		global $wgEnableBotPasswords;

		if ( !$wgEnableBotPasswords ) {
			return false;
		}

		$dbw = self::getDB( DB_MASTER );
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
	 * @param string $username User name
	 * @return bool Whether any passwords were removed
	 */
	public static function removeAllPasswordsForUser( $username ) {
		$centralId = CentralIdLookup::factory()->centralIdFromName(
			$username, CentralIdLookup::AUDIENCE_RAW, CentralIdLookup::READ_LATEST
		);
		return $centralId && self::removeAllPasswordsForCentralId( $centralId );
	}

	/**
	 * Remove all passwords for a user, by central ID
	 * @param int $centralId
	 * @return bool Whether any passwords were removed
	 */
	public static function removeAllPasswordsForCentralId( $centralId ) {
		global $wgEnableBotPasswords;

		if ( !$wgEnableBotPasswords ) {
			return false;
		}

		$dbw = self::getDB( DB_MASTER );
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
	 * @return array|false
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
		global $wgEnableBotPasswords, $wgPasswordAttemptThrottle;

		if ( !$wgEnableBotPasswords ) {
			return Status::newFatal( 'botpasswords-disabled' );
		}

		$manager = MediaWiki\Session\SessionManager::singleton();
		$provider = $manager->getProvider( BotPasswordSessionProvider::class );
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
		if ( !empty( $wgPasswordAttemptThrottle ) ) {
			$throttle = new MediaWiki\Auth\Throttler( $wgPasswordAttemptThrottle, [
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
