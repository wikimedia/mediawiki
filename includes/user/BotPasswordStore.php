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
use FormatJson;
use MediaWiki\Config\ServiceOptions;
use MWCryptRand;
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
class BotPasswordStore {

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
	public function getDatabase( int $db ) : IDatabase {
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
	) : StatusValue {
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
	) : StatusValue {
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
	private function validateBotPassword( BotPassword $botPassword ) : StatusValue {
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
	public function deleteBotPassword( BotPassword $botPassword ) : bool {
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
	 * @param string $username User name
	 * @return bool Whether any passwords were invalidated
	 */
	public function invalidateUserPasswords( string $username ) : bool {
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
	 * @param string $username User name
	 * @return bool Whether any passwords were removed
	 */
	public function removeUserPasswords( string $username ) : bool {
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
