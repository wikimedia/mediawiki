<?php

class GlobalConfigTest extends MediaWikiTestCase {

	/** @var GlobalConfig $config */
	protected $config;

	protected function setUp() {
		parent::setUp();
		$this->config = new GlobalConfig;
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
	 * @covers GlobalConfig::get
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
