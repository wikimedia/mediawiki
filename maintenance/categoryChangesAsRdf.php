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
 */
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\TurtleRdfWriter;
use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script to provide RDF representation of the recent changes in category tree.
 *
 * @ingroup Maintenance
 * @since 1.30
 */
class CategoryChangesAsRdf extends Maintenance {
	/**
	 * Insert query
	 */
	const SPARQL_INSERT = <<<SPARQL
INSERT DATA {
%s
};

SPARQL;

	/**
	 * Delete/Insert query
	 */
	const SPARQL_DELETE_INSERT = <<<SPARQLDI
DELETE {
	?category ?x ?y
}
INSERT {
	%s
}
WHERE {
   VALUES ?category {
        %s
   }
};

SPARQLDI;

	/**
	 * @var RdfWriter
	 */
	private $rdfWriter;
	/**
	 * Categories RDF helper.
	 * @var CategoriesRdf
	 */
	private $categoriesRdf;

	public function __construct() {
		parent::__construct();

		$this->addDescription( "Generate RDF dump of category changes in a wiki." );

		$this->setBatchSize( 200 );
		$this->addOption( 'output', "Output file (default is stdout). Will be overwritten.",
			false, true );
		$this->addOption( 's', 'Starting timestamp (inclusive)', true, true );
		$this->addOption( 'e', 'Ending timestamp (exclusive)', true, true );
	}

	public function execute() {
		$outFile = $this->getOption( 'output', 'php://stdout' );

		if ( $outFile === '-' ) {
			$outFile = 'php://stdout';
		}

		$output = fopen( $outFile, 'w' );
		// SPARQL Update is close to TTL
		$this->rdfWriter = new TurtleRdfWriter();
		$this->categoriesRdf = new CategoriesRdf( $this->rdfWriter );

		$this->categoriesRdf->setupPrefixes();
		$this->rdfWriter->start();

		$prefixes = $this->rdfWriter->drain();
		// we have to strip @ from prefix, since SPARQL UPDATE doesn't use them
		$prefixes = preg_replace( '/^@/m', '', $prefixes );
		fwrite( $output, $prefixes );

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		$processed = []; // So we don't try to process same thins twice

		// Handle deletes
		// This only does "true" deletes - i.e. those that the page stays deleted
		foreach ( $this->getDeletedCatsIterator( $dbr ) as $batch ) {
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				// This can produce duplicates, we don't care
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
				$processed[$row->rc_cur_id] = true;
			}
			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, [] ) );
		}

		// Handle moves
		// Moves go before additions because if category is moved, we should not process creation
		// as it would produce wrong data - because create row has old title
		foreach ( $this->getMovedCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
				if ( isset( $processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}

				if ( $row->page_namespace != NS_CATEGORY ) {
					continue;
				}
				$this->categoriesRdf->writeCategoryData( $row->page_title );
				$pages[$row->rc_cur_id] = $row->page_title;
				$processed[$row->rc_cur_id] = true;
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages ) );
		}

		// Handle restores
		// We need to handle restores too since delete may have happened in previous update.
		foreach ( $this->getRestoredCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				if ( isset( $processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->categoriesRdf->writeCategoryData( $row->rc_title );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$processed[$row->rc_cur_id] = true;
			}

			if ( empty( $pages ) ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );

			fwrite( $output, sprintf( self::SPARQL_INSERT, $this->rdfWriter->drain() ) );
		}

		// Handle additions
		foreach ( $this->getNewCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				if ( isset( $processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->categoriesRdf->writeCategoryData( $row->rc_title );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$processed[$row->rc_cur_id] = true;
			}

			if ( empty( $pages ) ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );

			fwrite( $output, sprintf( self::SPARQL_INSERT, $this->rdfWriter->drain() ) );
		}

		// Handle changes
		foreach ( $this->getChangedCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				if ( isset( $processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->categoriesRdf->writeCategoryData( $row->rc_title );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$processed[$row->rc_cur_id] = true;
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages ) );
		}

		// Update timestamp
		$to = $this->getOption( 'e' );
		fwrite( $output, $this->updateTS( $to ) );
	}

	/**
	 * Get SPARQL for updating set of categories
	 * @param IDatabase $dbr
	 * @param string[] $deleteUrls List of URIs to be deleted, with <>
	 * @param string[] $pages List of categories: id => title
	 * @return string SPARQL query
	 */
	private function getCategoriesUpdate( IDatabase $dbr, $deleteUrls, $pages ) {
		if ( empty( $deleteUrls ) ) {
			return "";
		}

		if ( !empty( $pages ) ) {
			$this->writeParentCategories( $dbr, $pages );
		}

		return sprintf( self::SPARQL_DELETE_INSERT,
				$this->rdfWriter->drain(),
				join( ' ', $deleteUrls ) );
	}

	/**
	 * Write data for a set of categories
	 * @param IDatabase $dbr
	 * @param string[] $pages List of categories: id => title
	 */
	private function writeParentCategories( IDatabase $dbr, $pages ) {
		foreach ( $this->getCategoryLinksIterator( $dbr, array_keys( $pages ) ) as $row ) {
			$this->categoriesRdf->writeCategoryLinkData( $pages[$row->cl_from], $row->cl_to );
		}
	}

	/**
	 * Update timestamp
	 * @param string|int $timestamp Timestamp for last change
	 * @return string SPARQL Update query for timestamp.
	 */
	public function updateTS( $timestamp ) {
		$dumpUrl = '<' . wfExpandUrl( '/categoriesDump', PROTO_CANONICAL ) . '>';
		$ts = wfTimestamp( TS_ISO_8601, $timestamp );
		$tsQuery = <<<SPARQL
DELETE {
  $dumpUrl schema:dateModified ?o .
}
WHERE {
  $dumpUrl schema:dateModified ?o .
};
INSERT DATA {
  $dumpUrl schema:dateModified "$ts"^^xsd:dateTime .
}

SPARQL;
		return $tsQuery;
	}

	/**
	 * Fetch newly created categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	private function getNewCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			'recentchanges',
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 1,
		] );
		$it->setFetchColumns( [ 'rc_title', 'rc_cur_id' ] );
		return $it;
	}

	/**
	 * Fetch moved categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	private function getMovedCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			[ 'recentchanges', 'page' ],
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
		    'rc_log_type' => 'move',
			'rc_type' => RC_LOG,
		] );
		$it->addJoinConditions( [ 'page' => [ 'INNER JOIN', 'rc_cur_id = page_id' ] ] );
		$it->setFetchColumns( [ 'page_title', 'page_namespace', 'rc_cur_id', 'rc_title' ] );
		return $it;
	}

	/**
	 * Fetch deleted categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	private function getDeletedCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			[ 'recentchanges', 'page' ],
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_log_type' => 'delete',
			'rc_log_action' => 'delete',
			'rc_type' => RC_LOG,
			'page_id IS NULL',
		] );
		// We will fetch ones that do not have page record. If they do,
		// this means they were restored, thus restoring handler will pick it up.
		$it->addJoinConditions( [ 'page' => [ 'LEFT JOIN', 'rc_cur_id = page_id' ] ] );
		$it->setFetchColumns( [ 'rc_cur_id', 'rc_title' ] );
		return $it;
	}

	/**
	 * Fetch restored categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	private function getRestoredCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			[ 'recentchanges', 'page' ],
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_log_type' => 'delete',
			'rc_log_action' => 'restore',
			'rc_type' => RC_LOG,
		] );
		// We will only fetch ones that have page record
		$it->addJoinConditions( [ 'page' => [ 'INNER JOIN', 'rc_cur_id = page_id' ] ] );
		$it->setFetchColumns( [ 'rc_cur_id', 'rc_title', 'page_title' ] );
		return $it;
	}

	/**
	 * Fetch categorization changes
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	private function getChangedCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			'recentchanges',
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_type' => RC_EDIT,
		] );
		$it->setFetchColumns( [ 'rc_title', 'rc_cur_id' ] );
		return $it;
	}

	/**
	 * Add timestamp limits to iterator
	 * @param BatchRowIterator $it Iterator
	 * @param IDatabase $dbr
	 */
	private function addTimestampConditions( BatchRowIterator $it, IDatabase $dbr ) {
		$it->addConditions( [
			'rc_timestamp >= ' . $dbr->timestamp( $this->getOption( 's' ) ),
			'rc_timestamp < ' . $dbr->timestamp( $this->getOption( 'e' ) ),
		] );
	}

	/**
	 * Get iterator for links for categories.
	 * @param IDatabase $dbr
	 * @param array $ids List of page IDs
	 * @return Traversable
	 */
	public function getCategoryLinksIterator( IDatabase $dbr, array $ids ) {
		$it = new BatchRowIterator(
			$dbr,
			'categorylinks',
			[ 'cl_from', 'cl_to' ],
			$this->mBatchSize
		);
		$it->addConditions( [
			'cl_type' => 'subcat',
			'cl_from' => $ids
		] );
		$it->setFetchColumns( [ 'cl_from', 'cl_to' ] );
		return new RecursiveIteratorIterator( $it );
	}

}

$maintClass = "CategoryChangesAsRdf";
require_once RUN_MAINTENANCE_IF_MAIN;
