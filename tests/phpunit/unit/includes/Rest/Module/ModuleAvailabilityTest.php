<?php

namespace MediaWiki\Tests\Rest\Module;

use MediaWiki\Rest\Module\AudienceDesignation;
use MediaWiki\Rest\Module\ModuleAvailability;

/**
 * @covers \MediaWiki\Rest\Module\ModuleAvailability
 */
class ModuleAvailabilityTest extends \MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideGetModuleAvailabilityCases
	 */
	public function testGetModuleAvailability( ?AudienceDesignation $ad, ModuleAvailability $expected ): void {
		$is = ModuleAvailability::getModuleAvailability( $ad );

		// The default message is unhelpful in identifying which test case failed
		$msg = "Failure for audience designation " . ( $ad?->name ?? 'null' );
		$this->assertSame( $expected, $is, $msg );
	}

	public static function provideGetModuleAvailabilityCases() {
		yield from [
			[ AudienceDesignation::PUBLIC, ModuleAvailability::PUBLISHED ],
			[ AudienceDesignation::INTERNAL, ModuleAvailability::PUBLISHED ],
			[ AudienceDesignation::BETA, ModuleAvailability::PUBLISHED ],
			[ AudienceDesignation::NONE, ModuleAvailability::PUBLISHED ],
			[ null, ModuleAvailability::DISABLED ],
		];
	}
}
