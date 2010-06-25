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

	function execute() {
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
		global $wgSeleniumTests, $wgOut;
		SeleniumLoader::load();

		$result = new PHPUnit_Framework_TestResult;
		$logger = new SeleniumTestHTMLLogger;
		$result->addListener( new SeleniumTestListener( $logger ) );
		$logger->setHeaders();

		// run tests
		$suite = new SeleniumTestSuite;
		foreach ( $wgSeleniumTests as $testClass ) {
			$suite->addTest( new $testClass );
		}
		$wgOut->addHTML( '<div class="selenium">' );
		$suite->run( $result );
		$wgOut->addHTML( '</div>' );
	}
}

