<?php

/**
 * @coversNothing
 */
class ServiceWiringTest extends MediaWikiTestCase {
	public function testServicesAreSorted() {
		global $IP;
		$services = array_keys( require "$IP/includes/ServiceWiring.php" );
		$sortedServices = $services;
		sort( $sortedServices );

		$this->assertSame( $sortedServices, $services,
			'Please keep services sorted alphabetically' );
	}
}
