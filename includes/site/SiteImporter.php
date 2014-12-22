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
 * @since 1.25
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
	 * @return callable
	 */
	public function getExceptionCallback() {
		return $this->exceptionCallback;
	}

	/**
	 * @param callable $exceptionCallback
	 */
	public function setExceptionCallback( $exceptionCallback ) {
		$this->exceptionCallback = $exceptionCallback;
	}

	/**
	 * @param string $file
	 */
	public function importFromFile( $file ) {
		$document = new DOMDocument();
		$document->load( $file );
		$this->importFromDOM( $document->documentElement );
	}

	/**
	 * @param string $xml
	 */
	public function importFromXML( $xml ) {
		$document = new DOMDocument();
		$document->loadXML( $xml );
		$this->importFromDOM( $document->documentElement );
	}

	/**
	 * @param DOMElement $root
	 */
	private function importFromDOM( DOMElement $root ) {
		$sites = $this->makeSiteList( $root );
		$this->store->saveSites( iterator_to_array( $sites ) );
	}

	/**
	 * @param DOMElement $root
	 *
	 * @return SiteList
	 */
	private function makeSiteList( DOMElement $root ) {
		$sites = new SiteList();

		$current = $root->firstChild;
		while ( $current ) {
			if ( $current instanceof DOMElement &&  $current->tagName === 'Site' ) {
				try {
					$site = $this->makeSite( $current );
					$sites->append( $site );
				} catch ( Exception $ex ) {
					$this->handleException( $ex );
				}
			}

			$current = $current->nextSibling;
		}

		return $sites;
	}

	/**
	 * @param DOMElement $siteElement
	 *
	 * @return Site
	 * @throws MWException
	 */
	public function makeSite( DOMElement $siteElement ) {
		if ( $siteElement->tagName !== 'Site' ) {
			throw new InvalidArgumentException( 'Expected <Site> tag, found ' . $siteElement->tagName );
		}

		$type = $this->getAttributeValue( $siteElement, 'type', Site::TYPE_UNKNOWN );
		$site = Site::newForType( $type );

		$site->setForward( $this->hasChild( $siteElement, 'Forward' ) );
		$site->setGlobalId( $this->getChildValue( $siteElement, 'GlobalID' ) );
		$site->setGroup( $this->getChildValue( $siteElement, 'Group', Site::GROUP_NONE ) );
		$site->setSource( $this->getChildValue( $siteElement, 'Source', Site::SOURCE_LOCAL ) );

		$pathTags = $siteElement->getElementsByTagName( 'Path' );
		for ( $i = 0; $i < $pathTags->length; $i++ ) {
			$pathElement = $pathTags->item( $i );
			$pathType = $this->getAttributeValue( $pathElement, 'type' );
			$path = $pathElement->textContent;

			$site->setPath( $pathType, $path );
		}

		$idTags = $siteElement->getElementsByTagName( 'LocalID' );
		for ( $i = 0; $i < $idTags->length; $i++ ) {
			$idElement = $idTags->item( $i );
			$idType = $this->getAttributeValue( $idElement, 'type' );
			$id = $idElement->textContent;

			$site->addLocalId( $idType, $id );
		}

		//@todo: import <ExtraData>
		//@todo: import <ExtraConfig>

		return $site;
	}

	/**
	 * @param DOMElement $element
	 * @param $name
	 * @param string|null|bool $default
	 *
	 * @return null|string
	 * @throws MWException If the attribute is not found and no default is provided
	 */
	private function getAttributeValue( DOMElement $element, $name, $default = false ) {
		$node = $element->getAttributeNode( $name );

		if ( !$node ) {
			if ( $default !== false ) {
				return $default;
			} else {
				throw new MWException( 'Required ' . $name . ' attribute not found in <' . $element->tagName . '> tag' );
			}
		}

		return $node->textContent;
	}

	/**
	 * @param DOMElement $element
	 * @param string $name
	 * @param string|null|bool $default
	 *
	 * @return null|string
	 * @throws MWException If the child element is not found and no default is provided
	 */
	private function getChildValue( DOMElement $element, $name, $default = false ) {
		$elements = $element->getElementsByTagName( $name );

		if ( $elements->length < 1 ) {
			if ( $default !== false ) {
				return $default;
			} else {
				throw new MWException( 'Required <' . $name . '> tag not found inside <' . $element->tagName . '> tag' );
			}
		}

		$node = $elements->item( 0 );
		return $node->textContent;
	}

	/**
	 * @param DOMElement $element
	 * @param string $name
	 *
	 * @return bool
	 * @throws MWException
	 */
	private function hasChild( DOMElement $element, $name ) {
		return $this->getChildValue( $element, $name, null ) !== null;
	}

	private function handleException( $ex ) {
		call_user_func( $this->exceptionCallback, $ex );
	}

}
