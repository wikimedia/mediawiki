<?php

use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;

/**
 * Factory for handling the special page list and generating SpecialPage objects.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @group SpecialPage
 */
abstract class FormSpecialPageTestCase extends SpecialPageTestBase {
	/**
	 * @return FormSpecialPage
	 */
	abstract protected function newSpecialPage();

	/**
	 * @covers FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsSitewideBlock() {
		$special = $this->newSpecialPage();
		$checkExecutePermissions = $this->getMethod( $special, 'checkExecutePermissions' );

		$blockErrorFormatter = $this->createMock( BlockErrorFormatter::class );
		$blockErrorFormatter->method( 'getMessage' )
			->willReturn( $this->getMockMessage( 'test' ) );
		$this->setService( 'BlockErrorFormatter', $blockErrorFormatter );

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
		$checkExecutePermissions( $user );
	}

	/**
	 * @covers FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsPartialBlock() {
		$special = $this->newSpecialPage();
		$checkExecutePermissions = $this->getMethod( $special, 'checkExecutePermissions' );

		$blockErrorFormatter = $this->createMock( BlockErrorFormatter::class );
		$blockErrorFormatter->method( 'getMessage' )
			->willReturn( $this->getMockMessage( 'test' ) );
		$this->setService( 'BlockErrorFormatter', $blockErrorFormatter );

		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		$readOnlyMode->method( 'isReadOnly' )->willReturn( false );
		$this->setService( 'ReadOnlyMode', $readOnlyMode );

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getBlock', 'getWikiId' ] )
			->getMock();
		$user->method( 'getWikiId' )->willReturn( WikiAwareEntity::LOCAL );
		$block = $this->createMock( DatabaseBlock::class );
		$block->method( 'isSitewide' )->willReturn( false );
		$block->method( 'getTargetUserIdentity' )->willReturn( $user );
		$block->method( 'getExpiry' )->willReturn( MWTimestamp::convert( TS_MW, 10 ) );
		$user->method( 'getBlock' )->willReturn( $block );

		$this->assertNull( $checkExecutePermissions( $user ) );
	}

	/**
	 * Get a protected/private method.
	 *
	 * @param FormSpecialPage $obj
	 * @param string $name
	 * @return callable
	 */
	protected function getMethod( FormSpecialPage $obj, $name ) {
		$method = new ReflectionMethod( $obj, $name );
		$method->setAccessible( true );
		return $method->getClosure( $obj );
	}
}
