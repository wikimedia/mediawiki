<?php

class GlobalVarConfigTest extends MediaWikiTestCase {

	/** @var GlobalVarConfig $config */
	protected $config;

	protected function setUp() {
		parent::setUp();
		$this->config = new GlobalVarConfig;
	}

	public static function provideGet() {
		return array(
			array( 'wgSitename', array( 'Sitename' ) ),
			array( 'wgFoo', array( 'Foo' ) ),
			array( 'efVariable', array( 'Variable', 'ef' ) ),
			array( 'Foo', array( 'Foo', '' ) ),
		);
	}

	/**
	 * @param string $name
	 * @param array $params
	 * @dataProvider provideGet
	 * @covers GlobalVarConfig::get
	 */
	public function testGet( $name, $params ) {
		$rand = wfRandom();
		$old = isset( $GLOBALS[$name] ) ? $GLOBALS[$name] : null;
		$GLOBALS[$name] = $rand;
		$out = call_user_func_array( array( $this->config, 'get' ), $params );
		$this->assertEquals( $rand, $out );
		if ( $old ) {
			$GLOBALS[$name] = $old;
		}
	}
}
