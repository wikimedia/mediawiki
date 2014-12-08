<?php

class ComposerLockComparerTest extends MediaWikiTestCase {

	private $json, $lock, $newjson;

	public function setUp() {
		parent::setUp();
		global $IP;
		$this->lock = "$IP/tests/phpunit/data/composer/composer.lock";
		$this->json = "$IP/tests/phpunit/data/composer/composer.json";
		$this->newjson = "$IP/tests/phpunit/data/composer/new-composer.json";
	}

	public static function provideIsHashUpToDate() {
		return array(
			array( 'json', true ),
			array( 'newjson', false ),
		);
	}

	/**
	 * @dataProvider provideIsHashUpToDate
	 * @covers ComposerLockComparer::isHashUpToDate
	 */
	public function testIsHashUpToDate( $file, $expected ) {
		$comparer = new ComposerLockComparer( $this->$file, $this->lock );
		$this->assertEquals( $expected, $comparer->isHashUpToDate() );
	}

	/**
	 * @covers ComposerLockComparer::getInstalledDependencies
	 */
	public function testGetInstalledDependencies() {
		$comparer = new ComposerLockComparer( $this->json, $this->lock );
		$this->assertArrayEquals( array(
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		), $comparer->getInstalledDependencies(), false, true );
	}

	/**
	 * @covers ComposerLockComparer::getRequiredDependencies
	 */
	public function testGetRequiredDependencies() {
		$comparer = new ComposerLockComparer( $this->json, $this->lock );
		$this->assertArrayEquals( array(
			'cdb/cdb' => '1.0.0',
			'cssjanus/cssjanus' => '1.1.1',
			'leafo/lessphp' => '0.5.0',
			'psr/log' => '1.0.0',
		), $comparer->getRequiredDependencies(), false, true );
	}
}
