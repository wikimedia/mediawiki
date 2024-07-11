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

	/**
	 * Verify the exception classes are JSON serializabe.
	 *
	 * @dataProvider provideExceptionClasses
	 */
	public function testJsonSerializeExceptions( $exception_class ) {
		$json = MWExceptionHandler::jsonSerializeException(
			new $exception_class()
		);
		$this->assertIsString( $json,
			"The $exception_class exception should be JSON serializable, got false." );
	}

	public static function provideExceptionClasses() {
		return [
			[ Exception::class ],
			[ MWException::class ],
		];
	}

	/**
	 * @covers \MWException::report
	 */
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
