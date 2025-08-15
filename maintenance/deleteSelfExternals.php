<?php
/**
 * Delete self-references to $wgServer from the externallinks table.
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

use MediaWiki\Deferred\LinksUpdate\ExternalLinksTable;
use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that deletes self-references to $wgServer
 * from the externallinks table.
 *
 * @ingroup Maintenance
 */
class DeleteSelfExternals extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Delete self-references to $wgServer from externallinks' );
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		// Extract the host and scheme from $wgServer
		$server = $this->getConfig()->get( MainConfigNames::Server );
		$bits = $this->getServiceContainer()->getUrlUtils()->parse( $server );
		if ( !$bits ) {
			$this->fatalError( 'Could not parse $wgServer' );
		}

		$this->output( "Deleting self externals from $server\n" );
		$db = $this->getServiceContainer()->getConnectionProvider()->getPrimaryDatabase(
			ExternalLinksTable::VIRTUAL_DOMAIN
		);

		// If it's protocol-relative, we need to do both http and https.
		// Otherwise, just do the specified scheme.
		$host = $bits['host'];
		if ( isset( $bits['port'] ) ) {
			$host .= ':' . $bits['port'];
		}
		if ( $bits['scheme'] != '' ) {
			$conds = [ LinkFilter::getQueryConditions( $host, [ 'protocol' => $bits['scheme'] . '://' ] ) ];
		} else {
			$conds = [
				LinkFilter::getQueryConditions( $host, [ 'protocol' => 'http://' ] ),
				LinkFilter::getQueryConditions( $host, [ 'protocol' => 'https://' ] ),
			];
		}

		// Convert the array of $conds into an IExpression object for use in the DELETE query
		// The use of array_filter is just there for a sanity check, as LinkFilter::getQueryConditions
		// only returns false if the host was invalid (we have already validated this above).
		$conds = array_map( static function ( $cond ) use ( $db ) {
			return $db->andExpr( $cond );
		}, array_filter( $conds ) );
		$domainExpr = $db->orExpr( $conds );

		$totalRows = 0;
		$batchStart = 0;
		$batchEnd = $batchStart + $this->getBatchSize();
		do {
			$this->output( "Deleting self-externals with el_id $batchStart to $batchEnd\n" );

			$db->newDeleteQueryBuilder()
				->deleteFrom( 'externallinks' )
				->where( $domainExpr )
				->andWhere( $db->expr( 'el_id', '>', $batchStart ) )
				->andWhere( $db->expr( 'el_id', '<=', $batchEnd ) )
				->caller( __METHOD__ )
				->execute();
			$rowsDeletedInThisBatch = $db->affectedRows();
			$totalRows += $rowsDeletedInThisBatch;

			$batchStart += $this->getBatchSize();
			$batchEnd += $this->getBatchSize();
			$this->waitForReplication();
		} while ( $rowsDeletedInThisBatch );

		$this->output( "done; deleted $totalRows rows\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DeleteSelfExternals::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
