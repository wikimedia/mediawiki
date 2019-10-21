<?php

/**
 * @coversNothing
 */
class ServiceWiringTest extends \MediaWikiUnitTestCase {
	public function testServicesAreSorted() {
		global $IP;
		$services = array_keys( require "$IP/includes/ServiceWiring.php" );
		$sortedServices = $services;
		natcasesort( $sortedServices );

		$this->assertSame( $sortedServices, $services,
			'Please keep services sorted alphabetically' );
	}
}
