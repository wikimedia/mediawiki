<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @author Antoine Musso
 * @copyright Copyright © 2013, Antoine Musso
 * @copyright Copyright © 2013, Wikimedia Foundation Inc.
 * @file
 */

class MWExceptionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers MWException
	 */
	public function testMwexceptionThrowing() {
		$this->expectException( MWException::class );
		throw new MWException();
	}

	/**
	 * @dataProvider provideTextUseOutputPage
	 * @covers MWException::useOutputPage
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

	/**
	 * @covers MWException::useMessageCache
	 */
	public function testUseMessageCache() {
		$e = new MWException();
		$this->assertTrue( $e->useMessageCache() );
	}

	/**
	 * @covers MWException::isLoggable
	 */
	public function testIsLogable() {
		$e = new MWException();
		$this->assertTrue( $e->isLoggable() );
	}

	/**
	 * @dataProvider provideIsCommandLine
	 * @covers MWException::isCommandLine
	 */
	public function testisCommandLine( $expected, $commandLineMode ) {
		$this->setMwGlobals( [
			'wgCommandLineMode' => $commandLineMode,
		] );
		$e = new MWException();
		$this->assertEquals( $expected, $e->isCommandLine() );
	}

	public static function provideIsCommandLine() {
		return [
			[ false, null ],
			[ true, true ],
		];
	}

	/**
	 * Verify the exception classes are JSON serializabe.
	 *
	 * @covers MWExceptionHandler::jsonSerializeException
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

}
