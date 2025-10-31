<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Auth;

use MediaWiki\Password\Password;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * A primary authentication provider that uses the temporary password field in
 * the 'user' table.
 *
 * A successful login will force a password reset.
 *
 * @note For proper operation, this should generally come before any other
 *  password-based authentication providers.
 *
 * @ingroup Auth
 * @since 1.27
 */
class TemporaryPasswordPrimaryAuthenticationProvider
	extends AbstractTemporaryPasswordPrimaryAuthenticationProvider
{

	/** @inheritDoc */
	public function testUserExists( $username, $flags = IDBAccessObject::READ_NORMAL ) {
		$username = $this->userNameUtils->getCanonical( $username, UserRigorOptions::RIGOR_USABLE );
		if ( $username === false ) {
			return false;
		}
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );
		return (bool)$db->newSelectQueryBuilder()
			->select( [ 'user_id' ] )
			->from( 'user' )
			->where( [ 'user_name' => $username ] )
			->caller( __METHOD__ )->fetchField();
	}

	/** @inheritDoc */
	protected function getTemporaryPassword( string $username, $flags = IDBAccessObject::READ_NORMAL ): array {
		$db = DBAccessObjectUtils::getDBFromRecency( $this->dbProvider, $flags );
		$row = $db->newSelectQueryBuilder()
			->select( [ 'user_newpassword', 'user_newpass_time' ] )
			->from( 'user' )
			->where( [ 'user_name' => $username ] )
			->caller( __METHOD__ )->fetchRow();

		if ( !$row ) {
			return [ null, null ];
		}
		return [
			$this->getPassword( $row->user_newpassword ),
			$row->user_newpass_time,
		];
	}

	/** @inheritDoc */
	protected function setTemporaryPassword( string $username, Password $tempPassHash, $tempPassTime ): void {
		$db = $this->dbProvider->getPrimaryDatabase();
		$db->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [
				'user_newpassword' => $tempPassHash->toString(),
				'user_newpass_time' => $db->timestampOrNull( $tempPassTime ),
			] )
			->where( [ 'user_name' => $username ] )
			->caller( __METHOD__ )->execute();
	}

}
