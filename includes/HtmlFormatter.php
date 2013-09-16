<?php

/**
 * Performs transformations of HTML by wrapping around libxm2 and working
 * around its countless bugs.
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
 * @file
 */
class HtmlFormatter {
	/**
	 * @var DOMDocument
	 */
	private $doc;

	private $html;
	private $itemsToRemove = array();
	private $elementsToFlatten = array();
	protected $removeImages = false;

	/**
	 * Constructor
	 *
	 * @param string $html: Text to process
	 */
	public function __construct( $html ) {
		$this->html = $html;
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

			// https://bugzilla.wikimedia.org/show_bug.cgi?id=53086
			$html = str_replace( ' <', '&#32;<', $html );

			libxml_use_internal_errors( true );
			$this->doc = new DOMDocument();
			$this->doc->strictErrorChecking = false;
			$this->doc->loadHTML( $html );
			libxml_use_internal_errors( false );
			$this->doc->encoding = 'UTF-8';
		}
		return $this->doc;
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
				if ( $tagToRemoveNode ) {
					$domElemsToRemove[] = $tagToRemoveNode;
				}
			}
		}

		$this->removeElements( $domElemsToRemove );

		// Elements with named IDs
		$domElemsToRemove = array();
		foreach ( $removals['ID'] as $itemToRemove ) {
			$itemToRemoveNode = $doc->getElementById( $itemToRemove );
			if ( $itemToRemoveNode ) {
				$domElemsToRemove[] = $itemToRemoveNode;
			}
		}
		$this->removeElements( $domElemsToRemove );

		// CSS Classes
		$domElemsToRemove = array();
		$xpath = new DOMXpath( $doc );
		foreach ( $removals['CLASS'] as $classToRemove ) {
			$elements = $xpath->query( '//*[contains(@class, "' . $classToRemove . '")]' );

			/** @var $element DOMElement */
			foreach ( $elements as $element ) {
				$classes = $element->getAttribute( 'class' );
				if ( preg_match( "/\b$classToRemove\b/", $classes ) && $element->parentNode ) {
					$domElemsToRemove[] = $element;
				}
			}
		}
		$this->removeElements( $domElemsToRemove );

		// Tags with CSS Classes
		foreach ( $removals['TAG_CLASS'] as $classToRemove ) {
			$parts = explode( '.', $classToRemove );

			$elements = $xpath->query(
				'//' . $parts[0] . '[@class="' . $parts[1] . '"]'
			);

			/** @var $element DOMElement */
			foreach ( $elements as $element ) {
				if ( $element->parentNode ) {
					$element->parentNode->removeChild( $element );
				}
			}
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Removes a list of elelments from DOMDocument
	 * @param array $elements
	 */
	private function removeElements( array $elements ) {
		/** @var $element DOMElement */
		foreach ( $elements as $element ) {
			if ( $element->parentNode ) {
				$element->parentNode->removeChild( $element );
			}
		}
	}

	/**
	 * libxml in its usual pointlessness converts many chars to entities - this function
	 * perfoms a reverse conversion
	 * @param string $html
	 * @return string
	 */
	private function fixLibXML( $html ) {
		wfProfileIn( __METHOD__ );
		static $replacements;
		if ( ! $replacements ) {
			// We don't include rules like '&#34;' => '&amp;quot;' because entities had already been
			// normalized by libxml. Using this function with input not sanitized by libxml is UNSAFE!
			$replacements = new ReplacementArray( array(
				'&quot;' => '&amp;quot;',
				'&amp;' => '&amp;amp;',
				'&lt;' => '&amp;lt;',
				'&gt;' => '&amp;gt;',
			) );
		}
		$html = $replacements->replace( $html );
		$html = mb_convert_encoding( $html, 'UTF-8', 'HTML-ENTITIES' );
		wfProfileOut( __METHOD__ );
		return $html;
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
			$html = $this->fixLibXml( $html );
		} else {
			$html = $this->html;
		}
		if ( wfIsWindows() ) {
			$html = str_replace( '&#13;', '', $html );
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
			throw new MWException( __METHOD__ . "(): unrecognized selector '$selector'" );
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
		}

		wfProfileOut( __METHOD__ );
		return $removals;
	}
}
