<?php

/**
 * @covers SpecialPage
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class SpecialPageTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgContLang' => Language::factory( 'en' )
		) );
	}

	/**
	 * @dataProvider getTitleForProvider
	 */
	public function testGetTitleFor( $expectedName, $name ) {
		$title = SpecialPage::getTitleFor( $name );
		$expected = Title::makeTitle( NS_SPECIAL, $expectedName );
		$this->assertEquals( $expected, $title );
	}

	public function getTitleForProvider() {
		return array(
			array( 'UserLogin', 'Userlogin' )
		);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Notice
	 */
	public function testInvalidGetTitleFor() {
		$title = SpecialPage::getTitleFor( 'cat' );
		$expected = Title::makeTitle( NS_SPECIAL, 'Cat' );
		$this->assertEquals( $expected, $title );
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
