<?php

namespace Wikimedia\Tests\Composer;

use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Composer\ComposerJson;

/**
 * @covers \Wikimedia\Composer\ComposerJson
 */
class ComposerJsonTest extends TestCase {
	use MediaWikiCoversValidator;

	private const JSON_FILE = __DIR__ . '/../../../../data/composer/composer.json';

	public function testGetRequiredDependencies() {
		$json = new ComposerJson( self::JSON_FILE );
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
	 */
	public function testNormalizeVersion( $input, $expected ) {
		$this->assertEquals( $expected, ComposerJson::normalizeVersion( $input ) );
	}
}
