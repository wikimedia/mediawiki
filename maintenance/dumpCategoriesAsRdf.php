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
use Wikimedia\Purtle\RdfWriterFactory;
use Wikimedia\Rdbms\IDatabase;

require_once __DIR__ . '/Maintenance.php';

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
	 * @param IDatabase $dbr Database connection
	 * @return RecursiveIterator
	 */
	public function getCategoryIterator( IDatabase $dbr ) {
		$it = new BatchRowIterator(
			$dbr,
			[ 'page', 'page_props', 'category' ],
			[ 'page_title' ],
			$this->getBatchSize()
		);
		$it->addConditions( [
			'page_namespace' => NS_CATEGORY,
		] );
		$it->setFetchColumns( [
			'page_title',
			'page_id',
			'pp_propname',
			'cat_pages',
			'cat_subcats',
			'cat_files'
		] );
		$it->addJoinConditions(
			[
				'page_props' => [
					'LEFT JOIN', [ 'pp_propname' => 'hiddencat', 'pp_page = page_id' ]
				],
				'category' => [
					'LEFT JOIN', [ 'cat_title = page_title' ]
				]
			]

		);
		return $it;
	}

	/**
	 * Get iterator for links for categories.
	 * @param IDatabase $dbr
	 * @param int[] $ids List of page IDs
	 * @return Traversable
	 */
	public function getCategoryLinksIterator( IDatabase $dbr, array $ids ) {
		$it = new BatchRowIterator(
			$dbr,
			'categorylinks',
			[ 'cl_from', 'cl_to' ],
			$this->getBatchSize()
		);
		$it->addConditions( [
			'cl_type' => 'subcat',
			'cl_from' => $ids
		] );
		$it->setFetchColumns( [ 'cl_from', 'cl_to' ] );
		return new RecursiveIteratorIterator( $it );
	}

	/**
	 * @param int $timestamp
	 */
	public function addDumpHeader( $timestamp ) {
		global $wgRightsUrl;
		$licenseUrl = $wgRightsUrl;
		if ( substr( $licenseUrl, 0, 2 ) == '//' ) {
			$licenseUrl = 'https:' . $licenseUrl;
		}
		$this->rdfWriter->about( $this->categoriesRdf->getDumpURI() )
			->a( 'schema', 'Dataset' )
			->a( 'owl', 'Ontology' )
			->say( 'cc', 'license' )->is( $licenseUrl )
			->say( 'schema', 'softwareVersion' )->value( CategoriesRdf::FORMAT_VERSION )
			->say( 'schema', 'dateModified' )
				->value( wfTimestamp( TS_ISO_8601, $timestamp ), 'xsd', 'dateTime' )
			->say( 'schema', 'isPartOf' )->is( wfExpandUrl( '/', PROTO_CANONICAL ) )
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

		foreach ( $this->getCategoryIterator( $dbr ) as $batch ) {
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

			foreach ( $this->getCategoryLinksIterator( $dbr, array_keys( $pages ) ) as $row ) {
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

$maintClass = DumpCategoriesAsRdf::class;
require_once RUN_MAINTENANCE_IF_MAIN;
