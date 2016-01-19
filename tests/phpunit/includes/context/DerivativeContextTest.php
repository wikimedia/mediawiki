<?php

class DerivativeContextTest extends MediaWikiTestCase {

	public function testContextTitle() {
		// the title used for the RequestContext object
		$title = Title::newFromText( 'TestTitle' );
		// the Title used for the DerivativeContext object
		$newTitle = Title::newFromText( 'TestTitle2' );

		$context = new RequestContext();
		$context->setTitle( $title );

		$derivativeContext = new DerivativeContext( $context );
		// check, if both WikiPage object are constructed with the same Title object
		$this->assertEquals(
			$derivativeContext->getWikiPage()->getTitle()->getText(),
			$context->getWikiPage()->getTitle()->getText(),
			'The WikiPage returned by DerivativeContext::getWikiPage() is the same' .
			'as the one returned by RequestContext::getWikiPage().'
		);

		// set the own Title to the DerivativeContext
		$derivativeContext->setTitle( $newTitle );

		$this->assertEquals(
			$derivativeContext->getWikiPage()->getTitle()->getText(),
			'TestTitle2',
			'The WikiPage returned by DerivativeContext::getWikiPage() is constructed using' .
			'the title set by DerivativeContext::setTitle().'
		);
		$this->assertNotEquals(
			$derivativeContext->getWikiPage()->getTitle()->getText(),
			$context->getWikiPage()->getTitle()->getText(),
			'The WikiPage returned by DerivativeContext::getWikiPage() isn\'t the same' .
			'as the one returned by RequestContext::getWikiPage().'
		);
	}
}
