<?php

class RequestContextTest extends MediaWikiTestCase {

	/**
	 * Test the relationship between title and wikipage in RequestContext
	 */
	public function testWikiPageTitle() {
		$context = new RequestContext();

		$curTitle = Title::newFromText( "A" );
		$context->setTitle( $curTitle );
		$this->assertTrue( $curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is first set WikiPage should be created on-demand for that title." );

		$curTitle = Title::newFromText( "B" );
		$context->setWikiPage( WikiPage::factory( $curTitle ) );
		$this->assertTrue( $curTitle->equals( $context->getTitle() ),
			"Title must be updated when a new WikiPage is provided." );

		$curTitle = Title::newFromText( "C" );
		$context->setTitle( $curTitle );
		$this->assertTrue( $curTitle->equals( $context->getWikiPage()->getTitle() ),
			"When a title is updated the WikiPage should be purged and recreated on-demand with the new title." );

	}

}
