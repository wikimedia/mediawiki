<?php

/**
 * Tests for OracleInstaller
 *
 * @group Database
 * @group Installer
 */

class OracleInstallerTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideOracleConnectStrings
	 * @covers OracleInstaller::checkConnectStringFormat
	 */
	public function testCheckConnectStringFormat( $expected, $connectString, $msg = '' ) {
		$validity = $expected ? 'should be valid' : 'should NOT be valid';
		$msg = "'$connectString' ($msg) $validity.";
		$this->assertEquals( $expected,
			OracleInstaller::checkConnectStringFormat( $connectString ),
			$msg
		);
	}

	/**
	 * Provider to test OracleInstaller::checkConnectStringFormat()
	 */
	function provideOracleConnectStrings() {
		// expected result, connectString[, message]
		return array(
			array( true, 'simple_01', 'Simple TNS name' ),
			array( true, 'simple_01.world', 'TNS name with domain' ),
			array( true, 'simple_01.domain.net', 'TNS name with domain' ),
			array( true, 'host123', 'Host only' ),
			array( true, 'host123.domain.net', 'FQDN only' ),
			array( true, '//host123.domain.net', 'FQDN URL only' ),
			array( true, '123.223.213.132', 'Host IP only' ),
			array( true, 'host:1521', 'Host and port' ),
			array( true, 'host:1521/service', 'Host, port and service' ),
			array( true, 'host:1521/service:shared', 'Host, port, service and shared server type' ),
			array( true, 'host:1521/service:dedicated', 'Host, port, service and dedicated server type' ),
			array( true, 'host:1521/service:pooled', 'Host, port, service and pooled server type' ),
			array( true, 'host:1521/service:shared/instance1', 'Host, port, service, server type and instance' ),
			array( true, 'host:1521//instance1', 'Host, port and instance' ),
		);
	}

}
