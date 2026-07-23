<?php

namespace MediaWiki\Tests\Unit;

use Wikimedia\Parsoid\Core\DOMCompat;
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
}
