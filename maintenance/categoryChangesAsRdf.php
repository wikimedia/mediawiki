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
	 * Delete query
	 */
	const SPARQL_DELETE = <<<SPARQLD
DELETE {
?category ?x ?y
} WHERE {
   ?category ?x ?y
   VALUES ?category {
     %s
   }
};

SPARQLD;

	/**
	 * Delete/Insert query
	 */
	const SPARQL_DELETE_INSERT = <<<SPARQLDI
DELETE {
?category ?x ?y
} INSERT {
%s
} WHERE {
  ?category ?x ?y
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
		$this->addOption( 'start', 'Starting timestamp (inclusive), in ISO or Mediawiki format.',
			true, true, 's' );
		$this->addOption( 'end', 'Ending timestamp (exclusive), in ISO or Mediawiki format.', true,
			true, 'e' );
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
		$this->initialize();
		$startTS = new MWTimestamp( $this->getOption( "start" ) );

		$endTS = new MWTimestamp( $this->getOption( "end" ) );
		$now = new MWTimestamp();
		$rcMaxAge = $this->getConfig()->get( 'RCMaxAge' );

		if ( $now->getTimestamp() - $startTS->getTimestamp() > $rcMaxAge ) {
			$this->error( "Start timestamp too old, maximum RC age is $rcMaxAge!" );
		}
		if ( $now->getTimestamp() - $endTS->getTimestamp() > $rcMaxAge ) {
			$this->error( "End timestamp too old, maximum RC age is $rcMaxAge!" );
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
		// Process newly added pages
		$this->handleAdds( $dbr, $output );
		// Process page edits
		$this->handleEdits( $dbr, $output );
		// Process categorization changes
		$this->handleCategorization( $dbr, $output );

		// Update timestamp
		fwrite( $output, $this->updateTS( $this->endTS ) );
	}

	/**
	 * Get the text of SPARQL INSERT DATA clause
	 * @return string
	 */
	private function getInsertRdf() {
		$rdfText = $this->getRdf();
		if ( !$rdfText ) {
			return "";
		}
		return sprintf( self::SPARQL_INSERT, $rdfText );
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

		return "# $mark\n" . sprintf( self::SPARQL_DELETE, implode( ' ', $deleteUrls ) ) .
			$this->getInsertRdf();
	}

	/**
	 * Write parent data for a set of categories.
	 * The list has the child categories.
	 * @param IDatabase $dbr
	 * @param string[] $pages List of child categories: id => title
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
			$tables = array_merge( $tables, $extra_tables );
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
			'page' => [ 'JOIN', 'rc_cur_id = page_id' ],
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
	 * Fetch categorization changes or edits
	 * @param IDatabase $dbr
	 * @return BatchRowIterator
	 */
	protected function getChangedCatsIterator( IDatabase $dbr, $type ) {
		$it =
			$this->setupChangesIterator( $dbr );
		$it->addConditions( [
			'rc_namespace' => NS_CATEGORY,
			'rc_new' => 0,
			'rc_type' => $type,
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
	 * @param int[] $ids List of page IDs
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

			fwrite( $output, $this->getInsertRdf() );
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
			fwrite( $output, $this->getInsertRdf() );
		}
	}

	/**
	 * Handle edits for category texts
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleEdits( IDatabase $dbr, $output ) {
		// Editing category can change hidden flag and add new parents.
		// TODO: it's pretty expensive to update all edited categories, and most edits
		// aren't actually interesting for us. Some way to know which are interesting?
		// We can capture recategorization on the next step, but not change in hidden status.
		foreach ( $this->getChangedCatsIterator( $dbr, RC_EDIT ) as $batch ) {
			$pages = [];
			$deleteUrls = [];
			foreach ( $batch as $row ) {
				// Note that on categorization event, cur_id points to
				// the child page, not the parent category!
				if ( isset( $this->processed[$row->rc_cur_id] ) ) {
					// We already captured this one before
					continue;
				}
				$this->writeCategoryData( $row );
				$pages[$row->rc_cur_id] = $row->rc_title;
				$this->processed[$row->rc_cur_id] = true;
				$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages, "Edits" ) );
		}
	}

	/**
	 * Handles categorization changes
	 * @param IDatabase $dbr
	 * @param resource $output
	 */
	public function handleCategorization( IDatabase $dbr, $output ) {
		$processedTitle = [];
		// Categorization change can add new parents and change counts
		// for the parent category.
		foreach ( $this->getChangedCatsIterator( $dbr, RC_CATEGORIZE ) as $batch ) {
			/*
			 * Note that on categorization event, cur_id points to
			 * the child page, not the parent category!
			 * So we need to have a two-stage process, since we have ID from one
			 * category and title from another, and we need both for proper updates.
			 * TODO: For now, we do full update even though some data hasn't changed,
			 * e.g. parents for parent cat and counts for child cat.
			 */
			$childPages = [];
			$parentCats = [];
			foreach ( $batch as $row ) {
				$childPages[$row->rc_cur_id] = true;
				$parentCats[$row->rc_title] = true;
			}

			$joinConditions = [
				'page_props' => [
					'LEFT JOIN',
					[ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ],
				],
				'category' => [
					'LEFT JOIN',
					[ 'cat_title = page_title' ],
				],
			];

			$pages = [];
			$deleteUrls = [];

			if ( $childPages ) {
				// Load child rows by ID
				$childRows = $dbr->select(
					[ 'page', 'page_props', 'category' ],
					[
						'page_id',
						'rc_title' => 'page_title',
						'pp_propname',
						'cat_pages',
						'cat_subcats',
						'cat_files',
					],
					[ 'page_namespace' => NS_CATEGORY, 'page_id' => array_keys( $childPages ) ],
					__METHOD__,
					[],
					$joinConditions
				);
				foreach ( $childRows as $row ) {
					if ( isset( $this->processed[$row->page_id] ) ) {
						// We already captured this one before
						continue;
					}
					$this->writeCategoryData( $row );
					$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
					$this->processed[$row->page_id] = true;
				}
			}

			if ( $parentCats ) {
				// Load parent rows by title
				$joinConditions = [
					'page' => [
						'LEFT JOIN',
						[ 'page_title = cat_title', 'page_namespace' => NS_CATEGORY ],
					],
					'page_props' => [
						'LEFT JOIN',
						[ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ],
					],
				];

				$parentRows = $dbr->select(
					[ 'category', 'page', 'page_props' ],
					[
						'page_id',
						'rc_title' => 'cat_title',
						'pp_propname',
						'cat_pages',
						'cat_subcats',
						'cat_files',
					],
					[ 'cat_title' => array_keys( $parentCats ) ],
					__METHOD__,
					[],
					$joinConditions
				);
				foreach ( $parentRows as $row ) {
					if ( $row->page_id && isset( $this->processed[$row->page_id] ) ) {
						// We already captured this one before
						continue;
					}
					if ( isset( $processedTitle[$row->rc_title] ) ) {
						// We already captured this one before
						continue;
					}
					$this->writeCategoryData( $row );
					$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
					if ( $row->page_id ) {
						$this->processed[$row->page_id] = true;
					}
					$processedTitle[$row->rc_title] = true;
				}
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages, "Changes" ) );
		}
	}
}

$maintClass = CategoryChangesAsRdf::class;
require_once RUN_MAINTENANCE_IF_MAIN;
