<?php
/**
 * Base code for update jobs that put some secondary data extracted
 * from article content into the database.
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
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

class PurgeJobUtils {
	/**
	 * Invalidate the cache of a list of pages from a single namespace.
	 * This is intended for use by subclasses.
	 *
	 * @param IDatabase $dbw
	 * @param int $namespace Namespace number
	 * @param string[] $dbkeys
	 */
	public static function invalidatePages( IDatabase $dbw, $namespace, array $dbkeys ) {
		if ( $dbkeys === [] ) {
			return;
		}
		$fname = __METHOD__;

		DeferredUpdates::addUpdate( new AutoCommitUpdate(
			$dbw,
			__METHOD__,
			function () use ( $dbw, $namespace, $dbkeys, $fname ) {
				$services = MediaWikiServices::getInstance();
				$lbFactory = $services->getDBLoadBalancerFactory();
				// Determine which pages need to be updated.
				// This is necessary to prevent the job queue from smashing the DB with
				// large numbers of concurrent invalidations of the same page.
				$now = $dbw->timestamp();
				$ids = $dbw->selectFieldValues(
					'page',
					'page_id',
					[
						'page_namespace' => $namespace,
						'page_title' => $dbkeys,
						'page_touched < ' . $dbw->addQuotes( $now )
					],
					$fname
				);

				if ( !$ids ) {
					return;
				}

				$batchSize = $services->getMainConfig()->get( 'UpdateRowsPerQuery' );
				$ticket = $lbFactory->getEmptyTransactionTicket( $fname );
				$idBatches = array_chunk( $ids, $batchSize );
				foreach ( $idBatches as $idBatch ) {
					$dbw->update(
						'page',
						[ 'page_touched' => $now ],
						[
							'page_id' => $idBatch,
							'page_touched < ' . $dbw->addQuotes( $now ) // handle races
						],
						$fname
					);
					if ( count( $idBatches ) > 1 ) {
						$lbFactory->commitAndWaitForReplication( $fname, $ticket );
					}
				}
			}
		) );
	}
}
