<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use MediaWiki\Settings\SettingsBuilderException;
use MediaWiki\Settings\Source\FileSource;
use MediaWiki\Settings\Source\Format\JsonFormat;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\FileSource
 */
class FileSourceTest extends TestCase {
	public function testAllowsStaleLoad() {
		$source = new FileSource( __DIR__ . 'foo.json' );
		$this->assertFalse( $source->allowsStaleLoad() );
	}

	public function testLoad() {
		$source = new FileSource( __DIR__ . '/fixtures/settings.json' );

		$this->assertSame(
			[ 'config' => [ 'MySetting' => 'BlaBla' ] ],
			$source->load()
		);
	}

	public function testLoadFormat() {
		$source = new FileSource( __DIR__ . '/fixtures/settings.json', new JsonFormat() );

		$this->assertSame(
			[ 'config' => [ 'MySetting' => 'BlaBla' ] ],
			$source->load()
		);
	}

	public function testLoadBadFormat() {
		$source = new FileSource( __DIR__ . '/fixtures/bad.txt', new JsonFormat() );

		$this->expectException( SettingsBuilderException::class );

		$settings = $source->load();
	}

	public function testLoadDirectory() {
		$source = new FileSource( __DIR__ . '/fixtures/dir.json' );

		$this->expectException( SettingsBuilderException::class );

		$settings = $source->load();
	}

	public function testLoadNoSuitableFormats() {
		$source = new FileSource( __DIR__ . '/fixtures/settings.toml', new JsonFormat() );

		$this->expectException( SettingsBuilderException::class );

		$settings = $source->load();
	}

	public function testGetHashKey() {
		$source = new FileSource( __DIR__ . '/fixtures/settings.json' );

		// We can't reliably mock the filesystem stat so simply ensure the
		// method returns and is non-zero in length
		$this->assertNotEmpty( $source->getHashKey() );
	}
}
