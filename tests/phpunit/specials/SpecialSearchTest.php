<?php

class SpecialSearchText extends \PHPUnit_Framework_TestCase {
	public function testSubPageRedirect() {
		$ctx = new RequestContext;

		SpecialPageFactory::executePath(
			Title::newFromText( 'Special:Search/foo_bar' ),
			$ctx
		);
		$url = $ctx->getOutput()->getRedirect();
		// some older versions of hhvm have a bug that doesn't parse relative
		// urls with a port, so help it out a little bit.
		// https://github.com/facebook/hhvm/issues/7136
		$url = wfExpandUrl( $url, PROTO_CURRENT );

		$parts = parse_url( $url );
		$this->assertEquals( '/w/index.php', $parts['path'] );
		parse_str( $parts['query'], $query );
		$this->assertEquals( 'Special:Search', $query['title'] );
		$this->assertEquals( 'foo bar', $query['search'] );
	}
}
