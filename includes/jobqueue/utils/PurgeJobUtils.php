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
use Wikimedia\Rdbms\IDatabase;
use MediaWiki\MediaWikiServices;

class PurgeJobUtils {
	/**
	 * Invalidate the cache of a list of pages from a single namespace.
	 * This is intended for use by subclasses.
	 *
	 * @param IDatabase $dbw
	 * @param int $namespace Namespace number
	 * @param array $dbkeys
	 */
	public static function invalidatePages( IDatabase $dbw, $namespace, array $dbkeys ) {
		if ( $dbkeys === [] ) {
			return;
		}

		$dbw->onTransactionIdle(
			function () use ( $dbw, $namespace, $dbkeys ) {
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
					__METHOD__
				);

				if ( !$ids ) {
					return;
				}

				$batchSize = $services->getMainConfig()->get( 'UpdateRowsPerQuery' );
				$ticket = $lbFactory->getEmptyTransactionTicket( __METHOD__ );
				foreach ( array_chunk( $ids, $batchSize ) as $idBatch ) {
					$dbw->update(
						'page',
						[ 'page_touched' => $now ],
						[
							'page_id' => $idBatch,
							'page_touched < ' . $dbw->addQuotes( $now ) // handle races
						],
						__METHOD__
					);
					$lbFactory->commitAndWaitForReplication( __METHOD__, $ticket );
				}
			},
			__METHOD__
		);
	}
}
