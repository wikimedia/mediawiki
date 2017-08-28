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

/**
 * Helper class to produce RDF representation of categories.
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
	 * @param string $fromName Child category name
	 * @param string $toName Parent category name
	 */
	public function writeCategoryLinkData( $fromName, $toName ) {
		$titleFrom = Title::makeTitle( NS_CATEGORY, $fromName );
		$titleTo = Title::makeTitle( NS_CATEGORY, $toName );
		$this->rdfWriter->about( $this->titleToUrl( $titleFrom ) )
			->say( self::ONTOLOGY_PREFIX, 'isInCategory' )
			->is( $this->titleToUrl( $titleTo ) );
	}

	/**
	 * Write out the data for single category.
	 * @param string $categoryName Category name
	 */
	public function writeCategoryData( $categoryName ) {
		$title = Title::makeTitle( NS_CATEGORY, $categoryName );
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
