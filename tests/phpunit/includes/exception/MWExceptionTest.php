<?php

use MediaWiki\MainConfigNames;

/**
 * @covers \MWException
 * @author Antoine Musso
 */
class MWExceptionTest extends MediaWikiIntegrationTestCase {

	public function testMwexceptionThrowing() {
		$this->expectException( MWException::class );
		throw new MWException();
	}

	public function testUseMessageCache() {
		$e = new MWException();
		$this->assertTrue( $e->useMessageCache() );
	}

	public function testIsLoggable() {
		$e = new MWException();
		$this->assertTrue( $e->isLoggable() );
	}

	public function testReport() {
		// Turn off to keep mw-error.log file empty in CI (and thus avoid build failure)
		$this->overrideConfigValue( MainConfigNames::DebugLogGroups, [] );

		global $wgOut;
		$wgOut->disable();

		$e = new class( 'Uh oh!' ) extends MWException {
			public function report() {
				global $wgOut;
				$wgOut->addHTML( 'Oh no!' );
			}
		};

		MWExceptionHandler::handleException( $e );

		$this->assertStringContainsString( 'Oh no!', $wgOut->getHTML() );
	}

}
