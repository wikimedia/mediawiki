<?php

/**
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
		return [
			[ true, 'simple_01', 'Simple TNS name' ],
			[ true, 'simple_01.world', 'TNS name with domain' ],
			[ true, 'simple_01.domain.net', 'TNS name with domain' ],
			[ true, 'host123', 'Host only' ],
			[ true, 'host123.domain.net', 'FQDN only' ],
			[ true, '//host123.domain.net', 'FQDN URL only' ],
			[ true, '123.223.213.132', 'Host IP only' ],
			[ true, 'host:1521', 'Host and port' ],
			[ true, 'host:1521/service', 'Host, port and service' ],
			[ true, 'host:1521/service:shared', 'Host, port, service and shared server type' ],
			[ true, 'host:1521/service:dedicated', 'Host, port, service and dedicated server type' ],
			[ true, 'host:1521/service:pooled', 'Host, port, service and pooled server type' ],
			[
				true,
				'host:1521/service:shared/instance1',
				'Host, port, service, server type and instance'
			],
			[ true, 'host:1521//instance1', 'Host, port and instance' ],
		];
	}

}
