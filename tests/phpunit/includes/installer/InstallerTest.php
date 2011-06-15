<?php

class Installer_TestHelper extends Installer {
	function showMessage( $msg ) {}
	function showError( $msg ) {}
	function showStatusMessage( Status $status ) {}

	function __construct() {
		$this->settings = array();
	}

}

class InstallerTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideEnvCheckServer
	 */
	function testEnvCheckServer( $expected, $input, $description ) {
		$installer = new Installer_TestHelper;
		$oldServer = $_SERVER;
		$_SERVER = $input;
		$rm = new ReflectionMethod( 'Installer_TestHelper', 'envCheckServer' );
		if( !method_exists( $rm, 'setAccessible' ) ) {
			$this->markTestIncomplete( "Test requires PHP 5.3.2 or above for ReflectionMethod::setAccessible" );
		} else {
			$rm->setAccessible( true );
			$rm->invoke( $installer );
			$_SERVER = $oldServer;
			$this->assertEquals( $expected, $installer->getVar( 'wgServer' ), $description );
		}
	}

	function provideEnvCheckServer() {
		return array(
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x'
				),
				'Host header'
			),
			array(
				'https://x',
				array(
					'HTTP_HOST' => 'x',
					'HTTPS' => 'on',
				),
				'Host header with secure'
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'SERVER_PORT' => 80,
				),
				'Default SERVER_PORT',
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'HTTPS' => 'off',
				),
				'Secure off'
			),
			array(
				'http://y',
				array(
					'SERVER_NAME' => 'y',
				),
				'Server name'
			),
			array(
				'http://x',
				array(
					'HTTP_HOST' => 'x',
					'SERVER_NAME' => 'y',
				),
				'Host server name precedence'
			),
			array(
				'http://[::1]:81',
				array(
					'HTTP_HOST' => '[::1]',
					'SERVER_NAME' => '::1',
					'SERVER_PORT' => '81',
				),
				'Apache bug 26005'
			),
			array(
				'http://localhost',
				array(
					'SERVER_NAME' => '[2001'
				),
				'Kind of like lighttpd per commit message in MW r83847',
			),
			array(
				'http://[2a01:e35:2eb4:1::2]:777',
				array(
					'SERVER_NAME' => '[2a01:e35:2eb4:1::2]:777'
				),
				'Possible lighttpd environment per bug 14977 comment 13',
			),
		);
	}
}
