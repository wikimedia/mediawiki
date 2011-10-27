<?php

/**
 * @group Database
 */
class GlobalWithDBTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideWfIsBadImageList
	 */
	function testWfIsBadImage( $name, $title, $blacklist, $expected, $desc ) {
		$this->assertEquals( $expected, wfIsBadImage( $name, $title, $blacklist ), $desc );
	}

	function provideWfIsBadImageList() {
		$blacklist = '* [[File:Bad.jpg]] except [[Nasty page]]';
		return array(
			array( 'Bad.jpg', false, $blacklist, true,
				'Called on a bad image' ),
			array( 'Bad.jpg', Title::makeTitle( NS_MAIN, 'A page' ), $blacklist, true,
				'Called on a bad image' ),
			array( 'NotBad.jpg', false, $blacklist, false,
				'Called on a non-bad image' ),
			array( 'Bad.jpg', Title::makeTitle( NS_MAIN, 'Nasty page' ), $blacklist, false,
				'Called on a bad image but is on a whitelisted page' ),
			array( 'File:Bad.jpg', false, $blacklist, false,
				'Called on a bad image with File:' ),
		);
	}
}
