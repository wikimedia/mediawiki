<?php

namespace MediaWiki\Tests\Rest\Module;

use MediaWiki\Rest\Module\ModuleAvailability;
use MediaWiki\Rest\Module\ModuleInfo;
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
			ModuleAvailability::PUBLISHED,
			false,
			'Test Module',
			'A module description',
			'1.0.0',
			'/openapi.json',
			[ 'beta' ],
			'https://example.com/base',
			'https://example.com/spec'
		);

		$this->assertSame( 'test/v1', $info->getId() );
		$this->assertSame( ModuleAvailability::PUBLISHED, $info->getAvailability() );
		$this->assertFalse( $info->isExternal() );
		$this->assertSame( 'Test Module', $info->getTitle() );
		$this->assertSame( 'A module description', $info->getDescription() );
		$this->assertSame( '1.0.0', $info->getVersion() );
		$this->assertSame( [ 'beta' ], $info->getGroups() );
		$this->assertSame( 'https://example.com/base', $info->getExternalBaseUrl() );
		$this->assertSame( 'https://example.com/spec', $info->getExternalSpecUrl() );
		$this->assertSame( '/openapi.json', $info->getLocalDescriptionSpecPath() );
	}

	/**
	 * Test that optional properties safely return null or empty arrays when omitted.
	 */
	public function testGettersNullableDefaults(): void {
		$info = new ModuleInfo(
			'minimal/v1',
			ModuleAvailability::HIDDEN,
			true,
			null,
			null,
			null,
			null,
			[]
		);

		$this->assertSame( 'minimal/v1', $info->getId() );
		$this->assertSame( ModuleAvailability::HIDDEN, $info->getAvailability() );
		$this->assertTrue( $info->isExternal() );
		$this->assertNull( $info->getTitle() );
		$this->assertNull( $info->getDescription() );
		$this->assertNull( $info->getVersion() );
		$this->assertSame( [], $info->getGroups() );
		$this->assertNull( $info->getExternalBaseUrl() );
		$this->assertNull( $info->getExternalSpecUrl() );
		$this->assertNull( $info->getLocalDescriptionSpecPath() );
	}

	/**
	 * Test that `getAvailability()` faithfully returns all `ModuleAvailability` enum cases.
	 *
	 * @dataProvider provideAvailabilityModes
	 */
	public function testGetAvailability( ModuleAvailability $availability ): void {
		$info = new ModuleInfo(
			'test/v1',
			$availability,
			false,
			null,
			null,
			null,
			null,
			[]
		);
		$this->assertSame( $availability, $info->getAvailability() );
	}

	public static function provideAvailabilityModes(): array {
		return [
			'published' => [ ModuleAvailability::PUBLISHED ],
			'hidden' => [ ModuleAvailability::HIDDEN ],
			'disabled' => [ ModuleAvailability::DISABLED ],
			'discoverable' => [ ModuleAvailability::DISCOVERABLE ],
		];
	}
}
