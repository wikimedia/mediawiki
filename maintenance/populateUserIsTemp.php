<?php
/**
 * Fills the user_is_temp column of the user table for users created before MW 1.42.
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
 * @ingroup Maintenance
 */

use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that Fills the user_is_temp column of the user table for users created before MW 1.42.
 *
 * @since 1.42
 * @ingroup Maintenance
 */
class PopulateUserIsTemp extends LoggedUpdateMaintenance {

	private TempUserConfig $tempUserConfig;
	private IDatabase $dbw;
	private IReadableDatabase $dbr;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Populates the user_is_temp field of the user table.' );
		$this->setBatchSize( 200 );
	}

	/** @inheritDoc */
	protected function getUpdateKey() {
		return __CLASS__;
	}

	/** @inheritDoc */
	protected function doDBUpdates() {
		$this->initServices();

		if ( !$this->tempUserConfig->isEnabled() ) {
			// If temporary user auto-creation is disabled, then just return early as there will be no rows to update.
			return true;
		}

		// Generate a SelectQueryBuilder that selects all temporary users (based on the configured match patterns)
		// which do have user_is_temp set to 0 (the default) in the the user table.
		$queryBuilder = $this->dbr->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user' )
			->where( [
				'user_is_temp' => 0,
				$this->tempUserConfig->getMatchCondition( $this->dbr, 'user_name', IExpression::LIKE ),
			] )
			->limit( $this->getBatchSize() ?? 200 )
			->caller( __METHOD__ );

		do {
			// Get a batch of user IDs for temporary accounts that do not have user_is_temp set to 1.
			$batch = $queryBuilder->fetchFieldValues();
			if ( count( $batch ) ) {
				// If there are user IDs in the batch, then update the user_is_temp column to '1' for these rows.
				$this->dbw->newUpdateQueryBuilder()
					->update( 'user' )
					->set( [ 'user_is_temp' => 1 ] )
					->where( [ 'user_id' => $batch ] )
					->caller( __METHOD__ )
					->execute();
			}
		} while ( count( $batch ) >= ( $this->getBatchSize() ?? 200 ) );

		return true;
	}

	/**
	 * Initialise the services and database connections used by this script.
	 *
	 * This code is not in ::doDBUpdates() so that this can be skipped in unit tests
	 * and the IReadableDatabase object can be strongly typed.
	 *
	 * @return void
	 */
	protected function initServices(): void {
		$this->tempUserConfig = $this->getServiceContainer()->getTempUserConfig();
		$this->dbw = $this->getDB( DB_PRIMARY );
		$this->dbr = $this->getDB( DB_REPLICA );
	}
}

$maintClass = PopulateUserIsTemp::class;
require_once RUN_MAINTENANCE_IF_MAIN;
