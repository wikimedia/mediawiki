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
 * @ingroup Maintenance
 */

use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * An error in a previous version of MediaWiki caused B type passwords to be written with
 * an :A: prefix to the database. This script corrects this.
 *
 * @since 1.44
 * @ingroup Maintenance
 */
class FixWrongPasswordPrefixes extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->setBatchSize( 100 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$dbw = $this->getPrimaryDB();
		$batchSize = $this->getBatchSize();
		$minUserId = 0;

		while ( true ) {
			$res = $dbw->newSelectQueryBuilder()
				->select( [ 'user_id', 'user_password' ] )
				->from( 'user' )
				->where( $dbw->expr( 'user_id', '>', $minUserId ) )
				->andWhere(
					$dbw->expr( 'user_password', IExpression::LIKE, new LikeValue( ':A:', $dbw->anyString() ) )
				)
				->orderBy( 'user_id' )
				->limit( $batchSize )
				->caller( __METHOD__ )
				->fetchResultSet();

			if ( $res->numRows() === 0 ) {
				break;
			}

			foreach ( $res as $row ) {
				$passwordHash = substr( $row->user_password, 3 );
				if ( strpos( $passwordHash, ':' ) > 0 ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'user' )
						->set( [ 'user_password' => ':B:' . $passwordHash ] )
						->where( [ 'user_id' => $row->user_id ] )
						->caller( __METHOD__ )
						->execute();
				}

				$minUserId = $row->user_id;
			}
		}

		$this->output( "Wrongly prefixed user password hashes, if present, have been fixed.\n" );
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = FixWrongPasswordPrefixes::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
