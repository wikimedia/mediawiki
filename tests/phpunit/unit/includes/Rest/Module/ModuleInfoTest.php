<?php

namespace MediaWiki\Tests\Rest\Module;

use MediaWiki\Rest\Module\ModuleInfo;
use MediaWiki\Rest\Module\ModuleMode;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Rest\Module\ModuleInfo
 */
class ModuleInfoTest extends MediaWikiUnitTestCase {

	/**
	 * Test that all getter methods return the values passed into the constructor.
	 */
	public function testGetters(): void {
		$info = new ModuleInfo(
			'test/v1',
			ModuleMode::PUBLISHED,
			false,
			'Test Module',
			'A module description',
			'1.0.0',
			[ 'beta' ],
			'https://example.com/base',
			'https://example.com/spec'
		);

		$this->assertSame( 'test/v1', $info->getId() );
		$this->assertSame( ModuleMode::PUBLISHED, $info->getAvailability() );
		$this->assertFalse( $info->isExternal() );
		$this->assertSame( 'Test Module', $info->getTitle() );
		$this->assertSame( 'A module description', $info->getDescription() );
		$this->assertSame( '1.0.0', $info->getVersion() );
		$this->assertSame( [ 'beta' ], $info->getGroups() );
		$this->assertSame( 'https://example.com/base', $info->getExternalBaseUrl() );
		$this->assertSame( 'https://example.com/spec', $info->getExternalSpecUrl() );
	}

	/**
	 * Test that optional properties safely return null or empty arrays when omitted.
	 */
	public function testGettersNullableDefaults(): void {
		$info = new ModuleInfo(
			'minimal/v1',
			ModuleMode::HIDDEN,
			true,
			null,
			null,
			null,
			[]
		);

		$this->assertSame( 'minimal/v1', $info->getId() );
		$this->assertSame( ModuleMode::HIDDEN, $info->getAvailability() );
		$this->assertTrue( $info->isExternal() );
		$this->assertNull( $info->getTitle() );
		$this->assertNull( $info->getDescription() );
		$this->assertNull( $info->getVersion() );
		$this->assertSame( [], $info->getGroups() );
		$this->assertNull( $info->getExternalBaseUrl() );
		$this->assertNull( $info->getExternalSpecUrl() );
	}

	/**
	 * Test that `getAvailability()` faithfully returns all `ModuleMode` enum cases.
	 *
	 * @dataProvider provideAvailabilityModes
	 */
	public function testGetAvailability( ModuleMode $mode ): void {
		$info = new ModuleInfo(
			'test/v1',
			$mode,
			false,
			null,
			null,
			null,
			[]
		);
		$this->assertSame( $mode, $info->getAvailability() );
	}

	public static function provideAvailabilityModes(): array {
		return [
			'published' => [ ModuleMode::PUBLISHED ],
			'hidden' => [ ModuleMode::HIDDEN ],
			'disabled' => [ ModuleMode::DISABLED ],
			'discoverable' => [ ModuleMode::DISCOVERABLE ],
		];
	}
}
