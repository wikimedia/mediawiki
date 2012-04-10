<?php

class PageQueryPageTest extends MediaWikiTestCase {

	/** Object based on PageQueryPage class */
	protected $qpage;

	function setUp() {
		$this->qpage = new PageQueryPageDummy();
	}
	function tearDown() {
		$this->qpage = null;
	}

	/**
	 * @param $expectedHTML string Formatted HTML to expect
	 * @param $ns integer namespace
	 * @param $title string The title
	 * @param $message string Optional message
	 */
	function assertQueryPageFormat( $expectedHTML, $ns, $title, $message = '') {
		// Crafts a row Title object for use with formatResult
		$row = (object) array(
			'namespace' => $ns,
			'title'     => $title,
		);

		global $wgArticlePath;
		$saved = $wgArticlePath;
		$wgArticlePath = 'w/$1';
		$HTML = $this->qpage->formatResult( 'unused $skin parameter', $row );
		$wgArticlePath = $saved;

		$this->assertEquals( $expectedHTML, $HTML, $message );
	}

	function testFormattingAnExistingArticle() {
		$this->assertQueryPageFormat(
			'<a href="w/Main_page" title="Main page">Main page</a>',
			NS_MAIN, 'Main_page',
			'Formatting of [[Main_page]]'
		);
	}

	function testFormattingMissingArticle() {
		$this->assertQueryPageFormat(
			'<a href="w/IDoNotExist4321" title="IDoNotExist4321">IDoNotExist4321</a>',
			255, 'IDoNotExist4321'
		);
	}

	function testFormattingIncorrectTitle() {
		$this->assertQueryPageFormat(
			'<!-- ERROR -->',
			NS_TALK, ''
		);
	}
}

/**
 * Let us create an instance of PageQueryPage
 */
class PageQueryPageDummy extends PageQueryPage {
}
