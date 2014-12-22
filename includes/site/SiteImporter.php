<?php

/**
 * Utility for importing site entries from a stream.
 * For the expected file format of the input stream, see SiteFormat.wiki.
 *
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
 * @since 1.21
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class SiteImporter {

	/**
	 * @var SiteStore
	 */
	private $store;

	/**
	 * @var callable
	 */
	private $exceptionCallback;

	public function __construct( SiteStore $store ) {
		$this->store = $store;
	}

	/**
	 * @param string $file
	 *
	 * @throws MWException
	 */
	public function importFile( $file ) {
		$xml = file_get_contents( $file );

		if ( !is_string( $xml ) ) {
			throw new MWException( 'Failed to read file ' . $file );
		}

		$this->importXML( $xml );
	}

	/**
	 * @param string $xml
	 *
	 * @throws MWException
	 */
	public function importXML( $xml ) {
		$dom = new DOMDocument();
		wfSuppressWarnings();
		$result = $dom->loadXML( $xml );
		wfRestoreWarnings();

		if ( !$result ) {
			// Try running the XML through UtfNormal to get rid of invalid characters
			$xml = UtfNormal::cleanUp( $xml );
			$result = $dom->loadXML( $xml );
		}

		if ( !$result ) {
			throw new MWException( 'Invalid XML' );
		}

		$this->importDOM( $dom );
	}

	/**
	 * @param DOMDocument $dom
	 *
	 * @throws MWException
	 */
	public function importDOM( DOMDocument $dom ) {
		if ( $dom->documentElement->tagName !== 'Sites' ) {
			throw new MWException( 'Expected <Sites> element.' );
		}

		/** @var DOMElement $element */
		$element = $dom->documentElement->firstChild;
		while ( $element ) {
			$this->importSite( $element );

			$element = $element->nextSibling;
		}
	}

	/**
	 * @param DOMElement $element
	 *
	 * @throws MWException
	 */
	public function importSite( DOMElement $element ) {
		if ( $element->tagName !== 'Site' ) {
			throw new MWException( 'Expected <Site> element.' );
		}

		$site = Site::newForType( $this->getAttributeValue( $element, 'type', Site::TYPE_UNKNOWN ) );
		$site->setSource( $this->getAttributeValue( $element, 'source', Site::SOURCE_LOCAL ) );

		$site->setGlobalId( $this->getTagValue( $element, 'GlobalID' ) );
		$site->setGroup( $this->getTagValue( $element, 'Group', Site::GROUP_NONE ) );
		$site->setForward( $this->hasTag( $element, 'Forward' ) );
		$site->setLanguageCode( $this->getTagValue( $element, 'Language', null ) );

		$paths = $this->getTagValueMap( $element, 'Path', 'type' );
		foreach ( $paths as $type => $path ) {
			$site->setPath( $type, $path );
		}
	}

	private function getAttributeValue( DOMElement $element, $attribute, $default = false ) {
		if ( $element->hasAttribute( $attribute ) ) {
			return $element->getAttributeNode( $attribute );
		}

		if ( $default === false ){
			throw new MWException( 'Expected attribute ' . $attribute . ' in <' . $element->tagName . '> tag' );
		}

		return $default;
	}

	private function getTagValue( DOMElement $element, $tag, $default = false ) {
		$matches = $element->getElementsByTagName( $tag );

		if ( $matches->length === 0 ) {
			if ( $default === false ) {
				throw new MWException( 'Expected <' . $tag . '> tag in <' . $element->tagName . '> tag' );
			} else {
				return $default;
			}
		}

		if ( $matches->length > 1 ) {
			throw new MWException( 'Found multiple <' . $tag . '> tags in <' . $element->tagName . '> tag' );
		}

		$valueElement = $matches->item( 0 );
		return $valueElement->textContent;
	}

	private function hasTag( DOMElement $element, $tag ) {
		$matches = $element->getElementsByTagName( $tag );
		return $matches->length > 0;
	}

	private function getTagValueMap( DOMElement $element, $tag, $keyAttribute ) {
		$values = array();
		$matches = $element->getElementsByTagName( $tag );

		for (  $i = 0; $i < $matches->length; $i++ ) {
			$subElement = $matches->item( $i );
			$key = $this->getAttributeValue( $subElement, $keyAttribute );
			$values[$key] = $subElement->textContent;
		}

		return $values;
	}

}
