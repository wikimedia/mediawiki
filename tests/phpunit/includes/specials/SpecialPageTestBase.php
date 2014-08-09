<?php

/**
 * Base class for testing special pages.
 *
 * @since 1.24
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Kinzler
 * @author Adam Shorland
 */
abstract class SpecialPageTestBase extends MediaWikiTestCase {

	protected $obLevel;

	public function setUp() {
		parent::setUp();

		$this->obLevel = ob_get_level();
	}

	public function tearDown() {
		$obLevel = ob_get_level();

		while ( ob_get_level() > $this->obLevel ) {
			ob_end_clean();
		}

		if ( $obLevel !== $this->obLevel ) {
			$this->fail( "Test changed output buffer level: was {$this->obLevel} before test, but $obLevel after test.");
		}

		parent::tearDown();
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected abstract function newSpecialPage();

	/**
	 * @param string $sub The subpage parameter to call the page with
	 * @param WebRequest|null $request Web request that may contain URL parameters, etc
	 * @param string|null $language The language code which should be used in the context of this special page
	 *
	 * @throws Exception|null
	 * @return array array( String, \WebResponse ) containing the output generated
	 *         by the special page.
	 */
	protected function executeSpecialPage( $sub = '', WebRequest $request = null, $language = null ) {
		if ( $request === null ) {
			$request = new \FauxRequest();
		}

		$response = $request->response();

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		if ( $language !== null ) {
			$context->setLanguage( $language );
		}

		$out = new OutputPage( $context );
		$context->setOutput( $out );

		$page = $this->newSpecialPage();
		$page->setContext( $context );

		$out->setTitle( $page->getPageTitle() );

		ob_start();

		$exception = null;
		try {
			$page->execute( $sub );

			if ( $out->getRedirect() !== '' ) {
				$out->output();
				$text = ob_get_contents();
			} elseif ( $out->isDisabled() ) {
				$text = ob_get_contents();
			} else {
				$text = $out->getHTML();
			}
		} catch ( Exception $ex ) {
			// PHP 5.3 doesn't have `finally`
			$exception = $ex;
		}

		// poor man's `finally` block
		ob_end_clean();

		// re-throw any errors after `finally` handling.
		if ( $exception ) {
			throw $exception;
		}

		$code = $response->getStatusCode();

		if ( $code > 0 ) {
			$response->header( "Status: " . $code . ' ' . HttpStatus::getMessage( $code ) );
		}

		return array( $text, $response );
	}

}
