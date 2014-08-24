<?php
/**
 * @group Database
 */

class SpecialMIMESearchTest extends MediaWikiTestCase {

	/** @var MIMEsearchPage */
	private $page;

	function setUp() {
		$this->page = new MIMEsearchPage;
		$context = new RequestContext();
		$context->setTitle( Title::makeTitle( NS_SPECIAL, 'MIMESearch' ) );
		$context->setRequest( new FauxRequest() );
		$this->page->setContext( $context );

		parent::setUp();
	}

	/**
	 * @dataProvider providerMimeFiltering
	 * @param string $par Subpage for special page
	 * @param string $major Major MIME type we expect to look for
	 * @param string $minor Minor MIME type we expect to look for
	 */
	function testMimeFiltering( $par, $major, $minor ) {
		$this->page->run( $par );
		$qi = $this->page->getQueryInfo();
		$this->assertEquals( $qi['conds']['img_major_mime'], $major );
		if ( $minor !== null ) {
			$this->assertEquals( $qi['conds']['img_minor_mime'], $minor );
		} else {
			$this->assertArrayNotHasKey( 'img_minor_mime', $qi['conds'] );
		}
		$this->assertContains( 'image', $qi['tables'] );
	}

	function providerMimeFiltering() {
		return array(
			array( 'image/gif', 'image', 'gif' ),
			array( 'image/png', 'image', 'png' ),
			array( 'application/pdf', 'application', 'pdf' ),
			array( 'image/*', 'image', null ),
			array( 'multipart/*', 'multipart', null ),
		);
	}
}
