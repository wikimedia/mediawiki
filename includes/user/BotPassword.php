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

use MediaWiki\Session\BotPasswordSessionProvider;

/**
 * Utility class for bot passwords
 * @since 1.27
 */
class BotPassword implements IDBAccessObject {

	const APPID_MAXLENGTH = 32;

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
	 * @param int $db Index of the connection to get, e.g. DB_MASTER or DB_SLAVE.
	 * @return DatabaseBase
	 */
	public static function getDB( $db ) {
		global $wgBotPasswordsCluster, $wgBotPasswordsDatabase;

		$lb = $wgBotPasswordsCluster
			? wfGetLBFactory()->getExternalLB( $wgBotPasswordsCluster )
			: wfGetLB( $wgBotPasswordsDatabase );
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
			'bp_restrictions' => isset( $data['restrictions'] )
				? $data['restrictions']
				: MWRestrictions::newDefault(),
			'bp_grants' => isset( $data['grants'] ) ? $data['grants'] : [],
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

		$passwordFactory = new \PasswordFactory();
		$passwordFactory->init( \RequestContext::getMain()->getConfig() );
		try {
			return $passwordFactory->newFromCiphertext( $password );
		} catch ( PasswordError $ex ) {
			return PasswordFactory::newInvalidPassword();
		}
	}

	/**
	 * Save the BotPassword to the database
	 * @param string $operation 'update' or 'insert'
	 * @param Password|null $password Password to set.
	 * @return bool Success
	 */
	public function save( $operation, Password $password = null ) {
		$conds = [
			'bp_user' => $this->centralId,
			'bp_app_id' => $this->appId,
		];
		$fields = [
			'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
			'bp_restrictions' => $this->restrictions->toJson(),
			'bp_grants' => FormatJson::encode( $this->grants ),
		];

		if ( $password !== null ) {
			$fields['bp_password'] = $password->toString();
		} elseif ( $operation === 'insert' ) {
			$fields['bp_password'] = PasswordFactory::newInvalidPassword()->toString();
		}

		$dbw = self::getDB( DB_MASTER );
		switch ( $operation ) {
			case 'insert':
				$dbw->insert( 'bot_passwords', $fields + $conds, __METHOD__, [ 'IGNORE' ] );
				break;

			case 'update':
				$dbw->update( 'bot_passwords', $fields, $conds, __METHOD__ );
				break;

			default:
				return false;
		}
		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$this->token = $dbw->selectField( 'bot_passwords', 'bp_token', $conds, __METHOD__ );
			$this->isSaved = true;
		}
		return $ok;
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
	 * Try to log the user in
	 * @param string $username Combined user name and app ID
	 * @param string $password Supplied password
	 * @param WebRequest $request
	 * @return Status On success, the good status's value is the new Session object
	 */
	public static function login( $username, $password, WebRequest $request ) {
		global $wgEnableBotPasswords;

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
			return Status::newFatal( 'botpasswords-invalid-name', $sep );
		}
		list( $name, $appId ) = explode( $sep, $username, 2 );

		// Find the named user
		$user = User::newFromName( $name );
		if ( !$user || $user->isAnon() ) {
			return Status::newFatal( 'nosuchuser', $name );
		}

		// Get the bot password
		$bp = self::newFromUser( $user, $appId );
		if ( !$bp ) {
			return Status::newFatal( 'botpasswords-not-exist', $name, $appId );
		}

		// Check restrictions
		$status = $bp->getRestrictions()->check( $request );
		if ( !$status->isOK() ) {
			return Status::newFatal( 'botpasswords-restriction-failed' );
		}

		// Check the password
		if ( !$bp->getPassword()->equals( $password ) ) {
			return Status::newFatal( 'wrongpassword' );
		}

		// Ok! Create the session.
		return Status::newGood( $provider->newSessionForRequest( $user, $bp, $request ) );
	}
}
