<?php

use MediaWiki\MediaWikiServices;

/**
 * Test class for Prefixindex class
 *
 * @group Database
 */
class SpecialPrefixindexTest extends MediaWikiTestCase {
	public function testSubPageRedirect() {
		$this->setMwGlobals( [
			'wgScript' => '/w/index.php',
		] );

		$ctx = new RequestContext;
		$sp = Title::newFromText( 'Special:Prefixindex/foo_bar' );
		SpecialPageFactory::executePath( $sp, $ctx );
		$url = $ctx->getOutput()->getRedirect();
		// some older versions of hhvm have a bug that doesn't parse relative
		// urls with a port, so help it out a little bit.
		// https://github.com/facebook/hhvm/issues/7136
		$url = wfExpandUrl( $url, PROTO_CURRENT );

		$parts = parse_url( $url );
		$this->assertEquals( '/w/index.php/Special:PrefixIndex/foo_bar', $parts['path'] );
		parse_str( $parts['query'], $query );
		$this->assertEquals( 'Special:Prefixindex', $query['title'] );
		$this->assertEquals( 'foo bar', $query['prefix'] );
	}
}