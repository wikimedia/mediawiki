<?php
/**
 * Unit tests for wfReportTime()
 */

class wfReportTimeTest extends MediaWikiTestCase {

	private $hostname;
	private $instancename;
	private $globals;

	function setUp() {
		$this->hostname = wfHostname();
		$this->instancename = 'TEST-INSTANCE-NAME';

		// Save globals
		global $wgShowHostnames, $wgShowInstanceName, $wgInstanceName;
		$this->globals['wgShowHostnames']    = $wgShowHostnames;
		$this->globals['wgShowInstanceName'] = $wgShowInstanceName;
		$this->globals['wgInstanceName']     = $wgInstanceName;

		$wgInstanceName = $this->instancename;
	}

	function tearDown() {

		// Restore globals
		global $wgShowHostnames, $wgShowInstanceName, $wgInstanceName;
		$wgShowHostnames    = $this->globals['wgShowHostnames'];
		$wgShowInstanceName = $this->globals['wgShowInstanceName'];
		$wgInstanceName     = $this->globals['wgInstanceName'];
	}


	function testShowHostnameAndInstanceName() {
		$this->showHostnames();
		$this->showInstanceName();

		$this->assertRegExp(
			sprintf( '/<!-- Served by %s \(%s\) in \d+\.\d+ secs. -->/'
				, $this->hostname
				, $this->instancename
			),
			wfReportTime()
		);
	}
	function testShowHostnameOnly() {
		$this->showHostnames();
		$this->hideInstanceName();

		$this->assertRegExp(
			sprintf( '/<!-- Served by %s in \d+\.\d+ secs. -->/'
				, $this->hostname
			),
			wfReportTime()
		);
	}
	function testShowInstanceNameOnly() {
		$this->hideHostnames();
		$this->showInstanceName();

		$this->assertRegExp(
			sprintf( '/<!-- Served by %s in \d+\.\d+ secs. -->/'
				, $this->instancename
			),
			wfReportTime()
		);
	}
	function testShowTimeOnly() {
		$this->hideHostnames();
		$this->hideInstanceName();

		$this->assertRegExp(
			sprintf( '/<!-- Served in \d+\.\d+ secs. -->/'
			),
			wfReportTime()
		);
	}


	/** helpers */

	function showHostnames() {
		global $wgShowHostnames;
		$wgShowHostnames = true;
		return $this;
	}
	function hideHostnames() {
		global $wgShowHostnames;
		$wgShowHostnames = false;
		return $this;
	}
	function showInstanceName() {
		global $wgShowInstanceName;
		$wgShowInstanceName = true;
		return $this;
	}
	function hideInstanceName() {
		global $wgShowInstanceName;
		$wgShowInstanceName = false;
		return $this;
	}
}
