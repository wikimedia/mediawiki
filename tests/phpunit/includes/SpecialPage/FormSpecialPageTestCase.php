<?php

namespace MediaWiki\Tests\SpecialPage;

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Rdbms\ReadOnlyMode;
use Wikimedia\TestingAccessWrapper;

/**
 * Factory for handling the special page list and generating SpecialPage objects.
 *
 * @license GPL-2.0-or-later
 * @group SpecialPage
 */
abstract class FormSpecialPageTestCase extends SpecialPageTestBase {
	/**
	 * @return FormSpecialPage
	 */
	abstract protected function newSpecialPage();

	/**
	 * @covers \MediaWiki\SpecialPage\FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsSitewideBlock() {
		$blockErrorFormatter = $this->createMock( BlockErrorFormatter::class );
		$blockErrorFormatter->method( 'getMessage' )
			->willReturn( $this->getMockMessage( 'test' ) );
		$this->setService( 'BlockErrorFormatter', $blockErrorFormatter );

		// Make the permission tests pass so we can check that the user is denied access because of their block.
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->method( 'userHasRight' )->willReturn( true );
		$this->setService( 'PermissionManager', $permissionManager );

		/** @var FormSpecialPage $special */
		$special = TestingAccessWrapper::newFromObject( $this->newSpecialPage() );

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock', 'getWikiId' ] )
			->getMock();
		$user->method( 'getWikiId' )->willReturn( WikiAwareEntity::LOCAL );
		$block = $this->createMock( DatabaseBlock::class );
		$block->method( 'isSitewide' )->willReturn( true );
		$block->method( 'getTargetUserIdentity' )->willReturn( $user );
		$block->method( 'getExpiry' )->willReturn( MWTimestamp::convert( TS_MW, 10 ) );
		$user->method( 'getBlock' )->willReturn( $block );

		$this->expectException( UserBlockedError::class );
		$special->checkExecutePermissions( $user );
	}

	/**
	 * @covers \MediaWiki\SpecialPage\FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsPartialBlock() {
		$blockErrorFormatter = $this->createMock( BlockErrorFormatter::class );
		$blockErrorFormatter->method( 'getMessage' )
			->willReturn( $this->getMockMessage( 'test' ) );
		$this->setService( 'BlockErrorFormatter', $blockErrorFormatter );

		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->method( 'isReadOnly' )->willReturn( false );
		$this->setService( 'ReadOnlyMode', $readOnlyMode );

		// Make the permission tests pass so we can check that the user is denied access because of their block.
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->method( 'userHasRight' )->willReturn( true );
		$this->setService( 'PermissionManager', $permissionManager );

		/** @var FormSpecialPage $special */
		$special = TestingAccessWrapper::newFromObject( $this->newSpecialPage() );

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock', 'getWikiId' ] )
			->getMock();
		$user->method( 'getWikiId' )->willReturn( WikiAwareEntity::LOCAL );
		$block = $this->createMock( DatabaseBlock::class );
		$block->method( 'isSitewide' )->willReturn( false );
		$block->method( 'getTargetUserIdentity' )->willReturn( $user );
		$block->method( 'getExpiry' )->willReturn( MWTimestamp::convert( TS_MW, 10 ) );
		$user->method( 'getBlock' )->willReturn( $block );

		$this->assertNull( $special->checkExecutePermissions( $user ) );
	}
}
