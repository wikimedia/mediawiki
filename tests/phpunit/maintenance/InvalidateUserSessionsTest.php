<?php

namespace MediaWiki\Tests\Maintenance;

use InvalidateUserSessions;
use MediaWiki\Session\SessionManager;
use RuntimeException;

/**
 * @covers \InvalidateUserSessions
 * @group Database
 * @author Dreamy Jazz
 */
class InvalidateUserSessionsTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return InvalidateUserSessions::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError( $options, $expectedOutputRegex ) {
		$this->expectCallToFatalError();
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecuteForFatalError() {
		return [
			'No options provided' => [ [], '/Either --user or --file is required/' ],
			'Both user and file provided' => [
				[ 'user' => 'test', 'file' => 'test' ], '/Cannot use both --user and --file/',
			],
			'Filename is not valid' => [
				[ 'file' => '/test/invalidpath/testing' ],
				'/Could not open ' . preg_quote( '/test/invalidpath/testing', '/' ) . '/',
			],
		];
	}

	/** @dataProvider provideExecute */
	public function testExecute( $options, $expectedUsernames, $expectedOutputString ) {
		// Mock the SessionManager service to expect calls to ::invalidateSessionsForUser
		$mockSessionManager = $this->createMock( SessionManager::class );
		$mockSessionManager->expects( $this->exactly( count( $expectedUsernames ) ) )
			->method( 'invalidateSessionsForUser' )
			->willReturnCallback( function ( $actualUser ) use ( $expectedUsernames ) {
				$this->assertContains( $actualUser->getName(), $expectedUsernames );
			} );
		$this->setService( 'SessionManager', $mockSessionManager );
		// Run the maintenance script
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideExecute() {
		return [
			'User argument for non-existing user' => [
				[ 'user' => 'Non-existing test user' ],
				[ 'Non-existing test user' ],
				"Could not find user Non-existing test user, tried to invalidate anyway\n",
			],
		];
	}

	public function testExecuteForFileOfUsernames() {
		$testUser1 = $this->getTestUser()->getUserIdentity();
		$testUser2 = $this->getTestSysop()->getUserIdentity();
		$testFilename = $this->getNewTempFile();
		file_put_contents( $testFilename, "Non-existing test user\n$testUser1\n$testUser2" );
		$this->testExecute(
			[ 'file' => $testFilename, 'batch-size' => 1 ],
			[ 'Non-existing test user', $testUser1->getName(), $testUser2->getName() ],
			"Could not find user Non-existing test user, tried to invalidate anyway\n" .
			"Invalidated sessions for user $testUser1\nInvalidated sessions for user $testUser2\n",
		);
	}

	public function testExecuteForThrownException() {
		// Mock the SessionManager service to throw an error when ::invalidateSessionsForUser is called.
		$mockSessionManager = $this->createMock( SessionManager::class );
		$mockSessionManager->method( 'invalidateSessionsForUser' )
			->willThrowException( new RuntimeException( "Testing\nTest" ) );
		$this->setService( 'SessionManager', $mockSessionManager );
		// Run the maintenance script
		$this->maintenance->setOption( 'user', 'Testing' );
		$this->maintenance->execute();
		$this->expectOutputString( "Failed to invalidate sessions for user Testing | Testing Test\n" );
	}
}
