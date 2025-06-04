<?php
/**
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
 * @ingroup Auth
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
