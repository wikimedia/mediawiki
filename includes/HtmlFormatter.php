<?php

/**
 * Performs transformations of HTML
 */
class HtmlFormatter {
	/**
	 * @var DOMDocument
	 */
	private $doc;

	private $html;
	private $htmlMode;
	private $itemsToRemove = array();
	private $elementsToFlatten = array();
	private $removeImages = false;
	private $idWhitelist = array();
	private $flattenRedLinks = false;

	/**
	 * Constructor
	 *
	 * @param string $html: Text to process
	 */
	public function __construct( $html ) {
		global $wgHtml5;
		wfProfileIn( __METHOD__ );

		$this->html = $html;
		$this->htmlMode = $wgHtml5;

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Turns a chunk of HTML into a proper document
	 * @param string $html
	 * @return string
	 */
	public static function wrapHTML( $html ) {
		return '<!doctype html><html><head></head><body>' . $html . '</body></html>';
	}

	/**
	 * Override this in descendant class to modify HTML after it has been converted from DOM tree
	 * @param string $html: HTML to process
	 * @return string: Processed HTML
	 */
	protected function onHtmlReady( $html ) {
		return $html;
	}

	/**
	 * @return DOMDocument: DOM to manipulate
	 */
	public function getDoc() {
		if ( !$this->doc ) {
			$html = mb_convert_encoding( $this->html, 'HTML-ENTITIES', "UTF-8" );
			libxml_use_internal_errors( true );
			$this->doc = new DOMDocument();
			$this->doc->loadHTML( '<?xml encoding="UTF-8">' . $html );
			libxml_use_internal_errors( false );
			$this->doc->preserveWhiteSpace = false;
			$this->doc->strictErrorChecking = false;
			$this->doc->encoding = 'UTF-8';
		}
		return $this->doc;
	}

	/**
	 * @return bool: Whether this class should output HTML
	 */
	public function getHtmlMode() {
		return $this->htmlMode;
	}

	/**
	 * Sets whether this class should output HTML
	 * @param bool $value
	 */
	public function setHtmlMode( $value ) {
		$this->htmlMode = $value;
	}

	/**
	 * Sets whether images should be removed from output
	 * @param bool $flag
	 */
	public function removeImages( $flag = true ) {
		$this->removeImages = $flag;
	}

	/**
	 * Adds one or more selector of content to remove
	 * @param Array|string $selectors: Selector(s) of stuff to remove
	 */
	public function remove( $selectors ) {
		$this->itemsToRemove = array_merge( $this->itemsToRemove, (array)$selectors );
	}

	/**
	 * Adds one or more element name to the list to flatten (remove tag, but not its content)
	 * @param Array|string $elements: Name(s) of tag(s) to flatten
	 */
	public function flatten( $elements ) {
		$this->elementsToFlatten = array_merge( $this->elementsToFlatten, (array)$elements );
	}

	/**
	 * Instructs the formatter to flatten all tags
	 */
	public function flattenAllTags() {
		$this->flatten( '[?!]?[a-z0-9]+' );
	}

	/**
	 * Sets whether red links should be flattened
	 * @param bool $flag
	 */
	public function flattenRedLinks( $flag = true ) {
		$this->flattenRedLinks = $flag;
	}

	/**
	 * @param Array|string $ids: Id(s) of content to keep
	 */
	public function whitelistIds( $ids ) {
		$this->idWhitelist = array_merge( $this->idWhitelist, array_flip( (array)$ids ) );
	}

	/**
	 * Checks whether specified element should not be removed due to whitelist
	 * @param DOMElement $element: Element to check
	 * @return bool
	 */
	private function elementNotWhitelisted( DOMElement $element ) {
		$idAttribute = $element->getAttributeNode( 'id' );
		if ( $idAttribute ) {
			$id = $idAttribute->value;
			if ( isset( $this->idWhitelist[$id] ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Removes content inappropriate for mobile devices
	 */
	public function filterContent() {
		wfProfileIn( __METHOD__ );
		$removals = $this->parseItemsToRemove();

		if ( !$removals ) {
			return;
		}

		$doc = $this->getDoc();

		// Remove tags

		// You can't remove DOMNodes from a DOMNodeList as you're iterating
		// over them in a foreach loop. It will seemingly leave the internal
		// iterator on the foreach out of wack and results will be quite
		// strange. Though, making a queue of items to remove seems to work.
		// For example:

		$domElemsToRemove = array();
		foreach ( $removals['TAG'] as $tagToRemove ) {
			$tagToRemoveNodes = $doc->getElementsByTagName( $tagToRemove );
			foreach ( $tagToRemoveNodes as $tagToRemoveNode ) {
				if ( $tagToRemoveNode && $this->elementNotWhitelisted( $tagToRemoveNode ) ) {
					$domElemsToRemove[] = $tagToRemoveNode;
				}
			}
		}

		foreach ( $domElemsToRemove as $domElement ) {
			$domElement->parentNode->removeChild( $domElement );
		}

		// Elements with named IDs
		foreach ( $removals['ID'] as $itemToRemove ) {
			$itemToRemoveNode = $doc->getElementById( $itemToRemove );
			if ( $itemToRemoveNode ) {
				$itemToRemoveNode->parentNode->removeChild( $itemToRemoveNode );
			}
		}

		// CSS Classes
		$xpath = new DOMXpath( $doc );
		foreach ( $removals['CLASS'] as $classToRemove ) {
			$elements = $xpath->query( '//*[@class="' . $classToRemove . '"]' );

			foreach ( $elements as $element ) {
				if ( $element->parentNode && $this->elementNotWhitelisted( $element ) ) {
					$element->parentNode->removeChild( $element );
				}
			}
		}

		// Tags with CSS Classes
		foreach ( $removals['TAG_CLASS'] as $classToRemove ) {
			$parts = explode( '.', $classToRemove );

			$elements = $xpath->query(
				'//' . $parts[0] . '[@class="' . $parts[1] . '"]'
			);

			foreach ( $elements as $element ) {
				if ( $element->parentNode && $this->elementNotWhitelisted( $element ) ) {
					$element->parentNode->removeChild( $element );
				}
			}
		}

		// Handle red links with action equal to edit
		if ( $this->flattenRedLinks ) {
			$redLinks = $xpath->query( '//a[@class="new"]' );
			foreach ( $redLinks as $redLink ) {
				// PHP Bug #36795 — Inappropriate "unterminated entity reference"
				$spanNode = $doc->createElement( "span", str_replace( "&", "&amp;", $redLink->nodeValue ) );

				if ( $redLink->hasAttributes() ) {
					$attributes = $redLink->attributes;
					foreach ( $attributes as $attribute ) {
						if ( $attribute->name != 'href' ) {
							$spanNode->setAttribute( $attribute->name, $attribute->value );
						}
					}
				}

				$redLink->parentNode->replaceChild( $spanNode, $redLink );
			}
		}
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Performs final transformations and returns resulting HTML
	 *
	 * @param DOMElement|string|null $element: ID of element to get HTML from or false to get it from the whole tree
	 * @return string: Processed HTML
	 */
	public function getText( $element = null ) {
		wfProfileIn( __METHOD__ );

		if ( $this->doc ) {
			if ( $element !== null && !( $element instanceof DOMElement ) ) {
				$element = $this->doc->getElementById( $element );
			}
			if ( $this->htmlMode ) {
				if ( $element ) {
					$body = $this->doc->getElementsByTagName( 'body' )->item( 0 );
					$nodesArray = array();
					foreach ( $body->childNodes as $node ) {
						$nodesArray[] = $node;
					}
					foreach ( $nodesArray as $nodeArray ) {
						$body->removeChild( $nodeArray );
					}
					$body->appendChild( $element );
				}
				$html = $this->doc->saveHTML();
			} else {
				$html = $this->doc->saveXML( $element, LIBXML_NOEMPTYTAG );
			}
			if ( wfIsWindows() ) {
				$html = str_replace( '&#13;', '', $html );
			}
		} else {
			$html = $this->html;
		}
		$html = preg_replace( '/<!--.*?-->|^.*?<body>|<\/body>.*$/s', '', $html );
		$html = $this->onHtmlReady( $html );

		if ( $this->elementsToFlatten ) {
			$elements = implode( '|', $this->elementsToFlatten );
			$html = preg_replace( "#</?($elements)\\b[^>]*>#is", '', $html );
		}

		wfProfileOut( __METHOD__ );
		return $html;
	}

	/**
	 * @param $selector: CSS selector to parse
	 * @param $type
	 * @param $rawName
	 * @return bool: Whether the selector was successfully recognised
	 */
	protected function parseSelector( $selector, &$type, &$rawName ) {
		if ( strpos( $selector, '.' ) === 0 ) {
			$type = 'CLASS';
			$rawName = substr( $selector, 1 );
		} elseif ( strpos( $selector, '#' ) === 0 ) {
			$type = 'ID';
			$rawName = substr( $selector, 1 );
		} elseif ( strpos( $selector, '.' ) !== 0 &&
			strpos( $selector, '.' ) !== false )
		{
			$type = 'TAG_CLASS';
			$rawName = $selector;
		} elseif ( strpos( $selector, '[' ) === false
			&& strpos( $selector, ']' ) === false )
		{
			$type = 'TAG';
			$rawName = $selector;
		} else {
			wfDebug( __METHOD__ . ": unrecognised selector '$selector'\n" );
			return false;
		}

		return true;
	}

	/**
	 * Transforms CSS selectors into an internal representation suitable for processing
	 * @return array
	 */
	protected function parseItemsToRemove() {
		wfProfileIn( __METHOD__ );
		$removals = array(
			'ID' => array(),
			'TAG' => array(),
			'CLASS' => array(),
			'TAG_CLASS' => array(),
		);

		foreach ( $this->itemsToRemove as $itemToRemove ) {
			$type = '';
			$rawName = '';
			if ( $this->parseSelector( $itemToRemove, $type, $rawName ) ) {
				$removals[$type][] = $rawName;
			}
		}

		if ( $this->removeImages ) {
			$removals['TAG'][] = "img";
			$removals['TAG'][] = "audio";
			$removals['TAG'][] = "video";
			$removals['CLASS'][] = "thumb tright";
			$removals['CLASS'][] = "thumb tleft";
			$removals['CLASS'][] = "thumbcaption";
			$removals['CLASS'][] = "gallery";
		}

		wfProfileOut( __METHOD__ );
		return $removals;
	}
}
