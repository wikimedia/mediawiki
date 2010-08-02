<?php

/**
 * TODO: remove this feature
 */

class SpecialSelenium extends SpecialPage {
	function __construct() {
		parent::__construct( 'Selenium', 'selenium', false );
	}

	function getDescription() {
		return 'Selenium';
	}

	function execute( $par ) {
		global $wgUser, $wgOut, $wgEnableSelenium, $wgRequest;

		if ( !$wgEnableSelenium ) {
			throw new MWException( 
				'Selenium special page invoked when it should not be registered!' );
		}

		$this->setHeaders();
		if ( !$this->userCanExecute( $wgUser ) ) {
			$this->displayRestrictionError();
			return;
		}

		if ( $wgRequest->wasPosted() && $wgUser->matchEditToken( $wgRequest->getVal( 'token' ) ) ) {
			$this->runTests();
		}
		$wgOut->addHTML( 
			Html::openElement( 'form', array(
				'method' => 'POST',
				'action' => $this->getTitle()->getLocalUrl(),
			) ) .
			Html::input( 'submit', 'Run tests', 'submit' ) .
			Html::hidden( 'token', $wgUser->editToken() ) .
			'</form>'
		);
	}

	function runTests() {
		global $wgSeleniumTestSuites, $wgOut, $wgSeleniumLogger;
		SeleniumLoader::load();

		$result = new PHPUnit_Framework_TestResult;
		$wgSeleniumLogger = new SeleniumTestHTMLLogger;
		$result->addListener( new SeleniumTestListener( $wgSeleniumLogger ) );
		//$wgSeleniumLogger->setHeaders();

		// run tests
		$wgOut->addHTML( '<div class="selenium">' );
		
		// for some really strange reason, foreach doesn't work here. It produces an infinite loop,
		// executing only the first test suite.
		for ( $i = 0; $i < count( $wgSeleniumTestSuites ); $i++ ) {
			$suite = new $wgSeleniumTestSuites[$i];
			$suite->addTests();
			$suite->run( $result );
		}
		$wgOut->addHTML( '</div>' );
	}
}

