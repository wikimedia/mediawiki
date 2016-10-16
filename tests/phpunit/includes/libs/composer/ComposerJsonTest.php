<?php

class ComposerJsonTest extends MediaWikiTestCase {

	private $json, $json2;

	public function setUp() {
		parent::setUp();
		global $IP;
		$this->json = "$IP/tests/phpunit/data/composer/composer.json";
		$this->json2 = "$IP/tests/phpunit/data/composer/new-composer.json";
	}

	/**
	 * @covers ComposerJson::__construct
	 * @covers ComposerJson::getRequiredDependencies
	 */
	public function testGetRequiredDependencies() {
		$json = new ComposerJson( $this->json );
		$this->assertArrayEquals( [
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		], $json->getRequiredDependencies(), false, true );
	}

	public static function provideNormalizeVersion() {
		return [
			[ 'v1.0.0', '1.0.0' ],
			[ '0.0.5', '0.0.5' ],
		];
	}

	/**
	 * @dataProvider provideNormalizeVersion
	 * @covers ComposerJson::normalizeVersion
	 */
	public function testNormalizeVersion( $input, $expected ) {
		$this->assertEquals( $expected, ComposerJson::normalizeVersion( $input ) );
	}
}
