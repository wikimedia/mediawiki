<?php

namespace MediaWiki\Tests\Maintenance;

use CommandLineInstaller;

/**
 * @covers \CommandLineInstaller
 * @author Dreamy Jazz
 */
class CommandLineInstallerTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return CommandLineInstaller::class;
	}

	public function testExecuteWhenHelpSpecified() {
		$this->maintenance->setOption( 'help', 1 );
		// ::maybeShowHelp uses ->mName which is null unless we call this.
		$this->maintenance->setName( 'install.php' );
		$this->expectCallToFatalError();
		$this->expectOutputString( $this->maintenance->getParameters()->getHelp() . "\n" );
		$this->maintenance->execute();
	}

	public function testExecuteWhenFirstArgumentProvidedButNotSecond() {
		$this->maintenance->setArg( 'name', 'mediawiki' );
		// ::maybeShowHelp uses ->mName which is null unless we call this.
		$this->maintenance->setName( 'install.php' );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Argument \<admin\> is required/' );
		$this->maintenance->execute();
	}
}
