<?php
use Wikimedia\Purtle\RdfWriter;

/**
 * Helper class to produce RDF representation of categories.
 * @package maintenance
 */
class CategoriesRdf {
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

	public function __construct( RdfWriter $writer ) {
		$this->rdfWriter = $writer;
	}

	/**
	 * Setup prefixes relevant for the dump
	 */
	public function setupPrefixes() {
		$this->rdfWriter->prefix( self::ONTOLOGY_PREFIX, self::ONTOLOGY_URL );
		$this->rdfWriter->prefix( 'rdfs', 'http://www.w3.org/2000/01/rdf-schema#' );
		$this->rdfWriter->prefix( 'owl', 'http://www.w3.org/2002/07/owl#' );
		$this->rdfWriter->prefix( 'schema', 'http://schema.org/' );
		$this->rdfWriter->prefix( 'cc', 'http://creativecommons.org/ns#' );
	}

	/**
	 * Write RDF data for link between categories.
	 * @param string $fromLabel Child category label
	 * @param string $toLabel Parent category label
	 */
	public function writeCategoryLinkData( $fromLabel, $toLabel ) {
		$titleFrom = Title::makeTitle( NS_CATEGORY, $fromLabel );
		$titleTo = Title::makeTitle( NS_CATEGORY, $toLabel );
		$this->rdfWriter->about( $this->titleToUrl( $titleFrom ) )
			->say( self::ONTOLOGY_PREFIX, 'parentCategory' )
			->is( $this->titleToUrl( $titleTo ) );
	}

	/**
	 * Write out the data for single category.
	 * @param string $titleLabel Category title
	 */
	public function writeCategoryData( $titleLabel ) {
		$title = Title::makeTitle( NS_CATEGORY, $titleLabel );
		$this->rdfWriter->about( $this->titleToUrl( $title ) )
			->say( 'a' )
			->is( self::ONTOLOGY_PREFIX, 'Category' );
		$titletext = $title->getText();
		$this->rdfWriter->say( 'rdfs', 'label' )->value( $titletext );
	}

	/**
	 * Convert Title to link to target page.
	 * @param Title $title
	 * @return string
	 */
	private function titleToUrl( Title $title ) {
		return $title->getFullURL( '', false, PROTO_CANONICAL );
	}
}
