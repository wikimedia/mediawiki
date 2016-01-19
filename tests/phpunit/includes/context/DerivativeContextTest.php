<?php

class DerivativeContextTest extends MediaWikiTestCase {

	public function testContextTitle() {
		// the title used for the RequestContext object
		$title = Title::newFromText( 'TestTitle' );
		// the Title used for the DerivativeContext object
		$newTitle = Title::newFromText( 'TestTitle2' );

		$context = new RequestContext();
		$context->setTitle( $title );

		$this->hideDeprecated( 'DerivativeContext::getRequestContextFromOtherContext' );
		$mockContext = new MockContextSource();
		$mockDerivativeContext = new DerivativeContext( $mockContext );
		$this->assertEquals(
			get_class( $mockDerivativeContext->getContext() ),
			'RequestContext',
			'Passing a non-mutable IContextSource implementation to DerivativeContext is' .
			'automatically converted to a mutable RequestContext.'
		);

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

/**
 * Mock to test, what happens, if DerivativeContext gets not a MutableContext.
 */
class MockContextSource extends ContextSource {
	/**
	 * Return this, instead of the main request context (which would be
	 * a MutableContext).
	 *
	 * @return MockContextSource
	 */
	public function getContext() {
		return $this;
	}

	/**
	 * Get the Config object
	 *
	 * @return Config
	 */
	public function getConfig() {
		return ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
	}

	/**
	 * Get the WebRequest object
	 *
	 * @return WebRequest
	 */
	public function getRequest() {
		return new WebRequest();
	}

	/**
	 * Get the Title object
	 *
	 * @return Title|null
	 */
	public function getTitle() {
		return null;
	}

	/**
	 * Returns false.
	 *
	 * @return bool
	 */
	public function canUseWikiPage() {
		return false;
	}

	/**
	 * Returns null
	 *
	 * @return WikiPage
	 */
	public function getWikiPage() {
		return new WikiPage();
	}

	/**
	 * Get the OutputPage object
	 *
	 * @return OutputPage
	 */
	public function getOutput() {
		return new OutputPage( $this );
	}

	/**
	 * Get the User object
	 *
	 * @return User
	 */
	public function getUser() {
		return User::newFromName( 'UTSysOp' );
	}

	/**
	 * Get the Language object
	 *
	 * @return Language
	 */
	public function getLanguage() {
		return new Language();
	}

	/**
	 * Get the Skin object
	 *
	 * @return Skin
	 */
	public function getSkin() {
		return new SkinTemplate();
	}
}
