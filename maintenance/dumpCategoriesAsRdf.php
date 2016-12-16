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
	 * Prefix used for Mediawiki ontology in the dump.
	 */
	const ONTOLOGY_PREFIX = 'mediawiki';
	/**
	 * Base URL for Mediawiki ontology.
	 */
	const ONTOLOGY_URL = 'https://www.mediawiki.org/ontology#';
	/**
	 * OWL description of the ontology.
	 */
	const OWL_URL = 'https://www.mediawiki.org/ontology/ontology.owl';
	/**
	 * License under which the dump is available.
	 * TODO: make configurable?
	 */
	const LICENSE = 'https://creativecommons.org/licenses/by-sa/3.0/';
	/**
	 * Current version of the dump format.
	 */
	const FORMAT_VERSION = "1.0";
	/**
	 * @var RdfWriter
	 */
	private $rdfWriter;

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
			'page',
			[ 'page_title' ],
			$this->mBatchSize
		);
		$it->addConditions( [
			'page_namespace' => NS_CATEGORY,
		] );
		$it->setFetchColumns( [ 'page_title', 'page_id' ] );
		return $it;
	}

	/**
	 * Write out the data for single category.
	 * @param object $row Database row from category table
	 */
	public function writeCategoryData( $row ) {
		$title = Title::makeTitle( NS_CATEGORY, $row->page_title );
		$this->rdfWriter->about( $this->titleToUrl( $title ) )
			->say( 'a' )
			->is( self::ONTOLOGY_PREFIX, 'Category' );
		$titletext = $title->getText();
		$this->rdfWriter->say( 'rdfs', 'label' )->value( $titletext );
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

	/**
	 * Write RDF data for link between categories.
	 * @param $row
	 * @param string[] $pages Map ID->title for pages
	 */
	public function writeCategoryLinkData( $row, array $pages ) {
		$titleFrom = Title::makeTitle( NS_CATEGORY, $pages[$row->cl_from] );
		$titleTo = Title::makeTitle( NS_CATEGORY, $row->cl_to );
		$this->rdfWriter->about( $this->titleToUrl( $titleFrom ) )
			->say( self::ONTOLOGY_PREFIX, 'parentCategory' )
			->is( $this->titleToUrl( $titleTo ) );
	}

	public function addDumpHeader( $timestamp ) {
		$this->rdfWriter->about( wfExpandUrl( '/categoriesDump', PROTO_CANONICAL ) )
			->a( 'schema', 'Dataset' )
			->a( 'owl', 'Ontology' )
			->say( 'cc', 'license' )->is( self::LICENSE )
			->say( 'schema', 'softwareVersion' )->value( self::FORMAT_VERSION )
			->say( 'schema', 'dateModified' )->value( wfTimestamp( TS_ISO_8601, $timestamp ), 'xsd', 'dateTime' )
			->say( 'schema', 'isPartOf' )->is( wfExpandUrl( '/', PROTO_CANONICAL ) )
			->say( 'owl', 'imports' )->is( self::OWL_URL );
	}

	public function execute() {
		$outFile = $this->getOption( 'output', 'php://stdout' );

		if ( $outFile === '-' ) {
			$outFile = 'php://stdout';
		}

		$output = fopen( $outFile, 'w' );

		$this->rdfWriter = $this->createRdfWriter( $this->getOption( 'format', 'ttl' ) );
		$this->rdfWriter->prefix( self::ONTOLOGY_PREFIX, self::ONTOLOGY_URL );
		$this->rdfWriter->prefix( 'rdfs', 'http://www.w3.org/2000/01/rdf-schema#' );
		$this->rdfWriter->prefix( 'owl', 'http://www.w3.org/2002/07/owl#' );
		$this->rdfWriter->prefix( 'schema', 'http://schema.org/' );
		$this->rdfWriter->prefix( 'cc', 'http://creativecommons.org/ns#' );
		$this->rdfWriter->start();

		$this->addDumpHeader( time() );
		fwrite( $output, $this->rdfWriter->drain() );

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );

		foreach ( $this->getCategoryIterator( $dbr ) as $batch ) {
			$pages = [];
			foreach ( $batch as $row ) {
				$this->writeCategoryData( $row );
				$pages[$row->page_id] = $row->page_title;
			}

			foreach ( $this->getCategoryLinksIterator( $dbr, array_keys( $pages ) ) as $row ) {
				$this->writeCategoryLinkData( $row, $pages );
			}
			fwrite( $output, $this->rdfWriter->drain() );
		}
	}

	/**
	 * Convert Title to link to target page.
	 * @param Title $title
	 * @return string
	 */
	private function titleToUrl( Title $title ) {
		return $title->getFullURL('', false, PROTO_CANONICAL );
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

$maintClass = "DumpCategoriesAsRdf";
require_once RUN_MAINTENANCE_IF_MAIN;
