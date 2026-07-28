<?php

namespace MediaWiki\Tests\Unit;

use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Ext\DOMUtils;

/**
 * Methods that can be used to assert on HTML. Used by special page tests and pager class
 * tests to check the outputted HTML matches the expected structure
 *
 * @stable to use
 * @since 1.47
 */
trait HtmlAssertionHelperTrait {
	/**
	 * Expects that one element exists with the given selector in the provided HTML.
	 *
	 * Useful for asserting on the special page HTML to check that elements exist and then
	 * checking that specific content is inside that element.
	 *
	 * Note: If you're passing the same HTML multiple times to this method, or use the result to perform another
	 * assertion, consider using {@link HtmlAssertionHelperTrait::assertSelectorMatchesOneElementInNode()} instead, as
	 * it can be faster as it doesn't need to parse and serialize the same HTML every time.
	 *
	 * @param string $html The HTML to search through
	 * @param string $selector The CSS selector to use as the search term
	 * @return string The HTML of the found element
	 * @since 1.47
	 */
	protected function assertSelectorMatchesOneElement( string $html, string $selector ): string {
		$specialPageDocument = DOMUtils::parseHTML( $html );
		$element = DOMCompat::querySelectorAll( $specialPageDocument, $selector );
		$this->assertCount( 1, $element, "Could not find only one element with CSS selector $selector in $html" );
		return DOMCompat::getOuterHTML( $element[0] );
	}

	/**
	 * Expects that one element exists with the given selector in the provided node.
	 *
	 * @see HtmlAssertionHelperTrait::assertSelectorMatchesOneElement()
	 *
	 * @param Document|Element $node The node containing the element we're looking for
	 * @param string $selector The CSS selector to use as the search term
	 * @param bool $returnAsHtml Whether a HTML string should be returned instead of an {@link Element} object.
	 * @return Element|string The found element as an object or a HTML string, depending on the value of $returnAsHtml
	 * @since 1.47
	 */
	protected function assertSelectorMatchesOneElementInNode(
		Document|Element $node,
		string $selector,
		bool $returnAsHtml = false,
	): Element|string {
		$element = DOMCompat::querySelectorAll( $node, $selector );
		if ( count( $element ) !== 1 ) {
			if ( $node instanceof Element ) {
				$html = DOMCompat::getOuterHTML( $node );
			} else {
				$html = $node->saveHTML();
			}
			$this->assertCount( 1, $element, "Could not find only one element with CSS selector $selector in $html" );
		}
		return $returnAsHtml ? DOMCompat::getOuterHTML( $element[0] ) : $element[0];
	}
}
