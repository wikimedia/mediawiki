<?php
use Wikimedia\Purtle\RdfWriter;
use Wikimedia\Purtle\RdfWriterFactory;

require_once __DIR__ . '/Maintenance.php';

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

		$dbr = $this->getDB( DB_REPLICA );
		$where = [];

		while ( true ) {
			$cats = $dbr->select(
				[ 'category', 'page' ],
				[ 'cat_title', 'page_id', 'cat_subcats' ],
				$where,
				__METHOD__,
				[
					'ORDER BY' => 'cat_title',
					'LIMIT' => $this->mBatchSize,
				], [
					'page' => [
						'LEFT JOIN',
						[
							'page_namespace' => NS_CATEGORY,
							'page_title = cat_title'
						]
					],
				]
			);

			if ( !$cats || $cats->numRows() < 1) {
				break;
			}

			foreach ( $cats as $row ) {
				$title = Title::makeTitle( NS_CATEGORY, $row->cat_title );
				$this->rdfWriter->about( $title->getCanonicalURL() )
					->say( 'a' )
					->is( self::ONTOLOGY_PREFIX, 'Category' );
				$this->rdfWriter->say( 'rdfs', 'label' )->value( $row->cat_title );
				if ( $row->cat_subcats > 0 ) {
					$this->doSubcats( $dbr, $row );
				}
			}
			$where = "cat_title > " . $dbr->addQuotes( $row->cat_title );
			fwrite( $output, $this->rdfWriter->drain() );
		}


		$this->rdfWriter->finish();
		fwrite($output, $this->rdfWriter->drain());
	}

	/**
	 * @param string $format Writer format
	 * @return RdfWriter
	 */
	private function createRdfWriter( $format ) {
		$factory = new RdfWriterFactory();
		return $factory->getWriter( $factory->getFormatName( $format ) );
	}

	/**
	 * Deal with subcaategories
	 * @param Database $dbr
	 * @param          $row
	 */
	private function doSubcats( Database $dbr, $row ) {
		$subcats = $dbr->select(
			[ 'categorylinks', 'page' ],
			'page_title',
			[ 'cl_to' => $row->cat_title, 'page_namespace' => NS_CATEGORY, 'cl_type' => 'subcat' ],
			__METHOD__,
			[],
			[
				'page' => [
					'INNER JOIN',
					[
						'page_id = cl_from'
					]
				],
			]
		);
		foreach ( $subcats as $subcat ) {
			$title = Title::makeTitle( NS_CATEGORY, $subcat->page_title );
			$this->rdfWriter->say( self::ONTOLOGY_PREFIX, 'hasSubcategory' )->is( $title->getCanonicalURL() );
		}
	}
}

$maintClass = "CategoriesRDF";
require_once RUN_MAINTENANCE_IF_MAIN;
