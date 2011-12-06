<?php

class EditPageTest extends MediaWikiTestCase {

	/**
	 * @dataProvider dataExtractSectionTitle
	 */
	function testExtractSectionTitle( $section, $title ) {
		$extracted = EditPage::extractSectionTitle( $section );
		$this->assertEquals( $title, $extracted );
	}

	function dataExtractSectionTitle() {
		return array(
			array(
				"== Test ==\n\nJust a test section.",
				"Test"
			),
			array(
				"An initial section, no header.",
				false
			),
			array(
				"An initial section with a fake heder (bug 32617)\n\n== Test == ??\nwtf",
				false
			),
			array(
				"== Section ==\nfollowed by a fake == Non-section == ??\nnoooo",
				"Section"
			)
		);
	}
}
