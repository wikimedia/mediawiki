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
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\RdfWriterFactory;
use Wikimedia\Rdbms\IReadableDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to provide RDF representation of the category tree.
 *
 * @ingroup Maintenance
 * @since 1.30
 */
class DumpCategoriesAsRdf extends Maintenance {
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

		$this->addDescription( "Generate RDF dump of categories in a wiki." );

		$this->setBatchSize( 200 );
		$this->addOption( 'output', "Output file (default is stdout). Will be overwritten.",
			false, true );
		$this->addOption( 'format', "Set the dump format.", false, true );
	}

	/**
	 * Produce row iterator for categories.
	 * @param IReadableDatabase $dbr
	 * @param string $fname Name of the calling function
	 * @return RecursiveIterator
	 */
	public function getCategoryIterator( IReadableDatabase $dbr, $fname ) {
		$it = new BatchRowIterator(
			$dbr,
			$dbr->newSelectQueryBuilder()
				->from( 'page' )
				->leftJoin( 'page_props', null, [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ] )
				->leftJoin( 'category', null, [ 'cat_title = page_title' ] )
				->select( [
					'page_title',
					'page_id',
					'pp_propname',
					'cat_pages',
					'cat_subcats',
					'cat_files'
				] )
				->where( [
					'page_namespace' => NS_CATEGORY,
				] )
				->caller( $fname ),
			[ 'page_title' ],
			$this->getBatchSize()
		);
		return $it;
	}

	/**
	 * Get iterator for links for categories.
	 * @param IReadableDatabase $dbr
	 * @param int[] $ids List of page IDs
	 * @param string $fname Name of the calling function
	 * @return Traversable
	 */
	public function getCategoryLinksIterator( IReadableDatabase $dbr, array $ids, $fname ) {
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
			$this->getBatchSize()
		);
		return new RecursiveIteratorIterator( $it );
	}

	/**
	 * @param int $timestamp
	 */
	public function addDumpHeader( $timestamp ) {
		$licenseUrl = $this->getConfig()->get( MainConfigNames::RightsUrl );
		if ( str_starts_with( $licenseUrl, '//' ) ) {
			$licenseUrl = 'https:' . $licenseUrl;
		}
		$urlUtils = $this->getServiceContainer()->getUrlUtils();
		$this->rdfWriter->about( $this->categoriesRdf->getDumpURI() )
			->a( 'schema', 'Dataset' )
			->a( 'owl', 'Ontology' )
			->say( 'cc', 'license' )->is( $licenseUrl )
			->say( 'schema', 'softwareVersion' )->value( CategoriesRdf::FORMAT_VERSION )
			->say( 'schema', 'dateModified' )
			->value( wfTimestamp( TS_ISO_8601, $timestamp ), 'xsd', 'dateTime' )
			->say( 'schema', 'isPartOf' )->is( (string)$urlUtils->expand( '/', PROTO_CANONICAL ) )
			->say( 'owl', 'imports' )->is( CategoriesRdf::OWL_URL );
	}

	public function execute() {
		$outFile = $this->getOption( 'output', 'php://stdout' );

		if ( $outFile === '-' ) {
			$outFile = 'php://stdout';
		}

		$output = fopen( $outFile, 'w' );
		$this->rdfWriter = $this->createRdfWriter( $this->getOption( 'format', 'ttl' ) );
		$this->categoriesRdf = new CategoriesRdf( $this->rdfWriter );

		$this->categoriesRdf->setupPrefixes();
		$this->rdfWriter->start();

		$this->addDumpHeader( time() );
		fwrite( $output, $this->rdfWriter->drain() );

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		foreach ( $this->getCategoryIterator( $dbr, __METHOD__ ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				$this->categoriesRdf->writeCategoryData(
					$row->page_title,
					$row->pp_propname === 'hiddencat',
					(int)$row->cat_pages - (int)$row->cat_subcats - (int)$row->cat_files,
					(int)$row->cat_subcats
				);
				if ( $row->page_id ) {
					$pages[$row->page_id] = $row->page_title;
				}
			}

			foreach ( $this->getCategoryLinksIterator( $dbr, array_keys( $pages ), __METHOD__ ) as $row ) {
				$this->categoriesRdf->writeCategoryLinkData( $pages[$row->cl_from], $row->cl_to );
			}
			fwrite( $output, $this->rdfWriter->drain() );
		}
		fflush( $output );
		if ( $outFile !== '-' ) {
			fclose( $output );
		}
	}

	/**
	 * @param string $format Writer format
	 * @return RdfWriter
	 */
	private function createRdfWriter( $format ) {
		$factory = new RdfWriterFactory();
		return $factory->getWriter( $factory->getFormatName( $format ) );
	}
}

// @codeCoverageIgnoreStart
$maintClass = DumpCategoriesAsRdf::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
