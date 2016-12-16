<?php
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\RdfWriterFactory;

require_once $IP . '/maintenance/Maintenance.php';

/**
 * Maintenance script to populate the category table.
 *
 * @ingroup Maintenance
 */
class CategoriesRDF extends Maintenance {

	const ONTOLOGY_PREFIX = 'mediawiki';

	// FIXME: probably need different URL
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
	 * Do the actual work. All child classes will need to implement this
	 */
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
		$where = [];

		$it = new BatchRowIterator(
			$dbr,
			'page',
			[ 'page_title' ],
			$this->mBatchSize
		);
		$it->addConditions([
			'page_namespace' => NS_CATEGORY,
		]);
		$it->setFetchColumns( [ 'page_title', 'page_id' ] );

		foreach ($it as $batch) {
			$pages = [];
			foreach($batch as $row) {
				$title = Title::makeTitle( NS_CATEGORY, $row->page_title );
				$this->rdfWriter->about( $this->titleToUrl( $title ) )
					->say( 'a' )
					->is( self::ONTOLOGY_PREFIX, 'Category' );
				$titletext = $title->getText();
				$this->rdfWriter->say( 'rdfs', 'label' )->value( $titletext );
				$pages[$row->page_id] = $row->page_title;
			}
			$subit = new BatchRowIterator(
				$dbr,
				'categorylinks',
				[ 'cl_from', 'cl_to' ],
				$this->mBatchSize
			);
			$subit->addConditions( [
				'cl_type' => 'subcat',
				'cl_from' => array_keys( $pages )
			] );
			$subit->setFetchColumns( [ 'cl_from', 'cl_to' ] );
			foreach ($subit as $cbatch) {
				foreach ( $cbatch as $row ) {
					$titleFrom = Title::makeTitle( NS_CATEGORY, $pages[$row->cl_from] );
					$titleTo = Title::makeTitle( NS_CATEGORY, $row->cl_to );
					$this->rdfWriter->about( $this->titleToUrl( $titleFrom ) )
						->say( self::ONTOLOGY_PREFIX, 'parentCategory' )
						->is( $this->titleToUrl( $titleTo ) );
				}
			}
			fwrite( $output, $this->rdfWriter->drain() );
		}

//		$it = new BatchRowIterator(
//			$dbr,
//			[ 'categorylinks' ],
//			[ 'cl_from', 'cl_to' ],
//			$this->mBatchSize
//		);
//		$it->addConditions([
//			'page_namespace' => NS_CATEGORY,
//		    'cl_type' => 'subcat',
//		    'page_id = cl_from'
//		]);
//		$it->addJoinConditions( [ 'page_id = cl_from' ] );
//		$it->setFetchColumns( [ 'page_id', 'page_title', 'cl_to' ] );
//		foreach ($it as $batch) {
//			foreach($batch as $row) {
//				$titleFrom = Title::makeTitle( NS_CATEGORY, $row->page_title );
//				$titleTo = Title::makeTitle( NS_CATEGORY, $row->cl_to );
//				$this->rdfWriter->about( $this->titleToUrl( $titleFrom ) )
//					->say( self::ONTOLOGY_PREFIX, 'parentCategory' )
//					->is( $this->titleToUrl( $titleTo ) );
//			}
//			fwrite( $output, $this->rdfWriter->drain() );
//		}
//
//		$this->rdfWriter->finish();
//		fwrite($output, $this->rdfWriter->drain());
	}

	private function titleToUrl(Title $title) {
		global $wgArticlePath;
		$url = str_replace( '$1', rawurlencode( $title->getPrefixedText() ), $wgArticlePath );
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
