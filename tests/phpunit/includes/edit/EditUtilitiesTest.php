<?php

/**
 * @group Editing
 */
class EditUtilitiesTest extends MediaWikiLangTestCase {
	/**
	 * @dataProvider provideExtractSectionTitle
	 * @covers EditUtilities::extractSectionTitle
	 */
	public function testExtractSectionTitle( $section, $title ) {
		$extracted = EditUtilities::extractSectionTitle( $section );
		$this->assertEquals( $title, $extracted );
	}

	public static function provideExtractSectionTitle() {
		return [
			[
				"== Test ==\n\nJust a test section.",
				"Test"
			],
			[
				"An initial section, no header.",
				false
			],
			[
				"An initial section with a fake heder (bug 32617)\n\n== Test == ??\nwtf",
				false
			],
			[
				"== Section ==\nfollowed by a fake == Non-section == ??\nnoooo",
				"Section"
			],
			[
				"== Section== \t\r\n followed by whitespace (bug 35051)",
				'Section',
			],
		];
	}

}
