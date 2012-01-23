<?php

class FileRepoTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeNull() {
		$f = new FileRepo();
	}
	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionCanNotBeAnEmptyArray() {
		$f = new FileRepo( array() );
	}
	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedNameKey() {
		$f = new FileRepo( array(
			'backend' => 'foobar'
		) );
	}
	/**
	 * @expectedException MWException
	 */
	function testFileRepoConstructionOptionNeedBackendKey() {
		$f = new FileRepo( array(
			'name' => 'foobar'
		) );
	}

}
