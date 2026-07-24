<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialLockdb
 * @covers \MediaWiki\Specials\Pager\UsersPager
 * @group Database
 */
class SpecialLockdbTest extends SpecialPageTestBase {

	public function testExecuteWhenUserLacksRight(): void {
		$this->expectException( PermissionsError::class );
		$this->executeSpecialPage();
	}

	public function testExecuteForView(): void {
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(lockdb-summary)', $html );

		$this->assertStringContainsString( '(enterlockreason)', $html );
		$this->assertStringContainsString( '(lockconfirm)', $html );
		$this->assertStringContainsString( '(lockbtn)', $html );
	}

	public function testWhenLockFileNotWritable(): void {
		$this->overrideConfigValue(
			MainConfigNames::ReadOnlyFile,
			$this->getNewTempDirectory() . '/test/test/test.txt'
		);
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$exceptionMessage = RequestContext::getMain()->msg( 'lockfilenotwritable' )
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

	public function testWhenLockFileExists(): void {
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $this->getNewTempFile() );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$exceptionMessage = RequestContext::getMain()->msg( 'databaselocked' )
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
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [ 'wpReason' => 'test' ], true ),
			null,
			$this->getTestSysop()->getAuthority()
		);

		$this->assertStringContainsString( '(locknoconfirm)', $html );
	}

	public function testExecuteForSubmitWhenConfirmSpecified(): void {
		ConvertibleTimestamp::setFakeTime( '20260504030201' );

		$lockFile = $this->getNewTempDirectory() . '/test.txt';
		$this->overrideConfigValue( MainConfigNames::ReadOnlyFile, $lockFile );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );
		$this->setGroupPermissions( 'sysop', 'siteadmin', true );

		$performer = $this->getTestSysop()->getUser();
		[ $html ] = $this->executeSpecialPage(
			'',
			new FauxRequest( [ 'wpReason' => 'Test', 'wpConfirm' => '1' ], true ),
			null,
			$performer
		);

		$this->assertStringContainsString( '(lockdbsuccesstext)', $html );

		$lockFileContents = file_get_contents( $lockFile );
		$this->assertSame(
			"Test\n<p>(lockedbyandtime: {$performer->getName()}, 4 (may_long) 2026, 03:02)</p>\n",
			$lockFileContents
		);
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'Lockdb' );
	}
}
