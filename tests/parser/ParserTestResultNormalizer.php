<?php
/**
 * @file
 * @ingroup Testing
 */

class ParserTestResultNormalizer {
	protected $doc, $xpath, $invalid;

	public static function normalize( $text, $funcs ) {
		$norm = new self( $text );
		if ( $norm->invalid ) {
			return $text;
		}
		foreach ( $funcs as $func ) {
			$norm->$func();
		}
		return $norm->serialize();
	}

	protected function __construct( $text ) {
		$this->doc = new DOMDocument( '1.0', 'utf-8' );

		// Note: parsing a supposedly XHTML document with an XML parser is not
		// guaranteed to give accurate results. For example, it may introduce
		// differences in the number of line breaks in <pre> tags.

		MediaWiki\suppressWarnings();
		if ( !$this->doc->loadXML( '<html><body>' . $text . '</body></html>' ) ) {
			$this->invalid = true;
		}
		MediaWiki\restoreWarnings();
		$this->xpath = new DOMXPath( $this->doc );
		$this->body = $this->xpath->query( '//body' )->item( 0 );
	}

	protected function removeTbody() {
		foreach ( $this->xpath->query( '//tbody' ) as $tbody ) {
			while ( $tbody->firstChild ) {
				$child = $tbody->firstChild;
				$tbody->removeChild( $child );
				$tbody->parentNode->insertBefore( $child, $tbody );
			}
			$tbody->parentNode->removeChild( $tbody );
		}
	}

	/**
	 * The point of this function is to produce a normalized DOM in which
	 * Tidy's output matches the output of html5depurate. Tidy both trims
	 * and pretty-prints, so this requires fairly aggressive treatment.
	 *
	 * In particular, note that Tidy converts <pre>x</pre> to <pre>\nx\n</pre>,
	 * which theoretically affects display since the second line break is not
	 * ignored by compliant HTML parsers.
	 *
	 * This function also removes empty elements, as does Tidy.
	 */
	protected function trimWhitespace() {
		foreach ( $this->xpath->query( '//text()' ) as $child ) {
			if ( strtolower( $child->parentNode->nodeName ) === 'pre' ) {
				// Just trim one line break from the start and end
				if ( substr_compare( $child->data, "\n", 0 ) === 0 ) {
					$child->data = substr( $child->data, 1 );
				}
				if ( substr_compare( $child->data, "\n", -1 ) === 0 ) {
					$child->data = substr( $child->data, 0, -1 );
				}
			} else {
				// Trim all whitespace
				$child->data = trim( $child->data );
			}
			if ( $child->data === '' ) {
				$child->parentNode->removeChild( $child );
			}
		}
	}

	/**
	 * Serialize the XML DOM for comparison purposes. This does not generate HTML.
	 */
	protected function serialize() {
		return strtr( $this->doc->saveXML( $this->body ),
			[ '<body>' => '', '</body>' => '' ] );
	}
}
