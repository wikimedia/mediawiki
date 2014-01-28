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
		if ( isset( $params[1] ) ) {
			$out = $this->config->getWithPrefix( $params[0], $params[1] );
		} else {
			$out = $this->config->get( $params[0] );
		}

		$this->assertEquals( $rand, $out );
		if ( $old ) {
			$GLOBALS[$name] = $old;
		}
	}
}
