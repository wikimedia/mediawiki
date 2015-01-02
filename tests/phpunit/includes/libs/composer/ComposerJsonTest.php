<?php

class ComposerJsonTest extends MediaWikiTestCase {

	private $json, $json2;

	public function setUp() {
		parent::setUp();
		global $IP;
		$this->json = "$IP/tests/phpunit/data/composer/composer.json";
		$this->json2 = "$IP/tests/phpunit/data/composer/new-composer.json";
	}

	public static function provideGetHash() {
		return array(
			array( 'json', 'cc6e7fc565b246cb30b0cac103a2b31e' ),
			array( 'json2', '19921dd1fc457f1b00561da932432001' ),
		);
	}

	/**
	 * @dataProvider provideGetHash
	 * @covers ComposerJson::getHash
	 */
	public function testIsHashUpToDate( $file, $expected ) {
		$json = new ComposerJson( $this->$file );
		$this->assertEquals( $expected, $json->getHash() );
	}

	/**
	 * @covers ComposerJson::getRequiredDependencies
	 */
	public function testGetRequiredDependencies() {
		$json = new ComposerJson( $this->json );
		$this->assertArrayEquals( array(
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		), $json->getRequiredDependencies(), false, true );
	}

	public static function provideNormalizeVersion() {
		return array(
			array( 'v1.0.0', '1.0.0' ),
			array( '0.0.5', '0.0.5' ),
		);
	}

	/**
	 * @dataProvider provideNormalizeVersion
	 * @covers ComposerJson::normalizeVersion
	 */
	public function testNormalizeVersion( $input, $expected ) {
		$this->assertEquals( $expected, ComposerJson::normalizeVersion( $input ) );
	}
}
