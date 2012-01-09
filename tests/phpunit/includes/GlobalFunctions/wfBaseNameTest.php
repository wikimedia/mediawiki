<?php
/**
 * Tests for wfBaseName()
 */
class wfBaseName extends MediaWikiTestCase {
	/**
	 * @dataProvider providePaths
	 */
	function testBaseName( $fullpath, $basename ) {
		$this->assertEquals( $basename, wfBaseName( $fullpath ),
				"wfBaseName('$fullpath') => '$basename'" );
	}
	
	function providePaths() {
		return array(
			array( '', '' ),
			array( '/', '' ),
			array( '\\', '' ),
			array( '//', '' ),
			array( '\\\\', '' ),
			array( 'a', 'a' ),
			array( 'aaaa', 'aaaa' ),
			array( '/a', 'a' ),
			array( '\\a', 'a' ),
			array( '/aaaa', 'aaaa' ),
			array( '\\aaaa', 'aaaa' ),
			array( '/aaaa/', 'aaaa' ),
			array( '\\aaaa\\', 'aaaa' ),
			array( '\\aaaa\\', 'aaaa' ),
			array( '/mnt/upload3/wikipedia/en/thumb/8/8b/Zork_Grand_Inquisitor_box_cover.jpg/93px-Zork_Grand_Inquisitor_box_cover.jpg',
				'93px-Zork_Grand_Inquisitor_box_cover.jpg' ),
			array( 'C:\\Progra~1\\Wikime~1\\Wikipe~1\\VIEWER.EXE', 'VIEWER.EXE' ),
			array( 'Östergötland_coat_of_arms.png', 'Östergötland_coat_of_arms.png' ),
		);
	}
}
