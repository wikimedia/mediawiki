<?php

namespace MediaWiki\Tests\Maintenance;

use FindDeprecated;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \FindDeprecated
 * @covers \DeprecatedInterfaceFinder
 * @covers \FileAwareNodeVisitor
 * @author Dreamy Jazz
 */
class FindDeprecatedTest extends MaintenanceBaseTestCase {

	private const FIXTURE_DIRECTORY = MW_INSTALL_PATH . '/tests/phpunit/data/FindDeprecated';

	protected function getMaintenanceClass() {
		return FindDeprecated::class;
	}

	protected function createMaintenance() {
		// Mock ::getMwInstallPath to return our mock path
		$obj = $this->getMockBuilder( $this->getMaintenanceClass() )
			->onlyMethods( [ 'getMwInstallPath' ] )
			->getMock();
		$obj->method( 'getMwInstallPath' )
			->willReturn( self::FIXTURE_DIRECTORY );
		return TestingAccessWrapper::newFromObject( $obj );
	}

	public function testExecute() {
		$this->maintenance->execute();
		$this->expectOutputRegex(
			"/Deprecated since 1.42:\n.*" . preg_quote( '+ FileWithDeprecatedCodeInSubDirectory::testMethodOne' )
			. "[\s\S]*" .
			"Deprecated since 1.43:\n.*" . preg_quote( '- FileWithDeprecatedCode::testMethodOne' ) . "[\s\S]*" .
			"Deprecated since 1.44:\n.*" . preg_quote( '+ FileWithDeprecatedCode::testMethodTwo' ) . "[\s\S]*" .
			"legend:\n.*-: soft-deprecated\n.*" . preg_quote( '+: hard-deprecated' ) . "/"
		);
	}
}
