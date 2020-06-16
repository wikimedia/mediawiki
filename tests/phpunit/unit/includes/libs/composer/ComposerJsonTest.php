<?php

class ComposerJsonTest extends PHPUnit\Framework\TestCase {

	private $json, $json2;

	protected function setUp() : void {
		parent::setUp();
		$this->json = __DIR__ . "/../../../../data/composer/composer.json";
		$this->json2 = __DIR__ . "/../../../../data/composer/new-composer.json";
	}

	/**
	 * @covers ComposerJson::__construct
	 * @covers ComposerJson::getRequiredDependencies
	 */
	public function testGetRequiredDependencies() {
		$json = new ComposerJson( $this->json );
		$this->assertEquals( [
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		], $json->getRequiredDependencies() );
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
