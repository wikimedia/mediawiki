<?php

class GlobalConfigTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
		//Make sure anything we use is reset after the test
		$this->setMwGlobals( array(
			'wgSitename' => 'default1',
			'wgFoo' => 'default2',
			'efVariable' => 'default3',
			'BAR' => 'default4',
		) );
	}

	public function provideGet() {
		return array(
			array( 'Sitename', 'wg', 'default1' ),
			array( 'Foo', 'wg', 'default2' ),
			array( 'Variable', 'ef', 'default3' ),
			array( 'BAR', '', 'default4' ),
		);
	}

	/**
	 * @dataProvider provideGet
	 * @covers GlobalConfig::get
	 */
	public function testGet( $name, $prefix, $expected ) {
		$config = new GlobalConfig;
		$got = $config->get( $name, $prefix );
		$this->assertEquals( $expected, $got );
	}

	public function provideSet() {
		return array(
			array( 'Sitename', array( 'newValue1' ), 'wg' ),
			array( 'Foo', array( 'newValue2', 'newValue3' ), 'wg' ),
			array( 'Variable', 'newValue4', 'ef' ),
			array( 'BAR', null, '' ),
		);
	}

	/**
	 * @dataProvider provideSet
	 * @covers GlobalConfig::set
	 */
	public function testSet( $name, $value, $prefix ) {
		$config = new GlobalConfig;
		$return = $config->set( $name, $value, $prefix );

		$this->assertInstanceOf( 'Status', $return );
		$this->assertEquals( true, $return->isGood() );
		$this->assertEquals( $value, $GLOBALS[ $prefix . $name ] );
	}

}
