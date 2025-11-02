<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\User\CentralId\CentralIdLookup;
use MWCryptRand;
use MWRestrictions;
use StatusValue;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * BotPassword interaction with databases
 *
 * @author DannyS712
 * @since 1.37
 */
class BotPasswordStore {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EnableBotPasswords,
	];

	private ServiceOptions $options;
	private IConnectionProvider $dbProvider;
	private CentralIdLookup $centralIdLookup;

	/**
	 * @param ServiceOptions $options
	 * @param CentralIdLookup $centralIdLookup
	 * @param IConnectionProvider $dbProvider
	 */
	public function __construct(
		ServiceOptions $options,
		CentralIdLookup $centralIdLookup,
		IConnectionProvider $dbProvider
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->centralIdLookup = $centralIdLookup;
		$this->dbProvider = $dbProvider;
	}

	/**
	 * Get a database connection for the bot passwords database
	 * @return IReadableDatabase
	 * @internal
	 */
	public function getReplicaDatabase(): IReadableDatabase {
		return $this->dbProvider->getReplicaDatabase( 'virtual-botpasswords' );
	}

	/**
	 * Get a database connection for the bot passwords database
	 * @return IDatabase
	 * @internal
	 */
	public function getPrimaryDatabase(): IDatabase {
		return $this->dbProvider->getPrimaryDatabase( 'virtual-botpasswords' );
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
		int $flags = IDBAccessObject::READ_NORMAL
	): ?BotPassword {
		if ( !$this->options->get( MainConfigNames::EnableBotPasswords ) ) {
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
		int $flags = IDBAccessObject::READ_NORMAL
	): ?BotPassword {
		if ( !$this->options->get( MainConfigNames::EnableBotPasswords ) ) {
			return null;
		}

		if ( ( $flags & IDBAccessObject::READ_LATEST ) == IDBAccessObject::READ_LATEST ) {
			$db = $this->dbProvider->getPrimaryDatabase( 'virtual-botpasswords' );
		} else {
			$db = $this->dbProvider->getReplicaDatabase( 'virtual-botpasswords' );
		}
		$row = $db->newSelectQueryBuilder()
			->select( [ 'bp_user', 'bp_app_id', 'bp_token', 'bp_restrictions', 'bp_grants' ] )
			->from( 'bot_passwords' )
			->where( [ 'bp_user' => $centralId, 'bp_app_id' => $appId ] )
			->recency( $flags )
			->caller( __METHOD__ )->fetchRow();
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
		int $flags = IDBAccessObject::READ_NORMAL
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
		?Password $password = null
	): StatusValue {
		$res = $this->validateBotPassword( $botPassword );
		if ( !$res->isGood() ) {
			return $res;
		}

		$password ??= PasswordFactory::newInvalidPassword();

		$dbw = $this->getPrimaryDatabase();
		$dbw->newInsertQueryBuilder()
			->insertInto( 'bot_passwords' )
			->ignore()
			->row( [
				'bp_user' => $botPassword->getUserCentralId(),
				'bp_app_id' => $botPassword->getAppId(),
				'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
				'bp_restrictions' => $botPassword->getRestrictions()->toJson(),
				'bp_grants' => FormatJson::encode( $botPassword->getGrants() ),
				'bp_password' => $password->toString(),
			] )
			->caller( __METHOD__ )->execute();

		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$token = $dbw->newSelectQueryBuilder()
				->select( 'bp_token' )
				->from( 'bot_passwords' )
				->where( [ 'bp_user' => $botPassword->getUserCentralId(), 'bp_app_id' => $botPassword->getAppId(), ] )
				->caller( __METHOD__ )->fetchField();
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
		?Password $password = null
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

		$dbw = $this->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'bot_passwords' )
			->set( $fields )
			->where( $conds )
			->caller( __METHOD__ )->execute();

		$ok = (bool)$dbw->affectedRows();
		if ( $ok ) {
			$token = $dbw->newSelectQueryBuilder()
				->select( 'bp_token' )
				->from( 'bot_passwords' )
				->where( $conds )
				->caller( __METHOD__ )->fetchField();
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
		$dbw = $this->getPrimaryDatabase();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'bot_passwords' )
			->where( [ 'bp_user' => $botPassword->getUserCentralId() ] )
			->andWhere( [ 'bp_app_id' => $botPassword->getAppId() ] )
			->caller( __METHOD__ )->execute();

		return (bool)$dbw->affectedRows();
	}

	/**
	 * Invalidate all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were invalidated
	 */
	public function invalidateUserPasswords( string $username ): bool {
		if ( !$this->options->get( MainConfigNames::EnableBotPasswords ) ) {
			return false;
		}

		$centralId = $this->centralIdLookup->centralIdFromName(
			$username,
			CentralIdLookup::AUDIENCE_RAW,
			IDBAccessObject::READ_LATEST
		);
		if ( !$centralId ) {
			return false;
		}

		$dbw = $this->getPrimaryDatabase();
		$dbw->newUpdateQueryBuilder()
			->update( 'bot_passwords' )
			->set( [ 'bp_password' => PasswordFactory::newInvalidPassword()->toString() ] )
			->where( [ 'bp_user' => $centralId ] )
			->caller( __METHOD__ )->execute();
		return (bool)$dbw->affectedRows();
	}

	/**
	 * Remove all passwords for a user, by name
	 * @param string $username
	 * @return bool Whether any passwords were removed
	 */
	public function removeUserPasswords( string $username ): bool {
		if ( !$this->options->get( MainConfigNames::EnableBotPasswords ) ) {
			return false;
		}

		$centralId = $this->centralIdLookup->centralIdFromName(
			$username,
			CentralIdLookup::AUDIENCE_RAW,
			IDBAccessObject::READ_LATEST
		);
		if ( !$centralId ) {
			return false;
		}

		$dbw = $this->getPrimaryDatabase();
		$dbw->newDeleteQueryBuilder()
			->deleteFrom( 'bot_passwords' )
			->where( [ 'bp_user' => $centralId ] )
			->caller( __METHOD__ )->execute();
		return (bool)$dbw->affectedRows();
	}

}
