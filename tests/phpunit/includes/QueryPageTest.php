<?php

class QueryPageTest extends MediaWikiTestCase {

	/**
	 * Test, if each query page is also a special page
	 */
	public function testQueryPagesRegisteredAsSpecialPage() {
		// We need to do this to make sure $wgQueryPages is set up
		// This SUCKS
		global $IP;
		require_once( "$IP/includes/QueryPage.php" );

		global $wgQueryPages;

		$allQueryPagesKeys = array();
		foreach ( $wgQueryPages as $page ) {
			$allQueryPagesKeys[] = $page[1];
		}

		//get list of all special pages
		$allSpecialPagesKeys = array_keys( (array)SpecialPageFactory::getList() );

		//remove all defined special pages from list of QueryPages
		$diff = array_diff( $allQueryPagesKeys, $allSpecialPagesKeys );

		//when the array is not empty, some query pages not registered as special pages
		$this->assertEquals(
			$diff,
			array(),
			'Each query page (core/extensions) is registered as special page.'
		);
	}
}