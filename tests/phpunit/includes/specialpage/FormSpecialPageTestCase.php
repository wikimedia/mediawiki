<?php

use MediaWiki\Block\DatabaseBlock;

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
	 * @covers FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsSitewideBlock() {
		$special = $this->newSpecialPage();
		$checkExecutePermissions = $this->getMethod( $special, 'checkExecutePermissions' );

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( new DatabaseBlock( [
				'address' => '127.0.8.1',
				'by' => $user->getId(),
				'reason' => 'sitewide block',
				'timestamp' => time(),
				'sitewide' => true,
				'expiry' => 10,
			] ) );

		$this->expectException( UserBlockedError::class );
		$checkExecutePermissions( $user );
	}

	/**
	 * @covers FormSpecialPage::checkExecutePermissions
	 */
	public function testCheckExecutePermissionsPartialBlock() {
		$special = $this->newSpecialPage();
		$checkExecutePermissions = $this->getMethod( $special, 'checkExecutePermissions' );

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getBlock' ] )
			->getMock();
		$user->method( 'getBlock' )
			->willReturn( new DatabaseBlock( [
				'address' => '127.0.8.1',
				'by' => $user->getId(),
				'reason' => 'partial block',
				'timestamp' => time(),
				'sitewide' => false,
				'expiry' => 10,
			] ) );

		$this->assertNull( $checkExecutePermissions( $user ) );
	}

	/**
	 * Get a protected/private method.
	 *
	 * @param object $obj
	 * @param string $name
	 * @return callable
	 */
	protected function getMethod( $obj, $name ) {
		$method = new ReflectionMethod( $obj, $name );
		$method->setAccessible( true );
		return $method->getClosure( $obj );
	}
}
