<?php

/**
 * @covers SpecialPageTest
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageTest extends MediaWikiTestCase {

	/**
	 * @dataProvider getTitleForProvider
	 */
	public function testGetTitleFor( $expected, $name ) {
		$title = SpecialPage::getTitleFor( $name );
		$this->assertEquals( $expected, $title );
	}

	public function getTitleForProvider() {
		return array(
			array( Title::makeTitle( NS_SPECIAL, 'UserLogin' ), 'Userlogin' )
		);
	}

	/**
	 * @expectedException MWException
	 */
	public function testInvalidGetTitleFor() {
		SpecialPage::getTitleFor( 'cat' );
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 * @dataProvider getTitleForWithWarningProvider
	 */
	public function testGetTitleForWithWarning( $expected, $name ) {
		$title = SpecialPage::getTitleFor( $name );
		$this->assertEquals( $expected, $title );
	}

	public function getTitleForWithWarningProvider() {
		return array(
			array( Title::makeTitle( NS_SPECIAL, 'UserLogin' ), 'UserLogin' )
		);
	}

}
