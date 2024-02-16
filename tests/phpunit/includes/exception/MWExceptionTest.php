<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MWException
 * @author Antoine Musso
 */
class MWExceptionTest extends MediaWikiIntegrationTestCase {

	public function testMwexceptionThrowing() {
		$this->expectException( MWException::class );
		throw new MWException();
	}

	/**
	 * @dataProvider provideTextUseOutputPage
	 */
	public function testUseOutputPage( $expected, $langObj, $fullyInitialised, $outputPage ) {
		if ( $langObj !== null ) {
			$this->setUserLang( $langObj );
		} else {
			// Reset the global to unset
			$this->setMwGlobals( 'wgLang', $langObj );
		}
		$this->setMwGlobals( [
			'wgFullyInitialised' => $fullyInitialised,
			'wgOut' => $outputPage,
		] );

		$e = TestingAccessWrapper::newFromObject( new MWException() );
		$this->assertEquals( $expected, $e->useOutputPage() );
	}

	public function provideTextUseOutputPage() {
		return [
			// expected, langObj, wgFullyInitialised, wgOut
			[ false, null, null, null ],
			[ false, $this->createMock( Language::class ), null, null ],
			[ false, $this->createMock( Language::class ), true, null ],
			[ false, null, true, null ],
			[ false, null, null, true ],
			[ true, $this->createMock( Language::class ), true, true ],
		];
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
		$this->setMwGlobals( 'wgDebugLogGroups', [] );

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

	/**
	 * @covers \MWException::report
	 */
	public function testReportDeprecated() {
		// Turn off to keep mw-error.log file empty in CI (and thus avoid build failure)
		$this->setMwGlobals( 'wgDebugLogGroups', [] );

		global $wgOut;
		$wgOut->disable();

		$e = new class( 'Uh oh!' ) extends MWException {
			public function getHTML() {
				throw new LogicException( 'This should not be called' );
			}

			public function getText() {
				return 'Oh no! ' . $this->getPageTitle();
			}
		};

		$this->expectDeprecationAndContinue( '/overrides getHTML which was deprecated/' );
		$this->expectDeprecationAndContinue( '/overrides getText which was deprecated/' );
		$this->expectDeprecationAndContinue( '/Use of MWException::getPageTitle was deprecated/' );

		ob_start();
		MWExceptionHandler::handleException( $e );
		ob_end_clean();

		$this->assertStringContainsString( 'Oh no!', $wgOut->getHTML() );
	}

}
