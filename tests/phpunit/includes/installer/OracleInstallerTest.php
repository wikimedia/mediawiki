<?php

/**
 * Tests for OracleInstaller
 * 
 * @group Database
 * @group Installer
 */

class OracleInstallerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();
	}

	protected function tearDown() {
		parent::tearDown();
	}

	function testCheckConnectStringFormat() {
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'simple_01' ) , 'Simple TNS name' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'simple_01.world' ) , 'TNS name with domain' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'simple_01.domain.net' ) , 'TNS name with domain' );

		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host123' ) , 'Host only' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host123.domain.net' ) , 'Host only' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( '//host123.domain.net' ) , 'Host only URL' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( '123.223.213.132' ) , 'Host IP only' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host:1521' ) , 'Host and port' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host:1521/service' ) , 'Host, port and service' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host:1521/service:shared' ) , 'Host, port, service and server type' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host:1521/service:shared/instance1' ) , 'Host, port, service, server type and instance' );
		$this->assertTrue( OracleInstaller::checkConnectStringFormat( 'host:1521//instance1' ) , 'Host, port and instance' );
	}

}
