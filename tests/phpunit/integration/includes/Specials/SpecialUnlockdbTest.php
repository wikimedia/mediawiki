<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialUnlockdb
 * @group Database
 */
class SpecialUnlockdbTest extends SpecialPageTestBase {

	public function testExecuteWhenUserLacksRight(): void {
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage();
	}

	public function testExecuteForView(): void {
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $this->getNewTempFile() );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(unlockdb-summary)', $html );

		$this->assertStringContainsString( '(unlockconfirm)', $html );
		$this->assertStringContainsString( '(unlockbtn)', $html );
	}

	public function testWhenLockFileDoesNotExist(): void {
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $this->getNewTempDirectory() . '/test.txt' );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$exceptionMessage = RequestContext::getMain()->msg( 'databasenotlocked' )
			->inLanguage( 'en' )
			->text();
		$this->expectExceptionMessage( $exceptionMessage );
		$this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);
	}

	public function testExecuteForSubmitWhenNoConfirm(): void {
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $this->getNewTempFile() );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [ 'wpReason' => 'test' ], true ),
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(locknoconfirm)', $html );
	}

	public function testExecuteForSubmitWhenLockFileCannotBeDeleted(): void {
		ConvertibleTimestamp::setFakeTime( '20260504030201' );

		$lockFile = $this->getNewTempFile();
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $lockFile );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$this->assertTrue( file_exists( $lockFile ) );

		$performer = $this->getTestSysop()->getUser();
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [ 'wpReason' => 'Test', 'wpConfirm' => '1' ], true ),
			null,
			$performer
		);

		$this->assertStringContainsString( '(unlockdbsuccesstext)', $html );

		$this->assertFalse( file_exists( $lockFile ) );
	}

	public function testExecuteForSubmitWhenConfirmSpecified(): void {
		ConvertibleTimestamp::setFakeTime( '20260504030201' );

		$lockFile = $this->getNewTempFile();
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $lockFile );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$this->assertTrue( file_exists( $lockFile ) );

		$performer = $this->getTestSysop()->getUser();
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [ 'wpReason' => 'Test', 'wpConfirm' => '1' ], true ),
			null,
			$performer
		);

		$this->assertStringContainsString( '(unlockdbsuccesstext)', $html );

		$this->assertFalse( file_exists( $lockFile ) );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Unlockdb' );
	}
}
