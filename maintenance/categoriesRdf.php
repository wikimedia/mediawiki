<?php
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\RdfWriterFactory;
use Wikimedia\Rdbms\IDatabase;

require_once $IP . '/maintenance/Maintenance.php';

/**
 * Maintenance script to populate the category table.
 *
 * @ingroup Maintenance
 */
class CategoriesRDF extends Maintenance {

	const ONTOLOGY_PREFIX = 'mediawiki';

	// TODO: ensure this is the URL we want
	const ONTOLOGY_URL = 'http://wikiba.se/ontology#';

	/**
	 * @var RdfWriter
	 */
	private $rdfWriter;

	public function __construct() {
		parent::__construct();

		$this->addDescription( "Generate RDF dump of categories in a wiki." );

		$this->setBatchSize( 200 );
		$this->addOption( 'output', "Output file (default is stdout). Will be overwritten.", false, true );
		$this->addOption( 'format', "Set the dump format.", false, true );
	}

	/**
	 * Produce row iterator for categories.
	 * @param IDatabase $dbr Database connection
	 * @return RecursiveIterator
	 */
	private function getCategoryIterator( IDatabase $dbr ) {
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
	private function getCategoryLinksIterator( IDatabase $dbr, array $ids ) {
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

	public function execute() {
		$outFile = $this->getOption( 'output', 'php://stdout' );

		if ( $outFile === '-' ) {
			$outFile = 'php://stdout';
		}

		$output = fopen( $outFile, 'w' );

		$this->rdfWriter = $this->createRdfWriter( $this->getOption( 'format', 'ttl' ) );
		$this->rdfWriter->prefix( self::ONTOLOGY_PREFIX, self::ONTOLOGY_URL );
		$this->rdfWriter->prefix( 'rdfs', 'http://www.w3.org/2000/01/rdf-schema#' );
		$this->rdfWriter->start();

		$dbr = $this->getDB( DB_REPLICA, ['vslow'] );

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
		global $wgArticlePath;
		$url = str_replace( '$1', wfUrlencode( $title->getPrefixedDBkey() ), $wgArticlePath );
		return wfExpandUrl( $url, PROTO_CANONICAL );
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

$maintClass = "CategoriesRDF";
require_once RUN_MAINTENANCE_IF_MAIN;
