<?php
/**
 * @group GlobalFunctions
 * @covers ::wfBaseName
 */
class WfBaseNameTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider providePaths
	 */
	public function testBaseName( $fullpath, $basename ) {
		$this->assertEquals( $basename, wfBaseName( $fullpath ),
			"wfBaseName('$fullpath') => '$basename'" );
	}

	public static function providePaths() {
		return [
			[ '', '' ],
			[ '/', '' ],
			[ '\\', '' ],
			[ '//', '' ],
			[ '\\\\', '' ],
			[ 'a', 'a' ],
			[ 'aaaa', 'aaaa' ],
			[ '/a', 'a' ],
			[ '\\a', 'a' ],
			[ '/aaaa', 'aaaa' ],
			[ '\\aaaa', 'aaaa' ],
			[ '/aaaa/', 'aaaa' ],
			[ '\\aaaa\\', 'aaaa' ],
			[ '\\aaaa\\', 'aaaa' ],
			[
				'/mnt/upload3/wikipedia/en/thumb/8/8b/'
					. 'Zork_Grand_Inquisitor_box_cover.jpg/93px-Zork_Grand_Inquisitor_box_cover.jpg',
				'93px-Zork_Grand_Inquisitor_box_cover.jpg'
			],
			[ 'C:\\Progra~1\\Wikime~1\\Wikipe~1\\VIEWER.EXE', 'VIEWER.EXE' ],
			[ 'Östergötland_coat_of_arms.png', 'Östergötland_coat_of_arms.png' ],
		];
	}
}
