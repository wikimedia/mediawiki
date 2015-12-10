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
use MediaWiki\Session\SessionInfo;

/**
 * Utility class for bot passwords
 * @since 1.27
 */
class BotPassword implements IDBAccessObject {

	const APPID_LENGTH = 32;

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
	 * @param int $flags IDBAccessObject read flags
	 */
	protected function __construct( $row, $flags = self::READ_NORMAL ) {
		$this->flags = $flags;

		$this->centralId = (int)$row->bp_user;
		$this->appId = $row->bp_app_id;
		$this->token = $row->bp_token;
		$this->restrictions = MWRestrictions::newFromJson( $row->bp_restrictions );
		$this->grants = FormatJson::decode( $row->bp_grants );
	}

	/**
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
	 * @param int $centralId from CentralIdLookup
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public static function newFromCentralId( $centralId, $appId, $flags = self::READ_NORMAL ) {
		global $wgBotPasswordsDatabase;

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = wfGetDB( $index, array(), $wgBotPasswordsDatabase );
		$row = $db->selectRow(
			'bot_passwords',
			array( 'bp_user', 'bp_app_id', 'bp_token', 'bp_restrictions', 'bp_grants' ),
			array( 'bp_user' => $centralId, 'bp_app_id' => $appId ),
			__METHOD__,
			$options
		);
		return $row ? new self( $row, $flags ) : null;
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
		global $wgBotPasswordsDatabase;

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $this->flags );
		$db = wfGetDB( $index, array(), $wgBotPasswordsDatabase );
		$password = $db->selectField(
			'bot_passwords',
			'bp_password',
			array( 'bp_user' => $this->centralId, 'bp_app_id' => $this->appId ),
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
		global $wgBotPasswordsDatabase;

		$dbw = wfGetDB( DB_MASTER, array(), $wgBotPasswordsDatabase );
		$dbw->update(
			'bot_passwords',
			array( 'bp_password' => PasswordFactory::newInvalidPassword()->toString() ),
			array( 'bp_user' => $centralId ),
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
		global $wgBotPasswordsDatabase;

		$dbw = wfGetDB( DB_MASTER, array(), $wgBotPasswordsDatabase );
		$dbw->delete(
			'bot_passwords',
			array( 'bp_user' => $centralId ),
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
		$provider = $manager->getProvider(
			'MediaWiki\\Session\\BotPasswordSessionProvider'
		);
		if ( !$provider ) {
			return Status::newFatal( 'botpasswords-no-provider' );
		}

		// Split name into name+appId
		$sep = self::getSeparator();
		if ( strpos( $username, $sep ) === false ) {
			return Status::newFatal( 'noname' );
		}
		list( $name, $appId ) = explode( $sep, $username, 2 );

		// Find the named user
		$user = User::newFromName( $name );
		if ( !$user || $user->isAnon() ) {
			return Status::newFatal( 'nosuchuser', $username );
		}

		// Get the bot password
		$bp = self::newFromUser( $user, $appId );
		if ( !$bp ) {
			return Status::newFatal( 'nosuchuser', $username );
		}

		// Check restrictions
		$status = $bp->getRestrictions()->check( $request );
		if ( !$status->isOk() ) {
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
