<?php
/**
 * Test for FakeTitle.
 */
class FakeTitleTest extends MediaWikiTestCase {

	/**
	 * @expectedException MWException
	 */
	function testCallingTitleMethodThrowException() {
		$f = new FakeTitle();
		$f->getText();
	}

	/**
	 * @expectedException MWException
	 */
	function testCallingTitleStaticMethodThrowException() {
		FakeTitle::newMainPage();
	}

}
