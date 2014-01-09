<?php

/**
 * @covers DataValue
 */
class TitleValueTest extends MediaWikiTestCase {

	public function testConstruction() {
		$title = new TitleValue( NS_USER, 'TestThis', '#stuff' );

		$this->assertEquals( NS_USER, $title->getNamespace() );
		$this->assertEquals( 'TestThis', $title->getText() );
		$this->assertEquals( '#stuff', $title->getSection() );
	}

	public function badConstructorProvider() {
		return array(
			array( 'foo', 'title', 'section' ),
			array( null, 'title', 'section' ),
			array( 2.3, 'title', 'section' ),

			array( NS_MAIN, 5, 'section' ),
			array( NS_MAIN, null, 'section' ),
			array( NS_MAIN, '', 'section' ),

			array( NS_MAIN, 'title', 5 ),
			array( NS_MAIN, 'title', null ),
			array( NS_MAIN, 'title', array() ),
		);
	}

	/**
	 * @dataProvider badConstructorProvider
	 */
	public function testConstructionErrors( $ns, $text, $section ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		new TitleValue( $ns, $text, $section );
	}

}
