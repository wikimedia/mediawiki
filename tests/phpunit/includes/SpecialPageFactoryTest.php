<?php

class SpecialPageFactoryTest extends MediaWikiTestCase {

	/**
	 * Test, if for each special page a entry exist in $specialPageAliases inside MessagesEn.php file
	 *
	 * It is not possible to test, if for each aliases a special page exist, because some special page
	 * are registered under conditions, but the aliases are valid in message file.
	 */
	public function testAllSpecialPagesWithAliases() {
		//get all defined alias entries from MessagesEn.php
		$allAliasesKeys = array_keys( Language::factory( 'en' )->getSpecialPageAliases() );

		//get the current list of all special pages
		$allPagesKeys = array_keys( (array)SpecialPageFactory::getList() );

		//remove all aliases keys from the pages keys
		$diff = array_diff( $allPagesKeys, $allAliasesKeys );

		//when the array is not empty, something is missing in language files
		$this->assertEquals(
			$diff,
			array(),
			'Each special page (core/extensions) has a corresponding alias in message file.'
		);
	}
}