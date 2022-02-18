<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\PhpSettingsSource;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\PhpSettingsSource
 */
class PhpSettingsSourceTest extends TestCase {
	public function testLoad() {
		$source = new PhpSettingsSource( __DIR__ . '/fixtures/strategies.php' );
		$this->assertSame( [], $source->load() );
	}

	public function testLoadInvalidPhpSource() {
		$this->expectException( SettingsBuilderException::class );

		$source = new PhpSettingsSource( __DIR__ . '/fixtures/bad-strategies.php' );
		$source->load();
	}

	public function testLoadFileNotArray() {
		$this->expectException( SettingsBuilderException::class );

		// Let this just be an empty string.
		$source = new PhpSettingsSource( __DIR__ . '/fixtures/strategies-bad-structure.php' );
		$source->load();
	}
}
