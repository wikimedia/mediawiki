<?php

/**
 * Copy of CentralAuth's CentralAuthServiceWiringTest.php
 * used to test the ServiceWiring.php file.
 */

namespace MediaWiki\Skins\Vector\Tests\Integration;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;

/**
 * Partly Tests ServiceWiring.php by ensuring that the call to the
 * service does not result in an error.
 *
 * @coversNothing PHPUnit does not support covering annotations for files
 * @group Vector
 */
class ServiceWiringTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideService
	 */
	public function testService( string $name ) {
		MediaWikiServices::getInstance()->get( $name );
		$this->addToAssertionCount( 1 );
	}

	public static function provideService() {
		$wiring = require __DIR__ . '/../../../includes/ServiceWiring.php';
		foreach ( $wiring as $name => $_ ) {
			yield $name => [ $name ];
		}
	}
}
