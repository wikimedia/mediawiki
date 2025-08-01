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

use MediaWiki\Category\CategoriesRdf;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\TurtleRdfWriter;
use Wikimedia\Rdbms\IReadableDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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
	private const SPARQL_INSERT = <<<SPARQL
INSERT DATA {
%s
};

SPARQL;

	/**
	 * Delete query
	 */
	private const SPARQL_DELETE = <<<SPARQLD
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
	 * @var RdfWriter
	 */
	private $rdfWriter;
	/**
	 * Categories RDF helper.
	 * @var CategoriesRdf
	 */
	private $categoriesRdf;

	/** @var string */
	private $startTS;
	/** @var string */
	private $endTS;

	/**
	 * List of processed page IDs,
	 * so we don't try to process same thing twice
	 * @var true[]
	 */
	protected $processed = [];

	public function __construct() {
		parent::__construct();

		$this->addDescription( "Generate RDF dump of category changes in a wiki." );

		$this->setBatchSize( 200 );
		$this->addOption( 'output', "Output file (default is stdout). Will be overwritten.", false,
			true, 'o' );
		$this->addOption( 'start', 'Starting timestamp (inclusive), in ISO or MediaWiki format.',
			true, true, 's' );
		$this->addOption( 'end', 'Ending timestamp (exclusive), in ISO or MediaWiki format.', true,
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
		$rcMaxAge = $this->getConfig()->get( MainConfigNames::RCMaxAge );

		if ( (int)$now->getTimestamp( TS_UNIX ) - (int)$startTS->getTimestamp( TS_UNIX ) > $rcMaxAge ) {
			$this->error( "Start timestamp too old, maximum RC age is $rcMaxAge!" );
		}
		if ( (int)$now->getTimestamp( TS_UNIX ) - (int)$endTS->getTimestamp( TS_UNIX ) > $rcMaxAge ) {
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
	 * @param IReadableDatabase $dbr
	 * @param string[] $deleteUrls List of URIs to be deleted, with <>
	 * @param string[] $pages List of categories: id => title
	 * @param string $mark Marks which operation requests the query
	 * @return string SPARQL query
	 */
	private function getCategoriesUpdate( IReadableDatabase $dbr, $deleteUrls, $pages, $mark ) {
		if ( !$deleteUrls ) {
			return "";
		}

		if ( $pages ) {
			$this->writeParentCategories( $dbr, $pages );
		}

		return "# $mark\n" . sprintf( self::SPARQL_DELETE, implode( ' ', $deleteUrls ) ) .
			$this->getInsertRdf();
	}

	/**
	 * Write parent data for a set of categories.
	 * The list has the child categories.
	 * @param IReadableDatabase $dbr
	 * @param string[] $pages List of child categories: id => title
	 */
	private function writeParentCategories( IReadableDatabase $dbr, $pages ) {
		foreach ( $this->getCategoryLinksIterator( $dbr, array_keys( $pages ), __METHOD__ ) as $row ) {
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
	 * @param IReadableDatabase $dbr
	 * @param string[] $columns List of additional fields to get
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	private function setupChangesIterator(
		IReadableDatabase $dbr,
		array $columns,
		string $fname
	) {
		$it = new BatchRowIterator( $dbr,
			$dbr->newSelectQueryBuilder()
				->from( 'recentchanges' )
				->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = rc_cur_id' ] )
				->leftJoin( 'category', null, [ 'cat_title = rc_title' ] )
				->select( array_merge( $columns, [
					'rc_title',
					'rc_cur_id',
					'pp_propname',
					'cat_pages',
					'cat_subcats',
					'cat_files'
				] ) )
				->caller( $fname ),
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		return $it;
	}

	/**
	 * Fetch newly created categories
	 * @param IReadableDatabase $dbr
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	protected function getNewCatsIterator( IReadableDatabase $dbr, $fname ) {
		$it = $this->setupChangesIterator( $dbr, [], $fname );
		$it->sqb->conds( [
			'rc_namespace' => NS_CATEGORY,
			'rc_source' => RecentChange::SRC_NEW,
		] );
		return $it;
	}

	/**
	 * Fetch moved categories
	 * @param IReadableDatabase $dbr
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	protected function getMovedCatsIterator( IReadableDatabase $dbr, $fname ) {
		$it = $this->setupChangesIterator(
			$dbr,
			[ 'page_title', 'page_namespace' ],
			$fname
		);
		$it->sqb->conds( [
			'rc_namespace' => NS_CATEGORY,
			'rc_source' => RecentChange::SRC_LOG,
			'rc_log_type' => 'move',
			'rc_type' => RC_LOG,
		] );
		$it->sqb->join( 'page', null, 'rc_cur_id = page_id' );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Fetch deleted categories
	 * @param IReadableDatabase $dbr
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	protected function getDeletedCatsIterator( IReadableDatabase $dbr, $fname ) {
		$it = new BatchRowIterator( $dbr,
			$dbr->newSelectQueryBuilder()
				->from( 'recentchanges' )
				->select( [ 'rc_cur_id', 'rc_title' ] )
				->where( [
					'rc_namespace' => NS_CATEGORY,
					'rc_source' => RecentChange::SRC_LOG,
					'rc_log_type' => 'delete',
					'rc_log_action' => 'delete',
					'rc_type' => RC_LOG,
					// We will fetch ones that do not have page record. If they do,
					// this means they were restored, thus restoring handler will pick it up.
					'NOT EXISTS (SELECT * FROM page WHERE page_id = rc_cur_id)',
				] )
				->caller( $fname ),
			[ 'rc_timestamp' ],
			$this->mBatchSize
		);
		$this->addTimestampConditions( $it, $dbr );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Fetch restored categories
	 * @param IReadableDatabase $dbr
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	protected function getRestoredCatsIterator( IReadableDatabase $dbr, $fname ) {
		$it = $this->setupChangesIterator( $dbr, [], $fname );
		$it->sqb->conds( [
			'rc_namespace' => NS_CATEGORY,
			'rc_source' => RecentChange::SRC_LOG,
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
	 * @param IReadableDatabase $dbr
	 * @param string $source
	 * @param string $fname Name of the calling function
	 * @return BatchRowIterator
	 */
	protected function getChangedCatsIterator( IReadableDatabase $dbr, $source, $fname ) {
		$it = $this->setupChangesIterator( $dbr, [], $fname );
		$it->sqb->conds( [
			'rc_namespace' => NS_CATEGORY,
			'rc_source' => $source,
		] );
		$this->addIndex( $it );
		return $it;
	}

	/**
	 * Add timestamp limits to iterator
	 * @param BatchRowIterator $it Iterator
	 * @param IReadableDatabase $dbr
	 */
	private function addTimestampConditions( BatchRowIterator $it, IReadableDatabase $dbr ) {
		$it->sqb->conds( [
			$dbr->expr( 'rc_timestamp', '>=', $dbr->timestamp( $this->startTS ) ),
			$dbr->expr( 'rc_timestamp', '<', $dbr->timestamp( $this->endTS ) ),
		] );
	}

	/**
	 * Need to force index, somehow on terbium the optimizer chooses wrong one
	 */
	private function addIndex( BatchRowIterator $it ) {
		$it->sqb->options( [
			'USE INDEX' => [ 'recentchanges' => 'rc_source_name_timestamp' ]
		] );
	}

	/**
	 * Get iterator for links for categories.
	 * @param IReadableDatabase $dbr
	 * @param int[] $ids List of page IDs
	 * @param string $fname Name of the calling function
	 * @return Traversable
	 */
	protected function getCategoryLinksIterator( IReadableDatabase $dbr, array $ids, $fname ) {
		$migrationStage = $this->getServiceContainer()->getMainConfig()->get(
			MainConfigNames::CategoryLinksSchemaMigrationStage
		);

		if ( $migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$qb = $dbr->newSelectQueryBuilder()
				->select( [ 'cl_from', 'cl_to' ] )
				->from( 'categorylinks' )
				->where( [
					'cl_type' => 'subcat',
					'cl_from' => $ids
				] )
				->caller( $fname );
				$primaryKey = [ 'cl_from', 'cl_to' ];
		} else {
			$qb = $dbr->newSelectQueryBuilder()
				->select( [ 'cl_from', 'cl_to' => 'lt_title' ] )
				->from( 'categorylinks' )
				->join( 'linktarget', null, 'cl_target_id=lt_id' )
				->where( [
					'cl_type' => 'subcat',
					'cl_from' => $ids
				] )
				->caller( $fname );
				$primaryKey = [ 'cl_from', 'cl_target_id' ];
		}

		$it = new BatchRowIterator(
			$dbr,
			$qb,
			$primaryKey,
			$this->mBatchSize
		);
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
	 * @param IReadableDatabase $dbr
	 * @param resource $output File to write the output
	 */
	public function handleDeletes( IReadableDatabase $dbr, $output ) {
		// This only does "true" deletes - i.e. those that the page stays deleted

		foreach ( $this->getDeletedCatsIterator( $dbr, __METHOD__ ) as $batch ) {
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
	 * @param IReadableDatabase $dbr
	 * @param resource $output
	 */
	public function handleMoves( IReadableDatabase $dbr, $output ) {
		foreach ( $this->getMovedCatsIterator( $dbr, __METHOD__ ) as $batch ) {
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
	 * @param IReadableDatabase $dbr
	 * @param resource $output
	 */
	public function handleRestores( IReadableDatabase $dbr, $output ) {
		fwrite( $output, "# Restores\n" );

		// This will only find those restores that were not deleted later.
		foreach ( $this->getRestoredCatsIterator( $dbr, __METHOD__ ) as $batch ) {
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

			if ( !$pages ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );

			fwrite( $output, $this->getInsertRdf() );
		}
	}

	/**
	 * @param IReadableDatabase $dbr
	 * @param resource $output
	 */
	public function handleAdds( IReadableDatabase $dbr, $output ) {
		fwrite( $output, "# Additions\n" );

		foreach ( $this->getNewCatsIterator( $dbr, __METHOD__ ) as $batch ) {
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

			if ( !$pages ) {
				continue;
			}

			$this->writeParentCategories( $dbr, $pages );
			fwrite( $output, $this->getInsertRdf() );
		}
	}

	/**
	 * Handle edits for category texts
	 * @param IReadableDatabase $dbr
	 * @param resource $output
	 */
	public function handleEdits( IReadableDatabase $dbr, $output ) {
		// Editing category can change hidden flag and add new parents.
		// TODO: it's pretty expensive to update all edited categories, and most edits
		// aren't actually interesting for us. Some way to know which are interesting?
		// We can capture recategorization on the next step, but not change in hidden status.

		foreach ( $this->getChangedCatsIterator( $dbr, RecentChange::SRC_EDIT, __METHOD__ ) as $batch ) {
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
	 * @param IReadableDatabase $dbr
	 * @param resource $output
	 */
	public function handleCategorization( IReadableDatabase $dbr, $output ) {
		$processedTitle = [];

		// Categorization change can add new parents and change counts
		// for the parent category.

		foreach ( $this->getChangedCatsIterator( $dbr, RecentChange::SRC_CATEGORIZE, __METHOD__ ) as $batch ) {
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

			$pages = [];
			$deleteUrls = [];

			if ( $childPages ) {
				// Load child rows by ID
				$childRows = $dbr->newSelectQueryBuilder()
					->select( [
						'page_id',
						'rc_title' => 'page_title',
						'pp_propname',
						'cat_pages',
						'cat_subcats',
						'cat_files',
					] )
					->from( 'page' )
					->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ] )
					->leftJoin( 'category', null, [ 'cat_title = page_title' ] )
					->where( [ 'page_namespace' => NS_CATEGORY, 'page_id' => array_keys( $childPages ) ] )
					->caller( __METHOD__ )->fetchResultSet();
				foreach ( $childRows as $row ) {
					if ( isset( $this->processed[$row->page_id] ) ) {
						// We already captured this one before
						continue;
					}
					$this->writeCategoryData( $row );
					if ( $row->page_id ) {
						$pages[$row->page_id] = $row->rc_title;
						$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
						$this->processed[$row->page_id] = true;
					}
				}
			}

			if ( $parentCats ) {
				// Load parent rows by title
				$parentRows = $dbr->newSelectQueryBuilder()
					->select( [
						'page_id',
						'rc_title' => 'cat_title',
						'pp_propname',
						'cat_pages',
						'cat_subcats',
						'cat_files',
					] )
					->from( 'category' )
					->leftJoin( 'page', null, [ 'page_title = cat_title', 'page_namespace' => NS_CATEGORY ] )
					->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ] )
					->where( [ 'cat_title' => array_map( 'strval', array_keys( $parentCats ) ) ] )
					->caller( __METHOD__ )->fetchResultSet();
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
					if ( $row->page_id ) {
						$pages[$row->page_id] = $row->rc_title;
						$deleteUrls[] = '<' . $this->categoriesRdf->labelToUrl( $row->rc_title ) . '>';
						$this->processed[$row->page_id] = true;
					}
					$processedTitle[$row->rc_title] = true;
				}
			}

			fwrite( $output, $this->getCategoriesUpdate( $dbr, $deleteUrls, $pages, "Changes" ) );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CategoryChangesAsRdf::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
