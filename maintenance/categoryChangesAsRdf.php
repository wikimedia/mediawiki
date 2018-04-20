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
} INSERT {
%s
} WHERE {
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

	private $startTS;
	private $endTS;

	/**
	 * List of processed page IDs,
	 * so we don't try to process same thing twice
	 * @var int[]
	 */
	protected $processed = [];

	public function __construct() {
		parent::__construct();

		$this->addDescription( "Generate RDF dump of category changes in a wiki." );

		$this->setBatchSize( 200 );
		$this->addOption( 'output', "Output file (default is stdout). Will be overwritten.", false,
			true, 'o' );
		$this->addOption( 'start', 'Starting timestamp (inclusive), in ISO or Mediawiki format.', true, true, 's' );
		$this->addOption( 'end', 'Ending timestamp (exclusive), in ISO or Mediawiki format.', true, true, 'e' );
	}

	/**
	 * Initialize external service classes.
	 */
	public function initialize() {
		// SPARQL Update syntax is close to Turtle format, so we can use Turtle writer.
		$this->rdfWriter = new TurtleRdfWriter();
		$this->categoriesRdf = new CategoriesRdf( $this->rdfWriter );
	}

	public function execute() {
		global $wgRCMaxAge;

		$this->initialize();

		$startTS = new MWTimestamp( $this->getOption( "start" ) );
		$endTS = new MWTimestamp( $this->getOption( "end" ) );
		$now = new MWTimestamp();

		if ( $now->getTimestamp() - $startTS->getTimestamp() > $wgRCMaxAge ) {
			$this->error( "Start timestamp too old, maximum RC age is $wgRCMaxAge!" );
		}
		if ( $now->getTimestamp() - $endTS->getTimestamp() > $wgRCMaxAge ) {
			$this->error( "End timestamp too old, maximum RC age is $wgRCMaxAge!" );
		}

		$this->startTS = $startTS->getTimestamp();
		$this->endTS = $endTS->getTimestamp();

		$outFile = $this->getOption( 'output', 'php://stdout' );
		if ( $outFile === '-' ) {
			$outFile = 'php://stdout';
		}

		$output = fopen( $outFile, 'wb' );

		$this->categoriesRdf->setupPrefixes();
		$this->rdfWriter->start();

		$prefixes = $this->getRdf();
		// We have to strip @ from prefix, since SPARQL UPDATE doesn't use them
		// Also strip dot at the end.
		$prefixes = preg_replace( [ '/^@/m', '/\s*[.]$/m' ], '', $prefixes );
		fwrite( $output, $prefixes );

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		// Deletes go first because if the page was deleted, other changes
		// do not matter. This only gets true deletes, i.e. not pages that were restored.
		$this->handleDeletes( $dbr, $output );
		// Moves go before additions because if category is moved, we should not process creation
		// as it would produce wrong data - because create row has old title
		$this->handleMoves( $dbr, $output );
		// We need to handle restores too since delete may have happened in previous update.
		$this->handleRestores( $dbr, $output );
		$this->handleAdds( $dbr, $output );
		$this->handleChanges( $dbr, $output );

		// Update timestamp
		fwrite( $output, $this->updateTS( $this->endTS ) );
	}

	/**
	 * Get SPARQL for updating set of categories
	 * @param IDatabase $dbr
	 * @param string[] $deleteUrls List of URIs to be deleted, with <>
	 * @param string[] $pages List of categories: id => title
	 * @param string $mark Marks which operation requests the query
	 * @return string SPARQL query
	 */
	private function getCategoriesUpdate( IDatabase $dbr, $deleteUrls, $pages, $mark ) {
		if ( empty( $deleteUrls ) ) {
			return "";
		}

		if ( !empty( $pages ) ) {
			$this->writeParentCategories( $dbr, $pages );
		}

		return "# $mark\n" . sprintf( self::SPARQL_DELETE_INSERT,
				$this->getRdf(),
				implode( ' ', $deleteUrls ) );
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
	 * Generate SPARQL Update code for updating dump timestamp
	 * @param string|int $timestamp Timestamp for last change
	 * @return string SPARQL Update query for timestamp.
	 */
	public function updateTS( $timestamp ) {
		$dumpUrl = '<' . $this->categoriesRdf->getDumpURI() . '>';
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
	 * Set up standard iterator for retrieving category changes.
	 * @param IDatabase $dbr
	 * @param string[] $columns List of additional fields to get
	 * @param string[] $extra_tables List of additional tables to join
	 * @return BatchRowIterator
	 */
	private function setupChangesIterator(
		IDatabase $dbr,
		array $columns = [],
		array $extra_tables = []
	) {
		$tables = [ 'recentchanges', 'page_props', 'category' ];
		if ( $extra_tables ) {
			$tables += $extra_tables;
		}
		$it = new BatchRowIterator( $dbr,
			$tables,
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$it->addJoinConditions(
			[
				'page_props' => [
					'LEFT JOIN', [ 'pp_propname' => 'hiddencat', 'pp_page = rc_cur_id' ]
				],
				'category' => [
					'LEFT JOIN', [ 'cat_title = rc_title' ]
				]
			]
		);
		$it->setFetchColumns( array_merge( $columns, [
			'rc_title',
			'rc_cur_id',
			'pp_propname',
			'cat_pages',
			'cat_subcats',
			'cat_files'
		] ) );
		return $it;
	}

	/**
	 * Fetch newly created categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getNewCatsIterator( IDatabase $dbr ) {
		$it = $this->setupChangesIterator( $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 1,
		] );
		return $it;
	}

	/**
	 * Fetch moved categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getMovedCatsIterator( IDatabase $dbr ) {
		$it = $this->setupChangesIterator( $dbr, [ 'page_title', 'page_namespace' ], [ 'page' ] );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_log_type' => 'move',
			'rc_type' => RC_LOG,
		] );
		$it->addJoinConditions( [
			'page' => [ 'INNER JOIN', 'rc_cur_id = page_id' ],
		] );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Fetch deleted categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getDeletedCatsIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator( $dbr,
			'recentchanges',
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
			// We will fetch ones that do not have page record. If they do,
			// this means they were restored, thus restoring handler will pick it up.
			'NOT EXISTS (SELECT * FROM page WHERE page_id = rc_cur_id)',
		] );
		$this->addIndex( $it );
		$it->setFetchColumns( [ 'rc_cur_id', 'rc_title' ] );
		return $it;
	}

	/**
	 * Fetch restored categories
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getRestoredCatsIterator( IDatabase $dbr ) {
		$it = $this->setupChangesIterator( $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_log_type' => 'delete',
			'rc_log_action' => 'restore',
			'rc_type' => RC_LOG,
			// We will only fetch ones that have page record
			'EXISTS (SELECT page_id FROM page WHERE page_id = rc_cur_id)',
		] );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Fetch categorization changes
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getChangedCatsIterator( IDatabase $dbr ) {
		$it = $this->setupChangesIterator( $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_type' => [ RC_EDIT, RC_CATEGORIZE ],
		] );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Add timestamp limits to iterator
	 * @param BatchRowIterator $it Iterator
	 * @param IDatabase $dbr
	 */
	private function addTimestampConditions( BatchRowIterator $it, IDatabase $dbr ) {
		$it->addConditions( [
			'rc_timestamp >= ' . $dbr->addQuotes( $dbr->timestamp( $this->startTS ) ),
			'rc_timestamp < ' . $dbr->addQuotes( $dbr->timestamp( $this->endTS ) ),
		] );
	}

	/**
	 * Need to force index, somehow on terbium the optimizer chooses wrong one
	 * @param BatchRowIterator $it
	 */
	private function addIndex( BatchRowIterator $it ) {
		$it->addOptions( [
			'USE INDEX' => [ 'recentchanges' => 'new_name_timestamp' ]
		] );
	}

	/**
	 * Get iterator for links for categories.
	 * @param IDatabase $dbr
	 * @param array $ids List of page IDs
	 * @return Traversable
	 */
	protected function getCategoryLinksIterator( IDatabase $dbr, array $ids ) {
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

	/**
	 * Get accumulated RDF.
	 * @return string
	 */
	public function getRdf() {
		return $this->rdfWriter->drain();
	}

	/**
	 * Handle category deletes.
	 * @param IDatabase $dbr
	 * @param resource $output File to write the output
	 */
	public function handleDeletes( IDatabase $dbr, $output ) {
		// This only does "true" deletes - i.e. those that the page stays deleted
		foreach ( $this->getDeletedCatsIterator( $dbr ) as $batch ) {
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				// This can produce duplicates, we don't care
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
				$this->processed[$row->rc_cur_id] = true;
			}
			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, [], "Deletes" ) );
		}
	}

	/**
	 * Write category data to RDF.
	 * @param stdclass $row Database row
	 */
	private function writeCategoryData( $row ) {
		$this->categoriesRdf->writeCategoryData(
			$row->rc_title,
			$row->pp_propname === 'hiddencat',
			(int)$row->cat_pages - (int)$row->cat_subcats - (int)$row->cat_files,
			(int)$row->cat_subcats
		);
	}

	/**
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleMoves( IDatabase $dbr, $output ) {
		foreach ( $this->getMovedCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';

				if ( isset( $this->processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}

				if ( $row->page_namespace != NS_CATEGORY ) {
					// If page was moved out of Category:, we'll just delete
					continue;
				}
				$row->rc_title = $row->page_title;
				$this->writeCategoryData( $row );
				$pages[$row->rc_cur_id] = $row->page_title;
				$this->processed[$row->rc_cur_id] = true;
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages, "Moves" ) );
		}
	}

	/**
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleRestores( IDatabase $dbr, $output ) {
		fwrite( $output, "# Restores\n" );
		// This will only find those restores that were not deleted later.
		foreach ( $this->getRestoredCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				if ( isset( $this->processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->writeCategoryData( $row );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$this->processed[$row->rc_cur_id] = true;
			}

			if ( empty( $pages ) ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );

			fwrite( $output, sprintf( self::SPARQL_INSERT, $this->getRdf() ) );
		}
	}

	/**
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleAdds( IDatabase $dbr, $output ) {
		fwrite( $output, "# Additions\n" );
		foreach ( $this->getNewCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				if ( isset( $this->processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->writeCategoryData( $row );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$this->processed[$row->rc_cur_id] = true;
			}

			if ( empty( $pages ) ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );
			fwrite( $output, sprintf( self::SPARQL_INSERT, $this->getRdf() ) );
		}
	}

	/**
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleChanges( IDatabase $dbr, $output ) {
		foreach ( $this->getChangedCatsIterator( $dbr ) as $batch ) {
			$pages = [];
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				if ( isset( $this->processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->writeCategoryData( $row );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$this->processed[$row->rc_cur_id] = true;
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages, "Changes" ) );
		}
	}
}

$maintClass = CategoryChangesAsRdf::class;
require_once RUN_MAINTENANCE_IF_MAIN;
