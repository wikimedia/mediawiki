<?php
/**
 * BotPassword interaction with databases
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

namespace MediaWiki\User;

use BotPassword;
use CentralIdLookup;
use DBAccessObjectUtils;
use FormatJson;
use IDBAccessObject;
use MediaWiki\Config\ServiceOptions;
use MWCryptRand;
use MWRestrictions;
use Password;
use PasswordFactory;
use StatusValue;
use User;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * @author DannyS712
 * @since 1.37
 */
class BotPasswordStore implements IDBAccessObject {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'EnableBotPasswords',
		'BotPasswordsCluster',
		'BotPasswordsDatabase',
	];

	/** @var ServiceOptions */
	private $options;

	/** @var LBFactory */
	private $lbFactory;

	/** @var CentralIdLookup */
	private $centralIdLookup;

	/**
	 * @param ServiceOptions $options
	 * @param CentralIdLookup $centralIdLookup
	 * @param LBFactory $lbFactory
	 */
	public function __construct(
		ServiceOptions $options,
		CentralIdLookup $centralIdLookup,
		LBFactory $lbFactory
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->centralIdLookup = $centralIdLookup;
		$this->lbFactory = $lbFactory;
	}

	/**
	 * Get a database connection for the bot passwords database
	 * @param int $db Index of the connection to get, e.g. DB_PRIMARY or DB_REPLICA.
	 * @return IDatabase
	 * @internal
	 */
	public function getDatabase( int $db ): IDatabase {
		if ( $this->options->get( 'BotPasswordsCluster' ) ) {
			$loadBalancer = $this->lbFactory->getExternalLB(
				$this->options->get( 'BotPasswordsCluster' )
			);
		} else {
			$loadBalancer = $this->lbFactory->getMainLB(
				$this->options->get( 'BotPasswordsDatabase' )
			);
		}
		return $loadBalancer->getConnectionRef(
			$db,
			[],
			$this->options->get( 'BotPasswordsDatabase' )
		);
	}

	/**
	 * Load a BotPassword from the database based on a UserIdentity object
	 * @param UserIdentity $userIdentity
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public function getByUser(
		UserIdentity $userIdentity,
		string $appId,
		int $flags = self::READ_NORMAL
	): ?BotPassword {
		if ( !$this->options->get( 'EnableBotPasswords' ) ) {
			return null;
		}

		$centralId = $this->centralIdLookup->centralIdFromLocalUser(
			$userIdentity,
			CentralIdLookup::AUDIENCE_RAW,
			$flags
		);
		return $centralId ? $this->getByCentralId( $centralId, $appId, $flags ) : null;
	}

	/**
	 * Load a BotPassword from the database
	 * @param int $centralId from CentralIdLookup
	 * @param string $appId
	 * @param int $flags IDBAccessObject read flags
	 * @return BotPassword|null
	 */
	public function getByCentralId(
		int $centralId,
		string $appId,
		int $flags = self::READ_NORMAL
	): ?BotPassword {
		if ( !$this->options->get( 'EnableBotPasswords' ) ) {
			return null;
		}

		list( $index, $options ) = DBAccessObjectUtils::getDBOptions( $flags );
		$db = $this->getDatabase( $index );
		$row = $db->selectRow(
			'bot_passwords',
			[ 'bp_user', 'bp_app_id', 'bp_token', 'bp_restrictions', 'bp_grants' ],
			[ 'bp_user' => $centralId, 'bp_app_id' => $appId ],
			__METHOD__,
			$options
		);
		return $row ? new BotPassword( $row, true, $flags ) : null;
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
	public function newUnsavedBotPassword(
		array $data,
		int $flags = self::READ_NORMAL
	): ?BotPassword {
		if ( isset( $data['user'] ) && ( !$data['user'] instanceof UserIdentity ) ) {
			return null;
		}

		$row = (object)[
			'bp_user' => 0,
			'bp_app_id' => trim( $data['appId'] ?? '' ),
			'bp_token' => '**unsaved**',
			'bp_restrictions' => $data['restrictions'] ?? MWRestrictions::newDefault(),
			'bp_grants' => $data['grants'] ?? [],
		];

		if (
			$row->bp_app_id === '' ||
			strlen( $row->bp_app_id ) > BotPassword::APPID_MAXLENGTH ||
			!$row->bp_restrictions instanceof MWRestrictions ||
			!is_array( $row->bp_grants )
		) {
			return null;
		}

		$row->bp_restrictions = $row->bp_restrictions->toJson();
		$row->bp_grants = FormatJson::encode( $row->bp_grants );

		if ( isset( $data['user'] ) ) {
			// Must be a UserIdentity object, already checked above
			$row->bp_user = $this->centralIdLookup->centralIdFromLocalUser(
				$data['user'],
				CentralIdLookup::AUDIENCE_RAW,
				$flags
			);
		} elseif ( isset( $data['username'] ) ) {
			$row->bp_user = $this->centralIdLookup->centralIdFromName(
				$data['username'],
				CentralIdLookup::AUDIENCE_RAW,
				$flags
			);
		} elseif ( isset( $data['centralId'] ) ) {
			$row->bp_user = $data['centralId'];
		}
		if ( !$row->bp_user ) {
			return null;
		}

		return new BotPassword( $row, false, $flags );
	}

	/**
	 * Save the new BotPassword to the database
	 *
	 * @internal
	 *
	 * @param BotPassword $botPassword
	 * @param Password|null $password Use null for an invalid password
	 * @return StatusValue if everything worked, the value of the StatusValue is the new token
	 */
	public function insertBotPassword(
		BotPassword $botPassword,
		Password $password = null
	): StatusValue {
		$res = $this->validateBotPassword( $botPassword );
		if ( !$res->isGood() ) {
			return $res;
		}

		if ( $password === null ) {
			$password = PasswordFactory::newInvalidPassword();
		}
		$fields = [
			'bp_user' => $botPassword->getUserCentralId(),
			'bp_app_id' => $botPassword->getAppId(),
			'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
			'bp_restrictions' => $botPassword->getRestrictions()->toJson(),
			'bp_grants' => FormatJson::encode( $botPassword->getGrants() ),
			'bp_password' => $password->toString(),
		];

		$dbw = $this->getDatabase( DB_PRIMARY );
		$dbw->insert(
			'bot_passwords',
			$fields,
			__METHOD__,
			[ 'IGNORE' ]
		);

		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$token = $dbw->selectField(
				'bot_passwords',
				'bp_token',
				[
					'bp_user' => $botPassword->getUserCentralId(),
					'bp_app_id' => $botPassword->getAppId(),
				],
				__METHOD__
			);
			return StatusValue::newGood( $token );
		}
		return StatusValue::newFatal( 'botpasswords-insert-failed', $botPassword->getAppId() );
	}

	/**
	 * Update an existing BotPassword in the database
	 *
	 * @internal
	 *
	 * @param BotPassword $botPassword
	 * @param Password|null $password Use null for an invalid password
	 * @return StatusValue if everything worked, the value of the StatusValue is the new token
	 */
	public function updateBotPassword(
		BotPassword $botPassword,
		Password $password = null
	): StatusValue {
		$res = $this->validateBotPassword( $botPassword );
		if ( !$res->isGood() ) {
			return $res;
		}

		$conds = [
			'bp_user' => $botPassword->getUserCentralId(),
			'bp_app_id' => $botPassword->getAppId(),
		];
		$fields = [
			'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
			'bp_restrictions' => $botPassword->getRestrictions()->toJson(),
			'bp_grants' => FormatJson::encode( $botPassword->getGrants() ),
		];
		if ( $password !== null ) {
			$fields['bp_password'] = $password->toString();
		}

		$dbw = $this->getDatabase( DB_PRIMARY );
		$dbw->update(
			'bot_passwords',
			$fields,
			$conds,
			__METHOD__
		);

		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$token = $dbw->selectField(
				'bot_passwords',
				'bp_token',
				$conds,
				__METHOD__
			);
			return StatusValue::newGood( $token );
		}
		return StatusValue::newFatal( 'botpasswords-update-failed', $botPassword->getAppId() );
	}

	/**
	 * Check if a BotPassword is valid to save in the database (either inserting a new
	 * one or updating an existing one) based on the size of the restrictions and grants
	 *
	 * @param BotPassword $botPassword
	 * @return StatusValue
	 */
	private function validateBotPassword( BotPassword $botPassword ): StatusValue {
		$res = StatusValue::newGood();

		$restrictions = $botPassword->getRestrictions()->toJson();
		if ( strlen( $restrictions ) > BotPassword::RESTRICTIONS_MAXLENGTH ) {
			$res->fatal( 'botpasswords-toolong-restrictions' );
		}

		$grants = FormatJson::encode( $botPassword->getGrants() );
		if ( strlen( $grants ) > BotPassword::GRANTS_MAXLENGTH ) {
			$res->fatal( 'botpasswords-toolong-grants' );
		}

		return $res;
	}

	/**
	 * Delete an existing BotPassword in the database
	 *
	 * @param BotPassword $botPassword
	 * @return bool
	 */
	public function deleteBotPassword( BotPassword $botPassword ): bool {
		$dbw = $this->getDatabase( DB_PRIMARY );
		$dbw->delete(
			'bot_passwords',
			[
				'bp_user' => $botPassword->getUserCentralId(),
				'bp_app_id' => $botPassword->getAppId(),
			],
			__METHOD__
		);

		return (bool)$dbw->affectedRows();
	}

	/**
	 * Invalidate all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were invalidated
	 */
	public function invalidateUserPasswords( string $username ): bool {
		if ( !$this->options->get( 'EnableBotPasswords' ) ) {
			return false;
		}

		$centralId = $this->centralIdLookup->centralIdFromName(
			$username,
			CentralIdLookup::AUDIENCE_RAW,
			CentralIdLookup::READ_LATEST
		);
		if ( !$centralId ) {
			return false;
		}

		$dbw = $this->getDatabase( DB_PRIMARY );
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
	public function removeUserPasswords( string $username ): bool {
		if ( !$this->options->get( 'EnableBotPasswords' ) ) {
			return false;
		}

		$centralId = $this->centralIdLookup->centralIdFromName(
			$username,
			CentralIdLookup::AUDIENCE_RAW,
			CentralIdLookup::READ_LATEST
		);
		if ( !$centralId ) {
			return false;
		}

		$dbw = $this->getDatabase( DB_PRIMARY );
		$dbw->delete(
			'bot_passwords',
			[ 'bp_user' => $centralId ],
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

}
