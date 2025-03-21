<?php
/**
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
 * @file
 */

namespace MediaWiki\Tests\Integration\Notification;

use MediaWiki\MediaWikiServices;
use MediaWiki\Notification\MiddlewareException;
use MediaWiki\Notification\Notification;
use MediaWiki\Notification\NotificationMiddlewareInterface;
use MediaWiki\Notification\RecipientSet;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Wikimedia\ScopedCallback;

/**
 * @group Mail
 * @covers \MediaWiki\Notification\MiddlewareChain
 */
class MiddlewareChainTest extends MediaWikiIntegrationTestCase {

	/**
	 * Test case when Middleware calls NotificationService::notify() to inject new notification
	 * This can cause endless loops where Middleware triggers a notification, that triggers the
	 * middleware again.
	 *
	 * @return void
	 */
	public function testMiddlewareCannotTriggerNotificationService() {
		$this->expectException( MiddlewareException::class );

		$scope = ExtensionRegistry::getInstance()->setAttributeForTest(
			'NotificationMiddleware', [
				[
					"factory" => static function ()  {
						return new class implements NotificationMiddlewareInterface {
							public function handle( $batch, callable $next ): void {
								MediaWikiServices::getInstance()
									->getNotificationService()
									->notify(
										new Notification( "bad" ), new RecipientSet( [] )
									);
								$next();
							}
						};
					},
				],
			]
		);
		$sut = MediaWikiServices::getInstance()->getNotificationService();
		$user = $this->createMock( UserIdentity::class );
		$sut->notify(
			new Notification( "good" ), new RecipientSet( [ $user ] )
		);
		ScopedCallback::consume( $scope );
	}
}
