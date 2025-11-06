<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\JobQueue\Utils;

use MediaWiki\Deferred\AutoCommitUpdate;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;

/**
 * Helper for a Job that writes data derived from page content to the database.
 *
 * Used by RefreshLinksJob via LinksUpdate.
 *
 * @ingroup JobQueue
 */
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
			static function () use ( $dbw, $namespace, $dbkeys, $fname ) {
				$services = MediaWikiServices::getInstance();
				$dbProvider = $services->getConnectionProvider();
				// Determine which pages need to be updated.
				// This is necessary to prevent the job queue from smashing the DB with
				// large numbers of concurrent invalidations of the same page.
				$now = $dbw->timestamp();
				$ids = $dbw->newSelectQueryBuilder()
					->select( 'page_id' )
					->from( 'page' )
					->where( [ 'page_namespace' => $namespace ] )
					->andWhere( [ 'page_title' => $dbkeys ] )
					->andWhere( $dbw->expr( 'page_touched', '<', $now ) )
					->caller( $fname )->fetchFieldValues();

				if ( !$ids ) {
					return;
				}

				$batchSize =
					$services->getMainConfig()->get( MainConfigNames::UpdateRowsPerQuery );
				$ticket = $dbProvider->getEmptyTransactionTicket( $fname );
				$idBatches = array_chunk( $ids, $batchSize );
				foreach ( $idBatches as $idBatch ) {
					$dbw->newUpdateQueryBuilder()
						->update( 'page' )
						->set( [ 'page_touched' => $now ] )
						->where( [ 'page_id' => $idBatch ] )
						->andWhere( $dbw->expr( 'page_touched', '<', $now ) ) // handle races
						->caller( $fname )->execute();
					if ( count( $idBatches ) > 1 ) {
						$dbProvider->commitAndWaitForReplication( $fname, $ticket );
					}
				}
			}
		) );
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PurgeJobUtils::class, 'PurgeJobUtils' );
